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
  function requestIsSetUp() {
    $this->assertInstanceof('\Zurv\Request\HTTP', $this->_request);
  }

  /**
   * @test
   */
  function getRequestMethods() {
    $_SERVER['REQUEST_METHOD'] = 'GET';

    $this->assertEquals(HTTP::GET, $this->_request->getRequestMethod());
    $this->assertTrue($this->_request->isGet());
    $this->assertFalse($this->_request->isPost());
  }

  /**
   * @test
   */
  function isAjaxRequest() {
    $this->assertFalse($this->_request->isXmlHttpRequest());
    $this->assertFalse($this->_request->isAjaxRequest());

    $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
    $this->assertTrue($this->_request->isXmlHttpRequest());
    $this->assertTrue($this->_request->isAjaxRequest());
  }
}