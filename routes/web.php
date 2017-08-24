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



// Authentication Routes
//Route::get('auth/login', 'Auth\LoginController@getLogin');
//Route::post('auth/login', 'Auth\LoginController@postLogin');
//Route::get('auth/logout', 'Auth\LoginController@postLogout');
//
//// Registration Routes
//Route::get('auth/register', 'Auth\LoginController@getRegister');
//Route::post('auth/register', 'Auth\LoginController@postRegister');

//Authentication Routes(login and register process) -- version 5.4

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::auth();

Route::get('profile', function () {
    // Only authenticated users may enter...
})->middleware('auth');

// slug format: w: word d:number -:dash _:underscore
Route::get('/blog/{slug}', ['as' => 'blog.single', 'uses' => 'BlogController@getSingle'])->where('slug', '[\w\d\-\_]+');

// Blog Page
Route::get('blog', ['uses' => 'BlogController@getIndex', 'as' => 'blog.index']);

// Home Page
Route::get('/', 'PagesController@getIndex');

// About Page
Route::get('about', 'PagesController@getAbout');

// Contact Page
Route::get('contact', 'PagesController@getContact');

// Post Resource Controller ( CRUD )
Route::resource('posts', 'PostController');

