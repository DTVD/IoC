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

        /* Append IoC register */
        foreach ($staticClasses as $class => $methods) {
            $methodCollection = is_array($methods) ? $methods : array($methods);
            self::$content .= ContentBuilder::register($class, $methodCollection);
        }

        /* Append IoC class */
        foreach ($staticClasses as $class => $methods) {
            $methodCollection = is_array($methods) ? $methods : array($methods);
            self::$content .= ContentBuilder::replace($class, $methodCollection);
        }

        $filePath = $config_dir.'/IoCConfig.php';
        return self::writeToFile($filePath);

    }

    /* Clean */
    public static function clean($config_dir)
    {
        $filePath = $config_dir.'/IoCConfig.php';
        /* Delete file */
        if ( !unlink($filePath) ){
            return "Oops - Cannnot delete file !\n";
        } else {
            $rel = "Delete : $filePath.\n";
        };
        /* Delete folder */
        if ( !rmdir($config_dir) ){
            return "Oops - Cannot delete directory !\n";
        } else {
            return $rel;
        };
    }

    /* Write To File function  */
    public static function writeToFile($filePath)
    {
        if ( file_exists($filePath) ) {
            return "Warning: File already exists at $filePath\n";
        }

        mkdir(dirname($filePath));

        if ( file_put_contents($filePath, self::$content) !== false ) {
            return "Create: $filePath.\n";
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
    public static function register($class,$methodCollection)
    {
        /* Init */
        $content = '';
        /* Loop methodCollection */
        foreach ($methodCollection as $method) {
        $content .= <<<EOT
\n
/* IoC register for {$class}::{$method} */
IoC::register('{$class}_{$method}', function(){
    return {$class}::{$method}();
});
EOT;
        }
        return $content;
    }

    /* Replacing real class */
    public static function replace($class,$methodCollection)
    {
        /* Class define */
        $content = <<<EOT
\n
/* Create IoC class for {$class} */
class IoC{$class}{
\n
EOT;
        /* Loop methodCollection */
        foreach ($methodCollection as $method) {
            $content .= <<<EOT
    public static function {$method}()
    {
        \$registedClosure = IoC::resolve('{$class}_{$method}');
        return \$registedClosure();
    }
\n
EOT;
        }
        /* Close class */
        $content .= <<<EOT
}
EOT;

        return $content;
    }

}