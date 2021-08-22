<?php

Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'communication', 'namespace' => 'Modules\Communication\Http\Controllers'], function()
{
    Route::get('/sms/group/', 'SmsController@index');
    Route::get('/sms/group/{selectGorup}', 'SmsController@selectGroup');
    Route::get('/sms/sms_log', 'SmsController@smsLogs');


    // sms credit
    Route::get('/sms/sms_credit', 'SmsCreditController@index');
    Route::get('/sms/template/', 'SmsTemplateController@createSmsTemplate');
    Route::get('/sms/template/list', 'SmsTemplateController@smsTemplateList');
    Route::get('/sms/template/edit/{id}', 'SmsTemplateController@smsTemplateEdit');
    Route::get('/sms/template/delete/{id}', 'SmsTemplateController@smsTemplateDelete');
    Route::post('/sms/template/store/', 'SmsTemplateController@storeSmsTemplate');

    Route::post('/sms/sms_credit/store', 'SmsCreditController@store');
    Route::get('/sms/sms_credit/update/{sms_credit_id}', 'SmsCreditController@show');
    Route::post('/sms/sms_credit/update/', 'SmsCreditController@update');

    //pending sms
    Route::get('/sms/pending_sms', 'SmsCreditController@pendingSms');
    Route::get('/sms/pending_sms/cancel/{id}', 'SmsCreditController@pendingSmsCancel');
    Route::get('/sms/pending_sms/approve/{id}', 'SmsCreditController@pendingSmsApprove');

    //sms Log
    Route::get('/sms/sms_log', 'SmsLogController@index');
    Route::get('/sms/sms_log/search', 'SmsLogController@smsLogSearch');


    // sms group teacher
    Route::post('/sms/group/teacher/send_sms', 'SmsController@smsSendTeacher');

    // sms group student
    Route::post('/sms/group/student/send_sms', 'SmsController@smsSendStudent');

    // sms group stuff
    Route::post('/sms/group/stuff/send_sms', 'SmsController@smsSendStuff');
    // sms group parent
    Route::post('/sms/group/parents/send_sms', 'SmsController@smsSendParent');

    // sms group teacher
    Route::post('/sms/group/custom-sms/send_sms', 'SmsController@smsSendCustom');


    // custom sms

    Route::get('/add/group/number','MobileNumberController@addGroupModal');
    Route::post('/add/group/number/store','MobileNumberController@storeMobileNumberGroup');
    Route::post('/group/number/update','MobileNumberController@updateMobileNumberGroup');

    // group view

    Route::get('/group/view/{id}','MobileNumberController@groupView');
    Route::get('/group/copy/{id}','MobileNumberController@groupCopyAllNumber');
    Route::get('/group/edit/{id}','MobileNumberController@groupNumberEditModal');
    Route::get('/group/delete/{id}','MobileNumberController@groupDelete');



    // notice
    Route::get('/notice/', 'NoticeController@index');
    Route::post('/notice/create', 'NoticeController@create');
    Route::get('/notice/delete/{id}', 'NoticeController@noticeDelete');
    Route::get('/notice/view/{id}', 'NoticeController@noticeView');
    Route::get('/notice/edit/{id}', 'NoticeController@noticeEdit');
    Route::post('/notice/update', 'NoticeController@noticeUpdate');
    Route::get('/notice/notice_cancel/{id}', 'NoticeController@noticeCancel');

    //get notice by user_type
    Route::get('/notice/all/{user_type}', 'NoticeController@getNoticeByUserType');
    Route::get('/sendsms', 'NoticeController@sendSMS');

    // event
    Route::resource('/event', 'EventController');
    Route::post('/event/status', 'EventController@status');
    Route::post('/event/delete', 'EventController@destroy');
    Route::POST('/dashboard/event/list', 'EventController@getDashboardEvent');
    Route::get('/dashboard/month-schedule/{year}/{monthId}', 'EventController@getMonthSchedule');

    // student telephone contact
    Route::get('/telephone-diary/student-contact', 'TelephoneDiaryController@studentContact');
    Route::post('/telephone-diary/student-contact/search', 'TelephoneDiaryController@studentContactSearch');
    Route::post('/telephone-diary/student-contact/downlaod', 'TelephoneDiaryController@studentContactSearchDownload');



    // student telephone contact
    Route::get('/telephone-diary/employee-contact', 'TelephoneDiaryController@employeeContact');
    Route::post('/telephone-diary/employee-contact/search', 'TelephoneDiaryController@employeeContactSearch');
    Route::post('/telephone-diary/employee-contact/downlaod', 'TelephoneDiaryController@employeeContactSearchDownload');




//    Route::get('/event', 'EventController@index');
//    Route::get('/event/create', 'EventController@create');
//    Route::post('/event/store', 'EventController@store');
//    Route::post('/event/store', 'EventController@store');
//    Route::post('/event/store', 'EventController@store');

    Route::get('/sms/sms_credit/invoice/{id}','SmsCreditController@downloadSmsCreditInvoice');
    Route::get('/sms/sms_credit/payment-status/{id}','SmsCreditController@updateSmsCreditPaymentStatus');



});
