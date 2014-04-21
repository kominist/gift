<?php
namespace Controller;
use Model\UserModel;

/**
 *  Audit
 *  Define what to log
 *
 *  @package Controller
 *  @subpackage Audit
 *  @category classes
 *  @licence GPL3
 */
class Audit{

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
    $this->mode = "none";
    $this->type = "";
  }

  /**
   * Set the current mode
   *
   * @param string $app
   * @return string if mode is accepted
   * @return boolean if mode is not accepted
   */
  public function setMode($mode)
  {
    $supported = ["none", "write"];
    if(in_array($mode, $supported, true))
    {
      return $this->mode = $mode;
    }
    return false;
  }

  /**
   * Set the current handler and its options
   *
   * @param mixed $handler
   * @param array $options
   * @return boolean handler have successfully been updated
   */
  public function setHandler($handler, $options)
  {
    $check = false;
    switch ($options["type"])
    {
      case "file" :
        $check = \file_exists($options["filepath"]);
        $this->handler = $handler;
        break;
    }
    return $check;
  }

  /**
   *  Log the http header status code
   *
   *  @param integer $statusCode
   *  @return void
   */
  public function setStatusCode($statusCode)
  {
    $this->statusCode = $statusCode;
  }

  /**
   *  Log the current user if exists
   *  else log it as "visitor"
   *
   *  @param string $username
   *  @return void
   */
  public function setUser($username)
  {
    $this->username = !empty($username)? $username: "visitor";
  }

  /**
   *  Log the ip if valid pattern
   *  else log it as 0
   *
   *  @param string $ip
   *  @return void
   */
  public function setIp($ip)
  {
    $this->ip = preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $ip)? $ip : 0;
  }

  /**
   *  Format the log
   *
   *  @param string entity
   *  @param string name
   *  @param string action
   *  @return void
   */
  public function push($entity, $name, $action)
  {
    $this->pusher = [
      $entity,
      [
        "{$action}" => "{$name}",
        "ip"        => $this->ip
      ]
    ];
  }

  /**
   *  Set the type of the log
   *
   *  @param integer $type
   *  @return boolean type have been successfully written
   */
  public function isA($type)
  {
    if(is_integer($type) && $this->mode === "write")
    {
      switch ($type)
      {
        case 100 : case "DEBUG" :
          $this->handler->addDebug($this->pusher[0], $this->pusher[1]);
          break;
        case 200 : case "INFO" :
          $this->handler->addInfo($this->pusher[0], $this->pusher[1]);
          break;
        case 250 : case "NOTICE" :
          $this->handler->addNotice($this->pusher[0], $this->pusher[1]);
          break;
        case 300 : case "WARNING" :
          $this->handler->addWarning($this->pusher[0], $this->pusher[1]);
          break;
        case 400 : case "ERROR" :
          $this->handler->addError($this->pusher[0], $this->pusher[1]);
          break;
        case 500: case "ALERT" :
          $this->handler->addAlert($this->pusher[0], $this->pusher[1]);
          break;
        case 600 : case "EMERGENCY" :
          $this->handler->addEmergency($this->pusher[0], $this->pusher[1]);
      }
      $this->type = $type;
      return true;
    }
    return false;
  }

  /**
   *  Log if user has registered
   *
   *  @return void
   */
  public function hasRegistered()
  {
    $this->push("user", $this->username, "registered");
    $this->isA(200);
  }

  /**
   *  Log if the user has logged in
   *
   *  @param string $email
   *  @return void
   */
  public function hasLogin($email)
  {
    $user = UserModel::where("email", "=", $email)->count();
    $level = (int)($user) === 1?200:500;
    $this->push("user", $this->username, "login");
    $this->isA($level);
  }

  /**
   *  Log if the user has logged out
   *
   *  @param mixed $session
   *  @return void
   */
  public function hasLogout($session)
  {
    # Are infos about current user normals?
    $user = UserModel::where("email", "=", $session->email)
      ->where("first_name", "=", $session->username)
      ->count();
    if((int)($user) === 1 && $session->username === $this->username)
    {
      $level = 200;
    } else {
      $level = 500;
    }
    $this->push("user", $this->username, "logout");
    $this->isA($level);
  }

  /**
   *  Log if the user had give a gift
   *
   *  @param string $from giver
   *  @param string $to getter
   *  @return void
   */
  public function gaveGift($from, $to)
  {
    $user = UserModel::where("email", "=", $to);
    $level = (int)($user->count()) === 1?200:500;
    $user = UserModel::where("email", "=", $to);
    $to = isset($user->first()->first_name)?$user->first()->first_name:"visitor";
    $this->push("gift", "from {$from} to {$to}", "initialized");
    $this->isA($level);
  }

  /**
   *  Log if the giver have canceled the gift
   *
   *  @param string $from giver
   *  @return void
   */
  public function canceledGift($from)
  {
    $this->push("gift", $from, "canceled");
    $this->isA(200);
  }

  /**
   *  Log if the getter have accepted the gift
   *
   *  @param string $getter
   *  @return void
   */
  public function acceptedGift($getter)
  {
    $this->push("gift", $getter, "accepted");
    $this->isA(200);
  }

  /**
   *  Log if the getter have refused the gift
   *
   *  @param string $getter
   *  @return void
   */
  public function refusedGift($getter)
  {
    $this->push("gift", $getter, "refused");
    $this->isA(200);
  }
}
