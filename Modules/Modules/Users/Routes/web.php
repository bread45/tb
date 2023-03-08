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
    Route::get('/users/status/{id}', 'OrganisersController@statusChange')->name('users.status');
    Route::post('/users/getall', 'OrganisersController@index')->name('users.getall');
    Route::resource('/users', 'OrganisersController');
    
    Route::get('/suppliers/status/{id}', 'SuppliersController@statusChange')->name('suppliers.status');
    Route::post('/suppliers/getall', 'SuppliersController@index')->name('suppliers.getall');
    Route::resource('/suppliers', 'SuppliersController');
});
});
