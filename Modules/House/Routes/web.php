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

Route::prefix('house')->group(function () {
    // house Routes
    Route::get('/manage-house', 'HouseController@index');
    Route::post('/create-house', 'HouseController@store');
    Route::get('/edit-house/{id}', 'HouseController@edit');
    Route::post('/update-house/{id}', 'HouseController@update');
    Route::get('/delete-house/{id}', 'HouseController@destroy');


    // Room Routes
    Route::post('/create-room', 'HouseController@createRoom');
    Route::get('/edit-room/{id}', 'HouseController@editRoom');
    Route::get('/delete-room/{id}', 'HouseController@deleteRoom');
    Route::post('/update-room/{id}', 'HouseController@updateRoom');
    Route::get('/assign-beds/{id}', 'HouseController@assignBeds');
    // Room Ajax Routes
    Route::get('/find-sections/from-academic-level', 'HouseController@findSectionsFromAcaemicLevel');
    Route::get('/find-students/from-section', 'HouseController@findStudentsFromSection');
    Route::get('/assign-student/to-bed', 'HouseController@assignStudentToBed');
    Route::get('/remove-student/from-bed', 'HouseController@removeStudentFromBed');


    // Evaluation Routes
    Route::get('/cadets-evaluation', 'HouseController@cadetsEvaluation');
    Route::get('/weightage-config', 'HouseController@weightageConfig');
    Route::post('/save-weightage', 'HouseController@saveWeightage');
    // Evaluation Ajax Routes
    Route::get('/get-semester/from-year', 'HouseController@getSemesterFromYear');
    Route::get('/get-weightage-events/from-type', 'HouseController@getWeightageEventsFromType');
    Route::get('/delete-weightage', 'HouseController@deleteWeightage');
    Route::get('/get-events/from-type', 'HouseController@getEventsFromType');
    Route::get('/search-evaluation-table', 'HouseController@searchEvaluationTable');


    // View Houses Routes
    Route::get('/view', 'HouseController@viewHouses');
    Route::get('/search/house', 'HouseController@searchHouse');

});
