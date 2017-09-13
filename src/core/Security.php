<?php
namespace Harps\Core;

class Security
{
    /**
     * Generate a new CSRF if it doesn't already exists
     * @return mixed
     */
    public static function csrf_gen()
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    /**
     * Generate a new CSRF
     * @return string
     */
    public static function csrf_regen()
    {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        return $_SESSION['csrf_token'];
    }

    /**
     * Check the validity of a token
     * @param string $token The posted token to check
     * @param bool $endProgram If true, will throw an Exception with the reason of the error
     * @return bool
     */
    public static function csrf_check(string $token, bool $endProgram = false)
    {
        if (!empty($_SESSION['csrf_token'])) {
            if (hash_equals($_SESSION['csrf_token'], $token)) {
                return true;
            } else {
                if ($endProgram == true) {
                    $backtrace = debug_backtrace()[0];
                    throw new \Exception("The given CSRF Token doesn't match with the session one" . "|||" . $backtrace["file"] . " line " . $backtrace["line"]);
                }
            }
        } else {
            if ($endProgram == true) {
                $backtrace = debug_backtrace()[0];
                throw new \Exception("The CSRF Token doesn't exist in the session" . "|||" . $backtrace["file"] . " line " . $backtrace["line"]);
            }
        }

        return false;
    }
}
