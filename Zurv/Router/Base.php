<?php
namespace Zurv\Router;

use \Zurv\Router;
use \Zurv\Request;

class Base implements Router {
  public function addRoute($route, $controller, $action = 'index', $parameters = array()) {

  }

  public function addRoutes($routes) {

  }

  public function route(Request $request) {
    $path = $request->getPath();
    
    return array('controller' => 'Index', 'action' => 'index');
  } 
}