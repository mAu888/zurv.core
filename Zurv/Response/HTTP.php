<?php
namespace Zurv\Response;

use \Zurv\Response;

class HTTP implements Response {
  protected $_body = '';

  public function getBody() {
    return $this->_body;
  }

  public function setBody($body) {
    $this->_body = $body;
  }
}