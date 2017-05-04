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

Auth::routes();

Route::get('docs/{folder?}/{option?}', 'DocsController@index');

Route::get('/', 'HomeController@index');

Route::resource('users','UsersController');
Route::post('users/{user}','UsersController@update');
Route::resource('uploads','Utils\UploadsController');
//Route::get('uploads/{folder}/{file}','Utils\UploadsController@show');
