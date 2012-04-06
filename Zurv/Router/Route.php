<?php
namespace Zurv\Router;

class Route {
  protected $_route = '';
  protected $_controller = 'Index';
  protected $_action = 'index';
  protected $_parameters = array();

  protected $_get = true;
  protected $_post = true;
  protected $_put = true;
  protected $_delete = true;

  protected $_requireAjax = false;

  public function __construct($route, $controller, $action, $parameters = array()) {
    $this->_route = $route;
    $this->_controller = $controller;
    $this->_action = $action;
    $this->_parameters = $parameters;
  }

  public function getRoute() {
    return $this->_route;
  }

  public function getAction() {
    return $this->_action;
  }

  public function getController() {
    return $this->_controller;
  }

  public function getParameters() {
    return $this->_parameters;
  }

  public function setController($controller) {
    $this->_controller = $controller;
  }

  public function setAction($action) {
    $this->_action = $action;
  }

  public function responds($requestType) {
    $requestType = strtolower($requestType);

    switch($requestType) {
      case 'get': return $this->_get; break;
      case 'post': return $this->_post; break;
      case 'put': return $this->_put; break;
      case 'delete': return $this->_delete; break;
      default: return false; break;
    }
  }

  public function setRequireXmlHttpRequest($require) {
    $this->_requireAjax = (bool)$require;
  }

  public function getRequireXmlHttpRequest() {
    return $this->_requireAjax;
  }

  public function forGetRequest($responds = true) {
    $this->_get = $responds;
  }

  public function forPostRequest($responds = true) {
    $this->_post = $responds;
  }

  public function forPutRequest($responds = true) {
    $this->_put = $responds;
  }

  public function forDeleteRequest($responds = true) {
    $this->_delete = $responds;
  }
}