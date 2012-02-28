<?php
require_once 'library/ext/torophp/toro.php';
require_once 'library/Zurv/Application.php';
require_once 'config/config.php';

require_once 'handlers/AppHandler.php';
$application = new \Zurv\Application();

$application->run(array(
	array('/', 'AppHandler')
));