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
Route::post('/resubmit/{id}', 'DeclinedPlacesController@update')->middleware('auth');
Route::delete('/decplace/{id}', 'DeclinedPlacesController@destroy')->middleware('auth');



//------------------------------PLACE INTERFACE---------------------------------------
Route::get('/event/{id}', 'EventController@show_event_page')->middleware('auth');
Route::get('/messages/{id}', 'EventController@fetchMessages');
Route::post('/messages', 'EventController@sendMessage');


//------------------------------ADMIN--------------------------------------------------
Route::get('/admin', 'AdminController@index')->middleware('admin');
Route::get('/admin/places', 'AdminController@places')->middleware('admin');
Route::get('/admin/users', 'AdminController@users')->middleware('admin');
Route::get('/admin/sporttypes', 'AdminController@sportTypes')->middleware('admin');

Route::post('/admin/sporttypes/add', 'TypeController@store')->middleware('admin');
Route::delete('/admin/sporttypes/delete/{id}', 'TypeController@destroy')->middleware('admin');
Route::get('/admin/deleteuser/{id}', 'AdminController@deleteUser')->middleware('admin');
Route::get('/admin/accplace/{id}', 'AdminController@acceptPlace')->middleware('admin');
Route::get('/admin/decplace/{id}', 'AdminController@declinePlace')->middleware('admin');


Route::get('/auth/redirect/{provider}', 'SocialController@redirect');
Route::get('/callback/{provider}', 'SocialController@callback');
Route::get('/user/{auth_id}', 'PagesController@show_profile');
