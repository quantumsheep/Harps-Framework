<?php
namespace Harps\Core;

use Harps\Controllers\View;

if(!defined("CURRENT_URI"))
    define("CURRENT_URI", Route::getCurrentUri());

if(!defined("ROUTED"))
    $GLOBALS["ROUTED"] = false;

class Route {
    private static $accept_route = false;
    private static $callback;
    private static $request;

    /**
     * Get the content from a specific URI
     * @param string $uri Requested URI to do the action
     * @param mixed $callback The action do to if the uri is valid
     */
    public static function get(string $uri, $callback) {
        if($_SERVER['REQUEST_METHOD'] == "GET") {
            self::getUnknownVar($uri, $request);

            if($uri == CURRENT_URI) {
                self::$callback = $callback;
                self::$request = $request;
                self::$accept_route = true;
            }
        }

        return new Route();
    }

    /**
     * Use the POST method from a specific URI
     * @param string $uri Requested URI to do the action
     * @param mixed $callback The action do to if the uri is valid
     */
    public static function post(string $uri, $callback) {
        if($_SERVER['REQUEST_METHOD'] == "POST") {
            self::getUnknownVar($uri, $request);

            if($uri == CURRENT_URI) {
                self::$callback = $callback;
                self::$request = $request;
                self::$accept_route = true;
            }
        }

        return new Route();
    }

    /**
     * Use the PUT method from a specific URI
     * @param string $uri Requested URI to do the action
     * @param mixed $callback The action do to if the uri is valid
     */
    public static function put(string $uri, $callback) {
        if($_SERVER['REQUEST_METHOD'] == "PUT") {
            self::getUnknownVar($uri, $request);

            if($uri == CURRENT_URI) {
                self::$callback = $callback;
                self::$request = $request;
                self::$accept_route = true;
            }
        }

        return new Route();
    }

    /**
     * Use the PATCH method from a specific URI
     * @param string $uri Requested URI to do the action
     * @param mixed $callback The action do to if the uri is valid
     */
    public static function patch(string $uri, $callback) {
        if($_SERVER['REQUEST_METHOD'] == "PATCH") {
            self::getUnknownVar($uri, $request);

            if($uri == CURRENT_URI) {
                self::$callback = $callback;
                self::$request = $request;
                self::$accept_route = true;
            }
        }

        return new Route();
    }

    /**
     * Use the DELETE method from a specific URI
     * @param string $uri Requested URI to do the action
     * @param mixed $callback The action do to if the uri is valid
     */
    public static function delete(string $uri, $callback) {
        if($_SERVER['REQUEST_METHOD'] == "DELETE") {
            self::getUnknownVar($uri, $request);

            if($uri == CURRENT_URI) {
                self::$callback = $callback;
                self::$request = $request;
                self::$accept_route = true;
            }
        }

        return new Route();
    }

    /**
     * Use the DELETE method from a specific URI
     * @param string $uri Requested URI to do the action
     * @param mixed $callback The action do to if the uri is valid
     */
    public static function options(string $uri, $callback) {
        if($_SERVER['REQUEST_METHOD'] == "OPTIONS") {
            self::getUnknownVar($uri, $request);

            if($uri == CURRENT_URI) {
                self::$callback = $callback;
                self::$request = $request;
                self::$accept_route = true;
            }
        }

        return new Route();
    }

    /**
     * Route if the client's requested method is one of the $method array
     * @param array $method Accepted methods
     * @param string $uri Requested URI to do the action
     * @param mixed $callback The action do to if the uri is valid
     */
    public static function match(array $method, string $uri, $callback) {
        if(in_array($_SERVER['REQUEST_METHOD'], array_map('strtoupper', $method))) {
            self::getUnknownVar($uri, $request);

            if($uri == CURRENT_URI) {
                self::$callback = $callback;
                self::$request = $request;
                self::$accept_route = true;
            }
        }

        return new Route();
    }

    /**
     * Route without checking client's requested method
     * @param string $uri Requested URI to do the action
     * @param mixed $callback The action do to if the uri is valid
     */
    public static function any(string $uri, $callback) {
        self::getUnknownVar($uri, $request);

        if($uri == CURRENT_URI) {
            self::$callback = $callback;
            self::$request = $request;
            self::$accept_route = true;
        }

        return new Route();
    }

    /**
     * Summary of group
     * @param array $uris The URIs allowed to do the callback action
     * @param mixed $callback The action do to if one of the uris are valid
     * @throws \InvalidArgumentException
     */
    public static function group($uris, $callback) {
        if(is_array($uris)) {
            foreach($uris as $uri => $method) {
                if($_SERVER['REQUEST_METHOD'] == strtoupper($method) || $method == "any") {
                    self::getUnknownVar($uri, $request);

                    if($uri == CURRENT_URI) {
                        self::$callback = $callback;
                        self::$request = $request;
                        self::$accept_route = true;
                    }

                    break;
                }
            }

            return new Route();
        } else {
            $backtrace = debug_backtrace()[0];
            throw new \InvalidArgumentException("The URIs must be an array" . "|||" . $backtrace["file"] . " line " . $backtrace["line"]);
        }
    }

    /**
     * Get the unknown variables from the routes like "/post/{n}"
     * @param mixed $uri URI used by the client
     * @param mixed $request The requested variable
     */
    private static function getUnknownVar(string &$uri, &$request) {
        $request = array();

        if(preg_match('/{(.*?)}/', $uri)) {
            $cutted_uri = explode('/', CURRENT_URI);
            $cutted_requested = explode('/', $uri);

            if(count($cutted_requested) == count($cutted_uri)) {
                for($i = 0; count($cutted_uri) != $i; $i++) {
                    if(preg_match('/{(.*?)}/', $cutted_requested[$i], $requested_vars)) {
                        $request[$requested_vars[1]] = $cutted_uri[$i];
                        $cutted_requested[$i] = $cutted_uri[$i];
                    }
                }
            }

            $uri = implode('/', $cutted_requested);
        }
    }

    /**
     * Define conditions to the unknown variables
     * @param array $conditions Unknown variables conditions for the route to be accepted, use like ["nb" => "[0-9]"]
     */
    public function where($conditions) {
        if(self::$accept_route == true) {
            foreach($conditions as $key => $condition) {
                if(!preg_match("/^" . $condition . "$/", self::$request[$key])) {
                    self::$accept_route = false;
                    break;
                }
            }
        }
    }

    /**
     * Redirect to the request
     * @param mixed $callback Route callback
     * @param mixed $request Route request
     */
    private static function routing($callback, $request) {
        if(is_callable($callback)) {
            if(!empty($request)) {
                call_user_func_array($callback, $request);
            } else {
                call_user_func($callback);
            }
        } else if(strpos($callback, '@') !== false) {
            $callback = explode('@', $callback);

            $result = ("\\App\\Controllers\\" . $callback[0] . "Controller")::{$callback[1]}($request);

            if(isset($result) && !empty($result)) {
                View::load($result[0], $result[1]);
            }
        } else {
            $backtrace = debug_backtrace()[2];
            throw new \InvalidArgumentException("Callback must be a callable or a controller string" . "|||" . $backtrace["file"] . " line " . $backtrace["line"]);
        }

        $GLOBALS["ROUTED"] = true;
    }

    /**
     * Redirection function to a view
     * @param mixed $view View to redirect to
     * @param mixed $model Content to send to the view
     */
    public static function RedirectToView($view, $model = null) {
        $blade = new \Philo\Blade\Blade(DIR_VIEWS, DIR_BLADE_CACHE);
        echo $blade->view()->make($view)->with("model", $model)->render();
    }

    /**
     * Get the current URI
     * @return string
     */
    public static function getCurrentUri() {
        $basepath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
        $uri = substr($_SERVER['REQUEST_URI'], strlen($basepath));
        if (strstr($uri, '?')) $uri = substr($uri, 0, strpos($uri, '?'));
        $uri = '/' . trim($uri, '/');
        return $uri;
    }

    /**
     * Get the current URI splited
     * @return string
     */
    public static function getSplitedCurrentUri() {
        $uri = explode('/', self::getCurrentUri());
        unset($uri[0]);

        return array_values($uri);
    }

    /**
     * Redirect if the route is validated
     */
    private function __destruct() {
        if(self::$accept_route == true) {
            self::$accept_route = false;
            self::routing(self::$callback, self::$request);
        }
    }
}