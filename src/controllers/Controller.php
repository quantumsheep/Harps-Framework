<?php
namespace Harps\Controllers;

class Controller
{
    public static function run(string $controller, string $action, array $data = array())
    {
        return call_user_func_array("\\App\\Controllers\\" . $controller . "Controller" . "::" . $action, $data);
    }
}