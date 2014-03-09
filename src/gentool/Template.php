<?php namespace orakaro\IoC\gentool;

class Template{

    /* Content static varable */
    public static $content;
    public static $namespace;
    public static $autoloadpath;

    /* Content generator */
    public static function genIoCConfig($config_dir, $staticClasses)
    {
        /* Output */
        $output= '';
        if (!file_exists($config_dir)){
            mkdir($config_dir, 0777, true);
        }

        /* Bootstrap IoCRegister.php */
        $ary = explode('/', $config_dir);
        self::$autoloadpath = str_repeat('/..', count($ary)). '/vendor/autoload.php';
        self::$namespace = implode('\\', $ary);
        self::$content = ContentBuilder::bootstrap();

        /* Append IoC registers */
        foreach ($staticClasses as $class => $methods) {
            /* Check if $methodCollection has one or more method */
            $methodCollection = is_array($methods) ? $methods : array($methods);
            self::$content .= ContentBuilder::register($class, $methodCollection);
        }

        /* Finish IoCRegister.php */
        self::$content .= ContentBuilder::finish();

        /* Write IoCRegister.php */
        $IoCRegisterFilePath = $config_dir.'/IoCRegister.php';
        $output.= self::writeToFile($IoCRegisterFilePath);

        /* Clear content */
        self::$content='';

        /* Write IoC class files */
        foreach ($staticClasses as $class => $methods) {
            /* Check if $methodCollection has one or more method */
            $methodCollection = is_array($methods) ? $methods : array($methods);
            self::$content .= ContentBuilder::replace($class, $methodCollection);
            $filePath = $config_dir.'/'.$class.'.php';
            $output .= self::writeToFile($filePath);
            self::$content='';
        }

        /* Output to console */
        return $output;
    }

    /* Clean */
    public static function clean($config_dir)
    {
        $output = '';
        if ( !file_exists($config_dir) ) {
            return "Directory \"$config_dir\" is not exists!\n";
        }
        /* Loop all path */
        foreach(new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($config_dir,\FilesystemIterator::SKIP_DOTS),
                    \RecursiveIteratorIterator::CHILD_FIRST)
                as $path) {
            if ($path->isFile()) {
                $item = $path->getPathname();
                /* Delete all files */
                if ( !unlink($item) ){
                    return "Oops - Cannnot delete file !\n";
                } else {
                    $output .= "Deleted file : $item.\n";
                };
            } else {
                $dir = $path->getPathname();
                /* Delete folder */
                if ( !rmdir($dir) ){
                    return "Oops - Cannot delete directory !\n";
                } else {
                    $output .= "Deleted dir : $dir.\n";
                };
            }
        }
        rmdir($config_dir);
        return $output;
    }

    /* Write To File function  */
    public static function writeToFile($filePath)
    {
        if ( file_exists($filePath) ) {
            return "Warning: File already exists at $filePath\n";
        }

        if ( file_put_contents($filePath, self::$content) !== false ) {
            return "Create: $filePath.\n";
        } else {
            return "Oops - something went wrong!\n";
        }
    }
}

class ContentBuilder{

    /* Bootstrap */
    public static function bootstrap()
    {
        $namespace = Template::$namespace;
        $autoloadpath = Template::$autoloadpath;
        return <<<EOT
<?php namespace {$namespace};
require_once __DIR__ . '{$autoloadpath}';
use orakaro\\IoC\core\\IoC;
\n
class IoCRegister{
    public static function registerAll()
    {
EOT;
    }

    /* Get class name from namespaces */
    public static function getClassName($namespacepath)
    {
        $ary = explode('\\', $namespacepath);
        $className = count($ary)==1 ? '\\'.$ary[0] : $namespacepath;
        return $className;
    }

    /* Production Register */
    public static function register($class,$methodCollection)
    {
        /* Init */
        $content = '';
        $className = self::getClassName($class);
        /* Loop methodCollection */
        foreach ($methodCollection as $method) {
            $arguments = self::getArguments($class,$method);
            $content .= <<<EOT
\n
    /* IoC register for {$class}::{$method} */
    IoC::register('{$class}_{$method}', function({$arguments}){
        return {$className}::{$method}($arguments);
    });
EOT;
        }
        return $content;
    }

    /* Finish */
    public static function finish()
    {
        return <<<EOT
\n
    }
}
EOT;
    }

    /* Get arguments */
    public static function getArguments($class,$method)
    {
        $r = new \ReflectionMethod($class,$method);
        $params = $r->getParameters();
        $arguments = '';
        foreach ($params as $param) {
            $name = $param->getName();
            if ($param->isOptional()) {
                $value = $param->getDefaultValue();
                $arguments .=  '$'.$name.'='.$value.',';
            } else {
                $arguments .=  '$'.$name. ',';
            }
        }
        return rtrim($arguments,',');

    }

    /* Replacing real class */
    public static function replace($class,$methodCollection)
    {
        $namespace = Template::$namespace;
        $autoloadpath = Template::$autoloadpath;
        /* Class define */
        $content = <<<EOT
<?php namespace {$namespace};
require_once __DIR__ . '{$autoloadpath}';
use orakaro\\IoC\core\\IoC;
\n
/* Create IoC class for {$class} */
class {$class}{
\n
EOT;
        /* Loop methodCollection */
        foreach ($methodCollection as $method) {
            $arguments = self::getArguments($class,$method);
            $content .= <<<EOT
    public static function {$method}({$arguments})
    {
        \$registedClosure = IoC::resolve('{$class}_{$method}');
        return \$registedClosure($arguments);
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