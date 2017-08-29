<?php
namespace Harps\Controller;

class View {
    /**
     * Return a view
     * @param mixed $view The simple view name like 'index' or '/doc/harps', file extension will be completed by itself
     * @param mixed $var The data to send to the view
     * @throws \Exception
     * @return array
     */
    public static function Load($view, $var) {
        if(glob(DIR_VIEWS . $view . ".*")) {
            return [$view, $var];
        } else {
            $backtrace = debug_backtrace()[0];
            throw new \Exception("View not found : " . $view . "|||" . $backtrace["file"] . " line " . $backtrace["line"]);
        }
    }
}