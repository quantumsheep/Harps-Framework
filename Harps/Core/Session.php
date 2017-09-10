<?php
namespace Harps\Core;

class Session
{
    /**
     * Set a session object
     * @param array $objects Objects to add to the session, key as session object name and value as session object value.
     */
    public function set(array $objects) {
        foreach($objects as $key => $value) {
            $_SESSION[$key] = $value;
        }

        return $this;
    }

    /**
     * Get session's object(s) value
     * @param array|string $objects
     * @return mixed
     */
    public function get($objects) {
        if(is_array($objects)) {
            $items = array();

            foreach($objects as $obj) {
                if(is_array($obj)) {
                    $backtrace = debug_backtrace()[0];
                    throw new \InvalidArgumentException("You can't use sub-array to get a session object, only strings" . "|||" . $backtrace["file"] . " line " . $backtrace["line"]);
                }

                if(!empty($_SESSION[$obj])) {
                    $items[$obj] = $_SESSION[$obj];
                } else {
                    $items[$obj] = null;
                }
            }
        } else {
            if(!empty($_SESSION[$objects])) {
                $items = $_SESSION[$objects];
            } else {
                $items = null;
            }
        }

        return $items;
    }

    /**
     * Delete a session object.
     * @param array|string $objects Object(s) to delete
     */
    public function delete($objects) {
        if(is_array($objects)) {
            foreach($objects as $obj) {
                unset($GLOBALS[_SESSION][$obj]);
            }
        } else {
            unset($GLOBALS[_SESSION][$objects]);
        }

        return $this;
    }

    /**
     * Completly destroy the session and all its data.
     * @param bool $regen If true, the session id will be regenerated after the session destroy
     */
    public function destroy(bool $regen = false) {
        $_SESSION = array();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();

        if($regen == true) {
            $this->regenerate();
        }

        return $this;
    }

    public function regenerate() {
        if(session_status == PHP_SESSION_NONE) {
            session_start();
        }

        session_regenerate_id(true);

        return $this;
    }
}