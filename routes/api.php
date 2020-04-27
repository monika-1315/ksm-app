<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/api/getDivisions', 'APIController@getDivisions');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/auth/register', 'AuthController@postRegister');
Route::post('/auth/login', 'AuthController@postLogin');
Route::post('/auth/social', 'AuthController@postSocialLogin');

Route::group(['middleware' => ['jwt.auth']], function () {

    Route::get('/auth/logout', 'AuthController@logout');
    Route::post('/auth/getUser', 'UsersController@getUser');
    Route::post('/auth/updateUser', 'UsersController@updateUser');
    Route::post('/auth/getUnauthorizedUsers', 'UsersController@getUnauthorizedUsers');
    Route::post('/auth/authorizeUser', 'UsersController@authorizeUser');
    Route::post('/auth/getMessages', 'APIController@getMessages');
    Route::post('/auth/getMessageById', 'APIController@getMessageById');
    Route::post('/auth/newMessage', 'APIController@newMessage');
    Route::post('/auth/getMessageByAuthor', 'APIController@getMessageByAuthor');
    Route::post('/auth/editMessage', 'APIController@editMessage');
    Route::post('/auth/deleteMessage', 'APIController@deleteMessage');
});