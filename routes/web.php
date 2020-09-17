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
Route::get('/api/getDivisions', 'DivisionsController@getDivisions');
Route::get('/api/getDivisionById', 'DivisionsController@getDivisionById');
Route::get('/api/getManagement', 'APIController@getManagement');
Route::get('/api/getDivisionsStats', 'DivisionsController@getDivisionsStats');
Route::get('/api/getEvents', 'EventsController@getUpcomingEvents');
Route::get('/api/getOldEvents', 'EventsController@getOldEvents');
Route::get('/api/getDivisionEvents', 'EventsController@getDivisionEvents');

Route::post('/mail', 'Mail@sendMail');

Route::get('/{any}', function() { return view('welcome'); })->where('any', '(.*)');



Route::get('/{id}', function () {
    return view('welcome');
});
