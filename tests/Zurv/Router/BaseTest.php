<?php
namespace Zurv\Router;

require_once '../Zurv/Request.php';
require_once '../Zurv/Router.php';
require_once '../Zurv/Router/Base.php';
require_once '../Zurv/Router/Route.php';

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
    $requestMock = $this->_getRequestMockWithPathControllerAction('/', 'Index', 'index');
    $route = $this->_router->route($requestMock);

    $this->assertEquals('Index', $route->getController());
    $this->assertEquals('index', $route->getAction());

    $requestMock = $this->_getRequestMockWithPathControllerAction('/bar', 'Bar', 'bar');
    $route = $this->_router->route($requestMock);

    $this->assertEquals('Bar', $route->getController());
    $this->assertEquals('bar', $route->getAction());
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

    $requestMock = $this->_getRequestMockWithPathControllerAction('/foo/bar', 'Foo', 'bar');
    $route = $this->_router->route($requestMock);

    $this->assertEquals('Foo', $route->getController());
    $this->assertEquals('bar', $route->getAction());

    $requestMock = $this->_getRequestMockWithPathControllerAction('/foo/bar/someAction', 'Foo', 'someAction');
    $route = $this->_router->route($requestMock);

    $this->assertEquals('Foo', $route->getController());
    $this->assertEquals('someAction', $route->getAction());
  }

  /**
   * @test
   */
  function inRouteDefinedParametersAreOverwrittenByRequestParameters() {
    $this->_router->addRoute('/', 'Index', 'index', array('id' => 1));

    $requestMock = $this->_getRequestMockWithPathControllerAction('/', 'Index', 'index', array('hasParameter', 'setParameter'));
    $requestMock->expects($this->once())
                ->method('hasParameter')
                ->with('id')
                ->will($this->returnValue(false));
    $requestMock->expects($this->once())
                ->method('setParameter')
                ->with('id', 1);
    $route = $this->_router->route($requestMock);

    $requestMock = $this->_getRequestMockWithPathControllerAction('/', 'Index', 'index', array('hasParameter'));
    $requestMock->expects($this->once())
                ->method('hasParameter')
                ->with('id')
                ->will($this->returnValue(true));
    $route = $this->_router->route($requestMock);
  }

  /**
   * @test
   */
  function extensionInRouteIsSetInRequestObject() {
    $this->_router->addRoute('/:controller', 'Index', 'index');

    $requestMock = $this->_getRequestMockWithPathControllerAction('/foo.json', 'Foo', 'index', array('setExtension'));
    $requestMock->expects($this->once())
                ->method('setExtension')
                ->with('json');
    $route = $this->_router->route($requestMock);

    $this->assertEquals('Foo', $route->getController());
    $this->assertEquals('index', $route->getAction());
  }

  /**
   * @test
   */
  function requestTypeAwareRouteOnlyRespondsToGivenType() {
    $route = $this->_router->addRoute('/', 'Index', 'index');
    $route->forGetRequest(false);

    $requestMock = $this->getMockForAbstractClass('\Zurv\Request');
    $requestMock->expects($this->once())
                ->method('getPath')
                ->will($this->returnValue('/'));
    $requestMock->expects($this->once())
                ->method('getRequestMethod')
                ->will($this->returnValue('get'));

    $route = $this->_router->route($requestMock);

    $this->assertFalse($route);
  }

  /**
   * @test
   */
  function dynamicParametersDefinedInRouteAreTransferredIntoRequestObject() {
    $route = $this->_router->addRoute('/(?P<id>[1-9][0-9]*)', 'Index', 'index');

    $requestMock = $this->getMockForAbstractClass('\Zurv\Request');
    $requestMock->expects($this->once())
                ->method('getPath')
                ->will($this->returnValue('/1'));
    $requestMock->expects($this->once())
                ->method('getRequestMethod')
                ->will($this->returnValue('get'));
    $requestMock->expects($this->once())
                ->method('setParameter')
                ->with('id', '1');

    $route = $this->_router->route($requestMock);
  }

  /**
   * @test
   */
  function requiringRequestToBeAjaxExpectsRouterToFailOnNormalRequest() {
    $route = $this->_router->addRoute('/', 'Index', 'index');
    $route->setRequireXmlHttpRequest(true);

    $requestMock = $this->getMockForAbstractClass('\Zurv\Request');
    $requestMock->expects($this->once())
                ->method('getPath')
                ->will($this->returnValue('/'));

    $route = $this->_router->route($requestMock);
    $this->assertFalse($route);
  }

  /**
   * @test
   */
  function requiringRequestToBeAjaxExpectsRouterToSuccessOnAjaxRequest() {
    $route = $this->_router->addRoute('/', 'Index', 'index');
    $route->setRequireXmlHttpRequest(true);

    $requestMock = $this->getMockForAbstractClass('\Zurv\Request');
    $requestMock->expects($this->once())
                ->method('getPath')
                ->will($this->returnValue('/'));
    $requestMock->expects($this->any())
                ->method('getRequestMethod')
                ->will($this->returnValue('get'));
    $requestMock->expects($this->once())
                ->method('isXmlHttpRequest')
                ->will($this->returnValue(true));

    $route = $this->_router->route($requestMock);
    $this->assertEquals('Index', $route->getController());
    $this->assertEquals('index', $route->getAction());
  }

  /**
   * @test
   **/
  function assignMultipleRoutesByArraySettingAdditionalRequirements() {
    $this->_router->addRoutes(
      array(
        '/foo/bar' => array(
          'controller' => 'Foo',
          'action' => 'bar',
          'isAjax' => true,
          'requestTypes' => array(Route::GET, Route::POST)
        )
      )
    );

    $requestMock = $this->_getRequestMockWithPathControllerAction('/foo/bar', 'Foo', 'bar');
    $requestMock->expects($this->atLeastOnce())
                ->method('isXmlHttpRequest')
                ->will($this->returnValue(true));

    $route = $this->_router->route($requestMock);
  }

  /**
   * Creates a mock object for a \Zurv\Request class instance.
   * @param string $path
   * @param string $controller
   * @param string $action
   */
  protected function _getRequestMockWithPathControllerAction($path, $controller, $action, $additionalMethods = array(), $requestMethod = 'get') {
    $requestMock = $this->getMockForAbstractClass('\Zurv\Request');

    $requestMock->expects($this->any())
                ->method('getRequestMethod')
                ->will($this->returnValue($requestMethod));
    $requestMock->expects($this->once())
                ->method('getPath')
                ->will($this->returnValue($path));
    $requestMock->expects($this->once())
                ->method('setController')
                ->with($controller);
    $requestMock->expects($this->once())
                ->method('setAction')
                ->with($action);

    return $requestMock;
  }
}