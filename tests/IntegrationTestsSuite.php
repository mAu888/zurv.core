<?php
class IntegrationTestsSuite extends PHPUnit_Framework_TestSuite {
  public static function suite() {
    $suite = new IntegrationTestsSuite();

    $suite->addTestFile('Integration/GetRequestTest.php');

    return $suite;
  }
}