<?php

Route::group(['middleware' => ['web', 'auth', 'access-permission'], 'prefix' => 'academics', 'namespace' => 'Modules\Academics\Http\Controllers'], function () {
    Route::get('/', 'AcademicsController@index');


    // student current / previous year semester result sheet
    Route::get('/get-previous-result', 'AssessmentsController@getMyReportCard');

    //
    Route::get('event', 'AcademicsController@event');
    Route::get('academic-calendar', 'AcademicsController@academicCalendar');
    Route::get('assignment', 'AcademicsController@assignment');
    Route::get('ad_assignment', 'AcademicsController@addAssignment');
    // acadamic/online test
    Route::get('online_test/question_category', 'AcademicsController@questionCategory');
    Route::get('online_test/question_master', 'AcademicsController@questionMaster');
    Route::get('online_test/grading_system', 'AcademicsController@gradingSystem');
    Route::get('online_test/create_grading_system', 'AcademicsController@createGadingSystem');
    Route::get('online_test/test_create', 'AcademicsController@createTest');
    Route::get('online_test/online_test_master', 'AcademicsController@testMaster');
    //test commit

    /*Subject*/
    Route::get('subject', ['as'   => 'subject', 'uses' => 'SubjectController@index']);
    Route::any("store-subject", ["as"   => "store-subject", "uses" => "SubjectController@store"]);
    Route::get('view-subject/{id}', ["as"   => "view-subject", "uses" => "SubjectController@show"]);
    Route::get('edit-subject/{id}', ["as"   => "edit-subject", "uses" => "SubjectController@edit"]);

    //    Route::post('edit-subject-perform/{id}', [
    //        "as"   => "edit-subject-perform",
    //        "uses" => "SubjectController@update"
    //    ]);

    Route::any("delete-subject/{id}", ["as"   => "delete-subject", "uses" => "SubjectController@delete"]);
    Route::post('edit-subject-perform/{id}', 'SubjectController@update');
    /*Subject End*/

    /*Start Academic year*/

    Route::any('academic-year', 'AcademicYearController@index');

    Route::any('store-academic-year', 'AcademicYearController@store');

    Route::any('view-academic-year/{id}', 'AcademicYearController@show');

    Route::any('edit-academic-year/{id}', 'AcademicYearController@edit');

    Route::any('edit-academic-year-perform/{id}', 'AcademicYearController@update');

    Route::any('delete-academic-year/{id}', 'AcademicYearController@delete');

    /*End Academic year*/

    /*Start Admission Year*/
    Route::any('admission-year', 'AcademicsAdmissionYearController@index');

    Route::any('store-admission-year', 'AcademicsAdmissionYearController@store');

    Route::any('view-admission-year/{id}', 'AcademicsAdmissionYearController@show');

    Route::any('edit-admission-year/{id}', 'AcademicsAdmissionYearController@edit');

    Route::any('edit-admission-year-perform/{id}', 'AcademicsAdmissionYearController@update');

    Route::any('delete-admission-year/{id}', 'AcademicsAdmissionYearController@delete');
    /*End Admission Year*/

    /*Course Start*/

    Route::any('academic-year', 'AcademicsYearController@index');
    Route::any('store-academic-year', 'AcademicsYearController@store');

    Route::any('view-academic-year/{id}', 'AcademicsYearController@show');

    Route::any('edit-academic-year/{id}', 'AcademicsYearController@edit');

    Route::any('edit-academic-year-perform/{id}', 'AcademicsYearController@update');

    Route::any('delete-academic-year/{id}', 'AcademicsYearController@delete');
    /*Course End*/

    /*Academics Level Start*/

    Route::any('academic-level', 'AcademicsLevelController@index');

    Route::any('store-academic-level', 'AcademicsLevelController@store');

    Route::any('view-academic-level/{id}', 'AcademicsLevelController@show');

    Route::any('edit-academic-level/{id}', 'AcademicsLevelController@edit');

    Route::any('edit-academic-level-perform/{id}', 'AcademicsLevelController@update');

    Route::any('delete-academic-level/{id}', 'AcademicsLevelController@delete');
    /*Acdemics Level End*/

    /*Batch Start*/

    Route::any('batch', 'BatchController@index');

    Route::any('add-batch', 'BatchController@add_batch_view');

    Route::any('view-batch/{id}', 'BatchController@show');

    Route::any('store-batch', 'BatchController@store');

    Route::any('edit-batch-view/{id}', 'BatchController@show_edit');

    Route::any('batch-data-edit/{id}', 'BatchController@update');

    Route::any('delete-batch/{id}', 'BatchController@delete');
    Route::any('batch-status-change/{id}', 'BatchController@batch_status_change');

    /*Batch End*/

    /*Section Start*/

    Route::any('section', 'SectionController@index');

    Route::any('add-section', 'SectionController@add_batch_view');

    Route::any('view-section/{id}', 'SectionController@show');

    Route::any('store-section', 'SectionController@store');

    Route::any('edit-section-view/{id}', 'SectionController@show_edit');

    Route::any('section-data-edit/{id}', 'SectionController@upadate_save');

    Route::any('delete-section/{id}', 'SectionController@delete');
    Route::any('section-status-change/{id}', 'SectionController@section_status_change');

    /*Section End*/


    Route::get('/division', 'DivisionController@index');
    Route::get('/division/create', 'DivisionController@create');
    Route::get('/division/{id}/edit', 'DivisionController@edit');
    Route::get('/division/{id}/delete', 'DivisionController@destroy');
    Route::get('/division/{id}/status', 'DivisionController@status');
    Route::post('/division/store', 'DivisionController@store');

    //getajaxforSelectedDropdown
    Route::get('search/section/from/{batchId}', 'ManageAcademicsController@getAjaxSection');



    /*Room category starts */
    Route::get('roomcategory', 'RoomCategoryController@index');
    Route::post('roomcategory/store', 'RoomCategoryController@storeRoomCategory');
    Route::get('roomcategory/delete/{id}', 'RoomCategoryController@deleteRoomCategory');
    Route::get('roommaster', 'RoomMasterController@index');
    Route::post('roommaster/create', 'RoomMasterController@createOrUpdateRoom');
    Route::get('roommaster/delete/{id}', 'RoomMasterController@deleteRoomMaster');
    /*Room category ends */

    // find class subject teacher list
    Route::get('/find/teacher/class/subject/{csId}', 'ManageTimetable\TimeTableController@findTeacher');
    // manage section
    Route::get('manage/section/show/{batchId}', 'ManageAcademicsController@showSection');
    Route::get('manage/section/create/{batchId}', 'ManageAcademicsController@addSection');
    Route::post('manage/section/store', 'ManageAcademicsController@storeSection');
    // manage subject
    Route::get('manage/class/subject/section', 'ManageAcademicsController@allClassSubject');
    Route::get('manage/subject/{batchId}', 'ManageAcademicsController@addSubject');
    Route::post('manage/subject/store', 'ManageAcademicsController@storeSubject');
    // assign teacher
    Route::get('manage/subjcet/teacher/view/{id}', 'ManageAcademicsController@viewAssingedTeacher');
    Route::get('manage/subjcet/teacher/delete/{id}', 'ManageAcademicsController@deleteAssingedTeacher');
    Route::get('manage/subjcet/teacher/assign/{id}', 'ManageAcademicsController@assingTeacher');
    Route::post('manage/subjcet/teacher/store', 'ManageAcademicsController@storeAssingTeacher');

    // find class section fourth (4th) subject list
    Route::post('/manage/class/section/fourth/subject/list', 'ManageAcademicsController@getClassSectionAdditionalSubjectList');
    Route::post('/manage/class/section/fourth/subject/store', 'ManageAcademicsController@storeClassSectionAdditionalSubjectList');

    // class section subject student
    Route::post('/class/subject/student/list', 'AssessmentsController@getClassSectionSubjectStudentList');

    ////////////// subject group /////////////////////
    Route::get('/subject/group', 'SubjectController@getSubjectGroup');
    Route::get('/subject/group/create', 'SubjectController@createSubjectGroup');
    Route::get('/subject/group/edit/{subGroupId}', 'SubjectController@editSubjectGroup');
    Route::get('/subject/group/delete/{subGroupId}', 'SubjectController@deleteSubjectGroup');
    Route::post('/subject/group/store', 'SubjectController@storeSubjectGroup');
    Route::get('/subject/group/assign', 'SubjectController@assignSubject');

    // subject group assign
    Route::post('/subject/group/assign/store', 'SubjectController@storeAssignedSubject');
    Route::get('/subject/group/assign/delete/{subGroupAssignId}', 'SubjectController@deleteAssignedSubject');
    Route::get('/subject/group/assign/{subGroupId}', 'SubjectController@assignSubject');


    // manage assessments pages
    Route::get('manage/assessments/{tabId}', 'AssessmentsController@index');
    // grade setup page
    Route::get('manage/assessments/category/create', 'AssessmentsController@createAssessmentCategory');
    Route::post('manage/assessments/category/store', 'AssessmentsController@storeAssessmentCategory');
    Route::get('manage/assessments/category/edit/{id}', 'AssessmentsController@editAssessmentCategory');
    Route::post('manage/assessments/category/update/{id}', 'AssessmentsController@updateAssessmentCategory');
    Route::get('manage/assessments/category/delete/{id}', 'AssessmentsController@destroyAssessmentCategory');

    //Route::get('manage/assessments/manage/attendance/getStudentDailyAttendancedetails/{gradeId}', 'AssessmentsController@assessmentDetails');
    Route::get('/manage/assessments/details/{gradeId}', 'AssessmentsController@assessmentDetails');
    Route::get('manage/assessments/grade/create', 'AssessmentsController@createGrade');
    Route::post('manage/assessments/grade/store-grade', 'AssessmentsController@storeGrade');
    Route::get('manage/assessments/grade/delete/{id}', 'AssessmentsController@destroyGrade');

    Route::get('manage/assessments/grade/edit/{id}', 'AssessmentsController@editGrade');
    Route::post('manage/assessments/grade/update/{id}', 'AssessmentsController@updateGrade');
    // assign grading weighted average
    Route::get('manage/assessments/grade/weight_average/assign', 'AssessmentsController@assignWeightedAverage');
    Route::POST('manage/assessments/grade/weight_average/assign', 'AssessmentsController@manageWeightedAverageAssign');
    // assign grading scale
    Route::get('manage/assessments/grade/scale/assign', 'AssessmentsController@assignGradeScale');
    Route::POST('manage/assessments/grade/scale/manage-assign', 'AssessmentsController@manageGradeScaleAssign');
    // assign assessment for count best results from multiple
    Route::get('manage/assessments/grade/category/assign', 'AssessmentsController@assignGradeCategory');
    Route::POST('manage/assessments/grade/category/manage-assign', 'AssessmentsController@manageGradeCategoryAssign');


    // assessment page
    Route::get('manage/assessments/assessment/create', 'AssessmentsController@createAssessment');
    Route::post('manage/assessments/assessment/store', 'AssessmentsController@storeAssessment');
    // edit assessment
    Route::get('manage/assessments/assessment/edit/{id}', 'AssessmentsController@editAssessment');
    Route::post('manage/assessments/assessment/update/{id}', 'AssessmentsController@updateAssessment');
    Route::get('manage/assessments/assessment/destroy/{id}', 'AssessmentsController@destroyAssessment');


    // grade-book
    Route::post('manage/assessments/gradebook/', 'AssessmentsController@getGradeBook');
    Route::post('manage/assessments/gradebook/store', 'AssessmentsController@storeGradeBook');
    Route::post('manage/assessments/gradebook/update/', 'AssessmentsController@updateGradeBook');
    Route::get('manage/assessments/gradebook/import/', 'AssessmentsController@importGradeBook');
    Route::post('manage/assessments/gradebook/export', 'AssessmentsController@exportGradeBook');
    Route::post('manage/assessments/gradebook/upload', 'AssessmentsController@uploadGradeBook');

    // extra book
    Route::post('/manage/assessments/extra-book/find', 'AssessmentsController@getExtraBook');
    Route::post('/manage/assessments/extra-book/store', 'AssessmentsController@storeExtraBook');

    // exam
    Route::post('/manage/assessments/exam/status', 'ExamController@getExamStatus');
    Route::post('/manage/assessments/exam/status/update', 'ExamController@updateExamStatus');
    Route::get('/exam-category/exam', 'ExamController@examCategoryExam');
    Route::get('/edit/exam-category/exam/{id}', ['access' => ['academics/exam-category.edit'], 'uses' => 'ExamController@editExamCategoryExam']);
    Route::post('/update/exam/category/{id}', ['access' => ['academics/exam-category.edit'], 'uses' => 'ExamController@updateExamCategoryExam']);
    Route::get('/delete/exam/category/{id}', ['access' => ['academics/exam-category.edit'], 'uses' => 'ExamController@deleteExamCategory']);
    Route::get('/exam/name/assign/view/{id}', ['access' => ['academics/exam.assign'], 'uses' => 'ExamController@examAssignView']);
    Route::get('/edit/exam/name/{id}', ['access' => ['academics/exam.edit'], 'uses' => 'ExamController@editExamName']);
    Route::post('/update/exam/name/{id}', ['access' => ['academics/exam.edit'], 'uses' => 'ExamController@updateExamName']);
    Route::get('/delete/exam/{id}', ['access' => ['academics/exam.delete'], 'uses' => 'ExamController@deleteExamName']);
    Route::post('/exam/class/assign/{id}', 'ExamController@examClassAssign');


    Route::post('/exam-category/store', 'ExamController@storeExamCategory');
    Route::post('/exam-name/store', 'ExamController@storeExamName');

    // Exam Mark routes
    Route::get('/exam/marks', 'ExamController@examMarks');
    Route::get('/exam/set/marks', 'ExamController@examSetMarks');
    Route::post('/exam/set/marks/post', 'ExamController@examSetMarksPost');
    Route::post('/exam/set/all/marks/post', 'ExamController@examSetAllMarksPost');
    Route::get('/exam/marks/entry/', 'ExamController@examMarksEntry');
    Route::post('/exam/save/student/marks', 'ExamController@examSaveStudentMarks');
    Route::post('/exam/delete/student/marks', 'ExamController@examDeleteStudentMarks');

    Route::get('/exam/list', 'ExamController@examList');
    Route::get('/exam/send-for-approval/{id}', 'ExamController@examSendForApproval');
    
    //tabulation routes by dev9

    Route::get('/exam/tabulation-sheet', 'TabulationSheetController@tabulationSheet');
    Route::get('/exam/tabulation-sheet-pdf', 'TabulationSheetController@tabulationSheetPdf');

    Route::get('/exam/final-sheet', 'BoardExamResultController@finalSheet');
    Route::get('/exam/final-sheet-pdf', 'BoardExamResultController@finalSheetPdf');

    //Board Exam Result Routes
    Route::get('/exam/board-exam-result', 'BoardExamResultController@boardExamResult');
    Route::get('/exam/board-exam-search-class', 'BoardExamResultController@boardExamSearchClass');
    Route::post('/exam/save/student/board-exam', 'BoardExamResultController@publicExamSaveStudentMarks');

    //Board Exam Result Ajax Routes

    Route::post('/exam/board-exam-result/search-students', 'BoardExamResultController@searchPublicExamStudent');

    // Exam Mark Ajax Routes
    Route::get('/exam/search-semester', 'ExamController@examSearchSemester');
    Route::get('/exam/search-exam', 'ExamController@examSearchExam');
    Route::get('/exam/search-class', 'ExamController@examSearchClass');
    Route::get('/exam/search-forms', 'ExamController@examSearchForms');
    Route::get('/exam/search-subjects', 'ExamController@examSearchSubjects');
    Route::get('/exam/search-subjects/from-marks', 'ExamController@searchSubjectsFromMarks');
    Route::get('/exam/student/search/', 'ExamController@examStudentSearch');

    // Exam Schedule routes
    Route::get('/exam/schedules', 'ExamController@examSchedules');
    // Exam Schedule Ajax Routes
    Route::get('/exam/search-classes/from-exam', 'ExamController@examSearchClassesFromExam');
    Route::get('/exam/search-schedule', 'ExamController@examSearchSchedule');
    Route::get('/exam/save-schedule', 'ExamController@examSaveSchedule');
    Route::get('/exam/save/all/schedules', 'ExamController@saveAllExamSchedules');

    // Exam Attendance routes
    Route::get('/exam/attendance', 'ExamController@examAttendance');
    // Exam Attendance Ajax Routes
    Route::get('/exam/search-subjects-from/exam-schedules', 'ExamController@searchSubjectsFromExamSchedule');
    Route::get('/exam/search-mark-parameters-from/exam-schedules', 'ExamController@searchMarkParametersFromExamSchedule');
    Route::get('/exam/search-students/for-attendance', 'ExamController@searchStudentsForAttendance');
    Route::get('/exam/save-students-attendance', 'ExamController@saveStudentsAttendance');
    Route::post('/exam/print/attendance/sheet', 'ExamController@printAttendanceSheet');

    //Exam Final Result By Dev9
    Route::get('/exam/term-exam', 'ExamFinalController@index');
    Route::get('/exam/term-exam-pdf', 'ExamFinalController@termEndExamResultPdf');
    Route::get('/exam/term-exam-details', 'ExamFinalController@termEndExamResults');
    Route::get('/exam/term-exam-details-pdf', 'ExamFinalController@termEndExamResultDetailsPdf');

    // Exam Seat Plan
    Route::get('/exam/seatPlan', 'ExamSeatPlanController@index');
    Route::get('/exam/from/exam-category', 'ExamSeatPlanController@examFromExamCategory');
    Route::get('/search/exam-seat', 'ExamSeatPlanController@searchExamSeat');
    Route::get('/get/students/from/sections', 'ExamSeatPlanController@getStudentsFromSections');
    Route::get('/get/seat/plan/view', 'ExamSeatPlanController@getSeatPlanView');
    Route::get('/schedule/wise/criteria/from/subject', 'ExamSeatPlanController@scheduleWiseCriteriaFromSubject');
    Route::post('/save/seat/plan', 'ExamSeatPlanController@saveSeatPlan');
    Route::post('/print/seat/plan', 'ExamSeatPlanController@printSeatPlan');
    Route::get('/invigilator/history/{yearId}/{termId}/{examId}/{ids}', ['access' => ['academics/exam/invigilator.history'], 'uses' => 'ExamSeatPlanController@invigilatorHistory']);


    // Tabulation Sheet Routes
    Route::get('/exam/tabulation-sheet/{type}/{listId?}', ['access' => ['academics/exam/tabulation-sheet/exam', 'academics/exam/tabulation-sheet/term', 'academics/exam/tabulation-sheet/year'], 'uses' => 'TabulationSheetController@index']);
    Route::post('/exam/tabulation-sheet-exam/search-marks', 'TabulationSheetController@examSheetSearchMarks');
    Route::get('/exam/tabulation-sheet-exam/approve', 'TabulationSheetController@examResultApprove');
    Route::post('/exam/tabulation-sheet-term/search-marks', 'TabulationSheetController@termSheetSearchMarks');
    Route::post('/exam/tabulation-sheet-term-summary/search-marks', 'TabulationSheetController@termSummarySheetSearchMarks');
    Route::post('/exam/tabulation-sheet-year/search-marks', 'TabulationSheetController@yearSheetSearchMarks');
    Route::get('/exam/from/year', 'TabulationSheetController@examFromYear');
    Route::get('/profile/academic2/exam-result/{std_id}/{batch}/{section}/{academicYear}/{termId}/{examId}', 'TabulationSheetController@getStudentProfileSingleExamInfo')->middleware('std-profile-permission'); //home



    // result publish send sms
    Route::post('/manage/assessments/exam/result/sendsms', 'ExamController@resultPublishSendSms');

    // Setting
    Route::post('/manage/assessments/category/setting', 'AssessmentsController@assessmentCategorySetting');
    Route::post('/manage/assessments/category/setting/manage', 'AssessmentsController@manageAssessmentCategorySetting');

    // result
    Route::post('manage/assessment/result', 'AssessmentsController@getResult');
    Route::post('manage/assessment/semester/merit-list', 'ExamController@getBachSectionSemesterMeritList');
    // result sheet for semester
    Route::post('manage/assessment/semester/result-sheet', 'AssessmentsController@getClassSectionSemesterResultSheet');
    Route::post('manage/assessment/semester/result-sheet-college', 'AssessmentsController@getClassSectionSemesterResultSheet');
    // tabulation sheet for semester
    Route::post('manage/assessment/semester/tabulation-sheet', 'AssessmentsController@getClassSectionSemesterResultSheet');
    Route::post('manage/assessment/semester/tabulation-sheet-college', 'AssessmentsController@getClassSectionSemesterResultSheetForCollege');
    // result sheet for year final
    Route::post('manage/assessment/final/result-sheet', 'AssessmentReportController@getClassSectionFinalResultSheet');

    // graph
    Route::get('manage/assessments/semester/graph', 'AssessmentsController@semesterAssessmentGraph');
    Route::get('manage/attendance/month/graph', 'AttendanceController@monthlyAttendanceGraph');



    /*Attendance Starts here*/
    Route::get('manage/attendance/{tabId}', 'AttendanceController@index');
    Route::get('manage/attendance/settings/status/create', 'AttendanceController@createStatus');
    Route::post('manage/attendance/settings/status/store', 'AttendanceController@storeStatus');
    Route::get('manage/attendance/settings/status/edit/{id}', 'AttendanceController@editStatus');
    Route::post('manage/attendance/settings/status/update/{id}', 'AttendanceController@updateStatus');
    Route::get('manage/attendance/settings/status/delete/{id}', 'AttendanceController@destroyStatus');

    Route::get('manage/attendance/settings/session/create', 'AttendanceController@createSession');
    Route::post('manage/attendance/settings/session/store', 'AttendanceController@storeSession');
    Route::get('manage/attendance/settings/session/edit/{id}', 'AttendanceController@editSession');
    Route::post('manage/attendance/settings/session/update/{id}', 'AttendanceController@updateSession');
    Route::get('manage/attendance/settings/session/delete/{id}', 'AttendanceController@destroySession');

    Route::get('manage/attendance/settings/type/attendance/{status}', 'AttendanceController@setAttendanceType');
    Route::get('manage/attendance/settings/type/session/{status}', 'AttendanceController@setSessionType');

    Route::post('manage/attendance/getStudentDailyAttendance', 'AttendanceController@getDailyAttendanceStudentList'); // daily attendance list
    Route::post('manage/attendance/storeStudentDailyAttendance', 'AttendanceUploadController@storeDailyAttendanceList'); // daily attendance store
    Route::post('manage/attendance/getAttendanceStudent', 'AttendanceController@getClassSectionStudentList');
    Route::post('manage/attendance/manage-attendance', 'AttendanceController@manageAttendance');
    Route::post('manage/attendance/manage-attendance-2', 'AttendanceController@manageAttendance2');
    Route::post('manage/attendance/manageanothertendance', 'AttendanceController@getAnotherAttendanceList');
    // export-import attendance list
    Route::get('/attendance/export', 'AttendanceController@exportAttendanceList');
    Route::get('/attendance/import', 'AttendanceController@importAttendanceList');
    Route::post('/attendance/upload', 'AttendanceController@uploadAttendanceList');
    Route::post('/attendance/upload/store', 'AttendanceController@uploadFinalAttendanceList');
    Route::post('/manage/attendance/file/upload', 'AttendanceUploadController@uploadAttendanceExcelData');
    //    // attendance by parent
    //    Route::get('/parent/attendance/info/{parentId}', 'AttendanceController@showChildAttendanceByParent');
    //    Route::post('/parent/attendance/show/attendance', 'AttendanceController@showChildAttendanceReport');
    /*Attendance ends here*/

    // attendance upload section
    //    Route::post('/upload/attendance/uploadpunch','AttendanceUploadController@uploadAttendancePunch');
    //    Route::post('/upload/attendance/final-upload','AttendanceUploadController@storeUploadedAttendance');

    // attendance upload section
    Route::get('/upload/attendance/download/{id}', 'AttendanceUploadController@downloadHistory');
    Route::post('/upload/attendance/upload', 'AttendanceUploadController@uploadAttendance');
    Route::post('/upload/attendance/final-upload', 'AttendanceUploadController@storeUploadedAttendance');
    Route::post('/upload/attendance/report', 'AttendanceUploadController@uploadedAttendanceReport');
    // download monthly attendance report
    Route::post('/attendance/upload/monthly/report', 'AttendanceUploadController@downloadMonthlyAttendanceReport');


    // Manage timetable starts here
    Route::get('/timetable/{tabId}', 'ManageTimetable\TimeTableController@index');
    Route::post('/timetable/classSectionDayTimetable', 'ManageTimetable\TimeTableController@classSectionDayTimetable');
    Route::post('/timetable/classSectionTimeTableManager', 'ManageTimetable\TimeTableController@classSectionTimeTableManager');
    Route::post('/timetable/classSectionTimeTable', 'ManageTimetable\TimeTableController@getClassSectionTimeTable');
    Route::get('/timetable/teacherTimeTable/{subTeacherId}', 'ManageTimetable\TimeTableController@getTeacherTimeTable');

    Route::get('/timetable/period/add', 'ManageTimetable\TimeTableController@createPeriod');
    Route::post('/timetable/period/store', 'ManageTimetable\TimeTableController@storePeriod');
    Route::get('/timetable/period/edit/{catId}', 'ManageTimetable\TimeTableController@editPeriod');
    Route::get('/timetable/period/delete/{periodId}', 'ManageTimetable\TimeTableController@deletePeriod');

    Route::get('/timetable/period/category/add', 'ManageTimetable\TimeTableController@createPeriodCategory');
    Route::post('/timetable/period/category/store', 'ManageTimetable\TimeTableController@storePeriodCategory');
    Route::get('/timetable/period/category/edit/{catId}', 'ManageTimetable\TimeTableController@editPeriodCategory');
    Route::get('/timetable/period/category/delete/{catId}', 'ManageTimetable\TimeTableController@deletePeriodCategory');
    Route::get('/timetable/store/timetable', 'ManageTimetable\TimeTableController@storeTimetable');
    // class section timetable
    Route::get('/find-class-section-timetable', 'ManageTimetable\TimeTableController@getClassSectionTimeTable');

    // set class section period
    Route::get('/timetable/period/category/assign/class', 'ManageTimetable\TimeTableController@assignPeriodCategory');
    Route::get('/timetable/period/category/assign/{id}', 'ManageTimetable\TimeTableController@periodCategoryDetails');
    Route::get('/timetable/period/category/assign/delete/{id}', 'ManageTimetable\TimeTableController@removeAssignedPeriodCategory');
    Route::get('/timetable/period/category/assign-manage', 'ManageTimetable\TimeTableController@manageAssignPeriodCategory');


    //Report
    Route::post('report/attendance/summary/class/section', 'Reports\ClassAttendanceSummaryReportController@classSectionAttendanceReportByDateRange');
    // Route::post('report/attendance/summary/class/section', 'Reports\ClassAttendanceSummaryReportController@indexReport');

    // semester
    Route::resource('/semester', 'SemesterController');
    Route::get('/semester/batch/assign', 'SemesterController@assignSemester');
    Route::get('/semester/batch/semester', 'SemesterController@getSemesterList');
    Route::get('/semester/batch/semester/status', 'SemesterController@changeSemesterStatus');
    Route::get('/semester/status/{id}', 'SemesterController@status');
    Route::get('/semester/delete/{id}', 'SemesterController@destroy');

    // report card setting
    Route::post('manage/assessments/report-card/setting/', 'AssessmentsController@manageReportCardSetting');
});

Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'academics', 'namespace' => 'Modules\Academics\Http\Controllers'], function () {

    Route::get('/timetable/studentTimeTable/{stdId}', 'ManageTimetable\TimeTableController@getStudentTimeTable');
    Route::get('/timetable/teacher/{teacherId}', 'ManageTimetable\TimeTableController@getTeacherTimeTableView');
    Route::get('/timetable/teacherTimeTable/report/{teacherId}', 'ManageTimetable\TimeTableController@getTeacherTimeTableReport');
    Route::get('/timetable/studentTimeTable/report/{stdId}', 'ManageTimetable\TimeTableController@getStudentTimeTableReport');

    // class teacher assign here

    Route::get('/timetable/class-teacher/assign', 'ManageTimetable\TimeTableController@classTeacherAssignModal');
    Route::post('/timetable/class-teacher-assign', 'ManageTimetable\TimeTableController@classTeacherAssign');
    // delete
    Route::get('/timetable/class-teacher/assign/delete/{id}', 'ManageTimetable\TimeTableController@classTeacherDelete');


    ////////// ajax request routes for student module  //////////
    Route::get('/find/level', 'AcademicsLevelController@findLevel');
    Route::get('/find/batch/', 'BatchController@findBatch');
    Route::get('/find/section', 'SectionController@findSection');
    Route::get('/find/subjcet', 'ManageAcademicsController@findsubjcet');
    Route::get('/find/group', 'ManageAcademicsController@findGroup');
    Route::get('/find/group/subject', 'ManageAcademicsController@findGroupSubject');
    Route::get('/find/batch/section/', 'SectionController@findBatchSection');
    Route::get('/find/batch/semester', 'ManageAcademicsController@findSemester');
    Route::get('manage', 'ManageAcademicsController@index');
    Route::get('/find/campus/subject', 'SubjectController@findCampusSubject');
    Route::get('/find/assessment-list', 'AssessmentsController@findAssessmentList');
    Route::get('/find/batch/division', 'SectionController@findBatchDivision');


    //    // report card
    //    Route::post('manage/assessments/report-card/show/', 'AssessmentsController@showSingleReportCard');
    //    Route::get('manage/assessments/report-card/download/{id}', 'AssessmentsController@singleReportCardDownloadOption');
    //    Route::post('manage/assessments/report-card/download/single', 'AssessmentsController@downloadSingleReportCard');
    //    Route::get('manage/assessments/report-card/download/', 'AssessmentsController@showClassSectionReportCard');
    //    Route::post('manage/assessments/report-card/download/', 'AssessmentsController@downloadClassSectionReportCard');

    // semester report card
    Route::post('manage/assessments/report-card/show/', 'AssessmentsController@showSingleReportCard');
    Route::get('manage/assessments/report-card/download/{id}', 'AssessmentsController@singleReportCardDownloadOption');
    //Route::post('manage/assessments/report-card/download/single', 'AssessmentsController@downloadSingleReportCard');
    Route::get('manage/assessments/report-card/download/', 'AssessmentsController@showClassSectionReportCard');
    Route::post('manage/assessments/report-card/download/', 'AssessmentsController@downloadClassSectionReportCard');

    // final report card
    Route::post('manage/assessments/final/report-card/single', 'AssessmentReportController@downloadFinalReportCard');
    Route::post('manage/assessments/final/report-card/batch/section/', 'AssessmentReportController@downloadFinalReportCard');



    // route for new assessment controller
    Route::post('manage/assessments/report-card/download/single', 'AssessmentsController@downloadSingleReportCard');
    Route::post('send/gradebook/sba/result', 'SendSmsResultController@SendSBAResultSMS');



    // Route for Physical Rooms Category
    Route::post('/physical/room/category/store', 'PhysicalRoomCategoryController@store');
    Route::get('/physical/room/category/edit/{id}', 'PhysicalRoomCategoryController@edit');
    Route::post('/physical/room/category/update/{id}', 'PhysicalRoomCategoryController@update');
    Route::get('/physical/room/category/delete/{id}', 'PhysicalRoomCategoryController@destroy');

    // Route for Physical Rooms
    Route::get('/physical/rooms', 'PhysicalRoomController@index');
    Route::post('/physical/room/store', 'PhysicalRoomController@store');
    Route::get('/physical/room/edit/{id}', 'PhysicalRoomController@edit');
    Route::post('/physical/room/update/{id}', 'PhysicalRoomController@update');
    Route::get('/physical/room/allocate/view/{id}', 'PhysicalRoomController@allocateView');
    Route::post('/physical/room/allocate_students/{id}', 'PhysicalRoomController@allocateStudents');
    Route::get('/physical/room/allocate/edit/view/{id}', 'PhysicalRoomController@allocateEditView');
    Route::get('/physical/room/delete/{id}', 'PhysicalRoomController@destroy');
    Route::post('/physical/room/search/students', 'PhysicalRoomController@searchStudents');
    Route::post('/physical/room/search/prefects', 'PhysicalRoomController@searchPrefects');
});
