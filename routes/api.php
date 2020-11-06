<?php
header('Access-Control-Allow-Origin: *');
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


// Route::prefix('api')->group(function () {
Route::get('usuarios', 'UserController@index');
// Route::get('usuario/{user}', 'UserController@show');
Route::get('usuario/{username}', 'UserController@getUser');
// });
Route::post('usuario', 'UserController@checkValidateUser');

Route::post('camillero', 'UserController@checkCamillero');