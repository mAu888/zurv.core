<?php
define('ZURV_BASE_PATH', realpath('./../../') . '/');
define('APP_BASE_PATH', realpath('./../') . '/');

require_once ZURV_BASE_PATH . 'Submodules/torophp/toro.php';
require_once ZURV_BASE_PATH . 'Zurv/Application.php';
require_once APP_BASE_PATH . 'config/config.php';

require_once APP_BASE_PATH . 'handlers/AppHandler.php';
$application = new \Zurv\Application(array(
	'libraryPath' => ZURV_BASE_PATH,
  'bootstrapperClass' => '\Zurv\Bootstrapper\Base',
  'registry' => \Zurv\Registry::getInstance()
));

$application->bootstrap()->run(array(
	array('/', 'AppHandler')
));