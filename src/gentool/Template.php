<?php namespace orakaro\IoC\gentool;

class Template{

    /* Content static varable */
    public static $content;

    /* Content generator */
    public static function genIoCConfig($config_dir, $staticClasses)
    {
        $depth = count(explode('/', $config_dir));
        $autoload_path = str_repeat('/..', $depth). '/vendor/autoload.php';
        ContentBuilder::bootstrap($autoload_path);

        foreach ($staticClasses as $class => $method) {
            self::$content .= ContentBuilder::register($class, $method);
            self::$content .= ContentBuilder::replace($class, $method);
        }

        $filePath = $config_dir.'/IoCConfig.php';
        return self::writeToFile($filePath);

    }

    /* Clean */
    public static function clean($config_dir)
    {
        $filePath = $config_dir.'/IoCConfig.php';
        $success = $success ?: "Delete : $filePath.\n";
        /* Delete file */
        if ( !unlink($filePath) ){
            return "Oops - something went wrong!\n";
        } else {
            $rel = $success;
        };
        /* Delete folder */
        if ( !rmdir($config_dir) ){
            return "Oops - something went wrong!\n";
        } else {
            return $rel;
        };
    }

    /* Write To File function  */
    public static function writeToFile($filePath)
    {
        $success = $success ?: "Create: $filePath.\n";

        if ( file_exists($filePath) ) {
            return "Warning: File already exists at $filePath\n";
        }

        mkdir(dirname($filePath));

        if ( file_put_contents($filePath, self::$content) !== false ) {
            return $success;
        } else {
            return "Oops - something went wrong!\n";
        }
    }
}

class ContentBuilder{

    /* Bootstrap */
    public static function bootstrap($autoload_path)
    {
        $content = <<<EOT
<?php
require_once __DIR__ . '{$autoload_path}';
use orakaro\\IoC\core\\IoC;
\n
/* Wake up lazy loading */
class Touchy {
    public static function wakeMeUp()
    {
    }
EOT;
        Template::$content = $content;
    }

    /* Production Register */
    public static function register($class,$method)
    {
        return <<<EOT
\n
/* Production register */
IoC::register('{$class}_{$method}', function(){
    return {$class}::{$method}();
});
EOT;
    }

    /* Replacing real class */
    public static function replace($class,$method)
    {
        return <<<EOT
\n
/* Replacing real classes */
class IoC{$class}{
    public static function {$method}()
    {
        \$registedClosure = IoC::resolve('{$class}_{$method}');
        return \$registedClosure();
    }
}
EOT;
    }

}