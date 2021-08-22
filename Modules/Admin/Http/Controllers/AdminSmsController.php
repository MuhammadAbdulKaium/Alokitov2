<?php

namespace Modules\Admin\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Setting\Entities\Country;
use App\Http\Controllers\Helpers\SessionHelper;
use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Support\Facades\Auth;
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
use Illuminate\Support\Facades\DB;
use Session;

class AdminSmsController extends Controller
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
    private  $sessionHelper;
    private  $country;


    public function __construct(AcademicHelper $academicHelper,SmsCredit $smsCredit,SmsLog $smsLog, SessionHelper $sessionHelper ,AcademicsLevel $academicsLevel, ClassSubject $classSubject, StudentInformation $studentInformation, StudentEnrollment $studentEnrollment, StudentAttendance $studentAttendance, StudentAttendanceDetails $studentAttendanceDetails, AttendanceManageView $attendanceManageView, EmployeeInformation $employeeInformation, ReportsController $reportsController,Language $language, Menu $menu, Module $module, Institute $institute, InstituteModule $instituteModule, Country $country)
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
        $this->country                = $country;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        // institute profile
        $instituteList = $this->academicHelper->getInstituteList();
        return view('admin::pages.manage-sms.sms-pending-list', compact('instituteList'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('admin::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('admin::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('admin::edit');
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
