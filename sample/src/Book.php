<?php

require_once __DIR__ . '/../vendor/autoload.php';

class Book {
    public $author;
    public function setAuthor($author)
    {
        $this->author = $author;
    }
    public function getAuthor()
    {
        return $this->author;
    }
    /* Evil static method */
    public static function license($version)
    {
        return "Licensed book version".$version;
    }
}

