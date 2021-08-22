<?php

Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'admission', 'namespace' => 'Modules\Admission\Http\Controllers'], function () {
    Route::get('/', 'AdmissionController@index');
    Route::resource('application', 'ApplicationController');
    Route::get('/applicant/{id}/{dataType}', 'ApplicationController@showApplicant');

    ///////////// email /////////////////
    Route::get('/applicant/{id}/email/edit', 'ApplicationController@editEmail');
    Route::post('/applicant/email/update', 'ApplicationController@updateEmail');
    ///////////// photo /////////////////
    Route::get('/applicant/{id}/photo/edit', 'ApplicantDocumentController@editPhoto');
    Route::post('/applicant/photo/update', 'ApplicantDocumentController@updatePhoto');

    ///////////// document /////////////////
    Route::get('/applicant/{id}/document/add', 'ApplicantDocumentController@addDocument');
    Route::post('/applicant/document/store', 'ApplicantDocumentController@storeDocument');
    Route::get('/applicant/document/{id}/edit', 'ApplicantDocumentController@editDocument');
    Route::get('/applicant/document/{id}/delete', 'ApplicantDocumentController@deleteDocument');

    //////////// address //////////////////
    Route::get('/applicant/address/{id}/edit', 'ApplicantAddressController@edit');
    Route::post('/applicant/address/{id}/update', 'ApplicantAddressController@update');

    //////////// personal /////////////////
    Route::get('/applicant/personal/{id}/edit', 'ApplicantInfoController@editPersonal');
    Route::post('/applicant/personal/{id}/update', 'ApplicantInfoController@updatePersonal');

    //////////////  manage enquiry ///////////////////////
    Route::get('/enquiry', 'AdmissionController@manageEnquiry');
    Route::get('/find/applicant', 'AdmissionController@findApplicant');

    //////////////  manage enquiry ///////////////////////
    Route::get('/applicant/download', 'AdmissionController@applicantdownlaodView');
    Route::post('/applicant/download', 'AdmissionController@applicantDownlaod');
    //////////////  manage enquiry ///////////////////////
    Route::get('/applicant-sitplan/download', 'AdmissionController@applicantSitPlandownlaodView');
    Route::post('/applicant-sitplan/download', 'AdmissionController@applicantSitPlanDownlaod');






    //////////////  manage fees ///////////////////////
    Route::get('/fees/', 'ApplicantFeesController@index');
    Route::get('/fees/show/{applicantId}', 'ApplicantFeesController@showFees');
    Route::post('/fees/store', 'ApplicantFeesController@storeFees');
    Route::get('/fees/invoice/{applicantId}', 'ApplicantFeesController@downloadInvoice');
    Route::get('/find/fees', 'ApplicantFeesController@findFees');

    /////////////  manage assessment /////////////////
    Route::get('/assessment', 'ApplicantAssessmentController@index');
    Route::get('/assessment/{pageId}', 'ApplicantAssessmentController@getPage');
    Route::any('/assessment/setting/exam', 'ApplicantAssessmentController@examSetting');

    // grade-book
    Route::post('/assessment/grade-book/update', 'ApplicantAssessmentController@updateGradeBook');
    Route::post('/assessment/grade-book/export', 'ApplicantAssessmentController@exportGradeBook');
    Route::get('/assessment/grade-book/import', 'ApplicantAssessmentController@importGradeBook');
    Route::post('/assessment/grade-book/upload', 'ApplicantAssessmentController@uploadGradeBook');
    Route::post('/assessment/grade-book/store', 'ApplicantAssessmentController@storeGradeBook');
    // result
    Route::get('/assessment/result/manage','ApplicantAssessmentController@manageResult');
    Route::get('/assessment/result/list','ApplicantAssessmentController@getResultSheet');
    Route::POST('/assessment/result/download','ApplicantAssessmentController@downloadResultSheet');
    Route::POST('/assessment/result/promote','ApplicantAssessmentController@promoteApplicant');
    // search
    Route::get('/find/grade-book', 'ApplicantAssessmentController@findGradeBook');
    // admission
    Route::post('/assessment/admit/confirm', 'ApplicantAssessmentController@confirmStdAdmission');
    Route::post('/assessment/result', 'ApplicantAssessmentController@admitStudent');
    Route::any('/assessment/setting/reports', 'AdmissionController@admissionReports');


    /////////////////////////////////  admission letter /////////////////////////
    //online admission Letter
    Route::get('/admission-letter/', function () {
        return view('admission::admission-letter.index');
    });

    Route::get('/admission-letter/{applicantId}/download', 'ApplicationController@downloadAdmissionLetter');

    //online admission letter create
    Route::get('/admission-letter/create', function () {
        return view('admission::admission-letter.create');
    });

    //onlice admission Letter view by id
    Route::get('/admission-letter/view', function () {
        return view('admission::admission-letter.view');
    });

    // admission form download here

    Route::get('/admission-form','AdmissionController@admissionFormDownload');



    //////////////////////////// HSC online application ////////////////////////////

    Route::get('/hsc/enquiry','HscAppController@manageEnquiry');
    Route::post('/hsc/find','HscAppController@findApplicantList');
    Route::get('/hsc/applicant/{id}','HscAppController@findSingleApplicant');
//    Route::get('/hsc/applicant/download/{id}','HscAppController@downloadSingleApplicant');
    // applicant to regular student moving
    Route::get('/hsc/promotion','HscAppController@manageApplicantPromotion');
    Route::post('/hsc/promotion/store','HscAppController@storeApplicantPromotion');
    // report
    Route::post('/hsc/applicant/admission-report','HscAppController@downloadHscAdmissionReport');


    //////////////////////////// HSC online application ////////////////////////////


});



Route::group(['middleware' => ['web'], 'prefix' => 'admission', 'namespace' => 'Modules\Admission\Http\Controllers'], function () {
    Route::get('/download/admit-card/{id}', 'ApplicationController@downloadAdmitCard');
    Route::get('/download/application/{id}', 'ApplicationController@downloadApplication');

    // hsc applicant admission form
    Route::get('/hsc/applicant/download/{id}','HscAppController@downloadSingleApplicant');
});


