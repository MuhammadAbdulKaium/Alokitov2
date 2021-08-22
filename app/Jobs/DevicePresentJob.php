<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Modules\Academics\Entities\AttendanceUpload;
use Modules\Student\Entities\StudentInformation;
use Modules\Student\Entities\StudentGuardian;
use Modules\Student\Entities\StudentParent;
use Modules\Communication\Entities\SmsLog;
use Modules\Communication\Entities\SmsBatch;
use Modules\Communication\Entities\SmsTemplate;
use Modules\Communication\Entities\SmsMessage;
use Modules\Academics\Entities\StudentAttendance;
use Modules\Academics\Entities\AttendanceUploadAbsent;
use Modules\Setting\Entities\AutoSmsSetting;
use Modules\Setting\Entities\AutoSmsModule;

use Modules\Setting\Entities\SmsInstitutionGetway;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\SmsSender;

// job
use App\Jobs\SendSms;

class DevicePresentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $studentId;
    protected $institute_id;
    protected $campus_id;

    public function __construct($studentId,$institute_id,$campus_id)
    {

        $this->studentId = $studentId;
        $this->institute_id = $institute_id;
        $this->campus_id = $campus_id;
        // student id list log
        Log::info('Institute ID'.$institute_id);
        Log::info('Cmapus ID'.$campus_id);
        Log::info('Device Present Job Start Bro ');

        $smsAttendace=SmsTemplate::select('message')->where('template_name','ATTENDANCE')->where('institution_id', $this->institute_id)->where('campus_id', $this->campus_id)->first();


        if(!empty($smsAttendace) && ($smsAttendace->count()>0)) {
            // get today student attendance List
            $presentStudentProfile = AttendanceUpload::where('std_id', $this->studentId)->first();

            $studentMsgAtt = array();
            $parentMsgAtt = array();
            $absentStudentArray = array();
            $message = "";
            //get name, batch, section, attendance-details
            $fullName = $presentStudentProfile->student()->title . " " . $presentStudentProfile->student()->first_name . " " . $presentStudentProfile->student()->middle_name . " " . $presentStudentProfile->student()->last_name;
            $section_name = $presentStudentProfile->section()->section_name;
            $batch_name = $presentStudentProfile->batch()->batch_name;
            $date = date("d-m-Y");
            // multiple staring replace
            $searchString = array("{attendance}", "{name}", "{section}", "{batch}", "{date}");
            $replaceString = array("Present", $fullName, $section_name, $batch_name, $date);

            $message = str_replace($searchString, $replaceString, $smsAttendace->message);

            //save message sms_message table
            $message_id = $this->setSmsMessage($message);
            $studentMsgAtt[$presentStudentProfile->std_id] = $message_id;
            $parent_id = $this->getParent($presentStudentProfile->std_id);
            if ($parent_id != 0)
                $parentMsgAtt[$parent_id] = $message_id;

            $attendanceSms = AutoSmsModule::where('status_code', "ATTENDANCE")->where('ins_id', $this->institute_id)->where('campus_id', $this->campus_id)->first();
            $autoSmsSettingProfile = AutoSmsSetting::where('auto_sms_module_id', $attendanceSms->id)->first();


            // get student and parent array
//        Log::info("user_tuype".print_r($autoSmsSettingProfile,true));

//       $autoSmsSettingProfile->user_type=["2",'4','1'];
            $userArray = json_decode($autoSmsSettingProfile->user_type);
            $arrayData = array();
            // get user type array data
            for ($i = 0; $i < count($userArray); $i++) {
                Log::info("user_ " . $userArray[$i]);
                if ($userArray[$i] == "2") {
                    // array for student
                    $arrayData['s'] = $studentMsgAtt;
                }
                if ($userArray[$i] == "4") {
                    // array for parents
                    $arrayData['p'] = $parentMsgAtt;
                }
            }

//
//
//
            // call to process data function
            $this->processData($arrayData);
//
//                 Log::info("");
        }


    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

//            Log::info("Parents phone number ".implode(", ",$studentPresentAbsentList));


    }

    // get parent id by Student Id
    public function getParent($std_id){
        $parent = StudentParent::where('std_id',$std_id)->where('is_emergency',1)->first();

        if(!empty($parent))
            return $parent->gud_id;
        else
            return 0;
    }

    // save message sms_message table
    public function setSmsMessage($message){
        $smsMessage = new SmsMessage();
        $smsMessage->message = $message;
        $sendSms = $smsMessage->save();
        //Log::info( print_r($sendSms,true));

        return DB::getPdo()->lastInsertId();
    }


// process data array function send sms etc
    public  function processData($arrayData){
        Log::info('sddsfsdf');

        if(array_key_exists("s",$arrayData)) {

            // get all student id and mobile number
            $studentProfile=StudentInformation::select('id','phone')->where('id',$this->studentId)->first();

//            Log::info( print_r($studentProfile,true));
            // insert data sms log
            $this->insertSmsLogData($studentProfile,"phone", $arrayData['s'],2);

        }

        if(array_key_exists("p",$arrayData)) {
            Log::info('parent');

            // parent emargency id
            $parentIsEmargencyProfile=StudentParent::select('gud_id')->where('std_id',$this->studentId)->where('is_emergency',1)->first();
            $parentPorfile=StudentGuardian::where('id', $parentIsEmargencyProfile->gud_id)->first();
            if(!empty($parentPorfile)) {
                Log::info('parent'.$parentPorfile);
                // insert data sms log
                $this->insertSmsLogData($parentPorfile,"mobile", $arrayData['p'],4);
            }

        }


    }


    public  function  insertSmsLogData($arrayProfile,$phone,$studentOrParrentMsgAtt,$userGroup){

//        Log::info("Sms Log Check");
        //get institution ApI

        $smsbatch = SmsBatch::where('institution_id', $this->institute_id)->first();
        $batch_count = $smsbatch->batch_count;
            if (!empty($arrayProfile->$phone)) {
                $smsLog = new SmsLog;
                $smsLog->institution_id =$this->institute_id;
                $smsLog->campus_id =$this->campus_id;
                $smsLog->user_id = $arrayProfile->id;
                $smsLog->user_no = $arrayProfile->$phone;
                $smsLog->user_group = $userGroup;
                //                $smsLog->message_id = 1;
                $smsLog->message_id = $studentOrParrentMsgAtt[$arrayProfile->id];
                $smsLog->delivery_status = 1;
                $smsLog->sms_batch_id = $batch_count;
                $smsLog->save();
            }

        //sms batch Count Update status
        SmsBatch::where('institution_id', $this->institute_id)->update(['batch_count' => $batch_count + 1]);

        // get sms api
        $apiGetway = SmsInstitutionGetway::where('institution_id',$this->institute_id)->where('status',1)->first();
        Log::info("Api Path Here,".($apiGetway->api_path));
        $smsApiPath= str_replace("\\","",$apiGetway->api_path);
        $smsSender_id= $apiGetway->sender_id;

        // create smsSender Object
        $sendSms=new SmsSender;
        // call sending Single sms Job
        $sendSms->sendSingleSms($batch_count,$smsApiPath,$smsMessage="present",$smsSender_id,$this->institute_id);

        Log::info('Device Present Job End Bro ');

    }




}
