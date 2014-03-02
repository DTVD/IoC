<?php namespace orakaro\IoC\gentool;

require_once __DIR__ . '/../../vendor/autoload.php';
class Template {
    public static function test($class_name, $test)
    {
        return <<<EOT
    public function test_{$test}()
    {
        \$response = Controller::call('{$class_name}@$test'); 
        \$this->assertEquals('200', \$response->foundation->getStatusCode());
        \$this->assertRegExp('/.+/', (string)\$response, 'There should be some content in the $test view.');
    }
EOT;
    }


    public static function func($func_name)
    {
        return <<<EOT
    public function {$func_name}()
    {

    }
EOT;
    }


    public static function new_class($name, $extends_class = null)
    {
        $content = "<?php class $name";
        if ( !empty($extends_class) ) {
            $content .= " extends $extends_class";
        }

        $content .= ' {}';

        Generate_Task::$content = $content;
    }


    public static function schema($table_action, $table_name, $cb = true)
    {
        $content = "Schema::$table_action('$table_name'";

        return $cb
            ? $content . ', function($table) {});'
            : $content . ');';
    }

}