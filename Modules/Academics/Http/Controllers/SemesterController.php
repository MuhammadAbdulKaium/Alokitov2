<?php

namespace Modules\Academics\Http\Controllers;

use Session;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Academics\Entities\Semester;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Academics\Entities\BatchSemester;
use Modules\Academics\Entities\ExamStatus;

class SemesterController extends Controller
{
    private  $semester;
    private $academicHelper;
    private $batchSemester;
    private $examStatus;
    // constructor
    public function __construct(Semester $semester, AcademicHelper $academicHelper, BatchSemester $batchSemester, ExamStatus $examStatus)
    {
        $this->semester = $semester;
        $this->academicHelper = $academicHelper;
        $this->batchSemester = $batchSemester;
        $this->examStatus = $examStatus;
    }

    // semester index
    public function index()
    {
        // all semester
        $allSemester = $this->semester->where([
            'academic_year_id'=>$this->academicHelper->getAcademicYear(),
            'institute_id'=>$this->academicHelper->getInstitute(),
            'campus_id'=>$this->academicHelper->getCampus()
        ])->orderBy('name', 'ASC')->get();
        //
        return view('academics::semester.index', compact('allSemester'));
    }

    public function assignSemester()
    {
        // academic levels
        $allAcademicsLevel = $this->academicHelper->getAllAcademicLevel();
        // return view with variables
        return view('academics::semester.modals.semester-assign', compact('allAcademicsLevel'));
    }

    public function assignBatchSemester(Request $request)
    {
        // academic levels
        $allAcademicsLevel = $this->academicHelper->getAllAcademicLevel();
        // return view with variables
        return view('academics::semester.modals.semester-assign', compact('allAcademicsLevel'));
    }

    // create semester
    public function create()
    {
        $semesterProfile = null;
        return view('academics::semester.modals.semester', compact('semesterProfile'));
    }

    // create semester
    public function getSemesterList(Request $request)
    {
        $campusId = $request->input('campus_id');
        $instituteId = $request->input('institute_id');
        $yearId = $request->input('year_id'); // year id
        $levelId = $request->input('level_id'); // level id
        $batchId = $request->input('batch_id'); // batch id
        // return type checking
        $returnType = $request->input('return_type', 'view');
        // academic Info
        $academicInfo = (object)['level'=>$levelId, 'batch'=>$batchId];

        // qry
        $qry = array();
        // checking
        if($yearId){
            $qry['academic_year_id'] = $yearId;
        }else{
            $qry['academic_year_id'] = $this->academicHelper->getAcademicYear();
        }

        if($instituteId){
            $qry['institute_id'] = $instituteId;
        }else{
            $qry['institute_id'] = $this->academicHelper->getInstitute();
        }

        if($campusId){
            $qry['campus_id'] = $campusId;
        }else{
            $qry['campus_id'] = $this->academicHelper->getCampus();
        }

        // checking return type
        if($returnType!='list') $qry['status'] = 1;

        // find all semester list with current academic year
        $semesterList  = $this->semester->where($qry)->get();

        // checking return type
        if($returnType=='api'){
            // return response with variable
            return $semesterList;
        }else{
            // return view with variable
            return view('academics::semester.modals.semester-assign-list', compact('semesterList', 'academicInfo'));
        }
    }

    public function changeSemesterStatus(Request $request)
    {
        // input details
        $year = $this->academicHelper->getAcademicYear();
        $campus = $this->academicHelper->getCampus();
        $institute = $this->academicHelper->getInstitute();

        $levelId = $request->input('level_id');
        $batchId = $request->input('batch_id');
        $semesterId = $request->input('semester_id');
        $requestType = $request->input('request_type');
        // find batch profile
        $batchProfile = $this->academicHelper->findBatch($batchId);
        // batch section list
        $sectionList = $batchProfile->section();

        // find section list and checking
        if($sectionList){
            // checking $requestType
            if($requestType=='assign'){
                // create batchSemester profile
                $batchSemesterCreated = $this->batchSemester->create([
                    'academic_year'=>$year,
                    'academic_level'=>$levelId,
                    'batch'=>$batchId,
                    'semester_id'=>$semesterId,
                ]);
                // checking
                if($batchSemesterCreated){
                    // loop counter
                    $sectionLoopCount = 0;
                    // section list looping
                    foreach ($sectionList as $section){
                        // now create semester exam status
                        $examStatusCreated = $this->examStatus->create([
                            'status'=>0,
                            'semester'=>$semesterId,
                            'section'=>$section->id,
                            'batch'=>$batchId,
                            'level'=>$levelId,
                            'academic_year'=>$year,
                            'campus'=>$campus,
                            'institute'=>$institute,
                        ]);
                        // checking
                        if($examStatusCreated){
                            $sectionLoopCount += 1;
                        }
                    }
                    // checking
                    if($sectionLoopCount==$sectionList->count()){
                        return ['status'=>'success', 'msg'=>'Semester Status Changed'];
                    }else{
                        return ['status'=>'failed', 'msg'=>'Unable to Create Semester exam status'];
                    }
                }else{
                    return ['status'=>'failed', 'msg'=>'Unable to create batch semester'];
                }
            }else{
                // check exam published or not
                $examStatusList = $this->examStatus->where([
                    'status'=>1, 'semester'=>$semesterId, 'batch'=>$batchId, 'level'=>$levelId, 'academic_year'=>$year, 'campus'=>$campus, 'institute'=>$institute,
                ])->get();
                // checking
                if($examStatusList->count()==0){
                    // delete batchSemester profile
                    $batchSemesterDeleted = $this->batchSemester->where([
                        'academic_year'=>$this->academicHelper->getAcademicYear(),
                        'academic_level'=>$levelId,
                        'batch'=>$batchId,
                        'semester_id'=>$semesterId,
                    ])->first()->delete();
                    // checking
                    if($batchSemesterDeleted){
                        // loop counter
                        $sectionLoopCount = 0;
                        // section list looping
                        foreach ($sectionList as $section){
                            // now create semester exam status
                            $examStatusDeleted = $this->examStatus->where([
                                'semester'=>$semesterId,
                                'section'=>$section->id,
                                'batch'=>$batchId,
                                'level'=>$levelId,
                                'academic_year'=>$year,
                                'campus'=>$campus,
                                'institute'=>$institute,
                            ])->first()->delete();
                            // checking
                            if($examStatusDeleted){
                                $sectionLoopCount += 1;
                            }
                        }
                        // checking
                        if($sectionLoopCount==$sectionList->count()){
                            return ['status'=>'success', 'msg'=>'Semester Status Changed'];
                        }else{
                            return ['status'=>'failed', 'msg'=>'Unable to Delete Semester exam status'];
                        }
                    }else{
                        return ['status'=>'failed', 'msg'=>'Unable to Delete batch semester'];
                    }
                }else{
                    return ['status'=>'failed', 'msg'=>'Exam Already Published Under this semester'];
                }
            }
        }else{
            return ['status'=>'failed', 'msg'=>'No section found for this batch'];
        }

    }

    // store and update semester profile
    public function store(Request $request)
    {
        // semester id
        $semesterId = $request->input('semester_id');
        // academic year
        $academicYear = $this->academicHelper->getAcademicYear();
        // checking $semesterId
        if($semesterId>0){
            // find semester profile for update
            $semesterProfile = $this->semester->find($semesterId);
        }else{
            // new semester profile
            $semesterProfile = new $this->semester();
        }
        // input details
        $semesterProfile->name = $request->input('name');
        $semesterProfile->academic_year_id = $academicYear;
        $semesterProfile->start_date = date('Y-m-d', strtotime($request->input('start_date')));
        $semesterProfile->end_date = date('Y-m-d', strtotime($request->input('end_date')));
        $semesterProfile->institute_id = $this->academicHelper->getInstitute();
        $semesterProfile->campus_id = $this->academicHelper->getCampus();

        // save profile
        $semesterProfileSubmitted = $semesterProfile->save();
        // checking
        if($semesterProfileSubmitted){
            Session::flash('success', "Semester Submitted");
            return redirect()->back();
        }else{
            Session::flash('warning', "Invalid information");
            return redirect()->back();
        }
    }

    // semester status changer
    public function status($id)
    {
        // find permission profile
        $semesterProfile = $this->semester->find($id);
        // find semesterProfile status
        $semesterProfile->status = ($semesterProfile->status==0?'1':'0');
        //save profile
        $semesterProfileSubmitted = $semesterProfile->save();
        // checking $permissionProfileSubmitted
        if($semesterProfileSubmitted){
            Session::flash('success', "Semester Status changed");
            return redirect()->back();
        }else{
            Session::flash('warning', "Unable to change semester");
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        // find permission profile
        $semesterProfile = $this->semester->find($id);
        return view('academics::semester.modals.semester', compact('semesterProfile'));
    }

    // delete semester
    public function destroy($id)
    {
        // find role profile
        $semesterProfile = $this->semester->find($id);
        // delete profile
        $semesterProfileDeleted = $semesterProfile->delete();
        // checking $semesterProfileDeleted
        if($semesterProfileDeleted){
            Session::flash('success', "Semester Deleted");
            return redirect()->back();
        }else{
            Session::flash('warning', "Unable to delete semester");
            return redirect()->back();
        }
    }
}
