<?php

namespace Modules\Academics\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Academics\Entities\AcademicsLevel;
use Modules\Academics\Entities\Assessments;
use Modules\Academics\Entities\Grade;
use Modules\Academics\Entities\GradeCategory;
use Modules\Academics\Entities\GradeCategoryAssign;
use Modules\Academics\Entities\ReportCardSetting;
use Modules\Academics\Entities\StudentGrade;
use Modules\Academics\Entities\StudentMark;
use Modules\Academics\Entities\GradeDetails;
use Modules\Academics\Entities\GradeScale;
use Modules\Academics\Entities\ClassGradeScale;
use Modules\Student\Entities\StudentEnrollment;
use Modules\Student\Entities\StudentInformation;
use Modules\Student\Http\Controllers\reports\StudentAttendanceReportController;
use Modules\Setting\Entities\Institute;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Setting\Entities\AutoSmsModule;
use App\Http\Controllers\SmsSender;
use Modules\Student\Entities\StudentProfileView;
use Modules\Academics\Entities\SubjectAssessment;
use Modules\Academics\Entities\SubjectAssessmentDetails;
use Modules\Academics\Entities\WeightedAverage;
use Modules\Academics\Entities\ExamStatus;
use Modules\Academics\Entities\ExtraBook;
use Modules\Academics\Entities\AdditionalSubject;
use Modules\Academics\Entities\AssessmentPassMark;


use Illuminate\Support\Facades\Log;

use Session;
use Validator;

use App;

class AssessmentsController extends Controller
{

    private $academicsLevel;
    private $assessments;
    private $gradeCategory;
    private $gradeScale;
    private $grade;
    private $gradeDetails;
    private $studentInfo;
    private $studentEnrollment;
    private $studentGrade;
    private $studentMark;
    private $studentAttendanceReportController;
    private $institute;
    private $classGradeScale;
    private $academicHelper;
    private $autoSmsModule;
    private $studentProfileView;
    private $gradeCategoryAssign;
    private $weightedAverage;
    private $subjectAssessment;
    private $subjectAssessmentDetails;
    private $examStatus;
    private $extraBook;
    private $reportCardSetting;
    private $additionalSubject;
    private $assessmentPassMark;

    // constructor
    public function __construct(AcademicsLevel $academicsLevel, StudentInformation $studentInfo, StudentEnrollment $studentEnrollment, Assessments $assessments, GradeCategory $gradeCategory, GradeScale $gradeScale, Grade $grade, GradeDetails $gradeDetails, StudentGrade $studentGrade, StudentMark $studentMark, StudentAttendanceReportController $studentAttendanceReportController, Institute $institute, ClassGradeScale $classGradeScale, AcademicHelper $academicHelper,AutoSmsModule $autoSmsModule, StudentProfileView $studentProfileView, GradeCategoryAssign $gradeCategoryAssign, WeightedAverage $weightedAverage, SubjectAssessment $subjectAssessment, SubjectAssessmentDetails $subjectAssessmentDetails, ExamStatus $examStatus, ExtraBook $extraBook, ReportCardSetting $reportCardSetting, AdditionalSubject $additionalSubject, AssessmentPassMark $assessmentPassMark)
    {
        $this->academicsLevel        = $academicsLevel;
        $this->studentEnrollment     = $studentEnrollment;
        $this->assessments           = $assessments;
        $this->gradeCategory         = $gradeCategory;
        $this->gradeScale            = $gradeScale;
        $this->grade                 = $grade;
        $this->gradeDetails          = $gradeDetails;
        $this->studentInfo           = $studentInfo;
        $this->studentGrade           = $studentGrade;
        $this->studentMark           = $studentMark;
        $this->studentAttendanceReportController = $studentAttendanceReportController;
        $this->institute           = $institute;
        $this->classGradeScale           = $classGradeScale;
        $this->academicHelper           = $academicHelper;
        $this->autoSmsModule           = $autoSmsModule;
        $this->studentProfileView           = $studentProfileView;
        $this->gradeCategoryAssign           = $gradeCategoryAssign;
        $this->weightedAverage           = $weightedAverage;
        $this->subjectAssessment         = $subjectAssessment;
        $this->subjectAssessmentDetails = $subjectAssessmentDetails;
        $this->examStatus = $examStatus;
        $this->extraBook = $extraBook;
        $this->reportCardSetting = $reportCardSetting;
        $this->additionalSubject = $additionalSubject;
        $this->assessmentPassMark = $assessmentPassMark;
    }


    //////////////////////////////// Manage Assessment Pages ////////////////////////////////

    // manage assessment index page
    public function index($tabId)
    {
        // gradeCategory
        $allGradeCategory = $this->gradeCategory->where([
            'institute'=>$this->academicHelper->getInstitute(), 'campus'=>$this->academicHelper->getCampus(), 'is_sba'=>0
        ])->orderBy('created_at', 'ASC')->get();
        // academic year
        $academicYear = $this->getAcademicYearId();
        // academics levels
        $allAcademicsLevel = $this->academicHelper->getAllAcademicLevel();
        // academics semester
//        $allAcademicsSemester = $this->getAcademicSemesters();A

        switch ($tabId) {

            case 'assessment':
                // assessments
                $allGradeScale = $this->grade->where([
                    'grade_scale_id'=>$this->getGradeScaleTypeId(),
                    'institute'=>$this->academicHelper->getInstitute(),
                    'campus'=> $this->academicHelper->getCampus()
                ])->get();
                // return assessment view page
                return view('academics::manage-assessments.assessments', compact('allGradeScale'))->with('page', 'assessment');
                break;

            case 'report-card':
                // return report card view page
                $assementCategory=$this->gradeCategory->where('institute',$this->academicHelper->getInstitute())->where('campus',$this->academicHelper->getCampus())->where('is_sba',0)->get();

                return view('academics::manage-assessments.report-card', compact('assementCategory'))->with('page', 'report-card');
                break;

            case 'grade-book':

                // return veiw with all variables
                return view('academics::manage-assessments.grade-book', compact('allAcademicsLevel'))->with('page', 'grade-book');
                break;

            case 'grade-setup':
                // gradeScale
                $allGradeScale = $this->gradeScale->all();
                // grade details
                $allGrade = $this->grade->where([
                    'grade_scale_id'=>$this->getGradeScaleTypeId(),
                    'institute'=>$this->academicHelper->getInstitute(),
                    'campus'=> $this->academicHelper->getCampus()
                ])->get();
                // return view with allGradeCategory variable
                return view('academics::manage-assessments.grade-setup', compact('allGradeCategory', 'allGradeScale', 'allGrade'))->with('page', 'grade-setup');
                break;

//            case 'result':
//                // return view with allGradeCategory variable
//                return view('academics::manage-assessments.result', compact('allAcademicsLevel', 'allGradeCategory'))->with('page', 'result');
//                break;

            case 'search':

                // return view with allGradeCategory variable
                return view('academics::manage-assessments.result-search', compact('allAcademicsLevel', 'allGradeCategory'))->with('page', 'result-sorting');
                break;

            case 'exam':
                // academic year list
                $academicYearList = $this->academicHelper->getAllAcademicYears();
                // return view with allGradeCategory variable
                return view('academics::manage-assessments.exam', compact('academicYearList'))->with('page', 'exam');
                break;

            case 'grade-book-setting':

                // return view with all variables
                return view('academics::manage-assessments.setting', compact('allAcademicsLevel'))->with('page', 'grade-book-setting');
                break;

            case 'passing-mark-setting':

                // return view with all variables
                return view('academics::manage-assessments.setting-passing-mark', compact('allAcademicsLevel'))->with('page', 'passing-mark-setting');
                break;

            case 'report-card-setting':
                // find institute report card setting profile
                $rSetting = $this->reportCardSetting->where([
                    'institute'=>$this->academicHelper->getInstitute(), 'campus'=> $this->academicHelper->getCampus()
                ])->first();

                // return view with all variables
                return view('academics::manage-assessments.report-card-setting', compact('rSetting'))->with('page', 'report-card-setting');
                break;

            case 'extra-book':

                // return veiw with all variables
                return view('academics::manage-assessments.extra-book', compact('allAcademicsLevel'))->with('page', 'extra-book');
                break;

            case 'result-sms':

                // return veiw with all variables
                return view('academics::sendsms.result-sms', compact('allAcademicsLevel'))->with('page', 'result-sms');
                break;

            default:
                abort(404);
                break;
        }
    }


    // find assessment list
    public function findAssessmentList(Request $request)
    {
        // response data
        $assessmentArrayList = array();
        // request details
        $academicYear = $this->academicHelper->getAcademicYear();
        $level = $request->input('level');
        $batch = $request->input('batch');
        $section = $request->input('section');
        $subject = $request->input('subject', null);
        $campusId = $this->academicHelper->getCampus();
        $instituteId = $this->academicHelper->getInstitute();
        // find batch section scale id
        $scaleId = $this->getGradeScaleId($batch, $section);

        // checking subject
        if($subject){
            // all category list
            $allGradeCategory = $this->gradeCategory->where(['institute'=>$instituteId, 'campus'=>$campusId])->orderBy('created_at', 'ASC')->get();
            // class subject profile
            $classSubjectProfile = $this->academicHelper->getClassSubject($subject);
            $batchSemesterWAList = $this->getClassSubjectAssessmentDetails($level, $batch, $academicYear, $classSubjectProfile->subject_id, $scaleId, $campusId, $instituteId);

            // all category looping
            foreach($allGradeCategory as $category){
                // checking assessment count
                if($category->allAssessmentCounter($scaleId)>0){
                    if($allAssessments = $category->myAssessments()){
                        if($allAssessments->count()>0){
                            foreach($allAssessments as $assessment){
                                // checking
                                if(array_key_exists($assessment->id, $batchSemesterWAList)){
                                    // find subject assessment points
                                    $assPoint = $batchSemesterWAList[$assessment->id];
                                    // checking
                                    if($assPoint>0 AND $assessment->counts_overall_score==0){
                                        // response data list
                                        $assessmentArrayList[] = ['id'=>$assessment->id, 'name'=>$assessment->name, 'cat_id'=>$category->id, 'points'=>$assPoint];
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }else{
            // assessment list for all subject in a class
            // all category list
            $allAssessments = $this->assessments->where(['grade_id'=>$scaleId])->get();
            // category assessment count
            if($allAssessments->count()>0){
                foreach($allAssessments as $assessment){
                    // checking
                    if($assessment->counts_overall_score==0){
                        // response data list
                        $assessmentArrayList[] = ['id'=>$assessment->id, 'name'=>$assessment->name, 'cat_id'=>$assessment->grading_category_id];
                    }
                }
            }
        }
        // return
        return $assessmentArrayList;
    }


//////////////////////////////// Setting Page ////////////////////////////////

    public function assessmentCategorySetting(Request $request)
    {

        $level = $request->input('academic_level');
        $batch = $request->input('batch');
        $section = $request->input('section');
        //$semesterId = $request->input('semester');
        $campusId = $this->academicHelper->getCampus();
        $instituteId = $this->academicHelper->getInstitute();
        //$academicYear = $this->academicHelper->getAcademicYear();

        $scaleId = $this->getGradeScaleId($batch, $section);
        // weightedAverageArrayList
        $weightedAverageArrayList = $this->getGradeScaleAssessmentCategoryWeightedAverageList($level, $batch, $campusId, $instituteId, $scaleId);

        // student class-section subject list
        $classSubjects = $this->studentAttendanceReportController->getClassUniqueSubjectList($batch);
        // grading scale
        $gradeScale = $this->grade->orderBy('name', 'ASC')->where('id',$scaleId)->first(['id', 'name', 'grade_scale_id']);
        // SubjectAssessmentList
        $subjectAssessmentArrayList =  $this->getClassAllSubjectAssessmentList($level, $batch, null, $scaleId, $campusId, $instituteId);
        //$subjectAssessmentArrayList =  $this->getClassAllSubjectAssessmentList($level, $batch, $academicYear, $scaleId, $campusId, $instituteId);

        // return view with variables
        $html = view('academics::manage-assessments.modals.setting', compact('gradeScale', 'classSubjects', 'weightedAverageArrayList', 'subjectAssessmentArrayList'))->render();
        // return
        return ['status'=>'success', 'msg'=>'assessment category setting', 'content'=>$html];
    }

// manageAssessmentCategorySetting
    public function manageAssessmentCategorySetting(Request $request)
    {
        // request details
        $subjectAssessmentId =  $request->input('sub_ass_id');
        $subjectAssessmentList =  $request->input('assessment');
        $level =  $request->input('academic_level');
        $batch =  $request->input('batch');
        $scaleId =  $request->input('grade_scale_id');
        // institute details
        //$academicYear = $this->academicHelper->getAcademicYear();
        $campus = $this->academicHelper->getCampus();
        $institute = $this->academicHelper->getInstitute();

        // subject assessment loop counter
        $assessmentLoopCounter = 0;

        // Start transaction!
        DB::beginTransaction();


        // grade creation
        try {
            // checking $subjectAssessmentId
            if($subjectAssessmentId>0){
                // now subject assessment
                $subjectAssessmentProfile = $this->subjectAssessment->find($subjectAssessmentId);

                // checking old grade_scale_id
                if($subjectAssessmentProfile->grade_scale_id != $scaleId){
                    // subject assessment details
                    $subjectAssessmentDetailsList = $this->subjectAssessmentDetails->where(['sub_ass_id'=>$subjectAssessmentId])->get();
                    // delete previous assessment list one by one
                    foreach ($subjectAssessmentDetailsList as $assessmentDetail){
                        // delete assessment detail
                        $assessmentDetail->delete();
                    }
                }
            }else{
                // now subject assessment
                $subjectAssessmentProfile = new $this->subjectAssessment();
            }

            // input details
            $subjectAssessmentProfile->level = $level;
            $subjectAssessmentProfile->batch = $batch;
            //$subjectAssessmentProfile->academic_year = null;
            $subjectAssessmentProfile->grade_scale_id = $scaleId;
            $subjectAssessmentProfile->campus = $campus;
            $subjectAssessmentProfile->institute = $institute;
            // save and checking
            if($subjectAssessmentProfile->save()){
                // now $assessmentMarkList looping
                foreach ($subjectAssessmentList as $subId=>$assessment){
                    // assessment id
                    $subAssId = $assessment['sub_ass_detail_id'];
                    $subAssMarks = $assessment['marks'];
                    // checking subAssId
                    if($subAssId>0){
                        // find subject assessment details
                        $subjectAssessmentDetailsProfile = $this->subjectAssessmentDetails->find($subAssId);
                    }else{
                        // create subject assessment details
                        $subjectAssessmentDetailsProfile = new $this->subjectAssessmentDetails();
                    }
                    // input details
                    $subjectAssessmentDetailsProfile->sub_id = $subId;
                    $subjectAssessmentDetailsProfile->sub_ass_id = $subjectAssessmentProfile->id;
                    $subjectAssessmentDetailsProfile->assessment_marks = json_encode($subAssMarks);
                    // save and checking
                    if($subjectAssessmentDetailsProfile->save()){
                        $assessmentLoopCounter +=1;
                    }
                }

                // checking
                if($assessmentLoopCounter==count($subjectAssessmentList)){
                    // If we reach here, then data is valid and working. Commit the queries!
                    DB::commit();
                    // assessment category setting modal
                    $html = (object)$this->assessmentCategorySetting($request);
                    // return
                    return ['status'=>'success', 'msg'=>'Subject Assessment Uploaded Successfully', 'content'=>$html->content];
                }else{
                    DB::rollback();
                    // return
                    return ['status'=>'failed', 'msg'=>'unable to upload subject assessment details','content'=>null];
                }
            }else{
                // Rollback and then redirect back to form with errors
                DB::rollback();
                // return
                return ['status'=>'failed', 'msg'=>'unable to save subject assessment profile','content'=>null];
            }

        } catch (ValidationException $e) {
            // Rollback and then redirect back to form with errors
            DB::rollback();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

//////////////////////////////// Setting Page ////////////////////////////////






//////////////////////////////// Result Page ////////////////////////////////

    public function getResult(Request $request)
    {
        $batch = $request->input('batch');
        $section = $request->input('section');
        $subject = $request->input('subject');
        $semesterId = $request->input('semester');
        $assessment = $request->input('ass_id');
        $category = $request->input('ass_cat_id');
        $requestType = $request->input('request_type', 'view');
        // result_list_type
        $listType = $request->input('result_list_type', 'ALL');
        $operator = $request->input('operation', null);
        $inputAssMarks = $request->input('ass_marks', null);

        // get grade scale id
        $scale = $this->getGradeScaleId($batch, $section);
        // subject gradeMark details
        $assMarks = $this->getSubjectGrdeMarkDemo($scale, $subject,$semesterId, $category, $assessment, $listType, $operator, $inputAssMarks);

        // student list
        $studentList =  $this->getClsssSectionStudentList($batch, $section);
        // student institute profile
        $instituteInfo = $this->getInstituteProfile();

        if($requestType == 'pdf') {
            // assessment info
            $assessmentInfo = (object)[
                'class' => $this->academicHelper->getBatch($batch)->batch_name,
                'section' => $this->academicHelper->getSection($section)->section_name,
                'subject' => $this->academicHelper->getClassSubject($subject)->subject()->subject_name,
                'semester' => $this->academicHelper->getSemester($semesterId)->name,
                'assessment' => $this->academicHelper->getAssessment($assessment)->name,
            ];

            view()->share(compact('studentList', 'assMarks', 'instituteInfo', 'assessmentInfo', 'listType'));
            // generate pdf
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView('academics::manage-assessments.reports.report-result')->setPaper('a4', 'portrait');
            // return $pdf->stream();
            return $pdf->download('assessment-result-sheet.pdf');
        }else{
            // return view with variables
            return view('academics::manage-assessments.modals.result', compact('studentList', 'assMarks'));
        }
    }


//////////////////////////////// Report Card Page ////////////////////////////////


    // show single student previous  result
    public function getMyReportCard()
    {

        $stdId=6626; $semesterId=98; $yearId=13; $levelId=30; $batchId=105; $sectionId=168; $campusId=13; $instituteId=12;
//
//        // student profile
//        $studentInfo = $this->studentInfo
//            ->where(['id'=>$stdId])
//            ->join('')
//            ->first();
//
//
//        // find student details
//        $instituteId = $studentInfo->institute;
//        $campusId = $studentInfo->campus;
//
//        // std enrollment information
//        $stdEnrollment = $studentInfo->singleEnroll();
//        $level = $stdEnrollment->academic_level;
//        $batch = $stdEnrollment->batch;
//        $section = $stdEnrollment->section;
//        $academicYear = $stdEnrollment->academicsYear();
//        $allSemester = $this->academicHelper->getBatchSemesterList($academicYear->id, $level, $batch);
//        // $allSemester = $academicYear->semesters()->toArray();
//        $scaleId = $this->getGradeScaleId($batch, $section);
//        // student class-section subject list
//        $classSubjects = $this->studentAttendanceReportController->getClsssSectionSubjectList($batch, $section);
//        // grading scale
//        $gradeScale = $this->grade->orderBy('name', 'ASC')->where('id',$scaleId)->first(['id', 'name', 'grade_scale_id']);
//
//        // weightedAverageArrayList
//        $weightedAverageArrayList = $this->getGradeScaleAssessmentCategoryWeightedAverageList($level, $batch, $campusId, $instituteId, $scaleId);
//        // SubjectAssessmentList
//        $subjectAssessmentArrayList =  $this->getClassAllSubjectAssessmentList($level, $batch, null, $scaleId, $campusId, $instituteId);
//
//        // checking return type
//        if($returnType=='json'){
//            // semester result sheet
//            $resultSheet = $this->getStudentGradeMark($instituteId, $campusId, $scaleId, $stdId, $request->input('semester'));
//            // assessment array list
//            $assessmentArrayList = $this->assessmentArrayListMaker($gradeScale, $instituteId, $campusId);
//            // checking result sheet
//            return ['result_sheet'=>$resultSheet, 'assessment_list'=>$assessmentArrayList];
//        }else{
//            //semester result sheet
//            $semesterResultSheet = array();
//
//            // checking report format as default/ weighted average / weighted average summary
//            if($reportFormat==0){ // default format
//                // students all semesters result
//                for($i=0; $i<count($allSemester); $i++){
//                    if($resultSheet = $this->getStudentGradeMark($instituteId, $campusId, $scaleId, $stdId, $allSemester[$i]['id'])){
//                        $semesterResultSheet[$allSemester[$i]['id']] = $resultSheet;
//                    }
//                }
//
//                // return view
//                return view('academics::manage-assessments.modals.report-card', compact('gradeScale', 'classSubjects', 'semesterResultSheet', 'allSemester', 'studentInfo', 'weightedAverageArrayList', 'subjectAssessmentArrayList'));
//
//
//            }if($reportFormat==1){  // weighted average format
//                // students all semesters result
//                for($i=0; $i<count($allSemester); $i++){
//                    if($resultSheet = $this->getStudentGradeMarkWeightedAverage($instituteId, $campusId, $scaleId, $stdId, $allSemester[$i]['id'], $subjectAssessmentArrayList)){
//                        $semesterResultSheet[$allSemester[$i]['id']] = $resultSheet;
//                    }
//                }
//
//                // return view
//                return view('academics::manage-assessments.modals.report-card-weighted-average', compact('gradeScale', 'classSubjects', 'semesterResultSheet', 'allSemester', 'studentInfo', 'weightedAverageArrayList', 'subjectAssessmentArrayList'));
//
//
//            }else{ // weighted average summary format
//                // students all semesters result
//                for($i=0; $i<count($allSemester); $i++){
//                    if($resultSheet = $this->getStudentGradeMarkWeightedAverage($instituteId, $campusId, $scaleId, $stdId, $allSemester[$i]['id'], $subjectAssessmentArrayList)){
//                        $semesterResultSheet[$allSemester[$i]['id']] = $resultSheet;
//                    }
//                }
//
//                // return view
//                return view('academics::manage-assessments.modals.report-card-weighted-average-summary', compact('gradeScale', 'classSubjects', 'semesterResultSheet', 'allSemester', 'studentInfo', 'weightedAverageArrayList', 'subjectAssessmentArrayList'));
//            }
//        }

    }


    // show single student current year semester result
    public function showSingleReportCard(Request $request){
        // report format
        $reportFormat = $request->input('report_format');
        // return type
        $returnType = $request->input('return_type', 'view');
        // checking return type
        if($returnType=='json'){
            // student gr_no
            $grNo = $request->input('gr_no');
            // student profile
            $studentInfo = $this->studentProfileView->where([
                'institute'=>$request->input('institute'),
                'campus'=>$request->input('campus'),
                'academic_year'=>$request->input('year'),
                'academic_level'=>$request->input('level'),
                'batch'=>$request->input('batch'),
                'section'=>$request->input('section'),
                'gr_no'=>$grNo
            ])->first()->student();
            // student id
            $stdId = $studentInfo->id;
        }else{
            // student id
            $stdId = $request->input('std_id');
            // student profile
            $studentInfo = $this->studentInfo->where('id', $stdId)->first();
        }
        // find student details
        $instituteId = $studentInfo->institute;
        $campusId = $studentInfo->campus;
        // std enrollment information
        $stdEnrollment = $studentInfo->singleEnroll();
        $level = $stdEnrollment->academic_level;
        $batch = $stdEnrollment->batch;
        $section = $stdEnrollment->section;
        $academicYear = $stdEnrollment->academicsYear();
        $allSemester = $this->academicHelper->getBatchSemesterList($academicYear->id, $level, $batch);
        // $allSemester = $academicYear->semesters()->toArray();
        $scaleId = $this->getGradeScaleId($batch, $section);
        // student class-section subject list
        $classSubjects = $this->studentAttendanceReportController->getClsssSectionSubjectList($batch, $section);
        // grading scale
        $gradeScale = $this->grade->orderBy('name', 'ASC')->where('id',$scaleId)->first(['id', 'name', 'grade_scale_id']);

        // weightedAverageArrayList
        $weightedAverageArrayList = $this->getGradeScaleAssessmentCategoryWeightedAverageList($level, $batch, $campusId, $instituteId, $scaleId);
        // SubjectAssessmentList
        $subjectAssessmentArrayList =  $this->getClassAllSubjectAssessmentList($level, $batch, null, $scaleId, $campusId, $instituteId);

        // checking return type
        if($returnType=='json'){
            // semester result sheet
            $resultSheet = $this->getStudentGradeMark($instituteId, $campusId, $scaleId, $stdId, $request->input('semester'));
            // assessment array list
            $assessmentArrayList = $this->assessmentArrayListMaker($gradeScale, $instituteId, $campusId);
            // checking result sheet
            return ['result_sheet'=>$resultSheet, 'assessment_list'=>$assessmentArrayList];
        }else{
            //semester result sheet
            $semesterResultSheet = array();

            // checking report format as default/ weighted average / weighted average summary
            if($reportFormat==0){ // default format
                // students all semesters result
                for($i=0; $i<count($allSemester); $i++){
                    if($resultSheet = $this->getStudentGradeMark($instituteId, $campusId, $scaleId, $stdId, $allSemester[$i]['id'])){
                        $semesterResultSheet[$allSemester[$i]['id']] = $resultSheet;
                    }
                }

                // return view
                return view('academics::manage-assessments.modals.report-card', compact('gradeScale', 'classSubjects', 'semesterResultSheet', 'allSemester', 'studentInfo', 'weightedAverageArrayList', 'subjectAssessmentArrayList'));


            }if($reportFormat==1){  // weighted average format
                // students all semesters result
                for($i=0; $i<count($allSemester); $i++){
                    if($resultSheet = $this->getStudentGradeMarkWeightedAverage($instituteId, $campusId, $scaleId, $stdId, $allSemester[$i]['id'], $subjectAssessmentArrayList)){
                        $semesterResultSheet[$allSemester[$i]['id']] = $resultSheet;
                    }
                }

                // return view
                return view('academics::manage-assessments.modals.report-card-weighted-average', compact('gradeScale', 'classSubjects', 'semesterResultSheet', 'allSemester', 'studentInfo', 'weightedAverageArrayList', 'subjectAssessmentArrayList'));


            }else{ // weighted average summary format
                // students all semesters result
                for($i=0; $i<count($allSemester); $i++){
                    if($resultSheet = $this->getStudentGradeMarkWeightedAverage($instituteId, $campusId, $scaleId, $stdId, $allSemester[$i]['id'], $subjectAssessmentArrayList)){
                        $semesterResultSheet[$allSemester[$i]['id']] = $resultSheet;
                    }
                }

                // return view
                return view('academics::manage-assessments.modals.report-card-weighted-average-summary', compact('gradeScale', 'classSubjects', 'semesterResultSheet', 'allSemester', 'studentInfo', 'weightedAverageArrayList', 'subjectAssessmentArrayList'));
            }
        }
    }

    public function singleReportCardDownloadOption($stdId)
    {
        return view('academics::manage-assessments.modals.report-report-card-download-option', compact('stdId'));

    }


    public function downloadSingleReportCard(Request $request)
    {

        //  return $id;
        $docType = 'pdf';
        $id = $request->input('std_id');
        $reportType = $request->input('report_type');
        $reportFormat = $request->input('report_format');
        // category id for quiz or class test or exam
        $categoryId = $request->input('category_id');

        // student profile
        $studentInfo = $this->studentInfo->where('id', $id)->first();
        $instituteId = $studentInfo->institute;
        $campusId = $studentInfo->campus;
        // student institute profile
        $instituteInfo = $this->getInstituteProfile();
        // std enrollment information
        $stdEnrollment = $studentInfo->singleEnroll();
        $level = $stdEnrollment->academic_level;
        $batch = $stdEnrollment->batch;
        $section = $stdEnrollment->section;
        $academicYear = $stdEnrollment->academicsYear();
        // scale id
        $scaleId = $this->getGradeScaleId($batch, $section);
        // $gradeScaleDetails = $this->gradeDetails->where('grade_id', $scaleId)->orderBy('sorting_order', 'ASC')->get()->toArray();
        //student class-section subject list
        $classSubjects = $this->studentAttendanceReportController->getClsssSectionSubjectList($batch, $section);
        // class subject group
        $subjectGroupList = $this->classSubjectGroupList($classSubjects);
        //semester result sheet
        $allSemester = $this->academicHelper->getBatchSemesterList($academicYear->id, $level, $batch);

        // weightedAverageArrayList
        $weightedAverageArrayList = $this->getGradeScaleAssessmentCategoryWeightedAverageList($level, $batch, $campusId, $instituteId, $scaleId);
        // SubjectAssessmentList
        $subjectAssessmentArrayList =  $this->getClassAllSubjectAssessmentList($level, $batch, null, $scaleId, $campusId, $instituteId);

        // student department
        $stdDeptId = $this->studentAttendanceReportController->findStdDepartment($level, $batch, $academicYear->id, $campusId, $instituteId);
        // Report Card Setting
        $reportCardSetting = $this->reportCardSetting->where(['institute'=>$instituteId, 'campus'=> $campusId])->first();
        // additional subject list
        $additionalSubjectList = (array) $this->additionalSubject->getStudentAdditionalSubjectList($id, $section, $batch, $academicYear->id,  $campusId, $instituteId);

        //$allSemester = $academicYear->semesters()->toArray();
        $semesterResultSheet = array();
        $subjectHighestMarksList = array();
        // students all semesters result
        for($i=0; $i<count($allSemester); $i++){
            // semester
            $mySemesterId = $allSemester[$i]['id'];
            // find semester profile
            $semesterProfile = $this->academicHelper->getSemester($mySemesterId);

            // checking report type
            if($reportType=='subject_detail'){
                if($reportFormat==0){
                    $semesterResultSheet[$mySemesterId] = $this->getStudentGradeMark($instituteId, $campusId, $scaleId, $id, $mySemesterId);
                }else{
                    $semesterResultSheet[$allSemester[$i]['id']] = $this->getStudentGradeMarkWeightedAverage($instituteId, $campusId, $scaleId, $id, $mySemesterId, $subjectAssessmentArrayList);
                }
            }else{
                // subject group report format
                if($reportFormat==0){
                    $semesterResultSheet[$allSemester[$i]['id']] = $this->getStudentSubjectGroupGradeMark($scaleId, $id, $mySemesterId, null);
                }else{
                    // default format with weighted average
                    $semesterResultSheet[$allSemester[$i]['id']] = $this->getStudentSubjectGroupGradeMark($scaleId, $id, $mySemesterId, $subjectAssessmentArrayList);
                }
            }

            // qry maker for student semester extra book marks list
            $extraBookQry = [
                'semester'=>$mySemesterId, 'section'=>$section, 'batch'=>$batch, 'a_level'=>$level,
                'a_year'=>$academicYear->id, 'campus'=>$campusId, 'institute'=>$instituteId
            ];
            // find student extra book
            $stdExtraBookList = $this->extraBook->where($extraBookQry)->get(['id', 'std_id', 'extra_marks']);
            // student ExtraBook re-arranging
            $stdExtraBookMarkSheet[$mySemesterId] = $this->reArrangeStudentExtraBookList($stdExtraBookList);

            // find exam status
            $examStatus = $this->examStatus->where([
                'semester'=>$mySemesterId, 'section'=>$section, 'batch'=>$batch, 'level'=>$level, 'academic_year'=>$academicYear->id, 'campus'=>$campusId, 'institute'=>$instituteId,
            ])->first();

            // checking exam status
            if($examStatus AND $examStatus->status==1){
                // semester subjects highest marks calculation
                $subjectHighestMarksList[$mySemesterId] = $this->getSubjectHighestMarks($instituteId, $campusId, $level, $batch, $section, $scaleId, $mySemesterId, $academicYear->id, $subjectAssessmentArrayList);

                // semester attendance list
                $semesterAttendanceSheet[$mySemesterId] = (object)$this->studentAttendanceReportController->getStdSemesterAttendanceReport($id, $mySemesterId, $stdDeptId, $section, $batch, $academicYear->id,  $campusId,  $instituteId);
            }else{
                // semester subjects highest marks calculation
                $subjectHighestMarksList[$mySemesterId] = ['subject_highest_marks'=>[], 'merit_list'=>[], 'extra_highest_marks'=>[], 'merit_list_with_extra_mark'=>[]];
                // $semesterAttendanceList
                $semesterAttendanceSheet[$mySemesterId] = (object)['status'=>'failed', 'msg'=>'Semester Exam Result Not Published'];
            }
        }

        // grading scale
        $gradeScale = $this->grade->orderBy('name', 'ASC')->where('id',$scaleId)->first(['id', 'name', 'grade_scale_id']);
        // grade scale scale details
        $gradeScaleDetails = $this->gradeDetails->where('grade_id', $scaleId)->orderBy('sorting_order', 'ASC')->get();
        // share all variables with the view
        view()->share(compact('gradeScale','subjectGroupList','gradeScaleDetails', 'classSubjects', 'docType', 'studentInfo','instituteInfo', 'gradeScaleDetails', 'semesterResultSheet', 'allSemester', 'weightedAverageArrayList', 'subjectAssessmentArrayList', 'subjectHighestMarksList', 'stdExtraBookMarkSheet', 'semesterAttendanceSheet', 'reportCardSetting', 'additionalSubjectList','categoryId'));

        // generate pdf
        $pdf = App::make('dompdf.wrapper');
        // checking report Type
        if($reportType=='subject_detail'){
            // checking report format
            if($reportFormat==0){
                $pdf->loadView('academics::manage-assessments.reports.report-student-report-card')->setPaper('a4', 'portrait');
            }elseif($reportFormat==1){
                $pdf->loadView('academics::manage-assessments.reports.report-student-weighted-average-report-card')->setPaper('a4', 'landscape');
            }elseif($reportFormat==2){
                $pdf = $pdf->loadView('academics::manage-assessments.reports.report-student-weighted-average-summary-report-card')->setPaper('a4', 'portrait');
            } elseif($reportFormat==4){
//                return "ddd";
                $pdf = $pdf->loadView('academics::manage-assessments.reports.report-ct-report-card')->setPaper('a4', 'portrait');
            }
        }else{
            // checking report format
            if($reportFormat==0){
                $pdf->loadView('academics::manage-assessments.reports.report-student-report-card-subject-group')->setPaper('a4', 'portrait');
            }elseif($reportFormat==1){
                $pdf->loadView('academics::manage-assessments.reports.report-student-report-card-subject-group-weighted-average')->setPaper('a4', 'landscape');
            }elseif($reportFormat==2){
                $pdf->loadView('academics::manage-assessments.reports.report-student-report-card-subject-group-weighted-average-summary')->setPaper('a4', 'portrait');
            }
        }
        // stream pdf
        return $pdf->stream();
        // download pdf
        // return $pdf->download($studentInfo->first_name.'_'.$studentInfo->middle_name.'_'.$studentInfo->last_name.'_Report_Card.pdf');
    }


// show a class section all student report card
    public function showClassSectionReportCard()
    {
        // academics year
        $allAcademicsLevel = $this->academicHelper->getAllAcademicLevel();
        // semester list
        $allSemesterList = $this->academicHelper->getAcademicSemester();
        // return view with variable
        return view('academics::manage-assessments.modals.report-report-card', compact('allAcademicsLevel', 'allSemesterList'));
    }

// download a class section all student report card
    public function downloadClassSectionReportCard(Request $request)
    {
        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'report_type' => 'required', 'report_format' => 'required', 'doc_type' => 'required',
        ]);

        // storing requesting input data
        if ($validator->passes()) {

            $docType = $request->input('doc_type', 'pdf'); // default doc type is pdf
            $reportType = $request->input('report_type');
            $reportFormat = $request->input('report_format');

            // checking
            if($reportType=='summary'){
                // academic batch
                $level = $request->input('academic_level');
                $batch = $request->input('batch');
                // academic section
                $section = $request->input('section');
                // semester
                $semester = $request->input('semester');
                // institute and campus
                $institute = $this->academicHelper->getInstitute();
                $campus = $this->academicHelper->getCampus();

                // academic year profile
                $academicYear = $this->academicHelper->getAcademicYearProfile();
                // scale id
                $scaleId = $this->getGradeScaleId($batch, $section);
                // institute info
                $instituteInfo = $this->getInstituteProfile();
                $gradeScale = $this->grade->orderBy('name', 'ASC')->where('id',$scaleId)->first(['id', 'name', 'grade_scale_id']);
                $gradeScaleDetails = $this->gradeDetails->where('grade_id', $scaleId)->orderBy('sorting_order', 'ASC')->get()->toArray();
                // student list
                $classSectionStudentList = $this->studentProfileView->where([
                    'batch'=>$batch, 'section'=>$section, 'academic_level'=>$level, 'campus'=>$campus, 'institute'=>$institute
                ])->orderBy('gr_no','ASC')->get(['std_id', 'institute', 'campus']);
                // find class sub
                $classSubjects = $this->studentAttendanceReportController->getClsssSectionSubjectList($batch, $section);
                // class subject group
                $subjectGroupList = $this->classSubjectGroupList($classSubjects);
                // find exam status
                $examStatus = $this->examStatus->where([
                    'semester'=>$semester, 'section'=>$section, 'batch'=>$batch, 'level'=>$level,
                    'academic_year'=>$academicYear->id, 'campus'=>$campus, 'institute'=>$institute,
                ])->first();
                // Report Card Setting
                $reportCardSetting = $this->reportCardSetting->where(['institute'=>$institute, 'campus'=> $campus])->first();

                // student department
                $stdDeptId = $this->studentAttendanceReportController->findStdDepartment($level, $batch, $academicYear->id, $campus, $institute);
                // $additionalSubjectList = (array) $this->additionalSubject->getStudentAdditionalSubjectList(null, $section, $batch, $academicYear->id,  $campus, $institute);
                // additional subject list
                $additionalSubjectList = $this->academicHelper->findClassSectionAdditionalSubjectList($section, $batch, $campus, $institute);

                //semester result sheet
                $allSemester = $this->academicHelper->getBatchSemesterList($academicYear->id, $level, $batch);

                // weightedAverageArrayList
                $weightedAverageArrayList = $this->getGradeScaleAssessmentCategoryWeightedAverageList($level, $batch, $campus, $institute, $scaleId);
                // SubjectAssessmentList
                $subjectAssessmentArrayList =  $this->getClassAllSubjectAssessmentList($level, $batch, null, $scaleId, $campus, $institute);
                // rearrange semester result sheet
                $semesterResultSheet[$semester] = $this->rearrangeStudentGradeMark($scaleId,  $classSectionStudentList, $semester, $reportFormat, $subjectAssessmentArrayList);


                // qry maker for student semester extra book marks list
                $extraBookQry = [
                    'semester'=>$semester, 'section'=>$section, 'batch'=>$batch, 'a_level'=>$level,
                    'a_year'=>$academicYear->id, 'campus'=>$campus, 'institute'=>$institute
                ];
                // find student extra book
                $stdExtraBookList = $this->extraBook->where($extraBookQry)->get(['id', 'std_id', 'extra_marks']);
                // student ExtraBook re-arranging
                $stdExtraBookMarkSheet[$semester] = $this->reArrangeStudentExtraBookList($stdExtraBookList);

                // checking exam status
                if($examStatus AND $examStatus->status==1){
                    // get subject highest marks in section
                    $subjectHighestMarksList[$semester] = $this->getSubjectHighestMarks($institute, $campus, $level, $batch, $section, $scaleId, $semester, $academicYear->id, $subjectAssessmentArrayList);
                    // get subject highest marks in class
                    $subjectHighestMarksListInClass[$semester] = $this->getSubjectHighestMarks($institute, $campus, $level, $batch, null, $scaleId, $semester, $academicYear->id, $subjectAssessmentArrayList);

                    $semesterAttendanceSheet[$semester] = (object)$this->studentAttendanceReportController->getStdSemesterAttendanceReport(null, $semester, $stdDeptId, $section, $batch, $academicYear->id,  $campus,  $institute);
                }else{
                    // get subject highest marks in class
                    $subjectHighestMarksListInClass[$semester] = ['subject_highest_marks'=>[], 'merit_list'=>[], 'extra_highest_marks'=>[], 'merit_list_with_extra_mark'=>[]];
                    // semester subjects highest marks calculation
                    $subjectHighestMarksList[$semester] = ['subject_highest_marks'=>[], 'merit_list'=>[], 'extra_highest_marks'=>[], 'merit_list_with_extra_mark'=>[],'failed_merit_list'=>[]];
                    // $semesterAttendanceList
                    $semesterAttendanceSheet[$semester] = (object)['status'=>'failed', 'msg'=>'Semester Exam Result Not Published'];
                }

                // share all variables with the view
                view()->share(compact('gradeScale', 'semesterResultSheet', 'allSemester', 'semester', 'docType', 'classSectionStudentList', 'instituteInfo', 'gradeScaleDetails', 'weightedAverageArrayList', 'subjectAssessmentArrayList', 'subjectHighestMarksList', 'subjectHighestMarksListInClass', 'stdExtraBookMarkSheet', 'semesterAttendanceSheet', 'classSubjects', 'additionalSubjectList', 'reportCardSetting', 'subjectGroupList'));

                // generate pdf
                //$pdf = App::make('dompdf.wrapper');
                //$pdf->loadView('academics::manage-assessments.reports.report-class-section-report-card')->setPaper('a4', 'portrait');
                // return $pdf->stream();
                //return $pdf->stream('pdfview.pdf');

                if($reportFormat==0){
                    // default format
                    $html = view('academics::manage-assessments.reports.report-class-section-report-card');
                    // snappy pdf details
                    $pdf = App::make('snappy.pdf.wrapper');
                    $pdf->loadHTML($html)->setPaper('a4')->setOrientation('portrait')->setOption('margin-bottom', 0);

                }elseif($reportFormat==1){
                    // checking user institute and campus id
                    if(($institute==35 && $campus==37) || ($institute==23 && $campus==24)){
//                     weighted-average format
                        return view('academics::manage-assessments.reports.report-class-section-weighted-average-report-card-kkhs',compact('gradeScale', 'semesterResultSheet', 'allSemester', 'semester', 'docType', 'classSectionStudentList', 'instituteInfo', 'gradeScaleDetails', 'weightedAverageArrayList', 'subjectAssessmentArrayList', 'subjectHighestMarksList', 'subjectHighestMarksListInClass', 'stdExtraBookMarkSheet', 'semesterAttendanceSheet', 'classSubjects', 'additionalSubjectList', 'reportCardSetting', 'subjectGroupList'));

                    }else{
                        // weighted-average format
                        $html = view('academics::manage-assessments.reports.report-class-section-weighted-average-report-card');
                        // snappy pdf details
                        $pdf = App::make('snappy.pdf.wrapper');
                        $pdf->loadHTML($html)->setPaper('a4')->setOrientation('landscape')->setOption('margin-bottom', 0);
                    }

                }elseif($reportFormat==2){
                    // checking user institute and campus id
                    if($institute==5 AND $campus==5){
                        $html = view('academics::manage-assessments.reports.report-class-section-weighted-average-summary-report-card-biam');
                    }else{
                        // weighted-average-summary format
                        $html = view('academics::manage-assessments.reports.report-class-section-weighted-average-summary-report-card');
                    }
                    // snappy pdf details
                    $pdf = App::make('snappy.pdf.wrapper');
                    $pdf->loadHTML($html)->setPaper('a4')->setOrientation('portrait')->setOption('margin-bottom', 0);

                }elseif($reportFormat==3){
                    // weighted-average format
                    $html = view('academics::manage-assessments.reports.report-class-section-subject-group-wa-details');
                    // snappy pdf details
                    $pdf = App::make('snappy.pdf.wrapper');
                    $pdf->loadHTML($html)->setPaper('a4')->setOrientation('landscape')->setOption('margin-bottom', 0);

                }elseif($reportFormat==4){
                    // weighted-average format
                    return  $html = view('academics::manage-assessments.reports.report-class-section-subject-group-wa-summary');
                    // snappy pdf details
                    $pdf = App::make('snappy.pdf.wrapper');
                    $pdf->loadHTML($html)->setPaper('a4')->setOrientation('portrait')->setOption('margin-bottom', 0);

                }
                else{
                    Session::flash('warning', "Invalid information !!!");
                    return redirect()->back();
                }

                return $pdf->inline();

            }else{
                // student details report card
                return $this->downloadSingleReportCard($request);
            }
        }else{
            Session::flash('warning', "Invalid information !!!");
            return redirect()->back();
        }

    }


    public function rearrangeStudentGradeMark($scaleId, $studentList, $semesterId, $reportFormat, $subjectAssessmentArrayList)
    {
        // response data array
        $data = array();
        // student looping for arranging the student grade  mark
        foreach ($studentList as $student){
            $instituteId = $student->institute;
            $campusId = $student->campus;
            // student data
            if($reportFormat==0){
                $data[$student->std_id]   = $this->getStudentGradeMark($instituteId, $campusId, $scaleId, $student->std_id, $semesterId);

            }else if($reportFormat==3){
                $data[$student->std_id]   = $this->getStudentSubjectGroupGradeMark($scaleId, $student->std_id, $semesterId, $subjectAssessmentArrayList);
//                $data[$student->std_id]   = $this->getStudentGradeMarkWeightedAverage($instituteId, $campusId, $scaleId, $student->std_id, $semesterId, $subjectAssessmentArrayList);
            }else if($reportFormat==4){
                $data[$student->std_id]   = $this->getStudentSubjectGroupGradeMark($scaleId, $student->std_id, $semesterId, $subjectAssessmentArrayList);
//                $data[$student->std_id]   = $this->getStudentGradeMarkWeightedAverage($instituteId, $campusId, $scaleId, $student->std_id, $semesterId, $subjectAssessmentArrayList);
            }else{
                $data[$student->std_id]   = $this->getStudentGradeMarkWeightedAverage($instituteId, $campusId, $scaleId, $student->std_id, $semesterId, $subjectAssessmentArrayList);
            }
        }
        // return data
        return $data;
    }

//////////////////////////////// Extra Book page ////////////////////////////////

//  get / find extra book
    public function getExtraBook(Request $request)
    {
        $level = $request->input('academic_level');
        $batch = $request->input('batch');
        $section = $request->input('section');
        $semester = $request->input('semester');
        $campus = $this->academicHelper->getCampus();
        $institute = $this->academicHelper->getInstitute();
        $academicYear = $this->academicHelper->getAcademicYear();

        // scale id
        $scaleId = $this->getGradeScaleId($batch, $section);
        // grading scale
        $gradeScale = $this->grade->orderBy('name', 'ASC')->where('id',$scaleId)->first(['id', 'name', 'grade_scale_id']);
        // $studentList
        $studentList = $this->getClsssSectionStudentList($batch, $section);
        // weightedAverageArrayList
        $weightedAverageArrayList = $this->getGradeScaleAssessmentCategoryWeightedAverageList($level, $batch, $campus, $institute, $scaleId);

        // qry maker
        $qry = [
            'semester'=>$semester, 'section'=>$section, 'batch'=>$batch, 'a_level'=>$level,
            'a_year'=>$academicYear, 'campus'=>$campus, 'institute'=>$institute
        ];

        // find student extra book
        $stdExtraBookList = $this->extraBook->where($qry)->get(['id', 'std_id', 'extra_marks']);
        // student ExtraBook re-arranging
        $stdExtraBookArrayList = $this->reArrangeStudentExtraBookList($stdExtraBookList);

        // student department
        $stdDeptId = $this->studentAttendanceReportController->findStdDepartment($level, $batch, $academicYear, $campus, $institute);

        // find exam status
        $examStatus = $this->examStatus->where([
            'semester'=>$semester, 'section'=>$section, 'batch'=>$batch, 'level'=>$level,
            'academic_year'=>$academicYear, 'campus'=>$campus, 'institute'=>$institute,
        ])->first();

        // class section semester attendance details
        $semesterAttendanceSheet = (object)$this->studentAttendanceReportController->getStdSemesterAttendanceReport(null, $semester, $stdDeptId, $section, $batch, $academicYear,  $campus,  $institute);

        // return view with variables
        $html = view('academics::manage-assessments.modals.extra-book', compact('gradeScale', 'studentList', 'weightedAverageArrayList', 'stdExtraBookArrayList', 'semesterAttendanceSheet', 'examStatus'))->render();
        // return
        return ['status'=>'success', 'msg'=>'assessment category setting', 'content'=>$html];
    }


// store extra book
    public function storeExtraBook(Request $request)
    {
        // category (assessment list) details
        $categoryDetails = $request->input('category');
        $assessmentList = (array)$request->input('assessment');
        $academicLevel = $request->input('academic_level');
        $batch = $request->input('batch');
        $section = $request->input('section');
        $semester = $request->input('semester');
        // academic Yea
        $academicYear = $this->academicHelper->getAcademicYear();
        $campus = $this->academicHelper->getCampus();
        $institute = $this->academicHelper->getCampus();

        // checking assessment list
        if(!empty($assessmentList) AND count($assessmentList)>0){

            // std assessment loop counter
            $assessmentLoopCounter = 0;
            // assessment list looping
            foreach ($assessmentList as $stdId=>$extraBookDetails){
                // Extra Book Mark id
                $stdExtraBookMarkId = $extraBookDetails['mark_id'];
                // extra marks data set maker
                $extraMarks = (object)['marks'=>(object)$extraBookDetails['mark_list'], 'category'=>(object)$categoryDetails];

                // checking Extra Book Mark Id
                if($stdExtraBookMarkId>0){
                    // now store student extra marks
                    $extraProfile = $this->extraBook->find($stdExtraBookMarkId);
                }else{
                    // now store student extra marks
                    $extraProfile = new $this->extraBook();
                }
                // input details
                $extraProfile->std_id = $stdId;
                $extraProfile->extra_marks = json_encode($extraMarks);
                $extraProfile->semester = $semester;
                $extraProfile->section = $section;
                $extraProfile->batch = $batch;
                $extraProfile->a_level = $academicLevel;
                $extraProfile->a_year = $academicYear;
                $extraProfile->campus = $campus;
                $extraProfile->institute = $institute;
                // save and checking
                if($extraProfile->save()){
                    $assessmentLoopCounter += 1;
                }
            }

            // now checking all submission
            if($assessmentLoopCounter == count($assessmentList)){
                // return success msg
                return ['status'=>'success', 'msg'=>'Student Extra Book Submitted !!!'];
            }else{
                // return success msg
                return ['status'=>'failed', 'msg'=>'Unable to Submit Student Extra Book !!!'];
            }
        }else{
            // return success msg
            return ['status'=>'failed', 'msg'=>'ExtraBook Assessment List is empty'];
        }
    }

//////////////////////////////// Grade Book page ////////////////////////////////

    // academic class section subject student list
    public function getClassSectionSubjectStudentList(Request $request)
    {
        $level = $request->input('academic_level');
        $batch = $request->input('batch');
        $section = $request->input('section');
        $subject = $request->input('subject');
        $downloadType = $request->input('download_type', 'pdf');
        $myRequest = $request->all();
        // institute details
        $campusId = $this->academicHelper->getCampus();
        $instituteId = $this->academicHelper->getInstitute();
        $academicYear = $this->academicHelper->getAcademicYear();
        $instituteInfo = $this->academicHelper->getInstituteProfile();
        // class subject profile
        $classSubjectProfile = $this->academicHelper->getClassSubject($subject);
        // student list
        $classStdList =  $this->getClsssSectionStudentList($batch, $section);
        $classSubStdList = $this->academicHelper->getAdditionalSubjectStdList($subject, $section, $batch, $campusId, $instituteId);
        // find subject student from class student list
        $studentList =  $this->academicHelper->getClassSubjectStudentList($classSubjectProfile, $classSubStdList, $classStdList);
        // share all variables with the view
        view()->share(compact('instituteInfo','studentList', 'myRequest'));

        // checking download type
        if($downloadType=='pdf'){
            // generate pdf
            $pdf = App::make('dompdf.wrapper');
            // load view
            $pdf = $pdf->loadView('academics::manage-academics.reports.report-class-section-subject-student')->setPaper('a4', 'portrait');
            // stream pdf
            return $pdf->stream();
            // download pdf
            // return $pdf->download('fileName');
        }else{
            //generate excel
            Excel::create($request->batch_name.' - '.$request->section_name.' - '.$request->subject_name, function ($excel) {
                $excel->sheet('Class Subject Student Report', function ($sheet) {
                    // Font family
                    $sheet->setFontFamily('Comic Sans MS');
                    // Set font with ->setStyle()
                    $sheet->setStyle(array('font' => array('name'=>'Calibri','size' => 12)));
                    // cell formatting
                    $sheet->setAutoSize(true);
                    // Set all margins
                    $sheet->setPageMargin(0.25);
                    // mergeCell
                    // $sheet->mergeCells(['C3:D1', 'E1:H1']);
                    $sheet->loadView('academics::manage-academics.reports.report-class-section-subject-student');
                });
            })->download('xlsx');
        }
    }


    public function getGradeBook(Request $request)
    {
        $allGrades = null;
        $level = $request->input('academic_level');
        $batch = $request->input('batch');
        $section = $request->input('section');
        $subject = $request->input('subject');
        $semesterId = $request->input('semester');
        // institute details
        $campusId = $this->academicHelper->getCampus();
        $instituteId = $this->academicHelper->getInstitute();
        $academicYear = $this->academicHelper->getAcademicYear();
        // class subject profile
        $classSubjectProfile = $this->academicHelper->getClassSubject($subject);

        // get grade scale id
        $scale = $this->getGradeScaleId($batch, $section);
        // subject gradeMark details
        $subjectGrades = $this->getSubjectGrdeMarkFirstTime($scale, $subject, $semesterId);
        // grading scale
        $gradeScale = $this->grade->where(['campus'=>$campusId, 'institute'=>$instituteId])->orderBy('name', 'ASC')
            ->where('id',$scale)->first(['id', 'name', 'grade_scale_id']);
        // student list
        $classStdList =  $this->getClsssSectionStudentList($batch, $section);
        $classSubStdList = $this->academicHelper->getAdditionalSubjectStdList($subject, $section, $batch, $campusId, $instituteId);
        // find subject student from class student list
        $studentList =  $this->academicHelper->getClassSubjectStudentList($classSubjectProfile, $classSubStdList, $classStdList);

        // weightedAverageArrayList
        $weightedAverageArrayList = $this->getGradeScaleAssessmentCategoryWeightedAverageList($level, $batch, $campusId, $instituteId, $scale);

        // SubjectAssessmentList
        $subjectAssessmentArrayList =  $this->getClassSubjectAssessmentDetails($level, $batch, $academicYear, $classSubjectProfile->subject_id, $scale, $campusId, $instituteId);

        // find exam status
        $examStatus = $this->examStatus->where([
            'semester'=>$semesterId, 'section'=>$section, 'batch'=>$batch, 'level'=>$level,
            'academic_year'=>$academicYear, 'campus'=>$campusId, 'institute'=>$instituteId,
        ])->first();

        //start romesh code
        $resultModule=$this->autoSmsModule->where('ins_id',$instituteId)->where('campus_id',$campusId)->where('status_code',"RESULT")->where('status',1)->get();
        //end romesh code

        // return view with variables
        return view('academics::manage-assessments.modals.grade-book', compact('gradeScale', 'studentList', 'allGrades', 'scale', 'subjectGrades','resultModule', 'weightedAverageArrayList', 'subjectAssessmentArrayList', 'examStatus'));
    }

    public function storeGradeBook(Request $request)
    {
        //return $request->all();
        $data = $request->input('data');
//        return $data['assCategory_1['];

        $validator = Validator::make($data, [
            'batch' => 'required',
            'section' => 'required',
            'subject' => 'required',
            //'grade_scale' => 'required',
        ]);

        // storing requesting input data
        if ($validator->passes()) {
            // $scale = $data['grade_scale'];
            $batch = $data['batch'];
            $section = $data['section'];
            $subject = $data['subject'];
            // $semesterId = $this->getAcademicSemesterId();
            $semesterId = $data['semester'];
            // institute details
            $academicYear = $this->getAcademicYearId();
            $campus = $this->getInstituteCampusId();
            $institute = $this->getInstituteId();
            // find scale for batch
            $scale = $this->getGradeScaleId($batch, $section);

            // all grades
            $allGrades =  $data['grade'];
            // std count and list
            $stdCount = $allGrades['std_count'];
            $stdList = $allGrades['std_list'];

            // grade list
            $gradeList = $this->studentGrade->where(['class_sub_id'=>$subject,  'semester'=>$semesterId, 'batch'=>$batch, 'section'=>$section,
                'academic_year'=>$academicYear, 'campus'=>$campus, 'institute'=>$institute])->get(['std_id', 'mark_id']);
            // gradeArrayList
            $gradeArrayList = array();
            // looping
            foreach ($gradeList as $stdGrade){
                $gradeArrayList[$stdGrade->std_id] = $stdGrade->mark_id;
            }


            // response array
            $responseData = array();

            // std loop counter
            $stdLoopCount = 0;
            // std looping
            for($i=1; $i<=$stdCount; $i++){
                // single student id
                $stdId =$stdList['std_'.$i];

                $stdMarkDetails = $allGrades['std_'. $stdId];
                $markId = $stdMarkDetails['mark_id'];
                $allMarks = $stdMarkDetails['mark'];
                // student mark details
                $allMarks = json_encode($allMarks);

                if($markId>0){
                    // checking grade list
                    if(array_key_exists($stdId, $gradeArrayList)==true){
                        // Student Subject Mark Id
                        $stdSubjectMarkId = $gradeArrayList[$stdId];
                        // checking mark id
                        if($stdSubjectMarkId==$markId){
                            // new student mark
                            $stdMark =  $this->studentMark->where('id', $markId)->first();
                            // store student mark details
                            $stdMark->marks = $allMarks;
                            // save student mark
                            $stdMarkUpdated = $stdMark->save();

                            // checking
                            if($stdMarkUpdated){
                                // store mark id for response
                                $responseData[] = ['std_id'=>$stdId, 'type'=>'update', 'mark_id'=>$stdMark->id];
                                // loop counter
                                $stdLoopCount = ($stdLoopCount+1);
                            }else{
                                Session::flash('warning', "unable to submit the marks grade details");
                                return redirect()->back();
                            }
                        }else{
                            // loop counter
                            $stdLoopCount = ($stdLoopCount+1);
                        }
                    }else{
                        // loop counter
                        $stdLoopCount = ($stdLoopCount+1);
                    }

                }else{
                    // checking grade list
                    if(array_key_exists($stdId, $gradeArrayList)==true){
                        // loop counter
                        $stdLoopCount = ($stdLoopCount+1);

                    }else{
                        // new student mark
                        $stdMark =  new $this->studentMark();
                        // store student mark details
                        $stdMark->marks = $allMarks;
                        // save student mark
                        $stdMarkCreated = $stdMark->save();

                        // checking
                        if($stdMarkCreated){
                            // new student grade
                            $stdGrade =  new $this->studentGrade();
                            // store std grade details
                            $stdGrade->std_id = $stdId;
                            $stdGrade->mark_id = $stdMark->id;
                            $stdGrade->scale_id =$scale;
                            $stdGrade->class_sub_id = $subject;
                            $stdGrade->semester = $semesterId;
                            $stdGrade->academic_year = $academicYear;
                            $stdGrade->section = $section;
                            $stdGrade->batch = $batch;
                            $stdGrade->campus = $campus;
                            $stdGrade->institute = $institute;
                            // save std grade details
                            $stdGradeCreated = $stdGrade->save();

                            // checking
                            if($stdGradeCreated){
                                // store mark id for response
                                $responseData[] = ['std_id'=>$stdId, 'type'=>'create', 'mark_id'=>$stdMark->id, 'grade_id'=>$stdGrade->id];
                                // loop counter
                                $stdLoopCount = ($stdLoopCount+1);
                            }else{
                                Session::flash('warning', "unable to submit the marks grade details");
                                return redirect()->back();
                            }
                        }else{
                            Session::flash('warning', "unable to submit the marks mark details");
                            return redirect()->back();
                        }
                    }
                }
            }


            // checking
            if($stdLoopCount == $stdCount){
                //romesh coding area starts here
                $checked_cat_ass = array();
                $category_id_list=$data['category_id_list'];
                $category_id_list_array = explode(",", $category_id_list);
                for($kk= 0 ; $kk < count($category_id_list_array)-1; $kk++){
                    $checked_cat_ass[$category_id_list_array[$kk]] = array();
//                    $assessments =$data['assCategory_['.$category_id_list_array[$kk]];
                    $assessments =$data['assCategory_'.$category_id_list_array[$kk].'['];
                    $assessments_array = explode(",", $assessments);
                    $checked_cat_ass[$category_id_list_array[$kk]] = $assessments_array;
                }

                // return $checked_cat_ass;

                $stdList = $allGrades['std_list'];
                $ass_value = $data['ass_value'];
                // asse value
                if($ass_value>0){
                    $gradeMarkJson = $this->getSubjectGrdeMark($scale, $subject, $semesterId);
                    $smsSender = new SmsSender;
                    $smsSender->create_result_job($stdList, $checked_cat_ass, $category_id_list_array, $gradeMarkJson);
                }
                // romesh coding area ends here

                // return subject gradeMark details
                return ['status'=>'success', 'std_marks'=>$responseData, 'msg'=>'GradeBook Submitted !!!'];
                // return $this->getSubjectGrdeMark($scale, $subject, $semesterId);
            }else{
                return ['status'=>'failed', 'msg'=>'unable to perform the action'];
                // Session::flash('warning', "unable to perform the action");
                //return redirect()->back();
            }
        }else{
            Session::flash('warning', "Invalid Information");
            return redirect()->back();
        }
    }

    public function updateGradeBook(Request $request)
    {
        // return $request->all();
        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'std_id'    => 'required',
            'mark_id' => 'required',
            'grade' => 'required',
        ]);

        // response data
        $data = null;
        // storing requesting input data
        if ($validator->passes()) {
            // receiving all inputs details
            $stdId = $request->input('std_id');
            $markId = $request->input('mark_id');
            $allGrades = $request->input('grade');


            // student mark details
            $stdMarkDetails = $allGrades['std_'. $stdId];
            // std all marks
            $allMark = $stdMarkDetails['mark'];
            $allMark = json_encode($allMark);

            // new student mark
            $stdMark = $this->studentMark->where('id',$markId)->first();
            // store student mark details
            $stdMark->marks = $allMark;
            // save student mark
            $stdMarkUpdated = $stdMark->save();
            // checking
            if($stdMarkUpdated){
                $data  = ['status'=>'success', 'msg'=>'GradeBook Updated !!!!'];
            }else{
                $data = ['status'=>'failed', 'msg'=>'Unable to update GradeBook !!!!'];
            }

        }else{
            $data = ['status'=>'failed', 'msg'=>'Invalid information provided'];
        }
        // return response data
        return $data;
    }

// get subject gradeMark details
    public function getStudentGradeMark($instituteId, $campusId, $scale, $stdId, $semester)
    {
        //  std grade list
        $stdGradeList = $this->studentGrade->where([
            'std_id'=>$stdId,
            'scale_id'=>$scale,
            'semester'=>$semester,
            'campus'=>$campusId,
            'institute'=>$instituteId
        ])->get();

        // grade scale scale details
        $gradeScaleDetails = $this->gradeDetails->where('grade_id', $scale)->orderBy('sorting_order', 'ASC')->get();

        // checking
        if($stdGradeList->count()>0){
            // response data
            $subTotalMarks = 0;
            $subTotalObtained = 0;
            $subPointsObtained = 0;
            $subPointsIncomplete = 0;
            $maxPointTotal = 0;

            $data = array();
            // stdMark looping
            foreach ($stdGradeList as $singleGrade){
                // subject profile
                $classSubjectProfile = $singleGrade->classSubject();
                // subject type
                $is_countable = $classSubjectProfile->is_countable;
                $subjectProfile = $classSubjectProfile->subject();
                // subject group
                $subjectGroupProfile = $subjectProfile->checkSubGroupAssign();
                // subject marks
                $gradeMark = $singleGrade->gradeMark()->marks;
                // analysing marks for details information
                $marksDetails = (object)$this->gradeMarkDetails(json_decode($gradeMark, true));
                // letter grade
                $letterGradeDetails = $this->subjectGradeCalculation($marksDetails->percentage, $gradeScaleDetails);

                // grade data
                $data['grade'][$singleGrade->class_sub_id] = array(
                    'has_group'=>$subjectGroupProfile?1:0,
                    'group_id'=>$subjectGroupProfile?$subjectGroupProfile->id:0,
                    'grade_id'=>$singleGrade->id,
                    'cs_id'=>$singleGrade->class_sub_id,
                    'sub_name'=> $subjectProfile->subject_name,
                    'sub_group_name'=> $subjectGroupProfile?$subjectGroupProfile->subjectGroup()->name:'No Group',
                    'mark_id'=>$singleGrade->gradeMark()->id,
                    'mark'=>json_decode($gradeMark),
                    'credit'=>$singleGrade->subject_credit,
                    'total'=>$marksDetails->total,
                    'obtained'=>$marksDetails->obtained,
                    'percentage'=>(int)$marksDetails->percentage,
                    'letterGrade'=>$letterGradeDetails?($letterGradeDetails['grade']):'N/A',
                    'letterGradePoint'=>$letterGradeDetails?($letterGradeDetails['point']." / ".$letterGradeDetails['max_point']):'N/A'
                );

                // checking subject type
                if($is_countable==1){
                    // subtotal
                    $subTotalMarks = ($subTotalMarks+$marksDetails->total);
                    $subTotalObtained = ($subTotalObtained+$marksDetails->obtained);
                    if($letterGradeDetails){
                        // checking letter grade
                        if($letterGradeDetails['grade'] != 'F'){
                            $maxPointTotal = ($maxPointTotal+$letterGradeDetails['max_point']);
                            $subPointsObtained = ($subPointsObtained+($letterGradeDetails['max_point']*$letterGradeDetails['point']));
                        }else{
                            $subPointsIncomplete = ($subPointsIncomplete+$letterGradeDetails['max_point']);
                        }
                    }
                }
            }

            // $precision
            $precision = 2;
            // result details
            $data['result']['total_points'] = $maxPointTotal;
            $data['result']['total_points_incomplete'] = $subPointsIncomplete;
            $data['result']['total_marks'] = $subTotalMarks;
            $data['result']['total_obtained'] = $subTotalObtained;
            $data['result']['total_gpa'] = $maxPointTotal==0?0:(substr(number_format(($subPointsObtained/$maxPointTotal), $precision + 1, '.', ''), 0, -1));
            $data['result']['total_percent'] = $subTotalMarks>0?(substr(number_format((($subTotalObtained*100)/$subTotalMarks), $precision + 1, '.', ''), 0, -1)):0;

            // return variable
            return $data;
        }else{
            return null;
        }
    }    // get subject gradeMark details


// Grade Mark Weighted Average for Subject Details
    public function getStudentGradeMarkWeightedAverage($instituteId, $campusId, $scale, $stdId, $semester, $subjectAssessmentArrayList)
    {
        // all subject assessment list
        $allSubCatAssMarkList = count($subjectAssessmentArrayList)>0?$subjectAssessmentArrayList['subject_list']:[];
        //  std grade list
        $stdGradeList = $this->studentGrade->where([
            'std_id'=>$stdId, 'scale_id'=>$scale, 'semester'=>$semester, 'campus'=>$campusId, 'institute'=>$instituteId
        ])->get();

        // grade scale
        $gradeScale = $this->grade->orderBy('name', 'ASC')->where('id',$scale)->first(['id', 'name', 'grade_scale_id']);
        // grade scale scale details
        $gradeScaleDetails = $this->gradeDetails->where('grade_id', $scale)->orderBy('sorting_order', 'ASC')->get();
        // checking
        if($stdGradeList->count()>0){
            // response data
            $subTotalMarks = 0;
            $subTotalObtained = 0;
            $subPointsObtained = 0;
            $subPointsIncomplete = 0;
            $maxPointTotal = 0;
            $totalExamMark=0;
            $totalFailedSubject=0;

            $data = array();
            // stdMark looping
            foreach ($stdGradeList as $singleGrade){
                // class subject profile
                $classSubProfile = $singleGrade->classSubject(); // subject_id, class_id, id
                // subject profile
                $subjectProfile = $classSubProfile->subject(); // institute, campus
                // subject group
                $subjectGroupProfile = $subjectProfile->checkSubGroupAssign();
                // subject marks
                $gradeMark = $singleGrade->gradeMark()->marks;
                // single subject category assessment list
                $subCatAssMarkList = (array)(array_key_exists($subjectProfile->id, $allSubCatAssMarkList)?$allSubCatAssMarkList[$subjectProfile->id]:[]);

                // analysing marks for details information
                $marksDetails = (object)$this->weightedAverageGradeMarkDetails(json_decode($gradeMark, true), $gradeScale, $subCatAssMarkList);
                // letter grade
                $letterGradeDetails = $this->subjectGradeCalculation((int)$marksDetails->percentage, $gradeScaleDetails);

                // grade data
                $data['grade'][$singleGrade->class_sub_id] = array(
                    'has_group'=>$subjectGroupProfile?1:0,
                    'group_id'=>$subjectGroupProfile?$subjectGroupProfile->id:0,
                    'grade_id'=>$singleGrade->id,
                    'cs_id'=>$singleGrade->class_sub_id,
                    'sub_name'=> $subjectProfile->subject_name,
                    'sub_group_name'=> $subjectGroupProfile?$subjectGroupProfile->subjectGroup()->name:'No Group',
                    'mark_id'=>$singleGrade->gradeMark()->id,
                    'mark'=>json_decode($gradeMark),
                    'credit'=>$singleGrade->subject_credit,
                    'pass_mark'=>$classSubProfile->pass_mark,
                    'exam_mark'=>$classSubProfile->exam_mark,
                    'is_countable'=>$classSubProfile->is_countable,
                    'total'=>round($marksDetails->total, 2, PHP_ROUND_HALF_UP),
                    'obtained'=>round($marksDetails->obtained, 2, PHP_ROUND_HALF_UP),
                    'percentage'=>round($marksDetails->percentage, 2, PHP_ROUND_HALF_UP),
                    'letterGrade'=>$letterGradeDetails?$letterGradeDetails['grade']:'N/A',
                    'letterGradePoint'=>$letterGradeDetails?$letterGradeDetails['point']:'N/A'
                );

                // checking class subject type
                if($classSubProfile->is_countable==1){

                    $totalExamMark+=$classSubProfile->exam_mark;
                    // subtotal
                    $subTotalMarks = ($subTotalMarks+round($marksDetails->total, 2, PHP_ROUND_HALF_UP));
                    $subTotalObtained = ($subTotalObtained+round($marksDetails->obtained, 2, PHP_ROUND_HALF_UP));
                    if($letterGradeDetails){
                        // checking letter grade
                        if($letterGradeDetails['grade'] != 'F'){
                            $maxPointTotal = ($maxPointTotal+$letterGradeDetails['max_point']);
                            $subPointsObtained = ($subPointsObtained+($letterGradeDetails['max_point']*$letterGradeDetails['point']));
                        }else{
                            // count failed subject
                            $totalFailedSubject+=1;
                            $subPointsIncomplete = ($subPointsIncomplete+$letterGradeDetails['max_point']);
                        }
                    }
                }
            }

            // $precision
            $precision = 2;
            // total marks percentage
            $totalMarkPercentage = $subTotalMarks>0?(round(($subTotalObtained*100)/$subTotalMarks, $precision, PHP_ROUND_HALF_UP)):0;
            // find final later grade details
            $finalLetterGradeDetails = $this->subjectGradeCalculation((int)$totalMarkPercentage, $gradeScaleDetails);

            // result details
            $data['result']['total_points'] = $maxPointTotal;
            $data['result']['total_points_incomplete'] = $subPointsIncomplete;
            $data['result']['total_exam_marks'] = $totalExamMark;
            $data['result']['total_marks'] = $subTotalMarks;
            $data['result']['total_obtained'] = $subTotalObtained;
            $data['result']['total_gpa'] = $totalFailedSubject>0?0:$finalLetterGradeDetails['point'];
            $data['result']['total_percent'] = $totalMarkPercentage;
            // subject highest marks
            // $data['highest_mark_list'] = $this->getSubjectHighestMarks($instituteId, $campusId, $scale, $semester);
            // return variable
            return $data;
        }else{
            return null;
        }
    }

    // semester subjects highest marks calculation
    public function getSubjectHighestMarks($instituteId, $campusId, $level, $batch, $section=null, $scale, $semesterId, $academicYear, $subjectAssessmentArrayList)
    {
        // response data
        $subjectMarksList = array();
        // extra marks data
        $extraMarksList = array();
        // std marks list
        $meritList = array();
        // merit list with extra marks
        $meritListWithExtraMark = array();

        // std marks list
        $failedList = array();
        // std marks list
        $failedSubList = array();

        // all subject assessment list
        $allSubjectCatAssMarkList = (array)(count($subjectAssessmentArrayList)>0?$subjectAssessmentArrayList['subject_list']:[]);

        // qry maker
        if($section){
            $qry = ['section'=>$section, 'batch'=>$batch, 'scale_id'=>$scale,'campus'=>$campusId,'institute'=>$instituteId,'semester'=>$semesterId, 'academic_year'=>$academicYear
            ];
        }else{
            $qry = ['batch'=>$batch, 'scale_id'=>$scale,'campus'=>$campusId,'institute'=>$instituteId,'semester'=>$semesterId, 'academic_year'=>$academicYear];
        }

        //  std grade list
        $stdGradeList = $this->studentGrade->where($qry)->get();

        // grade scale
        $gradeScale = $this->grade->orderBy('name', 'ASC')->where('id',$scale)->first(['id', 'name', 'grade_scale_id']);
        // grade scale scale details
        $gradeScaleDetails = $this->gradeDetails->where('grade_id', $scale)->orderBy('sorting_order', 'ASC')->get();
        // checking
        if($stdGradeList->count()>0){

            // stdMark looping
            foreach ($stdGradeList as $singleGrade){

                // class subject profile
                $classSubProfile = $singleGrade->classSubject(); // subject_id, class_id, id
                // subject profile
                $subjectProfile = $classSubProfile->subject(); // institute, campus
                // single subject category assessment list
                $subCatAssMarkList = (array)array_key_exists($subjectProfile->id, $allSubjectCatAssMarkList)?$allSubjectCatAssMarkList[$subjectProfile->id]:[];
                // subject marks
                $gradeMark = $singleGrade->gradeMark()->marks;
                // analysing marks for details information
                $marksDetails = (object) $this->weightedAverageGradeMarkDetails(json_decode($gradeMark, true), $gradeScale, $subCatAssMarkList);

                // compare current marks with
                $subjectMarkPercentage = $marksDetails->percentage;
                $subjectCurrentMarks = $marksDetails->obtained;
                // find subject grade details
                $gradeDetails = subjectGradeCalculation($subjectMarkPercentage, $gradeScaleDetails);

                // checking subject countable or not
                if($classSubProfile->is_countable==1){

                    // checking subject current marks
                    if(empty($subjectCurrentMarks) || ($subjectCurrentMarks==null)) $subjectCurrentMarks = 0;

                    // checking subject grade status
                    if($gradeDetails['grade']!='F' AND $gradeDetails['grade']!='N/A'  AND array_key_exists($singleGrade->std_id, $failedList)==false){
                        // my current marks
                        $myCurrentMarks = (int)round($subjectCurrentMarks*100);
                        // checking
                        if(array_key_exists($singleGrade->std_id, $meritList)==true){
                            $meritList[$singleGrade->std_id] += $myCurrentMarks;
                            $meritListWithExtraMark[$singleGrade->std_id] += $subjectCurrentMarks;
                        }else{
                            $meritList[$singleGrade->std_id] = $myCurrentMarks;
                            $meritListWithExtraMark[$singleGrade->std_id] = $subjectCurrentMarks;
                        }
                    }else{
                        // checking merit list
                        if(array_key_exists($singleGrade->std_id, $meritList)==true){
                            $preMarks = (($meritList[$singleGrade->std_id])+((int)round($subjectCurrentMarks*100)));
                            // unset student marks
                            unset($meritList[$singleGrade->std_id]);
                            unset($meritListWithExtraMark[$singleGrade->std_id]);
                        }else{
                            $preMarks = (int)round($subjectCurrentMarks*100);
                        }

                        // checking
                        if(array_key_exists($singleGrade->std_id, $failedList)==true){
                            $failedList[$singleGrade->std_id] += $preMarks;
                            $failedSubList[$singleGrade->std_id] += 1;
                        }else{
                            $failedList[$singleGrade->std_id] = $preMarks;
                            $failedSubList[$singleGrade->std_id] = 1;
                        }
                    }

                    // checking class subject marks
                    if(array_key_exists($singleGrade->class_sub_id, $subjectMarksList)==true){
                        // subject current marks
                        $subjectPreviousMarks = $subjectMarksList[$singleGrade->class_sub_id];
                        // compare current marks with
                        if($subjectCurrentMarks>$subjectPreviousMarks){
                            // replace subject marks
                            $subjectMarksList[$singleGrade->class_sub_id] = $subjectCurrentMarks;
                        }
                    }else{
                        // add subject marks
                        $subjectMarksList[$singleGrade->class_sub_id] = $subjectCurrentMarks;
                    }
                }
            }

            // qry maker for student semester extra book marks list
            $extraBookQry = [
                'semester'=>$semesterId, 'section'=>$section, 'batch'=>$batch, 'a_level'=>$level,
                'a_year'=>$academicYear, 'campus'=>$campusId, 'institute'=>$instituteId
            ];
            // find student extra book
            $stdExtraBookList = $this->extraBook->where($extraBookQry)->get(['id', 'std_id', 'extra_marks']);

            // student extra book mark list looping
            foreach ($stdExtraBookList as $singleStdExtraBook) {
                // student id
                $stdId = $singleStdExtraBook->std_id;
                // total mark counter
                $assTotalMarks = 0;

                // student extra marks
                $stdExtraMarksDetails = json_decode($singleStdExtraBook->extra_marks);
                $stdExtraMarks = $stdExtraMarksDetails->marks;
                // student extra marks looping
                foreach ($stdExtraMarks as $mark) {
                    // checking
                    if(!empty($mark) AND $mark!=null){
                        // extra mark input
                        $assTotalMarks += $mark;
                    }
                }
                // store student extra marks
                $extraMarksList[$stdId] = $assTotalMarks;

                // add extra marks with class subject total marks
                if(array_key_exists($stdId, $meritListWithExtraMark)==true){
                    // total marks with extra
                    $totalMarksWithExtra = ($meritListWithExtraMark[$stdId]+$assTotalMarks);
                    // update student extra marks
                    $meritListWithExtraMark[$stdId] = (int)round($totalMarksWithExtra*100);
                }
            }
        }

        // array sorting
        arsort($meritList);
        // array sorting
        arsort($failedList);
        // array sorting
        arsort($failedSubList);
        // array sorting
        arsort($extraMarksList);
        // array sorting
        arsort($meritListWithExtraMark);

        // return variable
        return [
            'subject_highest_marks'=>$subjectMarksList,
            'merit_list'=>array_unique($meritList),
            'failed_merit_list'=>array_unique($failedList),
            'failed_sub_list'=>$failedSubList,
            'extra_highest_marks'=>$extraMarksList,
            'merit_list_with_extra_mark'=>array_unique($meritListWithExtraMark)
        ];
    }

    public function getStudentSubjectGroupGradeMark($scale, $stdId, $semester, $subjectAssessmentArrayList)
    {
        //  std grade list
        $stdGradeList = $this->studentGrade->where([
            'std_id'=>$stdId, 'scale_id'=>$scale, 'semester'=>$semester,
            'campus'=>$this->academicHelper->getCampus(),
            'institute'=>$this->academicHelper->getInstitute()
        ])->get();

        // all subject assessment list
        $allSubCatAssMarkList = count($subjectAssessmentArrayList)>0?$subjectAssessmentArrayList['subject_list']:[];

        // grade scale scale details
        $gradeScaleDetails = $this->gradeDetails->where('grade_id', $scale)->orderBy('sorting_order', 'ASC')->get();

        // checking
        if($stdGradeList->count()>0){
            // response data
            $subTotalMarks = 0;
            $subTotalObtained = 0;
            $subPointsObtained = 0;
            $subPointsIncomplete = 0;
            $maxPointTotal = 0;
            $subFailed = 0;

            $data = array();
            // stdMark looping
            foreach ($stdGradeList as $singleGrade){

                // class subject profile
                $classSubProfile = $singleGrade->classSubject(); // subject_id, class_id, id
                // class subject type
                $classSubType = $classSubProfile->subject_type;
                // class subject group
                $classSubGroup = $classSubProfile->subject_group;
                // subject profile
                $subjectProfile = $classSubProfile->subject(); // institute, campus
                // single subject category assessment list
                $subCatAssMarkList = (array)(array_key_exists($subjectProfile->id, $allSubCatAssMarkList)?$allSubCatAssMarkList[$subjectProfile->id]:[]);

                // subject group
                // $subjectGroupAssignProfile = $subjectProfile->checkSubGroupAssign();
                //$subjectGroupProfile = $subjectGroupAssignProfile?$subjectGroupAssignProfile->subjectGroup():null;

                $subjectGroupProfile = $classSubGroup>0?$classSubProfile->subjectGroup():[];
                // subject marks
                $gradeMark = $singleGrade->gradeMark()->marks;

                // Checking
                if($subCatAssMarkList){
                    // grade scale
                    $gradeScale = $this->grade->orderBy('name', 'ASC')->where('id',$scale)->first(['id', 'name', 'grade_scale_id']);
                    // mark details with weighted average
                    $marksDetails = (object)$this->weightedAverageGradeMarkDetails(json_decode($gradeMark), $gradeScale, $subCatAssMarkList);
                }else{
                    // analysing marks for details information
                    $marksDetails = (object)$this->gradeMarkDetails(json_decode($gradeMark, true));
                }

                // letter grade
                $letterGradeDetails = (object)$this->subjectGradeCalculation((int)$marksDetails->percentage, $gradeScaleDetails);
                // grade data
                $data[$classSubGroup][$subjectProfile->id] = array(
                    'has_group'=>$classSubGroup>0?1:0,
                    'group_id'=>$classSubGroup,
                    'grade_id'=>$singleGrade->id,
                    'cs_id'=>$singleGrade->class_sub_id,
                    'sub_name'=> $subjectProfile->subject_name,
                    'sub_group_name'=> $subjectGroupProfile?$subjectGroupProfile->name:'No Group',
                    'mark_id'=>$singleGrade->gradeMark()->id,
                    'mark'=>json_decode($gradeMark),
                    'credit'=>$singleGrade->subject_credit,
                    'exam_mark'=>$classSubProfile->exam_mark,
                    'pass_mark'=>$classSubProfile->pass_mark,
                    'is_countable'=>$classSubProfile->is_countable,
                    'type'=>$classSubProfile->subject_type,
                    'total'=>$marksDetails->total,
                    'obtained'=>round($marksDetails->obtained, 2, PHP_ROUND_HALF_UP),
                    'percentage'=>(int)$marksDetails->percentage,
                    'letterGrade'=>$letterGradeDetails?$letterGradeDetails->grade:'N/A',
                    'letterGradePoint'=>$letterGradeDetails?$letterGradeDetails->point:'N/A',
                    'cat_marks_list'=>$marksDetails->cat_marks_list
                );

                // checking group subject
                if($classSubGroup){
                    // checking group total
                    if(array_key_exists('g_total', $data[$classSubGroup])==true){
                        $data[$classSubGroup]['g_total'] += $classSubProfile->exam_mark;
                        $data[$classSubGroup]['g_obtained'] += round($marksDetails->obtained, 2, PHP_ROUND_HALF_UP);
                    }else{
                        $data[$classSubGroup]['g_total'] = $classSubProfile->exam_mark;
                        $data[$classSubGroup]['g_obtained'] = round($marksDetails->obtained, 2, PHP_ROUND_HALF_UP);
                    }
                }

                // subtotal
                $subTotalMarks = ($subTotalMarks+$marksDetails->total);
                $subTotalObtained = ($subTotalObtained+$marksDetails->obtained);
                if($letterGradeDetails){
                    // checking letter grade
                    if($letterGradeDetails->grade != 'F'){
                        // count total obtained point
                        $subPointsObtained = ($subPointsObtained+($letterGradeDetails->max_point*$letterGradeDetails->point));
                        // count total max point
                        $maxPointTotal = ($maxPointTotal+$letterGradeDetails->max_point);
                    }else{
                        $subPointsIncomplete = ($subPointsIncomplete+$letterGradeDetails->max_point);
                        // failed subject counter
                        $subFailed += 1;
                    }
                }
            }

            $data['result']['total_points'] = $maxPointTotal;
            $data['result']['total_points_incomplete'] = $subPointsIncomplete;
            $data['result']['total_marks'] = $subTotalMarks;
            $data['result']['total_obtained'] = $subTotalObtained;
            $data['result']['total_gpa'] = $maxPointTotal==0?0:($subPointsObtained/$maxPointTotal);
            $data['result']['total_percent'] = round((($subTotalObtained*100)/$subTotalMarks), 2, PHP_ROUND_HALF_UP);
            $data['result']['total_failed_sub'] = $subFailed;

            // return variable
            return $data;
        }else{
            return null;
        }
    }

// default grade Mark Details
    public function gradeMarkDetails($subjectMarks)
    {
        $mark = $subjectMarks; // must be json decoded
        $cat_count = $mark['cat_count'];
        $cat_list = $mark['cat_list'];

        $totalMark = 0;
        $obtainedMarks = 0;
        // category looping
        for($x=1; $x<=$cat_count; $x++){
            // custom catId
            $myCatId = 'cat_'.$x;
            // checking catId
            if(array_key_exists($myCatId, $cat_list)==false)continue;
            // receive category details from category list
            $cat_id = $cat_list[$myCatId];
            $category = $mark['cat_'.$cat_id];
            $ass_count = array_key_exists('ass_count', $category)?$category['ass_count']:0;
            $ass_list = array_key_exists('ass_list', $category)?$category['ass_list']:[];

            // assessment looping
            for($k=1; $k<=$ass_count; $k++){
                // custom catId
                $myAssId = 'ass_'.$k;
                // checking catId
                if(array_key_exists($myAssId, $ass_list)==false)continue;
                // receive assessment details from assessment list
                $ass_id = $ass_list[$myAssId];
                $assessment = $category['ass_'.$ass_id];
                $ass_mark = $assessment['ass_mark'];
                $ass_points = $assessment['ass_points'];
                // counting
                $obtainedMarks = ($obtainedMarks+$ass_mark);
                $totalMark = ($totalMark+$ass_points);
            }
        }
        // percentage
        $percentage = $totalMark>0?($obtainedMarks*100)/$totalMark:0;

        // return response array data
        return array('total'=>$totalMark, 'obtained'=>$obtainedMarks, 'percentage'=>$percentage, 'cat_marks_list'=>null);
    }

// weighted average grade Mark Details
    public function weightedAverageGradeMarkDetails($subjectMarks, $gradeScale, $subCatAssMarkList)
    {
        // assessmentInfo and $assessmentWAInfo
        $assessmentInfo = array();
        $assessmentWAInfo = array();
        $assessmentObtainedMarksArrayList = array();
        // find subject cat mark list
        $allCatAssMarkList = (array)(count($subCatAssMarkList)>0?$subCatAssMarkList['cat_list']:[]);

        // checking assessment category
        if($gradeScale->assessmentsCount()>0){
            // grading scale assessment category list
            $allCategoryList = $gradeScale->assessmentCategory();
            // checking $allCategoryList
            if(!empty($allCategoryList) AND $allCategoryList->count()>0){
                // category list looping
                foreach($allCategoryList as $category){
                    // checking category type
                    if($category->is_sba==1) continue;

                    $singleCatAssMarkList = (array) (array_key_exists($category->id, $allCatAssMarkList)?$allCatAssMarkList[$category->id]:[]);
                    // category mark list
                    $categoryExamMark = $singleCatAssMarkList?$singleCatAssMarkList['exam_mark']:0;
                    // checking
                    if($categoryExamMark>0){
                        // find all assessment mark list
                        $allAssMarkList = $singleCatAssMarkList?$singleCatAssMarkList['ass_list']:[];
                        // find all assessment list
                        $allAssessmentList = $category->allAssessment($gradeScale->id);
                        // checking assessment list
                        if(!empty($allAssessmentList) AND $allAssessmentList->count()>0){
                            // assessment list looping
                            foreach($allAssessmentList as $assessment){
                                // category mark list
                                $singleAssMarkList = (array) (array_key_exists($assessment->id, $allAssMarkList)==true?$allAssMarkList[$assessment->id]:[]);
                                // category mark list
                                $assessmentExamMark = $singleAssMarkList?$singleAssMarkList['exam_mark']:0;
                                // set assessment mark info
                                $assessmentInfo[$category->id][] = ['id'=>$assessment->id,'points'=>$assessmentExamMark, 'w_average'=>$categoryExamMark];
                            }
                        }
                        // category weighted average
                        $assessmentWAInfo[$category->id]= $categoryExamMark;
                    }
                }
            }
        }

        $mark = (array)$subjectMarks; // must be json decoded
        $totalMark = 0;
        $obtainedMarks = 0;
        $totalSubjectMarks =0;
        // assessment info
        foreach ($assessmentInfo as $headCatId=>$headAssList){
            // custom catId
            $catId = 'cat_'.$headCatId;
            // total assessment and points
            $totalAssessmentMarks = 0;
            $totalAssessmentPoints = 0;

            // catId checking
            if(array_key_exists($catId, $mark)){
                // find category marks list from mark
                $categoryMarksList = (array) $mark[$catId];

                // assessment list looping
                foreach ($headAssList as $headAssDetails){
                    // custom assId
                    $assId = 'ass_'.$headAssDetails['id'];
                    // assId checking
                    if(array_key_exists($assId, $categoryMarksList)){
                        // find assessment marks list from categoryMarksList
                        $assessment = (array)$categoryMarksList[$assId];
                        // count total assessment marks and points
                        $totalAssessmentMarks += floatval(round($assessment['ass_mark'], 2, PHP_ROUND_HALF_UP));
                        $totalAssessmentPoints += floatval($assessment['ass_points']);
                        $totalSubjectMarks += floatval(round($assessment['ass_mark'], 2, PHP_ROUND_HALF_UP));
                    }else{
                        // count total assessment marks and points
                        $totalAssessmentMarks += 0;
                        $totalAssessmentPoints += floatval($headAssDetails['points']);
                    }
                }
            }

            // category weighted average
            $catAssignedMark = $assessmentWAInfo[$headCatId];
            // checking $catAssignedMark marks
            $catMarkPercentage = $totalAssessmentPoints>0?(($totalAssessmentMarks*100)/$totalAssessmentPoints):0;
            $catWeightedAverage = $catMarkPercentage>0?(($catMarkPercentage*$catAssignedMark)/100):0;

            // count
            $obtainedMarks +=$catWeightedAverage;
            // category total marks
            $totalMark += $catAssignedMark;
            // assessment marks recording
            $assessmentObtainedMarksArrayList[$headCatId] = floatval(round($catWeightedAverage, 2, PHP_ROUND_HALF_UP));
        }

        // percentage
        $percentage = $totalMark>0?($obtainedMarks*100)/$totalMark:0;
        // return response array data
        return [
            'total'=>$totalMark,
            'obtained'=> floatval(round($obtainedMarks, 2, PHP_ROUND_HALF_UP)),
            'percentage'=>$percentage,
            'cat_marks_list'=>$assessmentObtainedMarksArrayList
        ];
    }

// subject grade calculation
    public function subjectGradeCalculation($subjectMarksPercentage, $gradeScaleDetails)
    {
        // subject total marks in percentage
        $percentage = floatval($subjectMarksPercentage);
        $maxPoints = floatval(0.00);
        // looping
        foreach ($gradeScaleDetails as $grade){
            // max point checking
            if($grade->points > $maxPoints){
                $maxPoints = ($maxPoints+$grade->points);
            }

        }
        // looping
        foreach ($gradeScaleDetails as $grade){
            // max mark
            $maxMark = floatval($grade->max_per);
            // min mark
            $minMark = floatval($grade->min_per);
            // grade mark (letter grade)
            $gradeMark  = ['grade'=>$grade->name, 'point'=>$grade->points, 'max_point'=>$maxPoints];
            // grade checking
            if($percentage>=$minMark && $percentage<=$maxMark){
                return $gradeMark;
            }
        }
    }


// get subject gradeMark details
    public function getSubjectGrdeMark($scale, $subject, $semesterId)
    {
        $stdMarkList = $this->studentGrade->where([
            'scale_id'=>$scale,
            'class_sub_id'=>$subject,
            'semester'=>$semesterId,
            'campus'=>$this->academicHelper->getCampus(),
            'institute'=>$this->academicHelper->getInstitute()
        ])->get();

        // checking
        if($stdMarkList->count()>0){
            // response data
            $data = array();
            // stdMark looping
            foreach ($stdMarkList as $singleGrade){
                // stdProfile
                $stdProfile = $singleGrade->student();
                // std entollment
                $enroll = $stdProfile->enroll();
                // mark Profile
                $markProfile = $singleGrade->gradeMark();
                // data set
                $data['grade'][] = array(
                    'grade_id'=>$singleGrade->id,
                    'std_id'=>$singleGrade->std_id,
                    'gr_no'=>$enroll->gr_no,
                    'std_name'=>$stdProfile->first_name." ".$stdProfile->middle_name." ".$stdProfile->last_name,
                    'mark_id'=>$markProfile->id,
                    'mark'=>json_decode($markProfile->marks)
                );
            }
            // return variable
            return $data;
        }else{
            return null;
        }
    }
// get subject gradeMark details
    public function getSubjectGrdeMarkFirstTime($scale, $subject, $semesterId)
    {
        // response data
        $data = array();

        $stdMarkList = $this->studentGrade->where([
            'scale_id'=>$scale,
            'class_sub_id'=>$subject,
            'semester'=>$semesterId,
            'campus'=>$this->academicHelper->getCampus(),
            'institute'=>$this->academicHelper->getInstitute()
        ])->get();

        // checking
        if($stdMarkList->count()>0){

            // stdMark looping
            foreach ($stdMarkList as $singleGrade){
                // stdProfile
                $stdProfile = $singleGrade->student();
                // mark Profile
                $markProfile = $singleGrade->gradeMark();
                // data set
                $data[$stdProfile->id] = [
                    'grade_id'=>$singleGrade->id,
                    'std_id'=>$singleGrade->std_id,
                    'mark_id'=>$markProfile->id,
                    'mark'=>(array)json_decode($markProfile->marks)
                ];

            }
            // return variable
            return $data;
        }else{
            return null;
        }
    }


    public function getSubjectGrdeMarkDemo($scale, $subject,$semesterId, $category, $assessment, $listType, $operator, $inputAssMarks)
    {
        $stdMarkList = $this->studentGrade->where([
            'scale_id'=>$scale,
            'class_sub_id'=>$subject,
            'semester'=>$semesterId,
            'campus'=>$this->academicHelper->getCampus(),
            'institute'=>$this->academicHelper->getInstitute()
        ])->get();
        // main assessmentProfile
        $assessmentProfile = $this->assessments->find($assessment);

        // checking
        if($stdMarkList->count()>0){
            // response data
            $data = array();
            // stdMark looping
            foreach ($stdMarkList as $singleGrade){
                // stdProfile
                $stdProfile = $singleGrade->student();
                // Grade Mark Profile
                $gradeMarkProfile = $singleGrade->gradeMark();
                // mark Profile
                $markProfile = (array)json_decode($gradeMarkProfile->marks);
                // assessment and category  id
                $catId = 'cat_'.$category;
                $assId = 'ass_'.$assessment;
                // checking category and assessment key if exits
                if(array_key_exists($assId, (array)$markProfile[$catId])){
                    // assProfile form gradeMark/result field
                    $assProfile = $markProfile[$catId]->$assId;
                    // assessmentResult
                    $assessmentResult = ($assProfile->ass_mark >= $assessmentProfile->passing_points?"PASSED":"FAILED");
                    // checking if operator and input marks are exists
                    if($operator != null AND $inputAssMarks != null){
                        $assInfo = null;
                        $ass_marks = $assProfile->ass_mark;
                        // assessment information
                        $sampleAssInfo = [
                            'ass_mark' => $assProfile->ass_mark, 'ass_points' => $assProfile->ass_points,
                            'pass_points' => $assessmentProfile->passing_points, 'ass_result' => $assessmentResult,
                        ];
                        // checking
                        if($operator==1) {
                            if($ass_marks==$inputAssMarks) {$assInfo = $sampleAssInfo;}
                        }elseif($operator==2){
                            if($ass_marks>$inputAssMarks) {$assInfo = $sampleAssInfo;}
                        }elseif($operator==3){
                            if($ass_marks<$inputAssMarks) {$assInfo = $sampleAssInfo;}
                        }elseif($operator==4){
                            if($ass_marks>=$inputAssMarks) {$assInfo = $sampleAssInfo;}
                        }elseif($operator==5){
                            if($ass_marks<=$inputAssMarks) {$assInfo = $sampleAssInfo;}
                        }
                    }else{
                        // assessment information
                        $assInfo = [
                            'ass_mark'=>$assProfile->ass_mark, 'ass_points'=>$assProfile->ass_points,
                            'pass_points'=>$assessmentProfile->passing_points, 'ass_result'=>$assessmentResult,
                        ];
                    }

                    // checking
                    if($listType=="PASSED"){
                        if($assessmentResult=='PASSED'){
                            // store std assessment detail
                            $data[$stdProfile->id] = $assInfo;
                        }
                    }elseif($listType=="FAILED"){
                        if($assessmentResult=='FAILED'){
                            // store std assessment detail
                            $data[$stdProfile->id] = $assInfo;
                        }
                    }else{

                        // checking if operator and input marks are exists
                        if($operator != null AND $inputAssMarks != null){
                            if($assInfo) $data[$stdProfile->id] = $assInfo;
                        }else{
                            // store std assessment detail
                            $data[$stdProfile->id] = $assInfo;
                        }
                    }
                }
            }
            // return variable
            return $data;
        }else{
            return null;
        }
    }

// get class-section-subject student list
    public function getClsssSectionStudentList($class, $section)
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
            // find student profile
            $myProfile = $student->student();
            // student array list
            $studentList[] = [
                'id' => $student->std_id, 'gr_no' => $student->gr_no, 'status' => $student->status,
                'name' => $student->first_name." ".$student->middle_name." ".$student->last_name,
                'username' => $student->username,
                'gender' => $student->gender,
                'phone' => $myProfile->phone,
            ];
        }
        // return student list
        return $studentList;
    }

// import gradebook
    public function importGradeBook()
    {
        return view('academics::manage-assessments.modals.grade-book-import');
    }

// export grade book
    public function exportGradeBook(Request $request)
    {
        // request details
        $level = $request->input('academic_level');
        $batch = $request->input('batch');
        $section = $request->input('section');
        $subject = $request->input('subject');
        $semester = $request->input('semester');
        $academicYear = $this->academicHelper->getAcademicYear();
        $campusId = $this->academicHelper->getCampus();
        $instituteId = $this->academicHelper->getInstitute();
        $scaleId = $request->input('grade_scale');
        // find class subject profile
        $classSubjectProfile = $this->academicHelper->getClassSubject($subject);
        // find batch
        $batchProfile = $this->academicHelper->findBatch($batch);
        $sectionProfile = $this->academicHelper->findSection($section);
        $instituteProfile = $this->academicHelper->findInstitute($instituteId);

        if ($batchProfile->get_division()) {
            $batchName = $batchProfile->batch_name . " (" . $batchProfile->get_division()->name.") - ". $sectionProfile->section_name." - ".$classSubjectProfile->subject()->subject_name;
        } else {
            $batchName = $batchProfile->batch_name . " - " . $sectionProfile->section_name." - ".$classSubjectProfile->subject()->subject_name;
        }
        // grading scale
        $gradeScale = $this->grade->orderBy('name', 'ASC')->where('id', $request->input('grade_scale'))->first(['id', 'name', 'grade_scale_id']);
        // student list
        $studentList =  $this->getClsssSectionStudentList($request->input('batch'), $request->input('section'));
        // subject gradeMark details
        $subjectGrades = $this->getSubjectGrdeMark($request->input('grade_scale'), $request->input('subject'), $request->input('semester'));

        // weightedAverageArrayList
        $weightedAverageArrayList = $this->getGradeScaleAssessmentCategoryWeightedAverageList($level, $batch, $campusId, $instituteId, $scaleId);

        // SubjectAssessmentList
        $subjectAssessmentArrayList =  $this->getClassSubjectAssessmentDetails($level, $batch, $academicYear, $classSubjectProfile->subject_id, $scaleId, $campusId, $instituteId);

        // share all variables with the view
        view()->share(compact('gradeScale', 'studentList', 'subjectGrades', 'weightedAverageArrayList', 'subjectAssessmentArrayList'));

        //generate excel
        Excel::create($instituteProfile->institute_alias.' -- '.$batchName, function ($excel) {
            $excel->sheet('Student Assessment Form', function ($sheet) {
                // Font family
                $sheet->setFontFamily('Comic Sans MS');
                // Set font with ->setStyle()
                $sheet->setStyle(array('font' => array('name'=>'Calibri','size' => 12)));
                // cell formatting
                $sheet->setAutoSize(true);
                // Set all margins
                $sheet->setPageMargin(0.25);
                // mergeCell
                // $sheet->mergeCells(['C3:D1', 'E1:H1']);

                $sheet->loadView('academics::manage-assessments.modals.grade-book-export');
            });
        })->download('xlsx');
    }

// upload grade book xlsx file (ajax request use only)
    public function uploadGradeBook(Request $request){

        $level = $request->input('academic_level');
        $batch = $request->input('batch');
        $section = $request->input('section');
        $classSubjectId = $request->input('subject');
        $semesterId = $request->input('semester');
        // find subject id
        $subject = $this->academicHelper->getClassSubject($classSubjectId)->subject()->id;

        $academicYear = $this->academicHelper->getAcademicYear();
        $instituteId = $this->academicHelper->getInstitute();
        $campusId = $this->academicHelper->getCampus();

        $scale = $this->getGradeScaleId($batch, $section);
        $subjectGrades = null;
        // grading scale
        $gradeScale = $this->grade->orderBy('name', 'ASC')->where('id', $scale)->first(['id', 'name', 'grade_scale_id']);
        // student list
        $studentList =  $this->getClsssSectionStudentList($batch, $section);
        // find exam status
        $examStatus = $this->examStatus->where([
            'semester'=>$semesterId, 'section'=>$section, 'batch'=>$batch, 'level'=>$level,
            'academic_year'=>$academicYear, 'campus'=>$campusId, 'institute'=>$instituteId,
        ])->first();

        // receive input file
        $studentGradeFile = $request->file('grade_book');
        // checking
        if($studentGradeFile){
            // get file real path
            $filePath = $studentGradeFile->getRealPath();
            // receive data from the input file
            $allGrades = Excel::load($filePath, function($reader) {})->get();
            // checking data size
            if($allGrades->count() > 500){
                return redirect()->back()->withErrors("error", "Can not insert above 500!!");
            }
            // result module for sms use
            $resultModule=$this->autoSmsModule->where('status_code',"RESULT")->where('status',1)->get();
            // checking
            if(!empty($allGrades) && $allGrades->count()){
                // all grade json encode
                $allGrades = json_encode($allGrades);
                // weightedAverageArrayList
                $weightedAverageArrayList = $this->getGradeScaleAssessmentCategoryWeightedAverageList($level, $batch, $campusId, $instituteId, $scale);
                // $subjectAssessmentArrayList
                $subjectAssessmentArrayList =  $this->getClassSubjectAssessmentDetails($level, $batch, $academicYear, $subject, $scale, $campusId, $instituteId);
                // return veiw with variables
                return view('academics::manage-assessments.modals.grade-book', compact('gradeScale', 'studentList', 'allGrades', 'subjectGrades','scale', 'resultModule', 'subjectAssessmentArrayList', 'weightedAverageArrayList', 'examStatus'));
            }else{
                return redirect()->back()->withErrors("error", "File not found");
            }
        }
    }


//////////////////////////////// Grade Setup page ////////////////////////////////

// create assessment category
    public function createAssessmentCategory()
    {
        // return assessment add view
        return view('academics::manage-assessments.modals.grade-category')->with('gradeCategoryProfile', null);
    }

// store assessment category
    public function storeAssessmentCategory(Request $request)
    {
        // validator
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:30',
            'is_sba' => 'required',
        ]);

        // checking
        if ($validator->passes()) {
            // gradeCategoryProfile new instance
            $gradeCategoryProfile = new $this->gradeCategory();
            // store gradeCategoryProfile
            $gradeCategoryProfile->name = $request->input('name');
            $gradeCategoryProfile->is_sba = $request->input('is_sba');
            $gradeCategoryProfile->institute = $this->academicHelper->getInstitute();
            $gradeCategoryProfile->campus =  $this->academicHelper->getCampus();
            // save gradeCategoryProfile
            $gradeCategoryProfileCreated = $gradeCategoryProfile->save();

            // checking
            if ($gradeCategoryProfileCreated) {
                // success msg
                Session::flash('success', 'Assessment Category Added');
                // redireting
                return redirect()->back();
            }
        } else {
            // warning msg
            Session::flash('warning', 'Invalid Information');
            // redirecting
            return redirect()->back()->withInputs($validator);
        }
    }

// edit assessment category
    public function editAssessmentCategory($id)
    {
        // gradeCategoryProfile
        $gradeCategoryProfile = $this->gradeCategory->where('id', $id)->first();
        // return assessment add view
        return view('academics::manage-assessments.modals.grade-category', compact('gradeCategoryProfile'));
    }

// update assessment category
    public function updateAssessmentCategory(Request $request, $id)
    {
        // validator
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:30',
            'is_sba' => 'required',
        ]);

        // checking
        if ($validator->passes()) {
            // gradeCategoryProfile
            $gradeCategoryProfile = $this->gradeCategory->where('id', $id)->first();
            // store gradeCategoryProfile
            $gradeCategoryProfile->name = $request->input('name');
            $gradeCategoryProfile->is_sba = $request->input('is_sba');
            // save gradeCategoryProfile
            $gradeCategoryProfileCreated = $gradeCategoryProfile->save();

            // checking
            if ($gradeCategoryProfileCreated) {
                // success msg
                Session::flash('success', 'Assessment Category Updated');
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

// destroy assessment category
    public function destroyAssessmentCategory($id)
    {
        // gradeCategoryProfile
        $gradeCategoryProfile = $this->gradeCategory->where('id', $id)->first();
        // delete the gradeCategoryProfile
        $gradeCategoryProfileDeleted = $gradeCategoryProfile->delete();

        // checking
        if ($gradeCategoryProfileDeleted) {
            Session::flash('success', 'Category Deleted');
            return redirect()->back();
        } else {
            Session::flash('warning', 'Unable to perform the action');
            return redirect()->back();
        }
    }

// create grade
    public function createGrade()
    {
        return view('academics::manage-assessments.modals.grade-scale-add');
    }

// create grade
    public function storeGrade(Request $request)
    {
        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'grade_name'   => 'required',
            'gradeCounter' => 'required',
        ]);

        // storing requesting input data
        if ($validator->passes()) {
            // row counter
            $rowCount = $request->input('gradeCounter');
            //loop counter
            $i = 0;
            // Start transaction!
            DB::beginTransaction();

            // grade creation
            try {
                // Validate, then create if valid
                $gradeProfile = new $this->grade();
                // store user profile
                $gradeProfile->grade_scale_id = $request->input('scale_name');
                $gradeProfile->name           = $request->input('grade_name');
                $gradeProfile->institute = $this->academicHelper->getInstitute();
                $gradeProfile->campus =  $this->academicHelper->getCampus();
                // save user profile
                $gradeProfileCreated = $gradeProfile->save();

            } catch (ValidationException $e) {
                // Rollback and then redirect
                // back to form with errors
                // Redirecting with error message
                DB::rollback();
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

            // gredeDetails profile creation
            try {
                // single grade
                for ($x = 1; $x <= $rowCount; $x++) {
                    // receive the current grade form inputs
                    $grade = $request->$x;
                    // creating new class subject
                    $gradeDetailsProfile = new $this->gradeDetails();
                    // store grade profile
                    $gradeDetailsProfile->grade_id      = $gradeProfile->id;
                    $gradeDetailsProfile->name          = $grade['grade'];
                    $gradeDetailsProfile->min_per       = $grade['min'];
                    $gradeDetailsProfile->max_per       = $grade['max'];
                    $gradeDetailsProfile->points        = $grade['points'];
                    $gradeDetailsProfile->sorting_order = $x;
                    // saving gradeDetailsProfile
                    $gradeDetailsProfileCreated = $gradeDetailsProfile->save();
                    // checking
                    if ($gradeDetailsProfileCreated) {
                        // count classSubjectProfile creation
                        $i = $i + 1;
                    }
                }
            } catch (ValidationException $e) {
                // Rollback and then redirect
                // back to form with errors
                DB::rollback();
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

            // If we reach here, then data is valid and working. Commit the queries!
            DB::commit();

            // checking and redirecting
            if ($rowCount == $i) {
                // grade details
                return $this->grade->where([
                    'grade_scale_id'=>$this->getGradeScaleTypeId(),
                    'institute'=> $this->academicHelper->getInstitute(),
                    'campus'=> $this->academicHelper->getCampus()
                ])->get();
            } else {
                Session::flash('warning', 'unable to Grade');
                // receiving page action
                return redirect()->back();
            }
        } else {
            Session::flash('warning', 'Invalid Information');
            // receiving page action
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    public function editGrade($id)
    {
        // grade profile
        $gradeProfile = $this->grade->where('id', $id)->first();
        // return view with gradeProfile variable
        return view('academics::manage-assessments.modals.grade-scale-update', compact('gradeProfile'));
    }

    public function updateGrade(Request $request, $id)
    {
        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'grade_name'         => 'required',
            'gradeCounter'       => 'required',
            'gradeDeleteCounter' => 'required',
        ]);

        // storing requesting input data
        if ($validator->passes()) {

            // row counter
            $rowCount    = $request->input('gradeCounter');
            $deleteCount = $request->input('gradeDeleteCounter');
            //loop counter
            $i = 0;
            // Start transaction!
            DB::beginTransaction();

            // grade creation
            try {
                // Validate, then create if valid
                $gradeProfile = $this->grade->where('id', $id)->first();
                // store grade profile
                $gradeProfile->name = $request->input('grade_name');
                // save user profile
                $gradeProfileUpdate = $gradeProfile->save();

                // now delete
                $allDeletedGrades = $request->input('deleteList');
                if ($deleteCount > 0) {
                    // single subject
                    for ($a = 1; $a <= $deleteCount; $a++) {
                        $deletedGradeDetailsId = $allDeletedGrades[$a];
                        // delete class subject profile
                        $deletedGradeDetailsProfile = $this->gradeDetails->where('id', $deletedGradeDetailsId)->first();
                        $deletedGradeDetailsProfile->delete();
                    }
                }

                // single grade
                for ($x = 1; $x <= $rowCount; $x++) {
                    // receive the current grade form inputs
                    $grade = $request->$x;
                    // gradeId
                    $gradeDetailsId = $grade['id'];
                    // checking
                    if ($gradeDetailsId==0) {
                        // creating new class subject
                        $gradeDetailsProfile = new $this->gradeDetails();
                        // store grade profile
                    } else {
                        // find new class subject
                        $gradeDetailsProfile = $this->gradeDetails->find($gradeDetailsId);
                        // store grade profile
                    }
                    // store grade profile
                    $gradeDetailsProfile->grade_id      = $gradeProfile->id;
                    $gradeDetailsProfile->name          = $grade['grade'];
                    $gradeDetailsProfile->min_per       = $grade['min'];
                    $gradeDetailsProfile->max_per       = $grade['max'];
                    $gradeDetailsProfile->points        = $grade['points'];
                    $gradeDetailsProfile->sorting_order = $x;


                    // saving gradeDetailsProfile
                    $gradeDetailsProfileSubmitted = $gradeDetailsProfile->save();
                    // checking
                    if ($gradeDetailsProfileSubmitted) {
                        // count classSubjectProfile creation
                        $i = $i + 1;
                    }
                }

            } catch (ValidationException $e) {
                // Rollback and then redirect
                // back to form with errors
                DB::rollback();
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

            // If we reach here, then
            // data is valid and working.
            // Commit the queries!
            DB::commit();

            // checking and redirecting
            if ($rowCount == $i) {
                // grade details
                // grade details
                return $this->grade->where([
                    'grade_scale_id'=>$this->getGradeScaleTypeId(),
                    'institute'=> $this->academicHelper->getInstitute(),
                    'campus'=> $this->academicHelper->getCampus()
                ])->get();
            } else {
                Session::flash('warning', 'unable to Grade');
                // receiving page action
                return redirect()->back();
            }
        } else {
            Session::flash('warning', 'Invalid Information');
            // receiving page action
            return redirect()->back()->withErrors($validator)->withInput();
        }

    }

    public function destroyGrade($id)
    {
        // grade profile
        $gradeProfile = $this->grade->where('id', $id)->first();
        // delete profile
        $gradeProfileDeleted = $gradeProfile->delete();
        if ($gradeProfileDeleted) {
            return ['status'=>'success', 'msg'=>'Grade Deleted Successfully'];
        }else{
            return ['status'=>'failed', 'msg'=>'Unable to Deleted Grade'];
        }

    }

// assign grade scale
    public function assignGradeScale()
    {
        // all academics levels
        $allAcademicsLevel = $this->academicHelper->getAllAcademicLevel();
        // return view with variable
        return view('academics::manage-assessments.modals.grade-scale-assign', compact('allAcademicsLevel'));
    }

    public function manageGradeScaleAssign(Request $request)
    {
        // request details
        $batch = $request->input('batch_id');
        $section = $request->input('section_id', null);
        $shift = $request->input('shift_id', null);
        $scaleId = $request->input('scale_id');
        $academicLevel = $request->input('level_id');
        $requestType = $request->input('request_type', 'LIST');
        // institute details
        $campus = $this->academicHelper->getCampus();
        $institute = $this->academicHelper->getInstitute();
        // academic Info
        $academicInfo = (object)['level'=>$academicLevel, 'batch'=>$batch, 'section'=>$section, 'shift'=>$shift];
        // find all grade list in this campus+institute
        $gradeList = $this->grade->where(['institute'=>$institute, 'campus'=>$campus])->get();
        // qry
        $qry = ['institute'=>$institute, 'campus'=>$campus, 'batch_id'=>$batch];

        // checking
        if($requestType=='ASSIGN'){
            // checking
            if($classGradeScale = $this->classGradeScale->where($qry)->withTrashed()->first()){
                // restore class grade scale
                if ($classGradeScale->trashed()) $classGradeScale->restore();
            }else{
                // new classGradeScale
                $classGradeScale = new $this->classGradeScale();
            }
            // store class grade scale details
            $classGradeScale->scale_id = $scaleId;
            $classGradeScale->level_id = $academicLevel;
            $classGradeScale->batch_id = $batch;
            $classGradeScale->campus = $campus;
            $classGradeScale->institute = $institute;
            // save class grade scale
            $classGradeScale->save();
            // return view with variable
            return view('academics::manage-assessments.modals.grade-scale-assign-list', compact('gradeList', 'academicInfo'));
        }else if($requestType=='LIST'){
            // return view with variable
            return view('academics::manage-assessments.modals.grade-scale-assign-list', compact('gradeList', 'academicInfo'));
        }else{
            abort(404);
        }
    }

    public function assignGradeCategory()
    {
        // all academics levels
        $allAcademicsLevel = $this->academicHelper->getAllAcademicLevel();
        // return view with variable
        return view('academics::manage-assessments.modals.assessment-assign', compact('allAcademicsLevel'));
    }

    public function manageGradeCategoryAssign(Request $request)
    {

        // request details
        $institute = $this->academicHelper->getInstitute();
        $campus = $this->academicHelper->getCampus();
        $academicLevel = $request->input('academic_level');
        $batch = $request->input('batch');
        $section = $request->input('section');
        $semester = $request->input('semester');
        $requestType = $request->input('request_type', 'LIST');
        // academic Info
        $academicInfo = (object)['level'=>$academicLevel, 'batch'=>$batch, 'section'=>$section, 'semester'=>$semester];
        // find all grade list in this campus+institute
        // $gradeCategoryList = $this->gradeCategory->where(['institute'=>$institute, 'campus'=>$campus])->get();

        $classGradeScaleProfile = $this->classGradeScale->where(['institute'=>$institute, 'campus'=>$campus, 'level_id'=>$academicLevel, 'batch_id'=>$batch])->first();


        // checking request type
        if($requestType=='ASSIGN'){
            // assessment list
            $assessmentList = $request->input('ass_list');
            // loop counter
            $loopCounter = 0;
            // assessment assign list
            $assignList = array();
            // Start transaction!
            DB::beginTransaction();
            // student user creation
            try {
                // looping
                foreach($assessmentList as $assCatId=>$assignDetails){
                    // assessment cat $assCatAssignId and $assCatResultCount
                    $assCatAssignId = $assignDetails['assign_id'];
                    $assCatResultCount = $assignDetails['result_count'];
                    // checking
                    if($assCatAssignId>0){
                        $gradeCatAssignProfile = $this->gradeCategoryAssign->find($assCatAssignId);
                    }else{
                        $gradeCatAssignProfile = new $this->gradeCategoryAssign();
                    }

                    // input details
                    $gradeCatAssignProfile->result_count = $assCatResultCount;
                    // checking create or update
                    if($assCatAssignId==0) {
                        $gradeCatAssignProfile->grade_cat_id = $assCatId;
                        $gradeCatAssignProfile->level = $academicLevel;
                        $gradeCatAssignProfile->batch = $batch;
                        $gradeCatAssignProfile->section = $section;
                        $gradeCatAssignProfile->semester = $semester;
                        $gradeCatAssignProfile->campus = $campus;
                        $gradeCatAssignProfile->institute = $institute;
                    }
                    // saving profile and checking
                    if($gradeCatAssignProfile->save()){
                        $loopCounter += 1;
                        // assessment assign list
                        $assignList['assign_list'][] = $gradeCatAssignProfile->id;
                    }
                }

            } catch (ValidationException $e) {
                // Rollback and then redirect back to form with errors
                DB::rollback();
                return redirect()->back();
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }
            // If we reach here, then data is valid and working.
            // Commit the queries!
            DB::commit();
            // checking
            if($loopCounter == count($assessmentList)){
                $view = view('academics::manage-assessments.modals.assessment-assign-list', compact('classGradeScaleProfile', 'academicInfo'))->render();
                return ['status'=>'success', 'html'=>$view];
            }else{
                return ['status'=>'failed', 'msg'=>'Unable to perform the function'];
            }
        }else{
            // return view with variable
            return view('academics::manage-assessments.modals.assessment-assign-list', compact('classGradeScaleProfile', 'academicInfo'));
        }
    }


//////////////////////////////// assessment page ////////////////////////////////

// create assessment
    public function createAssessment()
    {
        // grading category
        $allGradeCategory = $this->gradeCategory->where([
            'institute'=>$this->academicHelper->getInstitute(),
            'campus'=>$this->academicHelper->getCampus()
        ])->orderBy('name', 'ASC')->get();
        // grade scale
        $allGradeScale = $this->gradeScale->where([
//            'institute'=>$this->academicHelper->getInstitute(),
//            'campus'=>$this->academicHelper->getCampus()
        ])->get();
        return view('academics::manage-assessments.modals.assessment', compact('allGradeCategory', 'allGradeScale'))->with('assessmentProfile', null);
    }

// store assessment
    public function storeAssessment(Request $request)
    {

        // return $request->all();
        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'name'                 => 'required',
            'grading_category'     => 'required',
            'grade'                => 'required',
            'status'               => 'required',
            'cs_count' => 'required',
            'counts_overall_score' => 'required',
        ]);

        // storing requesting input data
        if ($validator->passes()) {
            // count overall score
            $countOverallScore = $request->input('counts_overall_score');
            // checking
            if($countOverallScore>0){
                $points = $request->input('points');
                $passingPoints = $request->input('passing_points');
            }else{
                $points = null;
                $passingPoints = null;
            }

            // academic year
            $academicYear = $this->getAcademicYearId();
            // checking
            if($request->input('cs_count')>0) {
                $csCount = $request->input('cs_count');
                $batchList = $request->input('batch');
                $sectionList = $request->input('section');
                // class section looping
                for($i=0; $i<$csCount; $i++){
                    $this->setClassSectionGradeScale($request->input('grade'), $sectionList[$i], $batchList[$i]);
                }
            }else{
                // academicsLevel
                $academicsLevel = $this->academicsLevel->where('academics_year_id', $academicYear)->get();
                // looping
                foreach ($academicsLevel as $level) {
                    foreach ($level->batch() as $batch) {
                        foreach ($batch->section() as $section) {
                            $this->setClassSectionGradeScale($request->input('grade'), $section->id, $batch->id);
                        }
                    }
                }
            }

            // assessment profile
            $assessmentProfile = new $this->assessments();
            // now storing the input items
            $assessmentProfile->name                 = $request->input('name');
            $assessmentProfile->grading_category_id  = $request->input('grading_category');
            $assessmentProfile->grade_id             = $request->input('grade');
            $assessmentProfile->status               = $request->input('status');
            $assessmentProfile->counts_overall_score  = $countOverallScore;
            $assessmentProfile->points               = $points;
            $assessmentProfile->passing_points       = $passingPoints;
            // saving
            $assessmentProfileCreated = $assessmentProfile->save();

            // checking
            if ($assessmentProfileCreated) {
                // success msg
                Session::flash('success', 'Assessment Added');
                // redirecting
                return redirect()->back();
            }
        } else {
            // success msg
            Session::flash('warning', 'Invalid Information');
            // redirecting
            return redirect()->back();
        }
    }

    public function editAssessment($id)
    {
        // assessmentProfile
        $assessmentProfile = $this->assessments->where('id', $id)->first();
        // grading category
        $allGradeCategory = $this->gradeCategory->where([
            'institute'=>$this->academicHelper->getInstitute(),
            'campus'=>$this->academicHelper->getCampus()
        ])->orderBy('name', 'ASC')->get();
        // grade scale
        $allGradeScale = $this->gradeScale->all();
        // return view with assessment Profile variable
        return view('academics::manage-assessments.modals.assessment', compact('allGradeCategory', 'allGradeScale', 'assessmentProfile'));
    }

    public function assessmentDetails($id)
    {
        // grade scale profile
        $gradeScaleProfile = $this->grade->find($id);
        // class grade scale
        $classGradeScale = $this->classGradeScale->where([
            'scale_id' =>$id,
            'institute'=> $this->academicHelper->getInstitute(),
            'campus'=> $this->academicHelper->getCampus()
        ])->orderBy('batch_id', 'asc')->distinct()->get(['batch_id']);
        // return view with variables
        return view('academics::manage-assessments.modals.assessment-details', compact('classGradeScale', 'gradeScaleProfile'));
    }


    public function updateAssessment(Request $request, $id)
    {
        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'name'                 => 'required',
            'grading_category'     => 'required',
            'grade'                => 'required',
            'status'               => 'required',
            'counts_overall_score'               => 'required',
        ]);

        // storing requesting input data
        if ($validator->passes()) {
            // count overall score
            $countOverallScore = $request->input('counts_overall_score');
            // checking
            if($countOverallScore>0){
                $points = $request->input('points');
                $passingPoints = $request->input('passing_points');
            }else{
                $points = null;
                $passingPoints = null;
            }

            // academic year
            $academicYear = $this->getAcademicYearId();
            // checking
            if($request->input('cs_count')>0) {
                $csCount = $request->input('cs_count');
                $batchList = $request->input('batch');
                $sectionList = $request->input('section');
                // class section looping
                for($i=0; $i<$csCount; $i++){
                    $this->setClassSectionGradeScale($request->input('grade'), $sectionList[$i], $batchList[$i]);
                }
            }else{
                // academicsLevel
                $academicsLevel = $this->academicsLevel->where('academics_year_id', $academicYear)->get();
                // looping
                foreach ($academicsLevel as $level) {
                    foreach ($level->batch() as $batch) {
                        foreach ($batch->section() as $section) {
                            $this->setClassSectionGradeScale($request->input('grade'), $section->id, $batch->id);
                        }
                    }
                }
            }

            // assessment profile
            $assessmentProfile = $this->assessments->where('id', $id)->first();
            // now storing the input items
            $assessmentProfile->name                 = $request->input('name');
            $assessmentProfile->grading_category_id  = $request->input('grading_category');
            $assessmentProfile->grade_id             = $request->input('grade');
            $assessmentProfile->status               = $request->input('status');
            $assessmentProfile->counts_overall_score  = $countOverallScore;
            $assessmentProfile->points               = $points;
            $assessmentProfile->passing_points       = $passingPoints;
            // saving
            $assessmentProfileCreated = $assessmentProfile->save();

            // checking
            if ($assessmentProfileCreated) {
                // success msg
                Session::flash('success', 'Assessment Updated');
                // redireting
                return redirect()->back();
            }
        } else {
            // success msg
            Session::flash('success', 'Assessment Updated');
            // redireting
            return redirect()->back();
        }
    }

    public function destroyAssessment($id)
    {
        // gradeprofile
        $assessmentProfile = $this->assessments->where('id', $id)->first();
        // delete profile
        $assessmentProfileDeleted = $assessmentProfile->delete();
        // return back
        return redirect()->back();
    }


    public function setClassSectionGradeScale($scaleId, $section, $batch)
    {
        // find classGradeScale
        $classGradeScale = $this->classGradeScale->where(['section_id' => $section, 'batch_id' => $batch])->first();
        // checking
        if ($classGradeScale) {
            // update classGradeScale details
            $classGradeScale->scale_id = $scaleId;
            $classGradeScale->section_id =$section;
            $classGradeScale->batch_id = $batch;
            // save classGradeScale
            $classGradeScaleSubmitted = $classGradeScale->save();
        } else {
            // new classGradeScale object
            $newClassGradeScale = new $this->classGradeScale();
            // input classGradeScale details
            $newClassGradeScale->scale_id = $scaleId;
            $newClassGradeScale->section_id =$section;
            $newClassGradeScale->batch_id = $batch;
            // save newClassGradeScale
            $classGradeScaleSubmitted = $newClassGradeScale->save();
        }
    }


// set class section weighted average
    public function assignWeightedAverage()
    {
        // all academics levels
        $allAcademicsLevel = $this->academicHelper->getAllAcademicLevel();
        // return view with variable
        return view('academics::manage-assessments.modals.grade-scale-weighted-average-assign', compact('allAcademicsLevel'));
    }


    public function manageWeightedAverageAssign(Request $request)
    {
        // request details
        $academicYear = $this->academicHelper->getAcademicYear();
        $institute = $this->academicHelper->getInstitute();
        $campus = $this->academicHelper->getCampus();
        $academicLevel = $request->input('level_id');
        $batch = $request->input('batch_id');
        $section = $request->input('section_id');
        $shift = $request->input('shift_id');
        $requestType = $request->input('request_type', 'LIST');
        // academic Info
        $academicInfo = (object)['level'=>$academicLevel, 'batch'=>$batch];
        // qry
        $qry = ['institute'=>$institute, 'campus'=>$campus, 'level_id'=>$academicLevel, 'batch_id'=>$batch];

        // $qry['scale_id'] = $scaleId;
        // class grade scale profile
        $classGradeScaleProfile = $this->classGradeScale->where($qry)->first();

        // checking
        if($requestType=='ASSIGN'){
            // input marks
            $scaleId = $request->input('scale_id');
            $assessmentMarks = $request->input('marks');

            // checking batch old weighted average list
            $oldBatchWeightedAverageList = $this->weightedAverage->where([
                'level_id'=>$academicLevel, 'batch_id'=>$batch, 'campus_id'=>$campus, 'institute_id'=>$institute
            ])->whereNotIn('grade_scale_id', [$scaleId])->get();
            // checking batch weighted average
            if($oldBatchWeightedAverageList->count()>0){
                // $oldBatchWeightedAverageList looping
                foreach ($oldBatchWeightedAverageList as $waList){
                    // delete weighted average one by one
                    $waList->delete();
                }
            }

            // assessment loop counter
            $assessmentLoopCounter = 0;
            // checking marks
            if(!empty($assessmentMarks) AND count($assessmentMarks)>0){
                // category mark array list
                $categoryMarkArrayList = array();

                // marks looping
                foreach ($assessmentMarks as $assCatId=>$marksDetails){
                    // marks id
                    $marksId = $marksDetails['id'];
                    // mark id checking
                    if($marksId>0){
                        // assessment weighted average marks profile
                        $weightedAverageProfile = $this->weightedAverage->find($marksId);
                    }else{
                        // assessment weighted average marks profile
                        $weightedAverageProfile = new $this->weightedAverage();
                    }
                    // input details
                    $weightedAverageProfile->marks = $marksDetails['mark'];
                    $weightedAverageProfile->ass_cat_id = $assCatId;
                    $weightedAverageProfile->grade_scale_id = $scaleId;
                    $weightedAverageProfile->level_id = $academicLevel;
                    $weightedAverageProfile->batch_id = $batch;
                    $weightedAverageProfile->campus_id = $campus;
                    $weightedAverageProfile->institute_id = $institute;
                    // save and checking
                    if($weightedAverageProfile->save()){
                        // category assessment loop counter
                        $assessmentLoopCounter +=1;
                        // category mark array list
                        $categoryMarkArrayList[$assCatId] = $weightedAverageProfile->id;
                    }
                }
                // checking
                if($assessmentLoopCounter==count($assessmentMarks)){
                    return ['status'=>'success','msg'=>'Assessment Marks Assigned', 'cat_mark_list'=>$categoryMarkArrayList];
                }else{
                    return ['status'=>'failed','msg'=>'Unable to Assign Assessment Marks'];
                }
            }else{
                return ['status'=>'failed','msg'=>'No Assessment Marks found !!!!'];
            }
        }else{
            // checking $classGradeScaleProfile
            if($classGradeScaleProfile){
                // weighted average list
                $weightedAverageList = $this->weightedAverage->where([
                    'institute_id'=>$institute,'campus_id'=>$campus, 'grade_scale_id'=>$classGradeScaleProfile->scale_id, 'level_id'=>$classGradeScaleProfile->level_id, 'batch_id'=>$classGradeScaleProfile->batch_id
                ])->get();

                // weighted average array list
                $weightedAverageArrayList = array();
                // $weightedAverageList checking
                if($weightedAverageList->count()>0){
                    // $weightedAverageList looping
                    foreach ($weightedAverageList as $weightedAverageProfile){
                        $weightedAverageArrayList[$weightedAverageProfile->ass_cat_id] = [
                            'id'=>$weightedAverageProfile->id,
                            'mark'=>$weightedAverageProfile->marks,
                        ];
                    }
                }
            }else{
                $weightedAverageList = null;
                $weightedAverageArrayList = array();
            }

            // return view with variable
            return view('academics::manage-assessments.modals.assessment-category-assign-list', compact('classGradeScaleProfile', 'academicInfo', 'weightedAverageList', 'weightedAverageArrayList'));
        }

    }

// semester assessment graph
    public function semesterAssessmentGraph(Request $request)
    {
        $stdId = $request->input('std_id');
        $semesterId = $request->input('semester_id');
        $subjectId = $request->input('subject_id');
        $batchId = $request->input('batch_id');
        $sectionId = $request->input('section_id');
        $scale = $this->getGradeScaleId($batchId, $sectionId);
        // student assessment
        $semesterAssessmentList = $this->assessments->where(['grade_id'=>$scale])->get();
        $semesterAssessmentDataList = array();

        // academic grades
        $stdGradeList = $this->studentGrade->where([
            'scale_id'=>$scale,
            'std_id'=>$stdId,
            'class_sub_id'=>$subjectId,
            'semester'=>$semesterId,
            'campus'=>$this->academicHelper->getCampus(),
            'institute'=>$this->academicHelper->getInstitute()
        ])->first();

        // stdProfile
        if($stdGradeList AND $semesterAssessmentList->count()>0){
            // stdProfile
            $stdProfile = $stdGradeList->student();
            // Grade Mark Profile
            $gradeMarkProfile = $stdGradeList->gradeMark();
            // mark Profile
            $markProfile = (array)json_decode($gradeMarkProfile->marks);
            // category list
            $categoryList = (array)$markProfile['cat_list'];
            // category looping
            foreach($categoryList as $catKey=>$catValue){
                // category id
                $categoryId = "cat_".$catValue;
                // single category item
                $singleCategory = (array)$markProfile[$categoryId];
                // category assessment list
                $assessmentList = (array)$singleCategory['ass_list'];
                // assessment list looping
                foreach ($assessmentList as $assKey=>$assValue){
                    // assessment id
                    $assessmentId = "ass_".$assValue;
                    // single Assessment item
                    $singleAssessment = (array)$singleCategory[$assessmentId];
                    // assessment data list
                    //
                    $semesterAssessmentDataList[$singleAssessment['ass_id']] = [
                        'ass_mark'=>$singleAssessment['ass_mark'],
                        'ass_points'=>$singleAssessment['ass_points'],
                    ];
                }
            }

            $label[] = " ";
            $data[] = array('x'=>0,'y'=>0);

            $loopSpace = 0;
            foreach ($semesterAssessmentList as $myAssessment){
                $myAssessmentId = $myAssessment->id;
                $assMark = $semesterAssessmentDataList[$myAssessmentId]['ass_mark'];
                $assPoints = $semesterAssessmentDataList[$myAssessmentId]['ass_points'];

                $label[] = $myAssessment->name;
                $data[] = array('x'=>(int)($loopSpace+10),'y'=>($assMark*100)/$assPoints);
                // space counter
                $loopSpace = ($loopSpace+10);
            }

            return array('status'=>'success','data'=>$data,'labels'=>$label);
        }else{
            return array('status'=>'failed','msg'=>'No Assessment(s)');
        }
    }

// get academic grading scale id using academic batch and section
    public function getGradeScaleId($batch, $section)
    {
        // find and checking
        if($gradeScale = $this->classGradeScale->where(['batch_id' => $batch])->orderBy('created_at', 'desc')->first(['scale_id'])){
            return $gradeScale->scale_id;
        }else{
            return 0;
        }
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

    public function getAcademicSemesters()
    {
        return $this->academicHelper->getAcademicSemester();
    }

    /**
     * @param $classSubjects
     * @return array
     */
    public function classSubjectGroupList($classSubjects)
    {
        // response array
        $subjectGroupList = array();
        // looping
        for ($i = 0; $i < count($classSubjects); $i++) {
            $singleSubject = (array)$classSubjects[$i];
            // checking
            if ($singleSubject['has_group'] == 1) {
                // input details
                $subjectGroupList[$singleSubject['group_id']]['s_g_id'] = $singleSubject['group_id'];
                $subjectGroupList[$singleSubject['group_id']]['s_g_name'] = $singleSubject['group_name'];
                $subjectGroupList[$singleSubject['group_id']]['s_list'][] = [
                    'cs_id' => $singleSubject['cs_id'],
                    'sub_id' => $singleSubject['id'],
                    'sub_name' => $singleSubject['name'],
                    'exam_mark' => $singleSubject['exam_mark'],
                    'pass_mark' => $singleSubject['pass_mark'],
                    'is_countable' => $singleSubject['is_countable'],
                    'sub_code' => $singleSubject['code'],
                    'type' => $singleSubject['type'],
                ];
            }
        }
        // return
        return $subjectGroupList;
    }

    /**
     * @param $gradeScale
     * @param $instituteId
     * @param $campusId
     * @return array
     */
    public function assessmentArrayListMaker($gradeScale, $instituteId, $campusId)
    {
        // response array list
        $assessmentArrayList = array();
        // grade scale assessment count checking
        if ($gradeScale AND $gradeScale!=null AND $gradeScale->assessmentsCount()>0) {
            // find assessment category
            $assessmentCategory = $this->gradeCategory->where(['institute' => $instituteId, 'campus' => $campusId])->get();
            // $assessmentCategory = $this->gradeCategory->where(['institute' => $instituteId, 'campus' => $campusId])->orderBy('position', 'ASC')->get();
            // checking assessment
            if ($assessmentCategory) {
                // assessment category looping
                foreach ($assessmentCategory as $category) {
                    // find assessment list
                    $allAssessmentList = $category->allAssessment($gradeScale->id);
                    // assessment list checking
                    if ($allAssessmentList->count() > 0) {
                        $list = array();
                        // assessment looping
                        foreach ($allAssessmentList as $assessment) {
                            $list[$assessment->id] = ['ass_name' => $assessment->name, 'ass_points' => $assessment->points];
                        }
                        // add ass_list to the cat_list
                        $assessmentArrayList[$category->id] = ['cat_name' => $category->name, 'ass_list' => $list];
                    }
                }
            }
        }
        // return
        return $assessmentArrayList;
    }

    /**
     * @param $level
     * @param $batch
     * @param $academicYear
     * @param $scaleId
     * @param $campusId
     * @param $instituteId
     * @return mixed|array
     */
    public function getClassAllSubjectAssessmentList($level=null, $batch, $academicYear, $scaleId, $campusId, $instituteId)
    {
        // $subjectAssessmentArrayList
        $subjectAssessmentArrayList = array();

        // subject assessment profile
        $subjectAssessmentProfile = $this->subjectAssessment->where(['batch' => $batch, 'campus' => $campusId, 'institute' => $instituteId]);
        // checking level
        if($level){
            $subjectAssessmentProfile = $subjectAssessmentProfile->where(['level' => $level]);
        }
        // subject assessment profile
        $subjectAssessmentProfile = $subjectAssessmentProfile->first(['id']);

        // checking subjectAssessment profile
        if($subjectAssessmentProfile){
            // subjectAssessment list
            $subjectAssessmentList = $subjectAssessmentProfile->subjectAssessmentDetails()->get();
            // checking subjectAssessment list
            if($subjectAssessmentList->count()>0){
                // sub_ass_id
                $subjectAssessmentArrayList['sub_ass_id'] = $subjectAssessmentProfile->id;
                // $subjectAssessmentList looping
                foreach ($subjectAssessmentList as $subjectAssessment){
                    // marks list
                    $subjectAssessmentArrayList['subject_list'][$subjectAssessment->sub_id] = json_decode($subjectAssessment->assessment_marks, true);
                    // subject_list
                    $subjectAssessmentArrayList['subject_list'][$subjectAssessment->sub_id]['sub_ass_detail_id'] = $subjectAssessment->id;
                }
            }
        }
        // return
        return $subjectAssessmentArrayList;
    }

// getClassAllSubjectAssessmentArrayList
    public function getClassSubjectAssessmentArrayList($level, $batch, $academicYear, $scaleId, $campusId, $instituteId)
    {
        // $subjectAssessmentArrayList
        $subjectAssessmentArrayList = array();

        // subject assessment profile
        $subjectAssessmentProfile = $this->subjectAssessment->where([
            'level' => $level, 'batch' => $batch, 'grade_scale_id' => $scaleId, 'campus' => $campusId, 'institute' => $instituteId
        ])->first(['id']);

        // checking subjectAssessment profile
        if($subjectAssessmentProfile){
            // subjectAssessment list
            $subjectAssessmentList = $subjectAssessmentProfile->subjectAssessmentDetails()->get();
            // checking subjectAssessment list
            if($subjectAssessmentList->count()>0){
                // $subjectAssessmentList looping
                foreach ($subjectAssessmentList as $subjectAssessment){
                    // subject assessment looping
                    foreach (json_decode($subjectAssessment->assessment_marks) as $assessmentId=>$mark){
                        // marks list
                        $subjectAssessmentArrayList[$subjectAssessment->sub_id][$assessmentId] = (array)$mark;
                    }
                }
            }
        }

        // return
        return $subjectAssessmentArrayList;
    }


////////////////////////// Assessment Passing Mark Setting ////////////////////////////////

// get Assessment Passing Mark Setting/Distribution
    public function getAssessmentPassMarkSetting(Request $request)
    {
        // request details
        $batch = $request->input('batch');
        $level = $request->input('academic_level');
        $campus = $this->academicHelper->getCampus();
        $institute = $this->academicHelper->getInstitute();
        // find assessment passing mark
        $assPassMarkProfile = $this->assessmentPassMark->where(['batch'=>$batch, 'campus'=>$campus, 'institute'=>$institute])->first();
        // get grade scale id
        $scale = $this->getGradeScaleId($batch, null);
        // grading scale
        $gradeScale = $this->grade->where(['id'=>$scale, 'campus'=>$campus, 'institute'=>$institute])->first(['id', 'name']);
        // weightedAverageArrayList
        $weightedAverageArrayList = $this->getGradeScaleAssessmentCategoryWeightedAverageList($level, $batch, $campus, $institute, $scale);
        // return view with variable
        return view('academics::manage-assessments.modals.setting-passing-mark', compact('gradeScale', 'weightedAverageArrayList', 'assPassMarkProfile'));
    }


// get Assessment Passing Mark Setting/Distribution
    public function manageAssessmentPassMarkSetting(Request $request)
    {
        // request details
        $batch = $request->input('batch');
        $marks = $request->input('marks');
        $markId = $request->input('ass_pass_mark_id');
        // institute details
        $campus = $this->academicHelper->getCampus();
        $institute = $this->academicHelper->getInstitute();
        // checking mark id
        if($markId AND $markId>0){
            // find pass mark profile
            $assPassMarkProfile = $this->assessmentPassMark->find($markId);
        }else{
            // new pass mark profile
            $assPassMarkProfile = new $this->assessmentPassMark();
        }
        // store pass mark profile details
        // store pass mark profile details
        $assPassMarkProfile->marks = json_encode($marks, true);
        $assPassMarkProfile->batch = $batch;
        $assPassMarkProfile->campus = $campus;
        $assPassMarkProfile->institute = $institute;
        // save and checking
        if($assPassMarkProfile->save()){
            // success return
            return ['status'=>true, 'msg'=>'Assessment Passing Marks Submitted !!!'];
        }else{
            // success return
            return ['status'=>false, 'msg'=>'Unable to Submit Assessment Passing Marks !!!'];
        }
    }



// download a class section all student report card
    public function getClassSectionSemesterResultSheetForCollege(Request $request)
    {
        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'academic_level' => 'required', 'batch' => 'required', 'section' => 'required','semester' => 'required',
        ]);

        // storing requesting input data
        if ($validator->passes()) {

            // academic batch
            $level = $request->input('academic_level');
            $batch = $request->input('batch');
            $section = $request->input('section');
            $semester = $request->input('semester');
            $category = $request->input('category', 0);
            // find batch and section
            $sectionProfile = $this->academicHelper->findSection($section);
            $batchProfile = $this->academicHelper->findBatch($batch);
            $semesterProfile = $this->academicHelper->getSemester($semester);
            // institute and campus
            $institute = $this->academicHelper->getInstitute();
            $campus = $this->academicHelper->getCampus();
            $instituteInfo = $this->academicHelper->getInstituteProfile();
            // academic year profile
            $academicYear = $this->academicHelper->getAcademicYearProfile();
            // scale id
            $scaleId = $this->getGradeScaleId($batch, $section);
            // grade scale scale details
            $gradeScaleDetails = $this->gradeDetails->where('grade_id', $scaleId)->orderBy('sorting_order', 'ASC')->get();

            // grading scale
            $gradeScale = $this->grade->orderBy('name', 'ASC')->where('id',$scaleId)->first(['id', 'name', 'grade_scale_id']);

            // find grade scale category list
            $allCategoryList = $gradeScale->assessmentsCount()?$gradeScale->assessmentCategory():[];
            // find category details array list
            $catDetailArrayList = $this->getCategoryDetails($allCategoryList, $gradeScale);
            // subject assessment array list
            // $subjectAssessmentArrayList =  $this->getClassAllSubjectAssessmentList(null, $batch, null, $scaleId, $campus, $institute);

            // student list
            $studentList = $this->studentProfileView->where([
                'batch'=>$batch, 'section'=>$section, 'academic_level'=>$level, 'campus'=>$campus, 'institute'=>$institute
            ])->orderByRaw('LENGTH(gr_no) asc')->orderBy('gr_no', 'asc')->get(['std_id', 'gr_no', 'first_name', 'middle_name', 'last_name']);

            // find exam status
            // $examStatus = $this->examStatus->where([
            //     'semester'=>$semester, 'section'=>$section, 'batch'=>$batch, 'level'=>$level,
            //     'academic_year'=>$academicYear->id, 'campus'=>$campus, 'institute'=>$institute,
            // ])->first();

            // Class subject list
            $classSubArrayList = $this->academicHelper->findClassSectionGroupSubjectList($section, $batch, $campus, $institute);
            // additional subject list
            $additionalSubArrayList = $this->academicHelper->findClassSectionAdditionalSubjectList($section, $batch, $campus, $institute);

            // checking division name
            if ($batchProfile->get_division()) {
                $batchName = $batchProfile->batch_name . " --- " . $batchProfile->get_division()->name;
            } else {
                $batchName = $batchProfile->batch_name;
            }
            // subject details
            $classInfo = [
                'batch'=>$batch, 'batch_name'=>$batchName, 'section'=>$section, 'section_name'=>$sectionProfile->section_name,
                'semester'=>$semester, 'semester_name'=>$semesterProfile->name, 'campus'=>$campus, 'institute'=>$institute
            ];

            $tabulationSheet = $this->getTabulationMarkSheetForCollege($section, $batch, $category, $semester, $additionalSubArrayList, $gradeScaleDetails, $campus, $institute);
            return view('academics::manage-assessments.reports.report-class-section-semester-tabulation-sheet-college',compact('studentList', 'instituteInfo', 'classSubArrayList', 'additionalSubArrayList', 'tabulationSheet', 'catDetailArrayList', 'category', 'classInfo'));
            // compact all variables with view
            view()->share(compact('studentList', 'instituteInfo', 'classSubArrayList', 'additionalSubArrayList', 'tabulationSheet', 'catDetailArrayList', 'category', 'classInfo'));
            // generate pdf
            $pdf = App::make('dompdf.wrapper');
//            return view('academics::manage-assessments.reports.report-class-section-semester-tabulation-sheet-college');
            $pdf->loadView('academics::manage-assessments.reports.report-class-section-semester-tabulation-sheet-college')->setPaper('legal', 'landscape');
            return $pdf->stream($batchProfile->batch_name.'-'.$sectionProfile->section_name.'-'.$semesterProfile->name.'-final-result-sheet.pdf');
        }else{
            Session::flash('warning', "Invalid information !!!");
            return redirect()->back();
        }
    }


    private $gradeCounter = ['A+'=>0, 'A'=>0,  'A-'=>0, 'B'=>0, 'C'=>0, 'D'=>0, 'F'=>0, 'NA'=>0, 'ABSENT'=>0, 'TOTAL'=>0];
    private function countGrade($grade, $assStatus = true){
        // checking assessment
        if($assStatus){
            // checking grade
            if($grade === "A+"){
                $this->gradeCounter['A+'] +=1;
            }elseif($grade === "A"){
                $this->gradeCounter['A'] +=1;
            }elseif($grade === "A-"){
                $this->gradeCounter['A-'] +=1;
            }elseif($grade === "B"){
                $this->gradeCounter['B'] +=1;
            }elseif($grade === "C"){
                $this->gradeCounter['C'] +=1;
            }elseif($grade === "D"){
                $this->gradeCounter['D'] +=1;
            }elseif($grade === "F"){
                $this->gradeCounter['F'] +=1;
            }else{
                $this->gradeCounter['NA'] +=1;
            }
        }else{
            $this->gradeCounter['ABSENT'] +=1;
        }

        // total
        $this->gradeCounter['TOTAL'] +=1;
    }

    private $subFailCounter = ['pass_list'=>[],'fail_list'=>[]];

    private function stdSubFailCounter($stdId, $grade, $assStatus = true, $gredePoint){
        // checking grade
        if(($grade == "F") || ($assStatus==false)){
            if(array_key_exists($stdId, $this->subFailCounter['fail_list'])){
                $this->subFailCounter['fail_list'][$stdId] +=1;
            }else{
                $this->subFailCounter['fail_list'][$stdId] = 1;
            }
            // remove student from pass list
            unset($this->subFailCounter['pass_list'][$stdId]);
        }else if(array_key_exists($stdId, $this->subFailCounter['fail_list'])==false){

            if(array_key_exists($stdId, $this->subFailCounter['pass_list'])){
                $this->subFailCounter['pass_list'][$stdId] =$gredePoint;
            }else{
                $this->subFailCounter['pass_list'][$stdId] = $gredePoint;
            }
        }
    }


// college tabulation sheet
    public function getTabulationMarkSheetForCollege($section=null, $batch, $category,  $semester, $additionalSubArrayList, $gradeScaleDetails, $campus, $institute, $subjectId = null, $subjectGroupId = null,$returnType = null, $stdId = null){

        // find institute class section semester mark sheet
        $classSectionMarkSheet = DB::table('student_grades as grade')
            ->join('student_marks as mark', 'mark.id', '=', 'grade.mark_id')
            ->join('class_subjects as cSub', 'cSub.id', '=', 'grade.class_sub_id')
            ->select('grade.std_id', 'grade.class_sub_id as cs_id', 'cSub.subject_id as s_id','cSub.subject_group as sg_id','cSub.subject_type as cs_type', 'grade.semester', 'mark.marks')
            ->where(['grade.batch'=>$batch, 'grade.semester'=>$semester, 'grade.campus'=>$campus, 'grade.institute'=>$institute]);
        if($section != null){
            $classSectionMarkSheet->where(['grade.section'=>$section]);
        }
        if($subjectId != null){
            $classSectionMarkSheet->where(['grade.class_sub_id'=>$subjectId]);
        }
        if($subjectGroupId != null){
            $classSectionMarkSheet->where(['cSub.subject_group'=>$subjectGroupId]);
        }

        if($stdId != null){
            $classSectionMarkSheet->where(['grade.std_id'=>$stdId]);
        }

        // scale id
        $scaleId = $this->getGradeScaleId($batch, $section);
        // subject assessment array list
        $subjectAssessmentArrayList =  $this->getClassAllSubjectAssessmentList(null, $batch, null, $scaleId, $campus, $institute);

        // student result array list
        $stdResultArrayList = array();

        $stdGradeArrayList = [];
        $boardResultArrayList = [];
        $boardResultArrayList['failList'] = [];


        // class section mark sheet looping
        foreach ($classSectionMarkSheet->get() as $result){
            // student id
            $stdId = $result->std_id;
            // class subject id
            $subId = $result->s_id;
            // class subject id
            $csId = $result->cs_id;
            // class subject type
            $csType = $result->cs_type;
            // class subject group id
            $csgId = $result->sg_id;
            // semester id
            $semester = $result->semester;
            // marks list
            $markList = json_decode($result->marks);
            // find category list from mark list
            $catList = $markList->cat_list;
            // find student additional subject list
            $myAdditionalSubList = array_key_exists($stdId, $additionalSubArrayList)?$additionalSubArrayList[$stdId]:[];
            // subject total exam marks
            $subExamMark = 0;
            // subject total Obtained marks
            $subObtainedMark = 0;
            // class subject assessment setting
            $classSubAssSetting = ((array_key_exists($subId, $subjectAssessmentArrayList['subject_list']))?($subjectAssessmentArrayList['subject_list'][$subId]):[]);
            // category assessment setting list
            $categoryList = (array_key_exists('cat_list', $classSubAssSetting)?$classSubAssSetting['cat_list']:[]);
            // category list looping
            foreach ($catList as $catIndex=>$catId) {

                // checking category
                if ($category != $catId) continue;

                // category setting
                $catAssSetting = (array_key_exists($catId, $categoryList)?$categoryList[$catId]:[]);
                //cat exam mark
                $myCatExamMark = (array_key_exists('exam_mark', $catAssSetting)?$catAssSetting['exam_mark']:0);
                //cat pass mark
                $myCatPassMark = (array_key_exists('pass_mark', $catAssSetting)?$catAssSetting['pass_mark']:0);
                // category ass list
                $myCatAssList = (array_key_exists('ass_list', $catAssSetting)?$catAssSetting['ass_list']:0);

                // my category id
                $myCatId = 'cat_' . $catId;
                // category details
                $catDetails = $markList->$myCatId;
                // find assessment list from category details
                $assList = $catDetails->ass_list;

                // category total marks
                $catExamMark = 0;
                // category Obtained marks
                $catObtainedMark = 0;
                // assessment marks array list
                $assessmentMarksArrayList = array();
                $failedAssArrayList = array();
                // assessment status
                $assStatus = true;

                // assessment list looping
                foreach ($assList as $assIndex => $assId) {
                    // Assessment setting
                    $myAssSetting = (array_key_exists($assId, $myCatAssList)?$myCatAssList[$assId]:[]);
                    //Ass exam mark
                    $myExamMark = (array_key_exists('exam_mark', $myAssSetting)?$myAssSetting['exam_mark']:0);
                    //ass pass mark
                    $myPassMark = (array_key_exists('pass_mark', $myAssSetting)?$myAssSetting['pass_mark']:0);

                    // my assessment id
                    $myAssId = 'ass_' . $assId;
                    // assessment details
                    $assDetails = $catDetails->$myAssId;
                    // assessment point
                    $points = (int)$assDetails->ass_points;
                    // assessment marks
                    $marks = $assDetails->ass_mark;
                    // subject Obtained marks adding
                    $subObtainedMark += (int)$marks;
                    // category Obtained marks adding
                    $catObtainedMark += (int)$marks;
                    // checking marks
                    if ($marks != null) {
                        // assessment obtained mark input
                        $assessmentMarksArrayList[$assId] = $marks;
                    } else {
                        // assessment obtained mark input
                        $assessmentMarksArrayList[$assId] = 'Abs';
                        // assessment status
                        $assStatus = false;
                    }
                    // subject exam marks adding
                    $subExamMark += $points;
                    // category total marks adding
                    $catExamMark += $points;
                    // checking assessment status
                    if(($assStatus==true) && ($marks<$myPassMark)){
                        $failedAssArrayList[]=$assId;
                        // assessment status
                        $assStatus = false;
                    }
                }


                // calculate obtained marks percentage
                $catObtainedMarkPercentage = ($catExamMark > 0 ? (round((($catObtainedMark * 100) / $catExamMark), 2, PHP_ROUND_HALF_UP)) : 0);
                // find subject letter grade details
                $catLetterGradeDetails = $this->subjectGradeCalculation((int)$catObtainedMarkPercentage, $gradeScaleDetails);
                // get subject letter grade
                $catLetterGradeStatus = ($catLetterGradeDetails && $assStatus) ? ($catLetterGradeDetails['grade']) : 'F';
                // grade calculation
                $this->countGrade($catLetterGradeStatus, $assStatus);
                //$this->stdSubFailCounter($stdId, $catLetterGradeStatus, $assStatus);

                // find category grade point total
                $catLetterGradeMaxPoint = ($catLetterGradeDetails && $assStatus) ? ($catLetterGradeDetails['max_point']) : 0;
                // find category grade point obtained
                $catLetterGradeObtainedPoint = ($catLetterGradeDetails && $assStatus) ? ($catLetterGradeDetails['point']) : 0;

                // category obtained mark array push
                $stdResultArrayList[$stdId]['sub_list'][$csgId] = [
                    'ass_list' => $assessmentMarksArrayList,
                    'fail_ass_list' => $failedAssArrayList,
                    'exam_mark' => $catExamMark,
                    'obtained_mark' => $catObtainedMark,
                    'percentage' => $catObtainedMarkPercentage,
                    'lg' => $catLetterGradeStatus,
                    'gp' => $catLetterGradeObtainedPoint,
                ];

                //  count student total marks
                if(array_key_exists('result', $stdResultArrayList[$stdId])){
                    // find total failed count
                    //$totalFailed = $stdResultArrayList[$stdId]['result']['failed'];
                    // count student total exam marks
                    $totalExamMark = ($stdResultArrayList[$stdId]['result']['exam'] += $catExamMark);
                    // count student total Obtained marks
                    $totalObtainedMark = ($stdResultArrayList[$stdId]['result']['obtained'] += $catObtainedMark);
                    // calculate obtained marks percentage
                    $totalObtainedMarkPercentage = ($totalExamMark>0?(round((($totalObtainedMark*100)/$totalExamMark), 2, PHP_ROUND_HALF_UP)):0);
                    // count student total Obtained marks
                    $stdResultArrayList[$stdId]['result']['percentage'] = $totalObtainedMarkPercentage;
                    // find total letter grade details
                    $totalLetterGradeDetails = $this->subjectGradeCalculation((int)$totalObtainedMarkPercentage, $gradeScaleDetails);

                    // final result calculation
                    if($csType==1 || in_array($csgId, $myAdditionalSubList)){
                        // checking additional subject
                        if($csgId!=end($myAdditionalSubList)){
                            $stdResultArrayList[$stdId]['result']['failed']+=($catLetterGradeStatus=='F'?1:0);
                            $stdResultArrayList[$stdId]['result']['total_gp'] += $catLetterGradeMaxPoint;
                            $stdResultArrayList[$stdId]['result']['obtained_gp'] += $catLetterGradeObtainedPoint;
                            $stdResultArrayList[$stdId]['result']['obtained_gp_main'] += $catLetterGradeObtainedPoint;
                        }else{
                            // optional subject grade point
                            $optSubGradePoint = $catLetterGradeObtainedPoint-2;
                            // set optional subject grade point
                            $stdResultArrayList[$stdId]['result']['obtained_gp'] += ($optSubGradePoint>0?$optSubGradePoint:0);
                        }
                    }
                    // count total failed subject
                    $totalFailed = $stdResultArrayList[$stdId]['result']['failed'];
                    // final result
                    $stdResultArrayList[$stdId]['result']['gpa'] = $stdTotalGpa =$totalFailed==0?(round($stdResultArrayList[$stdId]['result']['obtained_gp']/6, 2, PHP_ROUND_HALF_UP)):0;
                    $stdResultArrayList[$stdId]['result']['lg'] = $this->gpaReverseCalculation($stdTotalGpa);
                    $stdResultArrayList[$stdId]['result']['gpa_with_out_optional'] = $totalFailed==0?(round($stdResultArrayList[$stdId]['result']['obtained_gp_main']/6, 2, PHP_ROUND_HALF_UP)):0;
                }else{
                    // subject status
                    $subStatus = $catLetterGradeStatus=='F'?1:0;
                    // count student total exam marks
                    $totalExamMark = ($stdResultArrayList[$stdId]['result']['exam'] = $catExamMark);
                    // count student total Obtained marks
                    $totalObtainedMark = ($stdResultArrayList[$stdId]['result']['obtained'] = $catObtainedMark);
                    // calculate obtained marks percentage
                    $totalObtainedMarkPercentage = ($totalExamMark>0?(round((($totalObtainedMark*100)/$totalExamMark), 2, PHP_ROUND_HALF_UP)):0);
                    // count student total Obtained marks
                    $stdResultArrayList[$stdId]['result']['percentage'] = $totalObtainedMarkPercentage;
                    // find total letter grade details
                    $totalLetterGradeDetails = $this->subjectGradeCalculation((int)$totalObtainedMarkPercentage, $gradeScaleDetails);
                    // count student total Obtained marks
                    $stdResultArrayList[$stdId]['result']['failed'] = $subStatus;
                    // $stdResultArrayList[$stdId]['result']['d_lg'] = ($totalLetterGradeDetails AND $subStatus==0)?$totalLetterGradeDetails['grade']:'F';
                    // $stdResultArrayList[$stdId]['result']['d_gp'] = ($totalLetterGradeDetails AND $subStatus==0)?$totalLetterGradeDetails['point']:0;
                    // final result calculation
                    $stdResultArrayList[$stdId]['result']['total_gp'] = $catLetterGradeMaxPoint;
                    $stdResultArrayList[$stdId]['result']['obtained_gp'] = $catLetterGradeObtainedPoint;
                    $stdResultArrayList[$stdId]['result']['obtained_gp_main'] = $catLetterGradeObtainedPoint;
                    // final result
                    $stdResultArrayList[$stdId]['result']['lg'] = 'F';
                    $stdResultArrayList[$stdId]['result']['gpa'] = $stdTotalGpa = 0;
                    $stdResultArrayList[$stdId]['result']['gpa_with_out_optional'] = 0;
                }


                $stdGradeArrayList[$stdId] = $this->gpaReverseCalculation($stdTotalGpa);

                $this->stdSubFailCounter($stdId, $catLetterGradeStatus, $assStatus, $stdTotalGpa);

//                if($stdTotalGpa >= 1){
//                    $boardResultArrayList['passList'][$stdId] = $stdTotalGpa;
//                }
//                 else if((int)$stdTotalGpa === 0){
//                    if(array_key_exists($stdId, $boardResultArrayList['failList'])){
//                        $boardResultArrayList['failList'][$stdId] +=1;
//                    }else{
//                        $boardResultArrayList['failList'][$stdId] = 1;
//                    }
//                }


//                $this->countGrade($stdTotalGpa);
            }
        }



        $myGradeCounter = $this->gradeCounter;
        $this->gradeCounter = ['A+'=>0, 'A'=>0,  'A-'=>0, 'B'=>0, 'C'=>0, 'D'=>0, 'F'=>0, 'NA'=>0, 'ABSENT'=>0, 'TOTAL'=>0];

        if ($returnType == "SUB_GROUP_RESULT") {
            return $myGradeCounter;
        }
        else if( $returnType == "SUB_SINGLE_RESULT"){
            $stdResultArrayList['grade_count'] = $myGradeCounter;
            return $stdResultArrayList;
        }
        else if( $returnType == "BOARD_RESULT"){
            $boardResultArrayList = $this->subFailCounter;
            $this->subFailCounter = ['pass_list'=>[],'fail_list'=>[]];
            return $boardResultArrayList;
        }
        else if( $returnType == "SUMMERY_RESULT"){
            $gradeArrayCount = array_count_values($stdGradeArrayList);
            return $gradeArrayCount;
        }
        else{//tabulation

            $stdResultArrayList['grade_count'] = array_count_values($stdGradeArrayList);;
            return $stdResultArrayList;
        }

    }


    private function gpaReverseCalculation($gpa){

        if(5 ==$gpa){
            $lg = 'A+';
        }
        else if(4.99 >= $gpa && 4.00 <= $gpa){
            $lg = 'A';
        }
        else if(3.99 >= $gpa && 3.50 <= $gpa){
            $lg = 'A-';
        }
        else if(3.49 >= $gpa && 3.00 <= $gpa){
            $lg = 'B';
        }
        else if(2.99 >= $gpa && 2.00 <= $gpa){
            $lg = 'C';
        }
        else if(1.99 >= $gpa && 1.00 <= $gpa){
            $lg = 'D';
        }
        else{
            $lg = 'F';
        }


        return $lg;
    }

// download a class section all student report card
    public function getClassSectionSemesterResultSheet(Request $request)
    {

        //return $request->all();

        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'academic_level' => 'required', 'batch' => 'required', 'section' => 'required','semester' => 'required',
        ]);

        // storing requesting input data
        if ($validator->passes()) {

            // report format
            $reportFormat = 1;
            // academic batch
            $level = $request->input('academic_level');
            $batch = $request->input('batch');
            $section = $request->input('section');
            $semester = $request->input('semester');
            $category = $request->input('category', null);
            $assessment = $request->input('assessment', null);
            $resultSheetType = $request->input('result_sheet_type', 'TOTAL');
            // find batch and section
            $sectionProfile = $this->academicHelper->findSection($section);
            $batchProfile = $this->academicHelper->findBatch($batch);
            $semesterProfile = $this->academicHelper->getSemester($semester);
            $assessmentProfile = $this->academicHelper->getAssessment($assessment);
            // institute and campus
            $institute = $this->academicHelper->getInstitute();
            $campus = $this->academicHelper->getCampus();
            $instituteInfo = $this->academicHelper->getInstituteProfile();
            // academic year profile
            $academicYear = $this->academicHelper->getAcademicYearProfile();
            // scale id
            $scaleId = $this->getGradeScaleId($batch, $section);
            // student list
            $studentList = $this->studentProfileView->where([
                'batch'=>$batch, 'section'=>$section, 'academic_level'=>$level, 'campus'=>$campus, 'institute'=>$institute
            ])->orderByRaw('LENGTH(gr_no) asc')->orderBy('gr_no', 'asc')->get();
            // find class sub
            $classSubjects = $this->studentAttendanceReportController->getClsssSectionSubjectList($batch, $section);
            // find exam status
            $examStatus = $this->examStatus->where([
                'semester'=>$semester, 'section'=>$section, 'batch'=>$batch, 'level'=>$level,
                'academic_year'=>$academicYear->id, 'campus'=>$campus, 'institute'=>$institute,
            ])->first();
            // grading scale
            $gradeScale = $this->grade->orderBy('name', 'ASC')->where('id',$scaleId)->first(['id', 'name', 'grade_scale_id']);
            // student department
            $additionalSubjectList = (array) $this->additionalSubject->getStudentAdditionalSubjectList(null, $section, $batch, $academicYear->id,  $campus, $institute);

            // checking division name
            if ($batchProfile->get_division()) {
                $batchName = $batchProfile->batch_name . " - " . $batchProfile->get_division()->name;
            } else {
                $batchName = $batchProfile->batch_name;
            }
            // subject details
            $classInfo = [
                'batch'=>$batch, 'batch_name'=>$batchName,
                'section'=>$section, 'section_name'=>$sectionProfile->section_name,
                'semester'=>$semester, 'semester_name'=>$semesterProfile->name,
                'campus'=>$campus, 'institute'=>$institute
            ];

            // checking assessment
            if($assessment AND $category){
                $subjectAssessmentArrayList = [];
                // add assessment name into the classInfo array list
                $classInfo['assessment_name'] = $assessmentProfile->name;
                // find semester single assessment result sheet
                $semesterResultSheet = $this->getClassSectionSingleAssessmentResultSheet($assessment, $category, $batch, $section, $semester, $scaleId, $campus, $institute);
            }else{
                // weightedAverageArrayList
                $weightedAverageArrayList = $this->getGradeScaleAssessmentCategoryWeightedAverageList($level, $batch, $campus, $institute, $scaleId);
                $subjectAssessmentArrayList =  $this->getClassAllSubjectAssessmentList($level, $batch, null, $scaleId, $campus, $institute);
                // rearrange semester result sheet
                $semesterResultSheet = $this->rearrangeStudentGradeMark($scaleId,  $studentList, $semester, $reportFormat, $subjectAssessmentArrayList);
                // subject highestMark list
                $subjectHighestMarksList = $this->getSubjectHighestMarks($institute, $campus, $level, $batch, $section, $scaleId, $semester, $academicYear->id, $subjectAssessmentArrayList);
            }

            // compact all variables with view
            view()->share(compact('studentList', 'classSubjects', 'semesterResultSheet', 'subjectHighestMarksList', 'instituteInfo', 'classInfo', 'additionalSubjectList', 'examStatus', 'subAssessmentResultSheet', 'gradeScale', 'subjectAssessmentArrayList', 'category'));
            // generate pdf
            $pdf = App::make('dompdf.wrapper');
            // checking assessment
            if($assessment AND $category){
                // load assessment report card view
                $pdf->loadView('academics::manage-assessments.reports.report-class-section-single-assessment-result')->setPaper('a4', 'landscape');
                return $pdf->stream($batchProfile->batch_name.'-'.$sectionProfile->section_name.'-'.$semesterProfile->name.'-----'.$assessmentProfile->name.'-----result-sheet.pdf');
            }else{
                // checking result sheet type
                if($resultSheetType=='TABULATION'){
                    // checking institute and campus
                    if($institute==12 AND $campus==13){
                        // load semester report card view
                        $pdf->loadView('academics::manage-assessments.reports.report-class-section-semester-tabulation-sheet-stjscb')->setPaper('legal', 'landscape');
                        return $pdf->stream($batchProfile->batch_name.'-'.$sectionProfile->section_name.'-'.$semesterProfile->name.'-final-result-sheet.pdf');
                    }else{
                        // load semester report card view
                        $pdf->loadView('academics::manage-assessments.reports.report-class-section-semester-tabulation-sheet')->setPaper('legal', 'landscape');
                        return $pdf->stream($batchProfile->batch_name.'-'.$sectionProfile->section_name.'-'.$semesterProfile->name.'-final-result-sheet.pdf');
                    }
                }else{
                    // load semester report card view
                    $pdf->loadView('academics::manage-assessments.reports.report-class-section-semester-result')->setPaper('a4', 'landscape');
                    return $pdf->stream($batchProfile->batch_name.'-'.$sectionProfile->section_name.'-'.$semesterProfile->name.'-final-result-sheet.pdf');
                }
            }
        }else{
            Session::flash('warning', "Invalid information !!!");
            return redirect()->back();
        }
    }

//
    public function getClassSectionSingleAssessmentResultSheet($assessment, $category, $batch, $section, $semester, $scale, $campus, $institute)
    {
        // response array
        $responseData = array();
        //  std grade list
        $stdGradeList = $this->studentGrade->where([
            'batch'=>$batch, 'section'=>$section, 'scale_id'=>$scale, 'semester'=>$semester, 'campus'=>$campus, 'institute'=>$institute
        ])->get();
        // checking
        if($stdGradeList->count()>0){
            // stdMark looping
            foreach ($stdGradeList as $index=>$singleGrade){
                // student id
                $stdId = $singleGrade->std_id;
                $classSubId = $singleGrade->class_sub_id;
                // subject marks
                $gradeMark = (array)json_decode($singleGrade->gradeMark()->marks, true);
                // custom catId
                $catId = (string)'cat_'.$category;
                // catId checking
                if(array_key_exists($catId, $gradeMark)){
                    // find category marks list from mark
                    $categoryMarksList = $gradeMark[$catId];
                    // custom assId
                    $assId = (string)'ass_'.$assessment;
                    // assId checking
                    if(array_key_exists($assId, $categoryMarksList)){
                        // find assessment marks list from categoryMarksList
                        $subAssessment = $categoryMarksList[$assId];
                        // input assessment mark and point details
                        $responseData[$stdId][$classSubId] = ['ass_mark'=>$subAssessment['ass_mark'],'ass_points'=>$subAssessment['ass_points']];
                    }
                }
            }
        }
        // return response array
        return $responseData;
    }




////////////////////////// Assessment Passing Mark Setting ////////////////////////////////


// manage report card setting
    public function manageReportCardSetting(Request $request)
    {
        // table request details
        $is_table_color = $request->input('is_table_color', 0);
        $tbl_header_tr_bg_color = $request->input('tbl_header_tr_bg_color');
        $tbl_header_tr_font_color = $request->input('tbl_header_tr_font_color');
        $tbl_even_tr_bg_color = $request->input('tbl_even_tr_bg_color');
        $tbl_even_tr_font_color = $request->input('tbl_even_tr_font_color');
        $tbl_odd_tr_bg_color = $request->input('tbl_odd_tr_bg_color');
        $tbl_odd_tr_font_color = $request->input('tbl_odd_tr_font_color');
        $tbl_opacity = $request->input('tbl_opacity', 0.5);
        // image_status request details
        $is_image = $request->input('is_image', 0);
        // border request details
        $is_border_color = $request->input('is_border_color', 0);
        $border_width = $request->input('border_width');
        $border_type = $request->input('border_type');
        $border_color = $request->input('border_color');
        // label request details
        $is_label_color = $request->input('is_label_color', 0);
        $label_bg_color = $request->input('label_bg_color');
        $label_font_color = $request->input('label_font_color');
        // watermark request details
        $is_watermark = $request->input('is_watermark', 0);
        $wm_opacity = $request->input('wm_opacity');
        $wm_url = $request->input('wm_url');

        // report card signature setting
        $parent_sign = $request->input('parent_sign', 0);
        $teacher_sign = $request->input('teacher_sign', 0);
        $auth_name = $request->input('auth_name', null);
        $authSign = $request->file('auth_sign', null);

        // old image path
        $oldImagePath = null;

        // report card setting id
        $reportSettingId = $request->input('report_setting_id', 0);
        // institute and campus details
        $campus = $this->academicHelper->getCampus();
        $institute = $this->academicHelper->getInstitute();


        // checking report card setting id
        if($reportSettingId>0){
            // find setting profile using id
            $reportSetting = $this->reportCardSetting->find($reportSettingId);
        }else{
            // new setting profile
            $reportSetting = new $this->reportCardSetting();
        }

        $reportSetting->is_table_color = $is_table_color;
        // checking $is_table_color
        if($is_table_color==1){
            $reportSetting->tbl_header_tr_bg_color = $tbl_header_tr_bg_color;
            $reportSetting->tbl_header_tr_font_color = $tbl_header_tr_font_color;
            $reportSetting->tbl_even_tr_bg_color = $tbl_even_tr_bg_color;
            $reportSetting->tbl_even_tr_font_color = $tbl_even_tr_font_color;
            $reportSetting->tbl_odd_tr_bg_color = $tbl_odd_tr_bg_color;
            $reportSetting->tbl_odd_tr_font_color = $tbl_odd_tr_font_color;
            $reportSetting->tbl_opacity = $tbl_opacity;
        }

        $reportSetting->is_border_color = $is_border_color;
        // checking $is_table_color
        if($is_border_color==1){
            $reportSetting->border_color = $border_color;
            $reportSetting->border_type = $border_type;
            $reportSetting->border_width = $border_width;
        }

        $reportSetting->is_label_color = $is_label_color;
        // checking $is_table_color
        if($is_label_color==1){
            $reportSetting->label_bg_color = $label_bg_color;
            $reportSetting->label_font_color = $label_font_color;
        }

        $reportSetting->is_watermark =$is_watermark;
        // checking $is_table_color
        if($is_watermark==1){
            $reportSetting->wm_url = $wm_url;
            $reportSetting->wm_opacity = $wm_opacity;
        }

        $reportSetting->is_image =$is_image;
        // checking $is_table_color
        if($is_image==1){
            $reportSetting->is_image = $is_image;
        }

        // report card signature setting
        $reportSetting->parent_sign = $parent_sign;
        $reportSetting->teacher_sign = $teacher_sign;
        $reportSetting->auth_name = $auth_name;
        // checking authSing
        if($authSign){
            // find old image
            $fileExtension = $authSign->getClientOriginalExtension();
            //$contentName     = $authSign->getClientOriginalName();
            $contentName     = "ems" . date("Ymdhis") . mt_rand(100000, 999999) . "." . $fileExtension;
            $contentFileName = $contentName;
//            $destinationPath = 'assets/principal-sign/';
            $destinationPath = 'assets/users/images/';
            // checking old image
            if($reportSetting->auth_sign){
                // old image path
                $oldImagePath = $destinationPath.$reportSetting->auth_sign;
            }

            // checking file uploading
            if($authSign->move($destinationPath, $contentFileName)){
                $reportSetting->auth_sign = $contentName;
            }
        }

        // input institute and campus
        $reportSetting->campus = $campus;
        $reportSetting->institute = $institute;
        // save and checking
        if($reportSetting->save()){
            // checking old image
            if($oldImagePath){
                File::delete($oldImagePath);
            }
            // return success msg
            return ['status'=>'success', 'setting_id'=>$reportSetting->id, 'msg'=>'Report Card Setting Submitted. !!!'];
        }else{
            // return failed msg
            return ['status'=>'failed', 'msg'=>'Unable to Submit Report Card Setting. !!!'];
        }
    }


    /**
     * @param $level
     * @param $batch
     * @param $academicYear
     * @param $scaleId
     * @param $campusId
     * @param $instituteId
     * @return mixed|array
     */
    public function getClassSubjectAssessmentDetails($level, $batch, $academicYear, $subjectId, $scaleId, $campusId, $instituteId)
    {
        // $subjectAssessmentArrayList
        $subjectAssessmentDetailArrayList = array();

        // subject assessment profile
        $subjectAssessmentProfile = $this->subjectAssessment->where([
            'level' => $level, 'batch' => $batch, 'grade_scale_id' => $scaleId, 'campus' => $campusId, 'institute' => $instituteId
        ])->first(['id']);

        // checking subjectAssessment profile
        if($subjectAssessmentProfile){
            // subjectAssessment list
            $subjectAssessmentDetailProfile = $subjectAssessmentProfile->subjectAssessmentDetails()->where(['sub_id'=>$subjectId])->first();
            // checking subjectAssessmentDetail
            if($subjectAssessmentDetailProfile){
                //subject assessment marks list
                $subjectAssessmentDetailArrayList = (array)json_decode($subjectAssessmentDetailProfile->assessment_marks, true);
            }
        }

        // return
        return $subjectAssessmentDetailArrayList;
    }

    /**
     * @param $levelId
     * @param $batchId
     * @param $campusId
     * @param $instituteId
     * @param $scaleId
     * @return array
     */
    public function getGradeScaleAssessmentCategoryWeightedAverageList($levelId, $batchId, $campusId, $instituteId, $scaleId)
    {
        // assessment weighted average array list
        $weightedAverageArrayList = array();

        // assessment weighted average marks list
        $assessmentWAList = $this->weightedAverage->where([
            'level_id'=>$levelId, 'batch_id'=>$batchId, 'campus_id'=>$campusId, 'institute_id'=>$instituteId, 'grade_scale_id'=>$scaleId
        ])->get();

        // checking
        if ($assessmentWAList->count() > 0) {
            // looping
            foreach ($assessmentWAList as $assessment) {
                // checking
                if($assessment->marks AND $assessment->marks>0){
                    $weightedAverageArrayList[$assessment->ass_cat_id] = $assessment->marks;
                }
            }
        }
        // return
        return $weightedAverageArrayList;
    }

    /**
     * @param $stdExtraBookList
     * @return array
     */
    public function reArrangeStudentExtraBookList($stdExtraBookList)
    {
        // student extra book array list
        $stdExtraBookArrayList = array();
        // student extra book mark list looping
        foreach ($stdExtraBookList as $singleStdExtraBook) {
            // extra mark id
            $eMarkId = $singleStdExtraBook->id;
            // student id
            $stdId = $singleStdExtraBook->std_id;
            // store extra book mark id
            $stdExtraBookArrayList[$stdId]['mark_id'] = $eMarkId;

            // student extra marks
            $stdExtraMarksDetails = json_decode($singleStdExtraBook->extra_marks);
            $stdExtraMarks = $stdExtraMarksDetails->marks;
            // student extra marks looping
            foreach ($stdExtraMarks as $assId => $mark) {
                // extra mark input
                $stdExtraBookArrayList[$stdId]['mark_list'][$assId] = $mark;
            }
        }
        // return
        return $stdExtraBookArrayList;
    }


// Class section semester student extra book list
    public function getSemesterExtraBook($semesterId, $section,$batch, $level,$academicYear, $campusId, $instituteId)
    {

        // qry maker for student semester extra book marks list
        $extraBookQry = [
            'semester'=>$semesterId, 'section'=>$section, 'batch'=>$batch, 'a_level'=>$level,
            'a_year'=>$academicYear, 'campus'=>$campusId, 'institute'=>$instituteId
        ];
        // find student extra book
        $stdExtraBookList = $this->extraBook->where($extraBookQry)->get(['id', 'std_id', 'extra_marks']);

        // checking
        if($stdExtraBookList->count()>0){
            // extra Marks Array List
            $extraMarksArrayList = array();
            // extra Marks Array List
            $extraMarksDetailsArrayList = array();

            // student extra book mark list looping
            foreach ($stdExtraBookList as $singleStdExtraBook) {
                // total mark counter
                $assTotalMarks = 0;
                // student id
                $stdId = $singleStdExtraBook->std_id;

                // student extra marks
                $stdExtraMarksDetails = json_decode($singleStdExtraBook->extra_marks);
                $stdExtraMarks = $stdExtraMarksDetails->marks;
                // student extra marks looping
                foreach ($stdExtraMarks as $mark) {
                    // checking
                    if(!empty($mark) AND $mark!=null){
                        // extra mark input
                        $assTotalMarks += $mark;
                    }
                }
                // store student total extra marks
                $extraMarksArrayList[$stdId] = $assTotalMarks;
                // store student details extra marks
                $extraMarksDetailsArrayList[$stdId] = $stdExtraMarksDetails;
            }

            // return
            return ['status'=>'success', 'summary'=>$extraMarksArrayList,'details'=>(array)$extraMarksDetailsArrayList];
        }else{
            // return
            return ['status'=>'failed', 'msg'=>'No Extra Book Found'];
        }
    }

    /**
     * @param $allCategoryList
     * @param $gradeScale
     * @param $assessmentInfo
     * @return mixed
     */
    public function getCategoryDetails($allCategoryList, $gradeScale)
    {
        // category details array
        $assessmentInfo = array();
        // category list looping
        foreach ($allCategoryList as $category) {
            if ($category->is_sba == 0) {
                $allAssessmentList = $category->allAssessment($gradeScale->id);
                if (!empty($allAssessmentList) AND $allAssessmentList->count() > 0) {
                    foreach ($allAssessmentList as $assessment) {
                        $assessmentInfo[$category->id][$assessment->id] = $assessment->name;
                    }
                }
            }
        }
        return $assessmentInfo;
    }

}
