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

Route::get('/', 'TheardsController@index');

Auth::routes();

Route::get('/home', 'HomeController@index');
Route::get('/theards', 'TheardsController@index');
Route::get('/theards/create', 'TheardsController@create');
Route::get('/theards/{channel}/{theard}', 'TheardsController@show');
Route::delete('/theards/{channel}/{theard}', 'TheardsController@destroy');
Route::post('/theards', 'TheardsController@store');
Route::get('/theards/{channel}/{theard}/replies', 'RepliesController@index');
Route::post('/theards/{channel}/{theard}/replies', 'RepliesController@store');
Route::get('/theards/{channel}', 'TheardsController@index');
Route::post('/replies/{reply}/favorites', 'FavoritesController@store');
Route::delete('/replies/{reply}/favorites', 'FavoritesController@destroy');
Route::post('/theards/{channel}/{theard}/subscriptions', 'TheardSubscriptionController@store')->middleware('auth');
Route::delete('/theards/{channel}/{theard}/subscriptions', 'TheardSubscriptionController@destroy')->middleware('auth');

Route::patch('/replies/{reply}', 'RepliesController@update');
Route::delete('/replies/{reply}', 'RepliesController@destroy');

Route::get('/profiles/{user}', 'ProfilesController@show')->name('profile');
Route::get('/profiles/{user}/notifications', 'UserNotificationsController@index');
Route::delete('/profiles/{user}/notifications/{notification}', 'UserNotificationsController@destroy');
