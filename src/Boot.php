<?php
use Harps\Controllers\View;
use Harps\Core\Handler;
use Harps\FilesUtils\Directories;
use Harps\Routing\Route;

class Boot
{
    private static function define_vars()
    {
        $to_define = array(
            "CURRENT_URI" => Route::get_current_uri(),
        );

        $to_global = array(
            "ROUTED" => false,
            "ASSETS" => array(),
            "ASSET_ACCEPTED" => "ACCEPTED",
        );

        foreach ($to_define as $key => $value) {
            if (!defined($key)) {
                define($key, $value);
            }
        }

        foreach ($to_global as $key => $value) {
            $GLOBALS[$key] = $value;
        }
    }

    /**
     * Start the Harps Framework's
     */
    public static function Harps()
    {
        if (!session_id()) {
            session_start();
        }

        Handler::register();

        if (!file_exists(DIR_BLADE_CACHE)) {
            Directories::create(DIR_BLADE_CACHE, 0777);
        }

        self::define_vars();

        require_once DIR_CONFIG . "Assets.php";
        require_once DIR_CONFIG . "Routes.php";

        if (isset($GLOBALS["ROUTED"]) && $GLOBALS["ROUTED"] != true) {
            View::load('/Exceptions/404');
        }
    }
}
