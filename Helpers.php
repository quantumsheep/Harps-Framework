<?php
use Harps\Core\Security;
use Harps\Controllers\View;

function crsf_gen()
{
    return Security::csrf_gen();
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
        View::throw_not_found($view);
    }
}
