<?php
session_start();

require_once(__DIR__ . "/Config/Parameters.php");

require_once(DIR_LIBS . "Utils/Handler.php");
set_error_handler("\\Libs\\Utils\\Handler::Error_Handler");
set_exception_handler("\\Libs\\Utils\\Handler::Exception_Handler");

require_once(DIR_ROOT . "vendor/autoload.php");
require_once(DIR_LIBS . "Autoloader.php");
Autoloader::register();

require_once(DIR_CONFIG . "Routes.php");