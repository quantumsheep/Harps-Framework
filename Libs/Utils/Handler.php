<?php
namespace Libs\Utils;

class Handler {
    /**
     * Handler for all errors
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
                ob_end_clean();
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
                ob_end_clean();
                header('HTTP/1.1 500 Internal Server Error');
            }
        }

        exit(1);
    }
}