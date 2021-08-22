<?php

Route::group(['middleware' => ['web', 'auth','access-permission'], 'prefix' => 'reports', 'namespace' => 'Modules\Reports\Http\Controllers'], function () {
    Route::get('/', 'ReportsController@index');
    Route::get('/{tabId}', 'ReportsController@allReports');

    Route::get('/all/attendance', 'ReportsController@getAttendaceReport');
    Route::get('/attendance/class/section/average', 'ReportsController@classSectionAverageReport');
    Route::get('attendance/student/average/', 'ReportsController@studentAttendance');

    // class section monthly attendance report
    Route::get('attendance/class-section/monthly','ReportsController@getClassSectionMonthlyAttendanceReport');

    // student absent report
    Route::get('/attendance/student/absent/days/', 'AcademicsReportController@studentAbsentDays');
    Route::post('/attendance/student/absent/days/summary', 'AcademicsReportController@studentAbsentDaysSummary');
    Route::post('/attendance/student/absent/days/details', 'AcademicsReportController@studentAbsentDaysDetails');

    //  student report
    Route::get('/academics/student/{type}/{downloadToken}', 'AcademicsReportController@allStudentReport');
    Route::get('/academics/class/section/average/{type}', 'AcademicsReportController@classSectionStudentReportGet');
    Route::post('/academics/class/section/average/{type}', 'AcademicsReportController@classSectionStudentReportPost');

    // student class section report here
    Route::get('/academics/class/section/student', 'AcademicsReportController@studentReportClassSectionModal');

    // class subject report here
    Route::get('/academics/student/class/subject/list', 'AcademicsReportController@studentReportClassSubjectList');
    // class section subject student list
    Route::get('/academics/class/subject/student/list', 'AcademicsReportController@classSubjectStudentList');

    //  Academic report
    Route::get('/academics/batch-section-repeater-and-transfer', 'AcademicsReportController@getBatchSectionRepeaterTransfer');
    Route::get('/academics/batch-section-dropout-and-promotion', 'AcademicsReportController@getBatchSectionDropoutPromotion');
    Route::post('/academics/batch-section-repeater-dropout-promotion-and-transfer', 'AcademicsReportController@manageBatchSectionRepeaterDropOutPromotionTransfer');

    // student fees report
    Route::get('/fees/student/invoice/', 'ReportsController@invoiceReportCard');
    Route::get('/invoice/search/', 'ReportsController@invoiceFiltering');
    Route::post('/invoice/export/', 'ReportsController@importInvoicePdforExcel');

    // student admission reports
    Route::get('/admission/filter-applicants', 'ReportsController@filterApplicants');

    // Student  waiver report

    Route::get('/fees/waiver/', 'WaiverReportController@feesWaiverReportModal');
    Route::get('/waiver/modal', 'WaiverReportController@waiverReportModal');
    Route::post('/waiver/batch/section', 'WaiverReportController@batchSectionWaiverReport');
    Route::post('/waiver/single/student/', 'WaiverReportController@singleStudentWaiverReport');

    // monthly fees report controller
    Route::get('/fees/monthly/report', 'ReportsController@feesMonthlyReportView');

    // fees fine report controller
    Route::get('/fees/fine/report', 'ReportsController@feesFineModalView');

    Route::get('/event/report', 'AcademicsReportController@getEventReport');
    Route::post('/event/report/download', 'AcademicsReportController@downloadEventReport');


    // student id card section
    Route::post('/student/id-card', 'StudentIdCardController@getStdIdCardList');
    Route::post('/student/id-card/download', 'StudentIdCardController@downloadStdIdCardList');
    // student id card setting
    Route::post('/student/id-card/setting','StudentIdCardController@storeTemplateSetting');


    Route::post('/student/testimonial-search', 'ReportsController@searchStudentClassSectionTestimonial');

    // waiver search by class section wise
    Route::get('/student/waiver-search', 'ReportsController@searchStudentWaiverClassSection');
    Route::post('/student-waiver/export', 'ReportsController@studentWaiverReportExport');


    // fees Details  controller
    Route::get('/fees/details/report', 'ReportsController@feesDetailsReports');

    // testimonial report here
    Route::post('/documents/customised-testimonial', 'ReportsController@customisedTestimonials');
    Route::post('/documents/testimonial', 'ReportsController@getTestimonialReport');
    Route::post('/documents/transfer-certificate', 'ReportsController@getTransferCertificateReport');

    // admit card
    Route::post('/admit-card/', 'ReportsController@admitCard');
    Route::post('/admit-card/download', 'ReportsController@downloadAdmitCard');
    Route::post('/admit-card/download/college', 'ReportsController@downloadAdmitCardForCollege');
    // sit plan view
    Route::post('/sit-plan/view/', 'ReportsController@sitPlanView');

    // exam attendance sheet
    Route::post('/examatsheet/view/', 'ReportsController@examAttSheet');




    // testimonial result downlaod
    Route::post('/student/result/testimonal/download/', 'ReportsController@getTestimonialResult');

    // enrollment history download
    Route::post('/student/enrollment/history','AcademicsReportController@getEnrollmentHistory');


    // exam attendance sheet
    Route::post('/college/student/result/', 'ReportsController@collegeResultReport');
    Route::post('/college/student/subject-wise/summary', 'ReportsController@collegeSubjectWiseSummery');
    Route::post('/college/student/result/summary', 'ReportsController@collegeResultSummery');
    Route::get('/college/student/result/summary-grade', 'ReportsController@collegeSummeryofGrade');
    Route::post('/college/student/tutorial-exam-report', 'ReportsController@tutorialExamReport');
    Route::get('/college/student/test-exam-report', 'ReportsController@testExamReport');
    Route::post('/college/student/hsc-exam-report', 'ReportsController@hscExamReport');

});
