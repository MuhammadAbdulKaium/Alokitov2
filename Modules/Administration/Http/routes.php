<?php

Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'administration', 'namespace' => 'Modules\Administration\Http\Controllers'], function()
{
    Route::get('/', 'AdministrationController@index');
});
