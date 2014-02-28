<?php

require_once '../config/IoCConfig.php';

class Library {
  public function overView($version)
  {
    $license = IoCBook::license($version);
    return $license. " in overview";
  }
}

$l = new Library;
echo $l->overView("2.0");

