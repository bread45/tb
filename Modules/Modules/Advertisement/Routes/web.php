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
    Route::resource('/advertisement', 'AdvertisementController');
    Route::get('/advertisement/list/getall', 'AdvertisementController@index')->name('advertisement.getall');
    Route::get('/advertisement/status/{id}', 'AdvertisementController@statusChange')->name('advertisement.status');
    
});
});
