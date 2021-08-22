<?php

Route::group(['middleware' => ['web', 'auth', 'access-permission'], 'prefix' => 'employee', 'namespace' => 'Modules\Employee\Http\Controllers'], function () {

    // employee index
    Route::get('/', 'EmployeeController@index');

    // manage employee
    Route::get('/manage', 'EmployeeController@manageEmployee')->name('manage-hr');
    Route::post('/manage/search', 'EmployeeController@searchEmployee');
    Route::post('/employee/search', 'EmployeeLeaveController@searchEmployee');
    Route::post('/search/leave/encashment', 'EmployeeLeaveController@searchEmployeeEncahment');
    Route::post('/assign/form/submit', 'EmployeeLeaveController@assignSubmitEmployee');

    Route::get('import/images', 'EmployeeController@imagePage');
    Route::post('image/import/upload', 'EmployeeController@imageUpload');


    // employee download


    Route::post('/manage/download/excel', 'EmployeeController@searchEmployeeDownload');


    // manage teacher
    Route::get('/manage/teacher', 'EmployeeController@manageTeacher');
    Route::post('/manage/teacher', 'EmployeeController@findTeacherList');

    // employee create
    Route::get('/create', 'EmployeeController@createEmployee');
    // employee store
    Route::post('/store', 'EmployeeController@storeEmployee');

    // employee info like email and photo
    Route::get('/email/edit/{id}', 'EmployeeInfoController@employeeEmailEdit');
    Route::post('/email/update/{id}', 'EmployeeInfoController@employeeEmailUpdate');
    Route::get('/photo/edit/{id}', 'EmployeeInfoController@employeePhotoEdit');
    Route::post('/photo/store', 'EmployeeInfoController@storeEmployeePhoto');
    Route::post('/photo/update/{id}', 'EmployeeInfoController@employeePhotoUpdate');

    // employee personal
    Route::get('/profile/personal/{id}', 'EmployeeInfoController@showEmployeeInfo');
    Route::get('/profile/personal/edit/{id}', 'EmployeeInfoController@editEmployeeInfo');
    Route::post('/profile/personal/update/{id}', 'EmployeeInfoController@updateEmployeeInfo');

    // employee addresses
    Route::get('/profile/address/{id}', 'EmployeeAddressController@showEmployeeAddress');
    Route::post('/profile/address/store', 'EmployeeAddressController@storeEmployeeAddress');
    Route::get('/profile/address/edit/{id}', 'EmployeeAddressController@editEmployeeAddress');
    Route::post('/profile/address/update/{id}', 'EmployeeAddressController@updateEmployeeAddress');

    // employee guardian
    Route::get('/profile/guardian/{id}', 'EmployeeGuardianController@show');
    Route::post('/profile/guardian/store', 'EmployeeGuardianController@store');
    Route::get('/profile/guardian/create/{id}', 'EmployeeGuardianController@create');
    Route::get('/profile/guardian/edit/{id}', 'EmployeeGuardianController@edit');
    Route::post('/profile/guardian/update/{id}', 'EmployeeGuardianController@update');
    Route::get('/profile/guardian/delete/{id}', 'EmployeeGuardianController@destroy');
    Route::get('/profile/guardian/emergency/{id}', 'EmployeeGuardianController@status');

    // employee document
    Route::get('/profile/document/{id}', 'EmployeeAttachmentController@show'); //home
    Route::get('/profile/document/create/{id}', 'EmployeeAttachmentController@create');
    Route::post('/profile/documents/store', 'EmployeeAttachmentController@store');
    Route::get('/profile/documents/edit/{id}', 'EmployeeAttachmentController@edit');
    Route::post('/profile/documents/update/{id}', 'EmployeeAttachmentController@update');
    Route::get('/profile/documents/delete/{id}', 'EmployeeAttachmentController@destroy');
    Route::get('/profile/documents/status/{id}/{status}', 'EmployeeAttachmentController@status');

    // employee qualification
    Route::get('/profile/qualification/{id}', 'EmployeeQualificationController@index');
    Route::get('/profile/create/qualification/{id}', 'EmployeeQualificationController@create');
    Route::post('/profile/store/qualification', 'EmployeeQualificationController@store');
    Route::get('/profile/edit/qualification/{id}', 'EmployeeQualificationController@edit');
    Route::post('/profile/update/qualification/{id}', 'EmployeeQualificationController@update');
    Route::get('/profile/delete/qualification/{id}', 'EmployeeQualificationController@destroy');

    // employee experience
    Route::get('/profile/experience/{id}', 'EmployeeExperienceController@index');
    Route::get('/profile/create/experience/{id}', 'EmployeeExperienceController@create');
    Route::post('/profile/store/experience', 'EmployeeExperienceController@store');
    Route::get('/profile/edit/experience/{id}', 'EmployeeExperienceController@edit');
    Route::post('/profile/update/experience/{id}', 'EmployeeExperienceController@update');
    Route::get('/profile/delete/experience/{id}', 'EmployeeExperienceController@destroy');


    // employee department
    Route::get('/departments', 'EmployeeDepartmentController@index');
    Route::get('/departments/{id}', 'EmployeeDepartmentController@show');
    Route::get('/department/create', 'EmployeeDepartmentController@create');
    Route::post('/departments/store', 'EmployeeDepartmentController@store');
    Route::get('/departments/edit/{id}', 'EmployeeDepartmentController@edit');
    Route::post('/departments/update/{id}', 'EmployeeDepartmentController@update');
    Route::get('/departments/delete/{id}', 'EmployeeDepartmentController@destroy');

    // employee designation
    Route::get('/designations', 'EmployeeDesignationController@index');
    Route::get('/designation/create', 'EmployeeDesignationController@create');
    Route::get('/designations/{id}', 'EmployeeDesignationController@show');
    Route::post('/designations/store', 'EmployeeDesignationController@store');
    Route::get('/designations/edit/{id}', 'EmployeeDesignationController@edit');
    Route::post('/designations/update/{id}', 'EmployeeDesignationController@update');
    Route::get('/designations/delete/{id}', 'EmployeeDesignationController@destroy');

    // employee import
    Route::get('/import', 'EmployeeController@importEmployee');
    Route::post('/import/list', 'EmployeeController@showImportedEmployeeList');
    Route::post('/import/list/check', 'EmployeeController@checkImportedEmployeeList');
    Route::post('/import/upload', 'EmployeeController@uploadEmployee');
    Route::post('/check/emails', 'EmployeeController@checkEmployeeEmail');


    // employee report
    Route::get('/report/profile/{id}', 'EmployeeReoprtController@index');
    // Route::get('/report/profile/{id}', 'EmployeeReoprtController@indexReport');

    ////////////////////  Holiday Management ////////////////////

    Route::get('/manage/national-holiday', 'NationalHolidayController@index');
    Route::get('/manage/national-holiday/create', 'NationalHolidayController@create');
    Route::post('/manage/national-holiday/store', 'NationalHolidayController@store');
    Route::get('/manage/national-holiday/edit/{holidayId}', 'NationalHolidayController@edit');
    Route::get('/manage/national-holiday/delete/{holidayId}', 'NationalHolidayController@destroy');

    ////////////////////  Week-off Management ////////////////////

    Route::get('/manage/week-off', 'WeekOffDayController@index');
    Route::get('/manage/week-off/create', 'WeekOffDayController@create');
    Route::post('/manage/week-off/store', 'WeekOffDayController@store');
    Route::get('/manage/week-off/edit/', 'WeekOffDayController@edit');
    Route::post('/manage/week-off/update/', 'WeekOffDayController@update');
    Route::get('/manage/week-off/delete/{weekOffId}', 'WeekOffDayController@destroy');


    ////////////////////  Holiday Management ////////////////////
    //employee leave
    Route::get('/leavetype', 'EmployeeLeaveController@index');
    Route::get('/addleavetype', 'EmployeeLeaveController@add_leave_type');
    Route::get('/leavestructure', 'EmployeeLeaveController@leave_structure');
    Route::get('/addleavestructure', 'EmployeeLeaveController@add_leave_structure');

    //Leave Assign
    Route::get('/leave/assign', 'EmployeeLeaveController@LeaveAssign');
    Route::get('/all/leave/assign', 'EmployeeLeaveController@AllLeaveAssign');
    Route::get('/leave/encashment', 'EmployeeLeaveController@LeaveEncashment');
    Route::get('/leave/assign/user', 'EmployeeLeaveController@userLeaveAssign');
    Route::get('/leave/assign/role', 'EmployeeLeaveController@roleLeaveAssign');
    Route::get('/department/designation/{id}', 'EmployeeLeaveController@getAjaxDepartmentDesignation');
    Route::get('/manage/search', 'EmployeeLeaveController@searchEmployee');

    ////////////////////  Leave Management ////////////////////
    // leave type
    Route::get('/manage/leave/type', 'LeaveManagementController@getType');
    Route::get('/manage/leave/type/create', 'LeaveManagementController@createType');
    Route::post('/manage/leave/type/store', 'LeaveManagementController@storeType');
    Route::get('/manage/leave/type/edit/{id}', 'LeaveManagementController@editType');
    Route::post('/manage/leave/type/update/{id}', 'LeaveManagementController@updateType');
    Route::get('/manage/leave/type/delete/{id}', 'LeaveManagementController@destroyType');
    // leave structure
    Route::get('/manage/leave/structure', 'LeaveManagementController@getStructure');
    Route::get('/manage/leave/structure/create', 'LeaveManagementController@createStructure');
    Route::post('/manage/leave/structure/store', 'LeaveManagementController@storeStructure');
    Route::get('/manage/leave/structure/edit/{id}', 'LeaveManagementController@editStructure');
    Route::post('/manage/leave/structure/update/{id}', 'LeaveManagementController@updateStructure');
    Route::get('/manage/leave/structure/delete/{id}', 'LeaveManagementController@destroyStructure');
    Route::get('/manage/leave/structure/{id}/{status}', 'LeaveManagementController@statusStructure');
    // leave structure type
    Route::get('/manage/leave/structure/venus/add/{id}', 'LeaveManagementController@createStructureType');
    Route::get('/manage/leave/structure/venus/edit/{id}', 'LeaveManagementController@editStructureType');
    Route::post('/manage/leave/structure/assign/type/store', 'LeaveManagementController@storeStructureType');

    // leave entitlement
    Route::get('/manage/leave/entitlement', 'LeaveManagementController@getLeaveEntitlement');
    Route::get('/manage/leave/entitlement/search', 'LeaveManagementController@getLeaveEntitledList');
    Route::get('/manage/leave/entitlement/create', 'LeaveManagementController@createLeaveAllocation');
    Route::post('/manage/leave/entitlement/store', 'LeaveManagementController@storeLeaveAllocation');

    // leave application
    Route::get('/leave/application', 'LeaveManagementController@leaveApplication');
    Route::get('/leave/application/create', 'LeaveManagementController@createLeaveApplication');
    Route::get('/leave/application/apply', 'LeaveManagementController@applyLeaveApplication');
    Route::post('/leave/application/store', 'LeaveManagementController@storeLeaveApplication');
    Route::get('/manage/leave/application', 'LeaveManagementController@manageLeaveApplication');
    Route::get('/manage/leave/application/{id}', 'LeaveManagementController@showLeaveApplication');
    Route::post('/manage/leave/application/status', 'LeaveManagementController@changeLeaveApplicationStatus');

    // leave report
    Route::get('/manage/leave/report', 'LeaveManagementController@getEmployeeLeave');
    Route::post('/manage/leave/report', 'LeaveManagementController@downloadEmployeeLeave');

    // ajax request
    Route::get('/find/leave/structure/type/{id}', 'LeaveManagementController@findStructureTypes');
    Route::get('/find/leave/types/{id}', 'LeaveManagementController@findEmployeeStructureTypes');
    Route::get('/find/designation/list/{id}', 'EmployeeDesignationController@findDesignationList');

    //get only teacher

    Route::get('/find/teacher', 'EmployeeInfoController@getOnlyTeacher');
    Route::get('/find/stuff/', 'EmployeeInfoController@getAllStuff');
    Route::get('/find/stuff/{department_id}', 'EmployeeInfoController@getStuffByDepartment');

    // find all teacher and staff
    Route::get('/find/employee', 'EmployeeInfoController@getAllEmployee');

    // update employee web position
    Route::post('/update/web-position', 'EmployeeController@updateWebPosition');
    /////////////////////// Start Shift Management///////////////////////////
    Route::get('/shift', 'EmpShiftManagementController@index');
    Route::get('/shift/create', 'EmpShiftManagementController@create');
    Route::post('/shift/store', 'EmpShiftManagementController@store');
    Route::get('/shift/{id}', 'EmpShiftManagementController@show');
    Route::get('/shift/edit/{id}', 'EmpShiftManagementController@edit');
    Route::post('/shift/update/{id}', 'EmpShiftManagementController@update');
    Route::get('/shift/delete/{id}', 'EmpShiftManagementController@destroy');
    /////////////////////// End Shift Management///////////////////////////


    /////////////////////// End employee Shift Allocation Management///////////////////////////
    Route::get('/shift_allocation_home', 'EmpShiftAllocationManageController@index');
    Route::get('/shift_allocation', 'EmpShiftAllocationManageController@shift_allocation');
    Route::post('/shift_allocation/store', 'EmpShiftAllocationManageController@store');
    Route::post('/shift_allocation/emp_list', 'EmpShiftAllocationManageController@emp_list');
    //    Route::get('/no_shift_emp','EmpShiftAllocationManageController@no_shift_emp');
    /////////////////////// End employee Shift Allocation Management///////////////////////////

    /////////////////////// Start employee Attendance Management///////////////////////////
    Route::get('/employee-attendance', 'EmpAttendanceManageController@index');
    Route::get('/employee-attendance/today', 'EmpAttendanceManageController@view');
    Route::get('/add-attendance', 'EmpAttendanceManageController@addAttendance');
    Route::get('/update-attendance/{id}', 'EmpAttendanceManageController@updateAttendance');
    Route::post('/add-attendance/store', 'EmpAttendanceManageController@store');
    Route::get('/employee-attendance/custom', 'EmpAttendanceManageController@addCustomAttendance');
    Route::post('/employee-attendance/custom', 'EmpAttendanceManageController@getCustomAttendance');
    Route::post('/employee-attendance/custom/store', 'EmpAttendanceManageController@storeCustomAttendance');
    //    Route::get('/emp-attendance/{id}','EmpAttendanceManageController@show');
    Route::post('/employee-attendance/emp_list', 'EmpAttendanceManageController@emp_list');
    Route::get('/upload-attendance', 'EmpAttendanceManageController@uploadAttForm');
    Route::post('/upload-attendance/fileUp', 'EmpAttendanceManageController@fileUp');
    Route::post('/upload-attendance/fileUpSave', 'EmpAttendanceManageController@fileUpSave');
    Route::post('/upload-attendance', 'EmpAttendanceManageController@uploadAttStore');
    Route::post('/attendance/report/download', 'EmpAttendanceManageController@downloadAttendanceReport');
    Route::get('/employee-monthly-attendance', 'EmpAttendanceManageController@viewMonthlyReportFrom');
    Route::post('/employee-monthly-attendance-report', 'EmpAttendanceManageController@monthlyAttendanceReport');


    /////////////////////// End Attendance Management///////////////////////////


    /////////////////////// Start Employee Ot Management///////////////////////////
    Route::get('/employee-over-time-entry', 'EmpOtManageController@index');
    Route::get('/employee-over-time-entry/add', 'EmpOtManageController@create');
    Route::post('/employee-over-time-entry/store', 'EmpOtManageController@store');

    /////////////////////// End Employee Ot Management///////////////////////////


    /////////////////////// Start Employee Attendance Setting ///////////////////////////
    Route::get('/employee-attendance-setting', 'EmployeeAttendanceSettingController@index');
    Route::get('/employee-attendance-setting/create', 'EmployeeAttendanceSettingController@create');
    Route::get('/employee-attendance-setting/edit/{id}', 'EmployeeAttendanceSettingController@edit');
    Route::post('/employee-attendance-setting/store', 'EmployeeAttendanceSettingController@store');
    Route::get('/employee-attendance-setting/delete/{id}', 'EmployeeAttendanceSettingController@delete');

    /////////////////////// End Employee Attendance Setting///////////////////////////
    ///
    /// Change Employee Status
    Route::get('/employee-status/change/{empID}', 'EmployeeController@changeEmployeeStatus');

    //    get Ajax


    // Employee Status
    Route::get('/employee/status', 'EmployeeStatusController@index');
    Route::get('/employee/status/create', 'EmployeeStatusController@create');
    Route::post('/employee/status/store', 'EmployeeStatusController@store');
    Route::get('/employee/status/edit/{id}', 'EmployeeStatusController@edit');
    Route::post('/employee/status/update/{id}', 'EmployeeStatusController@update');
    Route::get('/employee/status/delete/{id}', 'EmployeeStatusController@destroy');


    // Shift Configuration
    Route::get('/shift-configuration/{id?}', 'ShiftConfigurationController@index');
    Route::post('/shift-configuration/add', 'ShiftConfigurationController@store');
    Route::post('/shift-configuration/update/{id}', 'ShiftConfigurationController@update');
    Route::get('/shift-configuration/delete/{id}', 'ShiftConfigurationController@destroy');


    // Holiday Calender
    Route::get('/holiday-calender/{id?}', 'HolidayCalenderController@index');
    Route::post('/create/holiday-calender', 'HolidayCalenderController@store');
    Route::post('/update/holiday-calender/{id}', 'HolidayCalenderController@update');
    Route::get('/holiday-calender/set-up/{id}', 'HolidayCalenderController@calenderSetUP');
    Route::get('/search/holiday-calender', 'HolidayCalenderController@calenderSearch');
    Route::post('/save/holiday-calender/{id}', 'HolidayCalenderController@calenderSave');
    Route::get('/delete/holiday-calender/{id}', 'HolidayCalenderController@destroy');


    // Evaluations
    Route::get('/evaluations/{id?}', 'EvaluationController@index');
    Route::post('/create/evaluation-parameter', 'EvaluationController@store');
    Route::get('/edit/evaluation-parameter/{id}', 'EvaluationController@edit');
    Route::post('/update/evaluation-parameter/{id}', 'EvaluationController@update');
    Route::get('/delete/evaluation-parameter/{id}', 'EvaluationController@destroy');
    Route::post('/create/evaluation', 'EvaluationController@storeEvaluation');
    Route::get('/edit/evaluation/{id}', 'EvaluationController@editEvaluation');
    Route::post('/update/evaluation/{id}', 'EvaluationController@updateEvaluation');
    Route::get('/delete/evaluation/{id}', 'EvaluationController@destroyEvaluation');
    Route::get('/assign-view/evaluation-parameter/{id}', 'EvaluationController@assignViewEvaluationParameter');
    Route::post('/assign/evaluation-parameter', 'EvaluationController@assignEvaluationParameter');
    Route::post('/setup/evaluation-parameter', 'EvaluationController@setupEvaluationParameter');
    Route::post('/setup/update/evaluation-parameter', 'EvaluationController@setupUpdateEvaluationParameter');
});



Route::group(['middleware' => ['web', 'auth'], 'prefix' => 'employee', 'namespace' => 'Modules\Employee\Http\Controllers'], function () {

    // Evaluation with student permission

    Route::get('/evaluation/view/{id?}', 'EvaluationController@evaluationView');
    Route::post('/evaluation/score/distribution', 'EvaluationController@evaluationScoreDistribution');
    Route::get('/evaluation/search/view', 'EvaluationController@evaluationSearchView');
    Route::get('/evaluation/history/view', 'EvaluationController@evaluationHistoryView');
    Route::post('/evaluation/search', 'EvaluationController@evaluationSearch');
    Route::post('/evaluation/history', 'EvaluationController@evaluationHistory');

    // Ajax Routes of Evaluation
    Route::get('/evaluation/ajax/search/year', 'EvaluationController@evaluationAjaxSearchYear');
    Route::get('/evaluation/ajax/search/evaluation', 'EvaluationController@evaluationAjaxSearchEvaluation');
});
