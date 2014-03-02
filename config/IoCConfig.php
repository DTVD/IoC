<?php
require_once __DIR__ . '/../vendor/autoload.php';
use orakaro\IoC\core\IoC;


/* Wake up lazy loading */
class Touchy {
    public static function wakeMeUp()
    {
    }

/* Production register */
IoC::register('Book_license', function(){
    return Book::license();
});

/* Replacing real classes */
class IoCBook{
    public static function license()
    {
        $registedClosure = IoC::resolve('Book_license');
        return $registedClosure();
    }
}