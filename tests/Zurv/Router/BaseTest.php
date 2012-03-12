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
    $requestMock = $this->_getRequestMockWithPath('/', array('setController', 'setAction'));
    $requestMock->expects($this->once())
                ->method('setController')
                ->with('Index');
    $requestMock->expects($this->once())
                ->method('setAction')
                ->with('index');
    $route = $this->_router->route($requestMock);

    $this->assertEquals(array('controller' => 'Index', 'action' => 'index'), $route);

    $requestMock = $this->_getRequestMockWithPath('/bar', array('setController', 'setAction'));
    $requestMock->expects($this->once())
                ->method('setController')
                ->with('Bar');
    $requestMock->expects($this->once())
                ->method('setAction')
                ->with('bar');
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

    $requestMock = $this->_getRequestMockWithPath('/foo/bar', array('setController', 'setAction'));
    $requestMock->expects($this->once())
                ->method('setController')
                ->with('Foo');
    $requestMock->expects($this->once())
                ->method('setAction')
                ->with('bar');
    $route = $this->_router->route($requestMock);

    $this->assertEquals(array('controller' => 'Foo', 'action' => 'bar'), $route);

    $requestMock = $this->_getRequestMockWithPath('/foo/bar/someAction', array('setController', 'setAction'));
    $requestMock->expects($this->once())
                ->method('setController')
                ->with('Foo');
    $requestMock->expects($this->once())
                ->method('setAction')
                ->with('someAction');
    $route = $this->_router->route($requestMock);

    $this->assertEquals(array('controller' => 'Foo', 'action' => 'someAction'), $route);
  }

  /**
   * @test
   */
  function inRouteDefinedParametersAreOverwrittenByRequestParameters() {
    $this->_router->addRoute('/', 'Index', 'index', array('id' => 1));

    $requestMock = $this->_getRequestMockWithPath('/', array('hasParameter', 'setParameter', 'setController', 'setAction'));
    $requestMock->expects($this->once())
                ->method('hasParameter')
                ->with('id')
                ->will($this->returnValue(false));
    $requestMock->expects($this->once())
                ->method('setParameter')
                ->with('id', 1);
    $requestMock->expects($this->once())
                ->method('setController')
                ->with('Index');
    $requestMock->expects($this->once())
                ->method('setAction')
                ->with('index');
    $route = $this->_router->route($requestMock);

    $requestMock = $this->_getRequestMockWithPath('/', array('hasParameter', 'setController', 'setAction'));
    $requestMock->expects($this->once())
                ->method('hasParameter')
                ->with('id')
                ->will($this->returnValue(true));
    $requestMock->expects($this->once())
                ->method('setController')
                ->with('Index');
    $requestMock->expects($this->once())
                ->method('setAction')
                ->with('index');
    $route = $this->_router->route($requestMock);
  }

  /**
   * @test
   */
  function extensionInRouteIsSetInRequestObject() {
    $this->_router->addRoute('/:controller', 'Index', 'index');

    $requestMock = $this->_getRequestMockWithPath('/foo.json', array('setExtension', 'setController', 'setAction'));
    $requestMock->expects($this->once())
                ->method('setExtension')
                ->with('json');
    $requestMock->expects($this->once())
                ->method('setController')
                ->with('Foo');
    $requestMock->expects($this->once())
                ->method('setAction')
                ->with('index');
    $route = $this->_router->route($requestMock);

    $this->assertEquals('Foo', $route['controller']);
    $this->assertEquals('index', $route['action']);
  }

  /**
   * Creates a mock object for a \Zurv\Request class instance. The mock expects the getPath method to be called once.
   * @param string $path
   */
  protected function _getRequestMockWithPath($path, $additionalMethods = array()) {
    $requestMock = $this->getMock('\Zurv\Request', array_merge(array('getPath'), $additionalMethods));
    $requestMock->expects($this->once())
                ->method('getPath')
                ->will($this->returnValue($path));

    return $requestMock;
  }
}