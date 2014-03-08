<?php namespace orakaro\IoC\core;

/**
 * Inversion Of Control Class
 */
class IoC {
  protected static $registry = array();
  protected static $shared = array();

  // Register
  public static function register($name, \Closure $resolve)
  {
     static::$registry[$name] = $resolve;
  }

  // Singleton
  public static function singleton($name, \Closure $resolve)
  {
    static::$shared[$name] = $resolve();
  }

  // Resolve, consider register or singleton here
  public static function resolve($name)
  {
    if ( static::registered($name) )
    {
      $name = static::$registry[$name];
      return $name;
    }

    if ( static::singletoned($name) )
    {
      $instance = static::$shared[$name];
      return $instance;
    }

    throw new \Exception('IoC register exception.');
  }

  // Check resigtered or not
  public static function registered($name)
  {
    return array_key_exists($name, static::$registry);
  }

  // Check singleton object or not
  public static function singletoned($name)
  {
    return array_key_exists($name, static::$shared);
  }

}
