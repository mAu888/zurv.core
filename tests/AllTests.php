<?php
/**
 * Testsuite for the zurv.core framework
 *
 * @author Maurício Hanika
 */

require_once 'UnitTestsSuite.php';
require_once 'IntegrationTestsSuite.php';

class AllTests {
  public static function suite() {
    $suite = new PHPUnit_Framework_TestSuite('zurv.core');

    $suite->addTestSuite(UnitTestsSuite::suite());
    $suite->addTestSuite(IntegrationTestsSuite::suite());

    return $suite;
  }
}