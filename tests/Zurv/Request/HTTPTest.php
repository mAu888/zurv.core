<?php
namespace Zurv\Request;

require_once '../Zurv/Request.php';
require_once '../Zurv/Request/HTTP.php';

class HTTPTest extends \PHPUnit_Framework_TestCase {
  protected $_request;

  public function setUp() {
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['PATH_INFO'] = '';

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

  /**
   * @test
   */
  function getPathForCurrentRequest() {
    $_SERVER['PATH_INFO'] = '/foo/bar';

    $request = new HTTP();
    $this->assertEquals('/foo/bar', $request->getPath());

    $_SERVER = array();
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['REQUEST_URI'] = 'foo/index.php/foo/bar?id=1';
    $_SERVER['SCRIPT_NAME'] = 'foo/index.php/';

    $request = new HTTP();
    $this->assertEquals('/foo/bar', $request->getPath());

    $_SERVER = array();
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['REQUEST_URI'] = 'foo/index.php/foo/bar?id=1';
    $_SERVER['SCRIPT_NAME'] = 'foo/index.php';

    $request = new HTTP();
    $this->assertEquals('/foo/bar', $request->getPath());

    $_SERVER = array();
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['PHP_SELF'] = 'foo/index.php/foo/bar?id=1';
    $_SERVER['SCRIPT_NAME'] = 'foo/index.php/';

    $request = new HTTP();
    $this->assertEquals('/foo/bar', $request->getPath());
  }

  /**
   * @test
   */
  function extensionIsInitiallySetFromRequestUrl() {
    $_SERVER['PATH_INFO'] = '/foo/bar.json';

    $request = new HTTP();
    $this->assertEquals('json', $request->getExtension());
  }

  /**
   * @test
   */
  function setAndGetControllerAndActionForRequest() {
    $_GET['controller'] = 'index';
    $_GET['action'] = 'index';
    
    $request = new HTTP();
    $this->assertEquals('index', $request->getController());
    $this->assertEquals('index', $request->getAction());

    $request->setController('Foo');
    $request->setAction('bar');
    $this->assertEquals('Foo', $request->getController());
    $this->assertEquals('bar', $request->getAction());
  }
}