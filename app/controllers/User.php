<?php
namespace Controller;
use Model\UserModel as UserModel;
use Cartalyst\Sentry\Facades\Native\Sentry as Sentry;

/**
 *  User
 *  Manage user
 *
 *  @package Controller
 *  @subpackage User
 *  @category classes
 *  @licence GPL3
 */
class User
{

  /**
   *  App instance
   *  @var mixed $app
   */
  private $app;

  /**
   *  Constructor
   *
   *  @param mixed $app
   *  @return void
   */
  public function __construct($app)
  {
    $this->app = $app;
  }

  /**
   * Get info on current session' user
   *
   * @return array|false user informations
   */
  public function getInfo()
  {
    if(Sentry::check())
    {
      $user = Sentry::getUser();
      $data = $this->app->json([
        "username"  => $user->first_name,
        "password"  => "     ",
        "email"     => $user->email
      ]);
      return $data;
    } else {
      return false;
    }
  }

  /**
   * Authenticate, Disconnect or Login a user
   *
   * @param mixed $data user informations and action needed to do
   * @return boolean Determins action success or failure
   */
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

  /**
   *  Get list of users matching data for autocompletion
   *
   *  @param string $data
   *  @return mixed|false list of users matching or false if not
   */
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
  /**
   *  Get id of an user
   *
   *  @param string $name
   *  @return integer|false id of user
   */
  public function getIdFromName($name)
  {
    return UserModel::where("first_name", "=", $name)->first();
    return false;
  }

  /**
   *  Get session user
   *
   *  @return mixed|false user informations
   */
  public function getCurrent()
  {

    if(Sentry::check())
    {
      return $user = Sentry::getUser();
    }
    return false;
  }
}
