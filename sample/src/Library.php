<?php

require_once __DIR__ . '/../vendor/autoload.php';

class Library {

  /* Calling evil static method */
  public function overView($version)
  {
    $license = IoCBook::license($version);
    return $license. " in overview";
  }

  /* Realife production code */
  public function view()
  {
    echo $this->overView("2.0");
  }
}


