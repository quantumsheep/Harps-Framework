<?php
namespace Harps\Controllers;

class Controller
{
    /**
     * Check and return a view value
     * @param string $view The simple view name like 'index' or '/doc/harps', file extension will be completed by itself
     * @param mixed $var The data to send to the view
     * @throws \Exception
     */
    protected static function view(string $view, $var) {
        if(glob(DIR_VIEWS . $view . ".*")) {
            return array($view, $var);
        } else {
            $backtrace = debug_backtrace()[0];
            throw new \Exception("View not found : " . $view . "|||" . $backtrace["file"] . " line " . $backtrace["line"]);
        }
    }
}