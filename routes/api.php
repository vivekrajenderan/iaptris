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
Route::post('/verifyOtp', 'Api\AuthController@verifyOtp');
Route::post('/resendOtp', 'Api\AuthController@resendOtp');
Route::get('/roles', 'Api\AuthController@roles');
Route::get('/university', 'Api\AuthController@university');
Route::get('/topRoles', 'Api\AuthController@topRoles'); 
Route::get('/userDetails/{id}', 'Api\AuthController@userDetails');
Route::group(['middleware' => ['auth:api']], function () {    
    Route::get('/userAll/{searchVal?}', 'Api\AuthController@userAll');
    Route::post('/changepassword', 'Api\AuthController@changepassword');
    Route::post('/logout', 'Api\AuthController@logout');
    Route::post('/updateUser', 'Api\AuthController@updateUser'); 
    
    // Wholesale
    Route::get('/getProjectDropdown', 'Api\WholesaleController@getProjectDropdown');    
    Route::get('/getProjects/{projectid?}', 'Api\WholesaleController@getProjects');    
    Route::post('/addProject', 'Api\WholesaleController@addProject');    
    Route::post('/updateProject', 'Api\WholesaleController@updateProject');    
    Route::post('/updatePayment', 'Api\WholesaleController@updatePayment');    
    Route::get('/report/{projectid?}', 'Api\WholesaleController@report'); 
    //Designer
    Route::post('/extractProject', 'Api\DeisgnerController@extractProject');    
});
//Route::apiResource('/usercreate', 'Api\AuthController@usercreate')->middleware('auth:api');