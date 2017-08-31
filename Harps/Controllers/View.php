<?php
namespace Harps\Controllers;

class View
{
    /**
     * Directly load a view
     * @param string $view The simple view name like 'index' or '/doc/harps', file extension will be completed by itself
     * @param mixed $model The data to send to the view
     * @throws \Exception
     * @return array
     */
    public static function load(string $view, $model = null) {
        if(glob(DIR_VIEWS . $view . ".*")) {
            $blade = new \Philo\Blade\Blade(DIR_VIEWS, DIR_BLADE_CACHE);
            echo $blade->view()->make($view)->with("model", $model)->render();
        } else {
            $backtrace = debug_backtrace()[0];
            throw new \Exception("View not found : " . $view . "|||" . $backtrace["file"] . " line " . $backtrace["line"]);
        }
    }
}