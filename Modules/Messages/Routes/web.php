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
Route::group(['prefix' => 'admin'], function () {
    Route::group(['middleware' => ['auth', 'check-permission']], function() {
        Route::resource('/messages', 'MessagesController');
        Route::post('/messages/getall', 'MessagesController@index')->name('messages.getall');
        Route::get('/messages/status/{id}', 'MessagesController@statusChange')->name('messages.status');
        Route::get('/messages/{id}', 'MessagesController@show')->name('messages.show');
    });
});
