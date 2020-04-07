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

    //Purchase complete
    Route::post('purchase/complete', 'PagesController@payment_successful');

    //List Types
    Route::get('types', 'TypeController@index');
    //List Places
    Route::get('places/{nelat}/{swlat}/{nelng}/{swlng}', 'Places\PlaceController@index');

    //============================PLACES=======================================
    Route::group(['middleware' => 'auth:api'], function() {
        //Create new Place
        Route::post('placequeue', 'Places\PlaceQueueController@store');     //tested
        //Update place
        Route::put('place', 'Places\PlaceController@store');                //tested
        //Delete place
        Route::delete('place/{id}', 'Places\PlaceController@destroy');      //tested
    });



    //======================Find event by sort====================
    Route::post('find_events', 'Events\EventFindController@findEvents');
    Route::post('find_recommended_events', 'Events\EventFindController@recommendedEvents');


    //===========================EVENTS==============================
    //List Events
    Route::get('events', 'Events\EventController@index');
    //List Events by place
    Route::get('events/{id}', 'Events\EventController@get_events_by_place');
    //List Events by place and date
    Route::get('events/{id}/{event_id}/{date}', 'Events\EventController@get_events_by_place_and_date');
    //Get Event
    Route::get('event/{id}', 'Events\EventController@show');
    //Get Declined Event
    Route::get('declinedevent/{id}', 'Events\EventController@getDeclinedEvent');

    //Get which event happening right now or which event is closest one
    Route::get('liveevent/{id}', 'Events\EventController@closestEvent');

    Route::group(['middleware' => 'auth:api'], function() {
        //Create new Event
        Route::post('event', 'Events\EventController@store');           //tested
        //Update Event
        Route::put('event', 'Events\EventController@store');            //tested
        //Delete Event
        Route::delete('event/{id}', 'Events\EventController@destroy');  //tested
    });




    //==============================People Going=============================================
    Route::get('people_going', 'Events\PeopleGoingController@index');
    Route::get('people_going/{id}', 'Events\PeopleGoingController@returnByEvent');

    Route::group(['middleware' => 'auth:api'], function() {
        //Add person to event
        Route::post('person', 'Events\PeopleGoingController@store');    //tested
        //Remove person from event
        Route::delete('person/{id}', 'Events\PeopleGoingController@destroy');   //tested
    });