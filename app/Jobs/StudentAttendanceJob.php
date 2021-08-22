<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Modules\Academics\Entities\AttendanceUpload;
use Modules\Employee\Entities\AttendanceDevice;
use Modules\Student\Entities\StudentInformation;
use App\Http\Controllers\SmsSender;
class StudentAttendanceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('Student Attendance: started');
        $data = array();
        $data["operation"] = "fetch_log";
        $data["auth_code"] = "bh0qfj1gvnpddbcml0tej7z3vaaoosh";

        $now = Carbon::now();

        $start = $now;
        $access_date = $start->toDateString();
        try{
            $attendanceDeviceList = AttendanceDevice::select(['registration_id', 'access_date', 'access_time', 'person_type', 'institute_id', 'campus_id'])
                ->where('person_type', 'student')
                ->where('access_date', date('Y-m-d'))
                ->get();
            //Log::info('Data'.print_r($attendanceDeviceList));
            if($attendanceDeviceList->count()>0){
                foreach ($attendanceDeviceList as $studentAttendance){
                    $studentAttendanceArray = $this->prepareStudenteAttendanceData($studentAttendance);
                    //dd($studentAttendance);
                    $studentAttendanceProfile=AttendanceUpload::where('std_id',$studentAttendance->registration_id)->whereDate('entry_date_time', '=', date('Y-m-d',strtotime($studentAttendance->access_date)))->first();
                    Log::info('Profile ='.$studentAttendanceProfile);
                   // Log::info('get std_id='.$studentAttendance->registration_id);
                    if(!empty($studentAttendanceArray) && empty($studentAttendanceProfile)) {

                        AttendanceUpload::create($studentAttendanceArray);
//                        Log::info('device_preset_attence'.$studentAttendance->registration_id);
                    }
                }
                Log::info('Student Attendance Job : Data uploaded');
            }

            else{
                //dd("Attendance Data is Empty!!");
                Log::info('Student Attendance Job : Attendance Data is Empty!!');
            }

        } catch (\Exception $exception){
            Log::info('Student Attendance Job Error : ' .$exception->getMessage());
        }
        Log::info('Student Attendance Job Ended');

//
    }

    public function prepareStudenteAttendanceData($studentAttendance){
        $std_id = $studentAttendance->registration_id;
        $student = StudentInformation::find($std_id);
        if(empty($student)) return null;
        $student_enrolement = $student->enroll();
        if(empty($student_enrolement)) return null;
        $data = array(
            'std_id' => $studentAttendance->registration_id,
            'std_gr_no' => $student_enrolement->gr_no,
            'entry_date_time' => $studentAttendance->access_date. ' '. $studentAttendance->access_time,
            'academic_year' => $student_enrolement->academic_year,
            'level' => $student_enrolement->academic_level,
            'batch' => $student_enrolement->batch,
            'section' => $student_enrolement->section,
            'institute' => $studentAttendance->institute_id,
            'campus' => $studentAttendance->campus_id,
            'is_device' =>1
        );
        return $data;
    }

}
