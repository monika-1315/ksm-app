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
    Route::post('/auth/getUser', 'APIController@getUser');
    Route::post('/auth/updateUser', 'APIController@updateUser');
    Route::post('/auth/getUnauthorizedUsers', 'APIController@getUnauthorizedUsers');
    Route::post('/auth/authorizeUser', 'APIController@authorizeUser');
});