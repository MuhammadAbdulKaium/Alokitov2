<?php

namespace Modules\Academics\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\In;
use Modules\Student\Entities\StudentProfileView;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Academics\Entities\AttendanceUpload;
use Modules\Academics\Entities\AttendanceUploadAbsent;
use Modules\Academics\Entities\AttendanceUploadHistory;
use Modules\Student\Entities\StudentInformation;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Fees\Http\Controllers\AttendanceFineController;
use Modules\Setting\Entities\AttendanceFine;
use Modules\Academics\Http\Controllers\AttendanceController;
use Modules\Student\Entities\StudentAttendanceFine;
use Modules\Employee\Http\Controllers\NationalHolidayController;
use Modules\Employee\Http\Controllers\WeekOffDayController;
use Modules\Student\Http\Controllers\reports\StudentAttendanceReportController;
use GuzzleHttp\Client;
use Modules\Setting\Entities\Institute;
use App\Http\Controllers\SmsSender;

class AttendanceUploadController extends Controller
{
    private $carbon;
    private $academicHelper;
    private $attendanceUpload;
    private $attendanceUploadHistory;
    private $studentInformation;
    private $studentProfileView;
    private $attendanceFineController;
    private $attendanceUploadAbsent;
    private $attendanceFine;
    private $attendanceController;
    private $studentAttendanceFine;
    private $holidayController;
    private $weekOffDayController;
    private $attendanceReportController;
    private $institute;
    private $smsSender;

    // constructor
    public function __construct(AttendanceUpload $attendanceUpload, SmsSender $smsSender, Institute $institute, StudentAttendanceFine $studentAttendanceFine, StudentInformation $studentInformation, AcademicHelper $academicHelper, StudentProfileView $studentProfileView, AttendanceUploadHistory $attendanceUploadHistory, Carbon $carbon, AttendanceFineController $attendanceFineController, AttendanceUploadAbsent $attendanceUploadAbsent, AttendanceFine $attendanceFine, AttendanceController $attendanceController, NationalHolidayController $holidayController, WeekOffDayController $weekOffDayController, StudentAttendanceReportController $attendanceReportController)
    {

        $this->carbon  = $carbon;
        $this->attendanceUpload  = $attendanceUpload;
        $this->attendanceUploadHistory  = $attendanceUploadHistory;
        $this->studentInformation  = $studentInformation;
        $this->academicHelper  = $academicHelper;
        $this->studentProfileView  = $studentProfileView;
        $this->attendanceFineController  = $attendanceFineController;
        $this->attendanceUploadAbsent  = $attendanceUploadAbsent;
        $this->attendanceFine  = $attendanceFine;
        $this->attendanceController  = $attendanceController;
        $this->studentAttendanceFine  = $studentAttendanceFine;
        $this->holidayController  = $holidayController;
        $this->weekOffDayController  = $weekOffDayController;
        $this->attendanceReportController  = $attendanceReportController;
        $this->institute  = $institute;
        $this->smsSender  = $smsSender;
    }

    // store daily attendance list
    public function storeDailyAttendanceList(Request $request)
    {
//        return $request->all();
        // request details
        $level     = $request->input('academic_level');
        $batch     = $request->input('batch');
        $section   = $request->input('section');
        $date = date('Y-m-d', strtotime($request->input('date')));
        $attendanceList = $request->input('std_list');
        // institute information
        $campusId = $this->academicHelper->getCampus();
        $instituteId = $this->academicHelper->getInstitute();
        $academicYearId = $this->academicHelper->getAcademicYear();
        $fromDate = date('Y-m-d 00:00:00', strtotime($date));
        $toDate = date('Y-m-d 23:59:59', strtotime($date));
        // find attendance setting profile
        $attendanceSettingProfile =  $this->attendanceFine->where(['ins_id'=>$instituteId, 'campus_id'=>$campusId, 'setting_type'=>'PRESENT'])->first();
        // attendance search qry
        $qry = ['level'=>$level, 'batch'=>$batch,  'section'=>$section, 'institute'=>$instituteId, 'campus'=>$campusId, 'academic_year'=>$academicYearId];
        // find today upload attendance
        $todayPresentAttendance =  $this->attendanceUpload->where($qry)->whereBetween('entry_date_time', [$fromDate, $toDate])->limit(1)->get(['id']);
        // find today upload absent attendance
        $todayAbsentAttendance =  $this->attendanceUploadAbsent->where($qry)->where('date', $date)->limit(1)->get(['id']);

        // checking attendance fine setting
        if($attendanceSettingProfile){
            // checking
            //if($todayPresentAttendance->count()==0 AND $todayAbsentAttendance->count()==0){
            // now student attendance list empty checking
            if(!empty($attendanceList) AND count($attendanceList)>0){
                // Start transaction!
                DB::beginTransaction();
                // try to upload attendance list
                try {
                    // attendance fine setting present time
                    $presentTime = $attendanceSettingProfile->form_entry_time;
                    // attendance date time
                    $attendanceDateTime = date('Y-m-d '.$presentTime, strtotime($date));
                    // loop counter
                    $loopCounter = 0;
                    // now student attendance looping
                    foreach ($attendanceList as $stdId=>$stdAttendance){

                        // array to object conversion
                        $stdAttendance =  (object)$stdAttendance;
                        // att_id and previous_att_type
                        $attendanceId = $stdAttendance->att_id;
                        // std gr no
                        $stdGrNo = $stdAttendance->std_gr_no;
                        // attendance type
                        $attType = $stdAttendance->att_type;
                        // std profile maker
                        $stdProfile = (object)['std_id'=>$stdId, 'gr_no'=>$stdGrNo, 'academic_level'=>$level, 'batch'=>$batch, 'section'=>$section,
                            'academic_year'=>$academicYearId, 'card_no'=>null, 'campus'=>$campusId, 'institute'=>$instituteId];

                        // checking attendance id
                        if($attendanceId>0){
                            // checking is updated
                            if($stdAttendance->is_updated==1){
                                // previous_att_type
                                $previousAttendanceType = $stdAttendance->previous_att_type;
                                // checking previous attendance type
                                if($previousAttendanceType==1){
                                    $attendanceProfile = $this->attendanceUpload->find($attendanceId);
                                }else{
                                    $attendFineProfile=$this->studentAttendanceFine->where('attend_id',$attendanceId)->first();
                                    $attendFineProfile->delete();
                                    $attendanceProfile = $this->attendanceUploadAbsent->find($attendanceId);

                                }

                                // now delete attendance profile and checking
                                if($attendanceProfile->delete()){
                                    // checking
                                    if($attType==1 ||  $attType==2){
                                        // present attendance maker
                                        $response = (object)$this->makeUploadPresent($stdProfile, $attendanceDateTime, null);
                                    }else{//
                                        // absent attendance maker
                                        $response = (object)$this->makeUploadAbsent($stdProfile, $attendanceDateTime, null);
                                    }
                                }else{
                                    $response = (object)['status'=>'success', 'msg'=>'Nothing to update'];
                                }
                            }else{
                                $response = (object)['status'=>'success'];
                            }
                        }else{
                            // checking
                            if($attType==1 ||  $attType==2){
                                // present attendance maker
                                $response = (object)$this->makeUploadPresent($stdProfile, $attendanceDateTime, null);
                            }else{//
                                // absent attendance maker
                                $response = (object)$this->makeUploadAbsent($stdProfile, $attendanceDateTime, null);
                            }
                        }

                        // checking response
                        if($response->status=='success'){
                            // loop counter
                            $loopCounter +=1;
                        }
                    }
                    // checking
                    if($loopCounter==count($attendanceList)){

                        //request attendance date
                        $attedanceDate = date('Y-m-d',strtotime($request->input('date')));
                        $automaticSms = $request->input('send_automatic_sms');
                        //get all student array list
                        $getAllStudent = $request->input('std_list');
                        //all student id move to studentIdList variable
                        $studentIdList = array();
                        foreach ($getAllStudent as $key=>$student) {
                            $studentIdList[] = $student['std_id'];
                        }
                        // check condition and send request
                        if ($automaticSms == "1" && !empty($studentIdList)) {
                            $this->smsSender->absent_attendance_job($studentIdList);
                        }
                        // If we reach here, then data is valid and working. Commit the queries!
                        DB::commit();

                        $attendanceControllerResult = (object)$this->attendanceController->getDailyAttendanceStudentList($request);
                        // checking
                        if($attendanceControllerResult->status=='success'){
                            return ['status'=>'success','att_list'=>$attendanceControllerResult->content, 'msg'=>'Attendance List Uploaded'];
                        }else{
                            return ['status'=>'success','att_list'=>null, 'msg'=>'Attendance List Uploaded'];
                        }

                    }else{
                        // Rollback and then redirect back to form with errors
                        DB::rollback();
                        return ['status'=>'failed', 'msg'=>'Unable to Upload Attendance List'];
                    }
                } catch (ValidationException $e) {
                    // Rollback and then redirect back to form with errors
                    DB::rollback();
                } catch (\Exception $e) {
                    DB::rollback();
                    throw $e;
                }
            }else{
                return ['status'=>'failed', 'msg'=>'Empty Attendance List'];
            }
//            }else{
//                return ['status'=>'failed', 'msg'=>'Attendance Already Uploaded'];
//            }
        }else{
            return ['status'=>'failed', 'msg'=>'Attendance Fine Setting Not Found'];
        }
    }


    public function uploadAttendance(Request $request)
    {
        // upload function
        $attendanceFile = $request->file('attendance_list');
        // institute details
        $campus = $this->academicHelper->getCampus();
        $institute = $this->academicHelper->getInstitute();
        // upload and checking
        if($this->upLoadAttendanceFile($attendanceFile, $institute, $campus)){
            return ['status'=>true, 'msg'=>'Attendance File Uploaded'];
        }else{
            return ['status'=>false, 'msg'=>'Unable to Upload Attendance File'];
        }
    }

    public function storeUploadedAttendance(Request $request)
    {
        // receive input request
        $attendance = (array)$request->input('attendance');
        $attHistoryId = $request->input('att_history_id');
        $start = $request->input('start');
        $end = $request->input('end');
        $currentChunk = $request->input('current_chunk');
        $totalChunk = $request->input('total_chunk');

//        return $request->all();

        // checking
        if(count($attendance)>0 AND $attHistoryId>0){
            // Start transaction!
            DB::beginTransaction();

            // attendance date
            $attendanceDate = null;
            // absent counter
            $absentCounter = 0;
            // absent loop counter
            $absentLoopCounter = 0;

            // loop counter
            $loopCounter = 0;

            // try to upload attendance list
            try {

                //----------  present part ----------------//

                // looping
                for ($i=$start+1; $i<=$end; $i++){
                    // single attendance
                    Log::info($attendance[$i]);
                    $singleAttendance = $attendance[$i];

                    // uploadAttendanceProfile
                    $uploadAttendanceProfile = new $this->attendanceUpload();
                    // input details
                    $uploadAttendanceProfile->h_id = $attHistoryId;
                    $uploadAttendanceProfile->std_id = $singleAttendance['std_id'];
                    $uploadAttendanceProfile->std_gr_no = $singleAttendance['std_gr_no'];
                    $uploadAttendanceProfile->academic_year = $singleAttendance['year'];
                    $uploadAttendanceProfile->level = $singleAttendance['level'];
                    $uploadAttendanceProfile->batch = $singleAttendance['batch'];
                    $uploadAttendanceProfile->section = $singleAttendance['section'];
                    $uploadAttendanceProfile->card_no = $singleAttendance['card_no'];
                    $uploadAttendanceProfile->entry_date_time = $singleAttendance['entry_date_time'];
                    $uploadAttendanceProfile->campus =  $this->academicHelper->getCampus();
                    $uploadAttendanceProfile->institute =  $this->academicHelper->getInstitute();
                    $uploadAttendanceProfileSaved = $uploadAttendanceProfile->save();
                    // checking
                    if($uploadAttendanceProfileSaved){
                        // store std id in the present_std_array_list
                        $presentStdArrayList[$singleAttendance['std_id']] = $singleAttendance['entry_date_time'];
                        // attendance date
                        if($attendanceDate==null) $attendanceDate = $singleAttendance['entry_date_time'];
                        // loop counting
                        $loopCounter +=1;
                    }
                }

                //----------  absent part ----------------//
                // checking chunk


            } catch (ValidationException $e) {
                // Rollback and then redirect back to form with errors
                DB::rollback();
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

            // checking
            Log::info("loopCounter :" .$loopCounter . " count ". count($attendance));
            if($loopCounter==count($attendance)){
                // If we reach here, then data is valid and working. Commit the queries!
                DB::commit();
                $cache_count =1;
                if(Cache::has('attHistoryId_'.$attHistoryId)) {
                    $cache_count = Cache::get('attHistoryId_' . $attHistoryId);
                    $cache_count++;
                }
                Cache::put('attHistoryId_'.$attHistoryId, $cache_count, 22*30);

                if($cache_count==$totalChunk){
                    // checking
                    if($this->updateAttendanceHistoryStatus($attHistoryId)){
                        // upload attendanceList
                        $uploadAttendanceList = (object)$this->uploadAttendanceLIstWithHistoryId($attHistoryId);
                        // checking
                        if($uploadAttendanceList->status=='success'){
                            // attendance list
                            $uploadAttendanceList = (array)$uploadAttendanceList->content;
                            // now find std list
                            $studentList = $this->stdList(null, null, null); // level is null, batch is null, and section is null
                            // checking std list
                            if($studentList->count()>0){

                                // std list looping
                                foreach ($studentList as $index=>$stdProfile){
                                    // checking std in the present list
                                    if(array_key_exists($stdProfile->std_id, $uploadAttendanceList)==true) continue;
                                    // absent counter
                                    $absentCounter += 1;
                                    Log::info("std_id ". $stdProfile->std_id );
                                    // make the student as absent
                                    $uploadAbsentProfile = (object)$this->makeUploadAbsent($stdProfile, $attendanceDate, $attHistoryId);
                                    // checking
                                    if($uploadAbsentProfile->status=='success') $absentLoopCounter += 1;
                                }
                                Cache::forget('attHistoryId_'.$attHistoryId);
                            }else{
                                // statements
                            }
                        }else{
                            // statements
                        }

                    }else{
                        return ['status'=>'failed', 'msg'=>'Attendance History Update Failed failed'];
                    }
                }
                return ['status'=>'success', 'msg'=>'Attendance Uploaded'];
            }else{
                return ['status'=>'failed', 'msg'=>'Attendance Uploading failed'];
            }
        }else{
            return ['status'=>'failed', 'msg'=>'No attendance found'];
        }
    }

    // upload attendance file for future use
    public function upLoadAttendanceFile($attendanceFile, $institute, $campus)
    {
        $fileExtension = $attendanceFile->getClientOriginalExtension();
        $contentFileName = $attendanceFile->getClientOriginalName();
        $contentName   = "att_upload_".date("Ymdhis").mt_rand(1, 9).mt_rand(10, 99).mt_rand(100, 999).mt_rand(1000, 9999).mt_rand(10000, 99999).mt_rand(100000, 999999).".". $fileExtension;
        $destinationPath = 'assets/documents/institute_'.$institute.'/campus_'.$campus.'/attendance_uploads/';
        // now upload (move) the attendance file
        $attendanceUploaded = $attendanceFile->move($destinationPath, $contentName);
        // storing file name to the database
        if ($attendanceUploaded) {
            // attendance document
            $attendanceHistory = new $this->attendanceUploadHistory();
            // storing user document
            $attendanceHistory->name      = $contentName;
            $attendanceHistory->file_name = $contentFileName;
            $attendanceHistory->path      = $destinationPath;
            $attendanceHistory->mime      = $fileExtension;
            $attendanceHistory->uploaded_at = date('Y-m-d H:i:s', strtotime($this->carbon->now('Asia/Dhaka')->toDateTimeString()));
            $attendanceHistory->campus = $campus;
            $attendanceHistory->institute = $institute;
            $attendanceHistorySaved =  $attendanceHistory->save();
            // checking
            if($attendanceHistorySaved){
                return $attendanceHistory;
            }else{
                return null;
            }
        } else {
            return null;
        }

    }


    // uploadedAttendanceReport
    public function uploadedAttendanceReport(Request $request)
    {
        $redis_token = json_encode($request->except('_token'));

        // request details
        $requestType = $request->input('request_type', 'view'); // view or pdf
        $reportType = $request->input('report_type'); // PRESENT, ABSENT, LATE_PRESENT or ALL
        $attendanceDate = $request->input('attendance_date');
        $attendanceDateTime = date('Y-m-d 10:44:00', strtotime($attendanceDate));
        $level = $request->input('level_id');
        $batch = $request->input('class_id');
        $section = $request->input('section_id');
        $campusId = $this->academicHelper->getCampus();
        $instituteId = $this->academicHelper->getInstitute();
        $academicYearId = null;

        /*if(Cache::has($redis_token)){
            $file =  Cache::get($redis_token);
            return Storage::get($file);
        }*/

        // std list
        $stdList = $this->stdList($level, $batch, $section);
        // attendance list
        $start = microtime(true);

        $selectedDateAttendanceList = $this->attendanceList($attendanceDate, null, $level, $batch, $section, $academicYearId, $campusId, $instituteId);
        // Execute the query
        $time = microtime(true) - $start;
        Log::info("Time taken for quering database for attendance :" .$time);
        // attendanceArrayList
        $attendanceArrayList = array();

        // std and attendance list empty checking
        if($stdList AND $stdList->count()>0){
            // std list looping
            $start = microtime(true);
            foreach ($stdList as $stdProfile){

                // std enrollment details
                $stdProfileDetails = $this->stdEnrollmentDetails($stdProfile);
                // std attendance checking
                if (array_key_exists($stdProfile->std_id, $selectedDateAttendanceList) == true) {
                    // stdAttendanceDetails
                    $stdAttendanceDetails = $selectedDateAttendanceList[$stdProfile->std_id];
                    // std entry date time
                    $entryDateTime = $stdAttendanceDetails['entry_date_time'];
                    $outDateTime = $stdAttendanceDetails['out_date_time'];
                    // attendance array list
                    $attendanceArrayList[$stdProfile->std_id] = $this->attendanceFineController->check_attendance_type($entryDateTime, $outDateTime, $stdProfileDetails,$attendanceDate);
                } else {
                    $attendanceArrayList[$stdProfile->std_id] = [
                        'att_type' => 'ABSENT',
                        'att_date' => $attendanceDate,
                        'entry_date_time' => 'N/A',
                        'out_date_time' => 'N/A',
                        'std_profile' => $stdProfileDetails,
                    ];
                }
            }

            $time = microtime(true) - $start;
            Log::info("Time taken for making dataset of stuent attendace :" .$time);
            // institute information
            $instituteInfo = $this->academicHelper->getInstituteProfile();
            // share all variables with the view
            view()->share(compact('attendanceArrayList', 'reportType', 'requestType', 'instituteInfo'));
            // checking requestType (view or download)
            if($requestType=='pdf'){
                // generate pdf
                // $pdf = App::make('dompdf.wrapper');
                // $pdf->loadView('academics::manage-attendance.reports.report-upload-list-report')->setPaper('a4', 'portrait');
                // return $pdf->stream();
                // return $pdf->download('upload_attendance_report.pdf');

                $html = view('academics::manage-attendance.reports.report-upload-list-report', compact('attendanceArrayList', 'reportType', 'requestType', 'instituteInfo'));
                $pdf = App::make('snappy.pdf.wrapper');
                $pdf->loadHTML($html);
                return $pdf->inline();

            }elseif($requestType=='xlsx'){
                //generate excel
                Excel::create('upload_attendance_report', function ($excel) {
                    $excel->sheet('upload_attendance_report', function ($sheet) {
                        // Font family
                        $sheet->setFontFamily('Comic Sans MS');
                        // Set font with ->setStyle()
                        $sheet->setStyle(array('font' => array('name'=>'Calibri','size' => 12)));
                        // cell formatting
                        $sheet->setAutoSize(true);
                        // Set all margins
                        $sheet->setPageMargin(0.25);
                        // mergeCell
                        // $sheet->mergeCells(['C3:D1', 'E1:H1']);
                        $sheet->loadView('academics::manage-attendance.reports.report-upload-list-report');
                    });
                })->download('xlsx');

            }else{
                // return view with variable
                $start = microtime(true);
                $html = view('academics::manage-attendance.modals.upload-list-report');
                $time = microtime(true) - $start;
                Log::info("Time taken for making HTML stuent attendace :" .$time);
                /*$file = $redis_token.'_manage-attendance.modals.upload-list-report.html';
                Storage::put($file, $html);
                Cache::put($redis_token, $file, 22*60);*/
                return $html;
                //return view('academics::manage-attendance.modals.upload-list-report');
            }
        }else{
            // return view with variable
            $html = view('academics::manage-attendance.modals.upload-list-report', compact('attendanceArrayList', 'reportType'));
            Log::info("HTML is returned and updated cache 2");
            Cache::put($redis_token, json_encode($html), 22*60);
            return $html;
        }
    }


    // find std list
    public function stdList($level, $batch, $section)
    {
        // qry
        $qry = [
//            'academic_year'=>$this->academicHelper->getAcademicYear(),
            'campus'=>$this->academicHelper->getCampus(),
            'institute'=>$this->academicHelper->getInstitute()
        ];

        // checking
        if($level) $qry['academic_level']=$level;
        if($batch) $qry['batch']=$batch;
        if($section) $qry['section']=$section;

        // find std list
        return $stdList = $this->studentProfileView->where($qry)->orderByRaw('LENGTH(gr_no) asc')->orderBy('gr_no', 'asc')->get();
    }

    // find attendance list
    public function attendanceList($attendanceFromDate, $attendanceToDate, $level, $batch, $section, $academicYearId, $campusId, $instituteId)
    {
        // attendance date time
        $fromDateTime = date('Y-m-d 00:00:00', strtotime($attendanceFromDate));
        // checking
        if($attendanceToDate==null){
            $toDateTime = date('Y-m-d 23:59:59', strtotime($attendanceFromDate));
        }else{
            $toDateTime = date('Y-m-d 23:59:59', strtotime($attendanceToDate));
        }

        // qry
        $qry = ['campus'=>$campusId,'institute'=>$instituteId];
        // checking
        if($level) $qry['level']=$level;
        if($batch) $qry['batch']=$batch;
        if($section) $qry['section']=$section;
        if($academicYearId) $qry['academic_year']=$academicYearId;
        // find attendance list
        $attendanceList = $this->attendanceUpload->where($qry)->whereBetween('entry_date_time', array($fromDateTime, $toDateTime))->get();
        /*if(Cache::has('attendance_'.json_encode($qry))){
            Log::info(111);
            return $attendanceList = Cache::get('attendance_'.json_encode($qry));
        }

        else{
            Log::info(222);
            $attendanceList = $this->attendanceUpload->where($qry)->whereBetween('entry_date_time', array($fromDateTime, $toDateTime))->get();
        }*/

        // attendance array list
        $attendanceArrayList = array();
        // checking
        if($attendanceList->count()>0){
            // checking for attendance array list type
            if(($attendanceToDate==null)){
                // looping
                foreach ($attendanceList as $attendance){
                    // store attendance to the array list
                    $attendanceArrayList[$attendance->std_id] = [
                        'std_id'=> $attendance->std_id,
                        'std_gr_no'=> $attendance->std_gr_no,
                        'card_no'=> $attendance->card_no,
                        'atd_date'=> $attendance->entry_date_time?(date('Y-m-d', strtotime($attendance->entry_date_time))):'-',
                        'entry_date_time'=> $attendance->entry_date_time?(date('h:i:s a', strtotime($attendance->entry_date_time))):'-',
                        'out_date_time'=> $attendance->out_date_time?(date('h:i:s a', strtotime($attendance->out_date_time))):'-',
                    ];
                }
            }else{
                // looping
                foreach ($attendanceList as $attendance){
                    // attendance date
                    $atdDate = $attendance->entry_date_time?(date('Y-m-d', strtotime($attendance->entry_date_time))):'-';
                    // store attendance to the array list
                    $attendanceArrayList[$atdDate][$attendance->std_id] = [
                        'std_id'=> $attendance->std_id,
                        'std_gr_no'=> $attendance->std_gr_no,
                        'card_no'=> $attendance->card_no,
                        'entry_date_time'=> $attendance->entry_date_time?(date('h:i:s a', strtotime($attendance->entry_date_time))):'-',
                        'out_date_time'=> $attendance->out_date_time?(date('h:i:s a', strtotime($attendance->out_date_time))):'-',
                    ];
                }
            }

            // return attendance array list
            Cache::put('attendance_'.json_encode($qry), $attendanceArrayList, 22*60);
            return $attendanceArrayList;
        }else{
            // return false
            return $attendanceArrayList;
        }
    }

    /**
     * @param $stdProfile
     * @return object
     */
    public function stdEnrollmentDetails($stdProfile)
    {
        // Std Enrollment details
        if(Cache::has(json_encode($stdProfile)))
            return Cache::get(json_encode($stdProfile));
        $stdEnrollment = $stdProfile->enroll();
        $level = $stdEnrollment->level();
        $section = $stdEnrollment->section();
        $year = $stdEnrollment->academicsYear();
        $batch = $stdEnrollment->batch();
        $division = $batch->division();
        // checking
        if($division){
            $batchName = $batch->batch_name.' - '.$division->name;
        }else{
            $batchName = $batch->batch_name;
        }

        // std profile details
        $stdProfileDetails = (object)[
            'id' => $stdProfile->std_id,
            'gr_no' => $stdEnrollment->gr_no,
            'name' => $stdProfile->first_name . " " . $stdProfile->middle_name . " " . $stdProfile->last_name,
            'enroll' =>$level->level_name." (".$batchName.", ".$section->section_name.")",
            'year' => $year->id,
            'year_name' => $year->year_name,
            'level' => $level->id,
            'level_name' => $level->level_name,
            'batch' => $batch->id,
            'batch_name' => $batch->batch_name,
            'section' => $section->id,
            'section_name' => $section->section_name,
        ];
        // return stdProfileDetails
        Cache::put(json_encode($stdProfile), $stdProfileDetails, 22*60);
        return $stdProfileDetails;
    }


    public function updateAttendanceHistoryStatus($Id)
    {
        // find attendance history profile
        if($attendanceHistoryProfile = $this->attendanceUploadHistory->find($Id)){
            // update attendance history status
            $attendanceHistoryProfile->status = 1;
            // save
            $attendanceHistoryProfileSaved = $attendanceHistoryProfile->save();
            // checking
            if ($attendanceHistoryProfileSaved) {
                return true;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }


    // return daily attendance report to show in the dashboard
    public function dailyAttendanceReport() {
        // institute and campus details
        $campusId = $this->academicHelper->getCampus();
        $instituteId = $this->academicHelper->getInstitute();
        $academicYearId = $this->academicHelper->getAcademicYear();
        // return daily attendance report
        return $this->dailyAttendanceCounter($campusId, $instituteId, $academicYearId);
    }


    // return daily attendance report with api request
    public function apiDailyAttendanceReport($instituteId, $campusId) {
        // checking
        if($this->academicHelper->findCampusWithInstId($campusId, $instituteId)){
            // academic year
            $academicYearProfile = $this->academicHelper->findInstituteAcademicYear($instituteId, $campusId);
            // return daily attendance report
            return $this->dailyAttendanceCounter($campusId, $instituteId, $academicYearProfile->id);
        }else{
            // return daily attendance report
            return ['status'=>'failed', 'msg'=>'Invalid Campus or Institute ID'];
        }
    }


    /**
     * @param $campusId
     * @param $instituteId
     * @param $academicYearId
     * @return array
     */
    public function dailyAttendanceCounter($campusId, $instituteId, $academicYearId) {
        // today's date
        $todayDate = Carbon::today()->toDateString();
        // find today's attendance
        $attendanceList = $this->attendanceList($todayDate, null, null, null, null, $academicYearId, $campusId, $instituteId);
        // find campus-institute student list
        $studentList = $this->studentProfileView->where(['campus' => $campusId, 'institute' => $instituteId])->get();
        // checking student list
        if ($studentList->count() > 0) {
            // attendance details
            $totalStd = 0;
            $totalPresent = 0;
            $totalAbsent = 0;

            $maleStd = 0;
            $malePresent = 0;
            $maleAbsent = 0;

            $femaleStd = 0;
            $femalePresent = 0;
            $femaleAbsent = 0;

            // std list looping
            foreach ($studentList as $stdProfile) {
                // std id and gender
                $stdId = $stdProfile->std_id;
                $stdGender = $stdProfile->gender;
                // attendance type
                $attendanceType = 0;
                // checking stdId in attendance list
                if (array_key_exists($stdId, $attendanceList)) $attendanceType = 1;
                // attendance list
                $totalStd += 1;
                if ($attendanceType == 0) $totalAbsent += 1;
                if ($attendanceType == 1) $totalPresent += 1;

                // male or female checking
                if ($stdGender == 'Male') {
                    // total count
                    $maleStd += 1;
                    // checking
                    if ($attendanceType == 0) $maleAbsent += 1;
                    if ($attendanceType == 1) $malePresent += 1;
                } else {
                    // total count
                    $femaleStd += 1;
                    // checking
                    if ($attendanceType == 0) $femaleAbsent += 1;
                    if ($attendanceType == 1) $femalePresent += 1;
                }
            }

            // precision count
            $precision = 3;
            return $attendanceInfo = [
                'status' => 'success',
                'institute_id' => $instituteId,
                'campus_id' => $campusId,
                'academic_year_id' => $academicYearId,
                'total_std' => $totalStd,
                'total_male_std' => $maleStd,
                'total_female_std' => $femaleStd,
                'total_present_std' => $totalPresent,
                'total_absent_std' => $totalAbsent,
                'male_std_present' => $malePresent,
                'male_std_absent' => $maleAbsent,
                'female_std_present' => $femalePresent,
                'female_std_absent' => $femaleAbsent,
                'total_present_percentage' => $totalStd > 0 ? (substr(number_format(($totalPresent * 100 / $totalStd), $precision + 1, '.', ''), 0, -1)) : 0.00,
                'total_absent_percentage' => $totalStd > 0 ? (substr(number_format((100 - ($totalPresent * 100 / $totalStd)), $precision + 1, '.', ''), 0, -1)) : 0.00,
                'male_present_percentage' => $maleStd > 0 ? (substr(number_format(($malePresent * 100 / $maleStd), $precision + 1, '.', ''), 0, -1)) : 0.00,
                'male_absent_percentage' => $maleStd > 0 ? (substr(number_format((100 - ($malePresent * 100 / $maleStd)), $precision + 1, '.', ''), 0, -1)) : 0.00,
                'female_present_percentage' => $femaleStd > 0 ? (substr(number_format(($femalePresent * 100 / $femaleStd), $precision + 1, '.', ''), 0, -1)) : 0.000,
                'female_absent_percentage' => $femaleStd > 0 ? (substr(number_format((100 - ($femalePresent * 100 / $femaleStd)), $precision + 1, '.', ''), 0, -1)) : 0.00
            ];
        } else {
            // return
            return ['status' => 'failed', 'std_count'=>$studentList->count(), 'institute_id'=>$instituteId,
                'campus_id'=>$campusId, 'msg'=>'No student(s) found'
            ];
        }
    }

    /**
     * @param $attHistoryId
     * @return mixed|array
     */
    public function uploadAttendanceLIstWithHistoryId($attHistoryId)
    {
        // attendance list
        $attendanceList = $this->attendanceUpload->where(['h_id' => $attHistoryId])->get(['std_id']);
        Log::info("Total uploaded student : " . $attendanceList->count());
        // checking attendance list
        if ($attendanceList->count() > 0) {
            // attendance array list
            $attendanceArrayList = array();
            // attendance list looping
            foreach ($attendanceList as $attendance) {
                $attendanceArrayList[$attendance->std_id] = $attHistoryId;
            }
            // return attendance list
            return ['status'=>'success', 'msg'=>'Attendance list found', 'content'=>$attendanceArrayList];
        }else{
            // return msg
            return ['status'=>'failed', 'msg'=>'No Attendance Upload records found'];
        }

    }

    /**
     * @param $stdProfile
     * @param $attendanceDate
     * @return mixed
     */
    public function makeUploadPresent($stdProfile, $attendanceDate, $attHistoryId)
    {
        // checking stdProfile
        if(!empty($stdProfile) AND $stdProfile != null AND !empty($attendanceDate)){
            // object conversion
            $stdProfile = (object)$stdProfile;
            // uploadAttendanceProfile
            $uploadAttendanceProfile = new $this->attendanceUpload();
            // input details
            $uploadAttendanceProfile->h_id = $attHistoryId;
            $uploadAttendanceProfile->std_id = $stdProfile->std_id;
            $uploadAttendanceProfile->std_gr_no = $stdProfile->gr_no;
            $uploadAttendanceProfile->academic_year = $stdProfile->academic_year;
            $uploadAttendanceProfile->level = $stdProfile->academic_level;
            $uploadAttendanceProfile->batch = $stdProfile->batch;
            $uploadAttendanceProfile->section = $stdProfile->section;
            $uploadAttendanceProfile->card_no = $stdProfile->card_no;
            $uploadAttendanceProfile->entry_date_time =  date('Y-m-d H:i:s', strtotime($attendanceDate));
            $uploadAttendanceProfile->campus = $stdProfile->campus;
            $uploadAttendanceProfile->institute = $stdProfile->institute;
            // saving upload attendance and checking
            if($uploadAttendanceProfile->save()){
                return ['status'=>'success', 'id'=>$uploadAttendanceProfile->id, 'msg'=>'Attendance Upload Present Submitted'];
            }else{
                return ['status'=>'failed', 'msg'=>'Unable to Submit Attendance Upload Absent'];
            }
        }else{
            return ['status'=>'failed', 'msg'=>'Invalid Input Data'];
        }
    }


    /**
     * @param $stdProfile
     * @param $attendanceDate
     * @return mixed
     */
    public function makeUploadAbsent($stdProfile, $attendanceDate, $attHistoryId)
    {

        // academic info
        $campusId = $this->academicHelper->getCampus();
        $instituteId = $this->academicHelper->getInstitute();
        $academicYearId = $this->academicHelper->getAcademicYear();

        // get attendance setting fine amount
        $absentSettingFineProfile=$this->attendanceFine->where('ins_id',$instituteId)->where('campus_id',$campusId)->where('setting_type', 'ABSENT')->first();

        // checking stdProfile
        if (!empty($stdProfile) AND $stdProfile != null AND !empty($attendanceDate)) {
            // object conversion
            $stdProfile = (object)$stdProfile;

            // now store the student absent information
            $uploadAbsentProfile = new $this->attendanceUploadAbsent();
            // absent details
            $uploadAbsentProfile->h_id = $attHistoryId;
            $uploadAbsentProfile->std_id = $stdProfile->std_id;
            $uploadAbsentProfile->std_gr_no = $stdProfile->gr_no;
            $uploadAbsentProfile->academic_year = $stdProfile->academic_year;
            $uploadAbsentProfile->level = $stdProfile->academic_level;
            $uploadAbsentProfile->batch = $stdProfile->batch;
            $uploadAbsentProfile->section = $stdProfile->section;
            $uploadAbsentProfile->card_no = $stdProfile->card_no;
            $uploadAbsentProfile->date = date('Y-m-d', strtotime($attendanceDate));
            $uploadAbsentProfile->campus = $stdProfile->campus;
            $uploadAbsentProfile->institute = $stdProfile->institute;

            if ($uploadAbsentProfile->save()) {

                $studentAttendanceFine = new $this->studentAttendanceFine;
                $studentAttendanceFine->academic_year = $academicYearId;
                $studentAttendanceFine->attend_id = $uploadAbsentProfile->id;
                $studentAttendanceFine->ins_id = $instituteId;
                $studentAttendanceFine->campus_id = $campusId;
                $studentAttendanceFine->std_id = $stdProfile->std_id;
                $studentAttendanceFine->date = date('Y-m-d', strtotime($attendanceDate));
                $studentAttendanceFine->fine_amount = $absentSettingFineProfile->amount;
                $studentAttendanceFine->save();

                return ['status' => 'success', 'id' => $uploadAbsentProfile->id, 'msg' => 'Attendance Upload Absent Submitted'];
            } else {
                return ['status' => 'failed', 'msg' => 'Unable to Submit Attendance Upload Absent'];
            }
        } else {
            return ['status' => 'failed', 'msg' => 'Invalid Input Data'];
        }
    }



    // get class section std monthly attendance report
    public function downloadMonthlyAttendanceReport(Request $request)
    {
//        return $request;
        // request details
        $level = $request->input('academic_level');
        $batch = $request->input('batch');
        $batchName = $request->input('batch_name');
        $section = $request->input('section');
        $sectionName = $request->input('section_name');
        $month = $request->input('month');
        $year = $request->input('year');
        $reportType = $request->input('doc_type'); // pdf, xlsx

        // attendance array list
        $attendanceArrayList = array();
        // find batch section std list
        $stdList = $this->stdList($level, $batch, $section);
        $academicYear = $this->academicHelper->getAcademicYearProfile();
        // institute details
        $campus = $this->academicHelper->getCampus();
        $institute = $this->academicHelper->getInstitute();
        // class section dept id
        $stdDeptId = $this->attendanceReportController->findStdDepartment($level, $batch, null, $campus, $institute);

        // find current month attendance
        $attendanceList = $this->attendanceUpload->where(['level'=>$level, 'batch'=>$batch, 'section'=>$section, 'academic_year'=>$academicYear->id])
            ->whereMonth('entry_date_time', $month)
            ->whereYear('entry_date_time', $year)
            ->get(['id','std_id','entry_date_time']);

        //  attendance array list maker
        foreach ($attendanceList as $index=>$stdAtdList){
            // attendance date
            $atdDate = date('Y-m-d', strtotime($stdAtdList->entry_date_time));
            // input std attendance details
            $attendanceArrayList[$stdAtdList->std_id][$atdDate] = $stdAtdList->id;
        }

        // institute information
        $instituteInfo = $this->academicHelper->getInstituteProfile();
        $academicHolidayList = $this->holidayController->holidayList($academicYear->id, $campus, $institute);
        $academicWeeKOffDayList = $this->weekOffDayController->weekOffDayList($stdDeptId, $academicYear->id, $campus, $institute);

        // return view with variables
        view()->share(compact('batchName', 'sectionName', 'attendanceArrayList', 'month', 'reportType', 'instituteInfo', 'stdList', 'academicYear', 'academicHolidayList', 'academicWeeKOffDayList','year'));

        // checking report type
        if($reportType=='pdf'){
            // generate pdf
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView('academics::manage-attendance.reports.report-class-section-monthly-attendance')->setPaper('a4', 'landscape');
            return $pdf->stream();
        }else{
            //generate excel
            Excel::create('upload_attendance_report', function ($excel) {
                $excel->sheet('upload_attendance_report', function ($sheet) {
                    // Font family
                    $sheet->setFontFamily('Comic Sans MS');
                    // Set font with ->setStyle()
                    $sheet->setStyle(array('font' => array('name'=>'Calibri','size' => 12)));
                    // cell formatting
                    $sheet->setAutoSize(true);
                    // Set all margins
                    $sheet->setPageMargin(0.25);
                    // mergeCell
                    // $sheet->mergeCells(['C3:D1', 'E1:H1']);
                    $sheet->loadView('academics::manage-attendance.reports.report-class-section-monthly-attendance');
                });
            })->download('xlsx');
        }





    }

    public function uploadAttendanceExcelData(Request $request, $file_id = null){
        // validating all requested input data
//        $validator = Validator::make($request->all(), ['file_id'  => 'required']);
        // storing requesting input data
//        if ($validator->passes()) {
            // request details
            if($file_id == null)
              $fileId = $request->input('file_id');
            else
                $fileId = $file_id;
            // find uploaded file
            $fileProfile = $this->attendanceUploadHistory->find($fileId);
            // institute details
            $institute = $this->academicHelper->getInstituteProfile();
            // campus details
            $campus = $this->academicHelper->getCampus();

            // array for json body request
            $json = [
                'file_path'=> public_path().'/'.$fileProfile->path.$fileProfile->name,
                'file_original_name'=>$fileProfile->file_name,
                'institute_id'=>$institute->id,
                'campus_id' => $campus,
                'file_id' => $fileId
            ];

            // call guzzle http auto request
            $client = new Client();
            // result
            $result = json_decode($client->request('POST', 'http://localhost:5000/attendance/upload', ['json' => $json])->getBody()->getContents());
            // checking
            if($result->success){

                // return
                return ['status'=>'success', 'file_id'=>$fileProfile->id, 'msg'=>$result->message];
            }else{
                // return
                return ['status'=>'failed', 'msg'=>$result->message];
            }
//        }else{
//            // return
//            return ['status'=>'failed', 'msg'=>'Invalid Information provided !!!'];
//        }
    }


    // Device Realtime Attendnace here


    public function  dailyAttendanceReportByDevice(){

        // institute and campus details
        $campusId = $this->academicHelper->getCampus();
        $instituteId = $this->academicHelper->getInstitute();
        $academicYearId = $this->academicHelper->getAcademicYear();
        // return daily attendance report
        return $this->dailyAttendanceCounterByDevice($campusId, $instituteId, $academicYearId);

    }


    public function dailyAttendanceCounterByDevice($campusId, $instituteId, $academicYearId) {
        // today's date
        $todayDate = Carbon::today()->toDateString();
        // find today's attendance
         $attendanceList = $this->attendanceList($todayDate, null, null, null, null, $academicYearId, $campusId, $instituteId);

        // json data
        $json = array();
        $json["operation"] = "fetch_log";
        $json["auth_user"] = "iftekharhossain";
        $json["auth_code"] = "8gfd225cecs764567a342kmyfd398u5";
        $json["start_date"] = date('Y-m-d');
        $json["start_time"] = "00:00:01";
        $json["end_date"] = date('Y-m-d');
        $json["end_time"] = "23:59:59";

        //   return $json;

        //GuzzleHttp\Client
        $client = new Client();
        // guzzle client request

         $attendanceListDevice = json_decode($client->request('POST', 'https://rumytechnologies.com/rams/json_api', ['json' => $json])->getBody()->getContents())->log;


//        $attendanceListDevice = json_decode(file_get_contents('http://localhost/test/test.json'), true);

        // get instituted id by attendance device name

        $deviceName=$this->academicHelper->getInstituteProfile()->device_name;

        $attendanceList=array();
         foreach($attendanceListDevice as $key=>$attend) {
                if($attend->unit_name==$deviceName) {
//                    s_id
                    $stdId=str_replace('s_', '', $attend->registration_id);
                    $attendanceList[$stdId]['std_id']=$stdId;
                }
        }
//        return $studentAttendanceList;


        // find campus-institute student list
        $studentList = $this->studentProfileView->where(['campus' => $campusId, 'institute' => $instituteId, 'academic_year' => $academicYearId])->get();
        // checking student list
        if ($studentList->count() > 0) {
            // attendance details
            $totalStd = 0;
            $totalPresent = 0;
            $totalAbsent = 0;

            $maleStd = 0;
            $malePresent = 0;
            $maleAbsent = 0;

            $femaleStd = 0;
            $femalePresent = 0;
            $femaleAbsent = 0;

            // std list looping
            foreach ($studentList as $stdProfile) {
                // std id and gender
                $stdId = $stdProfile->std_id;
                $stdGender = $stdProfile->gender;
                // attendance type
                $attendanceType = 0;
                // checking stdId in attendance list
                if (array_key_exists($stdId, $attendanceList)) $attendanceType = 1;
                // attendance list
                $totalStd += 1;
                if ($attendanceType == 0) $totalAbsent += 1;
                if ($attendanceType == 1) $totalPresent += 1;

                // male or female checking
                if ($stdGender == 'Male') {
                    // total count
                    $maleStd += 1;
                    // checking
                    if ($attendanceType == 0) $maleAbsent += 1;
                    if ($attendanceType == 1) $malePresent += 1;
                } else {
                    // total count
                    $femaleStd += 1;
                    // checking
                    if ($attendanceType == 0) $femaleAbsent += 1;
                    if ($attendanceType == 1) $femalePresent += 1;
                }
            }

            // precision count
            $precision = 3;
            return $attendanceInfo = [
                'status' => 'success',
                'institute_id' => $instituteId,
                'campus_id' => $campusId,
                'academic_year_id' => $academicYearId,
                'total_std' => $totalStd,
                'total_male_std' => $maleStd,
                'total_female_std' => $femaleStd,
                'total_present_std' => $totalPresent,
                'total_absent_std' => $totalAbsent,
                'male_std_present' => $malePresent,
                'male_std_absent' => $maleAbsent,
                'female_std_present' => $femalePresent,
                'female_std_absent' => $femaleAbsent,
                'total_present_percentage' => $totalStd > 0 ? (substr(number_format(($totalPresent * 100 / $totalStd), $precision + 1, '.', ''), 0, -1)) : 0.00,
                'total_absent_percentage' => $totalStd > 0 ? (substr(number_format((100 - ($totalPresent * 100 / $totalStd)), $precision + 1, '.', ''), 0, -1)) : 0.00,
                'male_present_percentage' => $maleStd > 0 ? (substr(number_format(($malePresent * 100 / $maleStd), $precision + 1, '.', ''), 0, -1)) : 0.00,
                'male_absent_percentage' => $maleStd > 0 ? (substr(number_format((100 - ($malePresent * 100 / $maleStd)), $precision + 1, '.', ''), 0, -1)) : 0.00,
                'female_present_percentage' => $femaleStd > 0 ? (substr(number_format(($femalePresent * 100 / $femaleStd), $precision + 1, '.', ''), 0, -1)) : 0.000,
                'female_absent_percentage' => $femaleStd > 0 ? (substr(number_format((100 - ($femalePresent * 100 / $femaleStd)), $precision + 1, '.', ''), 0, -1)) : 0.00
            ];
        } else {
            // return
            return ['status' => 'failed', 'std_count'=>$studentList->count(), 'institute_id'=>$instituteId,
                'campus_id'=>$campusId, 'msg'=>'No student(s) found'
            ];
        }
    }



    // return Device daily attendance report with api request
    public function apiDeviceDailyAttendanceReport($instituteId, $campusId) {
        // checking
        if($this->academicHelper->findCampusWithInstId($campusId, $instituteId)){
            // academic year
            $academicYearProfile = $this->academicHelper->findInstituteAcademicYear($instituteId, $campusId);
            // return daily attendance report
            return $this->dailyAttendanceCounterByDevice($campusId, $instituteId, $academicYearProfile->id);
        }else{
            // return daily attendance report
            return ['status'=>'failed', 'msg'=>'Invalid Campus or Institute ID'];
        }
    }





}
