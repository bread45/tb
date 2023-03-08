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

Route::prefix('stepmanage')->group(function() {
    Route::get('/', 'StepManageController@index');
});
Route::group(['middleware' => ['auth', 'check-permission']], function() {
    Route::post('/stepmanage/order', 'StepManageController@orderChange')->name('stepmanage.order');
    Route::get('/stepmanage/status/{id}', 'StepManageController@statusChange')->name('stepmanage.status');
    Route::get('/stepmanage/getall', 'StepManageController@index')->name('stepmanage.getall');
    Route::resource('/stepmanage', 'StepManageController');
});
