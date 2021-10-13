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

//Route::prefix('cadetfees')->group(function() {

    Route::group(['middleware' => ['web','auth'], 'prefix' => 'cadetfees'], function()
    {
//        Fees Head
    Route::get('/fees/head', 'FeesHeadController@index');
    Route::get('/fees/head/create', 'FeesHeadController@create');
    Route::post('/fees/head/store', 'FeesHeadController@store');
    Route::get('/fees/head/edit/{id}', 'FeesHeadController@edit');
    Route::post('/fees/head/update/{id}', 'FeesHeadController@update');

//    Fees Structure
    Route::get('/fees/structure/create', 'FeesStructureController@create');
    Route::post('/fees/structure/store', 'FeesStructureController@store');
    Route::get('/fees/structure/edit/{id}', 'FeesStructureController@edit');
    Route::post('/fees/structure/update/{id}', 'FeesStructureController@update');

//    Fees Structure Details
    Route::get('/fees/structure/details/create/{id}', 'FeesStructureDetailsController@create');
    Route::get('/fees/structure/details/delete/{id}', 'FeesStructureDetailsController@destroy');
    Route::post('/fees/structure/details/store/', 'FeesStructureDetailsController@store');


    Route::get('/', 'CadetFeesController@index');
    Route::get('/create/fees', 'CadetFeesController@createFees');
    Route::get('/get/form/batch/{id}', 'CadetFeesController@get');
    Route::post('/assign/cadet/fees', 'CadetFeesController@assignCadetFees');
    Route::get('/generate/fees', 'CadetFeesController@generateCadetFees');
    Route::post('/generate/cadet/fees', 'CadetFeesController@storeGenerateCadetFees');

//    Manual Payment
    Route::get('/manual/fees', 'CadetFeesController@manualCadetFees');
    Route::post('/manage/search/fees/manually', 'CadetFeesController@searchCadetFeesManually');
    Route::get('/calculate/fees/manually/{id}', 'CadetFeesController@calculateCadetFeesManually');
    Route::post('/paid/fees/manually/{id}', 'CadetFeesController@paidCadetFeesManually');


});
