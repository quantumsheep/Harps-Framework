<?php
namespace Harps\Core;

if(!defined("CURRENT_URI"))
    define("CURRENT_URI", Route::getCurrentUri());

if(!defined("ROUTED"))
    $GLOBALS["ROUTED"] = false;

class Route {
    /**
     * Get the content from a specific URI
     * @param mixed $uri Requested URI to do the action
     * @param mixed $controller The controller address (example for function 'index()' in 'DefaultController': 'Default@index')
     */
    public static function get($uri, $controller) {
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

        if($uri == CURRENT_URI) {
            if(is_callable($controller)) {
                call_user_func($controller);
            } else if(strpos($controller, '@') !== false) {
                $controller = explode('@', $controller);
                $result = ("\\App\\Controllers\\" . $controller[0] . "Controller")::{$controller[1]}($request, "hi");
                self::RedirectToView($result[0], $result[1]);
            }

            $GLOBALS["ROUTED"] = true;
        }
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