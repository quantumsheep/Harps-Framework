<?php
namespace Harps\Client;

class Session
{
    /**
     * Set a session object
     * @param array $objects Object(s) to add to the session, key as session's object name and value as session's object value.
     */
    public static function set(array $objects)
    {
        foreach ($objects as $key => $value) {
            $_SESSION[$key] = $value;
        }

        return new Session();
    }

    /**
     * Get session's object(s) value
     * @param array|string $objects Session's object(s) to get
     * @return mixed
     */
    public static function get($objects)
    {
        if (is_array($objects)) {
            $items = array();

            foreach ($objects as $obj) {
                if (is_array($obj)) {
                    $backtrace = debug_backtrace()[0];
                    throw new \InvalidArgumentException("You can't use sub-array to get a session object, only strings" . "|||" . $backtrace["file"] . " line " . $backtrace["line"]);
                }

                if (!empty($_SESSION[$obj])) {
                    $items[$obj] = $_SESSION[$obj];
                } else {
                    $items[$obj] = null;
                }
            }
        } else {
            if (!empty($_SESSION[$objects])) {
                $items = $_SESSION[$objects];
            } else {
                $items = null;
            }
        }

        return $items;
    }

    public static function push(array $objects)
    {
        foreach ($objects as $key => $value) {
            if (is_array($_SESSION[$key])) {
                $_SESSION[$key][] = $value;
            } else {
                throw new \InvalidArgumentException("For pushing the session object must be an initialized array");
            }
        }

        return new Session();
    }

    /**
     * Delete a session object.
     * @param array|string $objects Object(s) to delete
     */
    public static function delete($objects)
    {
        if (is_array($objects)) {
            foreach ($objects as $obj) {
                unset($GLOBALS[_SESSION][$obj]);
            }
        } else {
            unset($GLOBALS[_SESSION][$objects]);
        }

        return new Session();
    }

    /**
     * Completly destroy the session and all its data.
     * @param bool $regen If true, the session id will be regenerated after the session destroy
     */
    public static function destroy(bool $regen = false)
    {
        $_SESSION = array();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();

        if ($regen == true) {
            self::regenerate();
        }

        return new Session();
    }

    public static function regenerate()
    {
        if (session_status == PHP_SESSION_NONE) {
            session_start();
        }

        session_regenerate_id(true);

        return new Session();
    }
}
