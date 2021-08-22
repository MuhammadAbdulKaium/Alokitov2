<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Communication\Entities\SmsLog;
use Modules\Communication\Entities\SmsStatus;
use App\Http\Controllers\Helpers\AcademicHelper;

use App\User;
use Illuminate\Support\Facades\Log;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;


class SendSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $sms_batch_id;
    protected $smsApi;
    protected $smsSender_id;
    protected $apiPath;
    protected $smsMessage;
    protected $institution_id;
    public function __construct($sms_batch_id,$smsApi,$smsMessage,$smsSender_id,$institution_id)
    {
        $this->sms_batch_id = $sms_batch_id;
        $this->smsApi = $smsApi;
        $this->smsMessage = $smsMessage;
        $this->smsSender_id = $smsSender_id;
        $this->institution_id = $institution_id;
        // check sms Api
        Log::info("====Entered".$this->smsMessage);
        Log::info("====Entered".$this->institution_id);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        Log::info("Sms Message Type ".$this->smsMessage);

        if($this->smsMessage=="forgotpasswordmsg") {
            Log::info("Forget  Sms Section Here  ");
            Log::info("Institute Id".$this->institution_id);
            Log::info("API".$this->smsApi);

            $batch_count = $this->sms_batch_id;
            //get smsLog list by sms_batch_it
            $singleSmsLog = SmsLog::where('sms_batch_id', $batch_count)->where('institution_id',$this->institution_id)->first();

            $apiPath = $this->smsApi . "&contacts=" . $singleSmsLog->user_no."&senderid=" .$this->smsSender_id. "&msg=" . urlencode($singleSmsLog->singleMessage()->message);

            // call guzzle http auto request
            $client = new Client(); //GuzzleHttp\Client
            $result = $client->get($apiPath);
            Log::info("==== Route".$result->getBody());

            Log::info("This is Forgot Password Message ");

        }



        elseif($this->smsMessage=="fees") {

            Log::info("Fees Sms Section Here  ");

            $batch_count = $this->sms_batch_id;
            Log::info("Fees Sms Batch Id".$batch_count);

            //get smsLog list by sms_batch_it
            $singleSmsLog = SmsLog::where('sms_batch_id', $batch_count)->where('institution_id',$this->institution_id)->first();

            if(!empty($singleSmsLog->user_no)){
                $apiPath = $this->smsApi . "&contacts=" . $singleSmsLog->user_no."&senderid=" .$this->smsSender_id. "&msg=" . urlencode($singleSmsLog->singleMessage()->message);
                Log::info("Full Sms Send API" . $apiPath);

            }
//            // call guzzle http auto request
            $client = new Client(); //GuzzleHttp\Client
            $result = $client->get($apiPath);
            Log::info("==== Route".$result->getBody());
            Log::info("==== Status Code".$result->getStatusCode());

            Log::info("This is My SMs Path");

        }

        elseif($this->smsMessage=="feesGenerateSingle") {

            Log::info("Fees Genrrate Section Here ");

            $batch_count = $this->sms_batch_id;
            Log::info("Fees Sms Batch Id".$batch_count);
            Log::info("Instituted Id".$this->institution_id);
            //get smsLog list by sms_batch_it
            $singleSmsLog = SmsLog::where('sms_batch_id', $batch_count)->where('institution_id',$this->institution_id)->first();

            if(!empty($singleSmsLog->user_no)){
                $apiPath = $this->smsApi . "&contacts=" . $singleSmsLog->user_no."&senderid=" .$this->smsSender_id. "&msg=" . urlencode($singleSmsLog->singleMessage()->message);
                Log::info("Full Sms Send API" . $apiPath);

                //            // call guzzle http auto request
            $client = new Client(); //GuzzleHttp\Client
            $result = $client->get($apiPath);
            Log::info("==== Route".$result->getBody());
            Log::info("==== Status Code".$result->getStatusCode());

            }

//
//            Log::info("This is My SMs Path");

        }


        elseif($this->smsMessage=="passwordchange") {

            Log::info("Password chnge Sms Call ");

            $batch_count = $this->sms_batch_id;
            Log::info("Fees Sms Batch Id".$batch_count);

            //get smsLog list by sms_batch_it
            $singleSmsLog = SmsLog::where('sms_batch_id', $batch_count)->where('institution_id',$this->institution_id)->first();

           $apiPath = $this->smsApi . "&contacts=" . $singleSmsLog->user_no."&senderid=" .$this->smsSender_id. "&msg=" . urlencode($singleSmsLog->singleMessage()->message);
                Log::info("Full Sms Send API" . $apiPath);
            // call guzzle http auto request
            $client = new Client(); //GuzzleHttp\Client
            $result = $client->get($apiPath);
            Log::info("==== Route".$result->getBody());
            Log::info("==== Status Code".$result->getStatusCode());

            Log::info("This is My SMs Path");

        }


        elseif($this->smsMessage=="onlineApplicaitonSmsSend") {

            Log::info("------------------ Online Application Send SMS Start----------------");

            $batch_count = $this->sms_batch_id;
            Log::info("Fees Sms Batch Id".$batch_count);
            Log::info("Institue ID Log".$this->institution_id);

            //get smsLog list by sms_batch_it
            $singleSmsLog = SmsLog::where('sms_batch_id', $batch_count)->where('institution_id',$this->institution_id)->first();

           $apiPath = $this->smsApi . "&contacts=" . $singleSmsLog->user_no."&senderid=" .$this->smsSender_id. "&msg=" . urlencode($singleSmsLog->singleMessage()->message);
                Log::info("Full Sms Send API" . $apiPath);
            // call guzzle http auto request
            $client = new Client(); //GuzzleHttp\Client
            $result = $client->get($apiPath);
//            Log::info("==== Route".$result->getBody());
//            Log::info("==== Status Code".$result->getStatusCode());

            Log::info("This is My SMs Path");

            Log::info("------------------ Online Application Send SMS End----------------");

        }

        //------------ Send HSC Online Applicaiton SMS ------------------//

        elseif($this->smsMessage=="hscApplicaitonSmsSend") {

            Log::info("------------------ Hsc application Send SMS Start----------------");

            $batch_count = $this->sms_batch_id;
            Log::info("Batch Id".$batch_count);
            Log::info("Institue ID Log".$this->institution_id);

            //get smsLog list by sms_batch_it
            $singleSmsLog = SmsLog::where('sms_batch_id', $batch_count)->where('institution_id',$this->institution_id)->first();

            $apiPath = $this->smsApi . "&contacts=" . $singleSmsLog->user_no."&senderid=" .$this->smsSender_id. "&msg=" . urlencode($singleSmsLog->singleMessage()->message);
            Log::info("Full Sms Send API" . $apiPath);
            // call guzzle http auto request
            $client = new Client(); //GuzzleHttp\Client
            $result = $client->get($apiPath);
//            Log::info("==== Route".$result->getBody());
//            Log::info("==== Status Code".$result->getStatusCode());

            Log::info("This is My SMs Path");

            Log::info("------------------ Hsc Application Send SMS End----------------");

        }


        //------ End HSC Online Applicaiton ---------------//







        // multipleFeesSms

        elseif($this->smsMessage=="multipleFeesSms") {
            Log::info("==== Fees Multi Sms Batch==".$this->sms_batch_id);
            // get batch id
            $batch_count = $this->sms_batch_id;

            //get smsLog list by sms_batch_it

            $smsLogList=SmsLog::where('sms_batch_id',$batch_count)->where('institution_id',$this->institution_id)->get();

            foreach ($smsLogList as $smsLog){

                if(!empty($smsLog->user_no)){
                    $apiPath = $this->smsApi . "&contacts=" . $smsLog->user_no."&senderid=" .$this->smsSender_id. "&msg=" . urlencode($smsLog->singleMessage()->message);
                    Log::info("Full Sms Send API" . $apiPath);

                    // call guzzle http auto request
                    $client = new Client(); //GuzzleHttp\Client
                    $result = $client->get($apiPath);
                    Log::info("==== Route".$result->getBody());
                    Log::info("==== Status Code".$result->getStatusCode());

                }
            }
        }


        elseif($this->smsMessage=="result") {
            Log::info("result Sms Section Here");

            $batch_count = $this->sms_batch_id;

            Log::info("Result Sms Log=========");

            $smsLogList=SmsLog::where('sms_batch_id',$batch_count)->where('institution_id',$this->institution_id)->get();

            foreach ($smsLogList as $smsLog){

                if(!empty($smsLog->user_no)){
                    $apiPath = $this->smsApi . "&contacts=" . $smsLog->user_no."&senderid=" .$this->smsSender_id. "&msg=" . urlencode($smsLog->singleMessage()->message);
                    Log::info("Full Sms Send API" . $apiPath);

                    // call guzzle http auto request
//                    $client = new Client(); //GuzzleHttp\Client
//                    $result = $client->get($apiPath);
//                    Log::info("==== Route".$result->getBody());

                }
            }

        }

        elseif($this->smsMessage=="attendance") {
            Log::info("attendance Sms Section Here");
            // get batch id
            $batch_count=$this->sms_batch_id;

            //get smsLog list by sms_batch_it
            $getNumberList=SmsLog::where('sms_batch_id',$batch_count)->where('institution_id',$this->institution_id)->get();
//             foreach ($smsLogList as $smsLog){
//
//                 $apiPath = $this->smsApi. "" . $smsLog->user_no . "&Message=" . urlencode($smsLog->singleMessage()->message);
//                 Log::info("==== Route" .$apiPath);
//
//
//
//                 // call guzzle http auto request
////                  $client = new Client(); //GuzzleHttp\Client
////                 $result = $client->get($apiPath);
////                 Log::info("==== Route".$result->getBody());
//
////                 $this->sendSmsLog($result->getBody());
//             }
            $this->sendSmsApiCall($getNumberList,'single',$this->smsApi);
            // check batch id log
            Log::info("Sms Message Nai".$this->sms_batch_id);
        }




        elseif($this->smsMessage=="absentattendance") {
            Log::info("absentattendance Sms Section Here");
            Log::info("==== Absent Attendance Batch_id==".$this->sms_batch_id);
            // get batch id
            $batch_count = $this->sms_batch_id;

            //get smsLog list by sms_batch_it

            $institute_id=session()->get('institute');
            $smsLogList=SmsLog::where('sms_batch_id',$batch_count)->where('institution_id',$this->institution_id)->get();
            Log::info("Test Logo Absent Logo");
            foreach ($smsLogList as $smsLog){

                if(!empty($smsLog->user_no)){
                    $apiPath = $this->smsApi . "&contacts=" . $smsLog->user_no."&senderid=" .$this->smsSender_id. "&msg=" . urlencode($smsLog->singleMessage()->message);
                    Log::info("Full Sms Send API" . $apiPath);

                    // call guzzle http auto request
                    $client = new Client(); //GuzzleHttp\Client
                    $result = $client->get($apiPath);
                    Log::info("==== Route".$result->getBody());
                    Log::info("==== Status Code".$result->getStatusCode());

                }
            }

        }


        elseif($this->smsMessage=="present") {
            Log::info("present Sms Section Here");
            Log::info("==== present Attendance Batch_id==".$this->sms_batch_id);
            // get batch id
            $batch_count = $this->sms_batch_id;

            //get smsLog list by sms_batch_it

            $smsLogList=SmsLog::where('sms_batch_id',$batch_count)->where('institution_id',$this->institution_id)->get();
            Log::info("Test Logo Absent Logo");
            foreach ($smsLogList as $smsLog){

                if(!empty($smsLog->user_no)){
                    $apiPath = $this->smsApi . "&contacts=" . $smsLog->user_no."&senderid=" .$this->smsSender_id. "&msg=" . urlencode($smsLog->singleMessage()->message);
                    Log::info("Full Sms Send API" . $apiPath);

//                    // call guzzle http auto request
                    $client = new Client(); //GuzzleHttp\Client
                    $result = $client->get($apiPath);
//                    Log::info("==== Route".$result->getBody());
//                    Log::info("==== Status Code".$result->getStatusCode());

                }
            }

        }

        // birthday sms send birthdaySms

        elseif($this->smsMessage=="birthdaySms") {
            Log::info("birthdaySms Sms Section Here");
            Log::info("==== birthdaySms Batch_id==".$this->sms_batch_id);
            // get batch id
            $batch_count = $this->sms_batch_id;


            $smsLogList=SmsLog::where('sms_batch_id',$batch_count)->where('institution_id',$this->institution_id)->get();

            foreach ($smsLogList as $smsLog){

                if(!empty($smsLog->user_no)){
                    $apiPath = $this->smsApi . "&contacts=" . $smsLog->user_no."&senderid=" .$this->smsSender_id. "&msg=" . urlencode($smsLog->singleMessage()->message);
                    Log::info("Full Sms Send API" . $apiPath);

                    // call guzzle http auto request
                    $client = new Client(); //GuzzleHttp\Client
                    $result = $client->get($apiPath);
                    Log::info("==== Route".$result->getBody());
                    Log::info("==== Status Code".$result->getStatusCode());

                }
            }

        }



        elseif($this->smsMessage=="absent") {

            Log::info("absent Sms Section Here");
            Log::info("==== Absent  Batch_id==".$this->sms_batch_id);
            // get batch id
            $batch_count = $this->sms_batch_id;

            $smsLogList=SmsLog::where('sms_batch_id',$batch_count)->where('institution_id',$this->institution_id)->get();

            foreach ($smsLogList as $smsLog){

                if(!empty($smsLog->user_no)){
                    $apiPath = $this->smsApi . "&contacts=" . $smsLog->user_no."&senderid=" .$this->smsSender_id. "&msg=" . urlencode($smsLog->singleMessage()->message);
                    Log::info("Full Sms Send API" . $apiPath);

                    // call guzzle http auto request
                    $client = new Client(); //GuzzleHttp\Client
                    $result = $client->get($apiPath);
                    Log::info("==== Route".$result->getBody());
                    Log::info("==== Status Code".$result->getStatusCode());

                }
            }


        }


        elseif($this->smsMessage=="assessment") {
            Log::info("==== assessment  Batch_id==".$this->sms_batch_id);
            Log::info("institue_id" . $this->institution_id);
            // get batch id
            $batch_count = $this->sms_batch_id;

            $smsLogList=SmsLog::where('sms_batch_id',$batch_count)->where('institution_id',$this->institution_id)->get();

            foreach ($smsLogList as $smsLog){

                if(!empty($smsLog->user_no)){
                    $apiPath = $this->smsApi . "&contacts=" . $smsLog->user_no."&senderid=" .$this->smsSender_id. "&msg=" . urlencode($smsLog->singleMessage()->message);
                    Log::info("Full Sms Send API" . $apiPath);

//                    // call guzzle http auto request
//                    $client = new Client(); //GuzzleHttp\Client
//                    $result = $client->get($apiPath);
//                    Log::info("==== Route".$result->getBody());
//                    Log::info("==== Status Code".$result->getStatusCode());

                }
            }


        }



        // check smsMessage Null or Value
        elseif($this->smsMessage!=null) {
            Log::info("====handled");
            Log::info("sms_batch_id====" . $this->sms_batch_id);

            // get all number list
            Log::info("institue_id" . $this->institution_id);

            $getNumberList = SmsLog::select("*")->where("sms_batch_id", $this->sms_batch_id)->where('institution_id',$this->institution_id)->get();
            Log::info("number_list" . $getNumberList);

            $countNumber = $getNumberList->count();
            // check numberLIst  if 100 then send sms
            Log::info("===countNumebr".$countNumber);
            if ($countNumber > 100) {
                Log::info("===countNumebr".$countNumber);
                // get number list
                $numberList = $getNumberList;
//            // get sms Apit and make api api path
                $apiPath = $this->smsApi . "&contacts=" .$this->arrayToString($numberList)."&senderid=" .$this->smsSender_id. "&msg=" . urlencode($this->smsMessage);
//                $apiPath = $this->smsApi. "" . $this->arrayToString($numberList) . "&Message=" . urlencode($this->smsMessage);

                $this->sendSmsApiCall($getNumberList,'single',$this->smsApi,$this->smsSender_id);
//
            }

            else {
                // get number list
                $numberList = $getNumberList;

                // get sms Apit and make api api path
                $apiPath = $this->smsApi . "&contacts=" .$this->arrayToString($numberList)."&senderid=" .$this->smsSender_id. "&msg=" . urlencode($this->smsMessage);
//                $apiPath = $this->smsApi. "" . $this->arrayToString($numberList) . "&Message=" . urlencode($this->smsMessage);

                $apiPath= $this->sendSmsApiCall($getNumberList,'single',$this->smsApi,$this->smsSender_id);

                Log::info("==== Route" . $apiPath);

                // call guzzle http auto request
//                $client = new Client(); //GuzzleHttp\Client
//                $result = $client->get($apiPath);
//                Log::info("==== Route".$result->getBody());
//
//                $this->sendSmsLog($result->getBody());

            }

        }



    }

// array to string function
    public  function arrayToString($numberList){
        $numberString="";
        for ($i=0;$i<$numberList->count();$i++) {
            $numberString=$numberString.$numberList[$i]->user_no;
            if ($i != $numberList->count() - 1) {
                $numberString =$numberString. ",";
            }
        }
        return $numberString;
    }



    // save sms XMl Repsonce Sms Status Table
    public  function sendSmsLog($resultXML){
        $xml=simplexml_load_string($resultXML) or die("Error: Cannot create object");
        for ($i=0;$i<$xml->count(); $i++) {
            $smsStatus = new SmsStatus;
            $smsStatus->message_id =$xml->ServiceClass[$i]->MessageId;
            $smsStatus->status =$xml->ServiceClass[$i]->Status;
            $smsStatus->status_text =$xml->ServiceClass[$i]->StatusText;
            $smsStatus->error_code =$xml->ServiceClass[$i]->ErrorCode;
            $smsStatus->error_text =json_encode($xml->ServiceClass[$i]->ErrorText);
            $smsStatus->sms_count =$xml->ServiceClass[$i]->SMSCount;
            $smsStatus->current_credit =$xml->ServiceClass[$i]->CurrentCredit;
            $smsStatus->sms_logid =10;
            $smsStatus->save();

        }
    }


    public function sendSmsApiCall($getNumberList,$smsType,$smsApi,$senderId){
        $smsDataSet=array();
        foreach ($getNumberList as $getNumber){
            $smsDataSet['messageList'][]= array(
                'message'=>$getNumber->singleMessage()->message,
                'mobileNo'=>$getNumber->user_no
            );
        }

        $smsDataSet['type']=$smsType;
        $smsDataSet['smsApi']=$smsApi."&senderid=" .$this->smsSender_id;


        Log::info("==== Json Data".json_encode($smsDataSet));

//
        // call guzzle http auto request
        $client = new Client(); //GuzzleHttp\Client
        //$result = $client->get($apiPath);
         $result = $client->request('POST', 'http://localhost:8080/sendsms', ['json' => $smsDataSet]);

    }


}
