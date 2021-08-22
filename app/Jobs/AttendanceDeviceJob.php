<?php

namespace App\Jobs;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Modules\Employee\Entities\AttendanceDevice;
use Modules\Employee\Entities\AttendanceDeviceLog;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\Student\Entities\StudentInformation;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class AttendanceDeviceJob implements ShouldQueue
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
        // laravel log
        Log::info("Attendance Device Job Started...");
        // find last attendance log
        $last_log = AttendanceDeviceLog::orderBy('created_at', 'desc')->first();

        // get the current time
        $current = Carbon::now();
        // end date time
        $end_date = $current->toDateString();
        $end_time = $current->toTimeString();
        // start date time
        $previous = $current->subDays(6);
        $start_date = $previous->toDateString();
        $start_time = $previous->toTimeString();
        // access id
        $access_id = $last_log?$last_log->access_id:0;

        // json data
        $json = array();
        $json["operation"] = "fetch_log";
        $json["auth_code"] = "bh0qfj1gvnpddbcml0tej7z3vaaoosh";
        $json["start_date"] = $start_date;
        $json["start_time"] = $start_time;
        $json["end_date"] = $end_date;
        $json["end_time"] = $end_time;
        // checking access id
        if($access_id>0) $json["access_id"] = $access_id;

        //GuzzleHttp\Client
        $client = new Client();
        // guzzle client request
        $attendanceList = json_decode($client->request('POST', 'https://rumytechnologies.com/rams/json_api', ['json' => $json])->getBody()->getContents())->log;

        // checking guzzle request log
        if(!empty($attendanceList) AND count($attendanceList)>0){

            // checking attendance device log
            if($attendanceDeviceLog = $this->createAttendanceDeviceLog($json)){

                // laravel log
                Log::info($json);

                // attendance loop counter
                $attendanceLoopCounter = 0;
                // my last access id
                $myLastAccessId = $access_id;
                // client request looping
                foreach ($attendanceList as $attendance) {
                    try {
                        $reg_id = strtolower($attendance->registration_id);
                        if(strlen($reg_id) <2) continue;
                        if(($reg_id[0] != 'e' || $reg_id[0] != 's') && $reg_id[1] != '_') continue;

                        // log data
                        $log_data = array();
                        $log_data["card"] = $attendance->card;
                        $log_data["access_id"] = $attendance->access_id;
                        $log_data["access_date"] = $attendance->access_date;
                        $log_data["access_time"] = $attendance->access_time;
                        // find user id form log registration id
                        $id = is_numeric(substr($attendance->registration_id, 2)) ? substr($attendance->registration_id, 2) : 0;
                        $log_data["registration_id"] = $id;

                        if($reg_id[0] =='e') {
                            $person_type = 'employee';
                            $employee = EmployeeInformation::find( (int) $id);
                            if(empty($employee)) continue;
                            // employee details
                            $log_data["person_type"] = $person_type;
                            $log_data["institute_id"] = $employee->institute_id;
                            $log_data["campus_id"] = $employee->campus_id;

                        }elseif ($reg_id[0] =='s'){
                            $person_type = 'student';
                            $student = StudentInformation::find( (int) $id);
                            if(empty($student)) continue;
                            // student details
                            $log_data["person_type"] = $person_type;
                            $log_data["institute_id"] = $student->institute;
                            $log_data["campus_id"] = $student->campus;
                        }

                        // attendance device log
                        AttendanceDevice::create($log_data);
                        // update my last access id
                        $myLastAccessId = $attendance->access_id;
                        // log loop counter
                        $attendanceLoopCounter +=1;

                    } catch (\Exception $exception) {
                        Log::info("Attendance Device Ended With Error...");
                        Log::info("Error Cause : ". $exception->getMessage());
                    }
                }

                // checking log loop counter
                if($attendanceLoopCounter>0){
                    $attendanceDeviceLog->access_id = $myLastAccessId;
                    $attendanceDeviceLog->status = 1;
                    $attendanceDeviceLog->count = $attendanceLoopCounter;
                    // update attendance log
                    if($attendanceDeviceLog->update()){
                        Log::info("Attendance Device Job Ended...");
                    }else{
                        Log::info("Unable to update attendance device log");
                    }
                }
            }else{
                Log::info("Unable to create attendance device log");
            }
        }else{
            Log::info("Guzzle request Is Empty...");
        }
    }

    private function createAttendanceDeviceLog($data)
    {
        try{
            $attendanceDeviceLog = AttendanceDeviceLog::create($data);
            if(!empty($attendanceDeviceLog))
                return $attendanceDeviceLog;
        }catch (\Exception $e){
            return null;
        }
        return null;
    }
}
