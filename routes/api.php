<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('users')->group(function () {
    Route::post('/newUser', 'UsersController@newUser');
    Route::post('/login', 'UsersController@login');
    Route::get('/getUsers', 'UsersController@getUsers')->middleware('auth:api');
    Route::get('/getUser', 'UsersController@getUser')->middleware('auth:api');
    Route::post('/deleteUser', 'UsersController@deleteUser')->middleware('auth:api');
    Route::post('/activateUser', 'UsersController@activeUser')->middleware('auth:api');
    Route::post('/imageUser', 'UsersController@updateImage')->middleware('auth:api');
    Route::post('/editUser', 'UsersController@UpdateData')->middleware('auth:api');
    Route::post('/recoverPassword', 'UsersController@changePassword');
});

Route::get('/getVideos', 'DB2VideosController@getVideos');
Route::get('/migrateVideos', 'DB2VideosController@migrateVideos');
Route::get('/getVideosDB1', 'DB2VideosController@getVideosDB1');
Route::post('/createAdmin', 'UsersController@CreateAdmin');
