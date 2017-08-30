<?php
use Harps\Core\Route;
/**
 *
 * ROUTING
 *
 */

Route::get('/', "Home@index");
Route::get('/users/{profilId}/board', "User@user_profil");
Route::post('', '');