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

Route::middleware('auth:api')->get('/user', function (Request $request) {
return $request->user();
});

// List events
Route::get('events', 'EventController@index');

// Show event
Route::get('event/{id}', 'EventController@show');

// Add event
Route::post('event', 'EventController@store');

// Update event
Route::put('event/{id}', 'EventController@update');

// Delete event
Route::delete('event/{id}', 'EventController@destroy');

// Multiple event
Route::post('events/multiple-event', 'EventController@createMultipleEvent');

// Multiple event
Route::post('events/month', 'EventController@getCurMonthEvents');
