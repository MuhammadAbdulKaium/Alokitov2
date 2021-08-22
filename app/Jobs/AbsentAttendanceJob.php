<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Modules\Student\Entities\StudentInformation;
use Modules\Student\Entities\StudentGuardian;
use Modules\Student\Entities\StudentParent;
use Modules\Communication\Entities\SmsLog;
use Modules\Communication\Entities\SmsBatch;
use Modules\Communication\Entities\SmsTemplate;
use Modules\Communication\Entities\SmsMessage;
use Modules\Academics\Entities\StudentAttendance;
use Modules\Setting\Entities\AutoSmsSetting;
use Modules\Setting\Entities\AutoSmsModule;

use Modules\Setting\Entities\SmsInstitutionGetway;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\SmsSender;

// job
use App\Jobs\SendSms;

class AbsentAttendanceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $studentIdList;
    protected $absentStudentList;

    public function __construct($studentIdList)
    {

        // user type for parents, student, teacher, stuff
            // user type 1=teacher
            //user type 2= student
            // user type 3=stuff;
            // user type 4=Parents


        $this->studentIdList = $studentIdList;
        // student id list log
        Log::info("Steudne Id List".implode(",",$this->studentIdList));

        $institute_id=session()->get('institute');
        $campus_id=session()->get('campus');

    // get message from sms_message table
        $smsAttendace=SmsTemplate::select('message')->where('template_name','ATTENDANCE')->where('institution_id', $institute_id)->where('campus_id', $campus_id)->first();

        if(!empty($smsAttendace) && ($smsAttendace->count()>0)) {

            // get today student attendance List
            $attdendanceList = StudentAttendance::where('attendance_date', date('Y-m-d'))->whereIn('student_id', $this->studentIdList)->get();

            $studentMsgAtt = array();
            $parentMsgAtt = array();
            $absentStudentArray = array();
            foreach ($attdendanceList as $attendance) {
                $message = "";
                //get name, batch, section, attendance-details
                $fullName = $attendance->student()->title . " " . $attendance->student()->first_name . " " . $attendance->student()->middle_name . " " . $attendance->student()->last_name;
                $section_name = $attendance->details()->section()->section_name;
                $batch_name = $attendance->details()->batch()->batch_name;
                $date = date("d-m-Y");

                if ($attendance->attendacnce_type == 0) {
                    $absentStudentArray[] = $attendance->student_id;

                    // multiple staring replace
                    $searchString = array("{attendance}", "{name}", "{section}", "{batch}", "{date}");
                    $replaceString = array("Absent", $fullName, $section_name, $batch_name, $date);

                    $message = str_replace($searchString, $replaceString, $smsAttendace->message);

                    //save message sms_message table
                    $message_id = $this->setSmsMessage($message);
                    $studentMsgAtt[$attendance->student_id] = $message_id;
                    $parent_id = $this->getParent($attendance->student_id);
                    if ($parent_id != 0)
                        $parentMsgAtt[$parent_id] = $message_id;
                }

            }

            $this->absentStudentList = $absentStudentArray;
            Log::info("Absent Id List" . implode(",", $this->absentStudentList));


            // gt auto setting modules user_type array by autosmsModules
            $attendanceSms = AutoSmsModule::where('status_code', "ATTENDANCE")->where('ins_id', $institute_id)->where('campus_id', $campus_id)->first();
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


//        $arrayData=['s'=>$studentMsgAtt,'p'=>$parentMsgAtt];
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

        if(array_key_exists("s",$arrayData)) {

            // get all student id and mobile number
            $studentList=StudentInformation::select('id','phone')->whereIn('id',$this->absentStudentList)->get();

         // insert data sms log
            $this->insertSmsLogData($studentList,"phone", $arrayData['s'],2);


        }

        if(array_key_exists("p",$arrayData)) {

            $parents=StudentParent::select('gud_id')->whereIn('std_id',$this->absentStudentList)->where('is_emergency',1)->get();
            // insert data sms log
            $parentArray=array();

            foreach ($parents as $parent) {
                $parentArray[]=$parent->gud_id;
            }

            $parentList=StudentGuardian::whereIn('id', $parentArray)->get();

            // insert data sms log
            $this->insertSmsLogData($parentList,"phone", $arrayData['p'],4);


        }


    }


    public  function  insertSmsLogData($arrayList,$phone,$studentOrParrentMsgAtt,$userGroup){
        //get institution ApI
        $institute_id=session()->get('institute');

        $smsbatch = SmsBatch::where('institution_id', $institute_id)->first();
        $batch_count = $smsbatch->batch_count;

           foreach ($arrayList as $data) {
               if (!empty($data->$phone)) {
                   $smsLog = new SmsLog;
                   $smsLog->institution_id =session()->get('institute');
                   $smsLog->campus_id =session()->get('campus');
                   $smsLog->user_id = $data->id;
                   $smsLog->user_no = $data->$phone;
                   $smsLog->user_group = $userGroup;
    //                $smsLog->message_id = 1;
                   $smsLog->message_id = $studentOrParrentMsgAtt[$data->id];
                   $smsLog->delivery_status = 1;
                   $smsLog->sms_batch_id = $batch_count;
                   $smsLog->save();
           }
         }
        //get institution ApI
        $institute_id=session()->get('institute');

        //sms batch Count Update status
        SmsBatch::where('institution_id', $institute_id)->update(['batch_count' => $batch_count + 1]);

        // get sms api
        $apiGetway = SmsInstitutionGetway::where('institution_id',$institute_id)->where('status',1)->first();
        Log::info("Api Path Here,".($apiGetway->api_path));
        $smsApiPath= str_replace("\\","",$apiGetway->api_path);
        $smsSender_id= $apiGetway->sender_id;

        // create smsSender Object
            $sendSms=new SmsSender;
            // call sending Single sms Job
            $sendSms->sendSingleSms($batch_count,$smsApiPath,$smsMessage="absentattendance",$smsSender_id);

    }





}
