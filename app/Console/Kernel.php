<?php

namespace App\Console;

use App\Jobs\AbsentScheduleJob;
use App\Jobs\AttendanceDeviceJob;
use App\Jobs\EmployeeAttendanceJob;
use App\Jobs\StudentAttendanceJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use DB;
use Modules\Setting\Entities\AttendanceSmsSetting;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
        //'App\Console\Commands\HappyBirthday'
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //$schedule->command('sms:birthday')->dailyAt('08:00');
        $schedule->job(new AttendanceDeviceJob())->everyFiveMinutes();

        // $schedule->job(new StudentAttendanceJob())->everyFiveMinutes();
        // $schedule->job(new EmployeeAttendanceJob())->everyFiveMinutes();
//        $schedule->job(new AbsentScheduleJob())->everyMinute();
//        $absentScheduleList=$this->absetSMSSettingList();
//        if(!empty($absentScheduleList)){
//            foreach ($absentScheduleList as $absentSchedule){
//                $schedule->job(new AbsentDeviceJob())->dailyAt($absentSchedule->set_time);
//            }
//        }



    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
        $this->load(__DIR__.'/Commands');
    }

    public function absetSMSSettingList(){
        return AttendanceSmsSetting::where('attendance_medium','DEVICE')
                                ->where('attendance_type','ABSENT')
            ->get();
    }
}
