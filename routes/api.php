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
    Route::get('places/{nelat}/{swlat}/{nelng}/{swlng}', 'Places\PlaceController@index');
    //Route::get('placescord/{nelat}/{swlat}/{nelng}/{swlng}', 'PlaceController@placesCord');


Route::group(['middleware' => 'auth:api'], function() {
    //Create new Place
    Route::post('place', 'Places\PlaceController@store');
    //Update place
    Route::put('place', 'Places\PlaceController@store');
    //Delete place
    Route::delete('place/{id}', 'Places\PlaceController@destroy');

    //Create new Place
    Route::post('placequeue', 'Places\PlaceQueueController@store');

});




    //List Events
    Route::get('events', 'EventController@index');
    //List Events by place
    Route::get('events/{id}', 'EventController@get_events_by_place');
    //Get Event
    Route::get('event/{id}', 'EventController@show');

    //Get which event happening right now or which event is closest one
    Route::get('nearevent/{id}', 'EventController@closestEvent');

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

    Route::get('people_going/{id}', 'PeopleGoingController@returnByEvent');

Route::group(['middleware' => 'auth:api'], function() {
    //Add person to event
    Route::post('person', 'PeopleGoingController@store');
    //Remove person from event
    Route::delete('person/{id}', 'PeopleGoingController@destroy');
});