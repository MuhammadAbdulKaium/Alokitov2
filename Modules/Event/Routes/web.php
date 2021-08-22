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

Route::group(['middleware' => ['web', 'auth', 'access-permission'], 'prefix' => 'event'], function () {

    // Event index
    Route::get('/', 'EventController@index');
    Route::get('/event/create', 'EventController@create');
    Route::post('/store', 'EventController@store');
    Route::get('/delete/{id}', 'EventController@destroy');
    Route::get('/edit/{id}', 'EventController@edit');
    Route::post('/update/{id}', 'EventController@update');
    Route::get('/assign/date/{id}', 'EventController@assignDate');
    Route::post('/search/students', 'EventController@searchStudents');
    Route::post('/save/event/date', 'EventController@saveEventDate');
    Route::get('/edit/team/{id}', 'EventController@editEventTeam');
    Route::post('/update/event/team', 'EventController@updateEventTeam');

    //Event Ajax Routes
    Route::get('/find/sub_cat/{id}', 'EventController@getAjaxTypeCategory');
    Route::get('/find/activity/{id}', 'EventController@getAjaxCategoryActivity');
    Route::get('/get/students/from/house', 'EventController@getStudentsFromHouse');
    Route::get('/get/sections/students/from/batch', 'EventController@getSectionsStudentsFromBatch');
    Route::get('/get/students/from/section', 'EventController@getStudentsFromSection');
    Route::get('/add/team', 'EventController@addTeam');
    Route::get('/remove/event/date', 'EventController@removeEventDate');
    Route::get('/delete/event/team', 'EventController@deleteEventTeam');


    // Event Marks Routes
    Route::get('/marks', 'EventController@eventMarks');

    // Event Marks Ajax Routes
    Route::get('/date-time/from/event', 'EventController@dateTimeFromEvent');
    Route::get('/team/from/date-time', 'EventController@teamFromDateTime');
    Route::post('/student-search/for/marks', 'EventController@studentSearchForMarks');
    Route::post('/save/students/event-marks', 'EventController@saveStudentsEventMarks');
});
