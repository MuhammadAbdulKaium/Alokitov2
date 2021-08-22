<?php

Route::group(['middleware' => ['web', 'auth'], 'namespace' => 'Modules\Admin\Http\Controllers'], function()
{
    Route::get('superadmin', 'AdminController@adminDashboard')->middleware('permission:view-super-admin-dashboard');
    Route::get('admin/institute/login/campus/{id}/', 'AdminController@campusLogin')->middleware('permission:view-super-admin-dashboard');
    Route::get('admin/institute/create', 'AdminController@createInstitute')->middleware('permission:view-super-admin-dashboard');
    Route::get('admin/institute/{id}/campus/create', 'AdminController@createCampus')->middleware('permission:view-super-admin-dashboard');
    // HigherAdmin Panel for chart
    Route::get('admin/dashboard/statics', 'HighAdminController@highDashboardStatics');
    Route::get('admin/dashboard/cadet/register', 'HighAdminController@getHighDashboardCadetRegister');
    Route::get('admin/dashboard/campusInstitute/{id}', 'HighAdminController@getAjaxInstituteCampus');
    Route::get('admin/dashboard/academicYear/{id}', 'HighAdminController@getAjaxAcademicYear');
    Route::get('admin/dashboard/academicDivision/{id}', 'HighAdminController@getAjaxAcademicDivision');
    Route::get('admin/dashboard/academicBatch/{id}', 'HighAdminController@getAjaxAcademicBatch');
    Route::get('admin/dashboard/academicSection/{id}', 'HighAdminController@getAjaxAcademicSection');
    Route::get('admin/dashboard/academicLevel/{id}', 'HighAdminController@getAjaxAcademicLevel');
    Route::post('admin/dashboard/searchcadetData/', 'HighAdminController@searchcadetData');
    Route::get('admin/dashboard/type/category/{id}', 'HighAdminController@getAjaxTypeCategory');
    Route::get('admin/dashboard/category/activity/{id}', 'HighAdminController@getAjaxCategoryActivity');


    // manage uno user
    Route::get('admin/manage/users/uno','UNOController@index');
    Route::get('admin/manage/users/uno/create','UNOController@create');
    Route::get('admin/manage/users/uno/edit/{unoId}','UNOController@edit');
    Route::post('admin/manage/users/uno/store','UNOController@store');
    Route::get('admin/manage/users/uno/institute/list/{id}','UNOController@show');
    Route::get('admin/manage/users/uno/institute/assign/{id}','UNOController@createInstituteAssignment');
    Route::post('admin/manage/users/uno/institute/assign/store','UNOController@storeInstituteAssignment');
    Route::get('admin/manage/users/uno/delete/{id}','UNOController@destroy');

    // uno user activity
    Route::get('admin/dashboard/uno', 'UNOController@unoDashboard')->middleware('permission:view-uno-dashboard');
    Route::get('admin/uno/institute/login/campus/{id}/', 'UNOController@campusLogin')->middleware('permission:view-uno-dashboard');
    Route::get('admin/dashboard/uno/institute', 'UNOController@campusDashboard')->middleware('permission:view-uno-dashboard');
    Route::get('admin/uno/find/institute/', 'UNOController@findInstituteList')->middleware('permission:view-uno-dashboard');

    // get institute today's attendance list
    Route::get('admin/uno/institute/summary','UNOController@getInstituteTodayAttendanceList');


    // get student previous days attendance list
    Route::post('admin/uno/find/student-previous-days-attendance-list', 'UNOController@getStudentPreviousAttendanceList')->middleware('permission:view-uno-dashboard');


    // manage institute bill
    Route::get('admin/bills/{slug}','BillController@index');


    // institute bills
    Route::get('admin/bill/create','BillController@createBill');
    Route::post('admin/bill/store','BillController@storeBill');

    // bill management
    Route::get('admin/billing/info/create','BillController@createBillInfo');
    Route::post('admin/billing/info/store', 'BillController@storeBillingInfo');
    Route::get('admin/billing/info/{id}/edit', 'BillController@editBillingInfo');
    Route::get('admin/billing/info/{id}/delete', 'BillController@destroyBillingInfo');

    //Subscription Management
    Route::post('admin/bills/subscription-management', 'SubscriptionManagementController@processSubscriptionManagement');


    Route::post('high-admin/academic/barchart','HighAdminGraphController@AcadimicChart');
    Route::post('admin/dashboard/barchart','HighAdminGraphController@AdminDashboardChart');

});








