<?php
namespace Zurv;

use \Zurv\Request;

interface Router {
  function addRoute($route, $controller, $action = 'index', $parameters = array());
  function addRoutes($routes);

  function route(Request $request);
}