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
    Route::resource('/casebooks', 'CasebooksController');
    Route::post('/casebooks/getall', 'CasebooksController@index')->name('casebooks.getall');
    Route::get('/casebooks/status/{id}', 'CasebooksController@statusChange')->name('casebooks.status');
    
});
 