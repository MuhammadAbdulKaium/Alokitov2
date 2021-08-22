<?php

namespace Modules\Student\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Student\Entities\StudentProfileView;
use Modules\Student\Entities\StudentEnrollment;
use Modules\Student\Entities\StdEnrollHistory;
use Redirect;
use Session;
use Validator;
use Modules\Academics\Entities\AcademicsLevel;

class StudentPromotionController extends Controller
{
    private $carbon;
    private $academicHelper;
    private $studentProfileView;
    private $studentEnrollment;
    private $stdEnrollHistory;
    private $academicsLevel;

    // constructor
    public function __construct(Carbon $carbon, AcademicHelper $academicHelper, StudentProfileView $studentProfileView, AcademicsLevel $academicsLevel, StudentEnrollment $studentEnrollment, StdEnrollHistory $stdEnrollHistory)
    {
        $this->carbon = $carbon;
        $this->academicHelper = $academicHelper;
        $this->studentProfileView = $studentProfileView;
        $this->studentEnrollment = $studentEnrollment;
        $this->stdEnrollHistory = $stdEnrollHistory;
        $this->academicsLevel = $academicsLevel;
    }


    ///////////////////////////////////  dropout  ////////////////////////

    public function dropoutStudent($historyId)
    {
        // find history profile
        $historyProfile = $this->stdEnrollHistory->find($historyId);
        // return view with all variable
        return view('student::pages.student-profile.modals.academic-apply-dropout', compact('historyProfile'));
    }

    public function confirmStudentDropout(Request $request)
    {
        // checking request details
        $stdId = $request->input('std_id');
        $remark = $request->input('remark');
        $historyId = $request->input('history_id');

        // std enroll profile
        $stdEnrollProfile = $this->studentEnrollment->where(['std_id'=>$stdId, 'enroll_status'=>'IN_PROGRESS'])->first();
        // update stdEnrollProfile
        $stdEnrollProfile->enroll_status = 'DROPOUT';
        $stdEnrollProfile->batch_status = 'DROPOUT';
        $stdEnrollProfile->remark = $remark;
        // save enroll profile
        $stdEnrollProfileSaved = $stdEnrollProfile->save();
        // checking
        if($stdEnrollProfileSaved){
            // find old enrollment history
            $oldEnrollmentProfile = $stdEnrollProfile->history('IN_PROGRESS');
            // update $oldEnrollmentProfile
            $oldEnrollmentProfile->batch_status = 'DROPOUT';
            $oldEnrollmentProfile->remark = $remark;
            // save $oldEnrollmentProfile and checking
            if($oldEnrollmentProfile->save()){
                // return
                return ['status'=>'success', 'h_id'=>$historyId, 'msg'=>'Student Academic Batch Status changed to DROPOUT'];
            }else{
                // return
                return ['status'=>'failed', 'msg'=>'unable to add new enroll history'];
            }
        }else{
            // return
            return ['status'=>'failed', 'msg'=>'unable to update enroll'];
        }
    }

    ///////////////////////////////////  promotion  ////////////////////////

    public function promoteStudent(Request $request)
    {
        // promo action type
        $promoteAction = $request->input('promo_action_type');
        // Selected student list
        $promoStdList = $request->input('std_list');
        // checking promo action type
        if($promoteAction=='PROMOTE' || $promoteAction=='REPEAT'){
            $academicYear = $request->input('academic_year');
            $academicLevel = $request->input('academic_level');
            $academicBatch = $request->input('batch');
            $academicSection = $request->input('section');
            // institute and campus
            $campus = $this->academicHelper->getCampus();
            $institute = $this->academicHelper->getInstitute();

            // promo std. looping
            foreach ($promoStdList as $stdId=>$grNo){
                // std enroll profile
                $stdEnrollProfile = $this->studentEnrollment->where(['std_id'=>$stdId, 'enroll_status'=>'IN_PROGRESS'])->first();
                // update stdEnrollProfile
                $stdEnrollProfile->gr_no = $grNo;
                $stdEnrollProfile->academic_year = $academicYear;
                $stdEnrollProfile->academic_level = $academicLevel;
                $stdEnrollProfile->batch = $academicBatch;
                $stdEnrollProfile->section = $academicSection;
                $stdEnrollProfile->enroll_status = 'IN_PROGRESS';
                $stdEnrollProfile->batch_status = 'IN_PROGRESS';
                // save enroll profile
                $stdEnrollProfileSaved = $stdEnrollProfile->save();
                // checking
                if($stdEnrollProfileSaved){
                    // find old enrollment history
                    $oldEnrollmentProfile = $stdEnrollProfile->history('IN_PROGRESS');
                    // update $oldEnrollmentProfile
                    $oldEnrollmentProfile->batch_status = $promoteAction=='PROMOTE'?'LEVEL_UP':'REPEATED';
                    // save $oldEnrollmentProfile
                    $oldEnrollmentProfileSaved = $oldEnrollmentProfile->save();
                    // checking
                    if($oldEnrollmentProfileSaved){
                        // new enroll history
                        $enrollHistoryProfile = new $this->stdEnrollHistory();
                        // input enroll history details
                        $enrollHistoryProfile->enroll_id = $stdEnrollProfile->id;
                        $enrollHistoryProfile->gr_no = $grNo;
                        $enrollHistoryProfile->academic_year = $academicYear;
                        $enrollHistoryProfile->academic_level = $academicLevel;
                        $enrollHistoryProfile->batch = $academicBatch;
                        $enrollHistoryProfile->section = $academicSection;
                        $enrollHistoryProfile->enrolled_at = date('Y-m-d', strtotime($this->carbon->now()));
                        $enrollHistoryProfile->batch_status = 'IN_PROGRESS';
                        // save enrollment history details
                        $enrollHistorySaved = $enrollHistoryProfile->save();
                    }else{
                        return 'unable to update old enroll history';
                    }

                }else{
                    return 'unable to update enroll';
                }
            }

            // student list
            $studentList = $this->studentProfileView->where([
                'academic_year'=>$academicYear, 'academic_level'=>$academicLevel, 'batch'=>$academicBatch,
                'section'=>$academicSection, 'campus'=>$campus, 'institute'=>$institute,
            ])->get();
            // academics years
            $academicYears = $this->academicHelper->getAllAcademicYears();
            $academicLevels=$this->academicsLevel->where([
                'institute_id'=>$this->academicHelper->getInstitute(),
                'campus_id'=>$this->academicHelper->getCampus()
            ])->get();
            // return view with variables
            Session::flash('success', count($promoStdList).' - Records saved successfully.');

            // return view with all variable
            return view('student::pages.enrollment.promote-student', compact('academicLevels', 'studentList', 'promoStdList'));

        }else{
            $graduateYear = $request->input('graduate_year');
            $graduateMonth = $request->input('graduate_month');

            $graduateCount=0;
            // promo std. looping
            foreach ($promoStdList as $stdId=>$grNo){
                // std inform profile
                $stdinfoProfile = $this->academicHelper->findStudent($stdId);
                // update  student status
                $stdinfoProfile->status=0;
                // save student information
                if($stdinfoProfile->save()) {
                    // std enroll profile
                    $stdEnrollProfile = $this->studentEnrollment->where(['std_id'=>$stdId, 'enroll_status'=>'IN_PROGRESS'])->first();
                    // update stdEnrollProfile
                    $stdEnrollProfile->enroll_status = 'GRADUATED';
                    $stdEnrollProfile->batch_status = 'GRADUATED';
                    // save enroll profile
                    $stdEnrollProfileSaved = $stdEnrollProfile->save();
                    // checking
                    if($stdEnrollProfileSaved){
                        // find old enrollment history
                        if($oldEnrollmentProfile = $stdEnrollProfile->history('IN_PROGRESS')){
                            // update $oldEnrollmentProfile
                            $oldEnrollmentProfile->enroll_status = $promoteAction=='GRADUATED';
                            $oldEnrollmentProfile->batch_status = $promoteAction=='GRADUATED';
                            // save $oldEnrollmentProfile
                            $oldEnrollmentProfile->save();
                        }
                        $graduateCount+=1;
                    }else{
                        return 'unable to update enroll';
                    }
                } else {
                    return 'unable to update student information (status)';
                }

                // checking
                if($graduateCount==count($promoStdList)){
                    // return view with variables
                    Session::flash('success', count($promoStdList).' - Records saved successfully.');

                    // return view with all variable
                    return redirect('/student/promote');
                }else{

                }

            }


        }
    }


    public function confirmStudent(Request $request)
    {
        // academics years
        $academicYears = $this->academicHelper->getAllAcademicYears();
        // Preview Selected Details
        $academicYear = $this->academicHelper->findYear($request->input('academic_year'));
        $academicLevel = $this->academicHelper->findLevel($request->input('academic_level'));
        $academicBatch = $this->academicHelper->findBatch($request->input('batch'));
        $academicSection = $this->academicHelper->findSection($request->input('section'));
        // Selected student list
        $promoStdList = $request->input('std_list');
        $promoteAction = $request->input('promote_action');
        // student list
        $studentList = $this->studentProfileView->where([
//            'academic_year'=>$request->input('academic_year'),
            'academic_level'=>$request->input('academic_level'),
            'batch'=>$request->input('batch'),
            'section'=>$request->input('section'),
        ])->get();

        // return view with variables
        return view('student::pages.enrollment.promote-confirm', compact('studentList', 'promoStdList','promoteAction', 'academicYears','academicYear', 'academicLevel', 'academicBatch', 'academicSection'));
    }

    ///////////////////////////////////  students  ////////////////////////

    public function index()
    {
        $studentList = null;
        $promoStdList = null;
        // academics years
        $academicYears = $this->academicHelper->getAllAcademicYears();
        $academicLevels=$this->academicsLevel->where([
            'institute_id'=>$this->academicHelper->getInstitute(),
            'campus_id'=>$this->academicHelper->getCampus()
        ])->get();
        // return view with all variable
        return view('student::pages.enrollment.promote-student', compact('academicYears', 'studentList', 'promoStdList','academicLevels'));
    }

    // search employee
    public function searchStudent(Request $request)
    {
        $academicYear  = $request->input('academic_year');
        $academicLevel = $request->input('academic_level');
        $batch         = $request->input('batch');
        $section       = $request->input('section');
        $grNo          = $request->input('gr_no');
        $email         = $request->input('email');

        // all search inputs
        $allSearchInputs = array();

        // check academicYear
        if ($academicYear) {
            $allSearchInputs['academic_year'] = $academicYear;
        }
        // check academicLevel
        if ($academicLevel) {
            $allSearchInputs['academic_level'] = $academicLevel;
        }
        // check batch
        if ($batch) {
            $allSearchInputs['batch'] = $batch;
        }
        // check section
        if ($section) {
            $allSearchInputs['section'] = $section;
        }
        // check grNo
        if ($grNo) {
            $allSearchInputs['gr_no'] = $grNo;
        }
        // check email
        if ($email) {
            $allSearchInputs['email'] = $email;
        }
        // search result
        $studentList = $this->studentProfileView->where($allSearchInputs)->orderByRaw('LENGTH(gr_no) asc')->orderBy('gr_no', 'asc')->get();
        // checking
        if ($studentList) {
            // return allStudent
            return view('student::pages.enrollment.modals.promote-student', compact('studentList'));
        } else {
            // return redirect
            return ['status'=>'failed', 'msg'=>'unable to perform the action'];
        }

    }


}
