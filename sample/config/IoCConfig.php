<?php

require_once __DIR__ . '/../vendor/autoload.php';
use orakaro\IoC\core\IoC;

/* Wake up lazy loading */
class Touchy {
  public static function wakeMeUp()
  {
  }
}

/* Production register */
IoC::register('book_license', function($version){
  return Book::license($version);
});

/* Replacing real classes */
class IoCBook {
  public static function license($version)
  {
    $b = IoC::resolve('book_license');
    return $b($version);
  }
}

