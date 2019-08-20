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


//List Types
Route::get('types', 'TypeController@index');

//List Places
Route::get('places', 'PlaceController@index');
//Create new Place
Route::post('place', 'PlaceController@store');
//Update place
Route::put('place', 'PlaceController@store');
//Delete place
Route::delete('place/{id}', 'PlaceController@destroy');

//List Events
Route::get('events', 'EventController@index');
//Create new Event
Route::post('event', 'EventController@store');
//Update Event
Route::put('event', 'EventController@store');
//Delete Event
Route::delete('event/{id}', 'EventController@destroy');