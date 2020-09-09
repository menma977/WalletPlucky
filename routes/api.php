<?php

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
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');

Route::post('login', 'Api\LoginController@index');
Route::get('login/show', 'Api\LoginController@show');
Route::middleware('auth:api')->group(function () {
  Route::get('logout', 'Api\LogoutController@index');

  Route::group(['prefix' => 'user', 'as' => 'user.'], static function () {
    Route::get('/', 'Api\UserController@index')->name('index');
    Route::post('/store', 'Api\UserController@store')->name('store');
  });

  Route::group(['prefix' => 'lot', 'as' => 'lot.'], static function () {
    Route::get('/', 'Api\LotController@index')->name('index');
    Route::get('/create', 'Api\LotController@create')->name('create');
    Route::post('/store', 'Api\LotController@store')->name('store');
  });

  Route::group(['prefix' => 'doge', 'as' => 'doge.'], static function () {
    Route::post('/store', 'Api\DogeController@store')->name('store');
  });

  Route::group(['prefix' => 'queue', 'as' => 'queue.'], static function () {
    Route::get('/', 'Api\QueueController@index')->name('index');
  });

  Route::group(['prefix' => 'setting', 'as' => 'setting.'], static function () {
    Route::get('/', 'Api\SettingController@index')->name('index');
  });
});
