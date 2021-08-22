<?php

Route::group(['middleware' => ['web', 'auth',], 'prefix' => 'role-management', 'namespace' => 'Modules\RoleManagement\Http\Controllers'], function () {
//   Users
    Route::get('/users/list', 'UserController@index');
    Route::get('/users/add', 'UserController@create');
    Route::post('/users/create', 'UserController@store');
    Route::get('/users/delete/{id}', 'UserController@delete');

//    Roles
    Route::get('/role/add', 'RoleController@create');
    Route::post('/role/create', 'RoleController@store');
    Route::get('/role/delete/{id}', 'RoleController@delete');
//    Menus
    Route::get('/menus/list', 'MenuController@index');
    Route::get('/menus/create', 'MenuController@create');
    Route::get('/menus/edit/{id}', 'MenuController@edit');
    Route::post('/menus/store', 'MenuController@store');
    Route::post('/menus/update', 'MenuController@updateMenu');

//    Privillage
    Route::get('/privillage/list/{id}', 'PrivillageController@index');

});