<?php

namespace Modules\Student\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\Setting\Entities\CadetAssessmentActivity;
use Modules\Setting\Entities\CadetPerformanceActivity;
use Modules\Setting\Entities\CadetPerformanceActivityPoint;
use Modules\Setting\Entities\CadetPerformanceCategory;
use Modules\Student\Entities\CadetAssesment;
use Modules\Student\Entities\StudentInformation;
use Modules\Student\Entities\StudentEnrollment;
use Modules\Student\Entities\StdEnrollHistory;
use App\Http\Controllers\Helpers\AcademicHelper;


class StudentEnrollController extends Controller
{
    private $enrollHistory;
    private $studentEnrollment;
    private $studentInformation;
    private $academicHelper;

    // construct method
    public function __construct(StudentInformation $studentInformation, StudentEnrollment $studentEnrollment, StdEnrollHistory $enrollHistory, AcademicHelper $academicHelper)
    {
        $this->enrollHistory= $enrollHistory;
        $this->studentEnrollment= $studentEnrollment;
        $this->studentInformation= $studentInformation;
        $this->academicHelper = $academicHelper;
    }

    // get student academic information
    public function getStudentAcademics($id)
    {
        // student information
        $personalInfo = $this->studentInformation->findOrFail($id);
        // return view with variables
        return view('student::pages.student-profile.student-academic', compact('personalInfo'))->with('page', 'academics');
    }
    public function getStudentAcademics2($id)
    {
        // student information
        $personalInfo = $this->studentInformation->findOrFail($id);
        $activity = CadetPerformanceActivity::where('cadet_category_id', 19)->get();
        $academics=CadetAssesment::where('student_id',$id)
            ->where('type',19)
            ->orderBy('date', 'DESC')
            ->get();
        // return view with variables
        return view('student::pages.student-profile.student-academic3', compact('personalInfo','academics', 'activity'))->with('page', 'academics')->with('std_id',$id);
    }

    public function getStudentAcademicsSubject($id,$item){
        $personalInfo = $this->studentInformation->findOrFail($id);
        $examDeatils=CadetAssessmentActivity::where('assessment_id',$item)->get();
        return view('student::pages.student-profile.modals.student-exam-subject',compact('examDeatils','personalInfo'));
    }
    public function getStudentPsychologyView($id,$item)
    {
        $personalInfo = $this->studentInformation->findOrFail($id);
        $examDeatils=CadetAssessmentActivity::where('assessment_id',$item)->get();
        return view('student::pages.student-profile.modals.student-exam-subject',compact('examDeatils','personalInfo'));
    }
    public function getStudentAcademicsEntry($id)
    {
        $personalInfo = $this->studentInformation->findOrFail($id);
        $performanceType=CadetPerformanceCategory::findOrFail(19);
        $activities=CadetPerformanceActivity::where('cadet_category_id','19')->get();
        $activity=1;
        $value=CadetPerformanceActivityPoint::where('cadet_performance_activity',$activity)->get();
        // return view with variables
        return view('student::pages.student-profile.student-academic-entry', compact('personalInfo','performanceType','activities','value'))->with('page', 'academics')->with('std_id', $id);
    }

    public function getStudentAcademicPoint(Request $request){
        $activity=1;
        $value=CadetPerformanceActivityPoint::where('cadet_performance_activity',$activity)->get();

    }
    
    // get student course enroll page
    public function courseEnroll()
    {
        // return view with variables
        return view('student::pages.student-profile.modals.academic-course-enroll');
    }

    // get student course edit page
    public function editEnroll($id)
    {
        // find student enroll profile
        $enrollProfile =$this->studentEnrollment->find($id);
        // academic level list
        $allAcademicsLevel = $this->academicHelper->getAllAcademicLevel();
        // return view with variables
        return view('student::pages.student-profile.modals.academic-course-enroll-update', compact('enrollProfile', 'allAcademicsLevel'));
    }


    // update academic info
    public function updateEnroll(Request $request)
    {

        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'std_id' => 'required', 'enroll_id' => 'required', 'academic_year' => 'required',
            'academic_level' => 'required', 'batch' => 'required', 'section' => 'required|max:100', 'gr_no' => 'required|max:100',
        ]);

        // storing requesting input data
        if ($validator->passes()) {
            // request details
            $grNo = $request->input('gr_no');
            $stdId = $request->input('std_id');
            $enrollId = $request->input('enroll_id');
            $year = $request->input('academic_year');
            $level = $request->input('academic_level');
            $batch = $request->input('batch');
            $section = $request->input('section');
            // Start transaction!
            DB::beginTransaction();

            // find student enrollment profile
            if($stdEnroll = $this->studentEnrollment->find($enrollId)){
                // update student enrollment profile
                $stdEnroll->gr_no = $grNo;
                $stdEnroll->academic_year = $year;
                $stdEnroll->academic_level = $level;
                $stdEnroll->batch = $batch;
                $stdEnroll->section = $section;
                // save and checking
                if($stdEnroll->save()){
                    // find student enrollment history
                    $stdEnrollHistory = $stdEnroll->history('IN_PROGRESS');
                    // update enroll history
                    $stdEnrollHistory->gr_no = $grNo;
                    $stdEnrollHistory->academic_year = $year;
                    $stdEnrollHistory->academic_level = $level;
                    $stdEnrollHistory->batch = $batch;
                    $stdEnrollHistory->section = $section;
                    // save enroll history
                    if($stdEnrollHistory->save()){
                        // If we reach here, then data is valid and working. Commit the queries!
                        DB::commit();
                        // return with success msg
                        return ['status'=> true, 'msg'=>'Academic Information Updated Successfully !!!!!'];
                    }else{
                        // Rollback and then redirect back to form with errors
                        DB::rollback();
                        // return with failed msg
                        return ['status'=> false, 'msg'=>'Academic Information Updated Successfully !!!!!'];
                    }
                }else{
                    // Rollback and then redirect back to form with errors
                    DB::rollback();
                    // return with failed msg
                    return ['status'=> false, 'msg'=>'Academic Information Updated Successfully !!!!!'];
                }
            }else{
                // return with failed msg
                return ['status'=> false, 'msg'=>'Academic Information Updated Successfully !!!!!'];
            }
        }else{
            // return with failed msg
            return ['status'=> false, 'msg'=>'Invalid Information'];
        }
    }

    // get student course info page
    public function courseInfo()
    {
        return view('student::pages.student-profile.modals.academic-course-info');
    }

    // get student course info edit page
    public function courseInfoEdit()
    {
        return view('student::pages.student-profile.modals.academic-course-info-update');
    }
    // get student batch info page
    public function batchInfo()
    {
        return view('student::pages.student-profile.modals.academic-batch-info');
    }

}
