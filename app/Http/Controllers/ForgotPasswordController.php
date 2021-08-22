<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\ForgotPasswordUser;
use Illuminate\Support\Facades\Session;
use Modules\Communication\Entities\SmsLog;
use Modules\Communication\Entities\SmsMessage;
use Modules\Communication\Entities\SmsBatch;
use Modules\Setting\Entities\SmsInstitutionGetway;
use App\Http\Controllers\SmsSender;
use App\Http\Controllers\Helpers\AcademicHelper;
use App\UserInfo;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetMail;

class ForgotPasswordController extends Controller
{

    private  $smsLog;
    private  $smsMessage;
    private  $smsBatch;
    private  $smsInstitutionGetway;
    private  $smsSender;
    private  $academicHelper;
    private  $userInfo;


    public function __construct( AcademicHelper $academicHelper,ForgotPasswordUser $forgotPasswordUser,SmsMessage $smsMessage, SmsSender $smsSender, SmsInstitutionGetway $smsInstitutionGetway,SmsBatch $smsBatch, SmsLog $smsLog, UserInfo $userInfo)
    {
        $this->smsLog                 = $smsLog;
        $this->smsMessage             = $smsMessage;
        $this->smsBatch               = $smsBatch;
        $this->smsInstitutionGetway   = $smsInstitutionGetway;
        $this->smsSender              = $smsSender;
        $this->forgotPasswordUser              = $forgotPasswordUser;
        $this->academicHelper              = $academicHelper;
        $this->userInfo              = $userInfo;


    }




    // forgot password
    public  function index(){
        return view('auth.forgot-password');
    }


    public  function forgotPasswordByEmail(Request $request){

        $email=$request->input('email');
          $userEmail=$this->checkUserEmail($email);
        if($userEmail){
             $userinstutueProfile=$this->userInfo->where('user_id',$userEmail)->first();
            $userinstutueProfile->institute_id;
            $forgotUser= new ForgotPasswordUser;
            $forgotUser->user_id=$userEmail;
            $forgotUser->institute_id=$userinstutueProfile->institute_id;;
            $forgotUser->save();

            Session::flash('success','Please check your email to reset your password ');
            return redirect()->back();
        } else {
            Session::flash('wrong','Sorry, we do not recognize this email address');
            return redirect()->back();
        }


    }

    // check your  email

    public  function checkUserEmail($email){
        $userEmail=User::where('email', $email)->first();
        if($userEmail){
           return $userEmail->id;
        } else {
            return false;
        }
    }


    // forgot user list

    public  function forgotPasswordUserList(){
        $forgotUsers=ForgotPasswordUser::orderBy('id', 'desc')->paginate(10);
        return view('auth.forgot-user-list',compact('forgotUsers'));
    }


    public function resetUserPassword(Request $request){

//        return $request->all();
        $instituteId=$this->academicHelper->getInstitute();// sms batch
        $campusId=$this->academicHelper->getCampus();// sms batch
        $smsbatch=$this->smsBatch->where('institution_id',$instituteId)->first();
        $smsApiProfile=$this->smsInstitutionGetway->where('institution_id',$instituteId)->where('status',1)->first();

        $batch_count=$smsbatch->batch_count;


        $user_id=$request->input('user_id');
        $phone=$request->input('phone');
        // random string generate
        $password= str_random(8);
        // user profile and update password here
        $userProfile=User::find($user_id);
        // passwordGenerate
        $userProfile->password=bcrypt($password);
        //create message
        $getPasswordMessage="Your Account Password is Reset to $password";
        $update=$userProfile->update();

        if($update) {
           $forgotUserProfile= ForgotPasswordUser::where('user_id',$user_id)->first();
            $forgotUserProfile->status=2;
            $forgotUserProfile->update();
        }


        //
        $smsMessage = new $this->smsMessage;
        $smsMessage->message = $getPasswordMessage;
        $sendSms = $smsMessage->save();

        if ($sendSms) {
                $smsLog = new $this->smsLog();
                $smsLog->institution_id = $instituteId;
                $smsLog->campus_id = $campusId;
                $smsLog->user_id = $user_id;
                $smsLog->user_no = $phone;
                // sms group dynamic hoba
                $smsLog->user_group = 1;
                $smsLog->message_id = $smsMessage->id;
                $smsLog->delivery_status = 1;
                $smsLog->sms_batch_id = $batch_count;
                $smsLog->save();
            }

        //sms batch Count Update status
        $this->smsBatch->where('institution_id',$instituteId)->update(['batch_count' =>$batch_count+1 ]);


        $batch_count= $smsLog->sms_batch_id;

        $smsApi=$this->getSmsApi();
        $smsMessage="forgotpasswordmsg";
        $smsApi= json_decode(str_replace("\\","",$smsApi), true);

        //$smsApi = "https://api.mobireach.com.bd/SendTextMultiMessage?Username=raqib&Password=Abcd@1234&From=ALOKITO&To=";
        $this->smsSender->passwordResetJob($batch_count,$smsApiProfile->api_path,$smsMessage,$smsApiProfile->sender_id,$instituteId);

        Session::flash('forgotpassmsg','Successfully Reset Your Password');
        return redirect()->back();
    }



    // get sms Api function
    public function  getSmsApi(){
        $institute_id=session()->get('institute');
//        get sms api
        $api_path = $this->smsInstitutionGetway->select('api_path')->where('institution_id',$institute_id)->where('id',1)->first();
        return $api_path;
    }


    // forgot password user delete method
    public function  forgotPasswordUserDelete($forgotPasswordUserId){
            ForgotPasswordUser::find($forgotPasswordUserId)->delete();
    }


    public function passwordResetMailTest(){

//        // the message
//        $msg = "First line of text\nSecond line of text";
//
//// use wordwrap() if lines are longer than 70 characters
//        $msg = wordwrap($msg,70);
//
//// send email
//        mail("romeshshil99@gmail.com","My subject",$msg);

        $data = ['firstname' => 'Recipient Name'];
        Mail::send('emails.password_reset', $data, function($message)
        {
            $message->to('romeshshil99@gmail.com', 'Recipient Name')->subject('Your Subject');
        });
    }


}
