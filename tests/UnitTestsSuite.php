<?php
class UnitTestsSuite extends PHPUnit_Framework_TestSuite {
  public static function suite() {
    $suite = new UnitTestsSuite();

    $suite->addTestFile('Zurv/DispatcherTest.php');
    $suite->addTestFile('Zurv/Request/HTTPTest.php');
    $suite->addTestFile('Zurv/Router/BaseTest.php');

    return $suite;
  }
}