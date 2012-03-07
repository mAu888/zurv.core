<?php
namespace Zurv\Request;

use \Zurv\Request;

class HTTP implements Request {
  const GET = 'get';
  const POST = 'post';

  public function getRequestMethod() {
    return strtolower($_SERVER['REQUEST_METHOD']);
  }

  public function isGet() {
    return strtolower($_SERVER['REQUEST_METHOD']) === self::GET;
  }

  public function isPost() {
    return strtolower($_SERVER['REQUEST_METHOD']) === self::POST;
  }

  public function isXmlHttpRequest() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
  }

  public function isAjaxRequest() {
    return $this->isXmlHttpRequest();
  }
}