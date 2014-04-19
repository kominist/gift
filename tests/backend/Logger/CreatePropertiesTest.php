<?php
use Controller\Audit;
use Model\UserModel;
use Cartalyst\Sentry\Facades\Native\Sentry as Sentry;
use Silex\WebTestCase;
use Monolog\Logger;
use Monolog\Handler\StreamHandler as StreamHandler;

class CreatePropertiesTest extends WebTestCase
{
  private $client;
  private $crawler;
  private $testLogFile;

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
    $this->logger = new Audit($this->app);
    $this->logger->setMode("write");
    $this->user = new Controller\User($this->app);
    $this->testLogFile = __DIR__ . "/../../../logs/test.log";
    $this->logs = new Logger("test");
    $this->logs->pushHandler(new StreamHandler($this->testLogFile, "logs"));
    $this->logger->setHandler(
      $this->logs,
      [
        "type" => "file",
        "filepath" => $this->testLogFile
      ]
    );
  }

  // Describe Mode
  //  It should be initialized with mode "none"
  public function testModeInitialized()
  {
    $this->logger->setMode("none");
    $mode = $this->logger->mode;
    $this->logger->setMode("write");
    $this->assertEquals("none", $mode);
  }

  //  Given the mode is supported
  //    It should switch type on demand
  public function testModeSwitch()
  {
    $mode = $this->logger->setMode("write");
    $this->assertEquals("write", $mode);
  }

  //  Given the mode is not supported
  //    It should return false
  public function testUnsupportedMode()
  {
    $mode = $this->logger->setMode("unsupportedMode");
    $this->assertFalse($mode);
  }

  // It should not change the mode
  public function testUnchangedMode()
  {
    $beforeMode = $this->logger->mode;
    $mode = $this->logger->setMode("unsupportedMode");
    $this->assertEquals($this->logger->mode, $beforeMode);
  }

  // Describe Handler
  //  Given the handler is a file
  //  Given a seekable file
  //    It should return true
  public function testHandlerSeekableFile()
  {
    $check = $this->logger->setHandler(
      $this->app["logs"],
      [
        "type" => "file",
        "filepath" => $this->testLogFile
      ]
    );
    $this->assertTrue($check);
  }

  //  Given the handler is a file
  //  Given a unseekable file
  //  It should create this file, in order to remove it
  public function testRemoveUnseekableFile()
  {
    if(!file_exists("unseekable.file"))
    {
      $fp = fopen("unseekable.file", "w");
      fwrite($fp, "lorem ipsum");
      fclose($fp);
    }
    $check = $this->logger->setHandler(
      $this->app["logs"],
      [
        "type" => "file",
        "filepath" => "unseekable.file"
      ]
    );
    $this->assertTrue($check);

    unlink("unseekable.file");
    $check = $this->logger->setHandler(
      $this->app["logs"],
      [
        "type" => "file",
        "filepath" => "unseekable.file"
      ]
    );
    $this->assertFalse($check);
  }

  //    It should return false
  public function testHandlerUnseekableFile()
  {
    // assure that the unseekable file does not exist
    if(file_exists("unseekable.file"))
    {
      if(!unlink("unseekable.file"))
      {
        echo "
          unseekable.file can't be removed, the assertion will fail.
          Please remove manually unseekable.file
          ";
      }
    }
    $check = $this->logger->setHandler(
      $this->app["logs"],
      [
        "type" => "file",
        "filepath" => "unseekable.file"
      ]
    );
    $this->assertFalse($check);
  }

  // Describe Request
  //  It should return a request
  public function testRequest()
  {
    $this->client = $this->createClient();
    $this->crawler = $this->client->request("GET", "/");
    $response = $this->client->getResponse();
    $statusCode = $response->getStatusCode();
    $this->assertEquals($statusCode, 200);
  }

  // Describe Session
  //    It should return the username
  public function testUser()
  {
    // get session
    $this->app["session"]->username = "giver";
    $user = UserModel::where("email", "=", "giver@user.com")->first();
    $session = $this->app["session"];
    $this->assertEquals($session->username, $user->first_name);
  }

  //    It should return a valid ip
  public function testIp()
  {
    $ip = !empty($_SERVER['HTTP_CLIENT_IP'])? $_SERVER['HTTP_CLIENT_IP'] :
      !empty($_SERVER['HTTP_X_FORWARDED_FOR'])? $_SERVER['HTTP_X_FORWARDED_FOR'] :
      !empty($_SERVER['REMOTE_ADDR'])? $_SERVER['REMOTE_ADDR'] : "127.0.0.1" ;
    $this->assertRegExp('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $ip);
  }

  //    Given username "giver" and ip "127.0.0.1"
  //      It should save the username as "giver"
  public function testSaveMember()
  {
    $this->app["session"]->username = "giver";
    $this->logger->setUser($this->app["session"]->username);
    $this->assertEquals($this->logger->username, "giver");
  }

  //      It should save the ip as "127.0.0.1"
  public function testSaveValidIp()
  {
    $ip = "127.0.0.1";
    $this->logger->setIp($ip);
    $this->assertEquals($this->logger->ip, "127.0.0.1");
  }

  //    Given username empty and ip "2.2"
  //      It should save the username as "visitor"
  public function testSaveVisitor()
  {
    $username = null;
    $this->logger->setUser($username);
    $this->assertEquals($this->logger->username, "visitor");
  }

  //      It should save the ip as 0
  public function testSaveInvalidIp()
  {
    $ip = "2.2";
    $this->logger->setIp($ip);
    $this->assertEquals($this->logger->ip, 0);
  }

  //  Describe Actions
  //    It should stack actions
  public function testStackActions()
  {
    $entity = "user";
    $name = "giver";
    $action = "login";
    $this->logger->setIp("127.0.0.1");
    $this->logger->push($entity, $name, $action);
    $this->assertEquals(
      [
        "user",
        [
          "login" => "giver",
          "ip"    => "127.0.0.1"
        ]
      ]
      , $this->logger->pusher
    );
  }

  //    It should log that the user has registered
  public function testHasRegistered()
  {
    $this->logger->setMode("write");
    $this->logger->setIp("127.0.0.1");
    $this->logger->setUser("newUser");
    $this->logger->hasRegistered();
    $this->assertEquals(
      [
        "user",
        [
          "registered" => "newUser",
          "ip"    => "127.0.0.1"
        ]
      ]
      , $this->logger->pusher
    );
    $this->assertEquals(200, $this->logger->type);
  }

  //    It should log that the user has login
  //    Given the username is found in the Database
  //        It should put "allowed" at end of the line log
  public function testHasLoginWithAllowed()
  {
    $this->logger->setUser("giver");
    $this->logger->setIp("127.0.0.1");
    $this->logger->hasLogin("giver@user.com");
    $this->assertEquals(
      [
        "user",
        [
          "login" => "giver",
          "ip"    => "127.0.0.1"
        ]
      ]
      , $this->logger->pusher
    );
    $this->assertEquals(200, $this->logger->type);
  }

  //    Given the username is not found in the Database
  //        It should put "forbidden" at end of the line log
  public function testHasLoginWithForbidden()
  {
    $this->logger->setUser("newUser");
    $this->logger->setIp("127.0.0.1");
    $this->logger->hasLogin("newUser");
    $this->assertEquals(
      [
        "user",
        [
          "login" => "newUser",
          "ip"    => "127.0.0.1"
        ]
      ]
      , $this->logger->pusher
    );
    $this->assertEquals(500, $this->logger->type);
  }

  //    It should log that the user has logout
  //    Given the user is logged in
  //      it should put "allowed" at end of the line
  public function testHasLogoutWithAllowed()
  {
    $this->logger->setUser("giver");
    $this->logger->setIp("127.0.0.1");
    $this->app["session"]->username = "giver";
    $this->app["session"]->email = "giver@user.com";
    $this->logger->hasLogout($this->app["session"]);
    $this->assertEquals(
      [
        "user",
        [
          "logout" => "giver",
          "ip"    => "127.0.0.1"
        ]
      ]
      , $this->logger->pusher
    );
    $this->assertEquals(200, $this->logger->type);
  }

  //  Given the current user is the giver
  //    It should log that the current user gave
  //    a gift to another user
  public function testGiveGiftToSeekableUser()
  {
    $this->logger->setUser("giver");
    $getterMail = "getter@user.com";
    $this->logger->setIp("127.0.0.1");
    $this->logger->gaveGift($this->logger->username, $getterMail);
    $this->assertEquals(
      [
        "gift",
        [
          "initialized" => "from giver to getter",
          "ip"    => "127.0.0.1"
        ]
      ]
      , $this->logger->pusher
    );
    $this->assertEquals(200, $this->logger->type);
  }

  //    Given that this another user is not in the Database
  //      It should put "forbidden" at end at the line
  public function testGiveGiftToUnseekableUser()
  {
    $this->logger->setUser("giver");
    $this->logger->setIp("127.0.0.1");
    $getterMail = "notInData@base.troll";
    $this->logger->gaveGift($this->logger->username, $getterMail);
    $this->assertEquals(
      [
        "gift",
        [
          "initialized" => "from giver to visitor",
          "ip"    => "127.0.0.1"
        ]
      ]
      , $this->logger->pusher
    );
    $this->assertEquals(500, $this->logger->type);
  }

  //    It should log that the current user canceled the gift
  public function testCanceledGift()
  {
    $this->logger->setUser("giver");
    $this->logger->setIp("127.0.0.1");
    $this->logger->canceledGift($this->logger->username);
    $this->assertEquals(
      [
        "gift",
        [
          "canceled" => "giver",
          "ip"    => "127.0.0.1"
        ]
      ]
      , $this->logger->pusher
    );
    $this->assertEquals(200, $this->logger->type);
  }

  //  Given the current user is the getter
  //    It should log that the current user accepted
  //    the gift
  public function testAcceptedGift()
  {
    $this->logger->setUser("getter");
    $this->logger->setIp("127.0.0.1");
    $this->logger->acceptedGift($this->logger->username);
    $this->assertEquals(
      [
        "gift",
        [
          "accepted" => "getter",
          "ip"    => "127.0.0.1"
        ]
      ]
      , $this->logger->pusher
    );
    $this->assertEquals(200, $this->logger->type);
  }

  //    It should log that the current user refused
  //    the gift
  public function testRefusedGift()
  {
    $this->logger->setUser("getter");
    $this->logger->setIp("127.0.0.1");
    $this->logger->refusedGift($this->logger->username);
    $this->assertEquals(
      [
        "gift",
        [
          "refused" => "getter",
          "ip"    => "127.0.0.1"
        ]
      ]
      , $this->logger->pusher
    );
    $this->assertEquals(200, $this->logger->type);
  }
}
