<?php

namespace Modules\API\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Support\Facades\Log;
use Modules\Student\Http\Controllers\StudentController;
use Modules\Employee\Http\Controllers\EmployeeController;
use Modules\Academics\Http\Controllers\ManageTimetable\TimeTableController;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\Academics\Entities\Batch;
use Modules\Academics\Entities\Section;
use Modules\Academics\Entities\Semester;
use Modules\Academics\Entities\AcademicsYear;
use Modules\Academics\Entities\AcademicsLevel;


class TimetableAPIController extends Controller
{

    private $academicHelper;
    private $studentController;
    private $employeeController;
    private $timeTableController;

    private $academicsYear;
    private $semester;
    private $academicsLevel;
    private $batch;
    private $section;

    // constructor
    public function __construct(AcademicHelper $academicHelper, StudentController $studentController, EmployeeController $employeeController, TimeTableController $timeTableController, AcademicsYear $academicsYear, Semester $semester, AcademicsLevel $academicsLevel, Batch $batch, Section $section)
    {
        $this->academicHelper  = $academicHelper;
        $this->studentController  = $studentController;
        $this->employeeController  = $employeeController;
        $this->timeTableController  = $timeTableController;

        $this->academicsYear    = $academicsYear;
        $this->semester         = $semester;
        $this->academicsLevel   = $academicsLevel;
        $this->batch            = $batch;
        $this->section          = $section;
    }


    // get batch section timetable
    public function getBatchSectionClassRoutine(Request $request)
    {
        $campusId      = $request->input('campus');
        $instituteId   = $request->input('institute');
        // checking campus with institute
        if($this->academicHelper->findCampusWithInstId($campusId, $instituteId)) {
            // find employee list
            $routineDetails = (object)$this->timeTableController->getClassSectionTimeTable($request);
            // checking
            if(!empty($routineDetails) AND !empty($routineDetails->period_id)>0 AND !empty($routineDetails->periods)>0){
                // class routine details
                $allClassPeriods = $routineDetails->periods;
                // $subjects = $routineDetails->subjects;
                $allTimetables = $routineDetails->timetable;

                // week days array list
                $weekDays = array(1=>'Saturday',2=>'Sunday',3=>'Monday',4=>'Tuesday',5=>'Wednesday',6=>'Thursday',7=>'Friday');
                // class routine array list
                $classRoutineArrayList = array();
                // class period array list
                $classPeriodArrayList = array();

                // day looping
                for($day=1; $day<=count($weekDays); $day++){
                    // period looping
                    foreach($allClassPeriods as $period){

                        // make class period array list
                        $classPeriodArrayList[$period->id] = $period->period_name;

                        // find day current period timetable
                        $timetableProfile = sortTimetable($day, $period->id, $allTimetables);
                        // checking
                        if($timetableProfile->count()>0){
                            // timetable details
                            $timetableProfile = (array) $timetableProfile->toArray();
                            $timetableProfile = reset($timetableProfile);
                            $teacherProfile = $period->teacher($timetableProfile['teacher']);
                            $classSubjectProfile = findClassSubject($timetableProfile['subject']);
                            $subjectProfile = $classSubjectProfile?$classSubjectProfile->subject():null;

                            $classRoutineArrayList[$day][$period->id] = [
                                'day'=>$weekDays[$day],
                                'period'=>$period->id,
                                'subject'=>$subjectProfile?$subjectProfile->subject_name:'(removed)',
                                'subject_code'=>$subjectProfile?$subjectProfile->subject_code:'-',
                                'teacher_name'=>$teacherProfile->first_name." ".$teacherProfile->middle_name." ".$teacherProfile->last_name,
                                'teacher_alias'=>$teacherProfile->alias,
                            ];
                        }else{
                            $classRoutineArrayList[$day][$period->id] = [];
                        }
                    }
                }
                // response
                $responseData = [
                    'status'=>'success',
                    'msg'=>'Class Routine',
                    'data'=>[
                        'day_list'=>$weekDays,
                        'period_list'=>$classPeriodArrayList,
                        'timetable_list'=>$classRoutineArrayList
                    ]
                ];
                // return class section class routine
                return json_encode($responseData);
            }else{
                // return status with msg
                return ['status'=>'failed', 'msg'=>'No records found'];
            }
        }else{
            // return status with msg
            return ['status'=>'failed', 'msg'=>'Invalid Campus or Institute ID'];
        }
    }


}
