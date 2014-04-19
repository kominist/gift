<?php
namespace Controller;
use Model\UserModel as UserModel;
use Cartalyst\Sentry\Facades\Native\Sentry as Sentry;
class User
{
  private $app;

  public function __construct($app)
  {
    $this->app = $app;
  }

  public function getInfo()
  {
    if(Sentry::check())
    {
      $user = Sentry::getUser();
      $data = $this->app->json([
        "username"  => $user->first_name,
        "password"  => "password",
        "email"     => $user->email
      ]);
      return $data;
    } else {
      return false;
    }
  }

  public function auth($data)
  {
    // login
    if($data->status === "login")
    {
      $credentials = [
        "email" => $data->email,
        "password" => $data->password
      ];
      Sentry::authenticateAndRemember($credentials);
      return 1;
    }
    // logout
    else if(Sentry::check() && $data->status === "inactive")
    {
      Sentry::logout();
    }
    // register
    else if($data->status === "register")
    {
      $credentials = [
        "email" => $data->email,
        "password" => $data->password,
        "first_name" => $data->username,
        "last_name" => false,
        "activated" => true,
        "permissions" => [
          "user.create" => 0,
          "user.view" => 1
        ]
      ];

      if($user = Sentry::createUser($credentials))
      {
        $users = Sentry::findGroupByName("Users");
        $user->addGroup($users);
        Sentry::loginAndRemember($user);
        return $this->app->json($credentials);
      }
    }
    return 0;
  }

  public function getUserList($data)
  {
    $return = [];
    foreach(UserModel::where("first_name","like", "%{$data->value}%")->get() as $q)
    {
      $return[]["username"] = $q->first_name;
    }
    if(!empty($return))
    {
      return $return;
    }
    return false;
  }

  public function getIdFromName($name)
  {
    return UserModel::where("first_name", "=", $name)->first();
    return false;
  }

  public function getCurrent()
  {

    if(Sentry::check())
    {
      return $user = Sentry::getUser();
    }
    return false;
  }
}
