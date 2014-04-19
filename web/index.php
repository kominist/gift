<?php
require_once __DIR__."/../vendor/autoload.php";

$app = new Silex\Application();
$app->register(new Silex\Provider\ServiceControllerServiceProvider());
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
use Symfony\Component\HttpFoundation\Request as Request;

// Template engine
$app->register(new Silex\Provider\TwigServiceProvider(), array(
  "twig.path" => __DIR__."/../app/views",
));

// Database
LaravelBook\Ardent\Ardent::configureAsExternal([
  "default" => "prod",
  "connections" => [
    "prod" => [
      "driver" => "sqlite",
      "database" => __DIR__ . "/../db/db.sqlite",
      "prefix" => ""
    ],
    "test" => [
      "driver" => "sqlite",
      "database" => ":memory:",
      "prefix" => ""
    ]
  ]
]);

//Session
$app->register(new Silex\Provider\SessionServiceProvider);

// Auth
use Cartalyst\Sentry\Facades\Native\Sentry as Sentry;
Sentry::setupDatabaseResolver(new PDO("sqlite:" . __DIR__ . "/../db/db.sqlite"), null, null);

// Logs
use Monolog\Logger;
use Monolog\Handler\StreamHandler as StreamHandler;
$app["logs"] = new Logger("test");
$app["logs"]->pushHandler(new StreamHandler(__DIR__ . "/../logs/mono.log", "logs"));

// Set environment
if (isset($app_env) && in_array($app_env, array("prod", "dev", "test")))
{
  $app["env"] = $app_env;
}
else {
  $app["env"] = "prod";
}

// Debug mode
$app["debug"] = true;
if($app["debug"])
{
  $app->register(new Whoops\Provider\Silex\WhoopsServiceProvider);
}

// Before
$app->before(function(Request $request) use ($app){
  if($request->cookies->has("user"))
  {
    $app["login"] = true;
    $app["session"]->set(
      "user",
      ["username" => $request->cookies->get("username")]
    );

  } else {
    $app["login"] = false;
  }
});

// Router
$app->get("/", function(Request $request) use ($app){
  $home =  new Controller\Homepage($app);
  return $home->indexAction();
});

$app->get("/user", function(Request $request) use ($app) {
  $user = new Controller\User($app);
  return $user->getInfo();
});

$app->post("/user", function(Request $request) use ($app) {
  $data = $request->getContent();
  if(!empty($data))
  {
    $user = new Controller\User($app);
    return $user->auth(json_decode($data));
  }
  return false;
});
$app->get("/gift", function(Request $request) use ($app) {
  $gift = new Controller\Gift($app);
  $current = Sentry::check()?Sentry::getUser()->id:0;
  return $gift->findAll($current);
});
$app->post("/gift", function(Request $request) use ($app) {
  $data = $request->getContent();
  if(!empty($data))
  {
    $gift = new Controller\Gift($app);
    return $app->json($gift->find(json_decode($data)));
  }
  return false;
});
$app->delete("/gift/{id}", function ($id) use($app){
  $gift = new Controller\Gift($app);
  $gift->delete($id);
  return $app->json($id);
});
$app->put("/gift/{id}", function (Request $request) use($app){
  $data = json_decode($request->getContent());
  $id = $request->get("id");
  if(!empty($data) && isset($id))
  {
    $gift = new Controller\Gift($app);
    if(isset($data->status) && ($data->status === "accepted" || $data->status === "refused"))
    {
      $gift->setStatus($id, $data->status);
    }
  }
  return $app->json($id);
});
$app->get("/search", function(Request $request) use($app) {
  return $app->json(false);
});
$app->post("/search", function(Request $request) use ($app) {
  $data = $request->getContent();
  if(!empty($data))
  {
    $user = new Controller\User($app);
    return $app->json($user->getUserList(json_decode($data)));
  }
  return false;
});
$app->post("/searchuser", function(Request $request) use ($app) {
  $data = $request->getContent();
  if(!empty($data))
  {
    $data = json_decode($data);
    $user = new Controller\User($app);
    $getter = $user->getIdFromName($data->username)->id;
    $current = $user->getCurrent()->id;
    if($current === false) {
      return json_encode(false);
    }
    $gift = new Controller\Gift($app);
    return json_encode($gift->send((int)($current), (int)($getter)));
  }
  return false;
});
$app->post("/searchtrade", function(Request $request) use ($app) {
  $data = $request->getContent();
  if(!empty($data))
  {
    $data = json_decode($data);
    $user = new Controller\User($app);
    $getter = $user->getIdFromName($data->filterOn)?:0;
    $gift = new Controller\Gift($app);
    if($getter !== 0)
    {
      return $gift->getUser((int)($getter->id));
    }
  }
  return false;
});

// Check environment
if($app["env"] === "test")
{
  return $app;
}
else {
  $app->run();
}
