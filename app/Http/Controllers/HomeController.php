<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Helpers\SessionHelper;
use App\Http\Controllers\Helpers\AcademicHelper;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Modules\Academics\Entities\AcademicsLevel;
use Modules\Academics\Entities\AcademicsYear;
use Modules\Academics\Entities\AttendanceManageView;
use Modules\Academics\Entities\ClassSubject;
use Modules\Academics\Entities\StudentAttendance;
use Modules\Academics\Entities\StudentAttendanceDetails;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\Setting\Entities\CadetPerformanceType;
use Modules\Student\Entities\StudentEnrollment;
use Modules\Student\Entities\StudentInformation;
use Modules\Reports\Http\Controllers\ReportsController;
use Modules\Setting\Entities\Language;
use Modules\Setting\Entities\Menu;
use Modules\Setting\Entities\Module;
use Modules\Setting\Entities\Institute;
use Modules\Setting\Entities\InstituteModule;
use Modules\Communication\Entities\Event;

use Modules\Communication\Entities\SmsCredit;
use Modules\Communication\Entities\SmsLog;
use Modules\Academics\Http\Controllers\AttendanceUploadController;
use Modules\Communication\Http\Controllers\EventController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use DB;
use App\OnlineClassTopic;
use Modules\Admin\Entities\BillingInfo;

class HomeController extends Controller
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
    private $academicEvent;

    private  $menu;
    private  $module;
    private  $institute;
    private  $instituteModule;
    private  $smsCredit;
    private  $smsLog;
    private  $sessionHelper;
    private  $attendanceUploadController;
    private  $eventController;
    private  $OnlineClassTopic;
    private $billingInfo;


    public function __construct(AcademicHelper $academicHelper,SmsCredit $smsCredit,SmsLog $smsLog, SessionHelper $sessionHelper ,AcademicsLevel $academicsLevel, ClassSubject $classSubject, StudentInformation $studentInformation, StudentEnrollment $studentEnrollment, StudentAttendance $studentAttendance, StudentAttendanceDetails $studentAttendanceDetails, AttendanceManageView $attendanceManageView, EmployeeInformation $employeeInformation, ReportsController $reportsController,Language $language, Menu $menu, Module $module, Institute $institute, InstituteModule $instituteModule, Event $academicEvent, AttendanceUploadController $attendanceUploadController, EventController $eventController,OnlineClassTopic $OnlineClassTopic, BillingInfo $billingInfo)
    {

        $this->middleware('auth');
        $this->user = Auth::user();
        $this->academicHelper                   = $academicHelper;
        $this->sessionHelper                    = $sessionHelper;
        $this->academicsLevel                   = $academicsLevel;
        $this->classSubject                     = $classSubject;
        $this->studentEnrollment                = $studentEnrollment;
        $this->studentInformation               = $studentInformation;
        $this->studentAttendance                = $studentAttendance;
        $this->studentAttendanceDetails         = $studentAttendanceDetails;
        $this->attendanceManageView             = $attendanceManageView;
        $this->employeeInformation              = $employeeInformation;
        $this->reportsController                = $reportsController;
        $this->language                         = $language;
        $this->academicEvent                    = $academicEvent;

        $this->menu                             = $menu;
        $this->module                           = $module;
        $this->institute                        = $institute;
        $this->instituteModule                  = $instituteModule;
        $this->smsCredit                        = $smsCredit;
        $this->smsLog                           = $smsLog;
        $this->attendanceUploadController       = $attendanceUploadController;
        $this->eventController                  = $eventController;
        $this->OnlineClassTopic                 = $OnlineClassTopic;
        $this->billingInfo                      = $billingInfo;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->hasRole('super-admin')) {
            // redirect to admin dashboard
            return redirect('/superadmin');

        }
        elseif(Auth::user()->hasRole('uno')){
            // redirect to uno dashboard
//            return redirect('/admin/dashboard/uno');
            return redirect('setting/uno/institute/pie');

        }
        elseif(Auth::user()->hasRole('admin')){
            // redirect to admin dashboard
            return redirect('/admin');

        }elseif (Auth::user()->hasRole('student')){
            // redirect to student dashboard
            return redirect('/dashboard/student');

        }elseif (Auth::user()->hasRole('teacher')){
            // redirect to student dashboard
            return redirect('/dashboard/teacher');

        }elseif (Auth::user()->hasRole('parent')){
            // redirect to student dashboard
            return redirect('/dashboard/parent');

        }elseif (Auth::user()->hasRole('hrms')){
            // redirect to admin dashboard
            return redirect('/dashboard/hr');

        }
        elseif (Auth::user()->hasRole('accountant')) {
            // redirect to admin dashboard
            return $this->accountDashboard();

        }elseif (Auth::user()->hasRole('guest')){

            // set session info
            $this->sessionHelper->setSession([
                'academic_year' =>2,
                'institute'=>2,
                'campus'=>2,
                'grading_scale'=>1
            ]);
            // redirect to admin dashboard
            return redirect('/fees');


        }else{
            abort(404);
        }
    }

    public function schoolAdminDashboard()
    {
        $type=CadetPerformanceType::whereIn('id',['1'])->get();
        $yearList = AcademicsYear::where('institute_id', $this->academicHelper->getInstitute())
            ->where('campus_id', $this->academicHelper->getCampus())
            ->orderBy('year_name', 'DESC')
            ->get();
        $academicLevel=AcademicsLevel::where('institute_id',$this->academicHelper->getInstitute())
            ->where('campus_id', $this->academicHelper->getCampus())
            ->get();

        if(Auth::user()->hasRole(['super-admin', 'admin', 'uno', 'guest'])){
            // checking userInfo for admin
            if(Auth::user()->hasRole('admin')){
                // user info
                $userInfo = Auth::user()->userInfo()->first();
                // institute id
                $instituteId = $userInfo->institute_id;
                $campusId = $userInfo->campus_id;
                if(!empty(session()->get('campus'))){
                    $campusId = session()->get('campus');
                }
                // set session info
                $this->sessionHelper->setSession([
                    'academic_year' =>$this->academicHelper->findInstituteAcademicYear($instituteId,$campusId)->id,
                    'institute'=>$instituteId,
                    'campus'=>$campusId,
                    'grading_scale'=>1
                ]);

            }else{
                // super admin access only
                $instituteId    = $this->academicHelper->getInstitute();
                $campusId       = $this->academicHelper->getCampus();
            }

            $allEventList = $this->academicEvent->where([
                'status'=>1,
                'campus'=>$campusId,
                'institute'=>$instituteId,
            ])->get();

            // student counter
            $totalStudent  = $this->studentInformation->gender('all');
            $totalEmployee = $this->employeeInformation->gender('all');
            // date
            $toDayDate        = Carbon::today()->toDateString();
            $upComingToDate   = Carbon::tomorrow()->toDateString();
            $upComingFromDate = Carbon::tomorrow()->addDays(7)->toDateString();
            //student bithdate
            $toDayStudentBirthday    = $this->studentInformation->whereMonth('dob',date('m'))->whereDay('dob',date('d'))->where('institute',$instituteId)->where('campus',$campusId)->get();
//            $upComingStudentBirthday = $this->studentInformation->whereBetween('dob', array($upComingToDate, $upComingFromDate))->get();
            $upComingStudentBirthday = $this->studentInformation->whereRaw('DAYOFYEAR(curdate()+1) <= DAYOFYEAR(dob) AND DAYOFYEAR(curdate()) + 7 >=  dayofyear(dob)')
                ->orderByRaw('DAYOFYEAR(dob)')
                ->where('institute',$instituteId)
                ->where('campus',$campusId)
                ->get();


            // employee bithdate
            $toDayEmployeetBirthday   = $this->employeeInformation->whereMonth('dob',date('m'))->whereDay('dob',date('d'))->where('institute_id',$instituteId)->where('campus_id',$campusId)->get();
//            $upComingEmployeeBirthday = $this->employeeInformation->whereBetween('dob', array($upComingToDate, $upComingFromDate))->get();
            $upComingEmployeeBirthday = $this->employeeInformation->whereRaw('DAYOFYEAR(curdate()+1) <= DAYOFYEAR(dob) AND DAYOFYEAR(curdate()) + 7 >=  dayofyear(dob)')
                ->orderByRaw('DAYOFYEAR(dob)')
                ->where('institute_id',$instituteId)
                ->where('campus_id',$campusId)
                ->get();

            // student today's attendance info
            // $attendanceInfo = $this->reportsController->todayAttendanceInfo();
            $attendanceInfo = $this->attendanceUploadController->dailyAttendanceReport();
            //$attendanceInfo = $this->attendanceUploadController->dailyAttendanceReportByDevice();

            // institute profile
            $instituteProfile = $this->academicHelper->getInstituteProfile();
            // institute Modules
            $instituteModules = $this->academicHelper->getInstituteModules();
            $allAcademicLevel = $this->academicHelper->getAllAcademicLevel();


            // sms _creadit COunt
            $smsCreditCount=$this->smsCredit->where('institution_id',$this->academicHelper->getInstitute())->where('campus_id',$this->academicHelper->getCampus())->where('status','1')->sum('sms_amount');
            // sms _sms Log Count
            $smsLogCount= $this->smsLog->where('institution_id',$this->academicHelper->getInstitute())->where('campus_id',$this->academicHelper->getCampus())->count();

            //sms_creadit - sms_log
            $totalSmsCreadit=$smsCreditCount-$smsLogCount;
            // get current month event/holiday/weekOff day list
            $monthScheduleList = $this->eventController->getMonthSchedule(date('Y'), date('m'));

            // return view with variable

            if(Auth::user()->hasRole('admin')){
                $userInfo = Auth::user()->userInfo()->first();
                $instituteId = $userInfo->institute_id;
                $campusId = $userInfo->campus_id;

                $smtObj = $this->billingInfo->where('year', date('Y'))->where('month', date('F'))->where('institute_id', $instituteId)->where('campus_id', $campusId)->first();
//                dd(date('Y'));
                if(isset($smtObj))
                {
                    if($smtObj->count() > 0) {
                        $status = $smtObj->subscriptionManagementTransaction->status;
                        $currDate = date('d F Y', strtotime(now()));
                        $limitDate = date('10 F Y', strtotime($currDate));

                        if($smtObj->subscriptionManagementTransaction->status != "paid") {
                            if ($currDate < $limitDate) {
                                return view('academic_modules.dashboard', compact('totalStudent', 'totalEmployee', 'toDayStudentBirthday', 'upComingStudentBirthday', 'toDayEmployeetBirthday', 'upComingEmployeeBirthday', 'attendanceInfo', 'instituteModules', 'allAcademicLevel','totalSmsCreadit', 'allEventList', 'monthScheduleList', 'type', 'yearList', 'academicLevel', 'instituteId', 'campusId'));
                            } else {
                                return redirect("/access/unavailable");
                            }
                        } else {
                            return view('academic_modules.dashboard', compact('totalStudent', 'totalEmployee', 'toDayStudentBirthday', 'upComingStudentBirthday', 'toDayEmployeetBirthday', 'upComingEmployeeBirthday', 'attendanceInfo', 'instituteModules', 'allAcademicLevel','totalSmsCreadit', 'allEventList', 'monthScheduleList', 'type', 'yearList', 'academicLevel', 'instituteId', 'campusId'));
                        }
                    }
                }
                else
                {
                    return redirect("/access/unavailable");
                }

            } else {
                return view('academic_modules.dashboard', compact('totalStudent', 'totalEmployee', 'toDayStudentBirthday', 'upComingStudentBirthday', 'toDayEmployeetBirthday', 'upComingEmployeeBirthday', 'attendanceInfo', 'instituteModules', 'allAcademicLevel','totalSmsCreadit', 'allEventList', 'monthScheduleList', 'type', 'yearList', 'academicLevel', 'instituteId', 'campusId'));
            }
            
        } else {
            return redirect('/');
        }
    }

    public function teacherDashboard()
    {
        // auth user
        $user = Auth::user();
        // checking employee profile
        if($userProfile = $user->employee()){
            // checking user status
            if($userProfile->status==1){
                // checking user role
                if($user->hasRole('teacher')){
                    // user info
                    $userInfo = $user->userInfo()->first();

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
            }else{
                abort(403);
            }
        }else{
            abort(403);
        }
    }

    public function teacherClassTopic(){

        if(Auth::user()->hasRole('teacher')){
            // user info
            $userInfo = Auth::user()->userInfo()->first();

            // auth user
            $user = Auth::user();
            // checking employee profile
            $userProfile = $user->employee();

            // academic year
            //$academicYear = $this->getAcademicYearId();
            // academics levels
            $allAcademicsLevel = $this->academicHelper->getAllAcademicLevel();



            // set session info
            $sessionHelper = $this->sessionHelper->setSession([
                'academic_year' =>$this->academicHelper->findInstituteAcademicYear($userInfo->institute_id, $userInfo->campus_id)->id,
                'institute'=>$userInfo->institute_id,
                'campus'=>$userInfo->campus_id,
                'grading_scale'=>1
            ]);
            // return view with variable

            
            $institute_id = $userInfo->institute_id;
            $campus_id    = $userInfo->campus_id;
            $teacher_id   = $userProfile->id; //$userInfo;
           

            $topic_list = DB::table('online_class_topics')
            ->when($institute_id, function ($query, $institute_id) {
                return $query->where('institute_id', $institute_id);
            })
            ->when($campus_id, function ($query, $campus_id) {
                return $query->where('campus_id', $campus_id);
            })
            ->when($teacher_id, function ($query, $teacher_id) {
                return $query->where('class_teacher_id', $teacher_id);
            })
            ->get();
            $topic_name = "ClassHistory";

            return view('academic_modules.class-topic-teacher',compact('topic_list','allAcademicsLevel','topic_name'));
        }else{
            return redirect('/');
        }
    }

    public function getTeacherClasstopic(Request $request){

        $class_id      = $request->input('class_id');
        $section_id    = $request->input('section_id');
        $subject_id    = $request->input('subject_id');
        if(Auth::user()->hasRole('teacher')){
            // user info
            $userInfo = Auth::user()->userInfo()->first();

            // auth user
            $user = Auth::user();
            // checking employee profile
            $userProfile = $user->employee();

            // academics levels
            $allAcademicsLevel = $this->academicHelper->getAllAcademicLevel();



            // set session info
            $sessionHelper = $this->sessionHelper->setSession([
                'academic_year' =>$this->academicHelper->findInstituteAcademicYear($userInfo->institute_id, $userInfo->campus_id)->id,
                'institute'=>$userInfo->institute_id,
                'campus'=>$userInfo->campus_id,
                'grading_scale'=>1
            ]);
            // return view with variable

            
            $institute_id = $userInfo->institute_id;
            $campus_id    = $userInfo->campus_id;
            $teacher_id   = $userProfile->id; //$userInfo;


            $data = array();
            // all class subject
            $allClassSubjectTopic = $this->OnlineClassTopic->where(['academic_class_id'=>$class_id, 'academic_section_id'=>$section_id, 'class_teacher_id'=>$teacher_id])->orderBy('id', 'ASC')->get();


            foreach ($allClassSubjectTopic as $classSubject) {
                $data[] = $this->ClassSubjectTopicReturnPack($classSubject);
            }
           

            return $data;
        }
          
    }

    public function SearchTeacherClasstopic(Request $request){
        $academic_id   = $request->input('academic_level_id');
        $class_id      = $request->input('class_id');
        $section_id    = $request->input('section_id');
        $subject_id    = $request->input('subject_id');
        $subject_topic = $request->input('subject_class_topic');

        if(Auth::user()->hasRole('teacher')){
            // user info
            $userInfo = Auth::user()->userInfo()->first();

            // auth user
            $user = Auth::user();
            // checking employee profile
            $userProfile = $user->employee();

            // academics levels
            $allAcademicsLevel = $this->academicHelper->getAllAcademicLevel();



            // set session info
            $sessionHelper = $this->sessionHelper->setSession([
                'academic_year' =>$this->academicHelper->findInstituteAcademicYear($userInfo->institute_id, $userInfo->campus_id)->id,
                'institute'=>$userInfo->institute_id,
                'campus'=>$userInfo->campus_id,
                'grading_scale'=>1
            ]);
            // return view with variable

            
            $institute_id = $userInfo->institute_id;
            $campus_id    = $userInfo->campus_id;
            $teacher_id   = $userProfile->id; //$userInfo;


            
            $topic_list = DB::table('online_class_topics')
            ->when($institute_id, function ($query, $institute_id) {
                return $query->where('institute_id', $institute_id);
            })
            ->when($campus_id, function ($query, $campus_id) {
                return $query->where('campus_id', $campus_id);
            })
            ->when($academic_id, function ($query, $academic_id) {
                return $query->where('academic_level_id', $academic_id);
            })
            ->when($class_id, function ($query, $class_id) {
                return $query->where('academic_class_id', $class_id);
            })
            ->when($section_id, function ($query, $section_id) {
                return $query->where('academic_section_id', $section_id);
            })
            ->when($subject_id, function ($query, $subject_id) {
                return $query->where('class_subject_id', $subject_id);
            })
            ->when($subject_topic, function ($query, $subject_topic) {
                return $query->where('id', $subject_topic);
            })
            ->when($teacher_id, function ($query, $teacher_id) {
                return $query->where('class_teacher_id', $teacher_id);
            })
            ->get();

            return view('academic_modules.class-topic-teacher',compact('topic_list','allAcademicsLevel'));
        }
        else{
            return redirect('/');
        }

    }

    public function ClassSubjectTopicReturnPack($classSubject)
    {
        return [
            'id' => $classSubject->id,
            'sub_topic' => $classSubject->class_topic
        ];
    }

    public function studentDashboard()
    {
        if(Auth::user()->hasRole('student')){
            // user info
            $userInfo = Auth::user()->userInfo()->first();
//            dd($userInfo);
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

    public function studentClassTopic(){

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

            $stdUser        = Auth::user(); 
            $stdInfo        = $stdUser->student(); 
            $stdEnroll      = $stdInfo->singleEnroll();

            $division = null;
            if($stdEnroll->batch()->get_division()){
                $division = ' ('.$stdEnroll->batch()->get_division()->name.')';
            }

            $academic_level      = $stdEnroll->level()->level_name;
            $academic_class      = $stdEnroll->batch()->batch_name.$division;
            $academic_section    = $stdEnroll->section()->section_name;

            $institute_id = $userInfo->institute_id;
            $campus_id    = $userInfo->campus_id;

            $topic_list = DB::table('online_class_topics')
            ->when($institute_id, function ($query, $institute_id) {
                return $query->where('institute_id', $institute_id);
            })
            ->when($campus_id, function ($query, $campus_id) {
                return $query->where('campus_id', $campus_id);
            })
            ->when($academic_level, function ($query, $academic_level) {
                return $query->where('academic_level', 'like','%'.$academic_level.'%');
            })
            ->when($academic_class, function ($query, $academic_class) {
                return $query->where('academic_class', 'like','%'.$academic_class.'%');
            })
            ->when($academic_section, function ($query, $academic_section) {
                return $query->where('academic_section', 'like','%'.$academic_section.'%');
            })
            ->get();

             $topic_name = (isset($topic_name) ? $topic_name : "classtopic");


            return view('academic_modules.class-topic-student',compact('topic_list','topic_name'));
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

    public function accountDashboard()
    {
        if(Auth::user()->hasRole('accountant')){
            $userInfo = Auth::user()->userInfo()->first();
            // set session info
            $sessionHelper = $this->sessionHelper->setSession([
                'academic_year' =>$this->academicHelper->findInstituteAcademicYear($userInfo->institute_id, $userInfo->campus_id)->id,
                'institute'=>$userInfo->institute_id,
                'campus'=>$userInfo->campus_id,
                'grading_scale'=>1
            ]);
            // return view with variable
            return redirect('/finance/accounts/dashboard');
        }else{
            return redirect('/');
        }
    }


    public function feesDashboard()
    {
        // return view with variable
        return view('academic_modules.dashboard-fees');
    }


    public function newDashboard()
    {

        // return view with variable
        return view('academic_modules.new-dashboard');
    }

    public function newDashboardAcc(){
        return view('academic_modules.new-dashboard-acc');
    }


    // all user change password

    public function showChangePasswordForm(){
        return view('auth.changepassword');
    }

    public function changePassword(Request $request){



        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            // The passwords matches
            return redirect()->back()->with("error","Your current password does not matches. Please try again.");
        }
        if(strcmp($request->get('current-password'), $request->get('new-password')) == 0){
            //Current password and new password are same
            return redirect()->back()->with("error","New Password cannot be same as your current password. Please choose a different password.");
        }

        $messages = [
            'new-password.confirmed' => 'Password confirmation doesn\'t match Password',
            'new-password.min' =>'password length must be greater than 6 characters'
        ];

        $validatedData = Validator::make($request->all(),[
            'current-password' => 'required',
            'new-password' => 'required|string|min:6|confirmed',
        ],$messages);

        if ($validatedData->fails()) {
            return redirect()->back()
                ->withErrors($validatedData)
                ->withInput();
        } else {
            //Change Password
            $user = Auth::user();
            $user->password = bcrypt($request->get('new-password'));
            $user->save();
            return redirect()->back()->with("success", "Password changed successfully !");
        }
    }

    // dashbaord route

    public function financeDashboard(){
        // return view with variable
        return view('dashboard.finance');
    }





}
