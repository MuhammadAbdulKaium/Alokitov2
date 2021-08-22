<?php

namespace Modules\Admin\Http\Controllers;


use App\User;
use App\Models\Role;
use App\RoleUser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;
use App\Http\Controllers\Helpers\SessionHelper;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Setting\Entities\Institute;
use Modules\Setting\Entities\State;
use Modules\Setting\Entities\InstituteAddress;
use App\UserInfo;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Modules\Academics\Http\Controllers\AttendanceUploadController;
use Modules\Academics\Entities\AttendanceUpload;
use Modules\Employee\Http\Controllers\NationalHolidayController;
use Modules\Employee\Http\Controllers\WeekOffDayController;
use Modules\Student\Http\Controllers\reports\StudentAttendanceReportController;

class UNOController extends Controller
{

    private  $user;
    private  $role;
    private  $roleUser;
    private  $sessionHelper;
    private  $academicHelper;
    private  $validator;
    private  $state;
    private  $instituteAddress;
    private  $attendanceUploadController;
    private  $attendanceUpload;
    private  $holidayController;
    private  $weekOffDayController;
    private  $attendanceReportController;
    private  $userInfo;


    public function __construct(User $user, Role $role, RoleUser $roleUser, SessionHelper $sessionHelper, AcademicHelper $academicHelper, Validator $validator, State $state, InstituteAddress $instituteAddress, AttendanceUploadController $attendanceUploadController, AttendanceUpload $attendanceUpload, NationalHolidayController $holidayController, WeekOffDayController $weekOffDayController, StudentAttendanceReportController $attendanceReportController, UserInfo $userInfo)
    {
        $this->middleware('auth');
        $this->user = $user;
        $this->role = $role;
        $this->roleUser = $roleUser;
        $this->sessionHelper  = $sessionHelper;
        $this->academicHelper = $academicHelper;
        $this->validator = $validator;
        $this->state = $state;
        $this->instituteAddress = $instituteAddress;
        $this->attendanceUploadController = $attendanceUploadController;
        $this->attendanceUpload = $attendanceUpload;
        $this->holidayController = $holidayController;
        $this->weekOffDayController = $weekOffDayController;
        $this->attendanceReportController = $attendanceReportController;
        $this->userInfo = $userInfo;
    }

    // uno dashboard
    public function unoDashboard()
    {
        // user profile
        $userProfile = Auth::user();
        // checking role
        if($userProfile->hasRole('uno')){
            // find uno institute list
            $instituteList = $this->userInfo->where(['user_id'=>$userProfile->id])->orderBy('institute_id', 'ASC')->get();
            // return view with variable
            return view('admin::layouts.dashboard-uno', compact('userProfile','instituteList'));
        }else{
            return redirect('/');
        }
    }

    // institute campus dashboard for uno
    public function campusDashboard()
    {
        // checking role
        if(Auth::user()->hasRole('uno')){
            // attendanceInfo
            $attendanceInfo = $this->attendanceUploadController->dailyAttendanceReport();
            // $allAcademicsLevel
            $allAcademicsLevel = $this->academicHelper->getAllAcademicLevel();
            // return view
            return view('admin::pages.manage-uno.uno-institute-dashboard', compact('attendanceInfo', 'allAcademicsLevel'));
        }else{
            return redirect('/');
        }
    }

    // uno campus login
    public function campusLogin($campusId)
    {
        // user profile
        $userProfile = Auth::user();
        // checking user role
        if($userProfile->hasRole('uno')){

            // checking uno institute assignment
            if($userProfile->hasRole('uno')){
                // institute array list
                $unoInstituteArrayList = array();
                // uno institute list
                $instituteList = Auth::user()->userInfo()->distinct()->get(['campus_id']);
                // checking
                if($instituteList->count()>0){
                    // uno institute looping
                    foreach ($instituteList as $unoInstitute){
                        $unoInstituteArrayList[$unoInstitute->campus_id] = $userProfile->id;
                    }
                }
                // checking uno institute assignment
                if(array_key_exists($campusId, $unoInstituteArrayList)==false){
                    // session comments will be here
                    Session::flash('warning', 'Attempt to login Unauthorized Institute (Campus)');
                    // return
                    return redirect()->back();
                }
            }

            // find campus profile
            if($campusProfile = $this->academicHelper->findCampus($campusId)){
                // find institute profile
                $instituteProfile = $campusProfile->institute();

                // checking session
                if (isset($_POST['institute']) || isset($_POST['campus'])){
                    // unset session value
                    unset($_SESSION['institute']);
                    unset($_SESSION['campus']);
                    unset($_SESSION['academic_year']);
                    unset($_SESSION['grading_scale']);
                }else{
                    // find academic year details
                    if($academicYearProfile = $this->academicHelper->findInstituteAcademicYear($instituteProfile->id, $campusProfile->id)){
                        // set session details
                        $this->sessionHelper->setSession([
                            'academic_year' =>$academicYearProfile->id,
                            'institute'=>$instituteProfile->id,
                            'campus'=>$campusId,
                            'grading_scale'=>1
                        ]);
                    }else{
                        // session comments will be here
                        Session::flash('warning', 'There is no academic Year for this campus');
                        // return
                        return redirect()->back();
                    }
                }
                // return to the admin dashboard
                return redirect('/admin/dashboard/uno/institute');
            }else{
                abort(404);
            }
        }else{
            return redirect('/');
        }
    }

    // get all institute institute today's attendance summary for uno
    public function getInstituteTodayAttendanceList()
    {
        // institute attendance list
        $instituteAttendanceArrayList = array();

        // find uno institute list
        $instituteList = $this->userInfo->where(['user_id'=>Auth::user()->id])->orderBy('institute_id', 'ASC')->get();
        // checking
        if($instituteList AND $instituteList->count()>0){
            // institute list looping for attendance list creation
            foreach ($instituteList as $singleInstitute){
                // institute details
                $campusId = $singleInstitute->campus_id;
                $instituteId = $singleInstitute->institute_id;
                // find academic year using institute and campus
                $academicYear = $this->academicHelper->findInstituteAcademicYear($instituteId, $campusId)->id;
                // institute attendance list
                $instituteAttendanceArrayList[$campusId] = $this->attendanceUploadController->dailyAttendanceCounter($campusId, $instituteId, $academicYear);
            }
        }
        // return view
        return view('admin::pages.manage-uno.uno-institute-attendance', compact('instituteAttendanceArrayList', 'instituteList'));
    }


    // get student previous attendance list
    public function getStudentPreviousAttendanceList(Request $request)
    {
        // input details
        $level = $request->input('academic_level', null);
        $batch = $request->input('batch', null);
        $section = $request->input('section', null);
        $startDate = $request->input('from_date');
        $endDate = $request->input('to_date');

        $fromYear = date('Y',strtotime($startDate));
        $fromMonth = date('m',strtotime($startDate));
        $fromDate = date('d',strtotime($startDate));
        // to_date details
        $toYear = date('Y',strtotime($endDate));
        $toMonth = date('m',strtotime($endDate));
        $toDate = date('d',strtotime($endDate));

        // institute details
        $campusId = $this->academicHelper->getCampus();
        $instituteId = $this->academicHelper->getInstitute();
        $academicYearId = $this->academicHelper->getAcademicYear();
        // batch section student list
        $studentList = $this->attendanceUploadController->stdList($level, $batch, $section);
        // find attendance list
        $allAttendanceList = $this->attendanceUploadController->attendanceList($startDate, $endDate, $level, $batch, $section, $academicYearId, $campusId, $instituteId);
        $stdDeptId = $this->attendanceReportController->findStdDepartment($level, $batch, $academicYearId, $campusId, $instituteId);
        // academic holiday list
        $academicHolidayList = $this->holidayController->holidayList($academicYearId, $campusId, $instituteId);
        // academic WeekOff Day list
        $academicWeeKOffDayList = (object)$this->weekOffDayController->weekOffDayList($stdDeptId, $academicYearId, $campusId, $instituteId);
        // attendance array list
        $attendanceArrayList = array();

        // $studentList list checking
        if (!empty($studentList) AND $studentList->count() > 0) {

            // student counter
            $stdCounter = 0;
            // student gender counter
            $total = 0;
            $maleTotal = 0;
            $femaleTotal = 0;

            // for same year and same/different month(s)
            if($fromYear==$toYear AND $fromMonth<=$toMonth){

                // month looping
                for($month=$fromMonth; $month<=$toMonth; $month++){
                    // month date range
                    $monthFirstDate = date('01', strtotime($fromYear.'-'.$month.'-01'));
                    $monthLastDate = date('t', strtotime($fromYear.'-'.$month.'-01'));

                    // date range reset
                    if($fromMonth==$month){$monthFirstDate = $fromDate;}
                    if($toMonth==$month){$monthLastDate = $toDate;}

                    for($day=$monthFirstDate; $day<=$monthLastDate; $day++){
                        // date formatting
                        $toDayDate = date('Y-m-d', strtotime($fromYear."-".$month."-".$day));

                        // male attendance counting
                        $malePresent = 0;
                        $maleAbsent = 0;
                        // female attendance counting
                        $femalePresent = 0;
                        $femaleAbsent = 0;

                        // now student list looping
                        foreach ($studentList as $student) {
                            // std id
                            $stdId = $student->std_id;
                            // std gender
                            $stdGender = $student->gender;

                            // checking student gender
                            if ($stdGender == 'Male') {
                                // male total count
                                if ($stdCounter == 0) $maleTotal += 1;
                                // checking today's date attendance list
                                if (array_key_exists($toDayDate, $allAttendanceList) == true) {
                                    // today's date attendance list
                                    $attendanceList = $allAttendanceList[$toDayDate];
                                    // checking student attendance
                                    if (array_key_exists($stdId, $attendanceList) == true) {
                                        $malePresent += 1;
                                    } else {
                                        $maleAbsent += 1;
                                    }
                                } else {
                                    $maleAbsent += 1;
                                }

                            } else {
                                // female total count
                                if ($stdCounter == 0) $femaleTotal += 1;
                                // checking today's date attendance list
                                if (array_key_exists($toDayDate, $allAttendanceList) == true) {
                                    // today's date attendance list
                                    $attendanceList = $allAttendanceList[$toDayDate];
                                    // checking student attendance
                                    if (array_key_exists($stdId, $attendanceList) == true) {
                                        $femalePresent += 1;
                                    } else {
                                        $femaleAbsent += 1;
                                    }
                                } else {
                                    $femaleAbsent += 1;
                                }
                            }
                            // total student count
                            if ($stdCounter == 0) $total += 1;
                        }
                        // std counter
                        $stdCounter += 1;

                        // calculate today's attendance list
                        $todayTotalPresent = $malePresent + $femalePresent;
                        $todayTotalAbsent = $maleAbsent + $femaleAbsent;
                        // precision count
                        $precision = 2;
                        // attendance array list
                        $attendanceArrayList[$toDayDate] = [
                            'total_present' => $todayTotalPresent,
                            'total_absent' => $todayTotalAbsent,
                            'total_present_percent' => $todayTotalPresent > 0 ? (substr(number_format(((($todayTotalPresent / $total) * 100)), $precision + 1, '.', ''), 0, -1)) : '0.00',
                            'total_absent_percent' => $todayTotalAbsent > 0 ? (substr(number_format(((($todayTotalAbsent / $total) * 100)), $precision + 1, '.', ''), 0, -1)) : '0.00',
                            'male_present' => $malePresent,
                            'male_absent' => $maleAbsent,
                            'male_present_percent' => $malePresent > 0 ? (substr(number_format(((($malePresent / $maleTotal) * 100)), $precision + 1, '.', ''), 0, -1)) : '0.00',
                            'male_absent_percent' => $maleAbsent > 0 ? (substr(number_format(((($maleAbsent / $maleTotal) * 100)), $precision + 1, '.', ''), 0, -1)) : '0.00',
                            'female_present' => $femalePresent,
                            'female_absent' => $femaleAbsent,
                            'female_present_percent' => $femalePresent > 0 ? (substr(number_format(((($femalePresent / $femaleTotal) * 100)), $precision + 1, '.', ''), 0, -1)) : '0.00',
                            'female_absent_percent' => $femaleAbsent > 0 ? (substr(number_format(((($femaleAbsent / $femaleTotal) * 100)), $precision + 1, '.', ''), 0, -1)) : '0.00',
                        ];
                    }
                }

                // std list maker
                $myStdList = (object)['total' => $total, 'male' => $maleTotal, 'female' => $femaleTotal];
                // view rendering
                $html = view('admin::pages.manage-uno.modals.uno-institute-std-attendance', compact('myStdList', 'attendanceArrayList', 'academicHolidayList', 'academicWeeKOffDayList', 'startDate', 'endDate'))->render();
                // return
                return ['status' => 'success', 'msg' => 'Student Attendance list', 'content' => $html];
            }else{
                // return
                return ['status'=>'failed', 'msg'=>'Invalid Date format'];
            }
        } else {
            // return
            return ['status' => 'failed', 'msg' => 'No student found for this class section'];
        }
    }



    ///////////////////////////////  UNO Management Details ////////////////////////////
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        // find UNO role profile
        $unoRoleProfile = $this->role->where(['name'=>'uno'])->first();
        // checking
        if($unoRoleProfile){
            // find uno users list
            $unoUsersList = $this->roleUser->where(['role_id'=>$unoRoleProfile->id])->distinct()->get(['user_id']);
            // return view with variable
            return view('admin::pages.manage-uno.manage-uno', compact('unoUsersList'));
        }else{
            //session data
            Session::flash('warning', 'No HighAdmin Role found');
            // redirect page action
            return redirect()->back();
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        // $unoProfile
        $userProfile = null;
        // return view with variable
        return view('admin::pages.manage-uno.modals.uno', compact('userProfile'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // request details
        $name = $request->input('name');
        $email = $request->input('email');
        $unoId = $request->input('uno_id');
        // validating all requested input data
        $validator = Validator::make($request->all(), ['name'=>'required', 'email'=>'required']);
        // validation checker
        if ($validator->passes()) {
            // Start transaction!
            DB::beginTransaction();
            // start to try crating admin user
            try {
                // checking unoId
                if($unoId>0){
                    $userProfile = $this->user->where(['id'=>$unoId])->first();
                    // checking user profile
                    if(!$userProfile) abort(404);
                    // checking email
                    if($userProfile->email != $email){
                        // validating all requested input data
                        $validator =  Validator::make($request->all(), ['email'=>'required|email|max:100|unique:users']);
                        // validation checker
                        if (!$validator->passes()) {
                            // Rollback and then redirect back to form with errors  Redirecting with error message
                            DB::rollback();
                            //session data
                            Session::flash('warning', 'Email already exists');
                            // receiving page action
                            return redirect()->back();
                        }
                    }
                }else{
                    $userProfile = new $this->user();
                    // validating all requested input data
                    $validator =  Validator::make($request->all(), ['email'=>'required|email|max:100|unique:users']);
                    // validation checker
                    if (!$validator->passes()) {
                        // Rollback and then redirect back to form with errors  Redirecting with error message
                        DB::rollback();
                        //session data
                        Session::flash('warning', 'Email already exists');
                        // receiving page action
                        return redirect()->back();
                    }
                }
                // profile details
                $userProfile->name = $name;
                $userProfile->email = strtolower($email);
                if($unoId==0) $userProfile->password = bcrypt(123456);
                // save user profile
                if($userProfile->save()){
                    // checking unoId for assignment
                    if($unoId==0){
                        // find uno role profile
                        $unoRoleProfile = $this->role->where(['name'=>'uno'])->first();
                        // checking
                        if($unoRoleProfile){
                            // assigning student role to this user
                            $userProfile->attachRole($unoRoleProfile);
                        }else{
                            // Rollback and then redirect back to form with errors
                            DB::rollback();
                            //session data
                            Session::flash('warning', 'Role (HighAdmin) not found');
                            // receiving page action
                            return redirect()->back();
                        }
                    }

                    // If we reach here, then data is valid and working.
                    // Commit the queries!
                    DB::commit();
                    //session data
                    Session::flash('success', 'User (HighAdmin) Profile Submitted');
                    // receiving page action
                    return redirect()->back();
                }else{
                    // Rollback and then redirect back to form with errors
                    // Redirecting with error message
                    DB::rollback();
                    //session data
                    Session::flash('warning', 'Unable to Submit User (HighAdmin) Profile');
                    // receiving page action
                    return redirect()->back();
                }
            } catch (ValidationException $e) {
                // Rollback and then redirect back to form with errors
                // Redirecting with error message
                DB::rollback();
                return redirect()->back()->withErrors($e->getErrors())->withInput();
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }
        }else{
            Session::flash('warning', 'Invalid Information');
            // receiving page action
            return redirect()->back();
        }
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($unoId)
    {
        // find uno profile
        $userProfile = $this->user->find($unoId);
        // return view with variable
        return view('admin::pages.manage-uno.modals.uno-institute-list', compact('userProfile'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($unoId)
    {
        // find uno profile
        $userProfile = $this->user->find($unoId);
        // return view with variable
        return view('admin::pages.manage-uno.modals.uno', compact('userProfile'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function createInstituteAssignment($unoId)
    {
        // find uno profile
        $userProfile = $this->user->find($unoId);
        // return view with variable
        return view('admin::pages.manage-uno.modals.uno-institute-assign', compact('userProfile'));
    }

    public function findInstituteList(Request $request)
    {
        // input details
        $cityId = $request->input('city_id');
        $stateId = $request->input('state_id');
        $adminId = $request->input('admin_id');
        // admin profile
        $userProfile = $this->user->find($adminId);
        // checking
        if($userProfile){
            // institute list
            $instituteList = $this->instituteAddress->where(['city_id'=>$cityId, 'state_id'=>$stateId])->distinct()->get(['institute_id']);
            // view rendering
            $content =  view('admin::pages.manage-uno.modals.uno-dashboard-institute-list', compact('userProfile', 'instituteList', 'cityId', 'stateId'))->render();
            // return view with variable
            return ['status'=>'success', 'content'=>$content];
        }else{
            // return view with variable
            return ['status'=>'failed', 'msg'=>'User profile not found'];
        }

    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function storeInstituteAssignment(Request $request)
    {
        // statement
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }

}
