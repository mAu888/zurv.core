<?php
namespace Zurv;

use \Zurv\Request;
use \Zurv\Response;

class Dispatcher {
  public function dispatch(Request $request, Response $response) {
    $controller = $this->_formatController($request->getController());
    $action = $this->_formatAction($request->getAction());

    if(! class_exists($controller)) {
      throw new \Exception("{$request->getController()} is not dispatchable");
    }

    $controller = new $controller($request, $response);

    if(! method_exists($controller, $action)) {
      throw new \Exception("{$request->getAction()} is not dispatchable");
    }

    call_user_func_array(array($controller, $action), array($request, $response));
  }

  protected function _formatAction($action) {
    if(strtolower(substr($action, 0, -6)) !== 'action') {
      return "{$action}Action";
    }

    return $action;
  }

  protected function _formatController($controller) {
    if(strtolower(substr($controller, 0, -10)) !== 'controller') {
      return "{$controller}Controller";
    }

    return $controller;
  }
}