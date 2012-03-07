<?php
namespace Zurv\Router;

use \Zurv\Router;
use \Zurv\Request;

class Base implements Router {
  /**
   * @var array
   */
  protected $_routes = array();

  public function addRoute($route, $controller, $action = 'index', $parameters = array()) {
    $this->_routes[$route] = array(
      'controller' => $controller,
      'action' => $action
    );
  }

  public function addRoutes($routes) {
    foreach($routes as $route => $options) {
      $action = 'index';
      if(isset($options['action']) && ! empty($options['action'])) {
        $action = $options['action'];
      }

      $this->addRoute($route, $options['controller'], $action);
    }
  }

  public function route(Request $request) {
    $path = $request->getPath();

    $matchedRoute = false;
    foreach($this->_routes as $route => $options) {
      if(preg_match('/^' . preg_quote($route, '/') . '$/i', $path, $matches)) {
        $matchedRoute = $options;
      }
    }

    return $matchedRoute;
  } 
}