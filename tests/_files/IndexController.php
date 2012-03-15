<?php
require_once 'Zurv/Controller.php';
require_once 'Zurv/Request.php';
require_once 'Zurv/Response.php';

use \Zurv\Controller;
use \Zurv\Request;
use \Zurv\Response;

class IndexController implements Controller {
  public function indexAction(Request $request, Response $response) {
    $response->setBody('Foo');
  }
}