<?php
namespace Application;

class Bootstrap extends \Zurv\Bootstrapper\Base {
  public function initRoutes($application) {
    $application->getRouter()->addRoute('/', 'Index', 'index');
    $application->getRouter()->addRoute('/:controller(?:/(:action)?)?', 'Index', 'index');
  }
}