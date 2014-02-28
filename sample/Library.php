<?php

require_once 'Book.php';
require_once __DIR__ . '/../vendor/autoload.php';

IoC::register('book_license', function($version){
  return Book::license($version);
});

class IoCBook {
  public static function license($version)
  {
    $c = IoC::resolve('book_license');
    return $c($version);
  }
}

class Library {
  public function overView($version)
  {
    echo IoCBook::license($version);
  }
}


$l = new Library;
$l->overView("2.0");


