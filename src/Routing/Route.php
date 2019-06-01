<?php
namespace Harps\Routing;

class Route
{
    /**
     * Boolean variable to check if the route is valid
     *
     * @var boolean
     */
    protected static $accept_route = false;

    /**
     * Callback for the current conditional route
     *
     * @var string|callable
     */
    protected static $callback;

    /**
     * The request of the uri's conditions
     *
     * @var array
     */
    protected static $request;

    // PLANNED IMPROVEMENT
    //
    //protected static $patterns = [
    //    "/{(.*?)\(int\)}/" => "/[0-9]+/",
    //    "/{(.*?)\(word\)}/" => "/[a-zA-Z]+/",
    //    "/{(.*?)\(string\)}/" => "/[a-z0-9-]+/",
    //    "/{(.*?)\(alphanum\)}/" => "/[a-zA-Z0-9-_]+/",
    //    "/{(.*?)\(guid\)}/" => "/^\{?[a-zA-Z0-9]{8}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}-[a-zA-Z0-9]{12}\}?$/"
    //];

    /**
     * Get the content from a specific URI
     *
     * @param string $uri Requested URI to do the action
     * @param string|callable $callback The action do to if the uri is valid
     */
    public static function get(string $uri, $callback)
    {
        return self::start_route($uri, $callback, "GET");
    }

    /**
     * Use the POST method from a specific URI
     *
     * @param string $uri Requested URI to do the action
     * @param string|callable $callback The action do to if the uri is valid
     */
    public static function post(string $uri, $callback)
    {
        return self::start_route($uri, $callback, "POST");
    }

    /**
     * Use the PUT method from a specific URI
     *
     * @param string $uri Requested URI to do the action
     * @param string|callable $callback The action do to if the uri is valid
     */
    public static function put(string $uri, $callback)
    {
        return self::start_route($uri, $callback, "PUT");
    }

    /**
     * Use the PATCH method from a specific URI
     *
     * @param string $uri Requested URI to do the action
     * @param string|callable $callback The action do to if the uri is valid
     */
    public static function patch(string $uri, $callback)
    {
        return self::start_route($uri, $callback, "PATCH");
    }

    /**
     * Use the DELETE method from a specific URI
     *
     * @param string $uri Requested URI to do the action
     * @param string|callable $callback The action do to if the uri is valid
     */
    public static function delete(string $uri, $callback)
    {
        return self::start_route($uri, $callback, "DELETE");
    }

    /**
     * Use the DELETE method from a specific URI
     *
     * @param string $uri Requested URI to do the action
     * @param string|callable $callback The action do to if the uri is valid
     */
    public static function options(string $uri, $callback)
    {
        return self::start_route($uri, $callback, "OPTIONS");
    }

    /**
     * Route if the client's requested method is one of the $method array
     *
     * @param array $method Accepted methods
     * @param string $uri Requested URI to do the action
     * @param string|callable $callback The action do to if the uri is valid
     */
    public static function match(array $method, string $uri, $callback)
    {
        if (in_array($_SERVER['REQUEST_METHOD'], array_map('strtoupper', $method))) {
            self::register($uri, $callback);
        }

        return new Route();
    }

    /**
     * Route without checking client's requested method
     *
     * @param string $uri Requested URI to do the action
     * @param string|callable $callback The action do to if the uri is valid
     */
    public static function any(string $uri, $callback)
    {
        return self::start_route($uri, $callback);
    }

    /**
     * Summary of group
     *
     * @param array $uris The URIs allowed to do the callback action
     * @param string|callable $callback The action do to if one of the uris are valid
     * @throws \InvalidArgumentException
     */
    public static function group(array $uris, $callback)
    {
        if (is_array($uris)) {
            foreach ($uris as $uri => $method) {
                if ($_SERVER['REQUEST_METHOD'] == strtoupper($method) || $method == "any") {
                    self::register($uri, $callback);
                    break;
                }
            }

            return new Route();
        } else {
            throw new \InvalidArgumentException("The URIs must be an array");
        }
    }

    /**
     * Start the routing verification
     *
     * @param string $uri The conditional uri
     * @param string|callable $callback The callback
     * @param string $method HTTP Method to check (Leave to NULL to do not check the method for the current request)
     * @return void
     */
    protected static function start_route(string $uri, $callback, string $method = null)
    {
        if ($method === null || $_SERVER['REQUEST_METHOD'] == $method) {
            self::register($uri, $callback);
        }

        return new Route();
    }

    /**
     * Start registring, reconstructing and verifying a route
     *
     * @param string $uri The conditional uri
     * @param string|callable $callback The callback
     * @return void
     */
    protected static function register(string $uri, $callback)
    {
        if (isset($GLOBALS["ROUTED"]) && $GLOBALS["ROUTED"] == true) {
            return;
        }

        $route_validity = true;
        self::get_unknown_vars($uri, $request, $route_validity);

        if ($route_validity == false) {
            self::$accept_route = false;
            return;
        }

        if ($uri == CURRENT_URI) {
            self::$callback = $callback;
            self::$request = $request;
            self::$accept_route = true;
        }
    }

    /**
     * Get the unknown variables from the routes like "/post/{n}"
     *
     * @param mixed $uri URI used by the client
     * @param mixed $request The requested variable
     */
    protected static function get_unknown_vars(string &$uri, &$request, &$route_validity)
    {
        $request = [];

        if (preg_match('/{(.+?)}|\[(.+?)\]/', $uri)) {
            $cutted_uri = explode('/', CURRENT_URI);
            $cutted_requested = explode('/', $uri);
            $n_uri = count($cutted_uri);

            if (count($cutted_requested) == $n_uri) {
                for ($i = 0; $n_uri > $i; $i++) {
                    if (preg_match('/{(.+?)}|\[(.+?):(.+?)\]/', $cutted_requested[$i], $requested_vars)) {
                        if ($requested_vars[0][0] == '[') {
                            $request_accepted = explode('|', $requested_vars[2]);

                            $accepted = false;
                            foreach ($request_accepted as $r_acc) {
                                if ($cutted_uri[$i] === $r_acc) {
                                    $request[$requested_vars[3]] = $r_acc;
                                    $cutted_requested[$i] = $cutted_uri[$i];

                                    $accepted = true;
                                    break;
                                }
                            }

                            if ($accepted == false) {
                                $route_validity = false;
                                return;
                            }
                        } else {
                            $request[$requested_vars[1]] = $cutted_uri[$i];
                            $cutted_requested[$i] = $cutted_uri[$i];
                        }
                    }
                }
            }

            $uri = implode('/', $cutted_requested);
        }
    }

    /**
     * Define conditions to the unknown variables
     *
     * @param array $conditions Unknown variables conditions for the route to be accepted, use like ["nb" => "[0-9]"]
     */
    public function where($conditions)
    {
        if (self::$accept_route == true) {
            foreach ($conditions as $key => $condition) {
                if (!preg_match("/^" . $condition . "$/", self::$request[$key])) {
                    self::$accept_route = false;
                    break;
                }
            }
        }
    }

    /**
     * Call a callable and echo his result
     *
     * @param string $callback Controller@Action
     * @param array $request The Request
     */
    protected static function call_callable(callable $callback, array $request)
    {
        if (!empty($request)) {
            echo call_user_func_array($callback, $request);
        } else {
            echo call_user_func($callback);
        }
    }

    /**
     * Call a controller and echo his result
     *
     * @param string $callback Controller@Action
     */
    protected static function call_controller(string $callback, array $request)
    {
        $callback = explode('@', $callback);

        echo call_user_func_array("\\App\\Controllers\\" . $callback[0] . "Controller::" . $callback[1], [$request]);
    }

    /**
     * Redirect to the request
     *
     * @param string|callable $callback Route callback
     * @param mixed $request Route request
     */
    protected static function routing($callback, $request)
    {
        if (is_callable($callback)) {
            self::call_callable($callback, $request);
        } elseif (strpos($callback, '@') !== false) {
            self::call_controller($callback, $request);
        } else {
            throw new \InvalidArgumentException("Callback must be a callable or a controller's string");
        }

        $GLOBALS["ROUTED"] = true;
    }

    /**
     * Get the current URI
     *
     * @return string
     */
    public static function get_current_uri()
    {
        $basepath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
        $uri = substr($_SERVER['REQUEST_URI'], strlen($basepath));
        if (strstr($uri, '?')) {
            $uri = substr($uri, 0, strpos($uri, '?'));
        }
        $uri = '/' . trim($uri, '/');
        return $uri;
    }

    /**
     * Get the current URI splited
     *
     * @return string
     */
    public static function get_splitted_current_uri()
    {
        $uri = explode('/', self::get_current_uri());
        unset($uri[0]);

        return array_values($uri);
    }

    /**
     * Redirect if the route is validated
     */
    protected function __destruct()
    {
        if (self::$accept_route == true) {
            self::$accept_route = false;
            self::routing(self::$callback, self::$request);
        }
    }
}
