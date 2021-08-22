<?php

Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'payroll', 'namespace' => 'Modules\Payroll\Http\Controllers'], function()
{
    //Salary Head
    Route::get('/salary/head', 'SalaryHeadController@index');
    Route::get('/salary/create', 'SalaryHeadController@create');
    Route::post('/salary/store', 'SalaryHeadController@store');
    Route::get('/salary/edit/{id}', 'SalaryHeadController@edit');
    Route::post('/salary/update', 'SalaryHeadController@update');
    Route::post('/salary/delete', 'SalaryHeadController@destroy');

    //Salary Grade
    Route::get('/salary/grade', 'SalaryGradeController@index');
    Route::get('/salary/grade/create', 'SalaryGradeController@create');
    Route::post('/salary/grade/store', 'SalaryGradeController@store');
    Route::get('/salary/grade/edit/{id}', 'SalaryGradeController@edit');
    Route::post('/salary/grade/update', 'SalaryGradeController@update');
    Route::post('/salary/grade/delete', 'SalaryGradeController@destroy');

    //Salary Assign
    Route::get('/salary/assign', 'SalaryAssignController@index');
    Route::get('/salary/assign/create', 'SalaryAssignController@create');
    Route::post('/salary/assign/store', 'SalaryAssignController@store');
    Route::get('/salary/assign/edit/{id}', 'SalaryAssignController@edit');
    Route::post('/salary/assign/update', 'SalaryAssignController@update');
    Route::post('/salary/assign/delete', 'SalaryAssignController@destroy');

    //Salary Assign
//    Route::get('/salary/assign', 'SalaryAssignController@index');
});