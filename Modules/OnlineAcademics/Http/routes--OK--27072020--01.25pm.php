<?php

Route::group(['middleware' => 'web', 'prefix' => 'onlineacademics', 'namespace' => 'Modules\OnlineAcademics\Http\Controllers'], function()
{
    Route::get('/', 'OnlineAcademicsController@index');
    Route::get('/onlineacademic/{classtopic}', 'OnlineAcademicsController@index');
    Route::post('/onlineacademic/getTopicInfo/', 'OnlineAcademicsController@getTopicInfo');
    Route::any('onlineacademic/edit/{id}', 'OnlineAcademicsController@edit');
    Route::post('/onlineacademic/updateTopicInfo/{id}', 'OnlineAcademicsController@update');
    Route::any('onlineacademic/destroy/{id}', 'OnlineAcademicsController@destroy');
    Route::get('onlineacademic/find/topic', 'OnlineAcademicsController@findtopic');
    Route::post('onlineacademic/ClassHistory', 'OnlineAcademicsController@ClassHistory');
    Route::post('onlineacademic/onlineclass', 'OnlineAcademicsController@StoreClassTopic');
    Route::get('onlineacademic/find/ajax_topic', 'OnlineAcademicsController@ajax_topic');

    Route::get('/find/teacher', 'OnlineAcademicsController@findTeacher');
    Route::get('/find/ajax_teacher_topic', 'OnlineAcademicsController@ajax_teacher_topic');

    Route::post('onlineacademic/ClassSchedule', 'OnlineAcademicsController@ClassSchedule');
    Route::post('onlineacademic/OnlineScheduleClass', 'OnlineAcademicsController@OnlineScheduleClass');
    Route::get('onlineacademic/LiveClassScheduled/{ScheduleId}', 'OnlineAcademicsController@LiveClassScheduled');
    Route::get('onlineclass/onlineclass_conducts', 'OnlineAcademicsController@onlineclass_condduct');

    
});
