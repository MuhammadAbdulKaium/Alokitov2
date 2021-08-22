<?php

namespace Modules\Student\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Student\Entities\StudentWaiver;
use Modules\Student\Entities\StudentEnrollment;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Student\Entities\StudentProfileView;

class StudentWaiverController extends Controller
{


    private $studentWaiver;
    private $studentEnrollment;
    private $academicHelper;
    private $studentProfileView;

    // constructor
    public function __construct(StudentWaiver $studentWaiver, StudentProfileView $studentProfileView, AcademicHelper $academicHelper, StudentEnrollment $studentEnrollment)
    {
        $this->studentWaiver = $studentWaiver;
        $this->studentEnrollment = $studentEnrollment;
        $this->academicHelper = $academicHelper;
        $this->studentProfileView = $studentProfileView;
    }



    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function addWaiverModel($id)
    {
        $studentProfile=$this->studentEnrollment->where('std_id',$id)->first();
        return view('student::pages.student-profile.modals.waiver-modal',compact('studentProfile'));
//        return view('student::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('student::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
//        $studentName=array(
//            'student_name'=>$request->input('student_name')
//        );

//        return $request->all();

        $stdId=$request->input('std_id');
        $waiver_type=$request->input('waiver_type');

        // student Profile
      $studentProfile=$this->studentWaiver->where('std_id',$stdId)->where('waiver_type',$waiver_type)->first();


        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

        $waiverId = $request->input('waiver_id');

        if(empty($studentProfile) || !empty($waiverId)) {

            if (!empty($waiverId)) {
                $studentWaiver = $this->studentWaiver->find($waiverId);
                $studentWaiver->std_id = $request->input('std_id');
                $studentWaiver->institution_id = $instituteId;
                $studentWaiver->campus_id = $campus_id;
                $studentWaiver->type = $request->input('type');
                $studentWaiver->waiver_type = $request->input('waiver_type');
                $studentWaiver->value = $request->input('value');
                $studentWaiver->start_date = date('Y-m-d', strtotime($request->input('start_date')));
                $studentWaiver->end_date = date('Y-m-d', strtotime($request->input('end_date')));
                $studentWaiver->status = 1;
                $saveStudentWaiver = $studentWaiver->save();

            } else {
                $studentWaiver = new $this->studentWaiver;
                $studentWaiver->std_id = $request->input('std_id');
                $studentWaiver->institution_id = $instituteId;
                $studentWaiver->campus_id = $campus_id;
                $studentWaiver->type = $request->input('type');
                $studentWaiver->waiver_type = $request->input('waiver_type');
                $studentWaiver->value = $request->input('value');
                $studentWaiver->start_date = date('Y-m-d', strtotime($request->input('start_date')));
                $studentWaiver->end_date = date('Y-m-d', strtotime($request->input('end_date')));
                $studentWaiver->status = 1;
                $saveStudentWaiver = $studentWaiver->save();

            }

            ///        return $studentWaiver;
            return response()->json([
                'type' => $studentWaiver->type,
                'value' =>$studentWaiver->value,
                'student_id' =>$studentWaiver->std_id,
                'student_name' =>$request->input('student_name'),
                'status' =>"success",
            ]);


        } else {

            ///        return $studentWaiver;
            return response()->json([
                'student_id' =>$studentProfile->std_id,
                'student_name' =>$request->input('student_name'),
                'status' =>"error",
            ]);

        }


//        return response()->json($studentWaiver)->withCallback($request->input('student_name'));
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function studentWaiverList()
    {


        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();


        $studentWaivers=$this->studentWaiver->where('institution_id',$instituteId)->where('campus_id',$campus_id)->paginate(10);
        return view('student::pages.student-waiver-list',compact('studentWaivers'));
//        return view('student::index');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('student::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function deleteWaiver($waiverId)
    {
        $this->studentWaiver->find($waiverId)->delete();
    }

    public  function editWaiverModal($waiverId){
        // set stude profile empty
        $studentProfile="";
        $studentWaiverProfile=$this->studentWaiver->find($waiverId);
        return view('student::pages.student-profile.modals.waiver-modal',compact('studentWaiverProfile','studentProfile'));
    }


    // student waiver manage here

    public function  manageStudentWaiver() {
        $allAcademicsLevel = $this->academicHelper->getAllAcademicLevel();
        return view('student::pages.waiver-manage',compact('allAcademicsLevel'));

    }

    public function  searchStudentWaiver(Request $request) {

        $level = $request->input('academic_level');
        $batch = $request->input('batch');
        $section = $request->input('section');

        // institute details
        $campusId = $this->academicHelper->getCampus();
        $instituteId = $this->academicHelper->getInstitute();
        $academicYear = $this->academicHelper->getAcademicYear();

        // response array
        $studentList = array();
        // class section students
        $studentList = $this->studentProfileView->where([
            'batch'=>$batch,
            'section'=>$section,
            'campus'=>$this->academicHelper->getCampus(),
            'institute'=>$this->academicHelper->getInstitute(),
        ])->orderByRaw('LENGTH(gr_no) asc')->orderBy('gr_no', 'asc')->get();


        // return student list
        return view('student::pages.modal.waiver-manage',compact('studentList'));


    }



    // student manage waiver store
        public function  manageStudentWaiverStore(Request $request) {

              $std_grno= $request->input('std_grno');

             $std_ids= $request->input('std_ids');
            $waiver_type= $request->input('waiver_type');
            $type= $request->input('type');
            $value= $request->input('value');
            $start_date= $request->input('start_date');
            $end_date= $request->input('end_date');
            $instituteId=$this->academicHelper->getInstitute();
            $campus_id=$this->academicHelper->getCampus();
            for($i=1; $i<=count($std_ids); $i++) {
                $studentId=$std_ids[$i];

                // student Profile
           $studentWaiverProfile=$this->studentWaiver->where('std_id',$studentId)->first();
             if (!empty($waiver_type[$studentId]) && !empty($value[$studentId]) && !empty($type[$studentId]) && !empty($start_date[$studentId]) && !empty($end_date[$studentId]) ) {
                if(empty($studentWaiverProfile)) {
                    $studentWaiver = new $this->studentWaiver;
                    $studentWaiver->std_id = $std_ids[$i];
                    $studentWaiver->institution_id = $instituteId;
                    $studentWaiver->campus_id = $campus_id;
                    $studentWaiver->type = $type[$studentId];
                    $studentWaiver->waiver_type = $waiver_type[$studentId];
                    $studentWaiver->value = $value[$studentId];
                    $studentWaiver->start_date = date('Y-m-d', strtotime($start_date[$studentId]));
                    $studentWaiver->end_date = date('Y-m-d', strtotime($end_date[$studentId]));
                    $studentWaiver->status = 1;
                    $saveStudentWaiver = $studentWaiver->save();
                } else {
                    $studentWaiverProfile->std_id = $std_ids[$i];
                    $studentWaiverProfile->institution_id = $instituteId;
                    $studentWaiverProfile->campus_id = $campus_id;
                    $studentWaiverProfile->type = $type[$studentId];
                    $studentWaiverProfile->waiver_type = $waiver_type[$studentId];
                    $studentWaiverProfile->value = $value[$studentId];
                    $studentWaiverProfile->start_date = date('Y-m-d', strtotime($start_date[$studentId]));
                    $studentWaiverProfile->end_date = date('Y-m-d', strtotime($end_date[$studentId]));
                    $studentWaiverProfile->status = 1;
                    $saveStudentWaiver = $studentWaiverProfile->save();
                     }
                }

             }

             return 'success';


         }




}
