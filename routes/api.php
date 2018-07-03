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

Route::get('clients/{client}/dategoalvalues/{date?}', 'ClientsController@dategoalvalues');
Route::get('clients', 'ClientsController@apilist');
Route::get('types', 'TypeController@apilist');
Route::get('indicators', 'IndicatorController@apilist');
Route::get('roles', 'RolesController@apilist');

Route::group(['prefix' => 'entities/'], function () {

	Route::post('addentities', 'EntitiesController@addentities');
	Route::get('addentities', 'EntitiesController@addentities');

	Route::post('addentities/s', 'EntitiesController@addentitiesSOAP');
	Route::get('addentities/s', 'EntitiesController@addentitiesSOAP');

	Route::post('editentities', 'EntitiesController@editentities');
	Route::get('editentities', 'EntitiesController@editentities');

	Route::post('editentities/s', 'EntitiesController@editentitiesSOAP');
	Route::get('editentities/s', 'EntitiesController@editentitiesSOAP');

	Route::get('formonth/{date}/{rol}', 'EntitiesController@showByMonth');
	

	Route::get('{identification}', 'EntitiesController@showByIdentification');

	Route::get('{identification}/formonth/{date}', 'EntitiesController@showByIdentificationtoMonth');

	Route::post('{identification}/addvalue', 'EntitiesController@addvalue');
	Route::post('{identification}/addgoalvalue', 'EntitiesController@addgoalvalue');
	Route::post('{identification}/addvaluetype', 'EntitiesController@addvaluetype');
	Route::get('{identification}/delvalue/{id}', 'EntitiesController@delvalue');
	Route::get('{identification}/delgoalvalue/{id}', 'EntitiesController@delgoalvalue');
	Route::get('{identification}/yearentity', 'EntitiesController@showIndicators');

	Route::get('{identification}/html', 'EntitiesController@showHtml');

});	
//API user routes
Route::group(['prefix' => 'leads/'], function () {
	Route::get('addleads', 'LeadController@addleads');
	Route::post('addleads', 'LeadController@addleads');
	Route::get('addleads/s', 'LeadController@addleadsSOAP');
	Route::post('addleads/s', 'LeadController@addleadsSOAP');
	Route::post('stages', 'LeadController@addstages');
	Route::get('stages/s', 'LeadController@addstagesSOAP');
	Route::post('stages/s', 'LeadController@addstagesSOAP');

	Route::get('test/s', 'LeadController@testSOAP');
	Route::post('test/s', 'LeadController@testSOAP');

});

Route::group(['middleware' => ['cors'], 'prefix' => 'v1/'], function(){
	//Route::middleware('auth:api')->get('/test', function () {
	Route::get('test', function () {
	    \Illuminate\Support\Facades\Log::info('OK');
	    return 'API authentication OK';
	});
    Route::post('entities/{identification}/addgoalvalue', 'EntitiesController@addgoalvalue');
    Route::post('entities/{identification}/addvaluetype', 'EntitiesController@addvaluetype');
    Route::get('entities/{identification}/addgoalvalue', 'EntitiesController@addgoalvalue');
    Route::get('entities/{identification}/formonth/{date}', 'EntitiesController@showByIdentificationtoMonth');
    Route::get('entities/{identification}/html', 'EntitiesController@showHtml');
});



