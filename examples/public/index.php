<?php
header('Content-Type: text/plain');

spl_autoload_register(function($class) {
  $class = str_replace('\\', '/', $class);

  if(file_exists('../../' . $class . '.php')) {
    require_once '../../' . $class . '.php';
  }
  else if(file_exists('../controllers/' . $class . '.php')) {
    require_once '../controllers/' . $class . '.php';
  }
});

// Set up request and response
$request = new \Zurv\Request\HTTP();
$response = new \Zurv\Response\HTTP();

$router = new \Zurv\Router\Base;
$route = $router->addRoute('/', 'Index', 'index');
$route->forGetRequest(true);

$route = $router->addRoute('/:controller(/:action)?');

$route = $router->route($request);

$dispatcher = new \Zurv\Dispatcher();
$dispatcher->dispatch($request, $response);

echo $response->getBody();