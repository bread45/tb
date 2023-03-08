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

Route::group(['middleware' => ['auth'],"prefix"=>'admin'], function() {
    Route::post('/blogs/order', 'BlogsController@orderChange')->name('blogs.order');
    Route::get('/blogs/status/{id}', 'BlogsController@statusChange')->name('blogs.status');
    Route::get('/blogs/getall', 'BlogsController@index')->name('blogs.getall');
    Route::resource('/blogs', 'BlogsController');

    Route::get('/blogs_category/status/{id}', 'BlogCategoriesController@statusChange')->name('blogs_category.status');
    Route::get('/blogs_category/getall', 'BlogCategoriesController@index')->name('blogs_category.getall');
    Route::resource('/blogs_category', 'BlogCategoriesController');

    Route::get('/tag_master/status/{id}', 'BlogTagsController@statusChange')->name('tag_master.status');
    Route::get('/tag_master/getall', 'BlogTagsController@index')->name('tag_master.getall');
    Route::resource('/tag_master', 'BlogTagsController');
});
