<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/api/getDivisions', 'APIController@getDivisions');
// Route::get('/api/getUser', 'APIController@getUser');

Route::get('/{id}', function () {
    return view('welcome');
});
