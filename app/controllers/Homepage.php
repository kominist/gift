<?php
namespace Controller;

/**
 *  Homepage
 *  Homepage rendering controller
 *
 *  @package Homepage
 *  @category classes
 *  @licence GPL3
 */
class Homepage
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
   *  Render the main page
   *
   *  @return mixed twig template to render
   */
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
