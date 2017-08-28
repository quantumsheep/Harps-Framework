<?php
/**
 *
 * PATHS
 * /!\ If you change the paths, don't forget to also change the "use" and "namespace" path in all files that need to be changed
 *
 */

define("DEV", true);
define("GET_ALL_ERRORS", false);

define("DIR_ROOT", dirname(__DIR__) . "/");
define("DIR_APP", DIR_ROOT . "App/");
define("DIR_LIBS", DIR_ROOT . "Libs/");
define("DIR_CONFIG", DIR_ROOT . "Config/");

define("DIR_VIEWS", DIR_APP . "Views/");
define("DIR_CONTROLLERS", DIR_APP . "Controllers/");
define("DIR_MANAGERS", DIR_APP . "Managers/");
define("DIR_MODELS", DIR_APP . "Models/");

define("DIR_BLADE_CACHE", DIR_LIBS . 'Others/Blade/Cache/');

define("DIR_EXCEPTIONS_VIEWS", DIR_VIEWS . "Exceptions/");
define("FILE_ERROR_500", DIR_EXCEPTIONS_VIEWS . "500.php");
define("FILE_ERROR_500_DEV", DIR_EXCEPTIONS_VIEWS . "500-DEV.php");


/**
 *
 * DATABASE
 *
 */

define("DB_HOST", "localhost");
define("DB_PORT", "9956");
define("DB_USER", "root");
define("DB_PASS", "koala");
define("DB_NAME", "harps");
define("DB_HOST_PORT", DB_HOST . ":" . DB_PORT);