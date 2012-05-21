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
    $route = new Route($route, $controller, $action, $parameters);
    
    array_push($this->_routes, $route);

    return $route;
  }

  public function addRoutes($routes) {
    foreach($routes as $route => $options) {
      $action = 'index';
      if(isset($options['action']) && ! empty($options['action'])) {
        $action = $options['action'];
        unset($options['action']);
      }

      $controller = 'Index';
      if(isset($options['controller']) && ! empty($options['controller'])) {
        $controller = $options['controller'];
        unset($options['controller']);
      }

      $route = $this->addRoute($route, $controller, $action, $options);

      if(isset($options['isAjax']) && $options['isAjax'] === true) {
        $route->setRequireXmlHttpRequest(true);
      }

      if(isset($options['requestTypes']) && is_array($options['requestTypes'])) {
        call_user_func_array(array($route, 'setRequestTypes'), $options['requestTypes']);
      }
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
    foreach($this->_routes as $route) {
      // Route does not respond to current request method
      if(! $route->responds($request->getRequestMethod())) {
        continue;
      }

      // Route requires request to be ajax
      if($route->getRequireXmlHttpRequest() && ! $request->isXmlHttpRequest()) {
        continue;
      }

      // Route is non ajax but request is
      if(! $route->getRequireXmlHttpRequest() && $request->isXmlHttpRequest()) {
        continue;
      }

      $routePattern = str_replace(array('/', ':action', ':controller'), array('\/', '(?P<action>[a-z-_]+)', '(?P<controller>[a-z-_]+)'), $route->getRoute());

      if(preg_match('/^' . $routePattern . '$/i', $path, $matches)) {
        $matchedRoute = $route;
        if(isset($matches['action']) && ! empty($matches['action'])) {
          $route->setAction($matches['action']);
          unset($matches['action']);
        }

        if(isset($matches['controller']) && ! empty($matches['controller'])) {
          $route->setController(ucfirst($matches['controller']));
          unset($matches['controller']);
        }

        // Set the routes default parameters
        foreach($matchedRoute->getParameters() as $name => $value) {
          if(! $request->hasParameter($name)) {
            $request->setParameter($name, $value);
          }
        }

        // Set the found controller and action to the request object
        $request->setController($matchedRoute->getController());
        $request->setAction($matchedRoute->getAction());

        foreach($matches as $key => $value) {
          if(is_string($key)) {
            $request->setParameter($key, $value);
          }
        }

        break;
      }
    }

    return $matchedRoute;
  } 
}