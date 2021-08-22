<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Helpers\SessionHelper;
use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Modules\Academics\Entities\AcademicsLevel;
use Modules\Academics\Entities\AttendanceManageView;
use Modules\Academics\Entities\ClassSubject;
use Modules\Academics\Entities\StudentAttendance;
use Modules\Academics\Entities\StudentAttendanceDetails;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\Student\Entities\StudentEnrollment;
use Modules\Student\Entities\StudentInformation;
use Modules\Reports\Http\Controllers\ReportsController;
use Modules\Setting\Entities\Language;

use Modules\Setting\Entities\Menu;
use Modules\Setting\Entities\Module;
use Modules\Setting\Entities\Institute;
use Modules\Setting\Entities\InstituteModule;

use Modules\Communication\Entities\SmsCredit;
use Modules\Communication\Entities\SmsLog;

class DemoHomeController extends Controller
{

    protected $user;

    private $academicHelper;
    private $academicsLevel;
    private $classSubject;
    private $studentInformation;
    private $studentAttendance;
    private $studentAttendanceDetails;
    private $studentEnrollment;
    private $attendanceManageView;
    private $employeeInformation;
    private $reportsController;
    private $language;

    private  $menu;
    private  $module;
    private  $institute;
    private  $instituteModule;
    private  $smsCredit;
    private  $smsLog;

    public function __construct(AcademicHelper $academicHelper,SmsCredit $smsCredit,SmsLog $smsLog, SessionHelper $sessionHelper ,AcademicsLevel $academicsLevel, ClassSubject $classSubject, StudentInformation $studentInformation, StudentEnrollment $studentEnrollment, StudentAttendance $studentAttendance, StudentAttendanceDetails $studentAttendanceDetails, AttendanceManageView $attendanceManageView, EmployeeInformation $employeeInformation, ReportsController $reportsController,Language $language, Menu $menu, Module $module, Institute $institute, InstituteModule $instituteModule)
    {

        $this->middleware('auth');
        $this->user = Auth::user();
        $this->academicHelper           = $academicHelper;
        $this->sessionHelper           = $sessionHelper;
        $this->academicsLevel           = $academicsLevel;
        $this->classSubject             = $classSubject;
        $this->studentEnrollment        = $studentEnrollment;
        $this->studentInformation       = $studentInformation;
        $this->studentAttendance        = $studentAttendance;
        $this->studentAttendanceDetails = $studentAttendanceDetails;
        $this->attendanceManageView     = $attendanceManageView;
        $this->employeeInformation      = $employeeInformation;
        $this->reportsController      = $reportsController;
        $this->language                 = $language;

        $this->menu = $menu;
        $this->module = $module;
        $this->institute = $institute;
        $this->instituteModule = $instituteModule;
        $this->smsCredit             = $smsCredit;
        $this->smsLog                = $smsLog;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->hasRole('admin')){
            // redirect to admin dashboard
            return redirect('/admin/dashboard');

        }elseif (Auth::user()->hasRole('student')){
            // redirect to student dashboard
            return redirect('/student/dashboard');

        }elseif (Auth::user()->hasRole('teacher')){
            // redirect to student dashboard
            return redirect('/teacher/dashboard');

        }elseif (Auth::user()->hasRole('parent')){
            // redirect to student dashboard
            return redirect('/parent/dashboard');

        }elseif (Auth::user()->hasRole('hrms')){
            // redirect to admin dashboard
            return redirect('/hr/dashboard');
        }
    }

    public function dashboard()
    {

        if(Auth::user()->hasRole('admin')){

            $userInfo = Auth::user()->userInfo()->first();
            $campusId = $userInfo->campus_id;
            if(!empty(session()->get('campus'))){
                $campusId = session()->get('campus');
            }
            // set session info
            $sessionHelper = $this->sessionHelper->setSession([
                'academic_year' =>$this->academicHelper->findInstituteAcademicYear($userInfo->institute_id, $userInfo->campus_id)->id,
                'institute'=>$userInfo->institute_id,
                'campus'=>$campusId,
                'grading_scale'=>1
            ]);


            // student counter
            $totalStudent  = $this->studentInformation->gender('all');
            $totalEmployee = $this->employeeInformation->gender('all');
            // date
            $toDayDate        = Carbon::today()->toDateString();
            $upComingToDate   = Carbon::tomorrow()->toDateString();
            $upComingFromDate = Carbon::tomorrow()->addDays(7)->toDateString();
            //student bithdate
            $toDayStudentBirthday    = $this->studentInformation->where('dob', $toDayDate)->get();
            $upComingStudentBirthday = $this->studentInformation->whereBetween('dob', array($upComingToDate, $upComingFromDate))->get();
            // employee bithdate
            $toDayEmployeetBirthday   = $this->employeeInformation->where('dob', $toDayDate)->get();
            $upComingEmployeeBirthday = $this->employeeInformation->whereBetween('dob', array($upComingToDate, $upComingFromDate))->get();
            // student today's attendance info
            $attendanceInfo = null;
            $attendanceInfo = $this->reportsController->todayAttendanceInfo();

            // institute profile
            $instituteProfile = $this->academicHelper->getInstituteProfile();
            // institute Modules
            $instituteModules = $this->academicHelper->getInstituteModules();
            $allAcademicLevel = $this->academicHelper->getAllAcademicLevel();


            // sms _creadit COunt
            $smsCreditCount=$this->smsCredit->where('status','1')->sum('sms_amount');
            // sms _sms Log Count
            $smsLogCount= $this->smsLog->count();

            //sms_creadit - sms_log
            $totalSmsCreadit=$smsCreditCount-$smsLogCount;


            // return view with variable
//            return view('academic_modules.dashboard', compact('totalStudent', 'totalEmployee', 'toDayStudentBirthday', 'upComingStudentBirthday', 'toDayEmployeetBirthday', 'upComingEmployeeBirthday', 'attendanceInfo', 'instituteModules', 'allAcademicLevel','totalSmsCreadit'));
            return view('dashboard.dashboard-2', compact('totalStudent', 'totalEmployee', 'toDayStudentBirthday', 'upComingStudentBirthday', 'toDayEmployeetBirthday', 'upComingEmployeeBirthday', 'attendanceInfo', 'instituteModules', 'allAcademicLevel','totalSmsCreadit'));


        }else{
            return redirect('/');
        }
    }

    public function teacherDashboard()
    {
        if(Auth::user()->hasRole('teacher')){
            // user info
            $userInfo = Auth::user()->userInfo()->first();
            // set session info
            $sessionHelper = $this->sessionHelper->setSession([
                'academic_year' =>$this->academicHelper->findInstituteAcademicYear($userInfo->institute_id, $userInfo->campus_id)->id,
                'institute'=>$userInfo->institute_id,
                'campus'=>$userInfo->campus_id,
                'grading_scale'=>1
            ]);
            // return view with variable
            return view('academic_modules.dashboard-teacher');
        }else{
            return redirect('/');
        }
    }

    public function studentDashboard()
    {
        if(Auth::user()->hasRole('student')){
            // user info
            $userInfo = Auth::user()->userInfo()->first();
            // set session info
            $sessionHelper = $this->sessionHelper->setSession([
                'academic_year' =>$this->academicHelper->findInstituteAcademicYear($userInfo->institute_id, $userInfo->campus_id)->id,
                'institute'=>$userInfo->institute_id,
                'campus'=>$userInfo->campus_id,
                'grading_scale'=>1
            ]);
            // return view with variable
            return view('academic_modules.dashboard-student');
        }else{
            return redirect('/');
        }
    }


    public function parentDashboard()
    {
        if(Auth::user()->hasRole('parent')){
            $userInfo = Auth::user()->userInfo()->first();
            // set session info
            $sessionHelper = $this->sessionHelper->setSession([
                'academic_year' =>$this->academicHelper->findInstituteAcademicYear($userInfo->institute_id, $userInfo->campus_id)->id,
                'institute'=>$userInfo->institute_id,
                'campus'=>$userInfo->campus_id,
                'grading_scale'=>1
            ]);

            // parent student list
            $studentList = Auth::user()->parent()->students();
            // return view with variable
            return view('academic_modules.dashboard-parent', compact('studentList'));
        }else{
            return redirect('/');
        }
    }


    public function hrDashboard()
    {
        if(Auth::user()->hasRole('hrms')){
            $userInfo = Auth::user()->userInfo()->first();
            // set session info
            $sessionHelper = $this->sessionHelper->setSession([
                'academic_year' =>$this->academicHelper->findInstituteAcademicYear($userInfo->institute_id, $userInfo->campus_id)->id,
                'institute'=>$userInfo->institute_id,
                'campus'=>$userInfo->campus_id,
                'grading_scale'=>1
            ]);
            // return view with variable
            return view('academic_modules.dashboard-hr');
        }else{
            return redirect('/');
        }
    }
}
