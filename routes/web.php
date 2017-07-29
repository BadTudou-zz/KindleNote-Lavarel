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

Route::get('/', function () {
    if (Auth::user()){
        return Redirect::to(action('HomeController@index'));
    }
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/notes/search/', 'NoteController@search');
Route::get('/notes/download/', 'NoteController@download');
Route::get('/logout', 'AuthController@logout');

Route::resource('notes', 'NoteController');


Route::group(['middleware' => 'auth','prefix' => 'users'], function()
{
    Route::get('/', 'UserController@index');
    Route::get('{id}', 'UserController@show');
    Route::patch('{id}', 'UserController@update');
    Route::delete('{id}', 'UserController@destroy');
});

Route::group(['middleware' => 'auth','prefix' => 'notes'], function()
{
    Route::get('/', 'NoteController@index');
    Route::get('create', 'NoteController@create');
    Route::post('/', 'NoteController@store');
    Route::delete('{id}', 'NoteController@destroy');
    Route::get('/{id}/markdown', 'NoteController@markdown');
    Route::post('batch', 'NoteController@batch');
});

Route::group(['middleware' => 'auth','prefix' => 'clipping'], function()
{
    Route::post('/', 'ClippingController@store');
    Route::get('/download', 'ClippingController@download');
});

Route::group(['middleware' => 'auth','prefix' => 'notification'], function()
{
    Route::post('/', 'NotificationController@markAllAsRead');
    Route::get('/download', 'ClippingController@download');
});