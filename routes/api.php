<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Below mention routes are public, user can access those without any restriction.
// Create New User
Route::post('v1/sign-up', 'Api\PhoneBookController@signUp');
// Login User
Route::post('v1/login', 'Api\PhoneBookController@login');
// Route::middleware('auth:user')->prefix('v1/')->group(function () {
//     Route::post('/create-contact', 'API\PhoneBookController@createContact');
//     Route::post('/update-contact', 'API\PhoneBookController@updateContact');
//     Route::post('/delete-contact', 'API\PhoneBookController@deleteContact');
//     Route::post('/read-contacts', 'API\PhoneBookController@readContacts');
// });
Route::get('/login',function(){
    return ('Login Fail !!!');
})->name('login');