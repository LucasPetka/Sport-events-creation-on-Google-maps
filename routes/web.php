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

Auth::routes();
Route::get('/', 'PagesController@index');
Route::get('/home/myevents', 'PagesController@myevents')->middleware('auth');
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/admin', 'AdminController@index')->middleware('admin');
Route::get('/admin/accplace/{id}', 'AdminController@acceptPlace')->middleware('admin');
Route::get('/admin/decplace/{id}', 'AdminController@declinePlace')->middleware('admin');
