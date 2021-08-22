<?php

namespace Modules\Academics\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Modules\Academics\Entities\Batch;
use Modules\Academics\Entities\ExamCategory;
use Modules\Academics\Entities\ExamName;
use Modules\Academics\Entities\GradeDetails;
use Modules\Academics\Entities\Section;
use Modules\Academics\Entities\Semester;
use Modules\Academics\Entities\Grade;
use Modules\Academics\Entities\GradeCategory;
use Modules\Academics\Entities\AcademicsYear;
use Modules\Academics\Entities\AcademicsLevel;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Academics\Entities\ExamStatus;
use Modules\Academics\Entities\ExamSummary;
use Modules\Student\Entities\StudentEnrollment;
use Modules\Student\Entities\StudentProfileView;
use Modules\Academics\Http\Controllers\AssessmentsController;
use Modules\Student\Http\Controllers\reports\StudentAttendanceReportController;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Modules\Student\Entities\StudentInformation;
use Illuminate\Support\Facades\Log;
use Modules\Communication\Entities\SmsTemplate;
use Modules\Student\Entities\StudentParent;
use Modules\Communication\Entities\SmsMessage;
use Modules\Setting\Entities\SmsInstitutionGetway;
use App\Http\Controllers\SmsSender;
use CreateAcademicsYearSemestersTable;
use Modules\Academics\Entities\ClassSubject;
use Modules\Academics\Entities\ExamAttendance;
use Modules\Academics\Entities\ExamMark;
use Modules\Academics\Entities\ExamMarkParameter;
use Modules\Academics\Entities\ExamSchedule;
use Modules\Academics\Entities\Subject;
use Modules\Academics\Entities\SubjectMark;
use Modules\Communication\Entities\SmsBatch;
use Modules\Communication\Entities\SmsLog;


class ExamController extends Controller
{

    private $grade;
    private $batch;
    private $section;
    private $semester;
    private $examStatus;
    private $examSummary;
    private $gradeCategory;
    private $academicHelper;
    private $academicsYear;
    private $academicsLevel;
    private $studentProfileView;
    private $assessmentsController;
    private $studentAttendanceReportController;
    private $smsSender;
    private $gradeDetails;
    private $assessmentReportController;

    // constructor
    public function __construct(Section $section, Batch $batch, Semester $semester, AcademicsYear $academicsYear, AcademicsLevel $academicsLevel,  Grade $grade, AcademicHelper $academicHelper, ExamStatus $examStatus, ExamSummary $examSummary, StudentProfileView $studentProfileView, AssessmentsController $assessmentsController, StudentAttendanceReportController $studentAttendanceReportController, GradeCategory $gradeCategory, SmsSender $smsSender, AssessmentReportController $assessmentReportController, GradeDetails $gradeDetails)
    {
        $this->grade = $grade;
        $this->batch = $batch;
        $this->section = $section;
        $this->semester = $semester;
        $this->examStatus = $examStatus;
        $this->examSummary = $examSummary;
        $this->gradeCategory = $gradeCategory;
        $this->academicHelper = $academicHelper;
        $this->academicsYear = $academicsYear;
        $this->academicsLevel = $academicsLevel;
        $this->studentProfileView = $studentProfileView;
        $this->assessmentsController = $assessmentsController;
        $this->studentAttendanceReportController = $studentAttendanceReportController;
        $this->smsSender = $smsSender;
        $this->gradeDetails = $gradeDetails;
        $this->assessmentReportController = $assessmentReportController;
    }

    // get exam status
    public function getExamStatus(Request $request)
    {
        // institute details
        $campusId = $this->academicHelper->getCampus();
        $instituteId = $this->academicHelper->getInstitute();
        $academicYear = $this->academicsYear->find($request->input('year'));
        $semesterProfile = $this->semester->find($request->input('semester'));
        // semester exam status
        $examStatus = $this->examStatus->where([
            'semester' => $request->input('semester'),
            'section' => $request->input('section'),
            'batch' => $request->input('batch'),
            'level' => $request->input('level'),
            'academic_year' => $request->input('year'),
            'campus' => $campusId,
            'institute' => $instituteId,
        ])->first();

        //        dd($examStatus);
        // return view with variables
        return view('academics::manage-assessments.modals.exam-status', compact('examStatus', 'academicYear', 'semesterProfile'));
    }
        public function editExamCategoryExam($id)
        {
            $examCategory=ExamCategory::where('id','=',$id)->first();
            return view('academics::exam.modal.edit-exam-category', compact('examCategory'));

        }
        public function updateExamCategoryExam(Request $request,$id)
        {
            $examCategory=ExamCategory::where('id','=',$id)->first();
            $categoryUpdate=$examCategory->update([
                'exam_category_name'=>$request->exam_category_name
            ]);
            if($categoryUpdate){
                Session::flash('message', 'Exam Category Update successfully!');
                return redirect()->back();
            }

        }
        public function deleteExamCategory($id){
            $examCategory=ExamCategory::where('id','=',$id)->first();
            $examName = ExamName::where('exam_category_id','=',$id)->first();
            if($examName){
                Session::flash('errorMessage', 'Exam Category Can not be deleted, dependencies on Exam Name!');
                return redirect()->back();
            }
            else{
                $deleteSuccess=$examCategory->delete();
                if($deleteSuccess){
                    Session::flash('message', 'Exam Category deleted successfully!');
                    return redirect()->back();
                }
                else{
                    Session::flash('errorMessage', 'Exam Category Not deleted!');
                    return redirect()->back();
                }
            }
        }
    public function updateExamStatus(Request $request)
    {

        // request details
        $esId = $request->input('es_id');
        $year = $request->input('year');
        $level = $request->input('level');
        $batch = $request->input('batch');
        $section = $request->input('section');
        $semester = $request->input('semester');
        // institute details
        $campusId = $this->academicHelper->getCampus();
        $instituteId = $this->academicHelper->getInstitute();
        // Start transaction!
        DB::beginTransaction();

        // grade creation
        try {
            // find exam status profile
            $examStatus = $this->examStatus->where(['id' => $esId, 'semester' => $semester, 'campus' => $campusId, 'institute' => $instituteId])->first();
            // checking
            if ($examStatus) {
                // checking exam status
                //                if($examStatus->staus==1){
                //                    return ['status'=>'failed', 'msg'=>'semester exam result already published'];
                //                }

                // find semester student list
                $studentList = $this->studentProfileView->where([
                    'academic_year' => $year, 'academic_level' => $level, 'batch' => $batch, 'section' => $section, 'campus' => $campusId, 'institute' => $instituteId
                ])->orderByRaw('LENGTH(gr_no) asc')->orderBy('gr_no', 'asc')->get();

                // checking student list
                if (!empty($studentList) and $studentList->count() > 0) {
                    // find batch section scale id
                    $scaleId = $this->assessmentsController->getGradeScaleId($batch, $section);
                    // grading scale
                    $gradeScale = $this->grade->orderBy('name', 'ASC')->where('id', $scaleId)->first(['id', 'name', 'grade_scale_id']);
                    // student department
                    $stdDeptId = $this->studentAttendanceReportController->findStdDepartment($level, $batch, $year, $campusId, $instituteId);
                    // semester attendance list
                    $semesterAttendanceSheet = (object)$this->studentAttendanceReportController->getStdSemesterAttendanceReport(null, $semester, $stdDeptId, $section, $batch, $year,  $campusId,  $instituteId);

                    // checking semester attendance list
                    if ($semesterAttendanceSheet->status == 'success') {
                        // holiday details
                        $totalHolidays = count($semesterAttendanceSheet->holiday_list);
                        $totalWeekOffDays = count($semesterAttendanceSheet->week_off_day_list);
                        $totalAttendanceDays = $semesterAttendanceSheet->total_attendance_day;
                        $totalWorkingDays = $totalAttendanceDays - ($totalHolidays + $totalWeekOffDays);
                        // attendance_list
                        $attendanceList = (array)$semesterAttendanceSheet->attendance_list;
                    } else {
                        // holiday details
                        $totalHolidays = 0;
                        $totalWeekOffDays = 0;
                        $totalAttendanceDays = 0;
                        $totalWorkingDays = 0;
                        // attendance_list
                        $attendanceList = null;
                    }

                    // weightedAverageArrayList
                    //$weightedAverageArrayList = $this->assessmentsController->getGradeScaleAssessmentCategoryWeightedAverageList($level, $batch, $campusId, $instituteId, $scaleId);

                    // weightedAverageArrayList
                    $weightedAverageArrayList = $this->assessmentsController->getGradeScaleAssessmentCategoryWeightedAverageList($level, $batch, $campusId, $instituteId, $scaleId);
                    // SubjectAssessmentList
                    $subjectAssessmentArrayList =  $this->assessmentsController->getClassAllSubjectAssessmentList($level, $batch, null, $scaleId, $campusId, $instituteId);

                    // semester subject highest marks list
                    /// $subjectHighestMarkList = $this->assessmentsController->getSubjectHighestMarks($instituteId, $campusId, $level, $batch, $section, $scaleId, $semester, $year, $weightedAverageArrayList);
                    // assessment array list
                    $assessmentArrayList = $this->assessmentArrayListMaker($gradeScale, $subjectAssessmentArrayList, $instituteId, $campusId);
                    // find semester extra book marks list
                    $semesterExtraBook = (object)$this->assessmentsController->getSemesterExtraBook($semester, $section, $batch, $level, $year, $campusId, $instituteId);
                    // checking
                    if ($semesterExtraBook->status == 'success') {
                        // extra book summary list
                        $ebSummaryList = (array)$semesterExtraBook->summary;
                        // extra book details list
                        $ebDetailList = (array)$semesterExtraBook->details;
                        // array sorting
                        arsort($ebSummaryList);
                    } else {
                        // extra book details list
                        $ebSummaryList = null;
                        // extra book summary list
                        $ebDetailList = null;
                    }

                    // student marks array list
                    $studentMarkArrayList = [];
                    $studentWAMarkArrayList = [];
                    $studentExtraMarkArrayList = [];
                    // student result array list
                    $studentResultArrayList = array();
                    $studentWAResultArrayList = array();
                    $studentExtraResultArrayList = array();
                    $studentAttendanceArrayList = array();

                    // student list looping
                    foreach ($studentList as $student) {
                        // student id
                        $studentId = $student->std_id;
                        // student semester result sheet
                        $gradeBook = $this->assessmentsController->getStudentGradeMark($instituteId, $campusId, $scaleId, $student->std_id, $semester);

                        $gradeBookWA = $this->assessmentsController->getStudentGradeMarkWeightedAverage($instituteId, $campusId, $scaleId, $student->std_id, $semester, $subjectAssessmentArrayList);

                        // checking
                        if ($gradeBook != null and !empty($gradeBook) and $gradeBookWA != null and !empty($gradeBookWA)) {
                            // student result
                            $semesterTotalResult = (object)$gradeBook['result'];
                            $semesterWATotalResult = (object)$gradeBookWA['result'];
                            // weighted average total
                            $waTotalMarks = floatval($semesterWATotalResult->total_obtained);

                            // result sheet
                            $studentMarkArrayList[$studentId] = $semesterTotalResult->total_obtained;
                            // weighted average result sheet
                            $studentWAMarkArrayList[$studentId] = $waTotalMarks;
                            // extra sheet
                            $studentExtraMarkArrayList[$studentId] = $ebSummaryList ? (floatval((float)$ebSummaryList[$studentId]) + $waTotalMarks) : null;
                            // result sheet
                            $studentResultArrayList[$studentId] = $gradeBook;
                            $studentWAResultArrayList[$studentId] = $gradeBookWA;
                            $studentExtraResultArrayList[$studentId] = $ebDetailList ? ['extra_marks' => $ebDetailList[$studentId], 'highest_mark' => reset($ebSummaryList)] : null;

                            // attendance sheet
                            if ($attendanceList and array_key_exists($studentId, $attendanceList)) {
                                // my attendance list
                                $myAttendanceList = (object)$attendanceList[$studentId];
                                $totalPresent = $myAttendanceList->present;
                                $totalAbsent = $myAttendanceList->absent;
                                // attendance percentage
                                $percentage = round(($totalPresent / $totalWorkingDays) * 100, 2);

                                // store student attendance details
                                $studentAttendanceArrayList[$studentId] = ['total_days' => $totalAttendanceDays, 'holiday' => $totalHolidays, 'week_off_day' => $totalWeekOffDays, 'working_days' => $totalWorkingDays, 'present_days' => $totalPresent, 'absent_day' => $totalAbsent, 'present_percent' => $percentage];
                            } else {
                                // store student attendance details
                                $studentAttendanceArrayList[$studentId] = ['total_days' => $totalAttendanceDays, 'holiday' => $totalHolidays, 'week_off_day' => $totalWeekOffDays, 'working_days' => $totalWorkingDays,];
                            }
                        } else {
                            $msg = $student->first_name . ' ' . $student->middle_name . ' ' . $student->last_name . " (" . $student->gr_no . ") Grade book not found";
                            // return
                            return ['status' => 'failed', 'msg' => $msg];
                        }
                    }

                    // array sorting for merit position
                    arsort($studentMarkArrayList);
                    // array sorting for weighted average merit position
                    arsort($studentWAMarkArrayList);
                    // array sorting for weighted average merit position with extra marks
                    arsort($studentExtraMarkArrayList);

                    // loop counter
                    $stdCounter = 0;
                    // now store student marks
                    foreach ($studentMarkArrayList as $stdId => $stdMarks) {
                        // student weighted average
                        $stdWAMarks = array_key_exists($stdId, $studentWAMarkArrayList) ? $studentWAMarkArrayList[$stdId] : null;
                        // student extra marks
                        $stdExtraMarks = array_key_exists($stdId, $studentExtraMarkArrayList) ? $studentExtraMarkArrayList[$stdId] : null;
                        // student weighted average merit position
                        $stdMeritPosition = array_search($stdId, array_keys($studentMarkArrayList)) + 1;
                        // student weighted average merit position
                        $stdWAMeritPosition = array_search($stdId, array_keys($studentWAMarkArrayList)) + 1;
                        // student weighted average merit position with extra marks
                        $stdExtraMeritPosition = array_search($stdId, array_keys($studentExtraMarkArrayList)) + 1;

                        // checking exam status
                        if ($oldResultSummary = $this->examSummary->where(['es_id' => $esId, 'std_id' => $stdId])->first()) {
                            $resultSummary = $oldResultSummary;
                        } else {
                            // exam summary instance
                            $resultSummary = new $this->examSummary();
                        }

                        // input details
                        $resultSummary->es_id = $esId;
                        $resultSummary->std_id = $stdId;
                        $resultSummary->merit = $stdMeritPosition;
                        $resultSummary->merit_wa = $stdWAMeritPosition;
                        $resultSummary->merit_extra = $stdExtraMeritPosition;
                        $resultSummary->marks = $stdMarks;
                        $resultSummary->marks_wa = $stdWAMarks;
                        $resultSummary->marks_extra = $stdExtraMarks;
                        $resultSummary->result = json_encode($studentResultArrayList[$stdId]); // std semester result
                        $resultSummary->result_wa = json_encode($studentWAResultArrayList[$stdId]); // std wa semester result
                        $resultSummary->result_extra = json_encode($studentExtraResultArrayList[$stdId]); // std semester extra result
                        //$resultSummary->assessments = json_encode($assessmentArrayList); // std assessment list with category details
                        $resultSummary->attendance = json_encode($studentAttendanceArrayList[$stdId]); // std semester extra result
                        // save and checking
                        if ($resultSummary->save()) {
                            $stdCounter += 1;
                        }
                    }

                    // checking
                    if ($stdCounter == count($studentList)) {
                        $examStatus->status = 1;
                        $examStatus->assessments = json_encode($assessmentArrayList);
                        // save and checking
                        if ($examStatus->save()) {
                            // If we reach here, then data is valid and working. Commit the queries!
                            DB::commit();
                            // return
                            return ['status' => 'success', 'msg' => 'Semester Result Published'];
                        } else {
                            // Rollback and then redirect  back to form with errors Redirecting with error message
                            DB::rollback();
                            // return
                            return ['status' => 'failed', 'msg' => 'Unable to update semester exam status'];
                        }
                    } else {
                        // Rollback and then redirect  back to form with errors Redirecting with error message
                        DB::rollback();
                        // return
                        return ['status' => 'failed', 'msg' => 'Unable to store student semester exam summary'];
                    }
                } else {
                    return ['status' => 'failed', 'msg' => 'No students found for this batch section'];
                }
            } else {
                return ['status' => 'failed', 'msg' => 'exam status profile not found'];
            }
        } catch (ValidationException $e) {
            // Rollback and then redirect  back to form with errors Redirecting with error message
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    // get batch section semester merit list
    public function getBachSectionSemesterMeritList(Request $request)
    {
        // request details
        $level = $request->input('academic_level');
        $batch = $request->input('batch');
        $section = $request->input('section', null);
        $semester = $request->input('semester');
        $instituteInfo = $this->academicHelper->getInstituteProfile();
        // institute details
        $academicYear = $this->academicHelper->getAcademicYear();
        $campus = $this->academicHelper->getCampus();
        $institute = $this->academicHelper->getInstitute();

        // find batch and section
        if ($section) {
            $sectionProfile = $this->academicHelper->findSection($section);
        } else {
            $sectionProfile = null;
        }

        $batchProfile = $this->academicHelper->findBatch($batch);
        $semesterProfile = $this->academicHelper->getSemester($semester);
        // checking division name
        if ($batchProfile->get_division()) {
            $batchName = $batchProfile->batch_name . " - " . $batchProfile->get_division()->name;
        } else {
            $batchName = $batchProfile->batch_name;
        }
        // subject details
        $classInfo = [
            'batch' => $batch, 'batch_name' => $batchName,
            'section' => $section, 'section_name' => $sectionProfile ? $sectionProfile->section_name : '',
            'semester' => $semester, 'semester_name' => $semesterProfile->name,
            'campus' => $campus, 'institute' => $institute
        ];
        // scale id
        $scaleId = $this->assessmentsController->getGradeScaleId($batch, $section);
        // student list
        $studentList = $this->studentProfileView->where(['batch' => $batch, 'academic_level' => $level, 'campus' => $campus, 'institute' => $institute]);
        if ($section) {
            $studentList = $this->studentProfileView->where(['section' => $section]);
        }
        // student list
        $studentList = $studentList->orderByRaw('LENGTH(gr_no) asc')->orderBy('gr_no', 'asc')->get();

        // student array list
        $studentArrayList = $this->rearrangeStudentList($studentList);
        // subject assessment array list
        $subjectAssessmentArrayList =  $this->assessmentsController->getClassAllSubjectAssessmentList($level, $batch, null, $scaleId, $campus, $institute);
        // rearrange semester result sheet
        $semesterResultSheet = $this->assessmentsController->rearrangeStudentGradeMark($scaleId,  $studentList, $semester, 1, $subjectAssessmentArrayList);
        // pass fail list
        $semesterMeritList = $this->passFailWithMeritList($semesterResultSheet);

        // compact all variables with view
        view()->share(compact('studentArrayList', 'semesterMeritList', 'instituteInfo', 'classInfo'));
        // generate pdf
        $pdf = App::make('dompdf.wrapper');
        // load semester report card view
        $pdf->loadView('academics::manage-assessments.reports.report-class-section-semester-merit-list')->setPaper('a4', 'portrait');
        return $pdf->stream();
    }

    // pass fail with merit list
    public function passFailWithMeritList($semesterResultSheet)
    {
        // merit array list
        $meritArrayList = [];
        // checking semester result sheet
        if (count($semesterResultSheet) > 0) {
            // semester result sheet looping
            foreach ($semesterResultSheet as $stdId => $marksDetails) {
                // student subject grade list
                $subGradeList = $marksDetails['grade'];
                // total
                $failSubCount = 0;
                $obtainedTotal = 0;
                // checking
                if (count($subGradeList) > 0) {
                    // subject grade list looping
                    foreach ($subGradeList as $subId => $subGradeDetails) {
                        // checking is countable
                        if ($subGradeDetails['is_countable'] == 0) continue;
                        // letter grade
                        $letterGrade = $subGradeDetails['letterGrade'];
                        // total obtained count
                        $obtainedTotal += $subGradeDetails['obtained'];
                        // checking
                        if ($letterGrade == 'F') {
                            $failSubCount += 1;
                        }
                    }
                    // checking fail subject counter
                    if ($failSubCount > 0) {
                        $meritArrayList['fail_list'][$stdId] = (int)($obtainedTotal * 100);
                        $meritArrayList['fail_sub_count'][$stdId] = $failSubCount;
                    } else {
                        $meritArrayList['pass_list'][$stdId] = (int)($obtainedTotal * 100);
                    }
                }
            }
        }

        arsort($meritArrayList['pass_list']);
        arsort($meritArrayList['fail_list']);
        asort($meritArrayList['fail_sub_count']);

        // return merit array list
        return $meritArrayList;
    }



    /**
     * @param $gradeScale
     * @param $instituteId
     * @param $campusId
     * @return array
     */
    public function assessmentArrayListMaker($gradeScale, $subjectAssessmentArrayList, $instituteId, $campusId)
    {
        // response array list
        $assessmentArrayList = array();
        // grade scale assessment count checking
        if ($gradeScale and $gradeScale != null and $gradeScale->assessmentsCount() > 0) {
            // find assessment category
            $assessmentCategory = $this->gradeCategory->where(['institute' => $instituteId, 'campus' => $campusId])->get();
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
                            $list[$assessment->id] = $assessment->name;
                        }
                        // add ass_list to the cat_list
                        $assessmentArrayList[$category->id] = ['cat_name' => $category->name, 'is_sba' => $category->is_sba, 'ass_list' => $list];
                    }
                }
            }
        }

        // add subject assessment list
        $assessmentArrayList['sub_ass_list'] = $subjectAssessmentArrayList;

        // return
        return $assessmentArrayList;
    }



    // update exam status
    //    public function updateExamStatus2(Request $request)
    //    {
    //        // request details
    //        $esId = $request->input('es_id');
    //        $year = $request->input('year');
    //        $level = $request->input('level');
    //        $batch = $request->input('batch');
    //        $section = $request->input('section');
    //        $semester = $request->input('semester');
    //        // institute details
    //        $campusId = $this->academicHelper->getCampus();
    //        $instituteId = $this->academicHelper->getInstitute();
    //        // Start transaction!
    //        DB::beginTransaction();
    //
    //        // grade creation
    //        try {
    //            // find exam status profile
    //            $examStatus = $this->examStatus->where(['id'=>$esId, 'semester'=>$semester, 'campus'=>$campusId, 'institute'=>$instituteId])->first();
    //            // checking
    //            if($examStatus){
    //                // checking exam status
    //                if($examStatus->staus==1){
    //                    return ['status'=>'failed', 'msg'=>'semester exam result already published'];
    //                }
    //
    //                // find semester student list
    //                $studentList = $this->studentProfileView->where([
    //                    'academic_year'=>$year, 'academic_level'=>$level, 'batch'=>$batch, 'section'=>$section, 'campus'=>$campusId, 'institute'=>$instituteId
    //                ])->get();
    //
    //                // checking student list
    //                if(!empty($studentList) AND $studentList->count()>0){
    //                    // find batch section scale id
    //                    $scaleId = $this->assessmentsController->getGradeScaleId($batch, $section);
    //                    // grading scale
    //                    $gradeScale = $this->grade->orderBy('name', 'ASC')->where('id', $scaleId)->first(['id', 'name', 'grade_scale_id']);
    //                    // assessment array list
    //                    $assessmentArrayList = $this->assessmentsController->assessmentArrayListMaker($gradeScale, $instituteId, $campusId);
    //
    //                    // student marks array list
    //                    $studentMarkArrayList = [];
    //                    // student result array list
    //                    $studentResultArrayList = array();
    //
    //                    // student list looping
    //                    foreach ($studentList as $student){
    //                        // student semester result sheet
    //                        $gradeBook = $this->assessmentsController->getStudentGradeMark($instituteId, $campusId, $scaleId, $student->std_id, $semester);
    //                        // checking
    //                        if($gradeBook != null AND !empty($gradeBook)){
    //                            // student result
    //                            $semesterTotalResult = (object)$gradeBook['result'];
    //                            // total marks
    //                            $marksObtained = $semesterTotalResult->total_obtained;
    //                            // result sheet
    //                            $studentMarkArrayList[$student->std_id] = $marksObtained;
    //                            // result sheet
    //                            $studentResultArrayList[$student->std_id] =['assessments'=>$assessmentArrayList, 'grade_book'=>$gradeBook];
    //                        }else{
    //                            $msg = $student->first_name.' '. $student->middle_name.' '. $student->last_name. " (".$student->gr_no.") Grade book not found";
    //                            // return
    //                            return ['status'=>'failed', 'msg'=>$msg];
    //                        }
    //                    }
    //
    //                    // array sorting for merit position
    //                    asort($studentMarkArrayList);
    //                    // student merit position counter
    //                    $stdMeritPosition = count($studentMarkArrayList);
    //                    // now store student marks
    //                    foreach ($studentMarkArrayList as $stdId=>$marks){
    //                        // std semester result
    //                        $stdResult = json_encode($studentResultArrayList[$stdId]);
    //                        // exam summary instance
    //                        $resultSummary = new $this->examSummary();
    //                        // input details
    //                        $resultSummary->es_id = $esId;
    //                        $resultSummary->std_id = $stdId;
    //                        $resultSummary->merit = $stdMeritPosition;
    //                        $resultSummary->marks = $marks;
    //                        $resultSummary->result = $stdResult;
    //                        // save profile
    //                        $resultSummarySaved = $resultSummary->save();
    //                        // save and checking
    //                        if($resultSummarySaved){
    //                            $stdMeritPosition = ($stdMeritPosition-1);
    //                        }
    //                    }
    //
    //                    // checking
    //                    if($stdMeritPosition==0){
    //                        $examStatus->status = 1;
    //                        // save and checking
    //                        if($examStatus->save()){
    //                            // If we reach here, then data is valid and working. Commit the queries!
    //                            DB::commit();
    //                            // return
    //                            return ['status'=>'success', 'msg'=>'Semester Result Published'];
    //                        }else{
    //                            // Rollback and then redirect  back to form with errors Redirecting with error message
    //                            DB::rollback();
    //                            // return
    //                            return ['status'=>'failed', 'msg'=>'Unable to update semester exam status'];
    //                        }
    //                    }else{
    //                        // Rollback and then redirect  back to form with errors Redirecting with error message
    //                        DB::rollback();
    //                        // return
    //                        return ['status'=>'failed', 'msg'=>'Unable to store student semester exam summary'];
    //                    }
    //                }else{
    //                    return ['status'=>'failed', 'msg'=>'No students found for this batch section'];
    //                }
    //            }else{
    //                return ['status'=>'failed', 'msg'=>'exam status profile not found'];
    //            }
    //        } catch (ValidationException $e) {
    //            // Rollback and then redirect  back to form with errors Redirecting with error message
    //            DB::rollback();
    //        } catch (\Exception $e) {
    //            DB::rollback();
    //            throw $e;
    //        }
    //    }

    public function rearrangeStudentList($studentList)
    {
        // student array list
        $studentArrayList = [];
        // student list looping
        foreach ($studentList as $student) {
            // student conversion
            $student = (object)$student;

            $sectionProfile = $this->academicHelper->findSection($student->section);
            $batchProfile = $this->academicHelper->findBatch($student->batch);
            // checking division name
            if ($batchProfile->get_division()) {
                $batchName = $batchProfile->batch_name . " - " . $batchProfile->get_division()->name;
            } else {
                $batchName = $batchProfile->batch_name;
            }


            // array list
            $studentArrayList[$student->std_id] = [
                'roll' => $student->gr_no,
                'name' => $student->first_name . ' ' . $student->middle_name . ' ' . $student->last_name,
                'batch_section' => $batchName . ' - ' . $sectionProfile->section_name,
            ];
        }
        // return
        return $studentArrayList;
    }




    // send result publish sms here

    public function resultPublishSendSms(Request $request)
    {
        //        return $request->all();

        $esId = $request->input('es_id');
        $year = $request->input('year');
        $level = $request->input('level');
        $batch = $request->input('batch');
        $section = $request->input('section');
        $semester = $request->input('semester');
        // institute details
        $campusId = $this->academicHelper->getCampus();
        $instituteId = $this->academicHelper->getInstitute();
        // send publish sms job call
        $this->smsSender->result_publish_job($esId);
        return 'success';
    }






    public function examMarksEntry(Request $request)
    {
        $academicYears = $this->academicHelper->getAllAcademicYears();
        return view('academics::exam.exam-marks-entry', compact('academicYears'));
    }

    public function examStudentSearch(Request $request)
    {
        $yearId = $request->yearId;
        $semesterId = $request->termId;
        $examId = $request->examId;
        $getClass = $request->batchId;
        $getSection = $request->sectionId;
        $getSubject = $request->subjectId;

        $getStudent = StudentProfileView::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
            'batch' => $getClass,
            'section' => $getSection,
        ])->get();

        $examParameter = SubjectMark::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'academic_year_id' => $yearId,
            'semester_id' => $semesterId,
            'exam_id' => $examId,
            'batch_id' => $getClass,
            'section_id' => $getSection,
            'subject_id' => $getSubject,
        ])->first();

        $examMarks = ExamMark::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'academic_year_id' => $yearId,
            'semester_id' => $semesterId,
            'exam_id' => $examId,
            'subject_id' => $getSubject,
            'batch_id' => $getClass,
            'section_id' => $getSection
        ])->get();

        $examAttendances = ExamAttendance::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'academic_year_id' => $yearId,
            'semester_id' => $semesterId,
            'exam_id' => $examId,
            'subject_id' => $getSubject,
            'batch_id' => $getClass,
            'section_id' => $getSection
        ])->get();

        $parameterMarks = json_decode($examParameter->marks, true)['fullMarks'];
        $parameterKeys = array_keys($parameterMarks);
        $parameters = ExamMarkParameter::whereIn('id', $parameterKeys)->get();

        $stdListView = view('academics::exam.exam-marks-entry-list', compact('getStudent', 'examParameter', 'examMarks', 'examAttendances', 'parameterMarks', 'parameters', 'yearId', 'semesterId', 'examId', 'getClass', 'getSection', 'getSubject'))->render();
        return ['status' => 'success', 'msg' => 'Student List found', 'html' => $stdListView];
    }

    public function examSaveStudentMarks(Request $request)
    {
        $subjectMark = SubjectMark::findOrFail($request->subjectMarksId);

        DB::beginTransaction();
        try {
            foreach ($request->marks as $key => $mark) {
                $totalMark = 0;
                $on100 = 0;
                $totalConversionMark = 0;

                foreach ($mark as $criteriaMark) {
                    $totalMark += $criteriaMark;
                }

                if ($totalMark > 0) {
                    $on100 = ($totalMark / $subjectMark->full_marks) * 100;
                    $totalConversionMark = ($on100 * $subjectMark->full_mark_conversion) / 100;
                }

                $previousExamMark = ExamMark::where([
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                    'academic_year_id' => $request->academicYearId,
                    'semester_id' => $request->semesterId,
                    'exam_id' => $request->examId,
                    'subject_id' => $request->subjectId,
                    'batch_id' => $request->batchId,
                    'section_id' => $request->sectionId,
                    'student_id' => $key,
                    'subject_marks_id' => $subjectMark->id,
                ])->first();

                if ($previousExamMark) {
                    $previousExamMark->update([
                        'total_mark' => $totalMark,
                        'total_conversion_mark' => $totalConversionMark,
                        'on_100' => $on100,
                        'breakdown_mark' => json_encode($mark),
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth::id()
                    ]);
                } else {
                    ExamMark::insert([
                        'academic_year_id' => $request->academicYearId,
                        'semester_id' => $request->semesterId,
                        'exam_id' => $request->examId,
                        'subject_id' => $request->subjectId,
                        'batch_id' => $request->batchId,
                        'section_id' => $request->sectionId,
                        'student_id' => $key,
                        'subject_marks_id' => $subjectMark->id,
                        'total_mark' => $totalMark,
                        'total_conversion_mark' => $totalConversionMark,
                        'on_100' => $on100,
                        'breakdown_mark' => json_encode($mark),
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'campus_id' => $this->academicHelper->getCampus(),
                        'institute_id' => $this->academicHelper->getInstitute(),
                    ]);
                }
            }

            DB::commit();
            Session::flash('message', 'Exam marks saved successfully!');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error Saving Exam Marks.');
            return redirect()->back();
        }
    }

    public function examCategoryExam()
    {
        $category = ExamCategory::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->get();

        $semester = Semester::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();

        $examName = ExamName::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->get();

        $batches = Batch::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->get();
        return view('academics::exam.exam-category-exam', compact('semester', 'category', 'examName','batches'));
    }

    public function getAllSemesters()
    {
        return Semester::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();
    }

    public function getAllExamNames()
    {
        return ExamName::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->get();
    }

    public function getAllBatch()
    {
        return Batch::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->get();
    }

    public function getSubjectsFromSection($sectionId)
    {
        $subjectIds = ClassSubject::where('section_id', $sectionId)->pluck('subject_id');
        return Subject::whereIn('id', $subjectIds)->get();
    }

    public function getExamMarkParameters()
    {
        return ExamMarkParameter::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute()
        ])->get();
    }

    public function getBatchesFromExamName($examId)
    {
        $examNameIds = ExamName::findOrFail($examId)->classes;

        return Batch::whereIn('id', $examNameIds)->get();
    }

    public function getExamNamesFromTerm($termId)
    {
        return Semester::findOrFail($termId)->examNames;
    }

    public function examMarks()
    {
        $academicYears = $this->academicHelper->getAllAcademicYears();

        return view('academics::exam.exam-marks', compact('academicYears'));
    }

    public function examSetMarks(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'yearId'      => 'required',
            'termId'      => 'required',
            'examId'      => 'required',
            'batchId'      => 'required',
            'sectionId'      => 'required',
        ]);

        if ($validator->passes()) {
            $academicYears = $this->academicHelper->getAllAcademicYears();
            $examMarkParameters = $this->getExamMarkParameters();
            $selectedAcademicYear = AcademicsYear::findOrFail($request->yearId);
            $semesters = $selectedAcademicYear->semesters();

            $examNames = $this->getExamNamesFromTerm($request->termId);
            $selectedSemester = Semester::FindOrFail($request->termId);
            $batches = $this->getBatchesFromExamName($request->examId);
            $selectedBatch = Batch::findOrFail($request->batchId);
            $allSection = $selectedBatch->section();
            $selectedSection = Section::findOrFail($request->sectionId);
            $exam = ExamName::findOrFail($request->examId);
            $allSubject = $this->getSubjectsFromSection($request->sectionId);

            if ($request->subjectId) {
                $selectedSubject = Subject::findOrFail($request->subjectId);
                $subjects = Subject::where('id', $request->subjectId)->get();
            } else {
                $selectedSubject = null;
                $subjects = $this->getSubjectsFromSection($request->sectionId);
            }

            $subjectMarks = SubjectMark::where([
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
                'academic_year_id' => $request->yearId,
                'semester_id' => $request->termId,
                'exam_id' => $request->examId,
                'batch_id' => $request->batchId,
                'section_id' => $request->sectionId,
            ])->get();

            return view('academics::exam.exam-marks', compact('academicYears', 'selectedAcademicYear', 'semesters', 'selectedSemester', 'examNames', 'batches', 'examMarkParameters', 'exam', 'subjects', 'subjectMarks', 'selectedBatch', 'allSection', 'selectedSection', 'allSubject', 'selectedSubject'));
        } else {
            Session::flash('errorMessage', 'Please input valid data before search!');
            return redirect()->back();
        }
    }


    // Exam Ajax Methods Starts
    public function examSearchSemester(Request $request)
    {
        if ($request->yearId) {
            return AcademicsYear::findOrFail($request->yearId)->semesters();
        } else {
            return [];
        }
    }
    public function examSearchExam(Request $request)
    {
        if ($request->termId) {
            return $this->getExamNamesFromTerm($request->termId);
        } else {
            return [];
        }
    }
    public function examSearchClass(Request $request)
    {
        if ($request->examNameId) {
            return $this->getBatchesFromExamName($request->examNameId);
        } else {
            return [];
        }
    }
    public function examSearchForms(Request $request)
    {
        if ($request->batch) {
            return Section::where('batch_id', $request->batch)->get();
        } else {
            return [];
        }
    }
    // Exam Ajax Methods Ends

    public function examAssignView($id)
    {
        $examAssign = ExamName::where('id','=',$id)->first();
        $sections = Section::with('singleBatch')->where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->get();
        $category = ExamCategory::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->get();
        $batches = Batch::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->get();
        $eXamId = $id;
        //        $exam=ExamName::find($id);
        return view('academics::exam.modal.exam-assign-class', compact('batches', 'sections', 'eXamId','examAssign'));
    }

    public function examClassAssign(Request $request, $id)
    {
//        $classJson = json_encode($request->sections);
        $examName = ExamName::find($id);

        if ($examName) {
            DB::beginTransaction();
            try {
//                $examName->classes = $classJson;
                $examName->classes = $request->sections;
                $sectionEntry = $examName->save();

                if ($sectionEntry) {
                    DB::commit();
                    Session::flash('message', 'New Exam Category created successfully.');
                    return redirect()->back();
                }
            } catch (\Exception $e) {
                DB::rollback();
                return "Error Saving datas!";
            }
        }
    }
    public function editExamName($id)
    {
        $category = ExamCategory::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->get();
        $semester = Semester::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();
        $examName=ExamName::where('id','=',$id)->first();
        return view('academics::exam.modal.edit-exam-name', compact('category','semester','examName'));

    }
    public function updateExamName(Request $request, $id)
    {
//        return $request;
        $validatedData = $request->validate([
            'exam_name' => 'required|unique:cadet_exam_name|max:255',
            'exam_category_id' => 'required',
            'term_id' => 'required',
        ]);
        $examNameDetails=ExamName::where('id','=',$id)->first();
        if($request->effective_on=='on')
        {
            $effective=1;
        }
        else{
            $effective=0;

        }
        DB::beginTransaction();
        try {
            $examName = $examNameDetails->exam_name = $request->exam_name;
            $examNameDetails->exam_category_id  = $request->exam_category_id;
            $examNameDetails->term_id = $request->term_id;
            $examNameDetails->effective_on = $effective;
            $examNameDetails->updated_by = Auth::id();
            $examNameDetails->save();

            if ($examName) {
                DB::commit();
                Session::flash('message', 'New Exam Update successfully.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error Update Exam name.');
            return redirect()->back();
        }
    }
    public function deleteExamName($id){
        $examNameDetails=ExamName::where('id','=',$id)->first();
        $deleteExam=$examNameDetails->delete();
        if($deleteExam)
        {
            Session::flash('message', 'Exam Delete successfully.');
            return redirect()->back();
        }
        else{
            Session::flash('errorMessage', 'Error Delete Exam.');
            return redirect()->back();
        }
    }

    // Exam Ajax Methods Starts
    public function examSearchSubjects(Request $request)
    {
        return $this->getSubjectsFromSection($request->section);
    }

    public function searchSubjectsFromMarks(Request $request)
    {
        $subjectIds = SubjectMark::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'academic_year_id' => $request->yearId,
            'semester_id' => $request->termId,
            'exam_id' => $request->examId,
            'batch_id' => $request->batchId,
            'section_id' => $request->sectionId,
        ])->pluck('subject_id');

        return Subject::whereIn('id', $subjectIds)->get();
    }

    public function examSetMarksPost(Request $request)
    {
        $examMarks = ExamMark::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'academic_year_id' => $request->yearId,
            'semester_id' => $request->termId,
            'exam_id' => $request->examId,
            'subject_id' => $request->subjectId,
            'batch_id' => $request->batchId,
        ])->first();

        if ($examMarks) {
            return "Saved Marks found on this subject, can not change this subject marks!";
        }

        $previousSubjectMark = SubjectMark::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'subject_id' => $request->subjectId,
            'academic_year_id' => $request->yearId,
            'semester_id' => $request->termId,
            'exam_id' => $request->examId,
            'batch_id' => $request->batchId,
            'section_id' => $request->sectionId,
        ])->first();

        DB::beginTransaction();
        try {
            if ($previousSubjectMark) {
                $previousSubjectMark->update([
                    'section_id' => $request->sectionId,
                    'full_marks' => $request->fullMark,
                    'pass_marks' => $request->passMark,
                    'full_mark_conversion' => $request->fullMarkConversion,
                    'pass_mark_conversion' => $request->passMarkConversion,
                    'marks' => $request->marks,
                    'effective_for' => $request->effectiveFor,
                    'status' => $request->type,
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id()
                ]);
            } else {
                $insertSubjectMark = SubjectMark::insert([
                    'subject_id' => $request->subjectId,
                    'academic_year_id' => $request->yearId,
                    'semester_id' => $request->termId,
                    'exam_id' => $request->examId,
                    'batch_id' => $request->batchId,
                    'section_id' => $request->sectionId,
                    'full_marks' => $request->fullMark,
                    'pass_marks' => $request->passMark,
                    'full_mark_conversion' => $request->fullMarkConversion,
                    'pass_mark_conversion' => $request->passMarkConversion,
                    'marks' => $request->marks,
                    'effective_for' => $request->effectiveFor,
                    'status' => $request->type,
                    'created_at' => Carbon::now(),
                    'created_by' => Auth::id(),
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                ]);
            }

            DB::commit();
            return 1;
        } catch (\Exception $e) {
            DB::rollback();
            return "Error Saving datas!";
        }
    }
    // Exam Ajax Methods Ends

    public function getExamCategories()
    {
        return ExamCategory::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->get();
    }

    public function getClassesFromSubject($subjectId)
    {
        $classIds = ClassSubject::where('subject_id', $subjectId)->pluck('class_id');
        return Batch::whereIn('id', $classIds)->get();
    }

    public function getSubjectMarksFromExam($examNameId)
    {
        return SubjectMark::with('subject')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'exam_id' => $examNameId
        ])->get();
    }

    public function getSubjectMarksFromExamAndSubject($examNameId, $subjectId)
    {
        return SubjectMark::with('subject')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'exam_id' => $examNameId,
            'subject_id' => $subjectId,
        ])->get();
    }

    public function getSubjectsFromExam($examNameId)
    {
        $subjectIds = SubjectMark::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'exam_id' => $examNameId
        ])->pluck('subject_id');

        return Subject::whereIn('id', $subjectIds)->get();
    }

    public function examSchedules()
    {
        $academicYears = $this->academicHelper->getAllAcademicYears();
        $examCategories = $this->getExamCategories();

        return view('academics::exam.exam-schedules', compact('academicYears', 'examCategories'));
    }

    // Exam Ajax methods start
    public function examSearchClassesFromExam(Request $request)
    {
        if ($request->examNameId) {
            return $this->getBatchesFromExamName($request->examNameId);
        } else {
            return [];
        }
    }

    public function examSearchSchedule(Request $request)
    {
        function getClasses($classIds)
        {
            return Batch::whereIn('id', $classIds)->get();
        };

        if ($request->classIds) {
            $classes = getClasses($request->classIds);

            $subjectMarks = SubjectMark::with('subject')->where([
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
                'academic_year_id' => $request->yearId,
                'semester_id' => $request->termId,
                'exam_id' => $request->examId,
            ])->whereIn('batch_id', $request->classIds)->get()->groupBy('subject_id');
        } else {
            $classes = $this->getBatchesFromExamName($request->examId);

            $subjectMarks = SubjectMark::with('subject')->where([
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
                'academic_year_id' => $request->yearId,
                'semester_id' => $request->termId,
                'exam_id' => $request->examId,
            ])->get()->groupBy('subject_id');
        }

        $previousSchedules = ExamSchedule::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'academic_year_id' => $request->yearId,
            'semester_id' => $request->termId,
            'exam_id' => $request->examId,
        ])->get();

        $markParameters = $this->getExamMarkParameters();

        $datas = ['subjectMarks' => $subjectMarks->all(), 'classes' => $classes, 'markParameters' => $markParameters, 'previousSchedules' => $previousSchedules];

        return $datas;
    }

    public function examSaveSchedule(Request $request)
    {
        DB::beginTransaction();
        try {
            foreach ($request->batchIds as $batchId) {
                $previousSchedule = ExamSchedule::where([
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                    'academic_year_id' => $request->yearId,
                    'semester_id' => $request->semesterId,
                    'exam_id' => $request->examId,
                    'subject_id' => $request->subjectId,
                    'batch_id' => $batchId,
                ])->first();

                if ($previousSchedule) {
                    $previousSchedule->update([
                        'schedules' => json_encode($request->schedules[$batchId]),
                        'from_date' => Carbon::parse($request->fromDate),
                        'to_date' => Carbon::parse($request->toDate),
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth::id(),
                    ]);
                } else {
                    ExamSchedule::insert([
                        'academic_year_id' => $request->yearId,
                        'semester_id' => $request->semesterId,
                        'exam_id' => $request->examId,
                        'subject_id' => $request->subjectId,
                        'batch_id' => $batchId,
                        'schedules' => json_encode($request->schedules[$batchId]),
                        'from_date' => Carbon::parse($request->fromDate),
                        'to_date' => Carbon::parse($request->toDate),
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'campus_id' => $this->academicHelper->getCampus(),
                        'institute_id' => $this->academicHelper->getInstitute(),
                    ]);
                }
            }

            DB::commit();
            return 'Exam Schedule Saved Successfully!';
        } catch (\Exception $e) {
            DB::rollback();
            return 'Error saving Exam Schedule!';
        }
    }

    // Exam Ajax methods end

    public function examSeatPlan()
    {
        return view('academics::exam.exam-seatPlan');
    }


    public function storeExamCategory(Request $request)
    {
        $validatedData = $request->validate([
            'exam_category_name' => 'required|unique:cadet_exam_category|max:255',
        ]);
        DB::beginTransaction();
        try {
            $examCategory = ExamCategory::insert([
                'exam_category_name' => $request->exam_category_name,
                'created_by' => Auth::id(),
                'campus' => $this->academicHelper->getCampus(),
                'institute' => $this->academicHelper->getInstitute(),
            ]);

            if ($examCategory) {
                DB::commit();
                Session::flash('message', 'New Exam Category created successfully.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error creating physical room.');
            return redirect()->back();
        }
    }
    public function storeExamName(Request $request)
    {
        $validatedData = $request->validate([
            'exam_name' => 'required|unique:cadet_exam_name|max:255',
            'exam_category_id' => 'required',
            'term_id' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $examName = ExamName::insert([
                'exam_name' => $request->exam_name,
                'exam_category_id' => $request->exam_category_id,
                'term_id' => $request->term_id,
                'effective_on' => $request->effective_on == 'on' ? 1 : 0,
                'created_by' => Auth::id(),
                'campus' => $this->academicHelper->getCampus(),
                'institute' => $this->academicHelper->getInstitute(),
            ]);

            if ($examName) {
                DB::commit();
                Session::flash('message', 'New Exam created successfully.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error creating Exam name.');
            return redirect()->back();
        }
    }




    // Exam Attendance Methods start
    public function examAttendance()
    {
        $academicYears = $this->academicHelper->getAllAcademicYears();

        return view('academics::exam.exam-attendance', compact('academicYears'));
    }

    // Exam Attendance Ajax Methods start
    public function searchSubjectsFromExamSchedule(Request $request)
    {
        $subjectIds = ExamSchedule::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'academic_year_id' => $request->academicYearId,
            'semester_id' => $request->semesterId,
            'exam_id' => $request->examId,
            'batch_id' => $request->batchId,
        ])->pluck('subject_id');

        return Subject::whereIn('id', $subjectIds)->get();
    }

    public function searchMarkParametersFromExamSchedule(Request $request)
    {
        $examScheduleRow = ExamSchedule::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'academic_year_id' => $request->academicYearId,
            'semester_id' => $request->semesterId,
            'exam_id' => $request->examId,
            'batch_id' => $request->batchId,
            'subject_id' => $request->subjectId,
        ])->first();

        $examSchedule = json_decode($examScheduleRow->schedules, true);

        return ExamMarkParameter::whereIn('id', array_keys($examSchedule))->get();
    }

    public function searchStudentsForAttendance(Request $request)
    {
        $previousAttendance = ExamAttendance::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'academic_year_id' => $request->academicYearId,
            'semester_id' => $request->semesterId,
            'exam_id' => $request->examId,
            'subject_id' => $request->subjectId,
            'criteria_id' => $request->criteriaId,
            'batch_id' => $request->batchId,
            'section_id' => $request->sectionId
        ])->first();

        $students = StudentProfileView::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
            'academic_year' => $request->academicYearId,
            'batch' => $request->batchId,
            'section' => $request->sectionId
        ])->get();

        $examSchedule = ExamSchedule::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'academic_year_id' => $request->academicYearId,
            'semester_id' => $request->semesterId,
            'exam_id' => $request->examId,
            'subject_id' => $request->subjectId,
            'batch_id' => $request->batchId
        ])->value('schedules');

        $schedule = json_decode($examSchedule, true);

        return [$previousAttendance, $students, $schedule[$request->criteriaId]];
    }

    public function saveStudentsAttendance(Request $request)
    {
        $previousAttendance = ExamAttendance::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'academic_year_id' => $request->academicYearId,
            'semester_id' => $request->semesterId,
            'exam_id' => $request->examId,
            'subject_id' => $request->subjectId,
            'criteria_id' => $request->criteriaId,
            'batch_id' => $request->batchId,
            'section_id' => $request->sectionId
        ])->first();

        DB::beginTransaction();
        try {
            if ($previousAttendance) {
                $previousAttendance->update([
                    'attendance' => json_encode($request->attendance),
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id()
                ]);
            } else {
                ExamAttendance::insert([
                    'academic_year_id' => $request->academicYearId,
                    'semester_id' => $request->semesterId,
                    'exam_id' => $request->examId,
                    'subject_id' => $request->subjectId,
                    'criteria_id' => $request->criteriaId,
                    'batch_id' => $request->batchId,
                    'section_id' => $request->sectionId,
                    'attendance' => json_encode($request->attendance),
                    'created_at' => Carbon::now(),
                    'created_by' => Auth::id(),
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                ]);
            }

            DB::commit();
            return "Attendance saved successfully!";
        } catch (\Exception $e) {
            DB::rollback();
            return "Error saving Attendance!";
        }
    }
}
