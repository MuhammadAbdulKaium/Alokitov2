<?php
namespace Modules\Academics\Observers;

use Illuminate\Support\Facades\Log;
use Modules\Academics\Entities\AttendanceUploadAbsent;
use Modules\Student\Entities\StudentInformation;
use App\Http\Controllers\SmsSender;
use Modules\Setting\Entities\AttendanceSmsSetting;

class DeviceAbsentObserver
{
    private $attendanceUploadAbsent;

    public function __construct(AttendanceUploadAbsent $attendanceUploadAbsent, StudentInformation $studentInformation ) {
        $this->attendanceUploadAbsent  = $attendanceUploadAbsent;
    }

//    public function created(AttendanceUploadAbsent $attendanceUploadAbsent) {
//        Log::info('attendance absent observer');
//        Log::info('ObserVer STD ID'.$attendanceUploadAbsent->std_id);
//
////        // check send absent sms setting
////        $absentProfileSMS= AttendanceSmsSetting::where('attendance_medium','DEVICE')
////                             ->where('attendance_type','ABSENT')
////                             ->where('institute_id',$attendanceUploadAbsent->institute)
////                             ->where('campus_id',$attendanceUploadAbsent->campus)
////                            ->first();
////
////        if($attendanceUploadAbsent->is_device==1 && !empty($absentProfileSMS)){
////            $smsSenderObj= new SmsSender;
////            $smsSenderObj->device_absent_job($attendanceUploadAbsent->std_id,$attendanceUploadAbsent->institute,$attendanceUploadAbsent->campus);
////        }
//    }


}
