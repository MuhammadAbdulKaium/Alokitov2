<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Student\Entities\StudentInformation;
use Modules\Student\Entities\StudentGuardian;
use Modules\Student\Entities\StudentParent;
use Modules\Communication\Entities\SmsLog;
use Modules\Communication\Entities\SmsBatch;
use Modules\Communication\Entities\SmsTemplate;
use Modules\Communication\Entities\SmsMessage;
use Modules\Academics\Entities\StudentAttendance;
use Modules\Setting\Entities\AutoSmsSetting;
use Illuminate\Support\Facades\Log;
use Modules\Academics\Entities\Assessments;
use Illuminate\Support\Facades\DB;
use Modules\Setting\Entities\SmsInstitutionGetway;
use App\Http\Controllers\SmsSender;
use Modules\Setting\Entities\AutoSmsModule;

class ResultJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $studentIdList;
    protected $assValue;
    protected $gradeMarkJson;
    protected $checked_cat_ass;
    protected $category_id_list;

    public function __construct($studentIdList,$checked_cat_ass,$category_id_list,$gradeMarkJson)
    {

        // user type for parents, student, teacher, stuff
        // user type 1=teacher
        //user type 2= student
        // user type 3=stuff;
        // user type 4=Parents


        $this->studentIdList = $studentIdList;
        $this->checked_cat_ass = $checked_cat_ass;
        $this->category_id_list = $category_id_list;
        $this->gradeMarkJson = $gradeMarkJson;


//        Log::info(print_r($this->studentIdList));
        // student id list log
//        Log::info($this->checked_cat_ass);
//
//        exit();


        $markArray=$this->gradeMarkJson;
        $studentmarks=$markArray['grade'];

//        Log::info("Student List".print_r($studentmarks));
//        exit();

        $resultarray = array();
        for($i=0;$i<count($studentmarks);$i++){
            $r_array=[];
            $this->createMessage($studentmarks[$i], $r_array);
            $resultarray[]=$r_array;
//                Log::info(print_r($this->createMessage($studentmarks[$i])));
        }

//        Log::info("asdasdasddasd".print_r($resultarray,true));

        $institute_id=session()->get('institute');
        $campus_id=session()->get('campus');

        // get message from sms_message table
        $smsResult=SmsTemplate::select('message')->where('template_name','RESULT')->where('institution_id', $institute_id)->where('campus_id', $campus_id)->first();

        if(!empty($smsResult) && ($smsResult->count()>0)) {

            $studentMsgAtt = array();
            $parentMsgAtt = array();

            for ($i = 0; $i < count($resultarray); $i++) {


//            Log::info("Student Id".$resultarray[$i]['student_id']);
                //get student information
                $studentProfile = StudentInformation::where('id', $resultarray[$i]['student_id'])->first();
                if (!empty($studentProfile->singleEnroll())) {

                    Log::info("Student Profile" . $studentProfile);
                    $fullName = $studentProfile->title . " " . $studentProfile->first_name . " " . $studentProfile->middle_name . " " . $studentProfile->last_name;
                    $section_name = $studentProfile->singleEnroll()->section()->section_name;
                    $batch_name = $studentProfile->singleEnroll()->batch()->batch_name;

                    $date = date("d-m-Y");

                    // multiple staring replace
                    $searchString = array("{name}", "{section}", "{batch}", "{result}", "{date}");
                    $replaceString = array($fullName, $section_name, $batch_name, $resultarray[$i]['result'], $date);

                    $message = str_replace($searchString, $replaceString, $smsResult->message);
//                Log::info("Message Log" . $message);

                    $message_id = $this->setSmsMessage($message);
                    $studentMsgAtt[$resultarray[$i]['student_id']] = $message_id;
                    $parent_id = $this->getParent($resultarray[$i]['student_id']);
                    if ($parent_id != 0)
                        $parentMsgAtt[$parent_id] = $message_id;
                }


            }
//            //save message sms_message table


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

    public function createMessage($studentMarks,&$r_array)
    {


        //    multiple staring replace
        $fullName = $studentMarks['std_name'];
        $std_id = $studentMarks['std_id'];
        $student_marks_array = (array)$studentMarks['mark'];
        $categorylist = $this->category_id_list;

//    Log::info('mark0:'.json_encode($student_marks_array));
        for ($i = 0; $i < count($categorylist)-1; $i++) {
            $cat_id = $categorylist[$i];
            $ass_id_list_array = (array)$this->checked_cat_ass[$categorylist[$i]];

//        Log::info('mark1:'.json_encode($student_marks_array));

            $result_string = "";

            for ($i = 0; $i < count($categorylist) - 1; $i++) {
                $cat_id = $categorylist[$i];
                $ass_id_list_array = (array)$this->checked_cat_ass[$categorylist[$i]];

            Log::info('assement Id List:'.json_encode($ass_id_list_array));
                $resultArray = array();

                for ($k = 0; $k < count($ass_id_list_array); $k++) {
                    $ass_id = $ass_id_list_array[$k];
//                Log::info('ass:'.$k." ".json_encode($ass_id));

                    $catId = 'cat_' . $cat_id;
                    $assId = 'ass_' . $ass_id;
                    $ass_name = Assessments::select('name')->where('id', $ass_id)->first();


                Log::info('aseetid:'.$assId);
                Log::info('===========:');
//                Log::info('mark2:'.json_encode($student_marks_array[$catId]));

                    // checking category and assessment key if exits
                    if (array_key_exists($assId, $student_marks_array[$catId])) {
                        Log::info("++++++Romesh SHil++++++++");
                        Log::info('catId:' . $cat_id . " " . 'assId:' . $assId);
                        $assProfile = $student_marks_array[$catId]->$assId;
                        /* $data[$stdProfile->id] = [
                             'ass_mark'=>$assProfile->ass_mark,
                             'ass_points'=>$assProfile->ass_points,
                         ];*/
                        $result_string = $result_string . $ass_name->name . " : " . $assProfile->ass_mark . "/" . $assProfile->ass_points . "\r\n";


                    Log::info( "std : ".$fullName."ass_marks : ". $assProfile->ass_mark. " ass_points : ". $assProfile->ass_points);
                    }

                }
            }
            //remove last enter
            $result_string=rtrim($result_string, "\r\n");
            $r_array = ["student_id" => $std_id, "result" => $result_string];


        }

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
        $sendSms->sendSingleSms($batch_count,$smsApiPath,$smsMessage="result",$smsSender_id);


    }


}
