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

Route::get('entities/create-from-file','EntitiesController@createFromFile');
Route::get('invoices/create-from-file','InvoiceController@createFromFile');
Route::get('contacts/create-from-file','EntitiesController@createFromContactFile');

Route::resource('vars','VarsController');
Route::resource('rules','RulesController');
Route::resource('goals','GoalsController');
Route::resource('entities','EntitiesController');
Route::resource('templates','TemplateController');
Route::resource('template-vars','TemplateVarsController');
Route::resource('invoices','InvoiceController');
Route::resource('redemptions','RedemptionController');


Route::get('devel/vars/{id}','VarsController@devel');
Route::get('devel/templates/{id}','TemplateController@devel');

Route::get('devel/redemption','RedemptionController@Devel');
Route::get('devel/entity','EntitiesController@devel');
