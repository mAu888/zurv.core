<?php
namespace Zurv\Bootstrapper;

use \Zurv\Application;
use \Zurv\Bootstrapper;

class Base implements Bootstrapper {
  protected $_application;
  protected $_bootstrapped = array();

  public function __construct(Application $application) {
    $this->_application = $application;
  }

  public function bootstrap($methodOrSection) {
    if(strpos($methodOrSection, 'init') !== 0) {
      $methodOrSection = 'init' . ucfirst($methodOrSection);
    }

    // If the section has already been bootstrapped, return
    if(in_array($methodOrSection, $this->_bootstrapped)) {
      return;
    }

    $this->{$methodOrSection}($this->_application);

    array_push($this->_bootstrapped, $methodOrSection);
  }
}