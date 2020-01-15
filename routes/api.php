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
    //Route::get('places/{nelat}/{swlat}/{nelng}/{swlng}', 'PlaceController@index');


Route::group(['middleware' => 'auth:api'], function() {
    //Create new Place
    Route::post('place', 'PlaceController@store');
    //Update place
    Route::put('place', 'PlaceController@store');
    //Delete place
    Route::delete('place/{id}', 'PlaceController@destroy');

    //Create new Place
    Route::post('placequeue', 'PlaceQueueController@store');


});




    //List Events
    Route::get('events', 'EventController@index');

Route::group(['middleware' => 'auth:api'], function() {
    //Create new Event
    Route::post('event', 'EventController@store');
    //Update Event
    Route::put('event', 'EventController@store');
    //Delete Event
    Route::delete('event/{id}', 'EventController@destroy');
});

    //People Going
    Route::get('people_going', 'PeopleGoingController@index');

Route::group(['middleware' => 'auth:api'], function() {
    //Add person to event
    Route::post('person', 'PeopleGoingController@store');
    //Remove person from event
    Route::delete('person/{id}', 'PeopleGoingController@destroy');
});