<?php
namespace Zurv\Request;

use \Zurv\Request;

class HTTP implements Request {
  const GET = 'get';
  const POST = 'post';
  const PUT = 'put';
  const DELETE = 'delete';

  protected $_parameters = array();

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

  public function isXmlHttpRequest() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
  }

  public function isAjaxRequest() {
    return $this->isXmlHttpRequest();
  }
}