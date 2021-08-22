<?php
namespace Modules\Academics\Observers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Academics\Entities\AttendanceUploadHistory;
use Modules\Student\Entities\StudentInformation;
use App\Http\Controllers\SmsSender;
use Modules\Academics\Http\Controllers\AttendanceUploadController;

class AttendanceUploadObserver
{
    private $attendanceUploadHistory;
    private $attendanceUploadController;

    public function __construct(AttendanceUploadHistory $attendanceUploadHistory, AttendanceUploadController $attendanceUploadController) {
        $this->attendanceUploadHistory   = $attendanceUploadHistory;
        $this->attendanceUploadController   = $attendanceUploadController;
    }

    public function created(AttendanceUploadHistory $attendanceUploadHistory) {

        $file_id=$attendanceUploadHistory->id;
        $request = new Request();
//      // check send sms radio value
//       $fileid= app('Illuminate\Http\Request')->get('file_id');
       if(!empty($file_id)) {
           $this->attendanceUploadController->uploadAttendanceExcelData($request,$file_id);
       } else {
           Log::info('file nai  Log..');
       }

    }



}
