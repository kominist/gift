<?php
use Controller\Audit;
use Silex\WebTestCase;
use Monolog\Logger;
use Monolog\Handler\StreamHandler as StreamHandler;

class WriteTest extends WebTestCase
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

  //  Describe write
  //    It should save on the log file
  public function testWrite()
  {
    // clean log file
    $fp = fopen($this->testLogFile, "w");
    fclose($fp);
    // mock
    $this->logger->setMode("write");
    $this->logger->setIp("127.0.0.1");
    $this->logger->push("test", "test", "test");
    $this->logger->isA(200);
    // read log file
    $content = file_get_contents($this->testLogFile);
    // assert
    $this->assertRegExp(
      "/^\[.+\]\stest\.INFO\s?\:?\s?.+$/",
      $content);
  }

  //    It should save an action (here, login)
  //    on the log file
  public function testWriteAction()
  {
    // clean log file
    $fp = fopen($this->testLogFile, "w");
    fclose($fp);
    // mock
    $this->logger->setIp("127.0.0.1");
    $this->logger->setUser("giver");
    $this->app["session"]->username = "giver";
    $this->logger->hasLogin("giver@giver.com");
    // open file
    $content = file_get_contents($this->testLogFile);
    // assert
    $this->assertTrue(in_array($this->logger->type, [200,500]));
    $this->assertEquals(
      "[" . date("Y-m-d H:i:s") . "] test.ALERT: user {\"login\":\"giver\",\"ip\":\"127.0.0.1\"} []\n",
      $content);
  }

  //    It should save multiple actions (here, initialized then canceled a gift)
  //    on the log file
  public function testMultipleWriteAction()
  {
    // clean log file
    $fp = fopen($this->testLogFile, "w");
    fclose($fp);
    // mock
    $this->logger->setIp("127.0.0.1");
    $this->logger->setUser("giver");
    $this->logger->gaveGift($this->logger->username, "getter@user.com");
    $this->logger->canceledGift($this->logger->username);
    // open file
    $content = file_get_contents($this->testLogFile);
    $this->assertEquals(
      "[" . date("Y-m-d H:i:s") . "] test.INFO: gift {\"initialized\":\"from giver to getter\",\"ip\":\"127.0.0.1\"} []\n" .
      "[" . date("Y-m-d H:i:s") . "] test.INFO: gift {\"canceled\":\"giver\",\"ip\":\"127.0.0.1\"} []\n" ,
      $content);
  }
}
