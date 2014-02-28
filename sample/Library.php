<?php

require_once 'IoCConfig.php';

class Library {
  public function overView($version)
  {
    return IoCBook::license($version);
  }
}

$l = new Library;
$l->overView("2.0");

