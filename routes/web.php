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

Route::resource('uploads','Utils\UploadsController');
Route::resource('clients','ClientsController');
Route::resource('vars','VarsController');
Route::resource('rules','RulesController');
Route::resource('goals','GoalsController');
Route::resource('entities','EntitiesController');
Route::resource('templates','TemplateController');
Route::resource('template-vars','TemplateVarsController');
Route::resource('invoices','InvoiceController');
Route::resource('redemptions','RedemptionController');
Route::resource('users','UsersController');

Route::get('/', 'HomeController@index');

Route::get('users/api','UsersController@apitokens');
Route::post('users/{user}','UsersController@update');

//Route::get('uploads/{folder}/{file}','Utils\UploadsController@show');

Route::post('entities','EntitiesController@index');
Route::get('entities/create-from-file','EntitiesController@createFromFile');

Route::get('invoices/create-from-file','InvoiceController@createFromFile');

Route::get('contacts/create-from-file','EntitiesController@createFromContactFile');

Route::get('tools/export','HomeController@export')->name('tools.export');
Route::post('tools/export','HomeController@export');





Route::get('devel/vars/{id}','VarsController@devel');
Route::get('devel/templates/{id}','TemplateController@devel');
Route::get('devel/redemption','RedemptionController@devel');
Route::get('devel/entity','EntitiesController@devel');

Route::get('docs/{folder?}/{option?}', 'DocsController@index');
