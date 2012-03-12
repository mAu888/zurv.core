<?php
namespace Zurv\Router;

use \Zurv\Router;
use \Zurv\Request;

class Base implements Router {
  /**
   * @var array
   */
  protected $_routes = array();

  public function addRoute($route, $controller = 'Index', $action = 'index', $parameters = array()) {
    $this->_routes[$route] = array(
      'controller' => $controller,
      'action' => $action,
      'parameters' => $parameters
    );
  }

  public function addRoutes($routes) {
    foreach($routes as $route => $options) {
      $action = 'index';
      if(isset($options['action']) && ! empty($options['action'])) {
        $action = $options['action'];
      }

      $controller = 'Index';
      if(isset($options['controller']) && ! empty($options['controller'])) {
        $controller = $options['controller'];
      }

      $this->addRoute($route, $controller, $action);
    }
  }

  public function route(Request $request) {
    $path = $request->getPath();

    // Check for an extension
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    if(! empty($ext)) {
      $request->setExtension($ext);
      $path = substr($path, 0, strlen($path) - strlen($ext) - 1);
    }

    $matchedRoute = false;
    foreach($this->_routes as $route => $options) {
      $route = str_replace(array('/', ':action', ':controller'), array('\/', '(?P<action>[a-z-_]+)', '(?P<controller>[a-z-_]+)'), $route);

      if(preg_match('/^' . $route . '$/i', $path, $matches)) {
        $matchedRoute = $options;

        if(isset($matches['action'])) {
          $matchedRoute['action'] = $matches['action'];
        }

        if(isset($matches['controller'])) {
          $matchedRoute['controller'] = ucfirst($matches['controller']);
        }
      }
    }

    if($matchedRoute) {
      foreach($matchedRoute['parameters'] as $name => $value) {
        if(! $request->hasParameter($name)) {
          $request->setParameter($name, $value);
        }
      }
      unset($matchedRoute['parameters']);

      // Set the found controller and action to the request object
      $request->setController($matchedRoute['controller']);
      $request->setAction($matchedRoute['action']);
    }

    return $matchedRoute;
  } 
}