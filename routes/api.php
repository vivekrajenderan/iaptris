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
Route::post('/register', 'Api\AuthController@register');
Route::post('/login', 'Api\AuthController@login');
Route::post('/forget', 'Api\AuthController@forget');
Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/userDetails/{id}', 'Api\AuthController@userDetails');
    Route::get('/userAll/{searchVal?}', 'Api\AuthController@userAll');
    Route::post('/changepassword', 'Api\AuthController@changepassword');
    Route::post('/logout', 'Api\AuthController@logout');
    Route::post('/updateUser', 'Api\AuthController@updateUser');
    Route::post('/changeStatus', 'Api\AuthController@changeStatus');
    Route::get('/dashboardDetails', 'Api\AuthController@dashboardDetails');
});
//Route::apiResource('/usercreate', 'Api\AuthController@usercreate')->middleware('auth:api');