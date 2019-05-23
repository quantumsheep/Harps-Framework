<?php
namespace Harps\Core;

class Handler
{
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
        if (GET_ALL_ERRORS == false) {
            if (!(error_reporting() & $errno)) {
                // Ce code d'erreur n'est pas inclus dans error_reporting()
                return null;
            }
        }

        $type = 'Error';

        ob_end_clean();
        if (DEV == true) {
            if (defined("FILE_ERROR_500_DEV") && file_exists(FILE_ERROR_500_DEV)) {
                require FILE_ERROR_500_DEV;
            } else {
                echo $errstr . "<br />";
                echo $errfile . " line " . $errline;
            }
        } else {
            if (defined("FILE_ERROR_500") && file_exists(FILE_ERROR_500)) {
                require FILE_ERROR_500;
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
    public static function Exception_Handler($e)
    {
        $type = 'Exception';

        ob_end_clean();
        if (DEV == true) {
            if (defined("FILE_ERROR_500_DEV") && file_exists(FILE_ERROR_500_DEV)) {
                require FILE_ERROR_500_DEV;
            } else {
                echo "<pre>" . self::ExceptionTracing($e) . "</pre>";
            }
        } else {
            if (defined("FILE_ERROR_500") && file_exists(FILE_ERROR_500)) {
                require FILE_ERROR_500;
            } else {
                header('HTTP/1.1 500 Internal Server Error');
            }
        }

        exit(1);
    }

    /**
     * Register the Handlers
     */
    public static function register()
    {
        $handler_env = "\\" . __CLASS__ . "::";
        set_error_handler($handler_env . "Error_Handler");
        set_exception_handler($handler_env . "Exception_Handler");
    }

    private static function ExceptionTracing($e, $seen = null)
    {
        $starter = $seen ? '<span>Caused by: </span>' : '';

        $result = array();
        if (!$seen) {
            $seen = array();
        }

        $trace = $e->getTrace();
        $prev = $e->getPrevious();

        $result[] = sprintf('<span>%s%s: %s</span>', $starter, get_class($e), $e->getMessage());

        $file = $e->getFile();
        $line = $e->getLine();

        while (true) {
            if (is_array($seen) && in_array("$file:$line", $seen)) {
                $result[] = sprintf('<span>... %d more</span>', count($trace) + 1);
                break;
            }
            $result[] = sprintf('<span>at %s%s%s(%s%s%s)</span>',
                count($trace) && array_key_exists('class', $trace[0]) ? str_replace('\\', '.', $trace[0]['class']) : '',
                count($trace) && array_key_exists('class', $trace[0]) && array_key_exists('function', $trace[0]) ? '.' : '',
                count($trace) && array_key_exists('function', $trace[0]) ? str_replace('\\', '.', $trace[0]['function']) : '(main)',
                $line === null ? $file : basename($file),
                $line === null ? '' : ':',
                $line === null ? '' : $line);
            if (is_array($seen)) {
                $seen[] = "$file:$line";
            }
            if (!count($trace)) {
                break;
            }
            $file = array_key_exists('file', $trace[0]) ? $trace[0]['file'] : 'Unknown Source';
            $line = array_key_exists('file', $trace[0]) && array_key_exists('line', $trace[0]) && $trace[0]['line'] ? $trace[0]['line'] : null;
            array_shift($trace);
        }
        $result = join("\n", $result);
        if ($prev) {
            $result .= "\n" . self::ExceptionTracing($prev, $seen);
        }

        return $result;
    }
}
