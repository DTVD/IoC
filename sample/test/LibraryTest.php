<?php

require_once __DIR__ . '/../vendor/autoload.php';
use orakaro\IoC\core\IoC;

class LibraryTest extends PHPUnit_Framework_TestCase{
  /**
   * Bootstrap for each test case
   */
  public function setUp()
  {
    /* Wake up lazy loading */
    Touchy::wakeMeUp();
    /* Redefined your desirable mock */
    IoC::register('Book_license', function($version){
      return 'mocked license';
    });
  }

  /**
   * Ending for each test case
   */
  public function tearDown()
  {
  }

  /**
   * test overView method
   * @param  String $version
   */
  public function testOverView()
  {
    $l = new Library;
    $this->assertSame('mocked license in overview',$l->overView("1.0"));
  }
}

