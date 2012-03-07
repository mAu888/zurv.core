<?php
namespace Zurv\Request;

require_once '../Zurv/Request.php';
require_once '../Zurv/Request/HTTP.php';

class HTTPTest extends \PHPUnit_Framework_TestCase {
  protected $_request;

  public function setUp() {
    $this->_request = new HTTP();
  }

  /**
   * @test
   */ 
  public function requestIsSetUp() {
    $this->assertInstanceof('\Zurv\Request\HTTP', $this->_request);
  }
}