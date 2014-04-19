<?php
use Controller\User;
use Model\UserModel;
use Silex\WebTestCase;

class UserActionTest extends WebTestCase
{

  public function createApplication()
  {
    require __DIR__ . "/../../../web/index.php";
    $app["debug"] = true;
    $app["exception_handler"]->disable();
    $app["env"] = "test";
    return $app;
  }

  public function setUp()
  {
    $this->app = $this->createApplication();
    $this->user = new User($this->app);
    $this->model = new UserModel();
  }

  // Describe Create
  //  Given email "lorem@ipsum.com"
  //  Given password "lorem"
  //  Given username "ipsum"
  //  it should register an user
  public function testRegisterUser()
  {
    $this->model->email = "lorem@ipsum.com";
    $this->model->password = "lorem";
    $this->model->first_name = "ipsum";
    $this->model->last_name = false;
    $this->model->activated = true;
    $this->model->permissions = "{user:create}";
    $id = $this->model->save();
    $this->assertTrue($id);
  }

  //  it should login an user
  public function testLoginUser()
  {
    $email = "lorem@ipsum.com";
    $password = "lorem";
    $login = $this->model
      ->where("email", "=", $email)
      ->where("password", "=", $password)
      ->first();
    $this->assertEquals($login->first_name, "ipsum");
  }
}

