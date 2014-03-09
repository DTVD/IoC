<?php

return array(
  /* Bunch of files will be saved to $config_dir */
  /* You will have to add a psr-4 define in your composer.json autoload section */
  /* See example at "sample folder" */
  'config_dir' => 'config/facade',
  /* List of classes and static methods that you want to mock */
  /* Key is class name and Value is method. Class can use with array of Methods */
  'static_classes' => array(
    'Gandalf' => 'knownAs',
    'Thorin' => 'knownAs',
    'Bilbo' => 'knownAs',
    'Thranduil' => 'knownAs',
    'Smaug' => 'knownAs',
    'Bard' => 'knownAs',
  ),
);