<?php

require_once __DIR__ . '/../vendor/autoload.php';
use config\facade\Book;

class Library {

  /* Calling evil static method */
  public function overView($version)
  {
    $license = Book::license($version);
    return $license. " in overview";
  }

  /* Real life production code */
  public function view()
  {
    echo $this->overView("2.0");
  }
}
