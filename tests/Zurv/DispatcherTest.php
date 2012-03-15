<?php
namespace Zurv;

require_once '../Zurv/Dispatcher.php';
require_once '../Zurv/Request.php';
require_once '../Zurv/Response.php';

use \Zurv\Request;

class DispatcherTest extends \PHPUnit_Framework_TestCase {
  protected $_dispatcher;

  public function setUp() {
    $this->_dispatcher = new Dispatcher();
  }

  /**
   * @test
   */
  public function dispatchNormalGetRequestFromDesktopBrowser() {
    $requestMock = $this->getMock('\Zurv\Request');
    $requestMock->expects($this->any())
                ->method('getController')
                ->will($this->returnValue('Index'));
    $requestMock->expects($this->any())
                ->method('getAction')
                ->will($this->returnValue('index'));

    $responseMock = $this->getMock('\Zurv\Response');
    $responseMock->expects($this->once())
                 ->method('setBody')
                 ->with('Foo');

    require_once 'IndexController.php';

    $this->_dispatcher->dispatch($requestMock, $responseMock);
  }
}
