<?php
namespace Zurv\Request;

require_once '../Zurv/Request.php';
require_once '../Zurv/Request/HTTP.php';

class HTTPTest extends \PHPUnit_Framework_TestCase {
  protected $_request;

  public function setUp() {
    $_SERVER['REQUEST_METHOD'] = 'GET';

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
    $request = new HTTP();

    $this->assertEquals(HTTP::GET, $request->getRequestMethod());
    $this->assertTrue($request->isGet());
    $this->assertFalse($request->isPost());
    $this->assertFalse($request->isPut());
    $this->assertFalse($request->isDelete());

    $_SERVER['REQUEST_METHOD'] = 'POST';
    $request = new HTTP();

    $this->assertEquals(HTTP::POST, $request->getRequestMethod());
    $this->assertTrue($request->isPost());
    $this->assertFalse($request->isGet());
    $this->assertFalse($request->isPut());
    $this->assertFalse($request->isDelete());

    $_SERVER['REQUEST_METHOD'] = 'PUT';
    $request = new HTTP();

    $this->assertEquals(HTTP::PUT, $request->getRequestMethod());
    $this->assertTrue($request->isPut());
    $this->assertFalse($request->isGet());
    $this->assertFalse($request->isPost());
    $this->assertFalse($request->isDelete());

    $_SERVER['REQUEST_METHOD'] = 'DELETE';
    $request = new HTTP();

    $this->assertEquals(HTTP::DELETE, $request->getRequestMethod());
    $this->assertTrue($request->isDelete());
    $this->assertFalse($request->isGet());
    $this->assertFalse($request->isPost());
    $this->assertFalse($request->isPut());
  }

  /**
   * @test
   */
  function getParameterFromCurrentRequestTypeOverwritesOthers() {
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_GET['id'] = 1;
    $_POST['id'] = 3;

    $request = new HTTP();

    $this->assertEquals(1, $request->getParameter('id'));
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