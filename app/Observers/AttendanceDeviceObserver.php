<?php

namespace App\Observers;

use Modules\Employee\Entities\AttendanceDevice;
use Modules\Employee\Entities\EmployeeAttendance;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\Student\Entities\StudentProfileView;
use Modules\Academics\Entities\AttendanceUpload;

class AttendanceDeviceObserver
{

    private  $attendanceUpload;
    private  $studentProfileView;
    private  $employeeAttendance;
    private  $employeeInformation;

    public function __construct(StudentProfileView $studentProfileView, AttendanceUpload $attendanceUpload, EmployeeAttendance $employeeAttendance, EmployeeInformation $employeeInformation){
        $this->attendanceUpload = $attendanceUpload;
        $this->studentProfileView = $studentProfileView;
        $this->employeeAttendance = $employeeAttendance;
        $this->employeeInformation = $employeeInformation;
    }

    // attendance device log created action
    public function created(AttendanceDevice $attendanceDevice) {
        // person Type
        $personType = $attendanceDevice->person_type;
        // access date
        $accessDate = $attendanceDevice->access_date;
        // access time
        $accessTime = $attendanceDevice->access_time;
        // registration id
        $registrationId = $attendanceDevice->registration_id;
        // campus id
        $campusId = $attendanceDevice->campus_id;
        // institute id
        $instituteId = $attendanceDevice->institute_id;

        // checking person type
        if($personType=='student'){
            // access date time
            $accessDateTime = date('Y-m-d H:i:s', strtotime($accessDate.' '.$accessTime));
            // qry
            $qry = ['std_id'=>$registrationId, 'campus'=>$campusId, 'institute'=>$instituteId];
            // find student attendance log profile
            if($attendanceLogProfile = $this->attendanceUpload->where($qry)->whereDate('entry_date_time', $accessDate)->first()){
                // entry date time
                $entryDateTime = date('Y-m-d H:i:s', strtotime($attendanceLogProfile->entry_date_time));
                // comparing tow date time
                if($accessDateTime>$entryDateTime){
                    // update student attendance out date time
                    $attendanceLogProfile->out_date_time = $accessDateTime;
                }else{
                    // update student attendance out date time
                    $attendanceLogProfile->out_date_time = $entryDateTime;
                    // update student attendance entry date time
                    $attendanceLogProfile->entry_date_time = $accessDateTime;
                }
                // check and save
                $attendanceLogProfile->save();
            }else{
                // find student profile
                if($studentProfile = $this->studentProfileView->where($qry)->first()){
                    // create student attendance
                    $this->attendanceUpload->create([
                        'std_id'=>$studentProfile->std_id,
                        'std_gr_no'=>$studentProfile->gr_no,
                        'entry_date_time'=> $accessDateTime,
                        'academic_year'=>$studentProfile->academic_year,
                        'level'=>$studentProfile->academic_level,
                        'batch'=>$studentProfile->batch,
                        'section'=>$studentProfile->section,
                        'campus'=>$studentProfile->campus,
                        'institute'=>$studentProfile->institute,
                        'is_device'=>1
                    ]);
                }
            }
        }elseif($personType=='employee'){
            // access date
            $accessDate = date('Y-m-d', strtotime($accessDate));
            // access time
            $accessTime = date('H:i:s', strtotime($accessTime));
            //employee attendance qry
            $atdQry = ['emp_id'=>$registrationId, 'brunch_id'=>$campusId, 'company_id'=>$instituteId];
            // find employee attendance log profile
            if($empAttendanceLogProfile = $this->employeeAttendance->where($atdQry)->whereDate('in_date', $accessDate)->first()){
                // entry time
                $entryTime = date('H:i:s', strtotime($empAttendanceLogProfile->in_time));
                // compare access and entry time
                if($accessTime>$entryTime){
                    // update employee attendance out time
                    $empAttendanceLogProfile->out_time = $accessTime;
                }else{
                    // update employee attendance in / out time
                    $empAttendanceLogProfile->in_time = $accessTime;
                    $empAttendanceLogProfile->out_time = $entryTime;
                }
                // update employee attendance out date
                $empAttendanceLogProfile->out_date = $accessDate;
                // update employee / save
                $empAttendanceLogProfile->save();
            }else{
                // employee qry
                $empQry = ['id'=>$registrationId, 'campus_id'=>$campusId, 'institute_id'=>$instituteId];
                // find employee profile
                if($employeeProfile = $this->employeeInformation->where($empQry)->first()){
                    // create employee attendance
                    $this->employeeAttendance->create([
                        'emp_id' => $registrationId,
                        'in_date' => $accessDate,
                        'in_time' => $accessTime,
                        'brunch_id' => $campusId,
                        'company_id' => $instituteId
                    ]);
                }
            }

        }
    }
}
