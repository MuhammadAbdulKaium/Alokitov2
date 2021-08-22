<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Modules\Employee\Entities\AttendanceDevice;
use Modules\Employee\Entities\EmployeeAttendance;

class EmployeeAttendanceJob implements ShouldQueue
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
        Log::info('EmployeeAttendanceJob: started');
        $data = array();
        $data["operation"] = "fetch_log";
        $data["auth_code"] = "bh0qfj1gvnpddbcml0tej7z3vaaoosh";

        try{
            $now = Carbon::now();

            $start = $now->subDay(1);
            $access_date = $start->toDateString();
            $attendanceDeviceList = AttendanceDevice::
            select(['registration_id', 'access_date', 'person_type', 'institute_id', 'campus_id'])
                ->selectRaw('min(`access_time`) as access_time')
                ->selectRaw('max(`access_time`) as max_access_time')
                ->where('person_type', 'employee')
                ->where('access_date', $access_date)
                ->orderByRaw('min(access_time)', 'asc')
                ->groupBy(['registration_id', 'person_type', 'access_date','institute_id', 'campus_id'])
                ->havingRaw('min(access_time) >= \'06.00.00\'')
                ->havingRaw('max(access_time) >= \'06.00.00\'')
                ->get();
            if($attendanceDeviceList->count()>0){
                foreach ($attendanceDeviceList as $employeeAttendance){
                    $employeeAttendance = $this->prepareEmployeeAttendanceData($employeeAttendance);
                    //dd($employeeAttendance);
                    EmployeeAttendance::create($employeeAttendance);
                }
                Log::info('EmployeeAttendance Data: inserted');
            }
            else{
                //dd("Attendance Data is Empty!!");
                Log::info('EmployeeAttendanceJob: Attendance Data is Empty!!');
            }
            Log::info('EmployeeAttendanceJob: ended');
        }catch (\Exception $exception){
            Log::info('EmployeeAttendanceJob: error ');
            Log::info('EmployeeAttendanceJob: error log '. $exception->getMessage());
        }


    }

    public function prepareEmployeeAttendanceData($employeeAttendance){
        $data = array(
            'in_date' => $employeeAttendance->access_date,
            'in_time' => $employeeAttendance->access_time,
            'out_date' => $employeeAttendance->access_date,
            'out_time' => $employeeAttendance->max_access_time,
            'company_id' => $employeeAttendance->institute_id,
            'brunch_id' => $employeeAttendance->campus_id,
            'emp_id' => $employeeAttendance->registration_id

        );
        return $data;

    }

}
