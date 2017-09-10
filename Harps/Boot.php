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

        require_once(dirname(__DIR__) . "/Config/Parameters.php");
		
		if(!file_exists(DIR_BLADE_CACHE)) {
			Directories::create(DIR_BLADE_CACHE);
		}
		
        require_once(DIR_HARPS . "Helpers.php");
        Harps\Core\Handler::register();

        require_once(DIR_CONFIG . "Routes.php");

        if(isset($GLOBALS["ROUTED"]) && $GLOBALS["ROUTED"] != true) {
            Harps\Core\Route::RedirectToView('/Exceptions/404');
        }
    }
}