<?php
namespace Harps\Core;

class Handler {
    /**
     * Handler for all Errors
     * @param mixed $errno
     * @param mixed $errstr
     * @param mixed $errfile
     * @param mixed $errline
     * @param mixed $errcontext
     * @return void
     */
    public static function Error_Handler($errno, $errstr, $errfile, $errline, $errcontext)
    {
        if(GET_ALL_ERRORS == false) {
            if (!(error_reporting() & $errno)) {
                // Ce code d'erreur n'est pas inclus dans error_reporting()
                return null;
            }
        }

        $type = 'Error';

        ob_end_clean();
        if(DEV == true) {
            if(defined("FILE_ERROR_500_DEV") && file_exists(FILE_ERROR_500_DEV)) {
                require(FILE_ERROR_500_DEV);
            } else {
                echo $errstr . "<br />";
                echo $errfile . " line " . $errline;
            }
        } else {
            if(defined("FILE_ERROR_500") && file_exists(FILE_ERROR_500)) {
                require(FILE_ERROR_500);
            } else {
                header('HTTP/1.1 500 Internal Server Error');
            }
        }

        exit(1);
    }

    /**
     * Handler for all Exceptions
     * @param mixed $e
     */
    public static function Exception_Handler($e) {
        $type = 'Exception';

        ob_end_clean();
        if(DEV == true) {
            if(defined("FILE_ERROR_500_DEV") && file_exists(FILE_ERROR_500_DEV)) {
                require(FILE_ERROR_500_DEV);
            } else {
                echo get_class($e) . " : ";
                if($e_backtrace = explode('|||', $e->getMessage())) {
                    echo $e_backtrace[0] . "<br />";
                    echo $e_backtrace[1];
                } else {
                    echo $e->getMessage() . "<br />";
                    echo $e->getFile() . " line " . $e->getLine();
                }
            }
        } else {
            if(defined("FILE_ERROR_500") && file_exists(FILE_ERROR_500)) {
                require(FILE_ERROR_500);
            } else {
                header('HTTP/1.1 500 Internal Server Error');
            }
        }

        exit(1);
    }

    /**
     * Register the Handlers
     */
    public static function register() {
        $handler_env = "\\" . __CLASS__ . "::";
        set_error_handler($handler_env . "Error_Handler");
        set_exception_handler($handler_env . "Exception_Handler");
    }
}