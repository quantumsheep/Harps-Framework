<?php
use Harps\Core\Route;
use Harps\Controllers\View;
/**
 *
 * ROUTING
 *
 */

Route::get('/', "Home@index");
Route::get('/users/{profilId}/board', "User@user_profil");
Route::get('/test', function() {
    View::load('index');
});