<?php
namespace Zurv\Response;

use \Zurv\Response;

class HTTP implements Response {
  protected $_body = '';

  /**
   * The http response header fields, which will be send to the clients browser
   *
   * @var array
   */
  protected $_headers = array();

  public function getBody() {
    return $this->_body;
  }

  public function setBody($body) {
    $this->_body = $body;
  }

  public function setHeader($header, $value) {
    $this->_headers[$header] = $value;
  }

  public function getHeaders() {
    return $this->_headers;
  }

  public function flush() {
    // Set headers
    foreach($this->_headers as $header => $value) {
      header("{$header}: {$value}");
    }

    echo $this->_body;

    // Clear the body
    $this->_body = '';
  }
}