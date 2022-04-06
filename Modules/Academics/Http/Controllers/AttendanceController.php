<?php

namespace Modules\Academics\Http\Controllers;
use App;
use Dompdf\Exception;
use Excel;
use Illuminate\Support\Facades\Auth;
use Session;
use Validator;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Academics\Entities\AcademicsLevel;
use Modules\Academics\Entities\AttendanceSession;
use Modules\Academics\Entities\AttendanceSetting;
use Modules\Academics\Entities\AttendanceType;
use Modules\Academics\Entities\AttendanceViewOne;
use Modules\Academics\Entities\AttendanceViewTwo;
use Modules\Academics\Entities\StudentAttendance;
use Modules\Academics\Entities\StudentAttendanceDetails;
use Modules\Student\Entities\StudentEnrollment;
use Modules\Student\Entities\StudentInformation;
use Modules\Student\Entities\StudentGuardian;
use Modules\Setting\Entities\AutoSmsModule;
use App\Http\Controllers\Helpers\AcademicHelper;
use App\Http\Controllers\SmsSender;
use Modules\Student\Entities\StudentProfileView;
use Modules\Academics\Entities\AttendanceUpload;
use Modules\Academics\Entities\AttendanceUploadAbsent;
use Modules\Academics\Entities\AttendanceUploadHistory;
use Modules\Academics\Entities\ClassTeacherAssign;

class AttendanceController extends Controller
{

    private $attendanceType;
    private $studentEnrollment;
    private $studentGuardian;
    private $attendanceSession;
    private $attendanceSetting;
    private $studentInformation;
    private $studentAttendance;
    private $studentAttendanceDetails;
    private $attendanceViewOne;
    private $attendanceViewTwo;
    private $academicsLevel;
    private $academicHelper;
    private $smsSender;
    private $autoSmsModule;

    private $studentProfileView;
    private $attendanceUpload;
    private $attendanceUploadAbsent;
    private $attendanceUploadHistory;

    // constructor
    public function __construct(AcademicsLevel $academicsLevel, ClassTeacherAssign $classTeacherAssign, AutoSmsModule $autoSmsModule, SmsSender $smsSender, StudentEnrollment $studentEnrollment, AttendanceType $attendanceType, AttendanceSession $attendanceSession, AttendanceSetting $attendanceSetting, StudentInformation $studentInformation, StudentAttendance $studentAttendance, StudentAttendanceDetails $studentAttendanceDetails, AttendanceViewOne $attendanceViewOne, AttendanceViewTwo $attendanceViewTwo, StudentGuardian $studentGuardian, AcademicHelper $academicHelper, AttendanceUpload $attendanceUpload, AttendanceUploadAbsent $attendanceUploadAbsent, StudentProfileView $studentProfileView, AttendanceUploadHistory $attendanceUploadHistory)
    {
        $this->academicsLevel           = $academicsLevel;
        $this->studentEnrollment        = $studentEnrollment;
        $this->studentGuardian          = $studentGuardian;
        $this->attendanceType           = $attendanceType;
        $this->attendanceSession        = $attendanceSession;
        $this->attendanceSetting        = $attendanceSetting;
        $this->studentInformation       = $studentInformation;
        $this->studentAttendance        = $studentAttendance;
        $this->studentAttendanceDetails = $studentAttendanceDetails;
        $this->attendanceViewOne        = $attendanceViewOne;
        $this->attendanceViewTwo        = $attendanceViewTwo;
        $this->academicHelper           = $academicHelper;
        $this->smsSender               = $smsSender;
        $this->autoSmsModule               = $autoSmsModule;

        $this->attendanceUpload  = $attendanceUpload;
        $this->studentProfileView  = $studentProfileView;
        $this->attendanceUploadAbsent  = $attendanceUploadAbsent;
        $this->attendanceUploadHistory  = $attendanceUploadHistory;
        $this->classTeacherAssign  = $classTeacherAssign;

    }

    /**
     * Display a listing of the resource.
     */
    // manage assessment index page
    public function index($tabId)
    {
        switch ($tabId) {

            case 'manage':
                // attendanceType
                // $allAcademicsLevel    = $this->academicsLevel->where('academics_year_id', $this->getAcademicYearId())->get();
                $allAcademicsLevel    = $this->academicsLevel->where([
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                ])->get();
                $allAttendanceSession = $this->attendanceSession->orderBy('session_name', 'ASC')->get();
                $attendanceSettingProfile = $this->getAttendanceSettings();

                // return veiw with variables
                return View('academics::manage-attendance.attendance', compact('allAcademicsLevel', 'allAttendanceSession', 'attendanceSettingProfile'))->with('page', 'manage');
                break;

            case 'daily-attendance':
                return abort(404);
                // academic level
                $allAcademicsLevel = $this->academicHelper->getAllAcademicLevel();
                // return veiw with variables
                return View('academics::manage-attendance.attendance-daily', compact('allAcademicsLevel'))->with('page', 'daily-attendance');
                break;


            case 'settings':
                // attendanceType
                $allAttendanceType        = $this->attendanceType->all();
                $allAttendanceSession     = $this->attendanceSession->all();
                $attendanceSettingProfile = $this->getAttendanceSettings();
                // return
                return View('academics::manage-attendance.settings', compact('allAttendanceType', 'allAttendanceSession', 'attendanceSettingProfile'))->with('page', 'settings');
                break;

            case 'upload':
                $attendanceHistory = $this->attendanceUploadHistory->where([
                    'campus'=>$this->getInstituteCampusId(),'institute'=>$this->getInstituteId()
                ])->orderBy('created_at', 'desc')->get();
                return View('academics::manage-attendance.upload', compact('attendanceHistory'))->with('page', 'upload');
                break;

            case 'report':
                // academic level
                $allAcademicsLevel = $this->academicHelper->getAllAcademicLevel();
                return View('academics::manage-attendance.report', compact('allAcademicsLevel'))->with('page', 'report');
                break;

            default:
                abort(404);
                break;
        }
    }
    /////////////////////////////////  Settings /////////////////////////////////
    // create status
    public function createStatus()
    {
        return view('academics::manage-attendance.modals.status')->with('attendanceTypeProfile', null);
    }

    // store status
    public function storeStatus(Request $request)
    {
        // return $request->all();
        // validator
        $validator = Validator::make($request->all(), [
            'status'     => 'required|max:30',
            'short_code' => 'required|max:30',
        ]);

        // checking
        if ($validator->passes()) {

            // create new instance of attendanceType
            $attendanceTypeProfile = new $this->attendanceType();
            // store inputs details
            $attendanceTypeProfile->type_name  = $request->input('status');
            $attendanceTypeProfile->short_code = $request->input('short_code');
            $attendanceTypeProfile->color      = $request->input('color');
            // save attendanceType
            $attendanceTypeCreated = $attendanceTypeProfile->save();
            // checking
            if ($attendanceTypeCreated) {
                return $allAttendanceType = $this->attendanceType->all();
            } else {

            }

        } else {
            // warning msg
            Session::flash('warning', 'Invalid Information');
            // redirecting
            return redirect()->back()->withInputs($validator);
        }
    }

    public function editStatus($id)
    {
        // attendanceTypeProfile
        $attendanceTypeProfile = $this->attendanceType->where('id', $id)->first();
        // return view with variable
        return view('academics::manage-attendance.modals.status', compact('attendanceTypeProfile'));
    }

    // store status
    public function updateStatus(Request $request, $id)
    {
        // validator
        $validator = Validator::make($request->all(), [
            'status'     => 'required|max:30',
            'short_code' => 'required|max:30',
        ]);

        // checking
        if ($validator->passes()) {
            // attendanceTypeProfile
            $attendanceTypeProfile = $this->attendanceType->where('id', $id)->first();
            // store inputs details
            $attendanceTypeProfile->type_name  = $request->input('status');
            $attendanceTypeProfile->short_code = $request->input('short_code');
            $attendanceTypeProfile->color      = $request->input('color');
            // save attendanceType
            $attendanceTypeCreated = $attendanceTypeProfile->save();
            // checking
            if ($attendanceTypeCreated) {
                return $allAttendanceType = $this->attendanceType->all();
            } else {
                // warning msg
                Session::flash('warning', 'Invalid Information');
                // redirecting
                return redirect()->back();
            }

        } else {
            // warning msg
            Session::flash('warning', 'Invalid Information');
            // redirecting
            return redirect()->back()->withInputs($validator);
        }
    }

    // destroy status
    public function destroyStatus($id)
    {
        // attendanceTypeProfile
        $attendanceTypeProfile = $this->attendanceType->where('id', $id)->first();
        // delete
        $attendanceTypeProfileDeleted = $attendanceTypeProfile->delete();

        // checking
        if ($attendanceTypeProfileDeleted) {
            return $allAttendanceType = $this->attendanceType->all();
        }
    }

    public function createSession()
    {
        return view('academics::manage-attendance.modals.session')->with('attendanceSessionProfile', null);
    }

    public function storeSession(Request $request)
    {
        // return $request->all();
        // validator
        $validator = Validator::make($request->all(), [
            'session_name' => 'required|max:30',
        ]);

        // checking
        if ($validator->passes()) {
            // create new instance of attendanceType
            $attendanceSessionProfile = new $this->attendanceSession();
            // store inputs details
            $attendanceSessionProfile->institution_id = $request->input('institution_id');
            $attendanceSessionProfile->campus_id      = $request->input('campus_id');
            $attendanceSessionProfile->session_name   = $request->input('session_name');
            // save attendanceType
            $attendanceSessionProfileCreated = $attendanceSessionProfile->save();
            // checking
            if ($attendanceSessionProfile) {
                return $allAttendanceSession = $this->attendanceSession->all();
            } else {
                // warning msg
                Session::flash('warning', 'Invalid Information');
                // redirecting
                return redirect()->back();
            }
        } else {
            // warning msg
            Session::flash('warning', 'Invalid Information');
            // redirecting
            return redirect()->back()->withInputs($validator);
        }
    }

    public function editSession($id)
    {
        // attendanceTypeProfile
        $attendanceSessionProfile = $this->attendanceSession->where('id', $id)->first();
        // return view with variable
        return view('academics::manage-attendance.modals.session', compact('attendanceSessionProfile'));
    }

    public function updateSession(Request $request, $id)
    {
        // return $request->all();
        // validator
        $validator = Validator::make($request->all(), [
            'session_name' => 'required|max:30',
        ]);

        // checking
        if ($validator->passes()) {
            // create new instance of attendanceType
            $attendanceSessionProfile = $this->attendanceSession->where('id', $id)->first();
            // store inputs details
            $attendanceSessionProfile->institution_id = $request->input('institution_id');
            $attendanceSessionProfile->campus_id      = $request->input('campus_id');
            $attendanceSessionProfile->session_name   = $request->input('session_name');
            // save attendanceType
            $attendanceSessionProfileCreated = $attendanceSessionProfile->save();
            // checking
            if ($attendanceSessionProfile) {
                return $allAttendanceSession = $this->attendanceSession->all();
            } else {
                // warning msg
                Session::flash('warning', 'Invalid Information');
                // redirecting
                return redirect()->back();
            }
        } else {
            // warning msg
            Session::flash('warning', 'Invalid Information');
            // redirecting
            return redirect()->back()->withInputs($validator);
        }
    }

    public function destroySession($id)
    {
        // create new instance of attendanceType
        $attendanceSessionProfile = $this->attendanceSession->where('id', $id)->first();
        // delete
        $attendanceSessionProfileDeleted = $attendanceSessionProfile->delete();

        // checking
        if ($attendanceSessionProfileDeleted) {
            return $allAttendanceSession = $this->attendanceSession->all();
        }
    }

    public function setAttendanceType($status)
    {
        $instituteId = $this->academicHelper->getInstitute();
        $campusId = $this->academicHelper->getCampus();
        // attendanceSettingProfile
        $attendanceSettingProfile = $this->attendanceSetting->where([
            'institution_id'=>$instituteId,
            'campus_id'=>$campusId,
        ])->first();
        // now update attendanceSettingProfile
        $attendanceSettingProfile->subject_wise = $status;
        // saving
        $attendanceSettingProfileUpdated = $attendanceSettingProfile->save();
        if ($attendanceSettingProfileUpdated) {
            return redirect()->back();
        }
    }

    public function setSessionType($status)
    {
        $instituteId = $this->academicHelper->getInstitute();
        $campusId = $this->academicHelper->getCampus();
        // attendanceSettingProfile
        $attendanceSettingProfile = $this->attendanceSetting->where([
            'institution_id'=>$instituteId,
            'campus_id'=>$campusId,
        ])->first();
        // now update attendanceSettingProfile
        $attendanceSettingProfile->multiple_sessions = $status;
        // saving
        $attendanceSettingProfileUpdated = $attendanceSettingProfile->save();
        // checking
        if ($attendanceSettingProfileUpdated) {
            return redirect()->back();
        }
    }

    ///////////////////////// Manage  Attendance /////////////////////////


    // batch section attendance list for daily attendance
    public function getDailyAttendanceStudentList(Request $request)
    {

        $campusId = $this->academicHelper->getCampus();
        $instituteId = $this->academicHelper->getInstitute();
        /// get automatic attendance modules is active
        $attendanceModule = $this->autoSmsModule->where('ins_id',$instituteId)->where('campus_id',$campusId)->where('status_code',"ATTENDANCE")->where('status',1)->first();

        // batch section details
        $level     = $request->input('academic_level');
        $batch     = $request->input('batch');
        $section   = $request->input('section');
        $inputDate = new Carbon($request->input('date'));
        $attendanceDate = $inputDate->toDateString();
        $academicYearId = $this->academicHelper->getAcademicYear();
        $studentList=[];
        if(Auth::user()->hasRole(['super-admin','admin'])) {
              $studentList = $this->getStudentList($batch, $section);

        } elseif(Auth::user()->hasRole('teacher')) {

              $classTeacherProfile=$this->classTeacherAssign
                ->where('institute_id',institution_id())
                ->where('campus_id',campus_id())
                ->where('teacher_id',Auth::user()->employee()->id)
                ->where('batch_id',$request->batch)
                ->where('section_id',$request->section)
                ->first();
            if(!empty($classTeacherProfile)) {
                $studentList = $this->getStudentList($batch, $section);
            } else {
                $studentList=[];
            }
        }else {
            $studentList=[];
        }

          $attendanceList =  $this->processDailyAttendanceList($instituteId, $campusId, $academicYearId, $level, $batch, $section, $attendanceDate);

        // view rendering
        $htmlContent = view('academics::manage-attendance.modals.daily-attendance-list', compact('studentList','attendanceList', 'attendanceDate','attendanceModule'))->render();
        // return view with variable
        return ['status'=>'success', 'msg'=>'Attendance list', 'content'=>$htmlContent];
    }

    public function getClassSectionStudentList(Request $request)
    {

        $campusId = $this->academicHelper->getCampus();
        $instituteId = $this->academicHelper->getInstitute();

        // get automatic attendance modules is active
         $attendanceModule = $this->autoSmsModule->where('ins_id',$instituteId)->where('campus_id',$campusId)->where('status_code',"ATTENDANCE")->where('status',1)->first();

        $batch     = $request->input('batch');
        $section   = $request->input('section');
        $subject   = $request->input('subject');
        $session   = $request->input('session');
        $pageType  = $request->input('attendance_page_type', 'manage');
        $inputDate = new Carbon($request->input('datepicker'));
        $attendanceDate = $inputDate->toDateString();
        $studentList = $this->getStudentList($batch, $section);

        // checking std list
        if((!is_null($studentList) AND !empty($studentList) AND count($studentList)>0)){
            // student attendance list
            if ($subject) {
                $attendanceSql = array('class_id' => $batch, 'section_id' => $section, 'subject_id' => $subject, 'session_id' => $session, 'deleted_at' => null);
            } else {
                $attendanceSql = array('class_id' => $batch, 'section_id' => $section, 'session_id' => $session, 'deleted_at' => null);
            }
            //student attendance list as events
            if ($subject) {
                $attendanceList = $this->processSingleDateAttendanceList($attendanceSql, $attendanceDate, 2);
            } else {
                $attendanceList = $this->processSingleDateAttendanceList($attendanceSql, $attendanceDate, 1);
            }

            // view rendering
            $htmlContent = view('academics::manage-attendance.modals.attendance-list', compact('studentList', 'attendanceList', 'pageType', 'attendanceDate', 'attendanceModule'))->render();
            // return view with variable
            return ['status'=>'success', 'msg'=>'Attendance list', 'content'=>$htmlContent];
        }else{
            // return
            return ['status'=>'failed', 'msg'=>'No Student(s) Found'];
        }
    }

    public function getAnotherAttendanceList(Request $request)
    {
        $batch     = $request->input('batch');
        $section   = $request->input('section');
        $subjcet   = $request->input('subject');
        $session   = $request->input('session');
        $inputDate = new Carbon($request->input('datepicker'));
        $fromDate  = $inputDate->toDateString();
        $toDate    = $inputDate->addDays(6)->toDateString();

        // // student attendance list
        if ($subjcet) {
            $attendanceSql = array('class_id' => $batch, 'section_id' => $section, 'subject_id' => $subjcet, 'session_id' => $session, 'deleted_at' => null);
        } else {
            $attendanceSql = array('class_id' => $batch, 'section_id' => $section, 'session_id' => $session, 'deleted_at' => null);
        }
        // return attendancelist
        if ($subjcet) {
            return $attendancelist = $this->processAttendanceList($attendanceSql, $fromDate, $toDate, 2);
        } else {
            return $attendancelist = $this->processAttendanceList($attendanceSql, $fromDate, $toDate, 1);
        }

    }

    public function processSingleDateAttendanceList($attendanceSql, $attendanceDate, $id)
    {
        // response array list
        $attendanceList = array();
        // attendance details
        if ($id == 1) {
            $allAttendanceList = $this->attendanceViewOne->where($attendanceSql)->where('attendance_date', $attendanceDate)->get();
        } else {
            $allAttendanceList = $this->attendanceViewTwo->where($attendanceSql)->where('attendance_date', $attendanceDate)->get();
        }
        // looping
        foreach ($allAttendanceList as $myAttendance) {
            // attendance color
            $attColor = null;
            // attendance Type
            $attType  = $myAttendance->attendacnce_type;
            // checking
            if($attType==0) $attColor = 'alert-danger';
            if($attType==1) $attColor = 'alert-success';
            if($attType==2) $attColor = 'alert-warning';

            // attendance list
            $attendanceList[$myAttendance->student_id] = [
                'std_id' => $myAttendance->student_id,
                'att_id' => $myAttendance->att_id,
                'att_date' => date('d-m-Y', strtotime($myAttendance->attendance_date)),
                'att_type' => $attType,
                'att_color' => $attColor,
            ];
        }
        // return
        return $attendanceList;
    }

    // process daily attendance list
    public function processDailyAttendanceList($institute, $campus, $academicYear, $level, $batch, $section, $attendanceDate)
    {
        $date = date('Y-m-d', strtotime($attendanceDate));
        $fromDate = date('Y-m-d 00:00:00', strtotime($attendanceDate));
        $toDate = date('Y-m-d 23:59:59', strtotime($attendanceDate));
        // attendance search qry
        $qry = ['level'=>$level, 'batch'=>$batch,  'section'=>$section, 'institute'=>$institute, 'campus'=>$campus, 'academic_year'=>$academicYear];

        // response array list
        $attendanceList = array();
        // find today upload attendance
         $presentAttendanceList =  $this->attendanceUpload->where($qry)->whereBetween('entry_date_time', [$fromDate, $toDate])->get();
        // find today upload absent attendance
        $absentAttendanceList =  $this->attendanceUploadAbsent->where($qry)->where('date', $date)->get();

        // checking attendance list
        if($presentAttendanceList->count()>0){
            // looping
            foreach ($presentAttendanceList as $presentAttendance) {
                // attendance list
                $attendanceList[$presentAttendance->std_id] = [
                    'att_id' => $presentAttendance->id,
                    'std_id' => $presentAttendance->std_id,
                    'std_gr_no' => $presentAttendance->std_gr_no,
                    'att_date' => date('d-m-Y', strtotime($presentAttendance->entry_date_time)),
                    'att_type' => 1,
                    'att_color' => 'alert-success',
                ];
            }
        }

        // checking attendance list
        if($absentAttendanceList->count()>0){
            // looping
            foreach ($absentAttendanceList as $absentAttendance) {
                // attendance list
                $attendanceList[$absentAttendance->std_id] = [
                    'att_id' => $absentAttendance->id,
                    'std_id' => $absentAttendance->std_id,
                    'std_gr_no' => $absentAttendance->std_gr_no,
                    'att_date' => date('d-m-Y', strtotime($absentAttendance->date)),
                    'att_type' => 0,
                    'att_color' => 'alert-danger',
                ];
            }
        }


        // return
        return $attendanceList;
    }



    public function processAttendanceList($attendanceSql, $fromDate, $toDate, $id)
    {
        // response array

        $attendanceList = array();

        // attendance details
        if ($id == 1) {
            $allAttendanceList = $this->attendanceViewOne->where($attendanceSql)->whereBetween('attendance_date', array($fromDate, $toDate))->get();
        } else {
            $allAttendanceList = $this->attendanceViewTwo->where($attendanceSql)->whereBetween('attendance_date', array($fromDate, $toDate))->get();
        }

        foreach ($allAttendanceList as $myAttendance) {
            if ($myAttendance->attendacnce_type == 1) {
                $toDayAttendanceTitle = 'Present';
                $toDayAttendanceColor = 'green';
            } else {
                $toDayAttendanceTitle = 'Absent';
                $toDayAttendanceColor = 'red';
            }
            // attendance list
            $attendanceList[] = array(
                'att_id' => $myAttendance->att_id,
                'id' => $myAttendance->student_id . strtotime($myAttendance->attendance_date) * 1000,
                'resourceId' => $myAttendance->student_id,
                'start' => strtotime($myAttendance->attendance_date) * 1000,
                'end' => strtotime($myAttendance->attendance_date) * 1000,
                'att_type' => $myAttendance->attendacnce_type,
                'title' => $toDayAttendanceTitle,
                'color' => $toDayAttendanceColor
            );
        }
        return $attendanceList;
    }

    public function manageAttendance2(Request $request)
    {
//        return $request->all();
        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'att_class'    => 'required',
            'att_section' => 'required',
            'att_date'     => 'required',
            'std_count'     => 'required',
            'std_list'     => 'required',
        ]);

        // storing requesting input data
        if ($validator->passes()) {
            // input details
            $attClass = $request->input('att_class');
            $attSection = $request->input('att_section');
            $attSubject = $request->input('att_subject');
            $attSession = $request->input('att_session');
            $attDate = $request->input('att_date');
            $stdList = $request->input('std_list');
            $stdCount = $request->input('std_count');

            // loop counter
            $loopCounter = 0;
            $responseAttendanceList = array();
            try {
                // looping
                foreach ($stdList as $stdId => $attDetails){
                    // att id
                    $stdId = $attDetails['std_id'];
                    $attId = $attDetails['att_id'];
                    $attType = $attDetails['att_type'];
                    // checking attId
                    if($attId>0){
                        // attendanceProfile
                        $studentAttendanceProfile = $this->studentAttendance->find($attId);
                    }else{
                        // attendanceProfile
                        $studentAttendanceProfile = new $this->studentAttendance();
                    }
                    // store student attendance details
                    $studentAttendanceProfile->student_id = $stdId;
                    $studentAttendanceProfile->attendance_date = date("Y-m-d", strtotime($attDate));
                    $studentAttendanceProfile->attendacnce_type = $attType;
                    // saving attendance
                    if ($studentAttendanceProfile->save()) {
                        if($attId == 0){
                            $studentAttendanceDetailsProfile = new $this->studentAttendanceDetails();
                            // store attendance details
                            $studentAttendanceDetailsProfile->student_attendace_id = $studentAttendanceProfile->id;
                            $studentAttendanceDetailsProfile->class_id = $attClass;
                            $studentAttendanceDetailsProfile->section_id = $attSection;
                            if ($attSubject) {$studentAttendanceDetailsProfile->subject_id = $attSubject;}
                            $studentAttendanceDetailsProfile->session_id = $attSession;
                            // save attendance details
                            if($studentAttendanceDetailsProfile->save()){
                                // response list with attendance id
                                $responseAttendanceList[] = ['std_id'=>$stdId, 'att_id'=>$studentAttendanceProfile->id];
                            }
                        }else{
                            $responseAttendanceList[] = ['std_id'=>$stdId, 'att_id'=>$studentAttendanceProfile->id];
                        }
                        // loop counter increment
                        $loopCounter += 1;
                    }
                }

            }catch (\Exception $exception){

            }finally{

                //request attendance date
                $attedanceDate = date('Y-m-d',strtotime($request->input('att_date')));
                $automaticSms = $request->input('send_automatic_sms');
                //get all student array list
                $getAllStudent = $request->input('std_list');
                //all student id move to studentIdList variable
                $studentIdList = array();
                foreach ($getAllStudent as $key=>$student) {
                    $studentIdList[] = $student['std_id'];
                }
                // check condition and send request
                if ($attedanceDate == date("Y-m-d") && $automaticSms == "1" && !empty($studentIdList)) {
                    $this->smsSender->absent_attendance_job($studentIdList);
                }

                // checking
                if($loopCounter == $stdCount){

                    return [
                        'status'=>'success',
                        'msg'=>'Attendance uploaded successfully',
                        'att_list' =>$responseAttendanceList
                    ];
                }else{
                    return ['status'=>'failed', 'msg'=>'Unable to upload perform the action'];
                }
            }
        }else {
            Session::flash('warning', "Invalid Information");
            return redirect()->back();
        }

    }

    // manage attendance
    public function manageAttendance(Request $request)
    {

        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'att_class'    => 'required',
            'att_sesction' => 'required',
            'att_std_list' => 'required',
            'date_counter' => 'required',
            'att_date'     => 'required',
        ]);

        // storing requesting input data
        if ($validator->passes()) {

            try {
                // receiveing required inputs details
                $attClass = $request->input('att_class');
                $attSection = $request->input('att_sesction');
                $attSubject = $request->input('att_subjcet');
                $attSession = $request->input('att_session');
                $dateCounter = $request->input('date_counter');
                $deleteCounter = $request->input('delete_counter');
                $studentList = json_decode($request->input('att_std_list'));
                // receive input students and date list
                $dateList = $request->datelist;

                // receiveing required inputs details
                $inputDate = new Carbon($request->input('att_date'));
                $fromDate = $inputDate->toDateString();
                $toDate = $inputDate->addDays(6)->toDateString();
                // student attendance list sql
                if ($attSubject) {
                    $attendanceSql = array('class_id' => $attClass, 'section_id' => $attSection, 'subject_id' => $attSubject, 'session_id' => $attSession, 'deleted_at' => null);
                } else {
                    $attendanceSql = array('class_id' => $attClass, 'section_id' => $attSection, 'session_id' => $attSession, 'deleted_at' => null);
                }

                //attendanceDeleteCounter
                $attendanceDeleteCounter = 0;
                // now delete from deleted attendacne list
                if ($deleteCounter > 0) {
                    // all deleted attendance date list
                    $allDeletedAttendance = $request->deleteList;
                    // single subject
                    for ($a = 1; $a <= $deleteCounter; $a++) {
                        $deletedSingleDate = date("Y-m-d", $allDeletedAttendance['date_' . $a] / 1000);
                        $deleteSql = array('attendance_date' => $deletedSingleDate);
                        // deletd attendance
                        $sinleDateAttendanceList = $this->studentAttendance->where($deleteSql)->get();
                        // now delete one by oneattendance_date
                        foreach ($sinleDateAttendanceList as $dateAttendance) {
                            $attendanceProfile = $this->studentAttendance->where('id', $dateAttendance->id)->first();
                            // delete attendanceProfile
                            $attendanceProfileDelete = $attendanceProfile->delete();
                            // checking
                            if ($attendanceProfileDelete) {
                                ($attendanceDeleteCounter + 1);
                            }
                        }
                    }
                }

                if ($dateCounter > 0) {
                    $x = 0;
                    for ($i = 1; $i <= $dateCounter; $i++) {
                        $singleDate = $dateList['date_' . $i];
                        // single date attendance list
                        $attendanceList = $request->input('att_' . $singleDate);
                        if ($attendanceList) {
                            // store every students list one by one
                            $m = 0;
                            for ($p = 0; $p < count($studentList); $p++) {
                                //student attendance
                                $myAttendancePrfile = $attendanceList['id_' . $studentList[$p]->id];
                                $myAttendanceId = $myAttendancePrfile['att_id'];
                                $myAttendanceType = $myAttendancePrfile['att_type'];
                                // checking
                                if ($myAttendanceId > 0) {
                                    // // attendanceProfile
                                    $studentAttendanceProfile = $this->studentAttendance->where('id', $myAttendanceId)->first();
                                    // update attendance
                                    $studentAttendanceProfile->attendacnce_type = $myAttendanceType;
                                    // saving attendance
                                    $studentAttendanceProfileUpdated = $studentAttendanceProfile->save();
                                    // checking
                                    if ($studentAttendanceProfileUpdated) {
                                        // attendanceDetailsProfile
                                        $studentAttendanceDetailsProfile = $this->studentAttendanceDetails->where('student_attendace_id', $myAttendanceId)->first();
                                        // update attendance details
                                        $studentAttendanceDetailsProfile->class_id = $attClass;
                                        $studentAttendanceDetailsProfile->section_id = $attSection;
                                        if ($attSubject) {
                                            $studentAttendanceDetailsProfile->subject_id = $attSubject;
                                        }
                                        $studentAttendanceDetailsProfile->session_id = $attSession;
                                        // save attendance details
                                        $studentAttendanceDetailsProfileUpdated = $studentAttendanceDetailsProfile->save();
                                        // checking
                                        if ($studentAttendanceDetailsProfileUpdated) {
                                            $m = $m + 1;
                                        }
                                    }
                                } else {
                                    // attendanceProfile
                                    $studentAttendanceProfile = new $this->studentAttendance();
                                    // store student attendance details
                                    $studentAttendanceProfile->student_id = $studentList[$p]->id;
                                    $studentAttendanceProfile->attendance_date = date("Y-m-d", $singleDate / 1000);
                                    $studentAttendanceProfile->attendacnce_type = $myAttendanceType;
                                    // saving attendance
                                    $studentAttendanceProfileCreated = $studentAttendanceProfile->save();

                                    // checking and create student attendance details
                                    if ($studentAttendanceProfileCreated) {
                                        $studentAttendanceDetailsProfile = new $this->studentAttendanceDetails();
                                        // store attendance details
                                        $studentAttendanceDetailsProfile->student_attendace_id = $studentAttendanceProfile->id;
                                        $studentAttendanceDetailsProfile->class_id = $attClass;
                                        $studentAttendanceDetailsProfile->section_id = $attSection;
                                        if ($attSubject) {
                                            $studentAttendanceDetailsProfile->subject_id = $attSubject;
                                        }
                                        $studentAttendanceDetailsProfile->session_id = $attSession;
                                        // save attendance details
                                        $studentAttendanceDetailsProfileCreated = $studentAttendanceDetailsProfile->save();
                                        // checking
                                        if ($studentAttendanceDetailsProfileCreated) {
                                            $m = $m + 1;
                                        }
                                    }
                                }
                            }
                            if ($m == count($studentList)) {
                                $x = $x + 1;
                            }
                        } else {
                            Session::flash('warning', "attendanceList is empty");
                            return redirect()->back();
                        }
                    }
                    if ($x == $dateCounter) {
                        if ($attSubject) {
                            return $attendancelist = $this->processAttendanceList($attendanceSql, $fromDate, $toDate, 2);
                        } else {
                            return $attendancelist = $this->processAttendanceList($attendanceSql, $fromDate, $toDate, 1);
                        }
                    }
                } else {
                    Session::flash('warning', "Unable to submit attendance");
                    return redirect()->back();
                }

                // return attendancelist
                if ($attSubject) {
                    return $attendancelist = $this->processAttendanceList($attendanceSql, $fromDate, $toDate, 2);
                } else {
                    return $attendancelist = $this->processAttendanceList($attendanceSql, $fromDate, $toDate, 1);
                }




            }catch (\Exception $exception){

            }
            finally{
                //request attendance date
                $attedanceDate = $request->input('att_date');
                $automaticSms = $request->input('send_automatic_sms');
                //get all student array list
                $getAllStudent = json_decode($request->input('att_std_list'));
                //all student id move to studentIdList variable
                $studentIdList = array();
                foreach ($getAllStudent as $student) {
                    $studentIdList[] = $student->id;
                }
                // check condition and send request
                if ($attedanceDate == date("Y-m-d") && $automaticSms == "1" && !empty($studentIdList)) {
                    $this->smsSender->create_attendance_job($studentIdList);
                }
            }
        }else {
            Session::flash('warning', "Invalid Information");
            return redirect()->back();
        }
    }



    ////////////////////////  Export and Import Attendance List ////////////////////////

    public function exportAttendanceList(Request $request)
    {

        // class section attendance info
        $attendanceInfo = (object)[
            'batch'=>$request->input('batch'),
            'section'=>$request->input('section'),
            'subject'=>$request->input('subject'),
            'session'=>$request->input('session')
        ];

        // std info
        $studentList = $this->getStudentList($request->input('batch'), $request->input('section'));
        // checking std list
        if(count($studentList)>0){
            // compact variables with view
            view()->share(compact('studentList', 'attendanceInfo'));
            //generate excel
            Excel::create('Student Attendance Form', function ($excel) {
                $excel->sheet('Student Attendance Form', function ($sheet) {
                    // Font family
                    $sheet->setFontFamily('Comic Sans MS');
                    // Set font with ->setStyle()
                    $sheet->setStyle(array('font' => array('name'=>'Calibri','size' => 12)));
                    // cell formatting
                    $sheet->setAutoSize(true);
                    // Set all margins
                    $sheet->setPageMargin(0.25);
                    // $sheet->protectCells('A1:A6', "123456");
                    // mergeCell
                    // $sheet->mergeCells(['C3:D1', 'E1:H1']);

                    $sheet->loadView('academics::manage-attendance.reports.report-attendance-export-form');
                });
            })->download('xlsx');
        }else{
            // warning msg
            Session::flash('warning', 'Invalid Information');
            return redirect()->back();
        }
    }

    public function importAttendanceList()
    {
        return view('academics::manage-attendance.modals.attendance-import');
    }

    public function uploadAttendanceList(Request $request)
    {
        // receive input file
        $stdAttendanceFile = $request->file('attendance_list');
        // checking
        if($stdAttendanceFile){
            // get file real path
            $filePath = $stdAttendanceFile->getRealPath();
            // receive data from the input file
            $allAttendance = Excel::load($filePath, function($reader) {})->get();
            // date list
            $dateList = array();
            // checking
            if($allAttendance->count()>0){
                // filtering attendance date list form the array list
                $loopCounter = 0;
                foreach($allAttendance[0] as $key => $val) {
                    $loopCounter ++;
                    if($loopCounter <=4) continue;
                    $dateList[] = $key;
                }
                // return view with variables
                return view('academics::manage-attendance.modals.attendance-import-list', compact('dateList', 'allAttendance'));
            }else{
                // return view with variables
                return view('academics::manage-attendance.modals.attendance-import-list', compact('allAttendance'));
            }
        }
    }

    // upload final attendance list
    public function uploadFinalAttendanceList(Request $request)
    {
        $batch = $request->input('batch');
        $section = $request->input('section');
        $subject = $request->input('subject', 0);
        $session = $request->input('session', 0);

        // last date
        $lastDate = null;
        // std list
        $stdList = $request->input('std_list');
        // attendance list
        $attendanceList = $request->input('att_list');
        // std loop counter
        $stdLoopCounter = 0;
        // Start transaction!
        DB::beginTransaction();
        // try to upload attendance list
        try {
            // std list looping
            for ($x=1; $x<=count($stdList); $x++){
                // std id
                $stdId = $stdList[$x];
                // attendance
                $stdAttendance = $attendanceList[$stdId];
                // attendance loop counter
                $attendanceCounter = 0;
                // looping
                foreach($stdAttendance as $key => $value) {
                    // attendance info
                    $stdAttendanceInfo = (object)[
                        'std_id'=>$stdId,
                        'att_date'=>$key,
                        'att_type'=>$value,
                        'batch'=>$batch,
                        'section'=>$section,
                        'subject'=>$subject,
                        'session'=>$session
                    ];
                    // last date
                    $lastDate = $key;
                    // submit attendance
                    $attendanceSubmitted = $this->setStdAttendance($stdAttendanceInfo);
                    // checking attendance
                    if($attendanceSubmitted){
                        $attendanceCounter = ($attendanceCounter+1);
                    }
                }
                // checking
                if ($attendanceCounter == count($stdAttendance)) {
                    // std attendance counter
                    $stdLoopCounter = ($stdLoopCounter+1);
                }
            }
        } catch (ValidationException $e) {
            // Rollback and then redirect back to form with errors
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        // checking
        if ($stdLoopCounter == count($stdList)) {
            // If we reach here, then data is valid and working. Commit the queries!
            DB::commit();

            // date conversion
            $attendanceDate = date("Y-m-d", strtotime(str_replace("_", "-", $lastDate)));
            // std list
            $studentList = $this->getStudentList($batch, $section);

            // student attendance list
            if ($subject>0) {
                // qry
                $attendanceSql = array('class_id' => $batch, 'section_id' => $section, 'subject_id' => $subject, 'session_id' => $session, 'deleted_at' => null);
                //student attendance list as events
                $attendanceList = $this->processSingleDateAttendanceList($attendanceSql, $attendanceDate, 2);
            } else {
                // qry
                $attendanceSql = array('class_id' => $batch, 'section_id' => $section, 'session_id' => $session, 'deleted_at' => null);
                //student attendance list as events
                $attendanceList = $this->processSingleDateAttendanceList($attendanceSql, $attendanceDate, 1);
            }

            // get automatice attendance modules is active
            $attendanceModule=$this->autoSmsModule->where('status_code',"ATTENDANCE")->where('status',1)->get();
            //view rendering
            $attView =  view('academics::manage-attendance.modals.attendance-list', compact('studentList', 'attendanceList', 'attendanceDate', 'attendanceModule'))->render();

            //then sent this data to ajax success
            return ['status'=>'success', 'html'=>$attView];
        }else{
            return ['status'=>'failed', 'msg'=>'Unable to Submit the attendance list'];
        }

    }

    ///////////////////  internal function ///////////////////

    public function setStdAttendance($stdAttendanceInfo)
    {
        // attendanceProfile
        $attendanceProfile = new $this->studentAttendance();
        // store student attendance details
        $attendanceProfile->student_id = $stdAttendanceInfo->std_id;
        $attendanceProfile->attendance_date = date("Y-m-d", strtotime(str_replace("_", "-", $stdAttendanceInfo->att_date)));
        $attendanceProfile->attendacnce_type = $stdAttendanceInfo->att_type;
        // saving attendance
        $attendanceProfileSubmitted = $attendanceProfile->save();
        // checking and create student attendance details
        if ($attendanceProfileSubmitted) {
            $attendanceDetailsProfile = new $this->studentAttendanceDetails();
            // store attendance details
            $attendanceDetailsProfile->student_attendace_id = $attendanceProfile->id;
            $attendanceDetailsProfile->class_id = $stdAttendanceInfo->batch;
            $attendanceDetailsProfile->section_id = $stdAttendanceInfo->section;
            $attendanceDetailsProfile->subject_id = $stdAttendanceInfo->subject;
            $attendanceDetailsProfile->session_id = $stdAttendanceInfo->session;
            // save attendance details
            $attendanceDetailsProfileSubmitted = $attendanceDetailsProfile->save();
            // checking
            if ($attendanceDetailsProfileSubmitted) {
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }



    // update
    // batch section student list
    public function getStudentList($class, $section)
    {
        // response array
        $studentList = array();
        // class section students
        $classSectionStudent = $this->studentProfileView->where([
            'status'=>1, 'batch'=>$class, 'section'=>$section,
            'campus'=>$this->academicHelper->getCampus(), 'institute'=>$this->academicHelper->getInstitute(),
        ])->orderByRaw('LENGTH(gr_no) asc')->orderBy('gr_no', 'asc')->get();

        // looping for adding division into the batch name
        foreach ($classSectionStudent as $student) {
            $studentList[] = [
                'id' => $student->std_id, 'gr_no' => $student->gr_no, 'status' => $student->status,
                'name' => $student->first_name." ".$student->middle_name." ".$student->last_name
            ];
        }
        // return student list
        return $studentList;
    }
    /**
     * @return mixed
     */
    public function getAttendanceSettings()
    {
        // attendanceSettings
        $attendanceSettingProfile = $this->attendanceSetting->where([
            'institution_id' => $this->getInstituteId(),
            'campus_id' => $this->getInstituteCampusId()
        ])->first();
        // return attendance profile
        return $attendanceSettingProfile;
    }


    /////////////////  Attendance Reports //////////////////
    public function monthlyAttendanceGraph(Request $request)
    {
        $attendanceMonth = $request->input('attendance_month');
        $stdId = $request->input('std_id');
        // institute student information
        $academicYear = $this->academicHelper->getAcademicYear();
        $attendanceSettings = $this->attendanceSetting->where(['institution_id' => $this->getInstituteId(), 'campus_id' => $this->getInstituteCampusId()])->first();

        // checking Attendance Settings
        if ($attendanceSettings->subject_wise == 0) {
            $attendanceProfile = $this->attendanceViewOne;
            $stdAttendanceList = $this->attendanceViewOne-> where(['academic_year' => $academicYear,'student_id'=>$stdId]) ->whereMonth('attendance_date', '=', $attendanceMonth) ->get();
        } else {
            $attendanceProfile = $this->attendanceViewTwo;
            $stdAttendanceList = $this->attendanceViewTwo-> where(['academic_year' => $academicYear,'student_id'=>$stdId]) ->whereMonth('attendance_date', '=', $attendanceMonth) ->get();
        }

        $totalAttendance = $stdAttendanceList->count();
        $stdPresentAttendance = $attendanceProfile->attendanceSorter(1, $stdAttendanceList)->count();
        $stdAbsentAttendance = $attendanceProfile->attendanceSorter(0, $stdAttendanceList)->count();


        //$number = '1518845.756789';
        // precision count
        // $precision = 3;
        // male_present_percentage' => substr(number_format($number, $precision+1, '.', ''), 0, -1),

        // return response data
        // precision count
        $precision = 3;
        return [
            'status'=> $totalAttendance>0?'success':'failed',
            'msg'=>'',
            'total'=> $totalAttendance,
            'present'=> $stdPresentAttendance,
            'absent'=> $stdAbsentAttendance,
            'present_percentage'=> $totalAttendance>0?(substr(number_format((($stdPresentAttendance*100)/$totalAttendance), $precision+1, '.', ''), 0, -1)):0,
            'absent_percentage'=> $totalAttendance>0?substr(number_format((($stdAbsentAttendance*100)/$totalAttendance), $precision+1, '.', ''), 0, -1):100,
        ];

    }


    /////////////  get institute information from session    /////////////
    public function getAcademicYearId()
    {
        return $this->academicHelper->getAcademicYear();
    }

    public function getInstituteId()
    {
        return $this->academicHelper->getInstitute();
    }
    public function getInstituteProfile()
    {
        return $this->academicHelper->getInstituteProfile();
    }

    public function getInstituteCampusId()
    {
        return $this->academicHelper->getCampus();
    }

    public function getGradeScaleTypeId()
    {
        return $this->academicHelper->getGradingScale();
    }

    public function getAcademicSemesterId()
    {
        return 1;
    }



}
