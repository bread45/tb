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
Route::group(['middleware' => ['auth', 'check-permission']], function() {
    Route::post('/general_setting/order', 'GeneralSettingsController@orderChange')->name('general_setting.order');
    Route::get('/general_setting/status/{id}', 'GeneralSettingsController@statusChange')->name('general_setting.status');
    Route::get('/general_setting/getall', 'GeneralSettingsController@index')->name('general_setting.getall');
    Route::resource('/general_setting', 'GeneralSettingsController');
});
