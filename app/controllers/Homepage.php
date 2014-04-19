<?php
namespace Controller;

class Homepage
{
  private $app;

  public function __construct($app)
  {
    $this->app = $app;

  }
  public function indexAction()
  {
    if( $this->app["login"] === true)
    {
      return $this->app["twig"]->render("homepage.twig", [
        "title" => "homepage",
        "login" => true
      ]);
    }
    return $this->app["twig"]->render("homepage.twig", [
      "title" => "homepage",
      "login" => false
    ]);
  }
}
