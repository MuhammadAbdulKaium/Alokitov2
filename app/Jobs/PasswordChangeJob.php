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

class PasswordChangeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected  $userId;
    protected  $password;
    public function __construct($userId,$password)
    {
        //
        $this->userId = $userId;
        $this->password = $password;
        Log::info('Password Change JOb : started'.$this->userId.'password'.$this->password);

        $userProfile=array();
        // Find User In Student Table
        $userProfile = StudentInformation::where('user_id',$this->userId)->first();
        $userGroup=2;
        Log::info('Student Profile'.$userProfile);
        if(empty($userProfile)){
             $userProfile = EmployeeInformation::where('user_id',$this->userId)->first();
            Log::info('Employee Profile'.$userProfile);
            $userGroup=1;
        } else {
            $userProfile=array();
        }

        if(!empty($userProfile) && !empty($userProfile->phone)) {
        $message="EIMS Credentials,Email: $userProfile->email,Password: $this->password";
                    $messageId=$this->setSmsMessage($message);
                    $this->insertSmsLogData($userProfile,$userProfile->phone,$messageId,$userGroup);
           }

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
        $institute_id = session()->get('institute');
        $smsbatch = SmsBatch::where('institution_id', $institute_id)->first();
        $batch_count = $smsbatch->batch_count;

        if (!empty($phone)) {
            Log::info("Mobile Number" . $phone);
            $smsLog = new SmsLog;
            $smsLog->institution_id = session()->get('institute');
            $smsLog->campus_id = session()->get('campus');
            $smsLog->user_id = $userProfile->user_id;
            $smsLog->user_no = $phone;
            $smsLog->user_group = $userGroup;
            //                $smsLog->message_id = 2;
            $smsLog->message_id = $messageId;
            $smsLog->delivery_status = 1;
            $smsLog->sms_batch_id = $batch_count;
            $smsLog->save();


            //get institution id
            $institute_id = session()->get('institute');

            //sms batch Count Update status
            SmsBatch::where('institution_id', $institute_id)->update(['batch_count' => $batch_count + 1]);

//         get sms api
            $apiGetway = SmsInstitutionGetway::where('institution_id', $institute_id)->where('status', 1)->first();
            Log::info("Api Path Here," . ($apiGetway->api_path));
            $smsApiPath = str_replace("\\", "", $apiGetway->api_path);
            $smsSender_id = $apiGetway->sender_id;

            // create smsSender Object
            $sendSms = new SmsSender;
            // call sending Single sms Job
            $sendSms->sendSingleSms($batch_count, $smsApiPath, $smsMessage = 'passwordchange', $smsSender_id,$institute_id);


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
