<?php

namespace App\Http\Controllers;

use App\Jobs\AbsentJob;
use App\Jobs\FeesJob;
use App\Jobs\HscApplicationJob;
use App\Jobs\PasswordChangeJob;
use App\Jobs\ResultJob;
use Illuminate\Http\Request;
use App\Jobs\SendSms;
use App\Jobs\AttendanceJob;
use App\Jobs\AbsentAttendanceJob;
use App\Jobs\FeesGenerateJob;
use App\Jobs\FeesGenerateMultiJob;
use App\Jobs\BirthdayJob;
use App\Jobs\OnlineApplicationJob;
use App\Jobs\ApplicantPasswordResetJob;
use App\Jobs\ResutPublishJob;
use App\Jobs\DevicePresentJob;

class SmsSender
{
    // send multiSms function batch, smsapi, smsMessage

    public  function  sendMultiSms($sms_batch_id,$smsApi,$smsMessage,$sender_id){
        // get institute
        $institute_id=session()->get('institute');
        $job = (new SendSms($sms_batch_id,$smsApi,$smsMessage,$sender_id,$institute_id));
        dispatch($job);
    }

    // Attendance Job Call Function
    public  function create_attendance_job($studentIdList){
        $job = (new AttendanceJob($studentIdList));
        dispatch($job);
    }

    // Attendance Job Call Function
    public  function absent_attendance_job($studentIdList){
        $job = (new AbsentAttendanceJob($studentIdList));
        dispatch($job);
    }

    // Send Single Sms Job Call Function
    public  function  sendSingleSms($sms_batch_id,$smsApi,$smsMessage,$sender_id,$institute_id=null){
        $job = (new SendSms($sms_batch_id,$smsApi,$smsMessage,$sender_id,$institute_id));
        dispatch($job);
    }

    // Result Job Job Call Function
    public  function create_result_job($stdList,$checked_cat_ass,$category_id_list,$gradeMarkJson){
        $job = (new ResultJob($stdList,$checked_cat_ass,$category_id_list,$gradeMarkJson));
        dispatch($job);
    }

    // Fees Job  Call Function
    public  function create_fees_job($stdId,$invoicePaidId,$paidAmount){
        $job = (new FeesJob($stdId,$invoicePaidId,$paidAmount));
        dispatch($job);
    }

    // Absent Job  Call Function
    public static function create_absent_job($stdId){
        $job = (new AbsentJob($stdId));
        dispatch($job);
    }

    // fees generate job call function
    public static function create_fees_generate_job($stdId,$invoiceId,$amount){
        $job = (new FeesGenerateJob($stdId,$invoiceId,$amount));
        dispatch($job);
    }

    // fees generate job call function
    public static function create_fees_multisms_generate_job($fees_id,$studendList){
        $job = (new FeesGenerateMultiJob($fees_id,$studendList));
        dispatch($job);
    }

    public static function birthday_sms_job($studentIdList,$instituteIds, $campusIds){
            $job = (new BirthdayJob($studentIdList, $instituteIds, $campusIds));
            dispatch($job);
    }

    public function passwordChangeSmsJob($userID,$password){
        $job = (new PasswordChangeJob($userID,$password));
        dispatch($job);
    }

    public function onlineApplicationSmsJob($applicantProfile, $applicantPersonal) {
        $job = (new OnlineApplicationJob($applicantProfile,$applicantPersonal));
        dispatch($job);
    }

    // application password change SMS JOB
        public function applicatanPasswordRestSmsJob($applicantProfile,$newPassword) {
        $job = (new ApplicantPasswordResetJob($applicantProfile,$newPassword));
        dispatch($job);
    }


    // Send Single Sms Job Call Function
    public  function  passwordResetJob($sms_batch_id,$smsApi,$smsMessage,$sender_id,$institute_id){
        $job = (new SendSms($sms_batch_id,$smsApi,$smsMessage,$sender_id,$institute_id));
        dispatch($job);
    }


    // online sms job sent to Sa College HSC admission

    public function hscApplicationJob($applicantProfile) {
        $job = (new HscApplicationJob($applicantProfile));
        dispatch($job);
    }

    public function result_publish_job($examStatus){
        $job = (new ResutPublishJob($examStatus));
        dispatch($job);
    }


    // assement sms here
    public  function  sendAssessmentMessage($sms_batch_id,$smsApi,$smsMessage,$sender_id){
        // get institute
        $institute_id=session()->get('institute');
        $job = (new SendSms($sms_batch_id,$smsApi,$smsMessage,$sender_id,$institute_id));
        dispatch($job);
    }



    /// device attendace job finge or card present message
    public static function device_present_job($stdId,$institute,$campus){
        $job = (new DevicePresentJob($stdId,$institute,$campus));
        dispatch($job);
    }




}
