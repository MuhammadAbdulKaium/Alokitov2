<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Auth::routes();

Route::get('/hostel/create', 'HostelController@create')->name('create_hostel');
Route::get('/hostel-facilities/create', 'HostelController@create_facilities')->name('create_facilities');
Route::get('/hostel-assign', 'HostelController@assign_hostel')->name('assign_hostel');


Route::get('/', 'HomeController@index');
Route::get('/admin', 'HomeController@schoolAdminDashboard')->middleware('permission:view-admin-dashboard');
Route::get('/dashboard/hr', 'HomeController@hrDashboard')->middleware('permission:view-hr-dashboard');
Route::get('/dashboard/parent', 'HomeController@parentDashboard')->middleware('permission:view-parent-dashboard');
Route::get('/dashboard/teacher', 'HomeController@teacherDashboard')->middleware('permission:view-teacher-dashboard');
Route::get('/dashboard/student', 'HomeController@studentDashboard')->middleware('permission:view-student-dashboard');
Route::get('/dashboard/fees', 'HomeController@feesDashboard');
Route::get('/home/', 'HomeController@newDashboard');
Route::get('/new-dashboard-acc/', 'HomeController@newDashboardAcc');

// Sageen school loging page
Route::get('/shaheen_login', function (){
    return view('auth.shaheen_login');
});

Route::get('/template-one',function (){
    return view('dashboard.dashboard-2');
});

Route::get('/json',function (){
    return json_decode(file_get_contents('http://localhost/test/test.json'), true);
});

Route::get('/admin/dashboard/demo', 'DemoHomeController@dashboard')->middleware('permission:view-admin-dashboard');

Route::get('/forgot-password','ForgotPasswordController@index');
Route::post('/forgot-password/check/','ForgotPasswordController@forgotPasswordByEmail');
Route::get('/forgot-password/users','ForgotPasswordController@forgotPasswordUserList');
Route::post('/reset/user/password/','ForgotPasswordController@resetUserPassword');
Route::get('/forgot-password/users/delete/{id}','ForgotPasswordController@forgotPasswordUserDelete');

Route::get('/pdf',function (){

//    return view('reports::pages.report.id-card-land-test');
    $pdf = PDF::loadView('reports::pages.report.id-card-land-test');
    $pdf->loadView('reports::pages.report.id-card-land-test')->setPaper('a4', 'landscape');

    return $pdf->stream('class_section_student_id_card.pdf');
});


// all user change password

Route::get('/mail','ForgotPasswordController@passwordResetMailTest');
Route::get('/changepassword','HomeController@showChangePasswordForm');
Route::post('/changePassword','HomeController@changePassword');

// Route Dashbarod Modules
//
Route::get('/finance/', 'HomeController@financeDashboard');
Route::get('/hr-payroll/', 'HomeController@hrPayrollDashboard');
// hr payroll sub menu
    Route::get('/hr-payroll/employee-management', 'HomeController@hrPayrollEmployeeManage');
Route::get('/hr-payroll/payroll', 'HomeController@hrPayrollPayroll');
Route::get('/hr-payroll/emp-configuration', 'HomeController@hrPayrollConfiguraiton');
Route::get('/hr-payroll/leave', 'HomeController@hrPayrollLeaveManagement');
Route::get('/hr-payroll/attendance', 'HomeController@hrPayrollAttendance');

//Route::fallback(function () {
//    echo "<div style='font-family:Arial;'><h1 style='font-size: 3rem;'>Access Unavailable!</h1><h3>To avail the services please contact us</h3><br /><strong>Venus IT Ltd.<br />Cell: +8801708872244</strong><br />Dhaka, Bangladesh<br />";
//    echo "For accessing check the <a href='/admin'>link</a>";
//});
