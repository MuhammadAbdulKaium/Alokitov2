<?php

namespace Modules\Admission\Http\Controllers;

use App;
use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Admission\Entities\ApplicantGrade;
use Modules\Admission\Entities\ApplicantMeritBatch;
use Modules\Admission\Entities\ApplicantResult;
use Modules\Admission\Entities\ApplicantExamSetting;
use Modules\Admission\Entities\ApplicantManageView;
use Modules\Admission\Entities\ApplicantUser;
use Modules\Student\Http\Controllers\StudentInfoController;
use Modules\Student\Http\Controllers\StudentAddressController;
use Modules\Student\Entities\StudentInformation;
use Modules\Student\Entities\StudentEnrollment;
use Modules\Student\Entities\StdEnrollHistory;
use Redirect;
use Session;
use Validator;
Use App\Models\Role;
Use App\User;
Use App\UserInfo;
use Carbon\Carbon;
use Modules\Student\Http\Controllers\StudentGuardController;
use Modules\Academics\Entities\AcademicsYear;


class ApplicantAssessmentController extends Controller
{
    private $academicHelper;
    private $applicant;
    private $applicantView;
    private $applicantGrade;
    private $applicantResult;
    private $examSetting;
    private $batch;
    private $studentInformation;
    private $studentEnrollment;
    private $stdEnrollHistory;
    private $studentInfoController;
    private $role;
    private $user;
    private $userInfo;
    private $carbon;
    private $studentGuardController;
    private $studentAddressController;
    private $academicsYear;

    // constructor
    public function __construct(AcademicHelper $academicHelper, ApplicantManageView $applicantView, ApplicantUser $applicant, ApplicantGrade $applicantGrade, ApplicantResult $applicantResult, ApplicantExamSetting $examSetting, ApplicantMeritBatch $batch,StudentInformation $studentInformation, StudentEnrollment $studentEnrollment, StdEnrollHistory $stdEnrollHistory, StudentInfoController $studentInfoController, Role $role, User $user, UserInfo $userInfo, Carbon $carbon, StudentGuardController $studentGuardController, StudentAddressController $studentAddressController, AcademicsYear $academicsYear)
    {
        $this->academicHelper = $academicHelper;
        $this->applicant      = $applicant;
        $this->applicantView  = $applicantView;
        $this->applicantGrade = $applicantGrade;
        $this->applicantResult = $applicantResult;
        $this->examSetting = $examSetting;
        $this->batch = $batch;
        $this->studentInformation = $studentInformation;
        $this->studentEnrollment = $studentEnrollment;
        $this->stdEnrollHistory = $stdEnrollHistory;
        $this->studentInfoController = $studentInfoController;
        $this->role = $role;
        $this->user = $user;
        $this->userInfo = $userInfo;
        $this->carbon = $carbon;
        $this->studentGuardController = $studentGuardController;
        $this->studentAddressController = $studentAddressController;
        $this->academicsYear = $academicsYear;
    }

    // index page
    public function index()
    {
        // academic year list
        $academicYears =  $this->academicsYear->where([
            'institute_id'=>$this->academicHelper->getInstitute(),
            'campus_id'=>$this->academicHelper->getCampus(),
        ])->get();
        // return view with variables
        return view('admission::admission-assessment.grade-book', compact('academicYears'))->with('page', 'grade-book');
    }
    // others page
    public function getPage($pageId)
    {
        // academic year list
        $academicYears =  $this->academicsYear->where([
            'institute_id'=>$this->academicHelper->getInstitute(),
            'campus_id'=>$this->academicHelper->getCampus(),
        ])->get();

        // switch to page
        switch ($pageId) {
            case 'grade-book':
                return view('admission::admission-assessment.grade-book', compact('academicYears'))->with('page', 'grade-book');
                break;
            case 'result':
                $admitApplicantList = null;
                $approvedApplicantList = null;
                return view('admission::admission-assessment.result', compact('academicYears', 'admitApplicantList', 'approvedApplicantList'))->with('page', 'result');
                break;
            case 'setting':
                return view('admission::admission-assessment.setting', compact('academicYears'))->with('page', 'setting');
                break;
            case 'reports':
                return view('admission::admission-assessment.reports', compact('academicYears'))->with('page', 'reports');
                break;
            default:
                return view('admission::admission-assessment.grade-book', compact('academicYears'))->with('page', 'grade-book');
        }
    }

    public function manageResult(Request $request)
    {
        // sql qry
        $qry = [
            'academic_year'  => $request->input('academic_year'),
            'academic_level' => $request->input('academic_level'),
            'batch'          => $request->input('batch'),
            'campus_id'      => $this->academicHelper->getCampus(),
            'institute_id'   => $this->academicHelper->getInstitute(),
        ];
        // exam settings
        if($examSettingProfile = $this->examSetting->where($qry)->first()){
            $examTaken = $examSettingProfile->exam_taken;
        }else{
            $examTaken = null;
        }

        // request type
        $actionType = $request->input('request_type', 'list');
        // checking request type
        if($actionType=='generate'){
            // generate applicant result sheet
            // passed and failed list
            $passedList = array();
            $failedList = array();

            // batch exam setting
            $examSetting = $this->examSetting->where($qry)->first();
            // setting details
            // $examMark = $examSetting->exam_marks;
            $examPassingMark = $examSetting->exam_passing_marks;
            // batch applicant list
            $applicantGradeList = $this->applicantGrade->where($qry)->orderBy('applicant_grade', 'DESC')->get();

            // applicant grade loop counter
            $applicantLoopCounter = 0;
            // applicant grade looping
            foreach ($applicantGradeList as $gradeList){
                $applicantId = $gradeList->applicant_id;
                $applicantGrade = $gradeList->applicant_grade;
                // new exam result
                $applicantResult = new $this->applicantResult();
                // checking applicant grade
                if($applicantGrade>=$examPassingMark){
                    $passedList[] = $applicantId;
                    // result details
                    $applicantResult->applicant_exam_result = 1;
                    $applicantResult->applicant_merit_type = 'Undefined';
                }else{
                    $failedList[] = $applicantId;
                    // result details
                    $applicantResult->applicant_exam_result = 0;
                    $applicantResult-> 	applicant_merit_position = 0;
                    $applicantResult->applicant_merit_type = 'FAILED';
                }

                // input or update grade details
                $applicantResult->applicant_id    = $applicantId; // applicant id
                $applicantResult->academic_year   = $request->input('academic_year');
                $applicantResult->academic_level  = $request->input('academic_level');
                $applicantResult->batch           = $request->input('batch');
                $applicantResult->campus_id       = $this->academicHelper->getCampus();
                $applicantResult->institute_id    = $this->academicHelper->getInstitute();
                // save applicant Result and checking
                if($applicantResult->save()){
                    $applicantLoopCounter += 1;
                }
            }

            // update applicant result with merit position
            // passed applicant looping
            for($i=0; $i<count($passedList); $i++){
                // applicant result profile
                $this->applicantResult->where([
                    'applicant_id'   => $passedList[$i],
                    'campus_id'      => $this->academicHelper->getCampus(),
                    'institute_id'   => $this->academicHelper->getInstitute(),
                ])->update(['applicant_merit_position' => ($i+1)]);
            }

            // merit batch profile
            $meritBatchProfile = new $this->batch();
            // input merit batch details
            $meritBatchProfile->merit_batch     = 1;
            $meritBatchProfile->academic_year   = $request->input('academic_year');
            $meritBatchProfile->academic_level  = $request->input('academic_level');
            $meritBatchProfile->batch           = $request->input('batch');
            $meritBatchProfile->campus_id       = $this->academicHelper->getCampus();
            $meritBatchProfile->institute_id    = $this->academicHelper->getInstitute();
            // save merit batch
            $meritBatchProfile->save();

            // generate applicant result sheet
            $applicantResultSheet = $this->applicantResult->where($qry)->get();
            // return applicant result sheer
            return view('admission::admission-assessment.modals.result-list', compact('applicantResultSheet', 'examTaken'));
        }else{
            // generate applicant result sheet
            $applicantResultSheet = $this->applicantResult->where($qry)->get();
            // show applicant result sheet
            return view('admission::admission-assessment.modals.result', compact('applicantResultSheet', 'examTaken'));
        }
    }

    // get applicant result sheet
    public function getResultSheet(Request $request)
    {
        // request list type
        $request_list_type = $request->input('request_list_type');
        $myPage = $request->input('my_page');
        // sql qry
        $qry = [
            'academic_year'  => $request->input('academic_year'),
            'academic_level' => $request->input('academic_level'),
            'batch'          => $request->input('batch'),
            'campus_id'      => $this->academicHelper->getCampus(),
            'institute_id'   => $this->academicHelper->getInstitute(),
        ];
        // batch list
        $batchList = $this->batch->where($qry)->first();
        // checking type as all applicant list or not
        if($request_list_type != 'LIST'){
            // checking list as passed or not
            if($request_list_type != 'PASSED'){
                $qry['applicant_merit_type'] = $request_list_type;
            }

            // checking list as failed or not
            if($request_list_type == 'FAILED'){
                $qry['applicant_exam_result']=0;
            }else{
                $qry['applicant_exam_result']=1;
            }
        }

        // generate applicant result sheet
        $applicantResultSheet = $this->applicantResult->where($qry)->get();
        return view('admission::admission-assessment.modals.result-'.strtolower($request_list_type), compact('applicantResultSheet', 'batchList', 'myPage'));
    }

    // download result sheet
    public function downloadResultSheet(Request $request)
    {
        // sql qry
        $qry = [
            'academic_year'  => $request->input('academic_year'),
            'academic_level' => $request->input('academic_level'),
            'batch'          => $request->input('batch'),
            'campus_id'      => $this->academicHelper->getCampus(),
            'institute_id'   => $this->academicHelper->getInstitute(),
        ];
        // report details
        $listType = $request->input('request_list_type');
        // institute information
        $instituteInfo = $this->academicHelper->getInstituteProfile();
        // exam details
        $examDetails = $this->examSetting->where($qry)->first();
        // academic information
        $academicInfo = [
            'year_name'  => $this->academicHelper->findYear($request->input('academic_year'))->year_name,
            'level_name'  => $this->academicHelper->findLevel($request->input('academic_level'))->level_name,
            'batch_name'  => $this->academicHelper->findBatch($request->input('batch'))->batch_name,
            'list_type'  => $listType
        ];

        // exam result type
        if($listType !='ALL'){
            $qry['applicant_exam_result'] = ($listType =='FAILED'?0:1);
        }
        // set applicant exam result type for passed list
        if($listType =='PASSED'){
            if($request->input('request_report_type') != "PASSED"){
                $qry['applicant_merit_type'] = $request->input('request_report_type');
            }
        }elseif ($listType=='FAILED'){
//            $qry['applicant_merit_type'] =$listType;

        }elseif ($listType=='MERIT'){
            $qry['applicant_merit_type'] =$listType;

        }elseif ($listType=='WAITING'){
            $qry['applicant_merit_type'] =$listType;
            $qry['applicant_merit_batch'] = $request->input('request_merit_batch');
            $academicInfo['batch_type']= $request->input('request_merit_batch');

        }elseif ($listType=='APPROVED'){
            $qry['applicant_merit_type'] =$listType;

        }elseif ($listType=='DISAPPROVED'){
            $qry['applicant_merit_type'] =$listType;

        }else{

        }

        // making $academicInfo as object
        $academicInfo = (object)$academicInfo;
        // passed applicant result sheet
        $applicantResultSheet = $this->applicantResult->where($qry)->get();
        // share all variables with the view
        view()->share(compact('instituteInfo', 'applicantResultSheet', 'examDetails', 'academicInfo'));

        // generate pdf
        $pdf = App::make('dompdf.wrapper');
        // load view
        $pdf->loadView('admission::admission-assessment.reports.report-result-'.strtolower($listType).'-list')->setPaper('a4', 'portrait');
        return $pdf->stream();
        //return $pdf->download(strtolower($listType).'_applicant_list.pdf');
    }





    public function promoteApplicant(Request $request)
    {
        // merit batch profile
        $meritBatchProfile = null;
        // request from page
        $meritType = $request->input('page');
        // my parent page (report module/admission module)
        $myPage = $request->input('my_page');
        // promote action
        $promoteAction = $request->input('promote_action');
        // applicant list
        $applicantList = $request->input('applicant_list');
        // looping
        foreach ($applicantList as $key=>$value){
            // applicant result profile
            $applicantResultProfile = $this->applicantResult->where(['applicant_id'=>$key])->first();
            // checking merit or not
            if($promoteAction == "MERIT"){
                $myBatch = $applicantResultProfile->applicant_merit_batch;
                $applicantResultProfile->applicant_merit_batch = ($myBatch==null?'0':$myBatch);
            }

            // checking waiting or not
            if($promoteAction == "WAITING"){
                // my merit batch
                $myBatch = $applicantResultProfile->applicant_merit_batch;
                // batch profile
                $meritBatchProfile = ($myBatch==null?$applicantResultProfile->meritBatch():null);
                // get and set batch to the applicant profile
                $applicantResultProfile->applicant_merit_batch = ($myBatch==null?$meritBatchProfile->merit_batch:$myBatch);
            }

            // set merit type
            $applicantResultProfile->applicant_merit_type = $promoteAction;
            // update applicant result profile
            $applicantResultProfile->save();
        }

        // update merit batch
        if($meritBatchProfile){
            $meritBatchProfile->merit_batch = ($meritBatchProfile->merit_batch+1);
            // save merit batch
            $meritBatchProfile->save();
        }

        // sql qry
        $qry = [
            'academic_year'  => $request->input('academic_year'),
            'academic_level' => $request->input('academic_level'),
            'batch'          => $request->input('batch'),
            'campus_id'      => $this->academicHelper->getCampus(),
            'institute_id'   => $this->academicHelper->getInstitute(),
            'applicant_exam_result' => 1,
        ];

        if($meritType != "passed"){
            $qry['applicant_merit_type'] = strtoupper($meritType);
        }
        // generate applicant result sheet
        $applicantResultSheet = $this->applicantResult->where($qry)->get();
        // return applicant result sheer
        return view('admission::admission-assessment.modals.result-'.$meritType, compact('applicantResultSheet', 'myPage'));

    }


    // store grade book
    public function storeGradeBook(Request $request)
    {
        // request details
        $applicantList = $request->applicant_list;
        // loop counter
        $loopCounter = 0;
        // returnData = array
        $returnData = array();
        // looping
        for ($i = 0; $i < count($applicantList); $i++) {
            // applicant id
            $applicantId = $applicantList[$i];
            // single Applicant Grade
            $singleApplicantGrade = $request->$applicantId;
            // grade id
            $applicantGradeId = $singleApplicantGrade['grade_id'];
            // checking
            if ($applicantGradeId > 0) {
                $applicantGrade = $this->applicantGrade->find($applicantGradeId);
            } else {
                $applicantGrade = new $this->applicantGrade();
            }
            // input or update grade details
            $applicantGrade->applicant_id    = $applicantId; // applicant id
            $applicantGrade->applicant_grade = $singleApplicantGrade['applicant_grade'];
            $applicantGrade->academic_year   = $request->input('academic_year');
            $applicantGrade->academic_level  = $request->input('academic_level');
            $applicantGrade->batch           = $request->input('academic_batch');
            $applicantGrade->campus_id       = $this->academicHelper->getCampus();
            $applicantGrade->institute_id    = $this->academicHelper->getInstitute();
            // save applicant grade
            if ($applicantGrade->save()) {
                $returnData[$applicantId] = $applicantGrade->id;
                $loopCounter              = ($loopCounter+1);
            }
        }
        // save applicant grade
        if ($loopCounter == count($applicantList)) {
            return ['status' => 'success', 'msg' => 'submitted', 'grade_id_list' => $returnData];
        } else {
            return ['status' => 'failed', 'msg' => 'unable to submit'];
        }
    }

    // upload grade book
    public function uploadGradeBook(Request $request)
    {

        // sql qry
        $qry = [
            'academic_year'  => $request->input('academic_year'),
            'academic_level' => $request->input('academic_level'),
            'batch'          => $request->input('academic_batch'),
            'campus_id'      => $this->academicHelper->getCampus(),
            'institute_id'   => $this->academicHelper->getInstitute(),
        ];
        // request details
        $applicantProfiles = $this->applicantView->where($qry)->orderBy('application_no', 'ASC')->get();
        // applicant_grade_book
        $gradeImportFile = $request->file('applicant_grade_book');
        // exam settings
        if($examSettingProfile = $this->examSetting->where($qry)->first()){
            $examTaken = $examSettingProfile->exam_taken;
        }else{
            $examTaken = null;
        }

        // checking
        if ($gradeImportFile) {
            // get file real path
            $filePath = $gradeImportFile->getRealPath();
            // receive data from the input file
            $data = Excel::load($filePath, function ($reader) {})->get();
            // checking
            if ($data->count() > 0) {
                // grade list
                $gradeList = array();
                // looping
                foreach ($data as $key => $applicant) {
                    $gradeList[$applicant->applicant_id] = ['grade_id' => $applicant->grade_id, 'grade_mark' => $applicant->grade_marks];
                }
                // return view with variable
                return view('admission::admission-assessment.modals.grade-book', compact('applicantProfiles', 'gradeList', 'examTaken'));
            } else {
                // return view with variable
                return view('admission::admission-assessment.modals.grade-book', compact('applicantProfiles', 'examTaken'));
            }
        }
    }

    // import grade book
    public function importGradeBook()
    {
        return view('admission::admission-assessment.modals.grade-book-upload');
    }

    // export grade book
    public function exportGradeBook(Request $request)
    {
        // request details
        $applicantProfiles = $this->applicantView->where([
            'academic_year'  => $request->input('academic_year'),
            'academic_level' => $request->input('academic_level'),
            'batch'          => $request->input('batch'),
        ])->orderBy('application_no', 'ASC')->get();

        // compact variables with view
        view()->share(compact('applicantProfiles'));
        //generate excel
        Excel::create('Applicant Assessment Form', function ($excel) {
            $excel->sheet('Applicant Assessment Form', function ($sheet) {
                // Font family
                $sheet->setFontFamily('Comic Sans MS');
                // Set font with ->setStyle()
                $sheet->setStyle(array('font' => array('name' => 'Calibri', 'size' => 12)));
                // cell formatting
                $sheet->setAutoSize(true);
                // Set all margins
                $sheet->setPageMargin(0.25);
                // $sheet->protectCells('A1:A6', "123456");
                // mergeCell
                // $sheet->mergeCells(['C3:D1', 'E1:H1']);

                $sheet->loadView('admission::admission-assessment.reports.report-grade-book-export');
            });
        })->download('xlsx');
    }

    // update grade book
    public function updateGradeBook(Request $request)
    {
        $applicantGradeId = $request->input('applicant_grade_id');
        // checking
        if ($applicantGradeId > 0) {
            $applicantGrade = $this->applicantGrade->find($applicantGradeId);
        } else {
            $applicantGrade = new $this->applicantGrade();
        }
        // input or update grade details
        $applicantGrade->applicant_id    = $request->input('applicant_id');
        $applicantGrade->applicant_grade = $request->input('applicant_grade');
        $applicantGrade->academic_year   = $request->input('academic_year');
        $applicantGrade->academic_level  = $request->input('academic_level');
        $applicantGrade->batch           = $request->input('academic_batch');
        $applicantGrade->campus_id       = $this->academicHelper->getCampus();
        $applicantGrade->institute_id    = $this->academicHelper->getInstitute();

        // save applicant grade
        if ($applicantGrade->save()) {
            return ['status' => 'success', 'msg' => 'submitted', 'grade_id' => $applicantGrade->id];
        } else {
            return ['status' => 'failed', 'msg' => 'unable to submit'];
        }
    }

    // find grade book
    public function findGradeBook(Request $request)
    {

        // sql qry
        $qry = [
            'academic_year'  => $request->input('academic_year'),
            'academic_level' => $request->input('academic_level'),
            'batch'          => $request->input('batch'),
            'campus_id'      => $this->academicHelper->getCampus(),
            'institute_id'   => $this->academicHelper->getInstitute(),
        ];
        // exam settings
        if($examSettingProfile = $this->examSetting->where($qry)->first()){
            $examTaken = $examSettingProfile->exam_taken;
        }else{
            $examTaken = null;
        }
        // request details
        $applicantProfiles = $this->applicantView->where($qry)->orderBy('application_no', 'ASC')->get();
        // return view with variable
        return view('admission::admission-assessment.modals.grade-book', compact('applicantProfiles', 'examTaken'));
    }

    // exam setting
    public function examSetting(Request $request)
    {
        // input details
        $academicYear = $request->input('academic_year');
        $academicLevel = $request->input('academic_level');
        $batch = $request->input('batch');
        $campusId = $this->academicHelper->getCampus();
        $instituteId = $this->academicHelper->getInstitute();

        // checking request_type
        if($request->input('request_type')=='show'){
            // fees setting details
            $feesSettingProfile = $this->examSetting->where([
                'academic_year'=>$academicYear,
                'academic_level'=>$academicLevel,
                'batch'=>$batch,
                'campus_id'=>$campusId,
                'institute_id'=>$instituteId,
            ])->first();

            // generate applicant result sheet
            $applicantResultSheet = $this->applicantResult->where([
                'academic_year'=>$academicYear,
                'academic_level'=>$academicLevel,
                'batch'=>$batch,
                'campus_id'=>$campusId,
                'institute_id'=>$instituteId,
            ])->get()->count();

            // return view with variable
            return view('admission::admission-assessment.modals.setting', compact('feesSettingProfile', 'applicantResultSheet'));
        }else{
            // fees setting id
            $examSettingId = $request->input('exam_setting_id');
            $examFees = $request->input('exam_fees');
            $enrollStudents = $request->input('merit_list_std_no');
            $waitingStudent = $request->input('waiting_list_std_no');
            $examDate = $request->input('exam_date');
            $examStartTime = $request->input('exam_start_time');
            $examEndTime = $request->input('exam_end_time');
            $examVenue = $request->input('exam_venue');
            $examTaken = $request->input('exam_taken');
            $examMarks = $request->input('exam_marks');
            $examPassingMarks = $request->input('exam_passing_marks');

            // checking fees_setting_id
            if($examSettingId>0){
                $examSettingProfile = $this->examSetting->find($examSettingId);
            }else{
                $examSettingProfile = new $this->examSetting();
            }
            // input fees setting details
            $examSettingProfile->exam_fees = $examFees;
            $examSettingProfile->merit_list_std_no = $enrollStudents;
            $examSettingProfile->waiting_list_std_no = $waitingStudent;
            $examSettingProfile->exam_date = date('Y-m-d', strtotime($examDate));
            $examSettingProfile->exam_start_time = $examStartTime;
            $examSettingProfile->exam_end_time = $examEndTime;
            $examSettingProfile->exam_venue = $examVenue;
            $examSettingProfile->exam_taken = $examTaken;
            $examSettingProfile->exam_marks = $examMarks;
            $examSettingProfile->exam_passing_marks = $examPassingMarks;
            $examSettingProfile->academic_year = $academicYear;
            $examSettingProfile->academic_level = $academicLevel;
            $examSettingProfile->batch = $batch;
            $examSettingProfile->campus_id = $campusId;
            $examSettingProfile->institute_id = $instituteId;
            // checking
            if($examSettingProfile->save()){
                return ['status'=>'success', 'exam_setting_id'=>$examSettingProfile->id];
            }else{
                return ['status'=>'failed'];
            }
        }
    }

    /////////////////   applicant admission //////////////////////
    public function confirmStdAdmission(Request $request)
    {
        $admitApplicantList = $request->input('applicant_list');
        $approvedApplicantList = $this->applicantResult->where(['applicant_merit_type'=>'APPROVED'])->get();
        // academic level list
        $academicLevels = $this->academicHelper->getAllAcademicLevel();
        // academic info
        $academicInfo = (object)[
            'year_id' => $request->input('academic_year'),
            'year_name' => $request->input('academic_year_name'),
            'level_id' => $request->input('academic_level'),
            'level_name' => $request->input('academic_level_name'),
            'batch_id' => $request->input('batch'),
            'batch_name' => $request->input('batch_name')
        ];

        // batch list

        // return view with variables
        return view('admission::admission-assessment.admission', compact('admitApplicantList', 'approvedApplicantList', 'academicInfo', 'academicLevels'));
    }


    // admit applicant
    public function admitStudent(Request $request)
    {
        // return $request->all();
        $academicYear = $request->input('academic_year');
        $academicLevel = $request->input('academic_level');
        $academicBatch = $request->input('batch');
        $academicSection = $request->input('section');
        // student list
        $studentList = $request->input('student_list');

        // Start transaction!
        DB::beginTransaction();

        try {
            // student list looping
            foreach ($studentList as $applicantId=>$applicationNo){
                // applicant profile information
                $applicantProfile = $this->applicant->find($applicantId);
                $applicantPersonalInfo = $applicantProfile->personalInfo();

                // create new std user account for the applicant
                $userFullName = $applicantPersonalInfo->std_name;
                // create user profile for student
                $userProfile = $this->studentInfoController->manageUserProfile(0, [
                    'name' =>$userFullName,
                    'email' => $applicantProfile->application_no.'@gmamil.com',
                    'username' => $applicantProfile->username,
                    'password'=> bcrypt(123456)
                ]);

                // create user_info for the newly created user
                $userInfoProfile = new $this->userInfo();
                // add user details
                $userInfoProfile->user_id = $userProfile->id;
                $userInfoProfile->institute_id = $applicantProfile->institute_id;
                $userInfoProfile->campus_id = $applicantProfile->campus_id;
                // save user Info profile
                if($userInfoProfile->save()){
                    // studentRoleProfile
                    $studentRoleProfile = $this->role->where('name', 'student')->first();
                    // assigning student role to this user
                    $userProfile->attachRole($studentRoleProfile);
                }

                // create std profile
                $studentProfile = $this->studentInfoController->manageStdProfile(0, [
                    'user_id'     => $userProfile->id,
                    'type'        => 1,
                    'title'       => $applicantPersonalInfo->title,
                    'first_name'  => $applicantPersonalInfo->std_name,
                    'middle_name' => '',
                    'last_name'   => '',
                    'bn_fullname'   => $applicantPersonalInfo->std_name_bn,
                    'gender'      => $applicantPersonalInfo->gender=='0'?'Male':'Female',
                    'dob'         => date('Y-m-d', strtotime($applicantPersonalInfo->birth_date)),
                    //'blood_group' => $applicantPersonalInfo->blood_group,
                    'email'       => $applicantProfile->email,
                    'phone'       => $applicantPersonalInfo->gud_phone,
                    'religion'       => $applicantPersonalInfo->religion,
                    'nationality' => 1,
                    //'nationality' => $applicantPersonalInfo->nationality,
                    'campus'      => $applicantProfile->campus_id,
                    'institute'   => $applicantProfile->institute_id,
                ]);

                // upload student photo
                $studentPhoto = $this->studentInfoController->applicantPhotoUploader($studentProfile->id, $applicantPersonalInfo->std_photo);

                // create student enrollment
                $this->studentInfoController->manageStdEnrollment(0, [
                    'std_id'         => $studentProfile->id,
                    // 'gr_no'          => $singleStudentInfo['gr_no'],
                    'gr_no'          => null,
                    'academic_level' => $academicLevel,
                    'batch'          => $academicBatch,
                    'section'        => $academicSection,
                    'academic_year'  => $academicYear,
                    'admission_year' => $this->academicHelper->getAdmissionYear(),
                    'enrolled_at'    => date('Y-m-d', strtotime($this->carbon->now()))
                ]);

                // create student address  details
                $this->studentAddressController->storeOnlineStudentAddress($userProfile->id,  $applicantPersonalInfo);

                // create student guardian details
                if($this->studentGuardController->storeOnlineStudentGuardian($studentProfile->id, $applicantProfile,  $applicantPersonalInfo)){
                    // update applicant admission status
                    $this->applicantResult->where(['applicant_id'=>$applicantProfile->id])->update(['admission_status'=>1, 'applicant_merit_type'=>'ADMITTED']);
                }else{
                    // Rollback and then redirect back to form with errors
                    DB::rollback();
                }
            }
        } catch (ValidationException $e) {
            // Rollback and then redirect back to form with errors
            DB::rollback();
            return redirect()->back()->withErrors($e->getErrors())->withInput();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
        // If we reach here, then data is valid and working.
        // Commit the queries!
        DB::commit();

        // success student information
        $admitApplicantList = $studentList;
        $approvedApplicantList = $this->applicantResult->where(['applicant_merit_type'=>'APPROVED'])->get();
        $academicYears = $this->academicHelper->getAllAcademicYears();
        // return view with variables
        return view('admission::admission-assessment.result', compact('academicYears','admitApplicantList', 'approvedApplicantList'))->with('page', 'result');


    }

























}
