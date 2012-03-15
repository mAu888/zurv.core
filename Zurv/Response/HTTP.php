<?php
namespace \Zurv\Response;

use \Zurv\Response;

class HTTP implements Response {
  public function setBody($body) {
    $this->_body = $body;
  }
}