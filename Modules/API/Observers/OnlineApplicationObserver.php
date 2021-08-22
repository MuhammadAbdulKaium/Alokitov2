<?php

namespace Modules\API\Observers;
use Modules\Admission\Entities\ApplicantEnrollment;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\SmsSender;

class OnlineApplicationObserver
{

    // smsSender
    private  $smsSender;

    // construct
    public function __construct(SmsSender $smsSender)
    {
        $this->smsSender = $smsSender;
    }

    // online application enrollment created confirmation
    public function created(ApplicantEnrollment $enrollment)
    {
        // find applicant personal information
        $application = $enrollment->application();
        // find applicant personal information
        $applicantPersonalInfo = $enrollment->applicantPersonalInfo();

        Log::info('applicant user created');
        //
        Log::info($applicantPersonalInfo->gud_phone);
        // send sms to online applicant guardian phone number with applicant login details
       $this->smsSender->onlineApplicationSmsJob($application, $applicantPersonalInfo);
    }






}