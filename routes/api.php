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
});
Route::get('clients', 'ClientsController@apilist');
Route::get('entities/{identification}', 'EntitiesController@showByIdentification');
Route::post('entities/{identification}/addvalue', 'EntitiesController@addvalue');
Route::post('entities/{identification}/addgoalvalue', 'EntitiesController@addgoalvalue');
Route::get('entities/{identification}/delvalue/{id}', 'EntitiesController@delvalue');
Route::get('entities/{identification}/delgoalvalue/{id}', 'EntitiesController@delgoalvalue');
Route::get('clients/{client}/dategoalvalues/{date?}', 'ClientsController@dategoalvalues');


