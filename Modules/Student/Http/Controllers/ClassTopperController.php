<?php

namespace Modules\Student\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Student\Entities\ClassTopper;
use Modules\Student\Entities\StudentEnrollment;
use Modules\Student\Entities\StudentInformation;
use App\Http\Controllers\Helpers\AcademicHelper;

class ClassTopperController extends Controller
{

    private $classTopper;
    private $studentEnrollment;
    private $studentInformation;
    private $academicHelper;

    public function __construct(ClassTopper $classTopper, StudentEnrollment $studentEnrollment, StudentInformation $studentInformation, AcademicHelper $academicHelper)
    {
        $this->classTopper = $classTopper;
        $this->studentEnrollment = $studentEnrollment;
        $this->studentInformation = $studentInformation;
        $this->academicHelper = $academicHelper;
    }

    // create class topper
    public function create($id)
    {

        // find student profile
        $studentProfile = $this->studentInformation->where(['id'=>$id])->with('classTopper')->first();
        // return view with variable(s)
        return view('student::pages.modal.class-topper', compact('studentProfile'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response|array|null
     */
    public function store(Request $request)
    {
        // institute details
        $campus = $this->academicHelper->getCampus();
        $institute = $this->academicHelper->getInstitute();
        // student details
        $stdId = $request->input('std_id');
        $stdProfile = $this->academicHelper->findStudent($stdId);
        $stdEnroll = $stdProfile->enroll();
        // request type
        $requestType = $request->input('request_type');

        // checking request type
        if($requestType=='assign'){
            // checking std profile
            if($classTopper = $this->checkClassTopperProfile($stdId)->onlyTrashed()->first()){
                // restore the trashed profile and checking
                $classTopper->restore();
            }else{
                // new class topper object
                $classTopper = new $this->classTopper();
            }
            // store class topper details
            $classTopper->gr_no = $stdEnroll->gr_no;
            $classTopper->std_id = $stdProfile->id;
            $classTopper->section = $stdEnroll->section;
            $classTopper->batch = $stdEnroll->batch;
            $classTopper->academic_level = $stdEnroll->academic_level;
            $classTopper->academic_year = $stdEnroll->academic_year;
            $classTopper->campus = $campus;
            $classTopper->institute = $institute;
            if($classTopper->save()){
                // return success msg
                return ['status'=>true, 'request_type'=>'assign', 'msg'=>'Class Topper Assigned'];
            }else{
                // return msg
                return ['status'=>false, 'msg'=>'Unable to set class topper'];
            }
        }else{
            // checking std profile
            if($classTopper = $this->checkClassTopperProfile($stdId)->first()){
                // now delete class topper profile
                if($classTopper->delete()){
                    // return success msg
                    return ['status'=>true, 'request_type'=>'remove', 'msg'=>'Class Topper Removed'];
                }else{
                    // return msg
                    return ['status'=>false, 'msg'=>'Unable to Remove class topper'];
                }
            }else{
                // return msg
                return ['status'=>false, 'msg'=>'Class Topper Profile not Found'];
            }
        }
    }


    //  check class topper profile exists or not
    public function checkClassTopperProfile($stdId)
    {
        // institute and campus details
        $campus = $this->academicHelper->getCampus();
        $institute = $this->academicHelper->getInstitute();
        // find class topper profile and return
        return $this->classTopper->where(['std_id'=>$stdId, 'campus'=>$campus, 'institute'=>$institute]);
    }

}
