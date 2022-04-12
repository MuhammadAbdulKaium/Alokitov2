<?php

namespace Modules\Academics\Http\Controllers\ManageTimetable;

use App;
use App\Helpers\UserAccessHelper;
use Excel;
use Modules\Academics\Entities\Section;
use Redirect;
use Session;
use Validator;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Academics\Entities\AcademicsLevel;
use Modules\Academics\Entities\ClassSubject;
use Modules\Academics\Entities\SubjectTeacher;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\Academics\Entities\ClassPeriod;
use Modules\Academics\Entities\ClassPeriodCategory;
use Modules\Academics\Entities\ClassSectionPeriod;
use Modules\Academics\Entities\ManageTimetable\TimeTable;
use Modules\Student\Entities\StudentEnrollment;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Academics\Entities\ClassTeacherAssign;


class TimeTableController extends Controller
{
    private  $section;
    private $timeTable;
    private $academicsLevel;
    private $classSubject;
    private $subjectTeacher;
    private $employeeInformation;
    private $classPeriod;
    private $classSectionPeriodCategory;
    private $classPeriodCategory;
    private $academicHelper;
    private $studentEnrollment;
    private $classTeacherAssign;
    use UserAccessHelper;


    // constructor
    public function __construct(Section $section, TimeTable $timeTable, ClassTeacherAssign $classTeacherAssign, AcademicsLevel $academicsLevel, ClassSubject $classSubject, SubjectTeacher $subjectTeacher, ClassPeriod $classPeriod, ClassSectionPeriod $classSectionPeriodCategory, ClassPeriodCategory $classPeriodCategory, AcademicHelper $academicHelper, EmployeeInformation $employeeInformation, StudentEnrollment $studentEnrollment)
    {
        $this->section = $section;
        $this->timeTable = $timeTable;
        $this->classSubject = $classSubject;
        $this->employeeInformation = $employeeInformation;
        $this->subjectTeacher = $subjectTeacher;
        $this->academicsLevel = $academicsLevel;
        $this->classPeriod = $classPeriod;
        $this->classSectionPeriodCategory = $classSectionPeriodCategory;
        $this->classPeriodCategory = $classPeriodCategory;
        $this->academicHelper = $academicHelper;
        $this->studentEnrollment = $studentEnrollment;
        $this->classTeacherAssign = $classTeacherAssign;
    }


    public function index(Request $request, $tabId)
    {
        $pageAccessData = self::linkAccess($request);
        // all academics levels
        $allAcademicsLevel = $this->academicHelper->getAllAcademicLevel();
        // all related class period categories
        $classPeriodCategories = $this->getAcademicClassPeriodCategory();

        if ($tabId == 'manage') {
            // return view with academicsLevels
            return view('academics::manage-timetable.manage', compact('allAcademicsLevel', 'pageAccessData'))->with('page', 'manage');
        } elseif ($tabId == 'timetable') {
            // return view with academicsLevels
            return view('academics::manage-timetable.timetable', compact('allAcademicsLevel', 'pageAccessData'))->with('page', 'timetable');
        } elseif ($tabId == 'settings') {
            // return view with academicsLevels
            return view('academics::manage-timetable.settings', compact('classPeriodCategories', 'pageAccessData'))->with('page', 'settings');
        } elseif ($tabId == 'routine') {
            // return view with academicsLevels
            return view('academics::manage-timetable.routine', compact('allAcademicsLevel', 'pageAccessData'))->with('page', 'routine');
        } elseif ($tabId == 'class-teacher-assign') {


            $empList = EmployeeInformation::with('singleUser', 'singleDesignation', 'singleDepartment')->where('institute_id', institution_id())->where('campus_id', campus_id())->where('status', 1)->get()->keyBy('id');

            $sections = $this->getAll();
            // class teacehr assign
            $classTeacherList = $this->classTeacherAssign->where('institute_id', institution_id())->where('campus_id', campus_id())->orderBy('batch_id', 'asc')->get();
            $teacherArray = $classTeacherList->groupBy('section_id');

            return view('academics::manage-timetable.class-teacher-assign', compact('teacherArray', 'empList', 'classTeacherList', 'sections', 'pageAccessData'))->with('page', 'class-teacher-assign');
        } else {
            return "Page not found";
        }
    }
    public function getAll()
    {
        return $sectionList = $this->section->with('divisions')->get();
    }

    // class teacher assign
    public function  classTeacherAssignModal()
    {
        $empList = EmployeeInformation::where('institute_id', institution_id())->where('campus_id', campus_id())->where('status', 1)->get();
        $allAcademicsLevel = $this->academicHelper->getAllAcademicLevel();
        return view('academics::manage-timetable.modals.class-teacher-assign-modal', compact('empList', 'allAcademicsLevel'));
    }


    // class teacher assign
    public function classTeacherAssign(Request $request)
    {
        //return $request;
        $classTeachers = $this->classTeacherAssign
            ->where('institute_id', institution_id())
            ->where('campus_id', campus_id())
            ->where('batch_id', $request->batch)
            ->where('section_id', $request->section)
            ->get()->pluck('teacher_id');
        //return $classTeachers;
        $teacherArray = (array)$request->teacherID;
        //return var_dump($teacherArray);
        for ($i = 0; $i < sizeof($classTeachers); $i++) {
            if (in_array($classTeachers[$i], $teacherArray)) {
                unset($teacherArray[$i]);
            } else {

                $removeTeacher = $this->classTeacherAssign->where('institute_id', institution_id())
                    ->where('campus_id', campus_id())
                    ->where('teacher_id', $classTeachers[$i])
                    ->where('batch_id', $request->batch)
                    ->where('section_id', $request->section)
                    ->first();

                ClassTeacherAssign::find($removeTeacher->id)->delete();
                //unset($classTeachers[$i]);

            }
        };


        foreach ($teacherArray as $key => $teacher) {

            $classTeacherAssignOBj = new $this->classTeacherAssign;
            $classTeacherAssignOBj->institute_id = institution_id();
            $classTeacherAssignOBj->campus_id = campus_id();
            $classTeacherAssignOBj->teacher_id = $teacher;
            $classTeacherAssignOBj->batch_id = $request->batch;
            $classTeacherAssignOBj->section_id = $request->section;
            $classTeacherAssignOBj->save();
        };
        $classTeachers = $this->classTeacherAssign
            ->where('institute_id', institution_id())
            ->where('campus_id', campus_id())
            ->where('batch_id', $request->batch)
            ->where('section_id', $request->section)
            ->where('deleted_at', null)
            ->get()->pluck('teacher_id');
        //return $classTeachers;
        //return $request->teacherID;
        //var_dump($classTeachers);


        Session::flash('success', 'Class Teacher  Successfully Assigned');

        return redirect()->back();
    }

    // delete class teacher
    public function classTeacherDelete($id)
    {
        $classTeacherProfile = $this->classTeacherAssign->find($id);
        if (!empty($classTeacherProfile)) {
            $classTeacherProfile->delete();
            Session::flash('success', 'Class Teacher  Successfully Assigned');
        } else {
            Session::flash('warning', 'Class Teacher  Not Found');
        }
        return redirect()->back();
    }


    ////////////////////////////////////////////////////////// Timetable //////////////////////////////////////////////////////////

    //    public function findClassSectionTimetable(Request $request)
    //    {
    //        $batch = $request->input('class_id');
    //        $section = $request->input('section_id');
    //        $shift = $request->input('shift_id');
    //
    //        // all timetables
    //        $allTimetables = $this->timeTable->where(['batch'=>$batch,'section'=>$section,'shift'=>$shift])->get();
    //        // all class periods
    //        $allClassPeriods = $this->getAcademicClassPeriods();
    //        // all class subject
    //        $allClassSubjects = $this->classSubject->where(['class_id'=>$batch,'section_id'=>$section])->get();
    //        // return view
    //        return view('academics::manage-timetable.modals.dashboard-class-section-timetable', compact('allClassPeriods', 'allClassSubjects', 'allTimetables'));
    //    }

    public function findTeacher($csId)
    {
        // all class subject teachers
        $subjectTeachers = $this->subjectTeacher->where(['class_subject_id' => $csId, 'is_active' => 1])->get();
        // $response data
        $responseArray = array();
        // looping
        foreach ($subjectTeachers as $teacher) {
            $teacherProfile = $teacher->employee();
            // make response array
            $responseArray[] = ['cs_teacher_id' => $teacher->id, 'id' => $teacherProfile->id, 'name' => $teacherProfile->first_name . " " . $teacherProfile->middle_name . " " . $teacherProfile->last_name];
        }

        return $responseArray;
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    public function storeTimetable(Request $request)
    {

        $day = $request->input('day');
        $period = $request->input('period');
        $shift = $request->input('shift');
        $room = $request->input('room');
        $batch = $request->input('batch');
        $section = $request->input('section');
        $subject = $request->input('subject');
        $teacher = $request->input('teacher');

        // return $this->checkTeacherAvailablity($day, $teacher, $shift, $period);

        // timetable profile id from the view
        $timetableId =  $request->input('timetable');

        // checking
        if ($timetableId > 0) {
            // find timetable profile
            $timetableProfile = $this->timeTable->findOrFail($timetableId);
        } else {
            // new object of timetable profile
            $timetableProfile = new $this->timeTable();
        }
        // checking timetable
        if ($timetableProfile) {
            // input timetable details
            $timetableProfile->day = $request->input('day');
            $timetableProfile->period = $request->input('period');
            $timetableProfile->shift = $request->input('shift');
            $timetableProfile->room = $request->input('room');
            $timetableProfile->batch = $request->input('batch');
            $timetableProfile->section = $request->input('section');
            $timetableProfile->subject = $request->input('subject');
            $timetableProfile->teacher = $request->input('teacher');
            $timetableProfile->room = 0;
            // $timetableProfile->academic_year = $this->academicHelper->getAcademicYear();
            $timetableProfile->campus = $this->academicHelper->getCampus();
            $timetableProfile->institute = $this->academicHelper->getInstitute();;
            // save timetable
            $timetableProfileSubmitted = $timetableProfile->save();
            // checking
            if ($timetableProfileSubmitted) {
                // success msg
                return array('status' => 'success', 'timetable_id' => $timetableProfile->id);
            } else {
                return array('status' => 'failed');
            }
        } else {
            return array('status' => 'failed');
        }
    }

    //    public function checkTeacherAvailablity($day, $teacher, $shift, $period)
    //    {
    //        // my period profile
    //        $myPeriodProfile = $this->classPeriod->find($period);
    //        $myPeriodStartHour = $myPeriodProfile->period_start_hour;
    //        $myPeriodStartMin = $myPeriodProfile->period_start_min;
    //        $myPeriodStartMin = $myPeriodProfile->period_start_min;
    //        $myPeriodStartMeridiem = $myPeriodProfile->period_start_meridiem;
    //        $myPeriodEndHour = $myPeriodProfile->period_end_hour;
    //        $myPeriodEndMin = $myPeriodProfile->period_end_min;
    //        $myPeriodEndMeridiem= $myPeriodProfile->period_end_meridiem;
    //
    //
    //        // teacher-day-shift timetables
    //        $teacherTimetables = $this->timeTable->where(['day' => $day, 'teacher' => $teacher, 'shift' => $shift])->get();
    //
    //        if($teacherTimetables->count()>0){
    //            $teacherChecker = array();
    //
    //            // looping
    //            foreach ($teacherTimetables as $timetable) {
    //                $periodProfile = $timetable->classPeriod();
    //                $periodStartHour = $periodProfile->period_start_hour;
    //                $periodStartMin = $periodProfile->period_start_min;
    //                $periodStartMeridiem = $periodProfile->period_start_meridiem;
    //                $periodEndHour = $periodProfile->period_end_hour;
    //                $periodEndMin = $periodProfile->period_end_min;
    //                $periodEndMeridiem= $periodProfile->period_end_meridiem;
    //
    //                if ($periodStartHour == $myPeriodStartHour && $periodStartMin == $myPeriodStartMin
    //                    && $periodStartMeridiem == $myPeriodStartMeridiem && $periodEndMin == $myPeriodEndMin) {
    //                    $teacherChecker[]= array(
    //                        'msg' => 'This teacher already booked in this class period',
    //                        'myTime' => $myPeriodStartHour . ":" . $myPeriodStartMin,
    //                        'time' => $periodStartHour . ":" . $periodStartMin
    //                    );
    //                }elseif ($periodStartHour <= $myPeriodStartHour && $periodStartMin <= $myPeriodStartMin
    //                    && $periodEndHour <= $myPeriodEndHour && $periodEndMin >= $myPeriodEndMin){
    //                    $teacherChecker[]= array(
    //                        'msg' => 'This teacher already booked in this class period',
    //                        'myTime' => $myPeriodStartHour . ":" . $myPeriodStartMin,
    //                        'time' => $periodStartHour . ":" . $periodStartMin
    //                    );
    //                }
    //                else{
    //                    $teacherChecker[]= array(
    //                        'msg' => 'This teacher is not booked in this class period',
    //                        'myTime' => $myPeriodStartHour . ":" . $myPeriodStartMin,
    //                        'time' => $periodStartHour . ":" . $periodStartMin
    //                    );
    //                }
    //            }
    //            return array('status' => 'test', 'result' =>$teacherChecker);
    //        }else{
    //            return array('status' => 'test', 'result' =>"teacher is available");
    //        }
    //    }

    ///////////////////// Manage Timetable  /////////////////////
    // get class section timetable
    public function getClassSectionTimeTable(Request $request)
    {
        $level = $request->input('level');
        $batch = $request->input('batch');
        $section = $request->input('section');
        $shift = $request->input('shift');
        $requestType = $request->input('request_type', 'timetable');
        // return type
        $returnType = $request->input('return_type', 'view');

        // checking return type
        if ($returnType == "json") {
            // $academicYear = $request->input('year');
            $campus = $request->input('campus');
            $institute = $request->input('institute');
        } else {
            // $academicYear = $this->academicHelper->getAcademicYear();
            $campus = $this->academicHelper->getCampus();
            $institute = $this->academicHelper->getInstitute();
        }

        // all timetables
        $allTimetables = $this->timeTable->where([
            'batch' => $batch,
            'section' => $section,
            'shift' => $shift,
            //'academic_year'=>$academicYear,
            'campus' => $campus,
            'institute' => $institute
        ])->get();

        // batch section assigned period id
        $batchSectionPeriodId = $this->getBatchSectionPeriodCategoryId($institute, $campus, null, $level, $batch, $section, $shift);
        // $batchSectionPeriodId = $this->getBatchSectionPeriodCategoryId($institute, $campus, $academicYear, $level, $batch, $section, $shift);
        // all class periods
        $allClassPeriods = $this->getAcademicClassPeriods($batchSectionPeriodId, $institute, $campus, null);
        // $allClassPeriods = $this->getAcademicClassPeriods($batchSectionPeriodId, $institute, $campus, $academicYear);
        // all class subject
        $allClassSubjects = $this->classSubject->where(['class_id' => $batch, 'section_id' => $section])->get();

        // checking
        if ($returnType == "json") {
            // return with variables for api request response
            return ['period_id' => $batchSectionPeriodId, 'periods' => $allClassPeriods, 'subjects' => $allClassSubjects, 'timetable' => $allTimetables];
        } else {
            // check request type
            if ($requestType == 'dashboard') {
                return view('academics::manage-timetable.modals.dashboard-class-section-timetable', compact('allClassPeriods', 'allClassSubjects', 'allTimetables', 'batchSectionPeriodId'));
            } else {
                // return view
                return view('academics::manage-timetable.modals.class-section-timetable', compact('allClassPeriods', 'allClassSubjects', 'allTimetables', 'batchSectionPeriodId'));
            }
        }
    }

    // get class section day timetable
    public function classSectionDayTimetable(Request $request)
    {
        $level = $request->input('level');
        $batch = $request->input('batch');
        $section = $request->input('section');
        $shift = $request->input('shift');
        $requestType = $request->input('request_type', 'timetable');
        // $academicYear = $this->academicHelper->getAcademicYear();
        $campus = $this->academicHelper->getCampus();
        $institute = $this->academicHelper->getInstitute();

        // all timetables
        $allTimetables = $this->timeTable->where([
            'batch' => $batch,
            'section' => $section,
            'shift' => $shift,
            //'academic_year'=>$academicYear,
            'campus' => $campus,
            'institute' => $institute
        ])->get();

        // batch section assigned period id
        $batchSectionPeriodId = $this->getBatchSectionPeriodCategoryId($institute, $campus, null, $level, $batch, $section, $shift);
        // $batchSectionPeriodId = $this->getBatchSectionPeriodCategoryId($institute, $campus, $academicYear, $level, $batch, $section, $shift);
        // all class periods
        $allClassPeriods = $this->getAcademicClassPeriods($batchSectionPeriodId, $institute, $campus, null);
        // $allClassPeriods = $this->getAcademicClassPeriods($batchSectionPeriodId, $institute, $campus, $academicYear);
        // all class subject
        $allClassSubjects = $this->classSubject->where(['class_id' => $batch, 'section_id' => $section])->get();

        // looping for class subject teacher list
        $classTeacherList = array();
        // looping
        foreach ($allClassSubjects as $classSubject) {
            // subject teacher list
            $subjectTeacherList = $classSubject->teacher();
            // looping
            foreach ($subjectTeacherList as $classTeacher) {
                // teacher profile
                $teacher = $classTeacher->employee();
                // add teacher to the list
                $classTeacherList[] = (object)[
                    'id' => $teacher->id,
                    'name' => $teacher->first_name . " " . $teacher->middle_name . " " . $teacher->last_name,
                    'alias' => $teacher->alias
                ];
            }
        }

        $batchProfile = $this->academicHelper->findBatch($batch);
        $sectionProfile = $this->academicHelper->findSection($section);

        // institute info
        $instituteInfo = $this->academicHelper->getInstituteProfile();
        // compact variables
        $compactedVariables = compact('allClassPeriods', 'allClassSubjects', 'allTimetables', 'batchSectionPeriodId', 'classTeacherList', 'batchProfile', 'sectionProfile', 'instituteInfo');

        // checking page request types
        if ($requestType == 'download') {
            // share variables with view
            view()->share($compactedVariables);
            // generate pdf
            $pdf = App::make('dompdf.wrapper');
            // load view
            $pdf->loadView('academics::manage-timetable.reports.report-class-section-teacher-day-timetable')->setPaper('a4', 'portrait');
            // return with pdf file
            return $pdf->download('teacher-class-section-day-routine.pdf');
        } else {
            // return view with variable
            return view('academics::manage-timetable.modals.routine', $compactedVariables);
        }
    }


    // get class section timetable
    public function classSectionTimeTableManager(Request $request)
    {
        // subject list
        $subjectList = $this->classSubject->where([
            'class_id' => $request->input('batch'),
            'section_id' => $request->input('section'),
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get(['id', 'subject_id']);
        // return view
        return view('academics::manage-timetable.modals.manage', compact('subjectList'));
    }

    // get class teacher timetable
    public function getTeacherTimeTable($subTeacherId)
    {
        // academic details
        // $academicYear = $this->academicHelper->getAcademicYear();
        $campus = $this->academicHelper->getCampus();
        $institute = $this->academicHelper->getInstitute();

        // subjectTeacherProfile
        $subjectTeacherProfile = $this->subjectTeacher->find($subTeacherId);
        // teacherProfile
        $teacherProfile = $subjectTeacherProfile->employee();
        // classSubjectProfile
        $classSubjectProfile = $subjectTeacherProfile->classSubject();

        $level = $classSubjectProfile->batch()->academicsLevel()->id;
        $batch = $classSubjectProfile->class_id;
        $section = $classSubjectProfile->section_id;
        // all timetables
        $teacherTimeTables = $this->timeTable->where([
            'batch' => $batch,
            'section' => $section,
            'subject' => $classSubjectProfile->id,
            'teacher' => $teacherProfile->id,
            // 'academic_year'=>$academicYear,
            'campus' => $campus,
            'institute' => $institute
        ])->get();


        // batch section assigned period id
        $batchSectionPeriodId = $this->getBatchSectionPeriodCategoryId($institute, $campus, null, $level, $batch, $section, 0);
        // $batchSectionPeriodId = $this->getBatchSectionPeriodCategoryId($institute, $campus, $academicYear, $level, $batch, $section, 0);
        // all class periods
        $allClassPeriods = $this->getAcademicClassPeriods($batchSectionPeriodId, $institute, $campus, null);
        // $allClassPeriods = $this->getAcademicClassPeriods($batchSectionPeriodId, $institute, $campus, $academicYear);
        // return view
        return view('academics::manage-timetable.modals.teacher-timetable', compact('teacherTimeTables', 'allClassPeriods', 'classSubjectProfile', 'teacherProfile', 'batchSectionPeriodId'));
    }


    // get class teacher timetable view
    public function getTeacherTimeTableView($teacherId)
    {
        // academic details
        // $academicYear = $this->academicHelper->getAcademicYear();
        $campus = $this->academicHelper->getCampus();
        $institute = $this->academicHelper->getInstitute();

        // teacherProfile
        $teacherProfile = $this->employeeInformation->where(['id' => $teacherId, 'campus_id' => $campus, 'institute_id' => $institute])->first();
        // all timetables
        $teacherTimeTables = $this->timeTable->where([
            'teacher' => $teacherId,
            // 'academic_year'=>$academicYear,
            'campus' => $campus,
            'institute' => $institute
        ])->get();

        // return view
        return view('employee::pages.modals.employee-timetable', compact('teacherTimeTables', 'teacherProfile'));
    }

    // get class teacher timetable report
    public function getTeacherTimeTableReport($teacherId)
    {
        // academic details
        // $academicYear = $this->academicHelper->getAcademicYear();
        $campus = $this->academicHelper->getCampus();
        $institute = $this->academicHelper->getInstitute();

        // teacherProfile
        //        $teacherProfile = $this->subjectTeacher->where('employee_id', $teacherId)->first()->employee();
        // all timetables
        $teacherTimeTables = $this->timeTable->where([
            'teacher' => $teacherId,
            //'academic_year'=>$academicYear,
            'campus' => $campus,
            'institute' => $institute
        ])->get();

        // instituteInfo
        $instituteInfo = $this->getInstituteProfile();
        // share all variables with the view
        view()->share(compact('teacherTimeTables', 'instituteInfo'));
        // generate pdf
        $pdf = App::make('dompdf.wrapper');
        // load view
        $pdf->loadView('academics::manage-timetable.reports.report-teacher-timetable')->setPaper('a4', 'portrait');
        return $pdf->stream();
        //        return $pdf->download('teacher-class-routine.pdf');
    }



    // get student timetable
    public function getStudentTimeTable($stdId)
    {
        // std enrollment
        $stdEnrollProfile = $this->studentEnrollment->where('std_id', $stdId)->first();

        $level = $stdEnrollProfile->academic_level;
        $batch = $stdEnrollProfile->batch;
        $section = $stdEnrollProfile->section;
        // $academicYear = $this->academicHelper->getAcademicYear();
        $campus = $this->academicHelper->getCampus();
        $institute = $this->academicHelper->getInstitute();

        // std timetables
        $stdTimeTables = $this->timeTable->where([
            'batch' => $batch,
            'section' => $section,
            // 'academic_year'=>$academicYear,
            'campus' => $campus,
            'institute' => $institute
        ])->get();

        // batch section assigned period id
        $batchSectionPeriodId = $this->getBatchSectionPeriodCategoryId($institute, $campus, null, $level, $batch, $section, 0);
        // $batchSectionPeriodId = $this->getBatchSectionPeriodCategoryId($institute, $campus, $academicYear, $level, $batch, $section, 0);
        // std class periods
        $allClassPeriods = $this->getAcademicClassPeriods($batchSectionPeriodId, $institute, $campus, null);
        // $allClassPeriods = $this->getAcademicClassPeriods($batchSectionPeriodId, $institute, $campus, $academicYear);
        // return view
        return view('academics::manage-timetable.modals.student-timetable', compact('stdTimeTables', 'allClassPeriods', 'batchSectionPeriodId'))->with('stdId', $stdId);
    }

    // get student timetable report
    public function getStudentTimeTableReport($stdId)
    {
        // std enrollment
        $stdEnrollProfile = $this->studentEnrollment->where('std_id', $stdId)->first();
        // std timetables
        $level = $stdEnrollProfile->academic_level;
        $batch = $stdEnrollProfile->batch;
        $section = $stdEnrollProfile->section;
        $shift = 'yet not assigned'; // std shifting is not workable till now
        // $academicYear = $this->academicHelper->getAcademicYear();
        $campus = $this->academicHelper->getCampus();
        $institute = $this->academicHelper->getInstitute();

        // std timetables
        $stdTimeTables = $this->timeTable->where([
            'batch' => $batch,
            'section' => $section,
            // 'academic_year'=>$academicYear,
            'campus' => $campus,
            'institute' => $institute
        ])->get();

        // batch section assigned period id
        $batchSectionPeriodId = $this->getBatchSectionPeriodCategoryId($institute, $campus, null, $level, $batch, $section, 0);
        // $batchSectionPeriodId = $this->getBatchSectionPeriodCategoryId($institute, $campus, $academicYear, $level, $batch, $section, 0);
        // std class periods
        $allClassPeriods = $this->getAcademicClassPeriods($batchSectionPeriodId, $institute, $campus, null);
        // $allClassPeriods = $this->getAcademicClassPeriods($batchSectionPeriodId, $institute, $campus, $academicYear);
        // instituteInfo
        $instituteInfo = $this->getInstituteProfile();
        // share all variables with the view
        view()->share(compact('stdTimeTables', 'allClassPeriods', 'instituteInfo', 'batchSectionPeriodId'));
        // generate pdf
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('academics::manage-timetable.reports.report-student-timetable')->setPaper('a4', 'portrait');
        // return $pdf->stream();
        return $pdf->download('class-routine.pdf');
    }

    //////////////////////////////////////////////////////////  Class Category Profile  //////////////////////////////////////////////////////////
    // get class teacher timetable
    public function createPeriod()
    {
        $classPeriodCategories = $this->getAcademicClassPeriodCategory();
        // return view
        return view('academics::manage-timetable.modals.period', compact('classPeriodCategories'))->with('periodProfile', null);
    }
    // get class teacher timetable
    public function storePeriod(Request $request)
    {

        //        return $request->all();

        // validator
        $validator = Validator::make($request->all(), [
            'period_name' => 'required',
            'period_category' => 'required',
            'period_shift' => 'required',
            'period_start_hour' => 'required',
            'period_start_min' => 'required',
            'period_start_meridiem' => 'required',
            'period_end_hour' => 'required',
            'period_end_min' => 'required',
            'period_end_meridiem' => 'required',
            'action_type' => 'required',
        ]);

        // checking validator
        if ($validator->passes()) {
            // checking input action type
            if ($request->input('action_type') == "create") {
                // create class period
                $classPeriodProfile = new $this->classPeriod();
            } else {
                // find class period
                $classPeriodProfile = $this->classPeriod->findOrFail($request->input('period_id'));
            }

            // checking classPeriodProfile existence
            if ($classPeriodProfile) {
                // store profile details
                $classPeriodProfile->campus = $this->getInstituteCampusId();
                $classPeriodProfile->institute = $this->getInstituteId();
                // $classPeriodProfile->academic_year = $this->getAcademicYearId();
                $classPeriodProfile->period_name = $request->input('period_name');
                $classPeriodProfile->period_category = $request->input('period_category');
                $classPeriodProfile->period_shift = $request->input('period_shift');
                $classPeriodProfile->period_start_hour = $request->input('period_start_hour');
                $classPeriodProfile->period_start_min = $request->input('period_start_min');
                $classPeriodProfile->period_start_meridiem = $request->input('period_start_meridiem');
                $classPeriodProfile->period_end_hour = $request->input('period_end_hour');
                $classPeriodProfile->period_end_min = $request->input('period_end_min');
                $classPeriodProfile->period_end_meridiem = $request->input('period_end_meridiem');
                $classPeriodProfile->is_break = $request->input('break', 0);
                // save classPeriodProfile
                $classPeriodSubmitted = $classPeriodProfile->save();

                // checking submission
                if ($classPeriodSubmitted) {
                    // success message
                    Session::flash('success', 'Class Period Submitted Successfully');
                    return redirect()->back();
                } else {
                    // warning message
                    Session::flash('warning', 'Unable to perform the action');
                    return redirect()->back();
                }
            } else {
                // warning message
                Session::flash('warning', 'Unable to create or update ClassPeriodProfile');
                return redirect()->back();
            }
        } else {
            // warning message
            Session::flash('warning', 'Invalid information');
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }


    // store class teacher timetable
    public function editPeriod($periodId)
    {
        // classPeriodCategories
        $classPeriodCategories = $this->getAcademicClassPeriodCategory();
        // category profile
        $periodProfile = $this->classPeriod->findOrFail($periodId);
        // return view
        return view('academics::manage-timetable.modals.period', compact('periodProfile', 'classPeriodCategories'));
    }

    // store class teacher timetable
    public function deletePeriod($catId)
    {
        // category profile
        $classPeriodProfile = $this->classPeriod->findOrFail($catId);
        // deleting classPeriodProfile
        $classPeriodProfileDeleted = $classPeriodProfile->delete();
        // checking
        if ($classPeriodProfileDeleted) {
            // success message
            Session::flash('success', 'Class Period Deleted Successfully');
            return redirect()->back();
        } else {
            // warning message
            Session::flash('warning', 'Unable to perform the action');
            return redirect()->back();
        }
    }



    //////////////////////////////////////////////////////////  Class Category Profile  //////////////////////////////////////////////////////////
    // get class teacher timetable
    public function createPeriodCategory()
    {
        // return view
        return view('academics::manage-timetable.modals.period-category')->with('categoryProfile', null);
    }

    // store class teacher timetable
    public function storePeriodCategory(Request $request)
    {
        // validator
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
        ]);

        // checking validator
        if ($validator->passes()) {
            // checking input action type
            if ($request->input('type') == "create") {
                // create class period category
                $categoryProfile = new $this->classPeriodCategory();
            } else {
                // update class period category
                $categoryProfile = $this->classPeriodCategory->findOrFail($request->input('cat_id'));
            }

            // checking classPeriodProfile existence
            if ($categoryProfile) {
                // store profile details
                $categoryProfile->campus = $this->getInstituteCampusId();
                $categoryProfile->institute = $this->getInstituteId();
                // $categoryProfile->academic_year = $this->getAcademicYearId();
                $categoryProfile->name = $request->input('name');
                // save category profile
                $classPeriodCategorySubmitted = $categoryProfile->save();

                // checking submission
                if ($classPeriodCategorySubmitted) {
                    // success message
                    Session::flash('success', 'Class Period Category Created Successfully');
                    return redirect()->back();
                } else {
                    // warning message
                    Session::flash('warning', 'Unable to perform the action');
                    return redirect()->back();
                }
            } else {
                // warning message
                Session::flash('warning', 'Unable to create or find ClassPeriodCategoryProfile');
                return redirect()->back();
            }
        } else {
            // warning message
            Session::flash('warning', 'Invalid information');
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    // store class teacher timetable
    public function editPeriodCategory($catId)
    {
        // category profile
        $categoryProfile = $this->classPeriodCategory->find($catId);
        // return view
        return view('academics::manage-timetable.modals.period-category', compact('categoryProfile'));
    }

    // store class teacher timetable
    public function deletePeriodCategory($catId)
    {
        // category profile
        $categoryProfile = $this->classPeriodCategory->find($catId);
        // deleting categoryProfile
        $categoryProfileDeleted = $categoryProfile->delete();
        // checking
        if ($categoryProfileDeleted) {
            // success message
            Session::flash('success', 'Class Period Category Deleted Successfully');
            return redirect()->back();
        } else {
            // warning message
            Session::flash('warning', 'Unable to perform the action');
            return redirect()->back();
        }
    }


    // assign period category to a class section
    public function periodCategoryDetails($id)
    {
        // period category id
        $periodCategoryProfile = $this->classPeriodCategory->find($id);
        // all academic levels
        // return view with variable
        return view('academics::manage-timetable.modals.period-category-details', compact('periodCategoryProfile'));
    }
    // assign period category to a class section
    public function assignPeriodCategory()
    {
        // all academic levels
        $allAcademicsLevel = $this->academicHelper->getAllAcademicLevel();
        // return view with variable
        return view('academics::manage-timetable.modals.period-category-assign', compact('allAcademicsLevel'));
    }

    public function manageAssignPeriodCategory(Request $request)
    {
        // request details
        //$academicYear = $this->academicHelper->getAcademicYear();
        $institute = $this->academicHelper->getInstitute();
        $campus = $this->academicHelper->getCampus();
        $academicLevel = $request->input('academic_level');
        $batch = $request->input('batch');
        $section = $request->input('section');
        $shift = $request->input('shift');
        // checking
        if ($request->input('request_type') == 'check') {
            $classSectionPeriodCategory = $this->classSectionPeriodCategory->where([
                //'academic_year'=>$academicYear,
                'institute_id' => $institute,
                'campus_id' => $campus,
                'academic_level' => $academicLevel,
                'batch' => $batch,
                'section' => $section,
                'cs_shift' => $shift,
            ])->first();

            // category list
            $categoryList = $this->classPeriodCategory->where(['institute' => $institute, 'campus' => $campus])->get();
            // $categoryList = $this->classPeriodCategory->where([ 'institute'=>$institute, 'campus'=>$campus, 'academic_year'=>$academicYear])->get();
            // checking
            if ($classSectionPeriodCategory) {
                return [
                    'status' => 'success',
                    'msg' => 'class period is assigned',
                    'csp_id' => $classSectionPeriodCategory->cs_period_category,
                    'cat_id' => $classSectionPeriodCategory->id,
                    'cat_name' => $classSectionPeriodCategory->periodCategory()->name,
                    'cat_list' => $categoryList,
                ];
            } else {
                return [
                    'status' => 'failed',
                    'msg' => 'class period is not assigned',
                    'cat_id' => 0,
                    'cat_list' => $categoryList,
                ];
            }
        } else {
            // period_category_id
            $periodCategoryId = $request->input('period_category_id');
            $csPeriodId = $request->input('cs_period_id');
            // checking
            if ($csPeriodId > 0) {
                // class section period assign profile
                $classSectionPeriodProfile = $this->classSectionPeriodCategory->find($csPeriodId);
            } else {
                // class section period assign profile
                $classSectionPeriodProfile = new $this->classSectionPeriodCategory();
            }
            // input details
            $classSectionPeriodProfile->cs_period_category = $periodCategoryId;
            // $classSectionPeriodProfile->academic_year = $academicYear;
            $classSectionPeriodProfile->academic_level = $academicLevel;
            $classSectionPeriodProfile->batch = $batch;
            $classSectionPeriodProfile->section = $section;
            $classSectionPeriodProfile->cs_shift = $shift;
            $classSectionPeriodProfile->institute_id = $institute;
            $classSectionPeriodProfile->campus_id = $campus;
            $classSectionPeriodProfile->save();

            // checking
            if ($classSectionPeriodProfile) {
                //$year = $this->academicHelper->findYear($academicYear);
                return ['status' => 'success', 'msg' => 'class period is assigned', 'cat_id' => $classSectionPeriodProfile->id];
            } else {
                return ['status' => 'failed', 'msg' => 'unable to assign class period category'];
            }
        }
    }


    public function removeAssignedPeriodCategory($id)
    {
        $classSectionPeriodCategory = $this->classSectionPeriodCategory->find($id);
        $classSectionPeriodCategoryRemoved = $classSectionPeriodCategory->delete();
        // checking
        if ($classSectionPeriodCategoryRemoved) {
            return ['status' => 'success', 'msg' => 'class section period removed'];
        } else {
            return ['status' => 'failed', 'msg' => 'Unable to remove class section period'];
        }
    }

    //////////////////////////////////////////////////////////  Local Helper function  //////////////////////////////////////////////////////////


    public function getAcademicClassPeriods($categoryId, $institute, $campus, $academicYear)
    {
        // periods
        return $this->classPeriod->where([
            'institute' => $institute,
            'campus' => $campus,
            //'academic_year' => $academicYear,
            'period_category' => $categoryId
        ])->get();
    }

    public function getAcademicClassPeriodCategory()
    {
        // period categories
        return $this->classPeriodCategory->where([
            'institute' => $this->getInstituteId(),
            'campus' => $this->getInstituteCampusId(),
            //            'academic_year' => $this->getAcademicYearId(),
        ])->get();
    }

    // get class section assigned period category id
    public function getBatchSectionPeriodCategoryId($institute, $campus, $academicYear, $level, $batch, $section, $shift)
    {
        $batchSectionPeriodProfile =  $this->classSectionPeriodCategory->where([
            // 'academic_year' => $academicYear,
            'institute_id' => $institute,
            'campus_id' => $campus,
            'academic_level' => $level,
            'batch' => $batch,
            'section' => $section,
            'cs_shift' => $shift,
        ])->first();
        // return variable
        return $batchSectionPeriodProfile ? $batchSectionPeriodProfile->cs_period_category : 0;
    }

    public function getAcademicYearId()
    {
        return $this->academicHelper->getAcademicYear();
    }
    public function getInstituteProfile()
    {
        return $this->academicHelper->getInstituteProfile();
    }
    public function getInstituteId()
    {
        return $this->academicHelper->getInstitute();
    }
    public function getInstituteCampusId()
    {
        return $this->academicHelper->getCampus();
    }

    public function getAcademicScaleId()
    {
        return $this->academicHelper->getGradingScale();
    }




    //    /**
    //     * @param $day
    //     * @param $teacher
    //     * @param $shift
    //     * @param $period
    //     * @return array
    //     */
    //    public function checkTeacherAvailablity($day, $teacher, $shift, $period)
    //    {
    //        // my period profile
    //        $myPeriodProfile = $this->classPeriod->find($period);
    //        $myPeriodStartHour = $myPeriodProfile->period_start_hour;
    //        $myPeriodStartMin = $myPeriodProfile->period_start_min;
    //
    //        // teacher-day-shift timetables
    //        $teacherTimetables = $this->timeTable->where(['day' => $day, 'teacher' => $teacher, 'shift' => $shift])->get();
    //
    //        // looping
    //        foreach ($teacherTimetables as $timetable) {
    //            $periodProfile = $timetable->classPeriod();
    //            $periodStartHour = $periodProfile->period_start_hour;
    //            $periodStartMin = $periodProfile->period_start_min;
    //
    //            if ($periodStartHour >= $myPeriodStartHour && $periodStartMin >= $myPeriodStartMin) {
    //                return array('status' => 'test', 'result' => array(
    //                    'msg' => 'This teacher already book in this class period',
    //                    'myTime' => $myPeriodStartHour . " " . $myPeriodStartMin,
    //                    'time' => $periodStartHour . " " . $periodStartMin
    //                ));
    //            } else {
    //                return array('status' => 'test', 'result' => "This teacher is not booked in this class period");
    //            }
    //
    //        }
    //    }

}
