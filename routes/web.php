<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');

Route::get('/', function () {
  return view('welcome');
});

Auth::routes(['register' => false]);

Route::middleware('auth')->group(static function () {
  Route::get('/home', 'HomeController@index')->name('home');

  Route::get('/total/user/view', 'HomeController@totalUserView')->name('totalUserView');
  Route::get('/online/user/view', 'HomeController@onlineUserView')->name('onlineUserView');
  Route::get('/new/user/view', 'HomeController@newUserView')->name('newUserView');
  Route::get('/total/upgrade/view', 'HomeController@totalUpgradeView')->name('totalUpgradeView');
  Route::get('/new/user/not/verified/view', 'HomeController@newUserNotVerifiedView')->name('newUserNotVerifiedView');

  Route::get('/total/user', 'HomeController@totalUser')->name('totalUser');
  Route::get('/online/user', 'HomeController@onlineUser')->name('onlineUser');
  Route::get('/new/user', 'HomeController@newUser')->name('newUser');
  Route::get('/total/upgrade', 'HomeController@totalUpgrade')->name('totalUpgrade');
  Route::get('/new/user/not/verified', 'HomeController@newUserNotVerified')->name('newUserNotVerified');

  Route::group(['prefix' => 'user', 'as' => 'user.'], static function () {
    Route::get('/', 'UserController@index')->name('index');
    Route::get('/indexDataDynamic', 'UserController@indexDataDynamic')->name('indexDataDynamic');
    Route::get('/suspend/{id}/{status}', 'UserController@suspend')->name('suspend');
    Route::get('/activate/{id}', 'UserController@activate')->name('activate');
    Route::get('/show/{id}', 'UserController@show')->name('show');
    Route::post('/update/password/{id}', 'UserController@updatePassword')->name('updatePassword');
    Route::post('/update/secondary/password/{id}', 'UserController@updateSecondaryPassword')->name('updateSecondaryPassword');
    Route::post('/update/phone/{id}', 'UserController@updatePhone')->name('updatePhone');
    Route::get('/lot/{id}', 'UserController@lotList')->name('lotList');
    Route::get('/pin/{id}', 'UserController@pinList')->name('pinList');
    Route::get('/delete/session/{id}', 'UserController@logoutSession')->name('logoutSession');
    Route::post('/find', 'UserController@find')->name('find');
    Route::get('/delete/bot/{id}', 'UserController@deleteBot')->name('deleteBot');
  });

  Route::group(['prefix' => 'lot', 'as' => 'lot.'], static function () {
    Route::get('/', 'LotListController@index')->name('index');
    Route::post('/store', 'LotListController@store')->name('store');
    Route::post('/update/{id}', 'LotListController@update')->name('update');
    Route::get('/delete/{id}', 'LotListController@destroy')->name('delete');
  });

  Route::group(['prefix' => 'level', 'as' => 'level.'], static function () {
    Route::get('/', 'LevelController@index')->name('index');
    Route::post('/store', 'LevelController@store')->name('store');
    Route::post('/update/{id}', 'LevelController@update')->name('update');
    Route::get('/delete/{id}', 'LevelController@destroy')->name('delete');
  });

  Route::group(['prefix' => 'setting', 'as' => 'setting.'], static function () {
    Route::get('/', 'SettingController@index')->name('index');
    Route::post('/update/wallet/it', 'SettingController@updateIt')->name('updateIt');
    Route::post('/update/fee', 'SettingController@fee')->name('fee');
    Route::post('/update/app', 'SettingController@app')->name('app');
    Route::get('/shot/down/{status}', 'SettingController@shotDown')->name('shotDown');
    Route::post('/edit/lot', 'SettingController@editLot')->name('editLot');
    Route::post('/save/wallet', 'SettingController@saveWallet')->name('saveWallet');
    Route::post('/edit/wallet/{id}', 'SettingController@editWallet')->name('editWallet');
    Route::get('/delete/wallet/{id}', 'SettingController@deleteWallet')->name('deleteWallet');
  });
});
