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

// Route to create a new role
Route::post('role','JwtAuthenticateController@createRole');
//Route to create a new permission
Route::post('permission','JwtAuthenticateController@createPermission');
// Route to assign role to user
Route::post('assign-role','JwtAuthenticateController@assignRole');
// Route to assign permission to a role
Route::post('attach-permission','JwtAuthenticateController@attachPermission');

// API route group that we need to protect
Route::group([
    ['middleware'=>['ability:admin,create-users']]
],function (){
   // Protected Route
    Route::get('users','JwtAuthenticateController@index');
});

// Authentication Route
Route::post('authenticate','JwtAuthenticateController@authenticate');

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
