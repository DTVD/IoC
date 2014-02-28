<?php

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
    public static function license($version)
    {
        return "Licensed book version".$version;
    }
}

