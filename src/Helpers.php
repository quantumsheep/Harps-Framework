<?php
use Harps\Controllers\View;
use Harps\Exceptions\ViewNotFoundException;
use Harps\Security\CSRF;

function csrf_gen()
{
    return CSRF::generate();
}

function csrf_regen()
{
    return CSRF::regenerate();
}

/**
 * Check and return a view value
 * @param string $view The simple view name like 'index' or '/doc/harps', file extension will be completed by itself
 * @param mixed $var The data to send to the view
 * @throws \Exception
 */
function view(string $view, $model = null)
{
    if (glob(DIR_VIEWS . $view . ".*")) {
        return View::generate($view, $model);
    } else {
        throw new ViewNotFoundException("View not found : \"$view\"");
    }
}
