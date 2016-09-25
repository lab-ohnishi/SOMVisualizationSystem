<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/events/{id}/inputs/insert', 'PerspectivesController@insert');
Route::post('/events/{id}/inputs/insert', 'PerspectivesController@res');

Route::get('/home', 'HomeController@index');
