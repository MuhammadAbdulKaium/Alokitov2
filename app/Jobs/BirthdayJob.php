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

use Modules\Setting\Entities\SmsInstitutionGetway;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\SmsSender;
use Modules\Setting\Entities\AutoSmsModule;

// job
use App\Jobs\SendSms;

class BirthdayJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $studentIdList;
    protected $instituteId;
    protected $campusId;

    public function __construct($studentIdList, $instituteId, $campusId)
    {

        // user type for parents, student, teacher, stuff
        // user type 1=teacher
        //user type 2= student
        // user type 3=stuff;
        // user type 4=Parents


        $this->studentIdList = $studentIdList;
        $this->instituteId = $instituteId;
        $this->campusId= $campusId;

        // student id list log
        Log::info("Steudne Id List".implode(",",$this->studentIdList));

        // get message from sms_message table
        $smsHappyBirthday=SmsTemplate::select('message')->where('template_name','BIRTHDAY')->where('institution_id',$this->instituteId)->where('campus_id', $this->campusId)->first();

        Log::info("++++++Birthday Message Array----");
        Log::info($smsHappyBirthday);

        if(!empty($smsHappyBirthday) && ($smsHappyBirthday->count()>0)) {

            // get today student attendance List
            $birthdayStudentList = StudentInformation::whereIn('id', $this->studentIdList)->get();

            $studentMsgAtt = array();
            $parentMsgAtt = array();
            $birthdayStudentArray = array();
            foreach ($birthdayStudentList as $student) {
                $message = "";
                //get name, batch, section, attendance-details
                $fullName = $student->title . " " . $student->first_name . " " . $student->middle_name . " " . $student->last_name;
                $date = date("d-m-Y");

                $birthdayStudentArray[] = $student->id;

                // multiple staring replace
                $searchString = array("{name}");
                $replaceString = array($fullName);

                $message = str_replace($searchString, $replaceString, $smsHappyBirthday->message);

                //save message sms_message table
                $message_id = $this->setSmsMessage($message);
                $studentMsgAtt[$student->id] = $message_id;
                $parent_id = $this->getParent($student->id);
                if ($parent_id != 0)
                    $parentMsgAtt[$parent_id] = $message_id;

            }

            $this->birthdayStudentList = $birthdayStudentArray;
            Log::info("Absent Id List" . implode(",", $this->birthdayStudentList));


            // gt auto setting modules user_type array by autosmsModules
            $birthdaySms = AutoSmsModule::where('status_code', "BIRTHDAY")->where('ins_id', $this->instituteId)->where('campus_id', $this->campusId)->first();
            $autoSmsSettingProfile = AutoSmsSetting::where('auto_sms_module_id', $birthdaySms->id)->first();

            // get student and parent array
//        Log::info("user_tuype".print_r($autoSmsSettingProfile));

//       $autoSmsSettingProfile->user_type=["2",'4','1'];
            $userArray = json_decode($autoSmsSettingProfile->user_type);
            Log::info($userArray);
            $arrayData = array();
            // get user type array data
            for ($i = 0; $i < count($userArray); $i++) {
                Log::info("user_ " . $userArray[$i]);
                if ($userArray[$i] == "2") {
                    // array for student
                    $arrayData['s'] = $studentMsgAtt;
                    Log::info("for students");
                }
                if ($userArray[$i] == "4") {
                    // array for parents
                    Log::info("for parents");
                    $arrayData['p'] = $parentMsgAtt;
                }
            }

            Log::info("=============Data Array=================");
            Log::info($arrayData);

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
            $studentList=StudentInformation::select('id','phone')->whereIn('id',$this->studentIdList)->get();

            // insert data sms log
            $this->insertSmsLogData($studentList,"phone", $arrayData['s'],2);


            Log::info("Student List List");
            Log::info($studentList);


        }

        if(array_key_exists("p",$arrayData)) {

            $parents=StudentParent::select('gud_id')->whereIn('std_id',$this->studentIdList)->where('is_emergency',1)->get();
            // insert data sms log
            $parentArray=array();

            foreach ($parents as $parent) {
                $parentArray[]=$parent->gud_id;
            }

            Log::info("Parent List List");
            Log::info($parentArray);

            $parentList=StudentGuardian::whereIn('id', $parentArray)->get();

            // insert data sms log
            $this->insertSmsLogData($parentList,"phone", $arrayData['p'],4);


        }


    }


    public  function  insertSmsLogData($arrayList,$phone,$studentOrParrentMsgAtt,$userGroup)
    {
        //get institution ApI

        // instituted id and campus id wise for loop

        Log::info("===================InstitueId=============");
        Log::info($this->instituteId);

            $smsbatch = SmsBatch::where('institution_id',$this->instituteId)->first();
                $batch_count = $smsbatch->batch_count;

                foreach ($arrayList as $data) {
                    if (!empty($data->$phone)) {
                        $smsLog = new SmsLog;
                        $smsLog->institution_id = $this->instituteId;
                        $smsLog->campus_id = $this->campusId;
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
                $institute_id = session()->get('institute');

                //sms batch Count Update status
                SmsBatch::where('institution_id',$institute_id)->update(['batch_count' => $batch_count + 1]);

                // get sms api
                $apiGetway = SmsInstitutionGetway::where('institution_id', $this->instituteId)->where('status', 1)->first();
                Log::info("Api Path Here," . ($apiGetway->api_path));
                $smsApiPath = str_replace("\\", "", $apiGetway->api_path);
                $smsSender_id = $apiGetway->sender_id;

                // create smsSender Object
                $sendSms = new SmsSender;
                // call sending Single sms Job
                $sendSms->sendSingleSms($batch_count, $smsApiPath, $smsMessage = "birthdaySms", $smsSender_id);

            }



}
