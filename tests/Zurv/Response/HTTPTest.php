<?php
namespace Zurv\Response;

require_once '../Zurv/Response.php';
require_once '../Zurv/Response/HTTP.php';

class HTTPTest extends \PHPUnit_Framework_TestCase {
  /**
   * @var \Zurv\Response
   */
  protected $_response;

  public function setUp() {
    $this->_response = new HTTP();
  }

  /**
   * @test
   */
  public function basicResponseInteraction() {
    $this->_response->setBody('A foolish game');
    $this->assertEquals('A foolish game', $this->_response->getBody());
  }

  /**
   * @test
   */
  public function flushingResponseOutputsToBufferAndClearsResponseBody() {
    $this->_response->setBody('A foolish game');

    ob_start();
    $this->_response->flush();
    $actual = ob_get_contents();
    ob_end_clean();

    $this->assertEquals('A foolish game', $actual);
    $this->assertEmpty($this->_response->getBody());
  }

  /**
   * @test
   * @runInSeparateProcess   
   */
  public function headersAreSetWhenResponseIsFlushed() {
    $this->_response->setHeader('Content-Type', 'text/html; charset=utf-8');
    $this->_response->setHeader('Expires', 'Fri, 30 Oct 1998 14:19:41 GMT');
    $this->_response->flush();

    $this->assertEquals(2, count($this->_response->getHeaders()));

    $headers = $this->_response->getHeaders();
    $this->assertContains('text/html; charset=utf-8', $headers);
    $this->assertContains('Fri, 30 Oct 1998 14:19:41 GMT', $headers);
    $this->assertArrayHasKey('Content-Type', $headers);
    $this->assertArrayHasKey('Expires', $headers);
  }
}