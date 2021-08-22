<?php

namespace Modules\Academics\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Academics\Entities\ExamStatus;
use Modules\Academics\Entities\Grade;
use Modules\Academics\Entities\GradeDetails;
use Modules\Academics\Http\Controllers\AssessmentsController;
use Modules\Academics\Entities\ReportCardSetting;
use Modules\Academics\Entities\ExtraBook;
use App;
use Excel;

class AssessmentReportController extends Controller
{

    private $extraBook;
    private $examStatus;
    private $grade;
    private $gradeDetails;
    private $academicHelper;
    private $assessmentsController;
    private $reportCardSetting;

    // constructor
    public function __construct(AcademicHelper $academicHelper, GradeDetails $gradeDetails, AssessmentsController $assessmentsController, ReportCardSetting $reportCardSetting, ExtraBook $extraBook, ExamStatus $examStatus, Grade $grade)
    {
        $this->extraBook = $extraBook;
        $this->examStatus = $examStatus;
        $this->grade = $grade;
        $this->gradeDetails = $gradeDetails;
        $this->academicHelper = $academicHelper;
        $this->reportCardSetting = $reportCardSetting;
        $this->assessmentsController = $assessmentsController;
    }



    // academic final result sheet
    public function getClassSectionFinalResultSheet(Request $request)
    {
        // find academic details
        $academicYear = $this->academicHelper->getAcademicYear();
        $campus = $this->academicHelper->getCampus();
        $institute = $this->academicHelper->getInstitute();
        $instituteInfo = $this->academicHelper->getInstituteProfile();
        // request details
        $level = $request->input('academic_level');
        $batch = $request->input('batch');
        $section = $request->input('section');
        // find batch profile
        $batchProfile = $this->academicHelper->findBatch($batch);
        $sectionProfile = $this->academicHelper->findSection($section);
        // class section  information
        $classInfo = [
            'batch'=>$batch, 'batch_name'=>$batchProfile->batch_name,
            'section'=>$section, 'section_name'=>$sectionProfile->section_name,
        ];

        $scaleId = $this->assessmentsController->getGradeScaleId($batch, $section);
        // grade scale scale details
        $gradeScaleDetails = $this->gradeDetails->where('grade_id', $scaleId)->orderBy('sorting_order', 'ASC')->get();
        // find academic semester list
        $allSemester = $this->academicHelper->getBatchSemesterList($academicYear, $level,  $batch);
        // student list
        $studentList = $this->assessmentsController->getClsssSectionStudentList($batch, $section);
        // class section final result sheet
        $finalResultSheet = $this->getFinalResultSheet($academicYear, $level, $batch, $section, $campus, $institute, $allSemester, null);
        // share all variables with the view
        view()->share(compact('allSemester','finalResultSheet', 'studentList', 'instituteInfo', 'gradeScaleDetails', 'classInfo'));
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('academics::manage-assessments.reports.report-final-result-sheet')->setPaper('a4', 'landscape');
        // stream pdf
        return $pdf->stream();
    }



    // get single student final report
    public function downloadFinalReportCard(Request $request)
    {
        // request details
        $stdId = $request->input('std_id', null);
        $requestType = $request->input('request_type', 'view');
        // campus and institute details
        $campus = $this->academicHelper->getCampus();
        $institute = $this->academicHelper->getInstitute();
        $academicYear = $this->academicHelper->getAcademicYear();

        // checking std id
        if($stdId){
            // fnd student profile
            $studentInfo = $this->academicHelper->findStudent($stdId);
            // std enrollment information
            $stdEnrollment = $studentInfo->singleEnroll();
            $level = $stdEnrollment->academic_level;
            $batch = $stdEnrollment->batch;
            $section = $stdEnrollment->section;
            $academicYear = $stdEnrollment->academic_year;
        }else{
            // std enrollment information
            $level = $request->input('academic_level');
            $batch = $request->input('batch');
            $section = $request->input('section');
        }

        // student list
        $studentList = $this->assessmentsController->getClsssSectionStudentList($batch, $section);
        // find batch section semester list
        $allSemester = $this->academicHelper->getBatchSemesterList($academicYear, $level, $batch);
        // scale id
        $scaleId = $this->assessmentsController->getGradeScaleId($batch, $section);
        // grade scale scale details
        $gradeScaleDetails = $this->gradeDetails->where('grade_id', $scaleId)->orderBy('sorting_order', 'ASC')->get();
        $cateAssessmentArrayList = $this->getCateAssessmentArrayList($scaleId);

        // examResultSheet
        $examResultSheet = $this->getFinalResultSheet($academicYear, $level, $batch, $section, $campus, $institute, $allSemester, $stdId);
        // qry maker for student semester extra book marks list
        $extraBookQry = [ 'section'=>$section, 'batch'=>$batch, 'a_level'=>$level, 'a_year'=>$academicYear, 'campus'=>$campus, 'institute'=>$institute];
        // find student extra book
        $stdExtraBookList = $this->extraBook->where($extraBookQry)->get(['id'])->count();

        // checking request type
        if($requestType=='download'){
            // Report Card Setting
            $reportCardSetting = $this->reportCardSetting->where(['institute'=>$institute, 'campus'=> $campus])->first();
            // institute information
            $instituteInfo = $this->academicHelper->getInstituteProfile();
            // share variables with view
            view()->share(compact('examResultSheet', 'allSemester', 'gradeScaleDetails', 'instituteInfo', 'studentList', 'stdId', 'reportCardSetting', 'stdExtraBookList', 'cateAssessmentArrayList'));
            // generate pdf
            $pdf = App::make('dompdf.wrapper');
            // checking institute and campus
            if($campus==5 && $institute==5){
                return view('academics::manage-assessments.reports.report-final-format-biam');
            }else if($campus==24 && $institute==23){
                // checking level
                if($level==81){
                    // $pdf->loadView('academics::manage-assessments.reports.report-final-format-khsr-1')->setPaper('a4', 'landscape');
                    return view('academics::manage-assessments.reports.report-final-format-khsr-1');
                }else{
//                    $pdf->loadView('academics::manage-assessments.reports.report-final-format-khsr-2')->setPaper('a4', 'landscape');
                    return view('academics::manage-assessments.reports.report-final-format-khsr-2');
                }
            }else{
                $pdf->loadView('academics::manage-assessments.reports.report-final-format-one')->setPaper('a4', 'portrait');
            }
            return $pdf->stream();
        }else{
            // return view with variable
            return view('academics::manage-assessments.modals.report-card-final-format-one', compact('examResultSheet', 'allSemester', 'studentInfo', 'gradeScaleDetails'));
        }
    }


    /**
     * @param $academicYear
     * @param $level
     * @param $batch
     * @param $section
     * @param $campus
     * @param $institute
     * @param $stdId
     * @return array
     */
    public function getFinalResultSheet($academicYear, $level, $batch, $section, $campus, $institute, $allSemester, $stdId)
    {
        // result sheet
        $examResultSheet = array();
        // checking
        if ($allSemester ANd !empty($allSemester) AND count($allSemester) > 0) {
            // semester list looping
            foreach ($allSemester as $semester) {
                // semester id
                $semesterId = $semester['id'];
                // exam status
                $examStatus = $this->academicHelper->findExamStatus($semesterId, $section, $batch, $level, $academicYear, $campus, $institute);
                // checking
                if (!empty($examStatus) AND $examStatus AND $examStatus->status == 1) {
                    // checking std id
                    if ($stdId) {
                        // find exam all mark list (summary list)
                        $examSummaryList = (object)$examStatus->examSummary()->where(['std_id' => $stdId])->get();
                    } else {
                        // find exam all mark list (summary list)
                        $examSummaryList = (object)$examStatus->examSummary()->get();
                    }

                    // checking exam summary list
                    if ($examSummaryList AND $examSummaryList->count() > 0) {
                        // exam summary list looping
                        foreach ($examSummaryList as $singleStudentResult) {
                            // object conversion
                            $singleStudentResult = (object)$singleStudentResult;
                            // student result details
                            $examStdId = $singleStudentResult->std_id;
                            $waMeritPosition = $singleStudentResult->merit_wa;
                            // $waObtainedMarks = $singleStudentResult->marks_wa;
                            // result sheet list for weighted average
                            $waResultList = json_decode($singleStudentResult->result_wa);
                            $extraResultList = json_decode($singleStudentResult->result_extra);
                            $attendanceList = json_decode($singleStudentResult->attendance);
                            // find subject marks list
                            $semesterSubjectMarksList = $this->getSubjectMarksArrayList($waResultList->grade);
                            // checking
                            if ($semesterSubjectMarksList AND count($semesterSubjectMarksList) > 0) {
                                // semester subject marks array list looping
                                foreach ($semesterSubjectMarksList as $subId => $marksDetails) {
                                    // special checking for BIAM Class one (A) student (26), 2019
                                    $skipSubList = [420,422,423,424,425,426,498,499,500, 1893];
                                    $skipStudentList = [17775, 13843,13844,13845,13846];
                                    if((in_array($examStdId, $skipStudentList)) && (in_array($subId, $skipSubList))){continue;}
                                    // class subject profile
                                    if($classSubjectProfile = $this->academicHelper->getClassSubject($subId)){
                                        $marksDetails['semester'] = $semester['name'];
                                        // student subject semester result
                                        $examResultSheet['std_list'][$examStdId]['sub_list'][$subId]['sub_name'] = $marksDetails['sub_name'];
                                        $examResultSheet['std_list'][$examStdId]['sub_list'][$subId]['is_countable'] = $classSubjectProfile->is_countable;
                                        $examResultSheet['std_list'][$examStdId]['sub_list'][$subId]['sub_exam_marks'] = $marksDetails['exam_mark'];
                                        $examResultSheet['std_list'][$examStdId]['sub_list'][$subId]['sub_sem_result'][$semesterId] = $marksDetails;
                                    }
                                }
                            }
                            // semester attendance calculation
                            $examResultSheet['std_list'][$examStdId]['sem_result_summary_list'][$semesterId] = $waResultList->result;
                            $examResultSheet['std_list'][$examStdId]['sem_atd_list'][$semesterId] = $attendanceList;
                            $examResultSheet['std_list'][$examStdId]['sem_merit_list'][$semesterId] = $waMeritPosition;
                            $examResultSheet['std_list'][$examStdId]['sem_extra_marks_list'][$semesterId] = $extraResultList;
                        }
                        // response data set
                        $examResultSheet['status'] = 'success';
                    } else {
                        // response data set
                        $examResultSheet['status'] = 'failed';
                        $examResultSheet['msg'] = 'No Exam Summary found';
                    }
                } else {
                    // response data set
                    $examResultSheet['status'] = 'failed';
                    $examResultSheet['msg'] = $semester['name'] . ' Exam Not Published';
                }
            }

            // class section final merit list
            $examResultSheet['section_final_merit_list'] = $this->classSectionFinalMeritList($section, $batch, $level, $academicYear, $campus, $institute);
            $examResultSheet['class_final_merit_list'] = $this->classSectionFinalMeritList(null, $batch, $level, $academicYear, $campus, $institute);
        } else {
            // response data set
            $examResultSheet['status'] = 'failed';
            $examResultSheet['msg'] = 'No Semester found';
        }
        // return exam result sheet
        return $examResultSheet;
    }

    // find class section final merit list
    public function classSectionFinalMeritList($section=null, $batch, $level, $academicYear, $campus, $institute)
    {
        // scale id
        $scaleId = $this->assessmentsController->getGradeScaleId($batch, $section);
        // grade scale scale details
        $gradeScaleDetails = $this->gradeDetails->where('grade_id', $scaleId)->orderBy('sorting_order', 'ASC')->get();
        // find academic semester list
        $semesterList = $this->academicHelper->getAcademicSemester();
        // checking academic semester list
        if($semesterList->count()>0){
            // semester list looping
            foreach ($semesterList as $semester){
                // exam status list
                $examStatusList = $this->examStatus->where([
                    'semester'=>$semester->id, 'batch'=>$batch, 'level'=>$level, 'academic_year'=>$academicYear, 'campus'=>$campus, 'institute'=>$institute,
                ]);
                // checking section list
                if($section){ $examStatusList->where(['section'=>$section]);}
                // exam status list
                $examStatusList = $examStatusList->get();

                // exam status list looping
                foreach ($examStatusList as $examStatus){
                    // checking
                    if (!empty($examStatus) AND $examStatus AND $examStatus->status == 1) {
                        // find semester exam all mark summary list
                        $examSummaryList = (object)$examStatus->examSummary()->get();
                        // checking exam summary list
                        if($examSummaryList->count()>0){
                            // exam summary list looping
                            foreach ($examSummaryList as $singleStudentResult) {
                                // object conversion
                                $singleStudentResult = (object)$singleStudentResult;
                                // student result details
                                $examStdId = $singleStudentResult->std_id;
                                // result sheet list for weighted average
                                $waResultList = json_decode($singleStudentResult->result_wa);
                                // find subject marks list
                                $semesterSubjectMarksList = $this->getSubjectMarksArrayList($waResultList->grade);
                                // checking
                                if ($semesterSubjectMarksList AND count($semesterSubjectMarksList) > 0) {
                                    // semester subject marks array list looping
                                    foreach ($semesterSubjectMarksList as $subId => $marksDetails) {
                                        // class subject profile
                                        if($classSubjectProfile = $this->academicHelper->getClassSubject($subId)){
                                            // special checking for BIAM Class one (A) student (26), 2019
                                            $skipSubList = [420,422,423,424,425,426,498,499,500, 1893];
                                            $skipStudentList = [17775, 13843,13844,13845,13846];
                                            if((in_array($examStdId, $skipStudentList)) && (in_array($subId, $skipSubList))){continue;}

                                            // checking is countable
                                            if($classSubjectProfile->is_countable>0){
                                                $this->meritList($semester->id, $examStdId, $subId, $marksDetails, $gradeScaleDetails);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            // return semester marks list
            return $this->rearrangeFinalMeritList($this->finalMeritList);
        }else{
            return [];
        }
    }


    // merit list
    private $finalMeritList = [];
    private $semesterCounter = [];
    private $my_list = ['pass_list'=>[], 'fail_list'=>[], 'fail_sub_count'=>[]];

    // merit list
    public function meritList($semesterId, $stdId, $subId, $marksDetails, $gradeScaleDetails){

        // checking semester
        if(in_array($semesterId, $this->semesterCounter)==false) $this->semesterCounter[] = $semesterId;

        // checking student in merit list
        if(array_key_exists($stdId, $this->finalMeritList)){
            // checking subject in student list
            if(array_key_exists($subId, $this->finalMeritList[$stdId])){
                $this->finalMeritList[$stdId][$subId]['count'] += 1;
                $this->finalMeritList[$stdId][$subId]['total'] += $marksDetails['exam_mark'];
                $this->finalMeritList[$stdId][$subId]['obtained'] += $marksDetails['obtained'];
                // my aggregated marks
                $myAggregated = round((($this->finalMeritList[$stdId][$subId]['obtained'])/(count($this->semesterCounter))), 2);
                $this->finalMeritList[$stdId][$subId]['aggregated'] = $myAggregated;
                // my percentage marks
                $myPercentage = round((($this->finalMeritList[$stdId][$subId]['obtained'])/($this->finalMeritList[$stdId][$subId]['total'])*100), 2, PHP_ROUND_HALF_UP);
                $this->finalMeritList[$stdId][$subId]['percentage'] = $myPercentage;
                // my letter grade
                $myLetterGrade = $this->assessmentsController->subjectGradeCalculation((int)$myPercentage, $gradeScaleDetails)['grade'];
                $this->finalMeritList[$stdId][$subId]['letterGrade'] = $myLetterGrade;
            }else{
                $this->finalMeritList[$stdId][$subId]['count'] = 1;
                $this->finalMeritList[$stdId][$subId]['total'] = $marksDetails['exam_mark'];
                $this->finalMeritList[$stdId][$subId]['obtained'] = $marksDetails['obtained'];
                // my aggregated marks
                $myAggregated = round((($marksDetails['obtained'])/(count($this->semesterCounter))), 2);
                $this->finalMeritList[$stdId][$subId]['aggregated'] = $myAggregated;
                // my percentage marks
                $myPercentage = round((($this->finalMeritList[$stdId][$subId]['obtained'])/($this->finalMeritList[$stdId][$subId]['total'])*100), 2, PHP_ROUND_HALF_UP);
                $this->finalMeritList[$stdId][$subId]['percentage'] = $myPercentage;
                // my letter grade
                $myLetterGrade = $this->assessmentsController->subjectGradeCalculation((int)$myPercentage, $gradeScaleDetails)['grade'];
                $this->finalMeritList[$stdId][$subId]['letterGrade'] = $myLetterGrade;
            }
        }else{
            $this->finalMeritList[$stdId][$subId]['count'] = 1;
            $this->finalMeritList[$stdId][$subId]['total'] = $marksDetails['exam_mark'];
            $this->finalMeritList[$stdId][$subId]['obtained'] = $marksDetails['obtained'];
            // my aggregated marks
            $myAggregated = round((($marksDetails['obtained'])/(count($this->semesterCounter))), 2);
            $this->finalMeritList[$stdId][$subId]['aggregated'] = $myAggregated;
            // my percentage marks
            $myPercentage = round((($this->finalMeritList[$stdId][$subId]['obtained'])/($this->finalMeritList[$stdId][$subId]['total'])*100), 2, PHP_ROUND_HALF_UP);
            $this->finalMeritList[$stdId][$subId]['percentage'] = $myPercentage;
            // my letter grade
            $myLetterGrade = $this->assessmentsController->subjectGradeCalculation((int)$myPercentage, $gradeScaleDetails)['grade'];
            $this->finalMeritList[$stdId][$subId]['letterGrade'] = $myLetterGrade;
        }
    }

    public function rearrangeFinalMeritList($finalMeritList)
    {
        // student list looping
        foreach ($finalMeritList as $stdId=>$subList){
            // subject list looping
            foreach ($subList as $subId=>$marksDetails){
                // checking student in fail list
                if((array_key_exists($stdId, $this->my_list['fail_list'])==false) && ($marksDetails['letterGrade']!="F")){
                    // checking student in pass list
                    if(array_key_exists($stdId, $this->my_list['pass_list'])){
                        $this->my_list['pass_list'][$stdId] += (int) round($marksDetails['obtained']*100, 2);
                    }else{
                        $this->my_list['pass_list'][$stdId] = (int) round($marksDetails['obtained']*100, 2);
                    }
                }else{
                    // checking student in fail list
                    if(array_key_exists($stdId, $this->my_list['fail_list'])){
                        $this->my_list['fail_list'][$stdId] += (int) round($marksDetails['obtained']*100, 2);
                    }else{
                        $this->my_list['fail_list'][$stdId] = (int) round($marksDetails['obtained']*100, 2);
                        // initiate student fail subject counter
                        $this->my_list['fail_sub_count'][$stdId] = 0;
                    }
                    // remove failed student from pass list
                    if(array_key_exists($stdId, $this->my_list['pass_list'])){
                        $this->my_list['fail_list'][$stdId] += $this->my_list['pass_list'][$stdId];
                        // remove student from pass list
                        unset($this->my_list['pass_list'][$stdId]);
                    }
                }

                // pass fail counter
                if($marksDetails['letterGrade']=="F"){
                    $this->my_list['fail_sub_count'][$stdId] += 1;
                }
            }
        }

        arsort($this->my_list['pass_list']);
        arsort($this->my_list['fail_list']);
        asort($this->my_list['fail_sub_count']);
        // my list
        $myList = $this->my_list;

        // reset
        $this->finalMeritList = [];
        $this->my_list = ['pass_list'=>[], 'fail_list'=>[], 'fail_sub_count'=>[]];

        // return
        return $myList;
    }


    /**
     * @param $gradeSheet
     * @return array
     */
    public function getSubjectMarksArrayList($gradeSheet)
    {
        //response data
        $response = array();
        // checking
        if (!empty($gradeSheet)) {

            // grade sheet looping
            foreach ($gradeSheet as $subjectGrade) {
                $response[$subjectGrade->cs_id] = [
                    'sub_name'=>$subjectGrade->sub_name,
                    'total'=>$subjectGrade->total,
                    'obtained'=>$subjectGrade->obtained,
                    'exam_mark'=>$subjectGrade->exam_mark,
                    'pass_mark'=>$subjectGrade->pass_mark,
                    'percentage'=>$subjectGrade->percentage,
                    'letterGrade'=>$subjectGrade->letterGrade,
                    'letterGradePoint'=>$subjectGrade->letterGradePoint,
                    'mark'=>$subjectGrade->mark
                ];
            }
        }

        // return response data
        return $response;
    }

    /**
     * @param $scaleId
     */
    public function getCateAssessmentArrayList($scaleId)
    {
        // response array list
        $responseArrayList = [];

        // grade scale
        $gradeScale = $this->grade->orderBy('name', 'ASC')->where('id', $scaleId)->first(['id', 'name', 'grade_scale_id']);
        // checking assessment category
        if ($gradeScale && $gradeScale->assessmentsCount() > 0) {
            // grading scale assessment category list
            $allCategoryList = $gradeScale->assessmentCategory();
            // checking $allCategoryList
            if (!empty($allCategoryList) AND $allCategoryList->count() > 0) {
                // category list looping
                foreach ($allCategoryList as $category) {
                    // checking category type
                    if ($category->is_sba == 1) continue;
                    $allAssessmentList = $category->allAssessment($gradeScale->id);
                    // checking assessment list
                    if (!empty($allAssessmentList) AND $allAssessmentList->count() > 0) {
                        $responseArrayList[$category->id]['name'] = $category->name;
                        // assessment list looping
                        foreach ($allAssessmentList as $assessment) {
                            $responseArrayList[$category->id]['ass_list'][$assessment->id] =$assessment->name;
                        }
                    }
                }
            }
        }
        // return
        return $responseArrayList;
    }
}
