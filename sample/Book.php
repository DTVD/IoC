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
}

// Register sample. Here $book1 and $book2 will end up with seperated instances of class Book
IoC::register('book',function(){
    $book = new Book;
    $book->setAuthor('DTVD');
    return $book;
});

$book1 = IoC::resolve('book');
$book2 = IoC::resolve('book');
echo "Register sample\n";
var_dump($book1()===$book2());

// Singleton sample. Here $bookSingleton1 and $bookSingleton2 will end up with same instances of class Book
IoC::singleton('bookSingleton',function(){
    $book = new Book;
    $book->setAuthor('DTVD');
    return $book;
});

$bookSingleton1 = IoC::resolve('bookSingleton');
$bookSingleton2 = IoC::resolve('bookSingleton');
echo "Singleton sample\n";
var_dump($bookSingleton1===$bookSingleton2);