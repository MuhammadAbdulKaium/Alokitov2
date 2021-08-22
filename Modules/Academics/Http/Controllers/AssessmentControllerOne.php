<?php

namespace Modules\Academics\Http\Controllers;

use App\Http\Controllers\Helpers\SessionHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Academics\Entities\AcademicsLevel;
use Modules\Academics\Entities\AdditionalSubject;
use Modules\Academics\Entities\AssessmentPassMark;
use Modules\Academics\Entities\Assessments;
use Modules\Academics\Entities\ClassGradeScale;
use Modules\Academics\Entities\ClassSubject;
use Modules\Academics\Entities\ExamStatus;
use Modules\Academics\Entities\ExtraBook;
use Modules\Academics\Entities\Grade;
use Modules\Academics\Entities\GradeCategory;
use Modules\Academics\Entities\GradeCategoryAssign;
use Modules\Academics\Entities\GradeDetails;
use Modules\Academics\Entities\GradeScale;
use Modules\Academics\Entities\ReportCardSetting;
use Modules\Academics\Entities\StudentGrade;
use Modules\Academics\Entities\StudentMark;
use Modules\Academics\Entities\SubjectAssessment;
use Modules\Academics\Entities\SubjectAssessmentDetails;
use Modules\Academics\Entities\WeightedAverage;
use Modules\Setting\Entities\AutoSmsModule;
use Modules\Setting\Entities\Institute;
use Modules\Student\Entities\StudentEnrollment;
use Modules\Student\Entities\StudentInformation;
use Modules\Student\Entities\StudentProfileView;
use Modules\Student\Http\Controllers\reports\StudentAttendanceReportController;

class AssessmentControllerOne extends Controller
{

    private $institute;
    private $studentInfo;
    private $classSubject;
    private $academicHelper;
    private $classGradeScale;


    // constructor
    public function __construct(AcademicHelper $academicHelper, Institute $institute, StudentInformation $studentInfo, ClassSubject $classSubject, ClassGradeScale $classGradeScale)
    {
        $this->institute = $institute;
        $this->studentInfo = $studentInfo;
        $this->classSubject = $classSubject;
        $this->academicHelper = $academicHelper;
        $this->classGradeScale = $classGradeScale;
    }


    public function downloadSingleReportCard(Request $request)
    {

        //  return $id;
        $docType = 'pdf';
        $stdId = $request->input('std_id');
        $reportType = $request->input('report_type');

        // find student profile and checking
        if($studentInfo = $this->studentInfo->where(['id'=>$stdId, 'status'=>1])->first()){

            // student information details
            $instituteId = $studentInfo->institute;
            $campusId = $studentInfo->campus;
            // student institute profile
            $instituteInfo = $this->institute->find($instituteId);
            // std enrollment information
            $stdEnrollment = $studentInfo->singleEnroll();
            $level = $stdEnrollment->academic_level;
            $batch = $stdEnrollment->batch;
            $section = $stdEnrollment->section;
            $academicYear = $stdEnrollment->academicsYear();
            // get class grading scale
            $scaleId = $this->classGradeScale->getClassGradeScale($batch);

            //student class-section subject list
           return $classSubjects = $this->classSubject->getClassSubjectList($batch);
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


        }else{
            return 'student not found';
        }
    }



}
