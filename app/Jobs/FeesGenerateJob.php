<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Modules\Fees\Entities\InvoicePayment;
use Modules\Fees\Entities\FeesInvoice;
use Modules\Communication\Entities\SmsTemplate;
use Modules\Student\Entities\StudentInformation;
use Modules\Communication\Entities\SmsMessage;
use Modules\Setting\Entities\AutoSmsSetting;
use Modules\Student\Entities\StudentGuardian;
use Modules\Communication\Entities\SmsBatch;
use Illuminate\Support\Facades\DB;
use Modules\Communication\Entities\SmsLog;
use Modules\Setting\Entities\SmsInstitutionGetway;
use App\Http\Controllers\SmsSender;
use Modules\Student\Entities\StudentParent;
use Modules\Setting\Entities\AutoSmsModule;

class FeesGenerateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $studentId;
    protected $invoiceId;

    public function __construct($studentId,$invoiceId,$amount)
    {
        //

        $this->studentId = $studentId;
        $this->invoiceId = $invoiceId;
        $this->amount = $amount;

        // student id list log
        Log::info("Steudnet Id " .$this->studentId);
        Log::info("Gopal Var 1 ");
        Log::info("Invoice Id".$this->invoiceId);

        $institute_id=session()->get('institute');
        $campus_id=session()->get('campus');
        // get message from sms_message table
        $smsFees=SmsTemplate::select('message')->where('template_name','FEES_GENERATED')->where('institution_id', $institute_id)->where('campus_id', $campus_id)->first();


        if(!empty($smsFees) && ($smsFees->count()>0)) {

            // get student Invoice Paid List
            $invoiceProfile = FeesInvoice::where('id', $invoiceId)->first();

            //get student information
            $studentProfile = StudentInformation::where('id', $this->studentId)->first();

            //get fees Name
            $feesName = $invoiceProfile->fees()->fee_name;
            $fullName = $studentProfile->title . " " . $studentProfile->first_name . " " . $studentProfile->middle_name . " " . $studentProfile->last_name;
            $section_name = $studentProfile->singleEnroll()->section()->section_name;
            $batch_name = $studentProfile->singleEnroll()->batch()->batch_name;

            $date = date("d-m-Y");
            $amount = $this->amount;
            // multiple staring replace
            $searchString = array("{fees}", "{name}", "{section}", "{batch}", "{amount}", "{date}");
            $replaceString = array($feesName, $fullName, $section_name, $batch_name, $amount, $date);

            $message = str_replace($searchString, $replaceString, $smsFees->message);


            //save message sms_message table
            $message_id = $this->setSmsMessage($message);
            $studentMsg = $message_id;
            $parent_id = $this->getParent($this->studentId);

            Log::info("Parent Id" . $parent_id);
            if ($parent_id != 0)
                $parentMsg = $message_id;

            $institute_id = session()->get('institute');
            $campus_id = session()->get('campus');

            $feesSms = AutoSmsModule::where('status_code', "FEES")->where('ins_id', $institute_id)->where('campus_id', $campus_id)->first();
            $autoSmsSettingProfile = AutoSmsSetting::where('auto_sms_module_id', $feesSms->id)->first();


            // get student and parent array
            Log::info("user_tuype" . print_r($autoSmsSettingProfile, true));

//       $autoSmsSettingProfile->user_type=["2",'4','1'];
            $userArray = json_decode($autoSmsSettingProfile->user_type);
            $arrayData = array();
            // get user type array data
            for ($i = 0; $i < count($userArray); $i++) {
                Log::info("user_ " . $userArray[$i]);
                if ($userArray[$i] == "2") {
                    // array for student
                    $arrayData['s'] = $studentMsg;
                }
                if ($userArray[$i] == "4") {
                    // array for parents
                    if ($parent_id != 0) {
                        $arrayData['p'] = $parentMsg;
                    }
                }
            }


//        $arrayData=['s'=>$studentMsg,'p'=>$parentMsg];
//
//
//
            // call to process data function
            $this->processData($arrayData);
//
//                 Log::info("");
        }


    }
//
//    /**
//     * Execute the job.
//     *
//     * @return void
//     */
    public function handle()
    {

//            Log::info("Parents phone number ".implode(", ",$studentPresentAbsentList));


    }
//
//    // get parent id by Student Id
    public function getParent($std_id){

        $parent = StudentParent::where('std_id',$std_id)->where('is_emergency',1)->first();

        if(!empty($parent))
            return $parent->gud_id;
        else
            return 0;
    }
//
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
// process data array function send sms etc
    public  function processData($arrayData){

        if(array_key_exists("s",$arrayData)) {

            // get student id and mobile number
            $student=StudentInformation::select('id','phone')->where('id',$this->studentId)->first();
            // insert data sms log
            $this->insertSmsLogData($student,"phone", $arrayData['s'],2);


        }

        if(array_key_exists("p",$arrayData)) {

            // get  parent id
            $parent=StudentParent::select('gud_id')->where('std_id',$this->studentId)->where('is_emergency',1)->first();
            // insert data sms log
            $parent->gud_id;

            $parentInfo=StudentGuardian::where('id', $parent->gud_id)->first();

            // get parent mobile information


//                  Log::info("Parents Inforatmon".print_r($parentInfo));

            $this->insertSmsLogData($parentInfo,"phone", $arrayData['p'],4);

        }


    }
//
//
    public  function  insertSmsLogData($singleStdOrParent,$phone,$studentOrParrentMsg,$userGroup)
    {
        $institute_id=session()->get('institute');
        $smsbatch = SmsBatch::where('institution_id', $institute_id)->first();
        $batch_count = $smsbatch->batch_count;


        if (!empty($singleStdOrParent->$phone)) {
            Log::info("Mobile Number" . $singleStdOrParent->$phone);
            $smsLog = new SmsLog;
            $smsLog->institution_id =session()->get('institute');
            $smsLog->campus_id =session()->get('campus');
            $smsLog->user_id = $singleStdOrParent->id;
            $smsLog->user_no = $singleStdOrParent->$phone;
            $smsLog->user_group = $userGroup;
            //                $smsLog->message_id = 2;
            $smsLog->message_id = $studentOrParrentMsg;
            $smsLog->delivery_status = 1;
            $smsLog->sms_batch_id = $batch_count;
            $smsLog->save();


            //get institution id
            $institute_id=session()->get('institute');

            //sms batch Count Update status
            SmsBatch::where('institution_id', $institute_id)->update(['batch_count' => $batch_count + 1]);

//         get sms api
            $apiGetway = SmsInstitutionGetway::where('institution_id',$institute_id)->where('status',1)->first();
            Log::info("Api Path Here,".($apiGetway->api_path));
            $smsApiPath= str_replace("\\","",$apiGetway->api_path);
            $smsSender_id= $apiGetway->sender_id;

            // create smsSender Object
            $sendSms=new SmsSender;
            // call sending Single sms Job
            $sendSms->sendSingleSms($batch_count,$smsApiPath,$smsMessage="feesGenerateSingle",$smsSender_id,$institute_id);


        }

    }




}
