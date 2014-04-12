###Inversion Of Control  [![Build Status](https://travis-ci.org/DTVD/IoC.svg?branch=master)](https://travis-ci.org/DTVD/IoC)

_"Don't call me let me call you"_
* Have you ever heard about Inversion Of Control / Dependency Injection in PHP ?
* Know some giants like [Symfony Service Container](http://symfony.com/doc/current/book/service_container.html) or [Laravel Facades ](http://laravel.com/docs/facades) ?
* Wishing you could use Inversion Of Control (IoC) and Facade Design Pattern in your legacy source code ?


I think I can help you a little bit ! Let's start :)

###Quick Start

This tiny packge will provide an easy and lightweight way to implement IoC
* Include following lines in your `composer.json`
```json
    "require": {
        "orakaro/ioc": "dev-master"
    }
```
* Get classes autoloaded
```bash
composer dump-autoload
```
* Done ! Now head over Usage section !

###Usage


Let me assume that you have some class like
```PHP
<?php
class Library{
  public function overview()
  {
    $book = Book::getTitle($ISBN);
    return $book;
  }

```
Note that ```overview``` method is using ```Book```class's static method ```getTItle``` here. 

Now let's create another file call IoCRegister.php 
```PHP
require_once __DIR__ . '/vendor/autoload.php';//Shoule be correct path to composer autoload!
use orakaro\IoC\core\IoC;

class IoCRegister{
  public static function registerAll()
  {
    /* IoC register for Book::getTitle */
    IoC::register('Book_getTitle', function($ISBN){
        return Book::getTitle($ISBN);
    });
  }
}

class IoCBook{
  public static function getTitle($ISBN)
  {
    $registedClosure = IoC::resolve('Book_getTitle');
    return $registedClosure($ISBN);
  }
}
```

Quite simple, right ? Now you can use ```IoCBook::getTItle($ISBN)``` instead as below
```PHP
<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . 'IoCRegister.php';
class Library{
  public function overview()
  {
    $book = IoCBook::getTitle($ISBN);
    return $book;
  }
```

###Why I need all that things ?

The idea of IoC help us solve many problem like remove dependencies in codes, remove duplication, etc.. 
With me it make sense especially in unittesting, where sometimes I have to face with some evil-static-call in classes.

In PHPUnit it's never easy to deal with static methods. 
[Static methods are just death to testability](http://misko.hevery.com/2008/12/15/static-methods-are-death-to-testability/), and PHPUnit have the capablility of [mocking static methods where caller and callee are in the same class. ](http://sebastian-bergmann.de/archives/883-Stubbing-and-Mocking-Static-Methods.html) 

How about when caller and callee in __seperated classes__ ?
With IoC implemented, I will help you to overcome that headache in a few basic steps :)

Check out [one-minute tutorial](https://github.com/DTVD/Misty-Mountains-Cold) !





[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/DTVD/ioc/trend.png)](https://bitdeli.com/free "Bitdeli Badge")

