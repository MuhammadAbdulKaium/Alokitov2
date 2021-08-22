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

Route::prefix('healthcare')->group(function () {
    Route::get('/', 'HealthCareController@index');


    // Prescription
    Route::get('/prescription', 'HealthCareController@index');
    Route::get('/create/prescription', 'HealthCareController@create');
    Route::post('/store/prescription', 'HealthCareController@store');
    Route::get('/edit/prescription/{id}', 'HealthCareController@edit');
    Route::post('/update/prescription/{id}', 'HealthCareController@update');
    Route::get('/prescription/status/change/{id}/{status}', 'HealthCareController@prescriptionStatusChange');
    Route::get('/print/prescription/{id}', 'HealthCareController@printPrescription');
    Route::get('/delete/prescription/{id}', 'HealthCareController@destroy');

    //Prescription Ajax Routes
    Route::get('/users/from/user-type', 'HealthCareController@usersFromUserType');


    // Investigation
    Route::get('/investigation', 'InvestigationController@index');
    Route::get('/create/investigation', 'InvestigationController@create');
    Route::get('/edit/investigation/{id}', 'InvestigationController@edit');
    Route::post('/store/investigation', 'InvestigationController@store');
    Route::post('/update/investigation/{id}', 'InvestigationController@update');
    Route::get('/delete/investigation/{id}', 'InvestigationController@destroy');

    // Investigation Report
    Route::get('/investigation/reports', 'InvestigationController@investigationReports');
    Route::get('/set/report/{id}', 'InvestigationController@setReport');
    Route::post('/save/report/{id}', 'InvestigationController@saveReport');
    Route::get('/view/report/{id}', 'InvestigationController@viewReport');
    Route::get('/deliver/report/{id}', 'InvestigationController@deliverReport');

    // Drug Report
    Route::get('/drug/reports', 'HealthCareController@drugReports');
    Route::get('/drug/status/change/{id}/{status}', 'HealthCareController@drugStatusChange');
});
