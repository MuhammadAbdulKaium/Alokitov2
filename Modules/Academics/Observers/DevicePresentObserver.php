<?php
namespace Modules\Academics\Observers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Modules\Academics\Entities\AttendanceUpload;
use Modules\Student\Entities\StudentInformation;
use App\Http\Controllers\SmsSender;

class DevicePresentObserver
{
    private $attendanceUploadPresent;

    public function __construct(AttendanceUpload $attendanceUploadPresent, StudentInformation $studentInformation ) {
        $this->attendanceUploadPresent  = $attendanceUploadPresent;
    }

    public function created(AttendanceUpload $attendanceUploadPresent) {
        Log::info('attendance present observer');
        Log::info('ObserVer STD ID'.$attendanceUploadPresent->std_id);

        // get the current time
        $current = Carbon::now();
        // current date
        $currentDate = $current->toDateString();
        // attendance date
        $attendanceDate = date('Y-m-d', strtotime($attendanceUploadPresent->entry_date_time));
        // checking attendance
        if(($attendanceUploadPresent->is_device==1) AND ($currentDate==$attendanceDate)){
            $smsSenderObj= new SmsSender;
            $smsSenderObj->device_present_job($attendanceUploadPresent->std_id,$attendanceUploadPresent->institute,$attendanceUploadPresent->campus);
        }
    }


}
