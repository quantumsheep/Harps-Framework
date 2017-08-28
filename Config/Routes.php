<?php
use Libs\Utils\Route;
/**
 *
 * ROUTING
 *
 */
Route::get('/', "Home@index");
Route::get('/users/{profilId}/board', "User@user_profil");