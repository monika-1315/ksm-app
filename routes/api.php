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


Route::get('/api/getDivisions', 'DivisionsController@getDivisions');
Route::get('/api/getDivisionById', 'DivisionsController@getDivisionById');
Route::get('/api/getManagement', 'APIController@getManagement');
Route::get('/api/getDivisionsStats', 'DivisionsController@getDivisionsStats');

Route::get('/api/getEvents', 'EventsController@getUpcomingEvents');
Route::get('/api/getOldEvents', 'EventsController@getOldEvents');
Route::get('/api/getDivisionEvents', 'EventsController@getDivisionEvents');

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
    Route::post('/auth/discardUser', 'UsersController@discardUser');

    Route::post('/auth/getAuthorizedUsers', 'UsersController@getAuthorizedUsers');
    Route::post('/auth/getAuthorizedUsersDiv', 'UsersController@getAuthorizedUsersDiv');
    Route::post('/auth/changeLeadership', 'UsersController@changeLeadership');
    Route::post('/auth/changeManagement', 'UsersController@changeManagement');

    Route::post('/auth/getMessages', 'MessageController@getMessages');
    Route::post('/auth/getMessageById', 'MessageController@getMessageById');
    Route::post('/auth/newMessage', 'MessageController@newMessage');
    Route::post('/auth/getMessageByAuthor', 'MessageController@getMessageByAuthor');
    Route::post('/auth/editMessage', 'MessageController@editMessage');
    Route::post('/auth/deleteMessage', 'MessageController@deleteMessage');

    Route::post('/auth/newDivision', 'DivisionsController@newDivision');
    Route::post('/auth/allDivisions', 'DivisionsController@getAllDivisions');
    Route::post('/auth/editDivision', 'DivisionsController@updateDivision');
    Route::post('/auth/deleteDivision', 'DivisionsController@deleteDivision');

    Route::post('/auth/getUserUpcomingEvents', 'EventsController@getUserUpcomingEvents');
    Route::post('/auth/getUserOldEvents', 'EventsController@getUserOldEvents');
    Route::post('/auth/getEventInfo', 'EventsController@getEventInfo');
    Route::post('/auth/getParticipants', 'ParticipantsController@getParticipants');
    Route::post('/auth/newEvent', 'EventsController@newEvent');
    Route::post('/auth/editEvent', 'EventsController@editEvent');
    Route::post('/auth/deleteEvent', 'EventsController@deleteEvent');
    Route::post('/auth/getCollidingEvents', 'EventsController@getCollidingEvents');
    Route::post('/auth/newParticipant', 'ParticipantsController@newParticipant');
    Route::post('/auth/editParticipant', 'ParticipantsController@editParticipant');
    Route::post('/auth/checkParticipant', 'ParticipantsController@checkParticipant');
    Route::post('/auth/deleteParticipant', 'ParticipantsController@deleteParticipant');
});