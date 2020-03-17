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

// Route::get('/', function () {
//     return view('welcome');
// });


Auth::routes(['verify' => true]); 

Route::get('/', 'PagesController@index');
Route::get('/home', 'HomeController@index')->name('home');


//--------------------EVENT FINDER------------------------------------------------
Route::get('/find_event', 'Events\EventFindController@index');


//-------------------PAYMENT AND EVENT VALIDATION------------------------------------
Route::get('/place_owner/{id}', 'PagesController@payment_page')->middleware('auth');
Route::get('/validate_time', 'Events\EventController@check_time');


//----------------------------PROFILE-------------------------------------------
Route::post('/update_profile', 'HomeController@update_profile')->middleware('auth');
Route::post('/resubmit/{id}', 'Places\DeclinedPlacesController@update')->middleware('auth');
Route::delete('/decplace/{id}', 'Places\DeclinedPlacesController@destroy')->middleware('auth');
Route::get('/returncreatedevents', 'HomeController@returnCreatedEvents')->middleware('auth');
Route::get('/returngoingtoevents', 'HomeController@returnGoingToEvents')->middleware('auth');
Route::get('/returnacceptedplaces', 'HomeController@returnAcceptedPlaces')->middleware('auth');
Route::get('/returndeclinedplaces', 'HomeController@returnDeclinedPlaces')->middleware('auth');
Route::get('/returnsubmitedplaces', 'HomeController@returnSubmitedPlaces')->middleware('auth');
Route::get('/returnsubmitedevents', 'HomeController@returnSubmitedEvents')->middleware('auth');
Route::get('/returndeclinedevents', 'HomeController@returnDeclinedEvents')->middleware('auth');



//------------------------------PLACE INTERFACE---------------------------------------
Route::get('/event/{id}', 'Events\EventController@show_event_page')->middleware('auth');
Route::get('/messages/{id}', 'Events\EventController@fetchMessages');
Route::post('/messages', 'Events\EventController@sendMessage');


//------------------------------ADMIN--------------------------------------------------
Route::get('/admin', 'AdminController@index')->middleware('admin');
Route::get('/admin/places', 'AdminController@places')->middleware('admin');
Route::get('/admin/events', 'AdminController@events')->middleware('admin');
Route::get('/admin/users', 'AdminController@users')->middleware('admin');
Route::get('/admin/sporttypes', 'AdminController@sportTypes')->middleware('admin');

Route::post('/admin/sporttypes/add', 'TypeController@store')->middleware('admin');
Route::delete('/admin/sporttypes/delete/{id}', 'TypeController@destroy')->middleware('admin');
Route::get('/admin/deleteuser/{id}', 'AdminController@deleteUser')->middleware('admin');
Route::get('/admin/accplace/{id}', 'AdminController@acceptPlace')->middleware('admin');
Route::get('/admin/decplace/{id}', 'AdminController@declinePlace')->middleware('admin');
Route::get('/admin/accevent/{id}', 'AdminController@acceptEvent')->middleware('admin');
Route::get('/admin/decevent/{id}', 'AdminController@declineEvent')->middleware('admin');


Route::get('/auth/redirect/{provider}', 'SocialController@redirect');
Route::get('/callback/{provider}', 'SocialController@callback');
Route::get('/user/{auth_id}', 'PagesController@show_profile');
