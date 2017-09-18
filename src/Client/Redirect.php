<?php
namespace Harps\Client;

use Harps\Controllers\Controller;

class Redirect
{
    public static function to_url(string $url)
    {
        header("Location: " . $url);
        exit();
    }

    public static function to_controller(string $controller, string $action)
    {
        Controller::run($controller, $action);
    }
}
