<?php
namespace Harps\Client;

class Cookie
{
    /**
     * Set a cookie value
     * @param array $cookies Cookie(s) to set, the array's key must be the cookie's name and for its value: array(string value, int expire, string path, string domain, bool secure, bool httponly).
     */
    public function set(array $cookies)
    {
        if (count($cookies) > 0) {
            foreach ($cookies as $key => $value) {
                if (is_array($value) && isset($value[0]) && is_string($value[0])) {
                    if ($value[1] != null && $value[1] != 0) {
                        $value[1] = time() + $value[1];
                    }

                    $cookie_info[] = $key;
                    foreach ($value as $cookie_val) {
                        $cookie_info[] = $cookie_val;
                    }

                    \call_user_func_array("setcookie", $cookie_info);
                }
            }
        }

        return $this;
    }

    /**
     * Get cookie(s) value
     * @param array|string $cookies Cookie(s) to get
     * @return mixed
     */
    public function get($cookies)
    {
        if (is_array($cookies)) {
            $items = array();

            foreach ($cookies as $cookie) {
                if (is_array($cookie)) {
                    $backtrace = debug_backtrace()[0];
                    throw new \InvalidArgumentException("You can't use sub-array to get a cookie, only strings" . "|||" . $backtrace["file"] . " line " . $backtrace["line"]);
                }

                if (!empty($_COOKIE[$cookie])) {
                    $items[$cookie] = $_COOKIE[$cookie];
                } else {
                    $items[$cookie] = null;
                }
            }
        } else {
            if (!empty($_COOKIE[$cookies])) {
                $items = $_COOKIE[$cookies];
            } else {
                $items = null;
            }
        }

        return $items;
    }

    /**
     * Delete a session object.
     * @param array|string $cookies Object(s) to delete
     */
    public function delete($cookies)
    {
        if (is_array($cookies)) {
            foreach ($cookies as $cookie) {
                if (isset($_COOKIE[$cookie])) {
                    unset($_COOKIE[$cookie]);
                    \setcookie($cookie, "", time() - 3600, '/');
                }
            }
        } else {
            if (isset($_COOKIE[$cookies])) {
                unset($_COOKIE[$cookies]);
                \setcookie($cookies, "", time() - 3600, '/');
            }
        }

        return $this;
    }
}
