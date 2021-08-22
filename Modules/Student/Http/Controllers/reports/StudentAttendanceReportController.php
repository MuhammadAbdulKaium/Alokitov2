<?php

namespace Modules\Student\Http\Controllers\reports;

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
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Academics\Entities\AttendanceUpload;
use Modules\Employee\Http\Controllers\NationalHolidayController;
use Modules\Employee\Http\Controllers\WeekOffDayController;
use Modules\Employee\Entities\StudentDepartment;
use Redirect;
use Session;
use Validator;

class StudentAttendanceReportController extends Controller
{
    private $classSubject;
    private $studentInformation;
    private $studentGuardian;
    private $attendanceSetting;
    private $studentAttendance;
    private $studentAttendanceDetails;
    private $studentEnrollment;
    private $attendanceViewOne;
    private $attendanceViewTwo;
    private $academicHelper;
    private $attendanceUpload;
    private $holidayController;
    private $weekOffDayController;
    private $studentDepartment;


    public function __construct(ClassSubject $classSubject, StudentInformation $studentInformation, StudentEnrollment $studentEnrollment, AttendanceSetting $attendanceSetting, StudentAttendance $studentAttendance, StudentAttendanceDetails $studentAttendanceDetails, AttendanceViewOne $attendanceViewOne, AttendanceViewTwo $attendanceViewTwo,  AcademicHelper $academicHelper, StudentGuardian $studentGuardian, AttendanceUpload $attendanceUpload, NationalHolidayController $holidayController, WeekOffDayController $weekOffDayController, StudentDepartment $studentDepartment)
    {
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
        $this->attendanceUpload        = $attendanceUpload;
        $this->holidayController        = $holidayController;
        $this->weekOffDayController        = $weekOffDayController;
        $this->studentDepartment        = $studentDepartment;
    }

    public function searchAttendanceReport($id)
    {
        // academic semester list
        $semesterList = $this->academicHelper->getAcademicSemester();
        return view('student::pages.student-profile.modals.attendance-report', compact('semesterList'))->with('id', $id);
    }

    public function attendanceReport(Request $request)
    {
        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'std_id'    => 'required',
            'from_date' => 'required',
            'to_date'   => 'required',
            'attendance_type'  => 'required',
            'doc_type'  => 'required',
        ]);

        // storing requesting input data
        if ($validator->passes()) {
            // all input data
            $instituteId   = $this->getInstituteId();
            $campusId      = $this->getInstituteCampusId();
            $studentId     = $request->input('std_id');
            $inputToDate   = new Carbon($request->input('to_date'));
            $toDate        = $inputToDate->toDateString();
            $inputFromDate = new Carbon($request->input('from_date'));
            $fromDate      = $inputFromDate->toDateString();
            $attendanceType  = $request->input('attendance_type', 'att_class');
            $docType       = $request->input('doc_type', 'pdf');
            $requestType       = $request->input('request_type', 'download');

            // checking class/school attendance
            if($attendanceType=='att_class'){
                return $this->getStdClassAttendance($instituteId, $campusId, $studentId, $fromDate, $toDate, $requestType, $docType);
            }else{
                return $this->getStdSchoolAttendance($instituteId, $campusId, $studentId, $fromDate, $toDate, $requestType, $docType);
            }
        } else {
            Session::flash('warning', 'Invalid Information');
            // receiving page action
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    public function studentAbsentDaysSummary($requestDataSource)
    {
        // input details
        $fromDate    = $requestDataSource->fromDate;
        $toDate      = $requestDataSource->toDate;
        $docType     = $requestDataSource->docType;
        $level       = $requestDataSource->level;
        $batch       = $requestDataSource->batch;
        $section     = $requestDataSource->section;
        $instituteId = $requestDataSource->instituteId;
        $campusId    = $requestDataSource->campusId;
        $attType = null;

        $classSectionInfo = (object)[
            'level'=>$this->academicHelper->findLevel($level)->level_name,
            'batch'=>$this->academicHelper->findBatch($batch)->batch_name,
            'section'=>$this->academicHelper->findSection($section)->section_name,
        ];

        // institute
        $instituteInfo = $this->academicHelper->getInstituteProfile();

        // institue attendance settings
        $attendanceInfo = $this->attendanceSetting->where(['institution_id' => $instituteId, 'campus_id' => $campusId])->first();
        // student list
        $studentList = $this->getClsssSectionStudentList($batch, $section);
        // all subject list
        $academicSubjects = $this->getClsssSectionSubjectList($batch, $section);
        // attendance raw data
        $attendanceRawData = $this->attendanceViewTwo->orderBy('sorting_order', 'ASC')->where(['class_id' => $batch, 'section_id' => $section])->whereBetween('attendance_date', array($fromDate, $toDate))->whereNull('deleted_at')->get();

        // student attendance list
        $attendanceList = array();
        // student looping
        for($i=0; $i<count($studentList); $i++){
            // student looping
            for($x=0; $x<count($academicSubjects); $x++){
                $attendanceList['std_'.$studentList[$i]['id']]['sub_'.$academicSubjects[$x]['id']]  = $this->countAttendance($studentList[$i]['id'], $academicSubjects[$x]['cs_id'], $attendanceRawData);
            }
        }
        // share all variables with the view
        view()->share(compact('attendanceList', 'studentList', 'academicSubjects', 'docType', 'attType', 'instituteInfo', 'classSectionInfo'));
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('student::reports.class-section-absent-summary-report')->setPaper('a4', 'landscape');
        // return $pdf->stream();
        return $pdf->download('pdfview.pdf');
    }


    public function countAttendance($stdId, $subjectId, $attendanceList)
    {
        $result = array();
        $sortedAttendanceList =  $this->studentSubjectAttendanceSort($subjectId, $this->studentAttendanceSort($stdId, $attendanceList));
        $result['P'] = $this->studentAttendanceTypeSort(1, $sortedAttendanceList)->count();
        $result['A'] = $this->studentAttendanceTypeSort(0, $sortedAttendanceList)->count();
        // return result
        return $result;
    }


    public function studentAttendanceTypeSort($attType, $attendanceList)
    {
        // filtering
        $attendanceList = $attendanceList->filter(function($attendance) use ($attType)
        {
            return $attendance->attendacnce_type == $attType;
        });
        // return attendance List
        return $attendanceList;
    }

    public function studentAttendanceSort($stdId, $attendanceList)
    {
        // filtering
        $attendanceList = $attendanceList->filter(function($attendance) use ($stdId)
        {
            return $attendance->student_id == $stdId;
        });
        // return attendance List
        return $attendanceList;
    }


    public function studentSubjectAttendanceSort($subjectId, $attendanceList)
    {
        // filtering
        $attendanceList = $attendanceList->filter(function($attendance) use ($subjectId)
        {
            return $attendance->subject_id == $subjectId;
        });
        // return attendance List
        return $attendanceList;
    }

    public function studentAbsentDaysDetails($requestDataSource)
    {
        // input details
        $studentId = $requestDataSource->stdId;
        $fromDate  = $requestDataSource->fromDate;
        $toDate    = $requestDataSource->toDate;
        $docType   = $requestDataSource->docType;
        //$docType     ='xlsx';
        $instituteId = $requestDataSource->instituteId;
        $campusId    = $requestDataSource->campusId;
        $attType       = null;

        // institue attendance settings
        $attendanceInfo = $this->attendanceSetting->where(['institution_id' => $instituteId, 'campus_id' => $campusId])->first();
        if($attendanceInfo->subject_wise==1){
            // attendanceType
            $attType = 'subject';
        }else{
            // attendanceType
            $attType = 'date';
        }

        // institute
        $instituteInfo = $this->academicHelper->getInstituteProfile();
        // studentProfile
        $studentInfo = $this->studentInformation->where('id', $studentId)->first();
        // enrollment Profile
        $studentEnrollment = $studentInfo->singleEnroll();
        // academic details
        $studentBatch   = $studentEnrollment->batch;
        $studentSection = $studentEnrollment->section;

        // all subject list
        $academicSubjects = $this->getClsssSectionSubjectList($studentBatch, $studentSection);

        // studentAttendanceList
        $studentAttendanceList = $this->getSingleStudentAttendanceList($studentId, $studentBatch, $studentSection, $fromDate, $toDate, $attendanceInfo);

        // share all variables with the view
        view()->share(compact('studentInfo', 'studentAttendanceList', 'instituteInfo', 'academicSubjects', 'docType', 'attType'));

        if ($docType == 'pdf') {
            // generate pdf
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView('student::reports.student-absent-details-report')->setPaper('a4', 'landscape');
            // return $pdf->stream();
            return $pdf->download('pdfview.pdf');
        } else {
            //generate excel
            Excel::create('Student Assessment Form', function ($excel) {
                $excel->sheet('Student Absent Report', function ($sheet) {
                    // Font family
                    $sheet->setFontFamily('Comic Sans MS');
                    // Set font with ->setStyle()
                    $sheet->setStyle(array('font' => array('name' => 'Calibri', 'size' => 12)));
                    // cell formatting
                    $sheet->setAutoSize(true);
                    // Set all margins
                    $sheet->setPageMargin(0.25);
                    // mergeCell
                    // $sheet->mergeCells(['C3:D1', 'E1:H1']);

                    $sheet->loadView('student::reports.student-absent-details-report');
                });
            })->download('xls');
        }

    }




    // get class-section-subject single student list
    public function getSingleStudentAttendanceList($studentId,$studentBatch, $studentSection,  $fromDate, $toDate, $attendanceInfo)
    {
        // studentAttendanceList
        $studentAttendanceList = array();
        // student all unique attendance date
        $attendanceDates = $this->studentAttendance->orderBy('attendance_date', 'ASC')->where('student_id', $studentId)->distinct('attendance_date')->whereBetween('attendance_date', array($fromDate, $toDate))->limit(500)->get(['attendance_date']);

        // now get all attendance on a specific date
        foreach ($attendanceDates as $singleDate) {

            // checking
            if ($attendanceInfo->subject_wise == 1) {
                // single date all subject attendance
                $allSubjectAttendance = $this->attendanceViewTwo->orderBy('sorting_order', 'ASC')->where(['student_id' => $studentId, 'class_id' => $studentBatch, 'section_id' => $studentSection, 'attendance_date' => $singleDate->attendance_date])->whereNull('deleted_at')->get();
                if ($allSubjectAttendance) {
                    // single date attendance array
                    $singeDateAttendanceArray = array();

                    // attendance
                    foreach ($allSubjectAttendance as $singleAttendance) {
                        // date
                        $singeDateAttendanceArray['date'] = $singleDate->attendance_date;
                        // class  details
                        $subjectName = $singleAttendance->classSubject()->subject()->subject_name;
                        $subjectId   = $singleAttendance->classSubject()->subject()->id;
                        // attendance
                        $singeDateAttendanceArray['attendance'][$subjectId] = $singleAttendance->attendacnce_type;
                    }
                    // checking
                    if ($singeDateAttendanceArray) {
                        $studentAttendanceList[] = $singeDateAttendanceArray;
                    }
                }
                // attendanceType
                $attType = 'subject';
            } else {
                // single date all subject attendance
                $allAttendance = $this->attendanceViewOne->where(['student_id' => $studentId, 'class_id' => $studentBatch, 'section_id' => $studentSection, 'attendance_date' => $singleDate->attendance_date])->whereNull('deleted_at')->get();
                // checking
                if ($allAttendance) {
                    // single date attendance array
                    $singeDateAttendanceArray = array();
                    // attendance
                    foreach ($allAttendance as $singleAttendance) {
                        // date
                        $singeDateAttendanceArray['date'] = $singleDate->attendance_date;
                        // attendance
                        $singeDateAttendanceArray['attendance'] = $singleAttendance->attendacnce_type;
                    }
                    // checking
                    if ($singeDateAttendanceArray) {
                        $studentAttendanceList[] = $singeDateAttendanceArray;
                    }
                }
                // attendanceType
                $attType = 'date';
            }
        }
        // return attendance list
        return $studentAttendanceList;
    }

    // get class-section-subject student list
    public function getClassUniqueSubjectList($class)
    {
        $studentAllSubjects = $this->classSubject->select('subject_id', 'sorting_order')->where(['class_id' => $class])->orderBy('sorting_order', 'ASC')->distinct()->get(['id','subject_id', 'sorting_order']);
        // subject array list
        $academicClassSectionSubjects = array();
        // looping
        foreach ($studentAllSubjects->unique('subject_id') as $singleSubject) {
            // get subject details
            $subjectDetails = $singleSubject->subject();
            $subjectGroup = $subjectDetails->checkSubGroupAssign();
            // adding to the array list
            $academicClassSectionSubjects[] = [
                'cs_id'=>$singleSubject->id,
                'id' => $subjectDetails->id,
                'name' => $subjectDetails->subject_name,
                'code' => $subjectDetails->subject_code,
                'has_group' => $subjectGroup?1:0,
                'group_id' => $subjectGroup?$subjectGroup->subjectGroup()->id:'0',
                'group_name' => $subjectGroup?$subjectGroup->subjectGroup()->sub_group_name:'No Group',
            ];
        }
        // return academic class subject
        return $academicClassSectionSubjects;
    }

    // get class-section-subject list
    public function getClsssSectionSubjectList($class, $section)
    {
        $studentAllSubjects = $this->classSubject->where(['class_id' => $class, 'section_id' => $section])->orderBy('sorting_order', 'ASC')->get();
        // subject array list
        $academicClassSectionSubjects = array();
        // looping
        foreach ($studentAllSubjects as $singleSubject) {
            // get subject details
            $subjectDetails = $singleSubject->subject();
            $subjectGroup = $singleSubject->subjectGroup();
            // adding to the array list
            $academicClassSectionSubjects[] = [
                'cs_id'=>$singleSubject->id,
                'id' => $subjectDetails->id,
                'name' => $subjectDetails->subject_name,
                'code' => $subjectDetails->subject_code,
                'pass_mark' => $singleSubject->pass_mark,
                'exam_mark' => $singleSubject->exam_mark,
                'is_countable' => $singleSubject->is_countable,
                'has_group' => $subjectGroup?1:0,
                'group_id' => $singleSubject->subject_group,
                'group_name' => $subjectGroup?$subjectGroup->name:'No Group',
                'type' => $singleSubject->subject_type,
                'list' => $singleSubject->subject_list,
            ];
        }
        // return academic class subject
        return $academicClassSectionSubjects;
    }

    // get class-section-subject student list
    public function getClsssSectionStudentList($class, $section)
    {
        // response array
        $studentList = array();
        // class section students
        $classSectionStudent = $this->studentEnrollment->where(['batch'=>$class, 'section'=>$section])->get(['id','std_id']);
        // looping for adding division into the batch name
        foreach ($classSectionStudent as $student) {
            $studentList[] = array('id' => $student->std_id, 'name' => $student->student()->first_name." ".$student->student()->middle_name." ".$student->student()->last_name);
        }
        // return student list
        return $studentList;
    }


    // get class-section-subject student list with indexing
    public function getBatchSectionStudentList($class, $section, $academicYear)
    {
        // response array
        $studentList = array();
        // class section students
        $classSectionStudent = $this->studentEnrollment->where(['batch'=>$class, 'section'=>$section, 'academic_year'=>$academicYear])->get(['id','std_id']);
        // looping for adding division into the batch name
        foreach ($classSectionStudent as $student) {
            $studentList[$student->std_id] = $student->student()->first_name." ".$student->student()->middle_name." ".$student->student()->last_name;
        }
        // return student list
        return $studentList;
    }

    ///////////////////  Parent activities ///////////////////


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

    /**
     * @param $instituteId
     * @param $campusId
     * @param $studentId
     * @param $fromDate
     * @param $toDate
     * @param $requestType
     * @param $docType
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getStdClassAttendance($instituteId, $campusId, $studentId, $fromDate, $toDate, $requestType, $docType)
    {
        $attType       = null;
        // institute attendance settings
        $attendanceInfo = $this->attendanceSetting->where(['institution_id' => $instituteId, 'campus_id' => $campusId])->first();
        // checking attendance setting
        if ($attendanceInfo->subject_wise == 1) {
            // attendanceType
            $attType = 'subject';
        } else {
            // attendanceType
            $attType = 'date';
        }

        // studentProfile
        $studentInfo = $this->studentInformation->where('id', $studentId)->first();
        // enrollment Profile
        $studentEnrollment = $studentInfo->singleEnroll();
        // academic details
        $studentBatch = $studentEnrollment->batch;
        $studentSection = $studentEnrollment->section;

        // all subject list
        $academicSubjects = $this->getClsssSectionSubjectList($studentBatch, $studentSection);

        // studentAttendanceList
        $studentAttendanceList = $this->getSingleStudentAttendanceList($studentId, $studentBatch, $studentSection, $fromDate, $toDate, $attendanceInfo);

        // institute Info
        $instituteInfo = $this->getInstituteProfile();

        if ($requestType == 'download') {
            // return $studentAttendanceList;
            // share all variables with the view
            view()->share(compact('studentInfo', 'studentAttendanceList', 'academicSubjects', 'docType', 'attType', 'instituteInfo'));

            if ($docType == 'pdf') {
                // generate pdf
                $pdf = App::make('dompdf.wrapper');
                $pdf->loadView('student::reports.attendance')->setPaper('a4', 'portrait');
                // return $pdf->stream();
                return $pdf->download('pdfview.pdf');
            } else {
                //generate excel
                Excel::create('New file', function ($excel) {
                    $excel->sheet('New sheet', function ($sheet) {
                        $sheet->loadView('student::reports.attendance');
                    });
                })->download('xls');
            }
        } else {
            return view('academics::manage-attendance.modals.attendance-parent', compact('studentInfo', 'studentAttendanceList', 'academicSubjects', 'docType', 'attType'))->with('reportAttendanceType', 'att_class');
        }
    }


    public function getStdSchoolAttendance ($instituteId, $campusId, $studentId, $fromDate, $toDate, $requestType, $docType)
    {
        // find std profile
        $studentInfo = $this->studentInformation->find($studentId);
        $studentEnrollment = $studentInfo->enroll();
        $academicLevel = $studentEnrollment->academic_level;
        $academicBatch = $studentEnrollment->batch;
        $academicYearId = $studentEnrollment->academic_year;

        $stdDeptId = $this->findStdDepartment($academicLevel, $academicBatch, null, $campusId, $instituteId);

        // date formation
        $startDate = date('Y-m-d 00:00:00', strtotime($fromDate));
        $endDate = date('Y-m-d 23:59:59', strtotime($toDate));

        // attendance list
        $studentAttendanceList = $this->attendanceUpload->where([
            'std_id'=>$studentId, 'campus'=>$campusId, 'institute'=>$instituteId,
        ])->whereBetween('entry_date_time', [$startDate, $endDate])->get();
        // reformatting std attendance list
        $studentAttendanceList = $this->reSizeStdAttendanceList($studentAttendanceList);
        // academic holiday list
        $academicHolidayList = $this->holidayController->holidayList($academicYearId, $campusId, $instituteId);
        // academic WeekOff Day list
        $academicWeeKOffDayList = $this->weekOffDayController->weekOffDayList($stdDeptId, $academicYearId, $campusId, $instituteId);
        // institute Info
        $instituteInfo = $this->getInstituteProfile();

        // now checking $requestType
        if ($requestType == 'download') {
            // share all variables with the view
            view()->share(compact('studentInfo', 'studentAttendanceList', 'instituteInfo', 'startDate', 'endDate', 'academicHolidayList', 'academicWeeKOffDayList'));
            // checking docType
            if ($docType == 'pdf') {
                // generate pdf
                $pdf = App::make('dompdf.wrapper');
                $pdf->loadView('student::reports.std-school-attendance')->setPaper('a4', 'portrait');
                // return $pdf->stream();
                return $pdf->download($studentId.'_school_attendance_report.pdf');
            } else {
                //generate excel
                Excel::create('New file', function ($excel) {
                    $excel->sheet('New sheet', function ($sheet) {
                        $sheet->loadView('student::reports.attendance');
                    });
                })->download('xls');
            }
        } else {
            return view('academics::manage-attendance.modals.attendance-parent', compact('studentInfo', 'studentAttendanceList','instituteInfo', 'startDate', 'endDate', 'academicHolidayList', 'academicWeeKOffDayList'))->with('reportAttendanceType', 'att_school');
        }
    }

    /**
     * @param $studentAttendanceList
     * @return array
     */
    public function reSizeStdAttendanceList($studentAttendanceList)
    {
        // attendance array list
        $attendanceArrayList = [];
        // attendance list checking
        if($studentAttendanceList->count()>0){
            // attendance looping
            foreach ($studentAttendanceList as $attendance) {
                // attendance date
                $attendanceDate = date('Y-m-d', strtotime($attendance->entry_date_time));
                // store attendance info into the array list
                $attendanceArrayList[$attendanceDate] = date('h:i:s', strtotime($attendance->entry_date_time));
            }
            // return attendance array list
            return $attendanceArrayList;
        }else{
            // return empty attendance array list
            return $attendanceArrayList;
        }
    }

    /**
     * @param $levelId
     * @param $batchId
     * @param $yearId
     * @param $campusId
     * @param $instituteId
     * @return array|mixed
     */
    public function findStdDepartment($levelId, $batchId, $yearId, $campusId, $instituteId)
    {
        // attendance array list
        $studentDepartmentProfile = $this->studentDepartment->where([
            'academic_level'=>$levelId,
            'academic_batch'=>$batchId,
            // 'academic_year'=>$yearId,
            'campus_id'=>$campusId,
            'institute_id'=>$instituteId
        ])->first();
        // checking
        if($studentDepartmentProfile){
            return $studentDepartmentProfile->dept_id;
        }else{
            return null;
        }
    }






    // student attendance report maker
    public function getStdSemesterAttendanceReport($studentId, $semesterId, $stdDeptId, $academicSection, $academicBatch, $academicYearId,  $campusId,  $instituteId)
    {
        // response data set
        $attendanceArrayList = array();
        // holiday counter
        $holidayArrayList = array();
        // week_off_day day counter
        $weekOffDayArrayList = array();
        // total attendance day counter
        $totalAttendanceDayCounter = 0;

        // semester profile
        $semesterProfile = $this->academicHelper->getSemester($semesterId);
        // date formation
        $startDate = date('Y-m-d 00:00:00', strtotime($semesterProfile->start_date));
        $endDate = date('Y-m-d 23:59:59', strtotime($semesterProfile->end_date));

        // from_date details
        $fromYear = date('Y',strtotime($startDate));
        $fromMonth = date('m',strtotime($startDate));
        $fromDate = date('d',strtotime($startDate));
        // to_date details
        $toYear = date('Y',strtotime($endDate));
        $toMonth = date('m',strtotime($endDate));
        $toDate = date('d',strtotime($endDate));


        // qry maker
        $qry = ['section'=>$academicSection, 'batch'=>$academicBatch, 'academic_year'=>$academicYearId, 'campus'=>$campusId, 'institute'=>$instituteId];
        // checking $studentId
        //if($studentId AND !empty($studentId)) $qry['std_id'] = $studentId;

        // checking $studentId
        if($studentId AND !empty($studentId)){
            $qry['std_id'] = $studentId;
            // student list
            $classSectionStudentList = [$studentId=>'Student Name'];

        }else{
            // student list
            $classSectionStudentList = $this->getBatchSectionStudentList($academicBatch, $academicSection, $academicYearId);
        }

        // attendance list
        $studentAttendanceList = $this->attendanceUpload->where($qry)->whereBetween('entry_date_time', [$startDate, $endDate])->orderBy('entry_date_time', 'ASC')->get(['id', 'std_id', 'entry_date_time']);
        // re arrange student attendance list
        $studentAttendanceArrayList = $this->reArrangeAttendanceList($studentAttendanceList);


        // checking student attendance list
        if(count($classSectionStudentList)>0){
            // academic holiday list
            $academicHolidayList = $this->holidayController->holidayList($academicYearId, $campusId, $instituteId);
            // academic WeekOff Day list
            $academicWeeKOffDayList = (object)$this->weekOffDayController->weekOffDayList($stdDeptId, $academicYearId, $campusId, $instituteId);

            // checking weekOffDayList
            if($academicWeeKOffDayList->status=='success'){
                // $academicWeeKOffDayList
                $academicWeeKOffDayList = $academicWeeKOffDayList->week_off_list;

                // checking date formation
                if($fromYear==$toYear AND $fromMonth<$toMonth){
                    // date looping
                    for($month=$fromMonth; $month<=$toMonth; $month++){
                        // current month date range finding
                        $monthFirstDate = date('01', strtotime($fromYear.'-'.$month.'-01'));
                        $monthLastDate = date('t', strtotime($fromYear.'-'.$month.'-01'));
                        // date range reset
                        if($fromMonth==$month){$monthFirstDate = $fromDate;}
                        if($toMonth==$month){$monthLastDate = $toDate;}
                        // current month date looping
                        for($day=$monthFirstDate; $day<=$monthLastDate; $day++){
                            // today's date formatting
                            $toDayDate = date('Y-m-d', strtotime($fromYear."-".$month."-".$day));

                            // checking date in the holiday list
                            if(array_key_exists($toDayDate, $academicHolidayList)==false){
                                // checking date in the week off day list
                                if(array_key_exists($toDayDate, $academicWeeKOffDayList)==false){
                                    // find today's attendance list
                                    if(array_key_exists($toDayDate, $studentAttendanceArrayList)){
                                        // attendance list
                                        $todayAttendanceList = $studentAttendanceArrayList[$toDayDate];
                                        // student list looping
                                        foreach ($classSectionStudentList as $stdId=>$stdDetails){

                                            // checking student attendance
                                            if(in_array($stdId, $todayAttendanceList)==true){ // today's attendance list found

                                                // checking attendance register
                                                if(array_key_exists($stdId, $attendanceArrayList)==true){
                                                    $attendanceArrayList[$stdId]['present'] +=1;
                                                }else{
                                                    $attendanceArrayList[$stdId]['present'] = 1;
                                                    $attendanceArrayList[$stdId]['absent'] = 0;
                                                }


                                            }else{ // today's attendance list not found

                                                // checking attendance register
                                                if(array_key_exists($stdId, $attendanceArrayList)==true){
                                                    $attendanceArrayList[$stdId]['absent'] +=1;
                                                }else{
                                                    $attendanceArrayList[$stdId]['present'] = 0;
                                                    $attendanceArrayList[$stdId]['absent'] = 1;
                                                }
                                            }
                                        }
                                    }else{
                                        // student list looping
                                        foreach ($classSectionStudentList as $stdId=>$stdDetails){
                                            // checking attendance register
                                            if(array_key_exists($stdId, $attendanceArrayList)==true){
                                                $attendanceArrayList[$stdId]['absent'] +=1;
                                            }else{
                                                $attendanceArrayList[$stdId]['present'] = 0;
                                                $attendanceArrayList[$stdId]['absent'] = 1;
                                            }
                                        }
                                    }
                                }else{
                                    // checking holiday array list
                                    if(in_array($toDayDate, $weekOffDayArrayList)==false){
                                        $weekOffDayArrayList[] = $toDayDate;
                                    }
                                }
                            }else{
                                // checking holiday array list
                                if(in_array($toDayDate, $holidayArrayList)==false){
                                    $holidayArrayList[] = $toDayDate;
                                }
                            }

                            // total attendance day counter
                            $totalAttendanceDayCounter += 1;
                        }
                    }
                    // return
                    return ['status'=>'success','attendance_list'=>$attendanceArrayList, 'holiday_list'=>$holidayArrayList, 'week_off_day_list'=>$weekOffDayArrayList, 'total_attendance_day'=>$totalAttendanceDayCounter];
                }else{
                    // return
                    return ['status'=>'failed', 'msg'=>'Invalid Date format'];
                }
            }else{
                // return
                return ['status'=>'failed', 'msg'=>$academicWeeKOffDayList->msg];
            }
        }else{
            // return
            return ['status'=>'failed', 'msg'=>'No Student List found'];
        }
    }



    /**
     * @param $studentAttendanceList
     * @return array
     */
    // re arrange student attendance list
    public function reArrangeAttendanceList($studentAttendanceList)
    {
        // response attendance list
        $stdAttendanceArrayList = array();
        // attendance list looping
        foreach ($studentAttendanceList as $stdAttendance) {
            // attendance date
            $attendanceDate = date('Y-m-d', strtotime($stdAttendance->entry_date_time));
            // $stdAttendanceArrayList
            $stdAttendanceArrayList[$attendanceDate][] = $stdAttendance->std_id;
        }
        // return
        return $stdAttendanceArrayList;
    }

}
