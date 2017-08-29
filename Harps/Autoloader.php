<?php
class Autoloader
{
    /**
     * Reegister the autoload function
     */
    public static function register(){
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    public static function autoload($class) {
        $path = explode('\\', $class);
        $class = implode('/', $path);
        $classToFind = $path[sizeof($path) - 1];

        if(file_exists(DIR_ROOT . $class.'.php')) {
            if(!class_exists(($classToFind), false))
                require DIR_ROOT . $class.'.php';
        } else {
            $backtrace = debug_backtrace()[1];
            throw new Exception("Class " . str_replace('/', '\\', $class) . " not found!" . "|||" . $backtrace["file"] . " line " . $backtrace["line"]);
        }
    }
}