<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
/* ROUTES FOR INDEX */
Route::get('/', 'IndexController@index');
Route::post('/signup', 'IndexController@signup');
Route::post('/check_username', 'IndexController@check_username');
Route::get('/load_settings_index', 'IndexController@load_settings_index');

/* ROUTES FOR LOGIN */
Route::get('/login', 'LoginController@index');
Route::post('/newLogin', 'LoginController@login');

/*ROUTES FOR HOME PAGE */
Route::get('/home', 'HomeController@index');

/*ROUTES FOR FEEDS */
Route::post('/share_post', 'FeedController@bake_post');
Route::post('/edit_post', 'FeedController@edit_post');
Route::post('/delete_post', 'FeedController@delete_post');
Route::post('/unlike_like_post_and_comment', 'FeedController@unlike_like_post_and_comment');
Route::post('/add_comment', 'FeedController@add_comment');


