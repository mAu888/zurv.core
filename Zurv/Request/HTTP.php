<?php
namespace Zurv\Request;

use \Zurv\Request;

class HTTP implements Request {
  const GET = 'get';
  const POST = 'post';
  const PUT = 'put';
  const DELETE = 'delete';

  protected $_controller = '';
  protected $_action = '';
  protected $_parameters = array();
  protected $_extension = '';

  public function __construct() {
    if($this->isPut() || $this->isDelete()) {
      parse_str(file_get_contents('php://input'), $this->_parameters);
    }

    if($this->isGet()) {
      $this->_parameters = array_merge($this->_parameters, $_POST, $_GET);
    }
    else if($this->isPost()) {
      $this->_parameters = array_merge($this->_parameters, $_GET, $_POST);
    }
    else {
      $this->_parameters = array_merge($_GET, $_POST, $this->_parameters);
    }

    // Set request extension
    $this->_extension = pathinfo($this->getPath(), PATHINFO_EXTENSION);

    // Handle initial controller and action
    $this->_controller = $this->getParameter('controller');
    $this->_action = $this->getParameter('action');
  }

  public function getRequestMethod() {
    return strtolower($_SERVER['REQUEST_METHOD']);
  }

  public function isGet() {
    return strtolower($_SERVER['REQUEST_METHOD']) === self::GET;
  }

  public function isPost() {
    return strtolower($_SERVER['REQUEST_METHOD']) === self::POST;
  }

  public function isPut() {
    return strtolower($_SERVER['REQUEST_METHOD']) === self::PUT;
  }

  public function isDelete() {
    return strtolower($_SERVER['REQUEST_METHOD']) === self::DELETE;
  }

  public function getParameter($name) {
    return isset($this->_parameters[$name]) ? $this->_parameters[$name] : null;
  }

  public function getExtension() {
    return $this->_extension;
  }

  public function getPath() {
    if(isset($_SERVER['PATH_INFO'])) {
      return $_SERVER['PATH_INFO'];
    }
    else if(isset($_SERVER['REQUEST_URI'])) {
      $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
      $path = str_replace($_SERVER['SCRIPT_NAME'], '', $path);

      return substr($path, 0, 1) === '/' ? $path : "/{$path}";
    }
    else {
      $path = parse_url($_SERVER['PHP_SELF'], PHP_URL_PATH);
      $path = str_replace($_SERVER['SCRIPT_NAME'], '', $path);

      return substr($path, 0, 1) === '/' ? $path : "/{$path}";
    }
  }

  public function setController($controller) {
    $this->_controller = $controller;
  }

  public function getController() {
    return $this->_controller;
  }

  public function setAction($action) {
    $this->_action = $action;
  }

  public function getAction() {
    return $this->_action;
  }

  public function isXmlHttpRequest() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
  }

  public function isAjaxRequest() {
    return $this->isXmlHttpRequest();
  }
}