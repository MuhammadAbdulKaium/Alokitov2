<?php

namespace Modules\API\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Employee\Http\Controllers\EmployeeController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class EmployeeAPIController extends Controller
{


    private $academicHelper;
    private $employeeController;

    // constructor
    public function __construct(AcademicHelper $academicHelper, EmployeeController $employeeController)
    {
        $this->academicHelper  = $academicHelper;
        $this->employeeController  = $employeeController;
    }


    // get academic employee list
    public function searchEmployeeList(Request $request)
    {
        $campusId      = $request->input('campus');
        $instituteId   = $request->input('institute');
        // checking campus with institute
        if($this->academicHelper->findCampusWithInstId($campusId, $instituteId)) {
            // find employee list
            $employeeList = $this->employeeController->searchEmployee($request);
            // employee array list
            $employeeArrayList = null;
            // checking
            if(count($employeeList)>0){
                // employee list looping
                foreach ($employeeList as $employee){

                    // checking employee photo
                    if($content = $employee->singelAttachment('PROFILE_PHOTO')){
                        $photo = url('/assets/users/images/'.$content->singleContent()->name);
                    }else{
                        $photo = url('/assets/users/images/user-default.png');
                    };

                    $employeeArrayList[$employee->id] = [
                        'title'=>$employee->title,
                        'name'=>$employee->first_name.' '.$employee->middle_name.' '.$employee->last_name,
                        'alias'=>$employee->alias,
                        'gender'=>$employee->gender,
                        'email'=>$employee->email,
                        'phone'=>$employee->phone,
                        'blood_group'=>$employee->blood_group,
                        'department'=>$employee->department()->name,
                        'designation'=>$employee->designation()->name,
                        'photo'=>$photo,
                    ];
                }
                // return
                return ['status'=>'success', 'msg'=>'Employee list', 'data'=>$employeeArrayList];
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
