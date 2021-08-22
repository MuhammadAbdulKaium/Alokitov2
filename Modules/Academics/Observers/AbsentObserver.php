<?php
namespace Modules\Academics\Observers;

use Illuminate\Support\Facades\Log;
use Modules\Academics\Entities\AttendanceUploadAbsent;
use Modules\Student\Entities\StudentInformation;
use App\Http\Controllers\SmsSender;

class AbsentObserver
{
    private $attendanceUploadAbsent;
    private $studentInformation;

    public function __construct(AttendanceUploadAbsent $attendanceUploadAbsent, StudentInformation $studentInformation ) {
        $this->attendanceUploadAbsent              = $attendanceUploadAbsent;
        $this->studentInformation       = $studentInformation;
    }

    public function created(AttendanceUploadAbsent $attendanceUploadAbsent) {

//      // check send sms radio value
       $sms= app('Illuminate\Http\Request')->get('send_automatic_sms');
       if($sms==1) {
           SmsSender::create_absent_job($attendanceUploadAbsent->std_id);
       }


    }



}
