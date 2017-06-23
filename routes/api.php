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

Route::get('/test',function(){
    return "ok"; 
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix' => '/v1', 'middleware' => 'client_credentials'], function() 
{
    Route::post('/auth/app', 'AuthController@authenticateApp');
    Route::post('/auth/user', 'AuthController@authenticateUser');
    Route::post('/auth/user/logout', 'AuthController@logoutUser');

    Route::get('/application-data', 'ApiHomeController@appData');
    Route::get('/user', 'ApiHomeController@userData');
    Route::post('/register', 'Register@store')->name('register');
    Route::get('/test',function(){
        return "ok"; 
    });
});
