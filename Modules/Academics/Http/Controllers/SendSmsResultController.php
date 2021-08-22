<?php

namespace Modules\Academics\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Student\Entities\StudentProfileView;
use Modules\Academics\Entities\StudentGrade;
use Modules\Academics\Entities\Assessments;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Setting\Entities\SmsInstitutionGetway;
use Modules\Communication\Entities\SmsBatch;
use Modules\Communication\Entities\SmsLog;
use Modules\Communication\Entities\SmsMessage;
use Modules\Communication\Entities\SmsTemplate;
use Modules\Student\Entities\StudentParent;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\SmsSender;
class SendSmsResultController extends Controller
{

    private $studentProfile;
    private $studentGrade;
    private $assessments;
    private $academicHelper;
    private $smsInstitutionGetway;
    private $smsBatch;
    private $smsLog;
    private $smsMessage;
    private $smsTemplate;
    private $studentParent;
    private $smsSender;


    public function __construct(StudentProfileView $studentProfileView,StudentGrade $studentGrade,Assessments $assessments,SmsInstitutionGetway $smsInstitutionGetway,SmsBatch $smsBatch,AcademicHelper $academicHelper,SmsLog $smsLog, SmsMessage $smsMessage,SmsTemplate $smsTemplate,StudentParent $studentParent,SmsSender $smsSender)
    {
        $this->studentProfile=$studentProfileView;
        $this->studentGrade=$studentGrade;
        $this->assessments=$assessments;
        $this->academicHelper=$academicHelper;
        $this->smsInstitutionGetway=$smsInstitutionGetway;
        $this->smsBatch=$smsBatch;
        $this->smsMessage=$smsMessage;
        $this->smsLog=$smsLog;
        $this->smsTemplate=$smsTemplate;
        $this->studentParent=$studentParent;
        $this->smsSender=$smsSender;
    }

    public function SendSBAResultSMS(Request $request)
    {

        DB::beginTransaction();
        try {

//        return $request->all();
        // get assement category by assessment id
        $assessmentProfile = $this->assessments->where('id', $request->assessment)->first();
        $category = $assessmentProfile->grading_category_id;
        $assessment = $request->assessment;
        $assessmentName = $assessmentProfile->name;

        // sms batch
        $instituteId = $this->academicHelper->getInstitute();
        $campusId = $this->academicHelper->getCampus();
        $smsApiProfile = $this->smsInstitutionGetway->where('institution_id', $instituteId)->where('status', 1)->first();

        $smsbatch = $this->smsBatch->where('institution_id', $instituteId)->where('campus_id', $campusId)->first();
        $batch_count = $smsbatch->batch_count;

        // get message from sms_message table
        $smsResult = SmsTemplate::select('message')->where('template_name', 'ASSESSMENT')->where('institution_id', $instituteId)->where('campus_id', $campusId)->first();




        // get all student list
        $studentList = $this->studentProfile->where('batch', $request->batch)->where('section', $request->section)->get();

        $studentResult = array();
        foreach ($studentList as $student) {
            $studentGrade = [];
            $studentGradeList = $this->studentGrade->where('batch', $request->batch)->where('section', $request->section)->where('semester', $request->semester)->where('std_id', $student->std_id)->get();
            $Stduentresult = '';
            foreach ($studentGradeList as $singleGrade) {
                // stdProfile
                $stdProfile = $singleGrade->student();
                // Grade Mark Profile
                $gradeMarkProfile = $singleGrade->gradeMark();
                // mark Profile
                $markProfile = (array)json_decode($gradeMarkProfile->marks);
                // assessment and category  id
                $catId = 'cat_' . $category;
                $assId = 'ass_' . $assessment;
                // checking category and assessment key if exits
                if (array_key_exists($assId, (array)$markProfile[$catId])) {
                    // assProfile form gradeMark/result field
                    $assProfile = $markProfile[$catId]->$assId;

                    $assInfo[] = [
                        'ass_mark' => $assProfile->ass_mark,
                        'subject' => $singleGrade->classSubject()->subject_code,
                    ];

//                     $studentResult[$stdProfile->id] = $assInfo;
                    $Stduentresult .= ' ' . $singleGrade->classSubject()->subject_code . ': ' . $assProfile->ass_mark;
                }

            }

            // studentFull Namem
            $studentProfile = $student->student();
            $fullName = $studentProfile->first_name." ".$studentProfile->middle_name." ".$studentProfile->last_name;
            $rollNumber = $student->gr_no;
            $section_name = $student->section()->section_name;
            $batch_name = $student->batch()->batch_name;
            $result = $Stduentresult;
            $date = date('d-m-Y');


            // multiple staring replace
            $searchString = array("{assessment}","{name}", "{roll}", "{section}", "{batch}", "{result}", "{date}");
            $replaceString = array($assessmentName,$fullName, $rollNumber, $section_name, $batch_name, $result, $date);

            $message = str_replace($searchString, $replaceString, $smsResult->message);
            $smsMessage = new $this->smsMessage;
            $smsMessage->message =$message;
            $sendSms = $smsMessage->save();

            // parents number and profile

            $parentsProfile= (object)$this->studentParent->emergencyContact($student->std_id);
            if(!empty($parentsProfile)) {

                // sms log send sms
                $smsLog = new $this->smsLog();
                $smsLog->institution_id = $this->academicHelper->getInstitute();
                $smsLog->campus_id = $this->academicHelper->getCampus();
                $smsLog->user_id = $parentsProfile->id;
                $smsLog->user_no = $parentsProfile->mobile;;
                $smsLog->user_group = 4;
                $smsLog->message_id = $smsMessage->id;
                $smsLog->delivery_status = 1;
                $smsLog->sms_batch_id = $batch_count;
                $smsLog->save();
            }

        }

        //sms batch Count Update status
           $this->smsBatch->where('institution_id', $instituteId)->where('campus_id', $campusId)->update(['batch_count' => $batch_count + 1]);

        // send sms api call
            $batch_count = $smsLog->sms_batch_id;
//
            $smsApi = $this->getSmsApi();
            $smsMessage = $request->input('message');
            $smsApi = json_decode(str_replace("\\", "", $smsApi), true);
            $sender_id=$smsApiProfile->sender_id;
            //$smsApi = "https://api.mobireach.com.bd/SendTextMultiMessage?Username=raqib&Password=Abcd@1234&From=ALOKITO&To=";
            $this->smsSender->sendAssessmentMessage($batch_count, $smsApi['api_path'],'assessment' ,$sender_id);



            DB::commit();
            return 'success';
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            return $e;
        }

    }


    // sms api get function
    // get sms Api function
    public function  getSmsApi(){
        $instituteId=$this->academicHelper->getInstitute();
//        get sms api
        $api_path = $this->smsInstitutionGetway->select('api_path')->where('institution_id',$instituteId)->where('status',1)->first();
        return $api_path;
    }



}
