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

//Route::middleware('auth:api')->get('/test', function () {
Route::get('/test', function () {
    \Illuminate\Support\Facades\Log::info('OK');
    return 'API authentication OK';
})->middleware('auth:api');

Route::get('clients/{client}/dategoalvalues/{date?}', 'ClientsController@dategoalvalues');
Route::get('entities/{identification}', 'EntitiesController@showByIdentification');
Route::put('entities/{entity}', 'EntitiesController@update');



Route::post('entities/{identification}/addvalue', 'EntitiesController@addvalue');
Route::post('entities/{identification}/addgoalvalue', 'EntitiesController@addgoalvalue');
Route::get('entities/{identification}/delvalue/{id}', 'EntitiesController@delvalue');
Route::get('entities/{identification}/delgoalvalue/{id}', 'EntitiesController@delgoalvalue');
Route::post('entities/set-rule','EntitiesController@setRule');
Route::post('entities/del-rule','EntitiesController@delRule');
Route::get('entities/create-zoho/{entity_id}/{module}','EntitiesController@createZoho');

Route::get('rules/all', 'RulesController@all');

Route::post('redemptions', 'RedemptionController@store');
Route::post('redemptions/delete', 'RedemptionController@delete');

Route::post('entities', 'EntitiesController@store');


Route::get('clients', 'ClientsController@apilist');
Route::get('vars', 'VarsController@apilist');
Route::get('templates', 'TemplateController@apilist');
