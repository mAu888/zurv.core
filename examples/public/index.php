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
require_once '../Bootstrap.php';
$app = new \Zurv\Application(
  array(
    'bootstrapperClass' => '\Application\Bootstrap'
  )
);
$app->bootstrap()->run();