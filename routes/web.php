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

Route::get('users/api','UsersController@apitokens');
Route::resource('users','UsersController');
Route::post('users/{user}','UsersController@update');
Route::resource('uploads','Utils\UploadsController');
Route::resource('clients','ClientsController');
//Route::get('uploads/{folder}/{file}','Utils\UploadsController@show');


Route::resource('rules','RulesController');
Route::resource('entities','EntitiesController');