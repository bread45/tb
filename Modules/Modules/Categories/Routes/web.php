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
Route::group(['middleware' => ['auth','check-permission']], function() {
    Route::get('/categories/status/{id}', 'CategoriesController@statusChange')->name('categories.status');
    Route::get('/categories/getall', 'CategoriesController@getAll')->name('categories.getall');
    Route::resource('/categories', 'CategoriesController');
});
