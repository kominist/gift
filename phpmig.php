<?php
use \Illuminate\Database\Capsule\Manager as Capsule;
use \Phpmig\Adapter;

$container = new \Phpmig\Pimple\Pimple();

$container['phpmig.migrations_path'] = __DIR__ . DIRECTORY_SEPARATOR . 'migrations';

$container['config'] = array(
  "driver"    => "sqlite",
  "database"  => __DIR__ . "/db/db.sqlite",
  "prefix"    => ""
);
$container["db"] = $container->share(function ($container) {
  return new PDO("sqlite:" . $container["config"]["database"]);
});
$container["schema"] = $container->share(function ($container) {
  $capsule = new Capsule;
  $capsule->addConnection($container["config"]);
  $capsule->setAsGlobal();
  return Capsule::schema();
});
$container["phpmig.adapter"] = $container->share( function() use ($container) {
  return new Adapter\PDO\Sql($container["db"], "migrations");
});

return $container;
