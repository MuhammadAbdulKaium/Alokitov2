<?php

namespace Modules\Student\Http\Controllers;

use App\User;
use App;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Academics\Entities\AttendanceSetting;
use Modules\Academics\Entities\AttendanceViewOne;
use Modules\Academics\Entities\AttendanceViewTwo;
use Modules\Academics\Entities\ClassSubject;
use Modules\Academics\Entities\StudentAttendance;
use Modules\Academics\Entities\StudentAttendanceDetails;
use Modules\Student\Entities\StudentEnrollment;
use Modules\Student\Entities\StudentInformation;
use Modules\Student\Entities\StudentGuardian;
use Modules\Student\Entities\StudentParent;
use Modules\Student\Entities\StudentProfileView;
use App\Http\Controllers\Helpers\AcademicHelper;
use Redirect;
use Session;
use Validator;
use Modules\Fee\Http\Controllers\FeeInvoiceController;
use Modules\Student\Entities\StudentAttendanceFine;

class StudentParentController extends Controller
{

    private $user;
    private $classSubject;
    private $studentInformation;
    private $studentGuardian;
    private $studentParent;
    private $attendanceSetting;
    private $studentAttendance;
    private $studentAttendanceDetails;
    private $studentEnrollment;
    private $attendanceViewOne;
    private $attendanceViewTwo;
    private $academicHelper;
    private $studentProfileView;
    private $feeInvoiceController;
    private $studentAttendanceFine;


    public function __construct(User $user, StudentAttendanceFine $studentAttendanceFine, FeeInvoiceController $feeInvoiceController, ClassSubject $classSubject, StudentInformation $studentInformation, StudentEnrollment $studentEnrollment, AttendanceSetting $attendanceSetting, StudentAttendance $studentAttendance, StudentAttendanceDetails $studentAttendanceDetails, AttendanceViewOne $attendanceViewOne, AttendanceViewTwo $attendanceViewTwo,  AcademicHelper $academicHelper, StudentGuardian $studentGuardian, StudentParent $studentParent, StudentProfileView $studentProfileView)
    {
        $this->user             = $user;
        $this->classSubject             = $classSubject;
        $this->studentEnrollment        = $studentEnrollment;
        $this->studentInformation       = $studentInformation;
        $this->attendanceSetting        = $attendanceSetting;
        $this->studentAttendance        = $studentAttendance;
        $this->studentAttendanceDetails = $studentAttendanceDetails;
        $this->attendanceViewOne        = $attendanceViewOne;
        $this->attendanceViewTwo        = $attendanceViewTwo;
        $this->academicHelper        = $academicHelper;
        $this->studentGuardian        = $studentGuardian;
        $this->studentParent        = $studentParent;
        $this->studentProfileView        = $studentProfileView;
        $this->feeInvoiceController        = $feeInvoiceController;
        $this->studentAttendanceFine        = $studentAttendanceFine;
    }

    public function createStudentParent($id)
    {
        return view('student::pages.student-profile.modals.guardian-parent')->with('std_id', $id);
    }

    public function storeStudentParent(Request $request)
    {
        $stdId = $request->input('std_id');
        $gudId = $request->input('gud_id');
        // student guardian profile
        if($this->studentParent->where(['std_id'=>$stdId, 'gud_id'=>$gudId])->first()){
            return ['status'=>'failed', 'msg'=>'user already exist'];
        }else{
            // guardian profile
            $guardianProfile = $this->studentGuardian->find($gudId);
            // add this guardian as student parent
            $studentParentProfile = $this->studentParent->create([
                'gud_id'=>$gudId,
                'std_id'=>$stdId,
            ]);
            // checking
            if($studentParentProfile){
                $parents = $this->studentParent->where(['std_id'=>$stdId])->get();
                $personalInfo = $this->studentInformation->find($stdId);
                return response()->json([
                    'status'=>'success',
                    'msg'=>'guardian added',
                    'parent_count'=>$parents->count(),
                    'html' => view('student::pages.student-profile.modals.guardian-parent-list', compact('parents', 'personalInfo'))->render()
                ]);
            }else{
                return ['status'=>'failed', 'msg'=>'unable to add guardian'];
            }
        }
    }

    // manage parent
    public function manageParent(Request $request)
    {
        // checking request_type
        if($request->input('request_type')=='search'){
            // query maker
            $qry = [
                'academic_year'=>$request->input('academic_year'),
                'academic_level'=>$request->input('academic_level'),
                'batch'=>$request->input('batch'),
                'section'=>$request->input('section'),
            ];

            // find parent list
            $studentList = $this->studentProfileView->where($qry)->get();
            // return view with variabale
            return View('student::pages.includes.parent-manage', compact('studentList'));
        }else{
            // Academic year
            $academicYears = $this->academicHelper->getAllAcademicYears();
            // return view with vaiables
            return View('student::pages.parent-manage', compact('academicYears'));
        }
    }



    // student profile guardian/parent energency
    public function setEmergencyParent($stdId, $gudId)
    {
        if($allStdParents = $this->studentParent->where(['std_id'=>$stdId])->get()){
            // looping
            foreach ($allStdParents as $parent) {
                //checking
                if ($parent->gud_id == $gudId) {
                    // update emergency
                    $parent->is_emergency = 1;
                    // save update
                    $guardianUpdated = $parent->save();
                } else {
                    // update emergency
                    $parent->is_emergency = 0;
                    // save update
                    $guardianUpdated = $parent->save();
                }
            }
            // success msg
            Session::flash('success', 'Emergency Contact Changed');
            // return back
            return redirect()->back();
        } else {
            // warning msg
            Session::flash('warning', 'Unable to set emergency contact');
            // return back
            return redirect()->back();
        }
    }

    // show attendance list
    public function showChildAttendanceByParent($parentId)
    {
        // guardian profile
        $guardianProfile = $this->studentGuardian->findOrFail($parentId);
        // checking
        if($guardianProfile){
            // student profile
            $studentProfile = null;
            // student list
            $studentList = $guardianProfile->students();
            $semesterList = $this->academicHelper->getAcademicSemester();
            // return view with variables
            return view('academics::manage-attendance.attendance-parent', compact('studentProfile', 'studentList', 'semesterList'));
        }else{
            abort(404);
        }
    }


    public function  showChildFeesInfoByParent($parentId){
        // guardian profile
        $guardianProfile = $this->studentGuardian->findOrFail($parentId);
        // student list
        $studentList = $guardianProfile->students();
        return view('fees::fees-parent.fees_invoice_info',compact('studentList'));
    }

    public function  showChildAllFeesInvoice(Request $request){
        $studentId=$request->input('std_id');
        (object) $inviceByYearMonth= $this->feeInvoiceController->singleStudentAllInvoice($studentId);

        // attendance Fine List
        $attendanceFineList=$this->studentAttendanceFine->where('std_id',$studentId)->orderBy('id','desc')->get();
        if(is_array($attendanceFineList)) { 
            if(!empty($attendanceFineList)) {
                foreach ($attendanceFineList as $fine) {
                    $yearMonth = date('Y-m', strtotime($fine->date));
                    $attenFineByYearMonth[$yearMonth][] = $fine;
                }
            } else {
                $attenFineByYearMonth=0;
            }
        } else {
            $attenFineByYearMonth=0;
        }
        return view('fees::fees-parent.invoice_list', compact('inviceByYearMonth','attenFineByYearMonth'));
    }





    // show attendance list
    public function showChildReportCardByParent($parentId)
    {
        // guardian profile
        $guardianProfile = $this->studentGuardian->findOrFail($parentId);
        // checking
        if($guardianProfile){
            // student profile
            $studentProfile = null;
            // student list
            $studentList = $guardianProfile->students();
            // return view with variables
            return view('academics::manage-assessments.report-card-parent', compact('studentList','studentProfile'));
        }else{
            abort(404);
        }

    }



}
