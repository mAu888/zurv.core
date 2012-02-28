<?php
define(ZURV_BASE_PATH, realpath('./../') . '/');

require_once ZURV_BASE_PATH . 'Submodules/torophp/toro.php';
require_once ZURV_BASE_PATH . 'Zurv/Application.php';
require_once 'config/config.php';

require_once 'handlers/AppHandler.php';
$application = new \Zurv\Application(array(
	'library' => ZURV_BASE_PATH
));

$application->run(array(
	array('/', 'AppHandler')
));