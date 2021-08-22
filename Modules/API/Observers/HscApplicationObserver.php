<?php

namespace Modules\API\Observers;
use Modules\Admission\Entities\HscApplicant;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\SmsSender;
use Modules\Academics\Entities\ClassSubStudent;

class HscApplicationObserver
{

    // smsSender
    private  $smsSender;

    // construct
    public function __construct(SmsSender $smsSender)
    {
        $this->smsSender = $smsSender;
    }

    // online application enrollment created confirmation
    public function created(HscApplicant $hscEnrollment)
    {
        // find applicant personal information
        $application = $hscEnrollment;
        // count std selected subject
        $this->countSubject($hscEnrollment);

        // log applicant profile
        Log::info('applicant user created');
        Log::info($application);

        // send sms to online applicant guardian phone number with applicant login details
        $this->smsSender->hscApplicationJob($application);
    }


    // count applicant subject
    public function countSubject($hscEnrollment)
    {
        $classId = $hscEnrollment->batch;
        $yearId = $hscEnrollment->year;
        $campusId = $hscEnrollment->campus_id;
        $instituteId = $hscEnrollment->institute_id;
        // my subject group
        $mySubGroup = (array) json_decode($hscEnrollment->group_list);
        // array value to key exchange
        $mySubGroup = array_flip($mySubGroup);
        // find batch group list
        $batchGroupList = ClassSubStudent::where(['class_id'=>$classId, 'year_id'=>$yearId, 'campus_id'=>$campusId, 'institute_id'=>$instituteId])->get();
        // batch group list checking
        if($batchGroupList->count()>0){
            // batch subject group looping
            foreach ($batchGroupList as $batchGroup){
                // checking subject group in the std subject / Group choice list
                if(array_key_exists($batchGroup->subject_group, $mySubGroup)){
                    $batchGroup->increment('std_admit',1);
                }
            }
        }
    }






}