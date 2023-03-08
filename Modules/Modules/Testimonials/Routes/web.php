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
    Route::post('/testimonials/order', 'TestimonialsController@orderChange')->name('testimonials.order');
    Route::get('/testimonials/status/{id}', 'TestimonialsController@statusChange')->name('testimonials.status');
    Route::get('/testimonials/show_slider/{id}', 'TestimonialsController@showSliderChange')->name('testimonials.show_slider');
    Route::get('/testimonials/getall', 'TestimonialsController@index')->name('testimonials.getall');
    Route::resource('/testimonials', 'TestimonialsController');
});
