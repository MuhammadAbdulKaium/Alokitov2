<?php

namespace Modules\Admin\Http\Controllers;
use App\Models\Role;
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



class AdminController extends Controller
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
        return view('admin::index');
    }

    public function adminDashboard()    {
        if(Auth::user()->hasRole('super-admin')){
            // student count
            $stdCount  = $this->studentInformation->all()->count();
            // employee count
            $employeeCount = $this->employeeInformation->all()->count();
            // institute profile
            $instituteList = $this->academicHelper->getInstituteList();
            //sms credit
            $smsCredit = ($this->smsCredit->where('status','1')->sum('sms_amount'))-($this->smsLog->count());
            // return view with variable
            return view('admin::layouts.dashboard', compact('stdCount','employeeCount','instituteList','smsCredit'));
        }else{
            return redirect('/');
        }
    }


    // campus login as institute admin for super admin role
    public function campusLogin($campusId)
    {
        // user profile
        $userProfile = Auth::user();
        // checking user role
        if($userProfile->hasRole('super-admin')){

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
                return redirect('/admin');
            }else{
                abort(404);
            }
        }else{
            return redirect('/');
        }
    }

    public function createInstitute()
    {
        // country list
        $countryList = $this->country->orderBy('name', 'ASC')->get();
        // return view with view
        return view('admin::pages.modals.add-institute', compact('countryList'));
    }

    // create campus
    public function createCampus($instituteId)
    {
        // country list
        $countryList = $this->country->orderBy('name', 'ASC')->get();
        // institute profile
        $instituteProfile = $this->institute->find($instituteId);
        // return
        return view('admin::pages.modals.add-campus', compact('instituteProfile', 'countryList'));
    }


}
