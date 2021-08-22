<?php

namespace Modules\Communication\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Communication\Entities\SmsMessage;
use Modules\Communication\Entities\SmsBatch;
use Modules\Communication\Entities\SmsLog;
use Modules\Academics\Entities\Batch;
use Modules\Setting\Entities\SmsInstitutionGetway;
use Modules\Academics\Entities\Section;
use Modules\Employee\Entities\EmployeeDepartment;
use Illuminate\Support\Facades\Session;
use App\User;
use App\Jobs\SendSms;
use App\Http\Controllers\SmsSender;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Communication\Entities\SmsCredit;
use Modules\Communication\Entities\SmsTemplate;
use Modules\Communication\Entities\MobileNumber;
use Modules\Communication\Entities\Group;


class SmsController extends Controller
{


    private  $smsLog;
    private  $smsClass;
    private  $smsMessage;
    private  $batch;
    private  $section;
    private  $employeeDepartment;
    private  $smsBatch;
    private  $smsInstitutionGetway;
    private  $academicHelper;
    private  $smsCredit;
    private  $smsTemplate;
    private  $mobileNumber;
    private  $group;


    public function __construct( Group $group,MobileNumber $mobileNumber, SmsCredit $smsCredit, SmsTemplate $smsTemplate, SmsMessage $smsMessage, AcademicHelper $academicHelper, SmsInstitutionGetway $smsInstitutionGetway, SmsSender $smsClass, SmsBatch $smsBatch, SmsLog $smsLog, Batch $batch, Section $section, EmployeeDepartment $employeeDepartment)
    {
        $this->smsLog                 = $smsLog;
        $this->smsClass               = $smsClass;
        $this->smsMessage             = $smsMessage;
        $this->batch                  = $batch;
        $this->section                = $section;
        $this->employeeDepartment     = $employeeDepartment;
        $this->smsBatch               = $smsBatch;
        $this->smsInstitutionGetway               = $smsInstitutionGetway;
        $this->academicHelper               = $academicHelper;
        $this->smsCredit               = $smsCredit;
        $this->smsTemplate               = $smsTemplate;
        $this->mobileNumber               = $mobileNumber;
        $this->group               = $group;




    }



    public function index()
    {

        $instituteId = $this->academicHelper->getInstitute();
        $campusId = $this->academicHelper->getCampus();
        $teacherSmsTemplateList= $this->smsTemplate->where('institution_id', $instituteId)->where('campus_id', $campusId)->where('sms_type',1)->orderByDesc('id')->get();

        return view('communication::pages.sms.teacher-sms', compact('teacherSmsTemplateList'))->with('page', 'teacher');

    }


    // all pages
    public function selectGroup($tabName)
    {
        $instituteId = $this->academicHelper->getInstitute();
        $campusId = $this->academicHelper->getCampus();


        switch ($tabName) {

            case 'teacher':
                // return veiw with variables

                // get sms template

                $teacherSmsTemplateList= $this->smsTemplate->where('institution_id', $instituteId)->where('campus_id', $campusId)->where('sms_type',1)->orderByDesc('id')->get();

                return view('communication::pages.sms.teacher-sms', compact('teacherSmsTemplateList'))->with('page', 'teacher');
                break;

            case 'student':
                // return veiw with variables
//                $academicYear=session()->get('academic_year');
                $batchs=$this->batch->where('institute',$instituteId)->where('campus',$campusId)->orderBy('batch_name', 'asc')->get();

                $studentSmsTemplateList= $this->smsTemplate->where('institution_id', $instituteId)->where('campus_id', $campusId)->where('sms_type',2)->orderByDesc('id')->get();


                return view('communication::pages.sms.student-sms',compact('batchs','studentSmsTemplateList'))->with('page', 'student');
                break;

            case 'stuff':
                // return veiw with variable
                //get all transactions
                $departments=$this->employeeDepartment->where('institute_id', $instituteId)->where('campus_id', $campusId)->get();

                $staffSmsTemplateList= $this->smsTemplate->where('institution_id', $instituteId)->where('campus_id', $campusId)->where('sms_type',4)->orderByDesc('id')->get();


                return view('communication::pages.sms.stuff-sms',compact('departments','staffSmsTemplateList'))->with('page', 'stuff');
                break;


            case 'parent':
                // return veiw with variables
                $batchs=$this->batch->where('institute',$instituteId)->where('campus',$campusId)->orderBy('batch_name', 'asc')->get();

                 $parentSmsTemplateList= $this->smsTemplate->where('institution_id', $instituteId)->where('campus_id', $campusId)->where('sms_type',3)->orderByDesc('id')->get();


                return view('communication::pages.sms.parent-sms',compact('parentSmsTemplateList','batchs'))->with('page', 'parent');
                break;

            case 'custom-sms':
                // return veiw with variables

                // get sms template
                $smsTemplateList= $this->smsTemplate->where('institution_id', $instituteId)->where('campus_id', $campusId)->orderByDesc('id')->get();
                $groupList= $this->group->where('institution_id', $instituteId)->where('campus_id', $campusId)->get() ;
                return view('communication::pages.sms.custom-sms', compact('smsTemplateList','groupList'))->with('page', 'custom-sms');
                break;



            default:
                return view('communication::pages.sms.teacher-sms')->with('page', 'teacher');
                break;
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */

    public function smsSendTeacher(Request $request)
    {

//        return  $request->all();
        //sms credit count
        //current sms Credit;
        $smsCredit = $this->sms_creditCount();

        $instituteId = $this->academicHelper->getInstitute();
        $campusId = $this->academicHelper->getCampus();
        $smsApiProfile=$this->smsInstitutionGetway->where('institution_id',$instituteId)->where('status',1)->first();

        $smsbatch = $this->smsBatch->where('institution_id', $instituteId)->where('campus_id', $campusId)->first();
        $batch_count = $smsbatch->batch_count;

        // select all teacher send sms
        $teacher_count = $request->input('teacher_count');
        $userList = $request->input('user_id');
        $phoneList = $request->input('phone');
        $phone_number = $request->input('phone_number');

        $count = count($userList);
        //  phone number count
        if(empty($phoneList)) {
            $numberCount = count(array_filter($phone_number));
        } else {
            $numberCount = count(array_filter($phoneList));
        }
        if ($smsCredit >= $numberCount) {

            if ($teacher_count > 0) {
                $smsMessage = new $this->smsMessage;
                $smsMessage->message = $request->input('message');
                $sendSms = $smsMessage->save();

                if ($sendSms) {
                    for ($i = 0; $i < $count; $i++) {
                        if (!empty($phoneList[$i])) {
                            $smsLog = new $this->smsLog();
                            $smsLog->institution_id = $this->academicHelper->getInstitute();
                            $smsLog->campus_id = $this->academicHelper->getCampus();
                            $smsLog->user_id = $userList[$i];
                            $smsLog->user_no = $phoneList[$i];;
                            $smsLog->user_group = 1;
                            $smsLog->message_id = $smsMessage->id;
                            $smsLog->delivery_status = 1;
                            $smsLog->sms_batch_id = $batch_count;
                            $smsLog->save();

                            //sms batch Count Update status
                            $this->smsBatch->where('institution_id', $instituteId)->where('campus_id', $campusId)->update(['batch_count' => $batch_count + 1]);
                        }
                    }


                }


            } // select auto complete data then push message
            else {
//            return $request->all();

                $userList = $request->input('user_id');
                $phoneList = $request->input('phone');
//            $teacher_count = $request->input('custom_teacher_count');
                $count = count($userList);
                //
                $empty = array();
                foreach ($phone_number as $key)
                    if (is_numeric($key)) // only numbers, a point and an `e` like in 1.1e10
                        array_push($empty, $key);

                $smsMessage = new $this->smsMessage;
                $smsMessage->message = $request->input('message');
                $sendSms = $smsMessage->save();

                if ($sendSms) {
                    for ($i = 0; $i < $count; $i++) {
                        if (!empty($phoneList[$i])) {
                            $smsLog = new $this->smsLog();
                            $smsLog->institution_id = $this->academicHelper->getInstitute();
                            $smsLog->campus_id = $this->academicHelper->getCampus();
                            $smsLog->user_id = $userList[$i];
                            $smsLog->user_no = $phoneList[$i];;
                            $smsLog->user_group = 1;
                            $smsLog->message_id = $smsMessage->id;
                            $smsLog->delivery_status = 1;
                            $smsLog->sms_batch_id = $batch_count;
                            $smsLog->save();

                            //sms batch Count Update status
                            //sms batch Count Update status
                            $this->smsBatch->where('institution_id', $instituteId)->where('campus_id', $campusId)->update(['batch_count' => $batch_count + 1]);
                        }

                    }

                    for ($i = 0; $i < count($empty); $i++) {
                        $smsLog = new $this->smsLog();
                        $smsLog->institution_id = $this->academicHelper->getInstitute();
                        $smsLog->campus_id = $this->academicHelper->getCampus();
                        $smsLog->user_id = 0;
                        $smsLog->user_no = $empty[$i];
                        $smsLog->user_group = 1;
                        $smsLog->message_id = $smsMessage->id;
                        $smsLog->delivery_status = 1;
                        $smsLog->sms_batch_id = $batch_count;
                        $smsLog->save();

                        //sms batch Count Update status
                        $this->smsBatch->where('institution_id', $instituteId)->where('campus_id', $campusId)->update(['batch_count' => $batch_count + 1]);
                    }


                }

            }


            $batch_count = $smsLog->sms_batch_id;
//
            $smsApi = $this->getSmsApi();
            $smsMessage = $request->input('message');
            $smsApi = json_decode(str_replace("\\", "", $smsApi), true);
            $sender_id=$smsApiProfile->sender_id;
            //$smsApi = "https://api.mobireach.com.bd/SendTextMultiMessage?Username=raqib&Password=Abcd@1234&From=ALOKITO&To=";
            $this->smsClass->sendMultiSms($batch_count, $smsApi['api_path'], $smsMessage,$sender_id,$instituteId);

//        Session::flash('success', 'Successfully Send SMS');
            return 'success';


        } else {
            return 'error';
        }
    }


    /// sms send by student
    public function smsSendStudent(Request $request)

    {


//        return $request->all();
        //current sms Credit;
        $smsCredit = $this->sms_creditCount();

        $instituteId= $this->academicHelper->getInstitute();
        $campusId= $this->academicHelper->getCampus();
        $smsApiProfile=$this->smsInstitutionGetway->where('institution_id',$instituteId)->where('status',1)->first();

        $smsbatch=$this->smsBatch->where('institution_id',$instituteId)->where('campus_id',$campusId)->first();

        $batch_count=$smsbatch->batch_count;
        // select all Student send sms
        $student_count = $request->input('student_count');
        $userList = $request->input('user_id');
        $phoneList = $request->input('phone');

        $count = count($userList);
        $numberCount = count(array_filter($phoneList));
        if ($smsCredit >= $numberCount) {

        if($student_count>0){

            $smsMessage = new $this->smsMessage;
            $smsMessage->message = $request->input('message');
            $sendSms = $smsMessage->save();

            if ($sendSms) {
                for ($i = 0; $i < $count; $i++) {
                    if(!empty($phoneList[$i])) {
                        $smsLog = new $this->smsLog();
                        $smsLog->institution_id = $this->academicHelper->getInstitute();
                        $smsLog->campus_id = $this->academicHelper->getCampus();
                        $smsLog->user_id = $userList[$i];
                        $smsLog->user_no = $phoneList[$i];;
                        $smsLog->user_group = 2;
                        $smsLog->message_id = $smsMessage->id;
                        $smsLog->delivery_status = 1;
                        $smsLog->sms_batch_id = $batch_count;
                        $smsLog->save();

                        //sms batch Count Update status
                        $this->smsBatch->where('institution_id',$instituteId)->where('campus_id',$campusId)->update(['batch_count' =>$batch_count+1 ]);


                    }


                }
            }

        }
        // select auto complete data then push message
        else {
//            return $request->all();

            $phone_number=$request->input('phone_number');

            $smsMessage = new $this->smsMessage;
            $smsMessage->message = $request->input('message');
            $sendSms = $smsMessage->save();

            if ($sendSms) {
                for ($i = 0; $i < $count; $i++) {
                    if(!empty($phoneList[$i])) {
                        $smsLog = new $this->smsLog();
                        $smsLog->institution_id = $this->academicHelper->getInstitute();
                        $smsLog->campus_id = $this->academicHelper->getCampus();
                        $smsLog->user_id = $userList[$i];
                        $smsLog->user_no = $phoneList[$i];;
                        $smsLog->user_group = 2;
                        $smsLog->message_id = $smsMessage->id;
                        $smsLog->delivery_status = 1;
                        $smsLog->sms_batch_id = $batch_count;
                        $smsLog->save();
                        //sms batch Count Update status
                        $this->smsBatch->where('institution_id',$instituteId)->where('campus_id',$campusId)->update(['batch_count' =>$batch_count+1 ]);


                    }


                }

            }

        }

        // get batch count

         $batch_count= $smsLog->sms_batch_id;

        //get sms api using function
        $smsApi=$this->getSmsApi();
        $smsMessage=$request->input('message');
        $smsApi= json_decode(str_replace("\\","",$smsApi), true);
        $sender_id=$smsApiProfile->sender_id;
        //$smsApi = "https://api.mobireach.com.bd/SendTextMultiMessage?Username=raqib&Password=Abcd@1234&From=ALOKITO&To=";
        $this->smsClass->sendMultiSms($batch_count,$smsApi['api_path'],$smsMessage,$sender_id,$instituteId);

//        Session::flash('success', 'Successfully Send SMS');
        return "success";


    } else {
            return "error";
        }
    }


    /// sms send by stuff
    public function smsSendStuff(Request $request)

    {


        $instituteId= $this->academicHelper->getInstitute();
        $campusId= $this->academicHelper->getCampus();
        $smsApiProfile=$this->smsInstitutionGetway->where('institution_id',$instituteId)->where('status',1)->first();

        $smsbatch=$this->smsBatch->where('institution_id',$instituteId)->where('campus_id',$campusId)->first();

        $batch_count=$smsbatch->batch_count;

//            return $request->all();
        // select all Stuff send sms
        $student_count = $request->input('student_count');

        if($student_count>0){

            $userList = $request->input('user_id');
            $phoneList = $request->input('phone');

            $count = count($userList);

            $smsMessage = new $this->smsMessage;
            $smsMessage->message = $request->input('message');
            $sendSms = $smsMessage->save();

            if ($sendSms) {
                for ($i = 0; $i < $count; $i++) {
                    $smsLog = new $this->smsLog();
                    $smsLog->institution_id =$this->academicHelper->getInstitute();
                    $smsLog->campus_id =$this->academicHelper->getCampus();
                    $smsLog->user_id = $userList[$i];
                    $smsLog->user_no = $phoneList[$i];;
                    $smsLog->user_group = 3;
                    $smsLog->message_id = $smsMessage->id;
                    $smsLog->delivery_status = 1;
                    $smsLog->sms_batch_id =$batch_count;
                    $smsLog->save();

                    //sms batch Count Update status
                    $this->smsBatch->where('institution_id',$instituteId)->where('campus_id',$campusId)->update(['batch_count' =>$batch_count+1 ]);

                }
            }

        }
        // select auto complete data then push message
        else {
//            return $request->all();

            $phone_number=$request->input('phone_number');
            $userList = $request->input('user_id');
            $phoneList = $request->input('phone');
            $count = count($userList);

            $smsMessage = new $this->smsMessage;
            $smsMessage->message = $request->input('message');
            $sendSms = $smsMessage->save();

            if ($sendSms) {
                for ($i = 0; $i < $count; $i++) {

                    $smsLog = new $this->smsLog();
                    $smsLog->institution_id =$this->academicHelper->getInstitute();
                    $smsLog->campus_id =$this->academicHelper->getCampus();
                    $smsLog->user_id = $userList[$i];
                    $smsLog->user_no = $phoneList[$i];;
                    $smsLog->user_group = 3;
                    $smsLog->message_id = $smsMessage->id;
                    $smsLog->delivery_status = 1;
                    $smsLog->sms_batch_id =$batch_count;
                    $smsLog->save();

                    //sms batch Count Update status
                    $this->smsBatch->where('institution_id',$instituteId)->where('campus_id',$campusId)->update(['batch_count' =>$batch_count+1 ]);


                }

            }

        }


        // get batch count

        $batch_count= $smsLog->sms_batch_id;

        //get sms api using function
        $smsApi=$this->getSmsApi();
        $smsMessage=$request->input('message');
        $smsApi= json_decode(str_replace("\\","",$smsApi), true);
        $sender_id=$smsApiProfile->sender_id;
        //$smsApi = "https://api.mobireach.com.bd/SendTextMultiMessage?Username=raqib&Password=Abcd@1234&From=ALOKITO&To=";
        $this->smsClass->sendMultiSms($batch_count,$smsApi['api_path'],$smsMessage,$sender_id,$instituteId);

//        Session::flash('success', 'Successfully Send SMS');
        return "success";

    }



    // sms test funciton
//    public function sendSMS()
//    {
//        $users = User::all();
//        foreach ($users as $user)
//        {
//            $job = (new SendSMS($user->email))->onQueue('sms');
//
//            $this->dispatch($job);
//        }
//    }

    // send sms for parents

    public function smsSendParent(Request $request)

    {



        //current sms Credit;
        $smsCredit = $this->sms_creditCount();

        $instituteId = $this->academicHelper->getInstitute();
        $campusId = $this->academicHelper->getCampus();
        $smsApiProfile=$this->smsInstitutionGetway->where('institution_id',$instituteId)->where('status',1)->first();

        $smsbatch = $this->smsBatch->where('institution_id', $instituteId)->where('campus_id', $campusId)->first();

        $batch_count = $smsbatch->batch_count;

        $userList = $request->input('user_id');
        $phoneList = $request->input('phone');
        $count = count($userList);
        $numberCount = count(array_filter($phoneList));
        if ($smsCredit >= $numberCount) {

            // select all Student send sms
            $parent_count = $request->input('parent_count');

            if ($parent_count > 0) {

                $smsMessage = new $this->smsMessage;
                $smsMessage->message = $request->input('message');
                $sendSms = $smsMessage->save();

                if ($sendSms) {
                    for ($i = 0; $i < $count; $i++) {
                        if (!empty($phoneList[$i])) {
                            $smsLog = new $this->smsLog();
                            $smsLog->institution_id = $this->academicHelper->getInstitute();
                            $smsLog->campus_id = $this->academicHelper->getCampus();
                            $smsLog->user_id = $userList[$i];
                            $smsLog->user_no = $phoneList[$i];;
                            $smsLog->user_group = 4;
                            $smsLog->message_id = $smsMessage->id;
                            $smsLog->delivery_status = 1;
                            $smsLog->sms_batch_id = $batch_count;
                            $smsLog->save();
                            //sms batch Count Update status
                            $this->smsBatch->where('institution_id', $instituteId)->where('campus_id', $campusId)->update(['batch_count' => $batch_count + 1]);

                        }
                    }
                }

            } // select auto complete data then push message
            else {
//            return $request->all();

                $phone_number = $request->input('phone_number');
                $userList = $request->input('user_id');
                $phoneList = $request->input('phone');
                $count = count($userList);

                $smsMessage = new $this->smsMessage;
                $smsMessage->message = $request->input('message');
                $sendSms = $smsMessage->save();

                if ($sendSms) {
                    for ($i = 0; $i < $count; $i++) {
                        if (!empty($phoneList[$i])) {
                            $smsLog = new $this->smsLog();
                            $smsLog->institution_id = $this->academicHelper->getInstitute();
                            $smsLog->campus_id = $this->academicHelper->getCampus();
                            $smsLog->user_id = $userList[$i];
                            $smsLog->user_no = $phoneList[$i];;
                            $smsLog->user_group = 4;
                            $smsLog->message_id = $smsMessage->id;
                            $smsLog->delivery_status = 1;
                            $smsLog->sms_batch_id = $batch_count;
                            $smsLog->save();
                            //sms batch Count Update status
                            $this->smsBatch->where('institution_id', $instituteId)->where('campus_id', $campusId)->update(['batch_count' => $batch_count + 1]);
                        }

                    }

                }

            }

//         get batch count
            $batch_count = $smsLog->sms_batch_id;

            //get sms api using function
            $smsApi = $this->getSmsApi();
            $smsMessage = $request->input('message');
            $smsApi = json_decode(str_replace("\\", "", $smsApi), true);
            $sender_id=$smsApiProfile->sender_id;
            //$smsApi = "https://api.mobireach.com.bd/SendTextMultiMessage?Username=raqib&Password=Abcd@1234&From=ALOKITO&To=";
            $this->smsClass->sendMultiSms($batch_count, $smsApi['api_path'], $smsMessage,$sender_id,$instituteId);

//            Session::flash('success', 'Successfully Send SMS');
            return "success";


        } else {
            return "error";
        }
    }




    // send custom sms function

    public function smsSendCustom(Request $request){

        $smsCredit = $this->sms_creditCount();

        $instituteId = $this->academicHelper->getInstitute();
        $campusId = $this->academicHelper->getCampus();
        $smsApiProfile=$this->smsInstitutionGetway->where('institution_id',$instituteId)->where('status',1)->first();

        $smsbatch = $this->smsBatch->where('institution_id', $instituteId)->where('campus_id', $campusId)->first();
        $batch_count = $smsbatch->batch_count;


        $phoneList = $request->input('phone_number');

        $smsMessage = new $this->smsMessage;
        $smsMessage->message = $request->input('message');
        $sendSms = $smsMessage->save();

        if($sendSms){
        for ($i = 0; $i < count($phoneList); $i++) {
                if (!empty($phoneList[$i])) {
                    $smsLog = new $this->smsLog();
                    $smsLog->institution_id = $this->academicHelper->getInstitute();
                    $smsLog->campus_id = $this->academicHelper->getCampus();
                    $smsLog->user_id = 0;
                    $smsLog->user_no = $phoneList[$i];;
                    $smsLog->user_group = 0;
                    $smsLog->message_id = $smsMessage->id;
                    $smsLog->delivery_status = 1;
                    $smsLog->sms_batch_id = $batch_count;
                    $smsLog->save();

                    //sms batch Count Update status
                    //sms batch Count Update status
                    $this->smsBatch->where('institution_id', $instituteId)->where('campus_id', $campusId)->update(['batch_count' => $batch_count + 1]);
                }

        }

        //get batch count
            $batch_count = $smsLog->sms_batch_id;

            //get sms api using function
            $smsApi = $this->getSmsApi();
            $smsMessage = $request->input('message');
            $smsApi = json_decode(str_replace("\\", "", $smsApi), true);
            $sender_id=$smsApiProfile->sender_id;
            //$smsApi = "https://api.mobireach.com.bd/SendTextMultiMessage?Username=raqib&Password=Abcd@1234&From=ALOKITO&To=";
            $this->smsClass->sendMultiSms($batch_count, $smsApi['api_path'], $smsMessage,$sender_id,$instituteId);

//            Session::flash('success', 'Successfully Send SMS');
            return "success";


            } else {
             return "error";
        }

 }






    // get sms Api function
    public function  getSmsApi(){
        $instituteId=$this->academicHelper->getInstitute();
//        get sms api
         $api_path = $this->smsInstitutionGetway->select('api_path')->where('institution_id',$instituteId)->where('status',1)->first();
         return $api_path;
    }


    public function  sms_creditCount() {

        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

        $smsCredits=$this->smsCredit->where('institution_id',$instituteId)->where('campus_id',$campus_id)->where('status','1')->orderBy('id','desc')->paginate(10);
        // sms _creadit COunt
        $smsCreditCount=$this->smsCredit->where('institution_id',$instituteId)->where('campus_id',$campus_id)->where('status','1')->sum('sms_amount');
        // sms _sms Log Count
        $smsLogCount= $this->smsLog->where('institution_id',$instituteId)->where('campus_id',$campus_id)->count();

        //sms_creadit - sms_log
        $totalSmsCreadit=$smsCreditCount-$smsLogCount;
        return $totalSmsCreadit;
    }


}


