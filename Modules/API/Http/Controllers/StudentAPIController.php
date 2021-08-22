<?php

namespace Modules\API\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Student\Http\Controllers\StudentController;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\Academics\Entities\Batch;
use Modules\Academics\Entities\Section;
use Modules\Academics\Entities\Semester;
use Modules\Academics\Entities\AcademicsYear;
use Modules\Academics\Entities\AcademicsLevel;


class StudentAPIController extends Controller
{

    private $academicHelper;
    private $studentController;

    private $academicsYear;
    private $semester;
    private $academicsLevel;
    private $batch;
    private $section;

    // constructor
    public function __construct(AcademicHelper $academicHelper, StudentController $studentController, AcademicsYear $academicsYear, Semester $semester, AcademicsLevel $academicsLevel, Batch $batch, Section $section)
    {
        $this->academicHelper  = $academicHelper;
        $this->studentController  = $studentController;

        $this->academicsYear    = $academicsYear;
        $this->semester         = $semester;
        $this->academicsLevel   = $academicsLevel;
        $this->batch            = $batch;
        $this->section          = $section;
    }

    // get academic student list
    public function searchStudentList(Request $request)
    {
        $campusId      = $request->input('campus');
        $instituteId   = $request->input('institute');
        $academicYear  = $request->input('academic_year');
        $academicLevel = $request->input('academic_level');
        $batch         = $request->input('batch');
        $section       = $request->input('section');


        // checking campus with institute
        if($this->academicHelper->findCampusWithInstId($campusId, $instituteId)){
            // year profile
            $yearProfile = $this->academicsYear->where(['id'=>$academicYear, 'campus_id'=> $campusId, 'institute_id'=>$instituteId])->first();

            // batch profile
            // $batchProfile = $this->batch->where(['id'=>$batch, 'campus'=> $campusId, 'institute'=>$instituteId])->first();
            // level profile
            // $levelProfile = $this->academicsLevel->where(['id'=>$academicLevel, 'campus_id'=> $campusId, 'institute_id'=>$instituteId])->first();
            // section profile
            // $sectionProfile = $this->section->where(['id'=>$section, 'academics_year_id'=>$academicYear, 'campus'=> $campusId, 'institute'=>$instituteId])->first();

            // checking section
            if($yearProfile){
                // student list
                $studentList = $this->studentController->searchStudent($request);
                // checking
                if(count($studentList)>0){
                    // student array list
                    $studentArrayList = array();
                    // std looping
                    foreach ($studentList as $student){
                        // checking std photo
                        if($content = $student->singelAttachment('PROFILE_PHOTO')){
                            $photo = url('/assets/users/images/'.$content->singleContent()->name);
                        }else{
                            $photo = url('/assets/users/images/user-default.png');
                        };

                        $studentArrayList[$student->std_id] = [
                            'name'=> $student->first_name.' '. $student->middle_name.' '. $student->last_name,
                            'email'=> $student->email,
                            'gr_no'=> $student->gr_no,
                            'photo'=> $photo,
                        ];
                    }
                    // return viw with variable
                    return ['status'=>'success', 'msg'=>'Academic Student list', 'data'=>$studentArrayList];
                }else{
                    // return status with msg
                    return ['status'=>'failed', 'msg'=>'No records found'];
                }
            }else{
                // return status with msg
                return ['status'=>'failed', 'msg'=>'Year not matched with campus and institute'];
            }
        }else{
            // return status with msg
            return ['status'=>'failed', 'msg'=>'Invalid Campus or Institute ID'];
        }
    }


}
