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

Route::get('/returncreatedevents', 'HomeController@returnCreatedEvents')->middleware('auth');
Route::get('/returngoingtoevents', 'HomeController@returnGoingToEvents')->middleware('auth');
Route::get('/returnacceptedplaces', 'HomeController@returnAcceptedPlaces')->middleware('auth');
Route::get('/returndeclinedplaces', 'HomeController@returnDeclinedPlaces')->middleware('auth');
Route::get('/returnsubmitedplaces', 'HomeController@returnSubmitedPlaces')->middleware('auth');
Route::get('/returnsubmitedevents', 'HomeController@returnSubmitedEvents')->middleware('auth');
Route::get('/returndeclinedevents', 'HomeController@returnDeclinedEvents')->middleware('auth');

Route::post('/resubmit/{id}', 'Places\DeclinedPlacesController@update')->middleware('auth');
Route::delete('/decplace/{id}', 'Places\DeclinedPlacesController@destroy')->middleware('auth');

Route::post('/resubmit_event', 'HomeController@resubmitEvent')->middleware('auth');
Route::delete('/decevent/{id}', 'HomeController@deleteEvent')->middleware('auth');



//------------------------------PLACE INTERFACE---------------------------------------
Route::get('/event/{id}/{title}', 'Events\EventController@show_event_page')->middleware('auth');
Route::get('/messages/{id}', 'Events\EventController@fetchMessages');
Route::post('/messages', 'Events\EventController@sendMessage');


//------------------------------ADMIN--------------------------------------------------
Route::get('/admin', 'AdminController@index')->middleware('admin');
Route::get('/admin/places_to_confirm', 'AdminController@places')->middleware('admin');
Route::get('/admin/events_to_confirm', 'AdminController@events')->middleware('admin');
Route::get('/admin/places', 'AdminController@allPlaces')->middleware('admin');
Route::get('/admin/users', 'AdminController@users')->middleware('admin');
Route::get('/admin/sporttypes', 'AdminController@sportTypes')->middleware('admin');
Route::put('/admin/updateplace', 'Places\PlaceController@update')->middleware('admin');
Route::get('/admin/deleteplace/{id}', 'AdminController@deletePlace')->middleware('admin');


//==============================Notification==========================================
Route::get('/notifications/get', 'NotificationController@get')->middleware('auth');
Route::post('/notification/read', 'NotificationController@read')->middleware('auth');
Route::post('/notification/readall', 'NotificationController@readAll')->middleware('auth');


//===================================Types====================================================
Route::post('/admin/sporttypes/add', 'TypeController@store')->middleware('admin');
Route::get('/admin/sporttypes/edit/{id}', 'TypeController@openUpdate')->middleware('admin');
Route::post('/admin/sporttypes/edit_type/{id}', 'TypeController@update')->middleware('admin');
Route::delete('/admin/sporttypes/delete/{id}', 'TypeController@destroy')->middleware('admin');

Route::get('/admin/deleteuser/{id}', 'AdminController@deleteUser')->middleware('admin');


//=====================================Accept/Decline Events and Places=============================
Route::get('/admin/accplace/{id}', 'AdminController@acceptPlace')->middleware('admin');    //tested
Route::get('/admin/decplace/{id}', 'AdminController@declinePlace')->middleware('admin');   //tested
Route::get('/admin/accevent/{id}', 'AdminController@acceptEvent')->middleware('admin');    //tested
Route::get('/admin/decevent/{id}', 'AdminController@declineEvent')->middleware('admin');   //tested
Route::get('/admin/destroyplace/{id}', 'AdminController@destroyPlace')->middleware('admin');    
Route::get('/admin/destroyevent/{id}', 'AdminController@destroyEvent')->middleware('admin');   


//============================Social login/register profile================================
Route::get('/auth/redirect/{provider}', 'SocialController@redirect');
Route::get('/callback/{provider}', 'SocialController@callback');
Route::get('/user/{auth_id}', 'PagesController@show_profile');
