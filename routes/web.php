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

// Home Page
Route::get('/', 'PagesController@getIndex');

// About Page
Route::get('about', 'PagesController@getAbout');

// Contact Page
Route::get('contact', 'PagesController@getContact');

// Post Resource Controller ( CRUD )
Route::resource('posts', 'PostController');
