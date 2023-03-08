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
    Route::get('/sliders/status/{id}', 'SlidersController@statusChange')->name('sliders.status');
    Route::get('/sliders/getall', 'SlidersController@index')->name('sliders.getall');
    Route::resource('/sliders', 'SlidersController');
    
    Route::get('/sliderimage/status/{id}', 'SliderImageController@statusChange')->name('sliderimage.status');
    Route::post('/sliderimage/getall', 'SliderImageController@index')->name('sliderimage.getall');
    Route::get('/sliderimage/sliders/{slider_id}', 'SliderImageController@index')->name('sliderimage.slider');
    Route::resource('/sliderimage', 'SliderImageController');
});
