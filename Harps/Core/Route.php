<?php
namespace Harps\Core;

if(!defined("CURRENT_URI"))
    define("CURRENT_URI", Route::getCurrentUri());

if(!defined("ROUTED"))
    $GLOBALS["ROUTED"] = false;

class Route {
    /**
     * Get the content from a specific URI
     * @param string $uri Requested URI to do the action
     * @param mixed $callback The controller address (example for function 'index()' in 'DefaultController': 'Default@index')
     */
    public static function get(string $uri, $callback) {
        if($_SERVER['REQUEST_METHOD'] == "GET") {
            self::getUnknownVar($uri, $request);

            if($uri == CURRENT_URI) {
                self::routing($callback, $request);
            }
        }
    }

    /**
     * Use the POST method from a specific URI
     * @param string $uri Requested URI to do the action
     * @param mixed $callback The controller address (example for function 'index()' in 'DefaultController': 'Default@index')
     */
    public static function post(string $uri, $callback) {
        if($_SERVER['REQUEST_METHOD'] == "POST") {
            self::getUnknownVar($uri, $request);

            if($uri == CURRENT_URI) {
                self::routing($callback, $request);
            }
        }
    }

    /**
     * Use the PUT method from a specific URI
     * @param string $uri Requested URI to do the action
     * @param mixed $callback The controller address (example for function 'index()' in 'DefaultController': 'Default@index')
     */
    public static function put(string $uri, $callback) {
        if($_SERVER['REQUEST_METHOD'] == "PUT") {
            self::getUnknownVar($uri, $request);

            if($uri == CURRENT_URI) {
                self::routing($callback, $request);
            }
        }
    }

    /**
     * Route if the client's requested method is one of the $method array
     * @param array $method Accepted methods
     * @param string $uri Requested URI to do the action
     * @param mixed $callback The controller address (example for function 'index()' in 'DefaultController': 'Default@index')
     */
    public static function match(array $method, string $uri, $callback) {
        if(in_array($_SERVER['REQUEST_METHOD'], array_map('strtoupper', $method))) {
            self::getUnknownVar($uri, $request);

            if($uri == CURRENT_URI) {
                self::routing($callback, $request);
            }
        }
    }

    /**
     * Route without checking client's requested method
     * @param array $method Accepted methods
     * @param string $uri Requested URI to do the action
     */
    public static function any(string $uri, $callback) {
        self::getUnknownVar($uri, $request);

        if($uri == CURRENT_URI) {
            self::routing($callback, $request);
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
                self::RedirectToView($result[0], $result[1]);
            }
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
}