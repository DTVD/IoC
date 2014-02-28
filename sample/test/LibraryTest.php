<?php

require_once '../config/IoCConfig.php';
require_once '../src/Library.php';

class LibraryTest extends PHPUnit_Framework_TestCase{

  /**
   * Bootstrap for each test case
   */
  public function setUp()
  {
    Ioc::register('book_license', function($version){
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
  public function testOverView($version)
  {
    $l = new Library;
    $this->assertSame('mocked license in overview',$l->overView(2.0));
  }
}

