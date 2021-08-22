<?php

namespace Modules\Student\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Academics\Entities\AcademicsYear;
use Modules\Student\Entities\StudentInformation;
use Modules\Student\Entities\StudentProfileView;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Student\Entities\StudentTestimonialResult;

class StudentTestimonialResultController extends Controller
{


    private $academicHelper;
    private $academicsYear;
    private $studentProfileView;
    private $studentInformation;
    private $studentTestimonialResult;

    public function __construct(AcademicHelper $academicHelper,  StudentProfileView $studentProfileView, AcademicsYear $academicsYear, StudentInformation $studentInformation, StudentTestimonialResult $studentTestimonialResult)
    {
        $this->academicHelper = $academicHelper;
        $this->academicsYear = $academicsYear;
        $this->studentProfileView = $studentProfileView;
        $this->studentInformation = $studentInformation;
        $this->studentTestimonialResult = $studentTestimonialResult;
    }



    // student manage result

    public function  manageStudentResult() {

        // Academic year
        $academicYears = $this->academicsYear->where([
            'institute_id'=>$this->academicHelper->getInstitute(),
            'campus_id'=>$this->academicHelper->getCampus()
        ])->get();
        // all inputs
        $allInputs = array('year' => null, 'level' => null, 'batch' => null, 'section' => null, 'gr_no' => null, 'email' => null);
        // return view with vaiables
        return View('student::pages.student-testimonial-result-manage', compact('academicYears', 'allInputs'))->with('allEnrollments', null);

    }

    // student manage result search
    public function searchStudentResult(Request $request)
    {
        $campusId  = $request->input('institute');
        $instituteId  = $request->input('campus');
        $academicYear  = $request->input('academic_year');
        $academicLevel = $request->input('academic_level');
        $batch         = $request->input('batch');
        $section       = $request->input('section');
        $grNo          = $request->input('gr_no');
        $email         = $request->input('email');
        $returnType    = $request->input('return_type', 'view');

        // qry
        $allSearchInputs = array();

        // checking return type
        if($returnType=="json"){
            // input institute and campus id
            $allSearchInputs['campus'] = $campusId;
            $allSearchInputs['institute'] = $instituteId;
        }else{
            // input institute and campus id
            $allSearchInputs['campus'] = $this->academicHelper->getCampus();
            $allSearchInputs['institute'] = $this->academicHelper->getInstitute();
        }

        // check academicYear
        if ($academicYear) $allSearchInputs['academic_year'] = $academicYear;
        // check academicLevel
        if ($academicLevel) $allSearchInputs['academic_level'] = $academicLevel;
        // check batch
        if ($batch) $allSearchInputs['batch'] = $batch;
        // check section
        if ($section) $allSearchInputs['section'] = $section;
        // check grNo
        if ($grNo) $allSearchInputs['gr_no'] = $grNo;
        // check email
        if ($email) $allSearchInputs['email'] = $email;

        $resultType= $request->input('result_type');

        // checking
        if($returnType=="json"){
            // return with variables
            return $this->studentProfileView->where($allSearchInputs)->orderByRaw('LENGTH(gr_no) asc')->orderBy('gr_no', 'asc')->get();
        }else{
            // search result
            $allEnrollments = $this->studentProfileView->where($allSearchInputs)->orderByRaw('LENGTH(gr_no) asc')->orderBy('gr_no', 'asc')->get();
            // checking
            if ($allEnrollments) {
//                return $allSearchInputs;
                // std list view maker
                $stdListView = view('student::pages.includes.student-testimonial-result-list', compact('allEnrollments','allSearchInputs','resultType'))->render();
                // return with variables
                return ['status'=>'success', 'msg'=>'Student List found', 'html'=>$stdListView];
            } else {
                return ['status'=>'failed', 'msg'=>'No Records found'];
            }
        }
    }

    public function  StudentResultStore(Request $request) {


         $result_type=$request->input('result_type');
        $count=count($request->input('std_id'));
         $std_ids=$request->input('std_id');
        $gpa=$request->input('gpa');
        $gpa_details=$request->input('gpa_details');
        $reg_no=$request->input('reg_no');
        $year=$request->input('year');
//        return $std_ids[0];

        for($i=0;$i<$count;$i++) {

            // check student Testimonal Result Profile
            $studentTestimonialProfile=$this->studentTestimonialResult->where('std_id',$std_ids[$i])->where('result_type',$result_type[$std_ids[$i]])->first();

            if(empty($studentTestimonialProfile)) {
                $studentResult = new $this->studentTestimonialResult;
                $studentResult->std_id = $std_ids[$i];
                $studentResult->result_type = $result_type[$std_ids[$i]];
                $studentResult->gpa = $gpa[$std_ids[$i]];
                $studentResult->gpa_details = $gpa_details[$std_ids[$i]];
                $studentResult->reg_no = $reg_no[$std_ids[$i]];
                $studentResult->year = $year[$std_ids[$i]];
                $studentResultSave = $studentResult->save();
            } else {
                $studentTestimonialProfile->std_id = $std_ids[$i];
                $studentTestimonialProfile->result_type = $result_type[$std_ids[$i]];
                $studentTestimonialProfile->gpa = $gpa[$std_ids[$i]];
                $studentTestimonialProfile->gpa_details = $gpa_details[$std_ids[$i]];
                $studentTestimonialProfile->reg_no = $reg_no[$std_ids[$i]];
                $studentTestimonialProfile->year = $year[$std_ids[$i]];
                $studentResultSave = $studentTestimonialProfile->save();
            }

        }

        return "success";
    }



}
