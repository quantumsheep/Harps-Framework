<?php
namespace Harps\Controllers;

use Harps\Exceptions\ViewNotFoundException;

class View
{
    /**
     * Throw a new ViewNotFoundException
     * @param string $view The view not found
     * @return void
     */
    public static function throw_not_found(string $view)
    {
        throw new ViewNotFoundException("View not found : \"$view\"");
    }

    /**
     * Create a new view using with blade template
     * @param string $view The view name
     * @param mixed $model Model to use for the view
     * @return void
     */
    private static function blade_view(string $view, $model = null)
    {
        return (new \Philo\Blade\Blade(DIR_VIEWS, DIR_BLADE_CACHE))->view()->make($view)->withModel($model)->render();
    }

    /**
     * Directly load a view
     * @param string $view The view name like 'index' or '/doc/harps', file extension will be completed by itself
     * @param mixed $model Model to use for the view
     * @throws \Exception
     * @return array
     */
    public static function load(string $view, $model = null)
    {
        if (glob(DIR_VIEWS . $view . ".*")) {
            echo self::blade_view($view, $model);
        } else {
            self::throw_not_found($view);
        }
    }

    /**
     * Generate a new view content
     * @param string $view The view name like 'index' or '/doc/harps', file extension will be completed by itself
     * @param mixed $model Model to use for the view
     * @return \Philo\Blade\Blade
     */
    public static function generate(string $view, $model = null)
    {
        if (glob(DIR_VIEWS . $view . ".*")) {
            return self::blade_view($view, $model);
        } else {
            self::throw_not_found($view);
        }
    }
}
