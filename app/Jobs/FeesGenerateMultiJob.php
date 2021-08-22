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
use Modules\Fees\Entities\FeesInvoice;
use Modules\Setting\Entities\AutoSmsModule;

use Modules\Setting\Entities\SmsInstitutionGetway;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\SmsSender;
use Modules\Fees\Http\Controllers\FeesInvoiceController;
// job
use App\Jobs\SendSms;

class FeesGenerateMultiJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $studentIdList;
    protected $fees_id;

    public function __construct($fees_id,$studentIdList)
    {

        // user type for parents, student, teacher, stuff
        // user type 1=teacher
        //user type 2= student
        // user type 3=stuff;
        // user type 4=Parents
        // fees invoice class make object

        $this->studentIdList = $studentIdList;
        $this->fees_id = $fees_id;
        // student id list log
        Log::info("Steudne Id List".implode(",",$this->studentIdList));

        // get message from sms_message table
        $institute_id=session()->get('institute');
        $campus_id=session()->get('campus');
        $smsFees=SmsTemplate::select('message')->where('template_name','FEES_GENERATED')->where('institution_id', $institute_id)->where('campus_id', $campus_id)->first();

        if(!empty($smsFees) && ($smsFees->count()>0)) {
            // get today student attendance List
            $invoiceProfileList = FeesInvoice::where('fees_id', $fees_id)->whereIn('payer_id', $this->studentIdList)->get();
            $studentMsgAtt = array();
            $parentMsgAtt = array();
            $absentStudentArray = array();
            foreach ($invoiceProfileList as $invoice) {
                $message = "";

                //get student information
                $studentProfile = StudentInformation::where('id', $invoice->payer_id)->first();

                //get fees Name
                $feesName = $invoice->fees()->fee_name;
                $fullName = $studentProfile->title . " " . $studentProfile->first_name . " " . $studentProfile->middle_name . " " . $studentProfile->last_name;
                $section_name = $studentProfile->singleEnroll()->section()->section_name;
                $batch_name = $studentProfile->singleEnroll()->batch()->batch_name;

                $date = date("d-m-Y");
                $amount = FeesInvoice::getStudentFeesAmountById($fees_id, $invoice->payer_id);
                // multiple staring replace
                $searchString = array("{fees}", "{name}", "{section}", "{batch}", "{amount}", "{date}");
                $replaceString = array($feesName, $fullName, $section_name, $batch_name, $amount, $date);

                $message = str_replace($searchString, $replaceString, $smsFees->message);


                //save message sms_message table
                $message_id = $this->setSmsMessage($message);
                $studentMsgAtt[$invoice->payer_id] = $message_id;
                $parent_id = $this->getParent($invoice->payer_id);
                if ($parent_id != 0)
                    $parentMsgAtt[$parent_id] = $message_id;
            }

            // gt auto setting modules user_type array by autosmsModules
//        $autoSmsSettingProfile= AutoSmsSetting::where('auto_sms_module_id',1)->first();


            $institute_id = session()->get('institute');
            $campus_id = session()->get('campus');
            $feesSms = AutoSmsModule::where('status_code', "FEES")->where('ins_id', $institute_id)->where('campus_id', $campus_id)->first();
            $autoSmsSettingProfile = AutoSmsSetting::where('auto_sms_module_id', $feesSms->id)->first();


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
            $studentList=StudentInformation::select('id','phone')->whereIn('id',$this->studentIdList)->get();

            // insert data sms log
            $this->insertSmsLogData($studentList,"phone", $arrayData['s'],2);


        }

        if(array_key_exists("p",$arrayData)) {

            $parents=StudentParent::select('gud_id')->whereIn('std_id',$this->studentIdList)->where('is_emergency',1)->get();
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
        $sendSms->sendSingleSms($batch_count,$smsApiPath,$smsMessage="multipleFeesSms",$smsSender_id);

    }





}
