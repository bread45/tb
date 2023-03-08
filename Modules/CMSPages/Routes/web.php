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
    Route::post('/cms_pages/order', 'CMSPagesController@orderChange')->name('cms_pages.order');
    Route::get('/cms_pages/status/{id}', 'CMSPagesController@statusChange')->name('cms_pages.status');
    Route::get('/cms_pages/getall', 'CMSPagesController@index')->name('cms_pages.getall');
    Route::resource('/cms_pages', 'CMSPagesController');
});
});
