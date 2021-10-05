<?php

Route::group(['middleware' => ['web', 'auth','setting-permission'], 'prefix' => 'website', 'namespace' => 'Modules\Website\Http\Controllers'], function()
{
    //Routes for website information menu
    Route::get('/information', 'InformationController@index');
    Route::get('/information/create', 'InformationController@create');
    Route::post('/information/store', 'InformationController@store');
    Route::get('/information/edit/{id}', 'InformationController@edit');
    Route::post('/information/update/{id}', 'InformationController@update');

    //Routes for website information menu
    Route::get('/committee', 'CommitteeController@index');
    Route::get('/committee/create', 'CommitteeController@create');
    Route::post('/committee/store', 'CommitteeController@store');
    Route::get('/committee/show/{id}', 'CommitteeController@show');
    Route::get('/committee/edit/{id}', 'CommitteeController@edit');
    Route::post('/committee/update/{id}', 'CommitteeController@update');
    Route::get('/committee/delete/{id}', 'CommitteeController@destroy');

    //Routes for website rules  menu
    Route::get('/rules', 'RulesController@index');
    Route::get('/rules/create', 'RulesController@create');
    Route::post('/rules/store', 'RulesController@store');
    Route::get('/rules/edit/{id}', 'RulesController@edit');
    Route::post('/rules/update/{id}', 'RulesController@update');
    Route::get('/rules/delete/{id}', 'RulesController@destroy');

    //Extra Information for website rules  menu,
    //here type = facilities or publications or circular or extra_curricular or books_syllabus
    Route::get('/extra/{type}', 'ExtraInformationController@index');
    Route::get('/extra/create/{type}', 'ExtraInformationController@create');
    Route::post('/extra/store', 'ExtraInformationController@store');
    Route::get('/extra/show/{type}/{id}', 'ExtraInformationController@show');
    Route::get('/extra/edit/{type}/{id}', 'ExtraInformationController@edit');
    Route::post('/extra/update/{id}', 'ExtraInformationController@update');
    Route::get('/extra/delete/{id}', 'ExtraInformationController@destroy');
    Route::get('/extra/facility-image-add/{id}', 'ExtraInformationController@facilityImageAddModal');
    Route::post('/extra/add-image/{id}', 'ExtraInformationController@addFacilityImage');
    Route::get('/extra/facility-image/delete/{id}/{key}', 'ExtraInformationController@destroyFacilityImage');

    //Routes for website public exam results
    Route::get('/public_exam', 'PublicExamResultController@index');
    Route::get('/public_exam/create', 'PublicExamResultController@create');
    Route::post('/public_exam/store', 'PublicExamResultController@store');
    Route::get('/public_exam/edit/{id}', 'PublicExamResultController@edit');
    Route::post('/public_exam/update/{id}', 'PublicExamResultController@update');
    Route::get('/public_exam/delete/{id}', 'PublicExamResultController@destroy');


    //Routes for website images
    Route::get('/image', 'ImageController@index');
    Route::get('/image/create', 'ImageController@create');
    Route::post('/image/store', 'ImageController@store');
    Route::get('/image/show/{id}', 'ImageController@show');
    Route::get('/image/edit/{id}', 'ImageController@edit');
    Route::post('/image/update/{id}', 'ImageController@update');
    Route::get('/image/delete/{id}/{key}', 'ImageController@destroy');

    //Routes for website form duration
    Route::get('/form', 'FormDurationController@index');
    Route::get('/form/create', 'FormDurationController@create');
    Route::post('/form/store', 'FormDurationController@store');
    Route::get('/form/edit/{id}', 'FormDurationController@edit');
    Route::post('/form/update/{id}', 'FormDurationController@update');
    Route::get('/form/delete/{id}', 'FormDurationController@destroy');
});


Route::group(['middleware' => 'cors', 'prefix' => 'website', 'namespace' => 'Modules\Website\Http\Controllers'], function()
{
    //Information API
    Route::post('api_get_information/','WebsiteAPIController@informationAPI');
    //Committee API
    Route::post('api_get_committee/','WebsiteAPIController@committeeAPI');
    //Rules API
    Route::post('api_get_rules/','WebsiteAPIController@rulesAPI');
    //Extras API
    Route::post('api_get_extras/','WebsiteAPIController@extrasAPI');
    //Public Exam Result API
    Route::post('api_get_public_exam_result/','WebsiteAPIController@publicExamAPI');
    //Image API
    Route::post('api_get_image/','WebsiteAPIController@imageAPI');
    //Image API
    Route::post('api_get_online_form_duration/','WebsiteAPIController@formDurationAPI');
});








