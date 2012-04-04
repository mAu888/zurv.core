<?php
namespace Zurv\View;

use Zurv\View\View;
use Zurv\View\Adapter\Factory as AdapterFactory;

require_once '../Zurv/View/View.php';
require_once '../Zurv/View/Adapter.php';
require_once '../Zurv/View/Adapter/Base.php';
require_once '../Zurv/View/Adapter/JSONView.php';
require_once '../Zurv/View/Adapter/Factory.php';

class JSONViewTest extends \PHPUnit_Framework_TestCase {
  protected $_view;

  public function setUp() {
    $adapter = AdapterFactory::create(AdapterFactory::JSON);
    $this->_view = new View($adapter);
  }

  /**
   * @test
   */
  public function viewIsInitializedCorrectly() {
    $this->assertInstanceof('\Zurv\View\View', $this->_view);
    $this->assertInstanceof('\Zurv\View\Adapter\JSONView', $this->_view->getAdapter());
  }

  /**
   * @test
   * @runInSeparateProcess
   */
  public function renderingTemplateRetursValidJSON() {
    $this->_view->foo = 'Bar';
    $this->_view->bar = array('foo', 'bar');

    $actual = $this->_view->render();
    $expected = '{"foo":"Bar","bar":["foo","bar"]}';

    $this->assertEquals($expected, $actual);
  }
}