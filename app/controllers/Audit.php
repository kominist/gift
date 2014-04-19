<?php
namespace Controller;
use Model\UserModel;

class Audit{
  private $app;

  public function __construct($app)
  {
    $this->app = $app;
    $this->mode = "none";
    $this->type = "";
  }

  # Mode that the instance is
  public function setMode($mode)
  {
    $supported = ["none", "write"];
    if(in_array($mode, $supported, true))
    {
      return $this->mode = $mode;
    }
    return false;
  }

  # Set the handler and its options
  # Types currently supported : file
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

  ###
  #   PROPERTIES
  ###
  # status of the http header
  public function setStatusCode($statusCode)
  {
    $this->statusCode = $statusCode;
  }

  # set visitor or (username if member)
  public function setUser($username)
  {
    $this->username = !empty($username)? $username: "visitor";
  }

  # set ip
  public function setIp($ip)
  {
    $this->ip = preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $ip)? $ip : 0;
  }

  ###
  #   ACTIONS
  ###
  # Stack the actions
  # pattern :
  # [%TIMESTAMP%] logname.LOGTYPE: logbag[actions] []
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

  ### Set type of action
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

  ### Users
  public function hasRegistered()
  {
    $this->push("user", $this->username, "registered");
    $this->isA(200);
  }

  public function hasLogin($email)
  {
    $user = UserModel::where("email", "=", $email)->count();
    $level = (int)($user) === 1?200:500;
    $this->push("user", $this->username, "login");
    $this->isA($level);
  }

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

  ### User action over gift
  public function gaveGift($from, $to)
  {
    $user = UserModel::where("email", "=", $to);
    $level = (int)($user->count()) === 1?200:500;
    $user = UserModel::where("email", "=", $to);
    $to = isset($user->first()->first_name)?$user->first()->first_name:"visitor";
    $this->push("gift", "from {$from} to {$to}", "initialized");
    $this->isA($level);
  }

  public function canceledGift($from)
  {
    $this->push("gift", $from, "canceled");
    $this->isA(200);
  }

  public function acceptedGift($getter)
  {
    $this->push("gift", $getter, "accepted");
    $this->isA(200);
  }

  public function refusedGift($getter)
  {
    $this->push("gift", $getter, "refused");
    $this->isA(200);
  }

}
