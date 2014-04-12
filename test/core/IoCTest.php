<?php
use orakaro\IoC\core\IoC;

/**
 * Test Inversion Of Control class
 */
class IoCTest extends PHPUnit_Framework_TestCase {

  /**
   * Test Register
   */
  public function testRegister()
  {
    IoC::register('Aragorn',function(){
      return 'Aragorn the heirs of Isildur';
    });
    $resolve =  IoC::resolve('Aragorn');
    $this->assertEquals('Aragorn the heirs of Isildur',$resolve());
  }

  /**
   * Test Singleton
   */
  public function testSingleton()
	{
    IoC::singleton('Frodo',function(){
      return 'Frodo of the Shire';
    });
    $resolve =  IoC::resolve('Frodo');
    $this->assertEquals('Frodo of the Shire',$resolve);
	}

  /**
   * Test Exception
   * @expectedException        Exception
   * @expectedExceptionMessage IoC register exception
   */
  public function testRegisterException()
  {
    $resolve =  IoC::resolve('DarkLord');
  }

  /**
   * Register will ends up with seperated instance
   */
  public function testSeperatedInstances($value='')
  {
    IoC::register('Gandalf',function(){
      return new stdClass;
    });
    $GandalfTheGrey = IoC::resolve('Gandalf');
    $GandalfTheWhite = IoC::resolve('Gandalf');
    $this->assertFalse($GandalfTheWhite() === $GandalfTheGrey());
  }

  /**
   * Singleton will ends up with same instance
   */
  public function testUniqueSingleton($value='')
  {
    IoC::singleton('Gandalf',function(){
      return new stdClass;
    });
    $GandalfTheGrey = IoC::resolve('Gandalf');
    $GandalfTheWhite = IoC::resolve('Gandalf');
    $this->assertTrue($GandalfTheWhite === $GandalfTheGrey);
  }

}