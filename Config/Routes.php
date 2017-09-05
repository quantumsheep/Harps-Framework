<?php
use Harps\Core\Route;
use Harps\Controllers\View;
/**
 *
 * ROUTING
 *
 */

Route::get('/', "Default@index");

Route::get('/page/{nb}', function($nb) {
    echo $nb;
})->where(["nb" => "[0-9]"]);