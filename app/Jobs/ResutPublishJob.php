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
use Modules\Setting\Entities\AutoSmsSetting;
use Illuminate\Support\Facades\DB;
use Modules\Setting\Entities\SmsInstitutionGetway;
use App\Http\Controllers\SmsSender;
use Modules\Setting\Entities\AutoSmsModule;
use Modules\Academics\Entities\ExamSummary;

class ResutPublishJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $examStatus;

    public function __construct($examStatus)
    {
        $this->examStatus=$examStatus;

        $institute_id=session()->get('institute');
        $campus_id=session()->get('campus');
        Log::info($institute_id);

        // get message from sms_message table
        $smsResult=SmsTemplate::select('message')->where('template_name','RESULT')->where('institution_id', $institute_id)->where('campus_id', $campus_id)->first();

        $examSummaryList =ExamSummary::where('es_id', $this->examStatus)->orderBy('merit', 'ASC')->get();
//        Log::info('SMS Template '.print_r($examSummaryList));

        if(!empty($smsResult) && ($smsResult->count()>0)) {

            $studentMsgAtt = array();
            $parentMsgAtt = array();

            foreach ($examSummaryList as $examSummary) {
//                Log::info('result summary'. $examSummary->id);

                $studentProfile = StudentInformation::where('id', $examSummary->std_id)->first();

                $examSummaryResult = json_decode($examSummary->result_wa, true);
                $result = '';
                $result .= 'Merit ' . $examSummary->merit_wa;
//        echo dd($examSummaryList['grade']);
//        return $examSummaryList['grade']['1112']['sub_name'];
                foreach ($examSummaryResult['grade'] as $key => $value) {
                    $result .= ', '. $examSummaryResult['grade'][$key]['sub_name'] . ' ' . $examSummaryResult['grade'][$key]['obtained'];
                }

                if (!empty($studentProfile->singleEnroll())) {

//                    Log::info("Student Profile" . $studentProfile);
                    $fullName = $studentProfile->title . " " . $studentProfile->first_name . " " . $studentProfile->middle_name . " " . $studentProfile->last_name;
                    $section_name = $studentProfile->singleEnroll()->section()->section_name;
                    $batch_name = $studentProfile->singleEnroll()->batch()->batch_name;
                    $rollNumber = $studentProfile->singleEnroll()->gr_no;

                    $date = date("d-m-Y");

                    // multiple staring replace
                    $searchString = array("{name}","{roll}", "{section}", "{batch}", "{result}", "{date}");
                    $replaceString = array($fullName,$rollNumber, $section_name, $batch_name, $result, $date);

                    $message = str_replace($searchString, $replaceString, $smsResult->message);
//                    Log::info("Message Log" . $message);

                    $message_id = $this->setSmsMessage($message);
                    $studentMsgAtt[$examSummary->std_id] = $message_id;
                    $parent_id = $this->getParent($examSummary->std_id);
                    if ($parent_id != 0)
                        $parentMsgAtt[$parent_id] = $message_id;
                }
            }

            //save message sms_message table


//        }

//        // gt auto setting modules user_type array by autosmsModules


//        $autoSmsSettingProfile= AutoSmsSetting::where('auto_sms_module_id',3)->first();
            $resultSms = AutoSmsModule::where('status_code', "RESULT")->where('ins_id', $institute_id)->where('campus_id', $campus_id)->first();
            $autoSmsSettingProfile = AutoSmsSetting::where('auto_sms_module_id', $resultSms->id)->first();


            // get student and parent array
//        Log::info("user_tuype".print_r($autoSmsSettingProfile,true));

//       $autoSmsSettingProfile->user_type=["2",'4','1'];
            $userArray = json_decode($autoSmsSettingProfile->user_type);
            $arrayData = array();
            // get user type array data
            for ($i = 0; $i < count($userArray); $i++) {
//                Log::info("user_ " . $userArray[$i]);
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
//            Log::info(print_r($arrayData));
            $this->processData($arrayData);
//

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


    //    // get parent id by Student Id
    public function getParent($std_id){
        $parent = StudentParent::where('std_id',$std_id)->where('is_emergency',1)->first();
        if(!empty($parent))
            return $parent->gud_id;
        else
            return 0;
    }




    // Grade Mark Profile
//    $gradeMarkProfile = $singleGrade->gradeMark();
    // mark Profile
//    $markProfile = (array)json_decode($gradeMarkProfile->marks);
    // assessment and category  id





    /* $replaceString = array($fullName,$section_name,$batch_name,$date);
     $message = str_replace($searchString, $replaceString, $smsAttendace->message);*/



    // Message Log
//                Log::info("Message" .$message);


//    // save message sms_message table
    public function setSmsMessage($message){
        $smsMessage = new SmsMessage();
        $smsMessage->message = $message;
        $sendSms = $smsMessage->save();
        //Log::info( print_r($sendSms,true));

        return DB::getPdo()->lastInsertId();
    }
//
//
//// process data array function send sms etc
    public  function processData($arrayData){
        $studentIdArray=ExamSummary::where('es_id', $this->examStatus)->pluck('std_id')->toArray();

        if(array_key_exists("s",$arrayData)) {

            // get all student id and mobile number
            $studentList=StudentInformation::select('id','phone')->whereIn('id',$studentIdArray)->get();
            // insert data sms log
            $this->insertSmsLogData($studentList,"phone", $arrayData['s'],2);


        }

        if(array_key_exists("p",$arrayData)) {
            Log::info($studentIdArray);

            $parents=StudentParent::select('gud_id')->whereIn('std_id',$studentIdArray)->where('is_emergency',1)->get();
//            Log::info('dddd'.print_r($parents));
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
//
//
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


        $apiGetway = SmsInstitutionGetway::where('institution_id',$institute_id)->where('status',1)->first();
        Log::info("Api Path Here,".($apiGetway->api_path));
        $smsApiPath= str_replace("\\","",$apiGetway->api_path);
        $smsSender_id= $apiGetway->sender_id;

        // create smsSender Object
        $sendSms=new SmsSender;
        // call sending Single sms Job
        $sendSms->sendSingleSms($batch_count,$smsApiPath,$smsMessage="result",$smsSender_id,$institute_id);

    }

}
