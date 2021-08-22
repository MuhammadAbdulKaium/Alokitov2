<?php

Route::group(['middleware' => 'cors', 'prefix' => 'api', 'namespace' => 'Modules\API\Http\Controllers'], function()
{
    Route::get('/', 'APIController@index');

    Route::get('/get-daily-attendance-report/{institute}/{campusId}','\Modules\Academics\Http\Controllers\AttendanceUploadController@apiDailyAttendanceReport');
    Route::get('/get-state-list','AcademicAPIController@getStateList');
    Route::get('/get-city-list/{id}','AcademicAPIController@getCityList');

    Route::get('/get-academic-year/{institute}/{campusId}','AcademicAPIController@getAcademicYear');
    Route::get('/get-academic-year-list/{institute}/{campusId}','AcademicAPIController@getAcademicYearList');
    Route::post('/get-academic-level-list/','AcademicAPIController@getAcademicLevelList');
    Route::post('/get-academic-batch-list/','AcademicAPIController@getAcademicBatchList');
    Route::post('/get-academic-section-list/','AcademicAPIController@getAcademicSectionList');
    Route::post('/get-academic-semester-list/','AcademicAPIController@getAcademicSemesterList');
    Route::post('/get-academic-batch-semester-list/','AcademicAPIController@getBatchSemesterList');
    // timetable
    Route::post('/get-academic-batch-section-timetable','TimetableAPIController@getBatchSectionClassRoutine');
    // communication
    Route::post('/get-academic-notice-list','AcademicAPIController@getAcademicNotice');
    // Result
    Route::post('/get-academic-single-student-result','AcademicAPIController@getAcademicResult');



    ///////////////////////////////// student controller /////////////////////////////////
    Route::post('/get-student-list','StudentAPIController@searchStudentList');


    ///////////////////////////////// employee controller /////////////////////////////////
    Route::post('/get-employee-list','EmployeeAPIController@searchEmployeeList');

    //////////////////////////////// student count /////////////////////////////////////////
    Route::post('/institute/student', 'AcademicAPIController@getInstituteStudent');
    Route::post('/institute/studentCount', 'AcademicAPIController@getInstituteStudentCount');
    Route::post('/institute/campus', 'AcademicAPIController@getInstituteCampus');
    Route::post('/institute/smscount', 'AcademicAPIController@getInstituteSmsCount');
    Route::post('/institute/institutecampusbymonth', 'AcademicAPIController@getInstituteCampusByMonth');
    Route::post('/institute/olddues', 'AcademicAPIController@getInstituteOldDues');
    Route::post('/institute/paidamount', 'AcademicAPIController@getInstitutePaidAmount');

    //////////////////////////////// institute top list /////////////////////////////////////////
    Route::get('/get-class-topper-list/{institute}/{campusId}', 'AcademicAPIController@getClassTopperList');
    Route::get('/get-student-list', 'AcademicAPIController@getUserList');


    /////////////////////////////////////  Online Admission (School) ////////////////////////////////////////////
    // store applicant
    Route::post('/store-online-student', 'AdmissionAPIController@storeOnlineStudent');
    // applicant user login
    Route::post('/applicant-user-login', 'AdmissionAPIController@studentUserLogin');
    // applicant password Reset
    Route::post('/applicant/reset/password', 'AdmissionAPIController@applicantResetPassword');



    /////////////////////////////////////  Online Admission (College) ////////////////////////////////////////////
    // HSC admission form
        Route::post('/store-hsc-online-student', 'AdmissionAPIController@storeHscStudent');
    Route::get('/find-class-group-subjects/{batchId}/{yearId}', 'AcademicAPIController@findAcademicGroupSubject');
    // HSC application double entry checking
    Route::get('/applicant/check-roll/{rollnumber}', 'AdmissionAPIController@checkRollnumber');
    Route::get('/applicant/check-reg/{regnumber}', 'AdmissionAPIController@checkRegnumber');
    Route::get('/applicant/check-secret/{secretcode}', 'AdmissionAPIController@checkSecretCode');

    Route::get('/applicant/profile/', 'AdmissionAPIController@getStudentByInvoiceId');
    Route::post('/bkash/token/store', 'AdmissionAPIController@tokenStore');
    Route::get('/bkash/token/find', 'AdmissionAPIController@getBkashToken');

    Route::post('/bkash/transaction/store', 'AdmissionAPIController@storeBkashTransaction');
    Route::get('/bkash/transaction/find', 'AdmissionAPIController@findTransaction');
    Route::post('/bkash/transaction/update', 'AdmissionAPIController@updateBkashTransaction');

    Route::post('/applicant/payment-verify/', 'AdmissionAPIController@applicantPaymentVerify');




    // HSC applicant login
    Route::post('/hsc-applicant-login', 'AdmissionAPIController@HscStudentLogin');



    //////////////////////////////// Attendance Device API ////////////////////////////////
    Route::get('/employee/attendance/{instituteId}/{campusId}', '\Modules\Employee\Http\Controllers\EmpAttendanceManageController@empAttendanceDaily');

});
