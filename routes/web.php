<?php

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

Route::get('/', 'JuppiterController@planTopShow');

Route::post('/main/result','JuppiterController@simpleResult');

Route::get('/main/travel_top', 'JuppiterController@PlanTopShow');

//Route::resource('main', 'JuppiterController');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
