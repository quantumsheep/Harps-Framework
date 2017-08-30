<?php
use Harps\Core\Route;
/**
 *
 * ROUTING
 *
 */

Route::get('/', "Home@index");
Route::get('/users/{profilId}/board', "User@user_profil");
Route::post('/ajax/test', 'Home@AjaxTest');
Route::match(['post', 'get'], '/testing/{nb}', function($nb) {
    echo $nb;                                  
});