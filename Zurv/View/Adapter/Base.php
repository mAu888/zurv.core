<?php
namespace Zurv\View\Adapter;

use \Zurv\Application;
use \Zurv\View\Adapter;

abstract class Base implements Adapter {
  protected $_helpers = array();

  public function __call($name, $args) {
    if(! isset($this->_helpers[$name])) {
      if(class_exists($name) && ($helper = new $name()) instanceof \Zurv\View\Helper) {
        $this->_helpers[$name] = $helper;
      }
    }

    // Attempt to load helper failed
    if(! isset($this->_helpers[$name])) {
      throw new \BadMethodCallException('Bad method call to $name');
    }

    return call_user_func_array(array($this->_helpers[$name], 'execute'), $args);
  }
}