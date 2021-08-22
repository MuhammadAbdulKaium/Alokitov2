<?php



Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'student', 'namespace' => 'Modules\Student\Http\Controllers'], function () {

    date_default_timezone_set('America/Los_Angeles');

    Route::get('/', 'StudentInfoController@index');

    Route::get('/manage', 'StudentController@manageStudent')->name('manage-student');
    Route::get('/manage/alumni', 'StudentController@manageStudentAlumni');
    Route::post('/manage/search', 'StudentController@searchStudent');
    Route::post('/manage/search/fees', 'StudentController@searchCadetFees');
    Route::post('/manage/search/fees/generate', 'StudentController@searchCadetFeesGenerate');
    Route::post('/manage/alumni/search', 'StudentController@searchStudentAlumni');
    Route::post('/manage/download/excel/pdf', 'StudentController@searchStudentDownload');
    Route::post('/manage/alumni/download/excel', 'StudentController@searchAlumniStudentDownload');
    Route::post('/update/profile/download/excel', 'StudentController@studentUpdateListExport');
    Route::get('/update/profile/import', 'StudentController@studentImportModal');
    Route::post('/update/profile/import/excel', 'StudentController@studentImportExcelFile');
    Route::post('/profile/check/emails', 'StudentController@checkEmails');
    Route::post('/profile/check/punchids', 'StudentController@checkPunchIds');

    //for fees search
    Route::post('/fees/search', 'StudentController@searchCadet');


    // student class section report download

    Route::post('/report/class/subject/', 'StudentController@studentClassSubjectReport');

    Route::get('/cadet-performance-bulk', 'StudentPerfController@bulkPerformanceStudent');

    // studdent manage status
    Route::get('/manage/status', 'StudentController@manageStudentStatus');
    Route::post('/manage/status/search', 'StudentController@searchStudentStatus');
    Route::post('/manage/deactive-student/download/excel', 'StudentController@downloadDeactiveStudent');
    //Cadet  Daily Duty
    Route::get('/daily/duty', 'StudentController@ViewDailyDuty');
    Route::post('/duty/search', 'StudentController@searchDutyStudent');
    Route::get('/cadet/activity/{id}', 'StudentActivityDirectoryCategoryController@getAjaxActivityByCategory');


    // single punch id check
    Route::post('/profile/check/single/punchid', 'StudentController@checkSinglePunchId');
    Route::post('/profile/check/single/email', 'StudentController@checkSingleEmail');


    // manage student for bulk amount of student profile configuration
    Route::any('/update/profile/', 'StudentController@manageStudentProfile');

    Route::any('/update/profile/', 'StudentController@manageStudentProfile');
    /// student status
    Route::get('/status/{id}', 'StudentInfoController@getStudentStatus');
    Route::post('/status/', 'StudentInfoController@storeStudentStatus');

    // student profile crate and store
    Route::get('/profile/create', 'StudentInfoController@createStudentInfo');
    Route::post('/profile/store', 'StudentInfoController@storeStudentInfo');

    // student profile photo edit, update and delete
    Route::get('/profile/photo/{id}', 'StudentInfoController@editStudentPhoto');
    Route::post('/profile/photo/store', 'StudentInfoController@storeStudentPhoto');
    Route::post('/profile/photo/update/{id}', 'StudentInfoController@updateStudentPhoto');
    // student profile email edit and update
    Route::get('/profile/email/{id}', 'StudentInfoController@editUserEmial');
    Route::post('/profile/email/update/{id}', 'StudentInfoController@updateUserEmial');

    ///// student profile personal /////
    Route::get('/profile/personal/{id}', 'StudentInfoController@getPersonalInfo')->middleware('std-profile-permission')->name('personalInfo');
    // personal info eidt and update
    Route::get('/profile/personal/edit/{id}', 'StudentInfoController@editPersonalInfo');
    Route::post('/profile/personal/update/{id}', 'StudentInfoController@updatePersonalInfo');

    ///// student profile address /////
    Route::get('/profile/addresses/{id}', 'StudentAddressController@getStudentAddresses')->middleware('std-profile-permission');
    // address edit delete and update
    Route::get('/profile/address/edit/{id}', 'StudentAddressController@editStudentAddress');
    Route::post('/profile/address/store/{id}', 'StudentAddressController@storeStuentAddress');
    Route::post('/profile/address/update/{id}', 'StudentAddressController@updateStudentAddress');

    /////  student profile address /////
    Route::get('/profile/guardians/{id}', 'StudentGuardController@getStudentGuardians')->middleware('std-profile-permission');
    //student performance //
    Route::get('/profile/factor/{id}/{type?}', 'StudentPerfController@getStudentPerf');
    Route::get('/profile/performance/{categoryname}/{type}/{categoryid}/{suid}', 'StudentPerfController@getStudentPerfCurriculam');
    Route::get('/profile/performance/co-curricular/{id}', 'StudentPerfController@getStudentPerfCoCurricular');
    Route::get('/profile/performance/extra-curricular/{id}', 'StudentPerfController@getStudentPerfExtraCurricular');
    Route::get('/profile/fector/entity/creates/{id}/{type}', 'StudentPerfController@createStudentPerformanceCaruculam');

    Route::get('/profile/factor/activity/new/add/{id}/{type}', 'StudentPerfController@StudentPerformanceActivityCreate');
    Route::get('/profile/factor/psychology/new/add/{id}/{type}', 'StudentPerfController@StudentPerformancePsychologyCreate');
    Route::get('/profile/factor/activity/delete/{id}', 'StudentPerfController@StudentPerformanceActivityDelete');
    Route::get('/profile/activity/{id}', 'StudentPerfController@AjaxActivityPoint');

    // Route::post('/profile/performance/activity/create', 'StudentPerfController@StudentPerformanceActivityStore');

    // guardian user creation
    Route::get('/profile/guardian/user/{id}', 'StudentGuardController@createParentUserAccount');
    // student profile guardian edit, update and delete with emergency
    Route::get('/profile/guardian/create/{id}', 'StudentGuardController@createStudentGuardian');
    Route::post('/profile/guardian/store', 'StudentGuardController@storeStudentGuardian');
    Route::get('/profile/guardian/edit/{id}', 'StudentGuardController@editStudentGuardian');
    Route::get('/profile/make/guardian/{stdId}/{gudId}', 'StudentGuardController@makeFamilyGuardian');
    Route::post('/profile/guardian/update/{id}', 'StudentGuardController@updateStudentGuardian');
    Route::get('/profile/guardian/delete/{id}', 'StudentGuardController@destroyStudnetGuardian');
    // emergency setting
    Route::get('/profile/guardian/emergency/{stdId}/{gudId}', 'StudentParentController@setEmergencyParent');
    // student guardian parent
    Route::get('/profile/guardian/parent/create/{id}', 'StudentParentController@createStudentParent');
    Route::post('/profile/guardian/parent/store', 'StudentParentController@storeStudentParent');

    //    psychology
    Route::get('/profile/psychology/{id}', 'StudentPsycoController@getStudentPsyco')->middleware('std-profile-permission');
    //    discipline
    Route::get('/profile/discipline/{id}', 'StudentDiscController@getStudentPsyco')->middleware('std-profile-permission');
    //    Hobby
    Route::get('/profile/hobby/{id}', 'StudentHobbyController@getStudentPsyco')->middleware('std-profile-permission');
    //    Aim
    Route::get('/profile/aim/{id}', 'StudentAimController@getStudentPsyco')->middleware('std-profile-permission');
    //    Dream
    Route::get('/profile/dream/{id}', 'StudentDreamController@getStudentPsyco')->middleware('std-profile-permission');
    //    Idol
    Route::get('/profile/idol/{id}', 'StudentIdolController@getStudentPsyco')->middleware('std-profile-permission');
    //    achievement
    Route::get('/profile/achievement/{id}', 'StudentAchivementController@getStudentPsyco')->middleware('std-profile-permission');
    Route::get('/profile/photos/{id}', 'StudentPhotoController@getStudentPsyco')->middleware('std-profile-permission');

    /////  student profile address /////
    Route::get('/profile/documents/{id}', 'StudentAttatchController@getStudentDocuments')->middleware('std-profile-permission'); //home
    // student profile documetns add, edti, update and delete
    Route::get('/documents/create/{id}', 'StudentAttatchController@createStudentDocument');
    Route::post('/documents/store', 'StudentAttatchController@storeStudentDocument');
    Route::get('/documents/edit/{id}', 'StudentAttatchController@editStudentDocument');
    Route::post('/documents/update/{id}', 'StudentAttatchController@updateStudentDocument');
    Route::get('/documents/delete/{id}', 'StudentAttatchController@deleteStudentDocument');
    Route::get('/documents/status/{id}/{status}', 'StudentAttatchController@changStudentDocumentStatus');

    /////  student profile address /////
    Route::get('/profile/academic/{id}', 'StudentEnrollController@getStudentAcademics')->middleware('std-profile-permission'); //home
    Route::get('/profile/academic2/{id}', 'StudentEnrollController@getStudentAcademics2')->middleware('std-profile-permission'); //home
    Route::get('/profile/psychology/view/{id}/{item}', 'StudentEnrollController@getStudentPsychologyView')->middleware('std-profile-permission'); //home
    //    Route::get('/profile/health/view/{id}/{item}', 'StudentEnrollController@getStudentHealthView')->middleware('std-profile-permission'); //home
    Route::get('/profile/academic2/{id}/{item}', 'StudentEnrollController@getStudentAcademicsSubject')->middleware('std-profile-permission'); //home
    Route::get('/profile/academic/entry/{id}', 'StudentEnrollController@getStudentAcademicsEntry')->middleware('std-profile-permission'); //home
    Route::get('/profile/academic/value', 'StudentEnrollController@getStudentAcademicPoint')->middleware('std-profile-permission'); //home
    Route::get('/course-enroll', 'StudentEnrollController@courseEnroll');
    Route::get('/course-edit/{id}', 'StudentEnrollController@editEnroll');
    Route::post('/course-update', 'StudentEnrollController@updateEnroll');
    Route::get('/course-info', 'StudentEnrollController@courseInfo');
    Route::get('/course-info-edit', 'StudentEnrollController@courseInfoEdit');
    Route::get('/batch-info', 'StudentEnrollController@batchInfo');

    /////  student profile address /////
    Route::get('/profile/fees/{id}', 'StudentInfoController@getStudentFees')->middleware('std-profile-permission'); //home
    Route::get('/demoreport', 'StudentReportController@indexReport'); //home


    Route::get('/profile/fees_info/{id}', 'StudentInfoController@getStudentFeesInfo')->middleware('std-profile-permission');
    Route::get('/profile/fees_info/download/{id}', 'StudentInfoController@getStudentFeesInfoReportById')->middleware('std-profile-permission');
    Route::get('/profile/fees-new/{id}', 'StudentInfoController@getStudentNewFeesInfo')->middleware('std-profile-permission');
    //    Student Remsarks
    Route::get('/profile/remarks/{id}', 'StudentRemarksController@getStudentRemarks')->middleware('std-profile-permission'); //home


    ///////////////////// Student Profile Roport Section /////////////////////
    Route::get('report/profile/{id}', 'StudentProfileReportController@index')->middleware('std-profile-permission');
    Route::get('report/profile/search/{id}', 'reports\StudentAttendanceReportController@searchAttendanceReport');
    Route::post('report/attendance', 'reports\StudentAttendanceReportController@attendanceReport');

    ///////////////////// Student Import Section /////////////////////
    Route::get('import', 'StudentImportController@index');
    Route::get('import/images', 'StudentImportController@imagePage');
    Route::post('import/upload', 'StudentImportController@upload');
    Route::post('image/import/upload', 'StudentImportController@imageUpload');
    Route::post('import/store', 'StudentImportController@store');
    Route::post('import/redirect', 'StudentImportController@showDataset');
    Route::get('download/student_import', 'StudentImportController@getDownload');
    Route::post('check/emails', 'StudentImportController@checkEmails');
    /////////////////////// Admin Student Import Section /////////////////////////
    Route::get('import/service', 'StudentImportController@adminStudentListCreate');
    Route::post('import/service/store', 'StudentImportController@adminStudentListStore');
    Route::post('import/service/upload', 'StudentImportController@adminStudentUpload');
    Route::get('service/{id}/delete', 'StudentImportController@adminStudentDelete');


    ///////////////////// Student promotion Section /////////////////////
    Route::get('promote', 'StudentPromotionController@index');
    Route::post('promote/confirm', 'StudentPromotionController@confirmStudent');
    Route::post('promote', 'StudentPromotionController@promoteStudent');
    Route::post('promote/search', 'StudentPromotionController@searchStudent');

    ///////////////////// Student Dropout //////////////////////////////////////
    Route::get('/enrol-detail/apply-dropout/{id}', 'StudentPromotionController@dropoutStudent');
    Route::post('/enrol-detail/apply-dropout', 'StudentPromotionController@confirmStudentDropout');

    ///////////////////// Student profile parents activities /////////////////////
    // attendance
    Route::get('/parent/attendance/info/{parentId}', 'StudentParentController@showChildAttendanceByParent');
    Route::post('/parent/attendance/show/attendance', 'reports\StudentAttendanceReportController@attendanceReport');
    // report card for parent
    Route::get('/parent/report-card/{parentId}', 'StudentParentController@showChildReportCardByParent');
    Route::post('/parent/report-card/show', 'reports\StudentAttendanceReportController@attendanceReport');
    // report card for student
    Route::get('/report-card/{id}', 'StudentController@getReportCard')->middleware('std-profile-permission');
    // attendance for student
    Route::get('/attendance/info/{id}', 'StudentController@getAttendanceInfo')->middleware('std-profile-permission');

    Route::get('/parent/fees/info/{parentId}', 'StudentParentController@showChildFeesInfoByParent');
    Route::post('/parent/fees/show/invoice', 'StudentParentController@showChildAllFeesInvoice');


    // ajax request
    Route::get('find/student', 'StudentInfoController@findStudent');
    Route::get('find/student/guardian', 'StudentGuardController@findGuardian');

    Route::get('find/user', 'StudentInfoController@findUser');

    // sms for select all student
    Route::get('find/all', 'StudentInfoController@getAllStudent');
    Route::get('find/student/{batch}/{section}', 'StudentInfoController@getStudentByBatchSection');

    //get all parents
    Route::get('find/parents/all', 'StudentGuardController@getallStudentGuardian');
    Route::get('find/parents/{batch}/{section}', 'StudentGuardController@getParentByBatchSection');

    // manage parent
    Route::any('/parent/manage', 'StudentParentController@manageParent');

    // student waiver section

    Route::get('/student-waiver/add-waiver/{id}', 'StudentWaiverController@addWaiverModel');
    Route::post('/student-waiver/add-waiver/store', 'StudentWaiverController@store');
    //        student waiverlist
    Route::get('/student-waiver/show-waiver/list', 'StudentWaiverController@studentWaiverList');
    Route::get('/student-waiver/delete/{id}', 'StudentWaiverController@deleteWaiver');
    Route::get('/student-waiver/update-waiver/{id}', 'StudentWaiverController@editWaiverModal');

    // student id card
    Route::get('/id-card/{stdId}', 'StudentController@showIdCard');
    Route::post('/id-card', 'StudentController@downloadIdCard');
    Route::get('/invoice/show/{id}/{backUrl}', 'StudentController@getInvoiceById')->middleware('std-profile-permission');

    // student manage result

    Route::get('/testimonial/result/manage', 'StudentTestimonialResultController@manageStudentResult');
    Route::post('/testimonial/result/manage/search', 'StudentTestimonialResultController@searchStudentResult');
    Route::post('/testimonial/result/manage/store', 'StudentTestimonialResultController@StudentResultStore');

    // waiver manage
    Route::get('/manage/waiver', 'StudentWaiverController@manageStudentWaiver');
    Route::post('/manage/waiver', 'StudentWaiverController@searchStudentWaiver');
    Route::post('/manage/waiver/store', 'StudentWaiverController@manageStudentWaiverStore');

    //student bulk image upload
    Route::get('/upload/images', 'StudentImageUploadController@index');
    Route::post('/upload/images/search', 'StudentImageUploadController@create');
    Route::post('/upload/images/store', 'StudentImageUploadController@store');


    ///////////////////// Class Topper List /////////////////////
    Route::post('/manage/class-top', 'ClassTopperController@store');
    Route::get('/manage/class-top/{id}', 'ClassTopperController@create');

    ///////////////////// get student attendance for device  Topper List /////////////////////
    Route::get('/get/device/attendance', 'StudentController@getStudentDeviceAttedance');
    Route::post('/student-attendance/custom/store', 'StudentController@storeStudentCustomAttendance');

    Route::get('/test', 'StudentController@testPdf');

    // for assessment
    Route::get('/performance/assessment/{type}/{suid}', 'CadetPerformanceAssessmentController@index');
    Route::post('/performance/assessment', 'CadetPerformanceAssessmentController@store');
    Route::get('/performance/assessment/show/{id}/{item}', 'CadetPerformanceAssessmentController@show');
    Route::post('/performance/assessment/update/{id}', 'CadetPerformanceAssessmentController@update');
    Route::get('/performance/assessment/delete/{id}', 'CadetPerformanceAssessmentController@destroy');
    Route::get('/performance/assessment/delete/data/{id}', 'CadetPerformanceAssessmentController@delete');

    //for graph
    Route::get('/profile/barchart/{type}/{categoryid}/{suid}', 'CadetGraphController@AjaxBarChart');
    Route::post('/profile/landing/graph', 'CadetGraphController@PostLandingGraph');
    Route::post('/profile/activity/wise', 'CadetGraphController@PostActivityGraph');
    Route::post('/profile/acadimic/graph', 'CadetGraphController@PostAcadimicGraph');
    Route::post('/profile/diameter/graph', 'CadetGraphController@PostDiameterGraph');


    // studdent Activity Directory Category
    // Route::get('/cadet-activity-directory', 'StudentActivityDirectoryCategoryController@index');
    Route::get('/student/cadet-activity-directory/category/create/{id}', 'StudentActivityDirectoryCategoryController@create');
    Route::any("/store-category", 'StudentActivityDirectoryCategoryController@store');
    Route::get('/category/{id}/edit', 'StudentActivityDirectoryCategoryController@edit');
    Route::post('/edit-activity-category/{id}', 'StudentActivityDirectoryCategoryController@update');
    Route::any("/delete-category/{id}", 'StudentActivityDirectoryCategoryController@destroy');

    // studdent Activity Directory Activity

    Route::get('/cadet-activity-list', 'StudentActivityDirectoryActivityController@getAjaxActivityList');
    Route::get('/cadet-activity-directory', 'StudentActivityDirectoryActivityController@index');
    Route::post("/store-activity", 'StudentActivityDirectoryActivityController@store');
    Route::get('/edit-activity/{id}', 'StudentActivityDirectoryActivityController@edit');
    Route::post('/update-activity/{id}', 'StudentActivityDirectoryActivityController@update');
    Route::get("/delete-activity/{id}", 'StudentActivityDirectoryActivityController@destroy');


    // Club Management
    Route::get('/club/setup/enrollment/{id?}', 'ClubSetupController@index');
    Route::post('/save/activity/schedule/{id}', 'ClubSetupController@saveActivitySchedule');
    Route::get('/search/activity/schedule', 'ClubSetupController@searchActivitySchedule');
    Route::post('/add/club/activity', 'ClubSetupController@addClubActivity');
    Route::post('/edit/club/employee', 'ClubSetupController@editClubEmployee');
    Route::post('/update/club/students', 'ClubSetupController@updateClubStudents');
    Route::get('/club/activity/history/{id}', 'ClubSetupController@clubActivityHistory');


    // Student History Profile
    Route::get('/profile/history/{id}', 'StudentHistoryController@index');
    Route::get('/profile/house/history/{id}', 'StudentHistoryController@houseHistory');
    Route::get('/profile/medical/history/{id}', 'StudentHistoryController@medicalHistory');


    // Task Schedule
    Route::get('/task/schedule/{id?}', 'TaskScheduleController@index');
    Route::post('/create/task/schedule/date', 'TaskScheduleController@store');
    Route::get('/edit/task/schedule/date/{id}', 'TaskScheduleController@edit');
    Route::post('/update/task/schedule/date/{id}', 'TaskScheduleController@update');
    Route::get('/delete/task/schedule/date/{id}', 'TaskScheduleController@destroy');
    Route::post('/create/task/schedule', 'TaskScheduleController@createTaskSchedule');
    Route::get('/delete/task/schedule/{id}', 'TaskScheduleController@deleteTaskSchedule');
    Route::get('/view/task/schedule', 'TaskScheduleController@viewTaskSchedule');

    // Task Schedule Ajax Routes
    Route::get('/get/activites/from/activity-cateogry', 'TaskScheduleController@getActivitiesFromActivityCategory');
    Route::get('/search/task/schedule/table', 'TaskScheduleController@searchTaskScheduleTable');
});
