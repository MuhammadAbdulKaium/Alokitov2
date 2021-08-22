<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Modules\Student\Entities\StudentInformation;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\Communication\Entities\SmsLog;
use Modules\Communication\Entities\SmsBatch;
use Modules\Communication\Entities\SmsMessage;
use Modules\Setting\Entities\SmsInstitutionGetway;
use DB;
use App\Http\Controllers\SmsSender;

class HscApplicationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected  $applicantUser;
    
    public function __construct($applicantUser)
    {
        //
        $this->applicantUser = $applicantUser;
        // log Applicant Details
        Log::info('Institute ID'.$applicantUser->institute_id);
        Log::info('Campus ID'.$applicantUser->campus_id);
        Log::info('Online Application Job : started'.$applicantUser->institute_id);
        // message format
        $message="Application Submitted !!! Student Name: ".$applicantUser->s_name.", Username: ".$applicantUser->username.", Password: ".$applicantUser->password_rand.", Secret Code: ".$applicantUser->a_no;
        $messageId = $this->setSmsMessage($message);
        $this->insertSmsLogData($applicantUser, $applicantUser->s_mobile, $messageId, 2);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {



    }


    public  function  insertSmsLogData($userProfile,$phone,$messageId,$userGroup)
    {
        //get institution id
        $institute_id = $userProfile->institute_id;
        $smsbatch = SmsBatch::where('institution_id', $institute_id)->first();
        $batch_count = $smsbatch->batch_count;

        if (!empty($phone)) {
            Log::info("Mobile Number" . $phone);
            $smsLog = new SmsLog;
            $smsLog->institution_id = $userProfile->institute_id;
            $smsLog->campus_id = $userProfile->campus_id;
            $smsLog->user_id = 0;
            $smsLog->user_no = $phone;
            $smsLog->user_group = $userGroup;
            $smsLog->message_id = $messageId;
            $smsLog->delivery_status = 1;
            $smsLog->sms_batch_id = $batch_count;
            $smsLog->save();

            //sms batch Count Update status
            SmsBatch::where('institution_id', $institute_id)->update(['batch_count' => $batch_count + 1]);
            // get sms api
            $apiGateway = SmsInstitutionGetway::where(['institution_id'=>$institute_id, 'status'=>1])->first();
            Log::info("Api Path Here," . ($apiGateway->api_path));
            $smsApiPath = str_replace("\\", "", $apiGateway->api_path);
            $smsSender_id = $apiGateway->sender_id;

            // create smsSender Object
            $sendSms = new SmsSender;
            // call sending Single sms Job
            $sendSms->sendSingleSms($batch_count, $smsApiPath, $smsMessage = 'hscApplicaitonSmsSend', $smsSender_id,$institute_id);


        }
    }



      public function setSmsMessage($message){
        $smsMessage = new SmsMessage();
        $smsMessage->message = $message;
        $sendSms = $smsMessage->save();
        //Log::info( print_r($sendSms,true));

        return DB::getPdo()->lastInsertId();
    }





}
