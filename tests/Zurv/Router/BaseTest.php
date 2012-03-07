<?php
namespace Zurv\Router;

require_once '../Zurv/Router.php';
require_once '../Zurv/Router/Base.php';

class BaseTest extends \PHPUnit_Framework_TestCase {
  protected $_router;

  public function setUp() {
    $this->_router = new Base();
  }

  /**
   * @test
   */
  function routerIsSetUp() {
    $this->assertInstanceof('\Zurv\Router\Base', $this->_router);
  }

  /**
   * @test
   */
  function setAndProcessSimpleRoutes() {
    $this->_router->addRoutes(
      array(
        '/' => array('controller' => 'Index'),
        '/foo' => array('controller' => 'Index', 'action' => 'foo'),
        '/bar' => array('controller' => 'Bar', 'action' => 'bar')
      )
    );

    // Create mock for request
    $requestMock = $this->_getRequestMockWithPath('/');
    $route = $this->_router->route($requestMock);

    $this->assertEquals(array('controller' => 'Index', 'action' => 'index'), $route);

    $requestMock = $this->_getRequestMockWithPath('/bar');
    $route = $this->_router->route($requestMock);

    $this->assertEquals(array('controller' => 'Bar', 'action' => 'bar'), $route);
  }

  /**
   * @test
   */
  function setAndProcessDynamicRoutes() {
    $this->_router->addRoutes(
      array(
        '/(?P<controller>[a-z]+)/(?P<action>[a-z]+)' => array(),
        '/foo/bar/:action' => array('controller' => 'Foo')
      )
    );

    $requestMock = $this->_getRequestMockWithPath('/foo/bar');
    $route = $this->_router->route($requestMock);

    $this->assertEquals(array('controller' => 'Foo', 'action' => 'bar'), $route);

    $requestMock = $this->_getRequestMockWithPath('/foo/bar/someAction');
    $route = $this->_router->route($requestMock);

    $this->assertEquals(array('controller' => 'Foo', 'action' => 'someAction'), $route);
  }

  /**
   * Creates a mock object for a \Zurv\Request class instance. The mock expects the getPath method to be called once.
   * @param string $path
   */
  protected function _getRequestMockWithPath($path) {
    $requestMock = $this->getMock('\Zurv\Request', array('getPath'));
    $requestMock->expects($this->once())
                ->method('getPath')
                ->will($this->returnValue($path));

    return $requestMock;
  }
}