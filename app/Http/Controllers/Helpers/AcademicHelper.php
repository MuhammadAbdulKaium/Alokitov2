<?php

namespace App\Http\Controllers\Helpers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Setting\Entities\Institute;
use Modules\Setting\Entities\Campus;

use App\Subject;
use Modules\Academics\Entities\Batch;
use Modules\Academics\Entities\Section;
use Modules\Academics\Entities\ClassSubject;
use Modules\Academics\Entities\Assessments;
use Modules\Academics\Entities\Semester;
use Modules\Academics\Entities\AcademicsYear;
use Modules\Academics\Entities\AcademicsLevel;
Use Modules\Academics\Entities\AcademicsAdmissionYear;
use Modules\Student\Entities\StudentProfileView;
use Modules\Student\Entities\StudentInformation;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\Academics\Entities\BatchSemester;
use Modules\Academics\Entities\AdditionalSubject;
use Modules\Setting\Entities\State;
use Modules\Setting\Entities\City;
use Modules\Academics\Entities\ExamStatus;
use Modules\Academics\Entities\SubjectGroup;




class AcademicHelper extends Controller
{

    private $institute;
    private $batch;
    private $section;
    private $subject;
    private $classSubject;
    private $assessments;
    private $semester;
    private $academicsYear;
    private $academicsLevel;
    private $studentProfileView;
    private $employeeInformation;
    private $batchSemester;
    private $studentInformation;
    private $campus;
    private $additionalSubject;
    private $state;
    private $city;
    private $examStatus;
    private $subjectGroup;

    public function __construct(Institute $institute, Batch $batch, Section $section, Subject $subject, ClassSubject $classSubject, Assessments $assessments, Semester $semester, AcademicsYear $academicsYear, AcademicsLevel $academicsLevel, StudentProfileView $studentProfileView, EmployeeInformation $employeeInformation, BatchSemester $batchSemester, StudentInformation $studentInformation, Campus $campus, AdditionalSubject $additionalSubject, State $state, City $city, ExamStatus $examStatus, SubjectGroup $subjectGroup)
    {
        $this->institute = $institute;
        $this->batch = $batch;
        $this->section = $section;
        $this->subject = $subject;
        $this->classSubject = $classSubject;
        $this->assessments = $assessments;
        $this->semester = $semester;
        $this->academicsYear = $academicsYear;
        $this->academicsLevel = $academicsLevel;
        $this->studentProfileView = $studentProfileView;
        $this->employeeInformation = $employeeInformation;
        $this->batchSemester = $batchSemester;
        $this->studentInformation = $studentInformation;
        $this->campus = $campus;
        $this->additionalSubject = $additionalSubject;
        $this->state = $state;
        $this->city = $city;
        $this->examStatus = $examStatus;
        $this->subjectGroup = $subjectGroup;
    }

    // find exam status
    public function findExamStatus($semester, $section, $batch, $level, $academicYear, $campus, $institute)
    {
        // find exam status
        return  $examStatus = $this->examStatus->where([
            'semester'=>$semester, 'section'=>$section, 'batch'=>$batch, 'level'=>$level, 'academic_year'=>$academicYear, 'campus'=>$campus, 'institute'=>$institute,
        ])->first();
    }


    // get additional subject list
    public function getAdditionalSubjectStdList($subId, $section, $batch, $campus, $institute)
    {
        // response data
        $responseData = [];
        // find academic year
        $academicYear = $this->getAcademicYear();
        // find additional subject list
        $additionalSubjectList = $this->additionalSubject
            ->where(['section'=>$section, 'batch'=>$batch, 'campus'=>$campus, 'institute'=>$institute])->get(['std_id', 'sub_list']);
        // checking
        if($additionalSubjectList->count()>0){
            // looping
            foreach ($additionalSubjectList as $addSubject){
                // find subject list
                $subList = json_decode($addSubject->sub_list);
                // checking sublist
                if($subList){
                    // subject list looping
                    foreach ($subList as $subType=>$stdSubList){
                        // student subject list
                        $subjectArray = (array) json_decode($stdSubList);
                        // checking student subject list
                        if(in_array($subId,$subjectArray)){
                            $responseData[$addSubject->std_id] = $subId;
                        }
                    }
                }
            }
        }
        // return response data
        return $responseData;
    }

    // check subject student list from class student list
    public function getClassSubjectStudentList($classSubject, $classSubjectStdList, $classStdList)
    {
        // response array list
        $responseData = array();
        // checking class subject type
        if($classSubject->subject_type==0 || $classSubject->subject_type==1){
            // return class student list
            return $classStdList;
        }else{
            // checking
            if(count($classSubjectStdList)>0 AND count($classStdList)>0){
                // class student list looping
                foreach ($classStdList as $student){
                    // checking student in the subject student list
                    if(array_key_exists($student['id'],$classSubjectStdList)){
                        $responseData[] = $student;
                    }
                }
            }
        }

        // return response data
        return $responseData;
    }

    // all academics years list
    public function getAllAcademicYears()
    {
//        return $this->academicsYear->where(['status'=>0])->get();
        return $this->academicsYear->where([
            'institute_id'=>$this->getInstitute(),
            'campus_id'=>$this->getCampus(),
        ])->get();
    }

    // get institute profile
    public function getInstituteProfile()
    {
        return $this->institute->find($this->getInstitute());
    }

    // get institute academic year id
    public function getAcademicYear()
    {
        return session()->get('academic_year');
    }

    // get institute admission year id
    public function getAdmissionYear()
    {
        return AcademicsAdmissionYear::where(['status'=>1])->first()->id;
    }

    // find academic year profile
    public function getAcademicYearProfile()
    {
        return $this->academicsYear->find($this->getAcademicYear());
    }
    // get institute modules
    public function getAcademicSemester()
    {
        // all semester
        return $this->semester->where([
            'academic_year_id'=>$this->getAcademicYear(),
            'institute_id'=>$this->getInstitute(),
            'campus_id'=>$this->getCampus(),
            'status'=>1,
        ])->get();
    }

    // get institute id
    public function getInstitute()
    {
        return session()->get('institute');
    }

    // get institute campus id
    public function getCampus()
    {
        return session()->get('campus');
    }

    // get institute grading scale id
    public function getGradingScale()
    {
        return session()->get('grading_scale');
    }
    // get institute modules
    public function getInstituteModules()
    {
        return $this->getInstituteProfile()->instituteModules()->get();
    }

    // get all academic levels using this academic year id and status is_active
    public function getAllAcademicLevel()
    {
        return $this->academicsLevel->where([
            /*'academics_year_id'=>$this->getAcademicYear(),*/
            'institute_id'=>$this->getInstitute(),
            'campus_id'=>$this->getCampus(),
            'is_active'=>1
        ])->get();
    }

    // institution academic detail information
    public function getAcademicInfo()
    {
        $campusId = $this->getCampus();
        $instituteId = $this->getInstitute();
        // qry maker
        $qry = ['campus'=>$campusId, 'institute'=>$instituteId];
        // institute total student list
        $studentList = $this->studentProfileView->where($qry)->get()->count();
        // institute total employee list
        $employeeList = $this->employeeInformation->where(['campus_id'=>$campusId, 'institute_id'=>$instituteId]);
        // batch / class list
        $batchList = $this->batch->where($qry)->get()->count();
        // section list
        $sectionList = $this->section->where($qry)->get()->count();
        // subject  list
        $subjectList = $this->subject->where($qry)->get()->count();

        // academic details
        return (object)[
            'batch'=>$batchList,
            'section'=>$sectionList,
            'subject'=>$subjectList,
            'student'=>$studentList,
            'teacher'=>$employeeList->where(['category'=>1])->get()->count(),
            'staff'=>$employeeList->where(['category'=>0])->get()->count(),
            'total_employee'=>$employeeList->get()->count()
        ];
    }

    // find batch
    public function getBatchList()
    {
        $campusId = $this->getCampus();
        $instituteId = $this->getInstitute();
        // qry maker
        $qry = ['campus'=>$campusId, 'institute'=>$instituteId];
        $allBatch =$this->batch->where($qry)->get();

        // response array
        $batchList = array();
        // looping for adding division into the batch name
        foreach ($allBatch as $batch) {
            if ($batch->get_division()) {
                $batchList[$batch->id] = $batch->batch_name . " - " . $batch->get_division()->name;
            } else {
                $batchList[$batch->id] = $batch->batch_name . " - " . $batch->get_division()->name;
            }
        }

        return $batchList;

    }

    // find batch
    public function getBatch($batchId)
    {
        return $this->batch->find($batchId);
    }
    // find section
    public function getSection($sectionId)
    {
        return $this->section->find($sectionId);
    }
    // find subject
    public function getSubject($subjectId)
    {
        return $this->subject->find($subjectId);
    }
    // find subject
    public function getClassSubject($classSubjectId)
    {
        return $this->classSubject->find($classSubjectId);
    }
    // find subject
    public function getAssessment($assessmentId)
    {
        return $this->assessments->find($assessmentId);
    }
    // find semester
    public function getSemester($semesterId)
    {
        return $this->semester->find($semesterId);
    }
    // get all institute list
    public function getInstituteList()
    {
        return $this->institute->orderBy('institute_name', 'ASC')->get();
    }

    // get all campus list
    public function getCampusList()
    {
        return $this->campus->orderBy('name', 'ASC')->get();
    }

    // find batch semester list
    public function getBatchSemesterList($academicYear, $academicLevel,  $academicBatch)
    {
        $semesterList = array();
        // semester list
        $batchSemesterList =  $this->batchSemester->where([
            'academic_year'=>$academicYear,
            'academic_level'=>$academicLevel,
            'batch'=>$academicBatch
        ])->get();

        // looping for adding division into the batch name
        foreach ($batchSemesterList as $myBatchSemester) {
            $semester = $myBatchSemester->semester();
            //  checking semester profile
            if($semester){
                // semester list maker
                $semesterList[] = [
                    'id' => $semester->id,
                    'name' => $semester->name,
                    'start_date' => $semester->start_date,
                    'end_date' => $semester->end_date
                ];
            }
        }
        // return
        return $semesterList;
    }


    // find class section group subject list
    public function findClassSectionSubjectList($section, $batch, $campus, $institute)
    {
        // group subject array list
        $subjectArrayList = array();

        // find class section group subject list
        $subjectList = DB::table('class_subjects as cs')
            ->join('subject as s', 's.id', '=', 'cs.subject_id')
            ->select('s.id as s_id','cs.id as cs_id', 's.subject_name as s_name', 'cs.subject_code as s_code', 'cs.subject_type as s_type')
            ->where(['cs.section_id'=>$section,'cs.class_id'=>$batch, 's.campus'=>$campus,'s.institute'=>$institute])
            ->orderBy('sorting_order', 'ASC')->get();

        // subject list looping
        foreach ($subjectList as $subject){
            // add group and subject details
            $subjectArrayList[$subject->cs_id] = [
                's_id' => $subject->s_id,  'name' => $subject->s_name, 'code' =>$subject->s_code, 'type' => $subject->s_type
            ];
        }

        // return group subject list
        return $subjectArrayList;
    }

    // find class section group subject list
    public function findClassSectionGroupSubjectList($section=null, $batch, $campus, $institute)
    {
        // group subject array list
        $groupSubjectArrayList = array();

        // find class section group subject list
        $subjectList = DB::table('class_subjects as cs')->join('subject as s', 's.id', '=', 'cs.subject_id')->join('subject_group as g', 'g.id', '=', 'cs.subject_group')
            ->select('g.id as g_id', 'g.name as g_name', 'cs.id as cs_id', 's.subject_name as s_name', 'cs.subject_code as s_code', 'cs.subject_type as s_type')
            ->where(['cs.class_id'=>$batch, 'g.campus'=>$campus,'g.institute'=>$institute]);

        if($section != null) {
            $subjectList->where('cs.section_id', $section);
        }
            $subjectListRes = $subjectList->orderBy('sorting_order', 'ASC')->get();

        // subject list looping
        foreach ($subjectListRes as $subject){
            // checking group id
            if(array_key_exists($subject->g_id, $groupSubjectArrayList)){
                // add another subject details
                $groupSubjectArrayList[$subject->g_id]['subject'][$subject->cs_id]=$subject->s_name;
                $groupSubjectArrayList[$subject->g_id]['code'][]=$subject->s_code;
            }else {
                // add group and subject details
                $groupSubjectArrayList[$subject->g_id] = [
                    'name' => $subject->g_name, 'type' => $subject->s_type, 'subject' => [$subject->cs_id => $subject->s_name], 'code' => [$subject->s_code]
                ];
            }
        }

        // return group subject list
        return $groupSubjectArrayList;
    }


    // find class section group subject list
    public function findClassSectionAdditionalSubjectList($section, $batch, $campus, $institute, $type='group')
    {
        // additional subject array list
        $additionalArrayList = array();

        // find class section group subject list
        $additionalSubject = DB::table('academic_additional_subjects')
            ->where([ 'batch'=>$batch, 'campus'=>$campus, 'institute'=>$institute]);
        if($section != null){
            $additionalSubject->where('section',$section);
        }

        $additionalSubjectList =  $additionalSubject->distinct('std_id')
            ->get(['std_id', 'sub_list', 'group_list']);

        // additional subject looping
        foreach ($additionalSubjectList as $additional){
            // student id
            $stdId = $additional->std_id;
            // subject and group list
            $subList = (array) json_decode($additional->sub_list);
            $groupList = (array) json_decode($additional->group_list);

            // checking type
            if($type=='group'){
                // checking student subject (e_1)
                if(array_key_exists('e_1', $groupList) AND $groupList['e_1']){
                    // store student subject group
                    $additionalArrayList[$stdId][] = $groupList['e_1'];
                }
                // checking student subject (e_2)
                if(array_key_exists('e_2', $groupList) AND $groupList['e_2']){
                    // store student subject group
                    $additionalArrayList[$stdId][] = $groupList['e_2'];
                }
                // checking student subject (e_3)
                if(array_key_exists('e_3', $groupList) AND $groupList['e_3']){
                    // store student subject group
                    $additionalArrayList[$stdId][] = $groupList['e_3'];
                }
                // checking student subject (e_3)
                if(array_key_exists('opt', $groupList) AND $groupList['opt']){
                    // store student subject group
                    $additionalArrayList[$stdId][] = $groupList['opt'];
                }
            }else{
                $pattern = array('/[^a-zA-Z0-9 -]/');
                // checking student subject (e_1)
                if(array_key_exists('e_1', $subList) AND $subList['e_1']){
                    // store student subject group
                    $additionalArrayList[$stdId][] = preg_replace($pattern, '', $subList['e_1']);
                }
                // checking student subject (e_2)
                if(array_key_exists('e_2', $subList) AND $subList['e_2']){
                    // store student subject group
                    $additionalArrayList[$stdId][] = preg_replace($pattern, '', $subList['e_2']);
                }
                // checking student subject (e_3)
                if(array_key_exists('e_3', $subList) AND $subList['e_3']) {
                    // store student subject group
                    $additionalArrayList[$stdId][] = preg_replace($pattern, '', $subList['e_3']);
                }
                // checking student subject (e_3)
                if(array_key_exists('opt', $subList) AND $subList['opt']){
                    // store student subject group
                    $additionalArrayList[$stdId][] = preg_replace($pattern, '', $subList['opt']);
                }
            }

        }

        // return additional subject list
        return $additionalArrayList;
    }


    // find academic Year profile using institute and campus id
    public function findInstituteAcademicYear($instituteId, $campusId)
    {
        return $this->academicsYear->where(['institute_id'=>$instituteId, 'campus_id'=>$campusId, 'status'=>1])->first();
    }


    // find academic year profile
    public function findYear($yearId)
    {
        return $this->academicsYear->find($yearId);
    }
    // find academic level profile
    public function findLevel($levelId)
    {
        return $this->academicsLevel->find($levelId);
    }
    // find academic batch profile
    public function findBatch($batchId)
    {
        return $this->batch->find($batchId);
    }
    // find academic section profile
    public function findSection($sectionId)
    {
        return $this->section->find($sectionId);
    }

    // find institute profile
    public function findInstitute($instituteId)
    {
        return $this->institute->find($instituteId);
    }

    // find campus profile
    public function findCampus($campusId)
    {
        return $this->campus->find($campusId);
    }

    // find campus profile with institute id
    public function findCampusWithInstId($campusId, $instId)
    {
        return $this->campus->where(['id'=>$campusId, 'institute_id'=>$instId])->first();
    }

    ////////////// std information ////////
    // find academic section profile
    public function findStudent($stdId)
    {
        return $this->studentInformation->find($stdId);
    }

    // find state list
    public function stateList(){
        /// country id
        $countryId = 1;
        // all state list
        return $this->state->where(['country_id'=>$countryId])->get(['id', 'name']);
    }

    // find state list
    public function getCityList($stateId){
        // all city list
        return $this->city->where(['state_id'=>$stateId])->get(['id', 'name']);
    }

    public function stdIdsHasTheSub($batchId, $sectionId, $subjectId)
    {
        $stdIds = [];
        if (is_array($batchId)) {
            $stdSubjects = AdditionalSubject::whereIn('batch', $batchId)->get();
        } else {
            $stdSubjects = AdditionalSubject::where('batch', $batchId)->get();
        }

        if ($sectionId) {
            $stdSubjects = $stdSubjects->where('section', $sectionId);
        }

        foreach ($stdSubjects as $key1 => $stdSubject) {
            $subList = json_decode($stdSubject->sub_list, 1);

            foreach ($subList as $key2 => $subIds) {
                if (strpos($subIds, (string)$subjectId)) {
                    $stdIds[$stdSubject->std_id] = $stdSubject->std_id;
                }
            }
        }

        return $stdIds;
    }

}
