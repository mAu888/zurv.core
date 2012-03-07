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
    $requestMock = $this->getMock('\Zurv\Request', array('getPath'));
    $requestMock->expects($this->once())
                ->method('getPath')
                ->will($this->returnValue('/'));

    $route = $this->_router->route($requestMock);

    $this->assertEquals(array('controller' => 'Index', 'action' => 'index'), $route);
  }
}