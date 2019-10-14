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

use App\Theard;

Route::get('/', 'TheardsController@index');

Auth::routes();

Route::get('/home', 'HomeController@index');
Route::get('/questions', 'TheardsController@index');
Route::get('/questions/create', 'TheardsController@create');
Route::get('/questions/{theard}', 'TheardsController@show');
Route::delete('/questions/{theard}', 'TheardsController@destroy');
Route::post('/questions', 'TheardsController@store');
Route::get('/questions/{theard}/replies', 'RepliesController@index');
Route::post('/questions/{theard}/replies', 'RepliesController@store');
Route::get('/tagged/{channel}', 'TheardsController@index');
Route::post('/replies/{reply}/favorites', 'FavoritesController@store');
Route::delete('/replies/{reply}/favorites', 'FavoritesController@destroy');
Route::post('/questions/{theard}/subscriptions', 'TheardSubscriptionController@store')->middleware('auth');
Route::delete('/questions/{theard}/subscriptions', 'TheardSubscriptionController@destroy')->middleware('auth');

Route::patch('/replies/{reply}', 'RepliesController@update');
Route::delete('/replies/{reply}', 'RepliesController@destroy');

Route::get('/profiles/{user}', 'ProfilesController@show')->name('profile');
Route::get('/profiles/{user}/notifications', 'UserNotificationsController@index');
Route::delete('/profiles/{user}/notifications/{notification}', 'UserNotificationsController@destroy');

Route::get('/test', function () {

    $theards = Theard::all();

    foreach ($theards as $theard) {
        $theard->update(['slug' => str_slug($theard->title)]);
        $theard->save();
    }
});
