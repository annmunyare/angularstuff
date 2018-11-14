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
    return view('welcome');
});

Route::group(['prefix' => 'api/v1'], function() {

    Route::resource('products', 'ProductController');
   });
   
Route::group(['prefix' => 'api/v1'], function() {

    Route::resource('category', 'CategoryController');
   });
   Route::get('/sent',  'FlowController@getstate');

   //Routes for message
Route::get('/messages',  'MessageController@index');
Route::get('/messages/create',  'MessageController@create');
Route::post('/messages',  'MessageCController@store');
Route::get('/messages/edit/{id}',  'MessageCController@edit');
Route::patch('/messages/{id}',  'MessageCController@update');
Route::get('/messages/delete/{id}',  'MessageCController@destroy');

Route::get('/reports',  'ReportController@index');
// Route::get('/wts',  array( 'middleware' => 'cors','uses' =>  'MessageController@store'));

