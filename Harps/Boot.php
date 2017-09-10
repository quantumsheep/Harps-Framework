<?php
use Harps\FilesUtils\Directories;

class Boot
{
    /**
     * Start the Harps Framework's
     */
    public static function Harps() {
        if (!session_id()) {
            session_start();
        }

        Harps\Core\Handler::register();

        if(!file_exists(DIR_BLADE_CACHE)) {
			Directories::create(DIR_BLADE_CACHE);
        }
        
        require_once(DIR_CONFIG . "Routes.php");

        if(isset($GLOBALS["ROUTED"]) && $GLOBALS["ROUTED"] != true) {
            Harps\Core\Route::RedirectToView('/Exceptions/404');
        }
    }
}