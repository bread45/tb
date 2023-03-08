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
Route::group(['middleware' => ['auth']], function() {
    Route::get('permissions/modules/status/{id}', 'ModuleController@statusChange')->name('admin.permissions.modules.status');
    Route::get('permissions/modules/getall', 'ModuleController@index')->name('admin.permissions.modules.getall');
    Route::resource('permissions/modules', 'ModuleController');

    Route::get('permissions/routes/status/{id}', 'RouteManagerController@statusChange')->name('admin.permissions.routes.status');
    Route::get('permissions/routes/getall', 'RouteManagerController@index')->name('admin.permissions.routes.getall');
    Route::resource('permissions/routes', 'RouteManagerController');

    Route::get('permissions/manager/loadpermission/{id}', 'PermissionsController@loadPermissions')->name('admin.permissions.loadpermission');
    Route::any('permissions/store', 'PermissionsController@store')->name('manager.store');
    Route::get('permissions/manager', 'PermissionsController@index')->name('admin.permissions.index');
    
});

