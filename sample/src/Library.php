<?php

require_once __DIR__ . '/../vendor/autoload.php';

class Library {
  public function overView($version)
  {
    $license = IoCBook::license($version);
    return $license. " in overview";
  }
}

$l = new Library;
echo $l->overView("2.0");

