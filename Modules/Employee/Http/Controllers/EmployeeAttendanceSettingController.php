<?php

namespace Modules\Employee\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Modules\Employee\Entities\EmployeeAttendanceSetting;
use App\Http\Controllers\Helpers\AcademicHelper;

class EmployeeAttendanceSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */

    private  $employeeAttendanceSetting;
    private  $academicHelper;

    public function  __construct(EmployeeAttendanceSetting $employeeAttendanceSetting, AcademicHelper $academicHelper)
    {
        $this->employeeAttendanceSetting=$employeeAttendanceSetting;
        $this->academicHelper=$academicHelper;
    }

    public function index()
    {
        // get institute name
        $institute_id=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

        $employeeAttendanceList=$this->employeeAttendanceSetting->where('institution_id',$institute_id)->where('campus_id',$campus_id)->get();
        return view('employee::pages.emp-attendance-setting.list',compact('employeeAttendanceList'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $attendanceSettingProfile='';
        return view('employee::pages.emp-attendance-setting.modal.add', compact('attendanceSettingProfile'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {

        // get institute name
        $institute_id=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

        $emp_id=$request->input('emp_id');
         $start_time=date('H:i',strtotime($request->input('start_time')));
        $end_time=date('H:i',strtotime($request->input('end_time')));

        $emp_attendance_setting_id=$request->input('emp_attendance_setting_id');
        if(!empty($emp_attendance_setting_id)) {

            $employeeAttendanceProfile=$this->employeeAttendanceSetting->find($emp_attendance_setting_id);
            $employeeAttendanceProfile->institution_id=$institute_id;
            $employeeAttendanceProfile->campus_id=$campus_id;
            $employeeAttendanceProfile->emp_id=$emp_id;
            $employeeAttendanceProfile->start_time=$start_time;
            $employeeAttendanceProfile->end_time=$end_time;
            $result=$employeeAttendanceProfile->save();
            if($result){
                Session::flash('success','Employee Attendance Setting Successfully Updated');
            } else {
                Session::flash('warning','Something Wrong Please Try Again');

            }


        } else {
            $employeeSettingProfile=$this->employeeAttendanceSetting->find($emp_id);
            if(empty($employeeSettingProfile)) {
                $employeeObj = new $this->employeeAttendanceSetting;
                $employeeObj->institution_id = $institute_id;
                $employeeObj->campus_id = $campus_id;
                $employeeObj->emp_id = $emp_id;
                $employeeObj->start_time = $start_time;
                $employeeObj->end_time = $end_time;
                $result = $employeeObj->save();
                if ($result) {
                    Session::flash('success', 'Employee Attendance Setting Successfully Created');
                } else {
                    Session::flash('warning', 'Something Wrong Please Try Again');

                }
            } else {
                Session::flash('success', 'Employee Attendance Setting Already Exist');
            }
        }

        return redirect()->back();

    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('employee::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $attendanceSettingProfile=$this->employeeAttendanceSetting->find($id);
        if($attendanceSettingProfile){
            return view('employee::pages.emp-attendance-setting.modal.add', compact('attendanceSettingProfile'));
         } else {
            Session::flash('warning','Something Wrong Try Again');
        }

        return redirect()->back();

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
    public function delete($id)
    {
        $attendanceSettingProfile=$this->employeeAttendanceSetting->find($id);
        if($attendanceSettingProfile){
            $attendanceSettingProfile->delete();
            Session::flash('success','Employee Attendance Setting Successfully Deleted');
        } else {
            Session::flash('warning','Something Wrong Try Again');
        }

        return redirect()->back();

    }
}
