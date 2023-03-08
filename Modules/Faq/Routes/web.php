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

    Route::get('/faqs_category/status/{id}', 'FaqCategoriesController@statusChange')->name('faqs_category.status');
    Route::get('/faqs_category/getall', 'FaqCategoriesController@index')->name('faqs_category.getall');
    Route::resource('/faqs_category', 'FaqCategoriesController');


    Route::post('/faq/order', 'FaqController@orderChange')->name('faq.order');
    Route::get('/faq/status/{id}', 'FaqController@statusChange')->name('faq.status');
    Route::get('/faq/getall', 'FaqController@index')->name('faq.getall');
    Route::resource('/faq', 'FaqController');
});
