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
    Route::get('/judgments/status/{id}', 'JudgmentsController@statusChange')->name('judgments.status');
    Route::get('/judgments/document/{id}', 'JudgmentsController@removedocument')->name('judgments.document');
    Route::get('/judgments/image/{id}', 'JudgmentsController@removeimage')->name('judgments.image');
    Route::post('/judgments/getall', 'JudgmentsController@index')->name('judgments.getall');
    Route::post('/judgments/sendletter', 'JudgmentsController@sendletter')->name('judgments.sendletter');
    Route::resource('/judgments', 'JudgmentsController');
     
});
