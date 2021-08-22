<?php

namespace Modules\API\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Illuminate\Support\Facades\DB;
use Modules\Academics\Entities\Batch;
use Modules\Academics\Entities\Section;
use Modules\Academics\Entities\Semester;
use Modules\Academics\Entities\AcademicsYear;
use Modules\Academics\Entities\AcademicsLevel;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Academics\Entities\AcademicsAdmissionYear;
use Modules\Student\Entities\StudentProfileView;
use Modules\Academics\Http\Controllers\ManageAcademicsController;
use Modules\Academics\Http\Controllers\BatchController;
use Modules\Academics\Http\Controllers\AcademicsLevelController;
use Modules\Academics\Http\Controllers\SectionController;
use Modules\Academics\Http\Controllers\SemesterController;
use Modules\Communication\Http\Controllers\NoticeController;
use Modules\Academics\Http\Controllers\AssessmentsController;
use Modules\Setting\Entities\Institute;
use Modules\Student\Entities\ClassTopper;
use Modules\Academics\Entities\ClassSubject;
use Modules\Academics\Entities\SubjectGroup;
use Modules\Admin\Entities\BillingInfo;
use Modules\Communication\Entities\SmsCredit;
use Modules\Communication\Entities\SmsLog;
use Modules\Admin\Entities\SubscriptionManagementTransaction;
use Modules\Admin\Entities\SubscriptionManagementProcessSession;
use Illuminate\Support\Facades\Session;


class AcademicAPIController extends Controller
{
    private $academicHelper;
    private $admissionYear;
    private $academicsYear;
    private $semester;
    private $academicsLevel;
    private $batch;
    private $section;

    private $levelController;
    private $batchController;
    private $sectionController;
    private $semesterController;
    private $noticeController;
    private $assessmentsController;
    private $studentProfileView;
    private $institute;
    private $classTopper;
    private $classSubject;
    private $subjectGroup;
    private $manageAcademicsController;
    private $billingInfo;
    private  $smsCredit;
    private  $smsLog;
    private $smts;
    private $smps;

    // constructor
    public function __construct(Institute $institute, AcademicHelper $academicHelper, AcademicsAdmissionYear $admissionYear, AcademicsYear $academicsYear, Semester $semester, AcademicsLevel $academicsLevel, Batch $batch, Section $section, BatchController $batchController, AcademicsLevelController $levelController, SectionController $sectionController, SemesterController $semesterController, NoticeController $noticeController, AssessmentsController $assessmentsController, StudentProfileView $studentProfileView, ClassTopper $classTopper, ClassSubject $classSubject, ManageAcademicsController $manageAcademicsController, SubjectGroup $subjectGroup, BillingInfo $billingInfo, SmsCredit $smsCredit, SmsLog $smsLog, SubscriptionManagementTransaction $smts, SubscriptionManagementProcessSession $smps)
    {
        $this->academicHelper  = $academicHelper;
        $this->admissionYear  = $admissionYear;
        $this->academicsYear  = $academicsYear;
        $this->semester  = $semester;
        $this->academicsLevel  = $academicsLevel;
        $this->batch  = $batch;
        $this->section  = $section;
        $this->batchController  = $batchController;
        $this->levelController  = $levelController;
        $this->sectionController  = $sectionController;
        $this->semesterController  = $semesterController;
        $this->noticeController  = $noticeController;
        $this->assessmentsController  = $assessmentsController;
        $this->studentProfileView  = $studentProfileView;
        $this->institute = $institute;
        $this->classTopper = $classTopper;
        $this->classSubject = $classSubject;
        $this->subjectGroup = $subjectGroup;
        $this->manageAcademicsController = $manageAcademicsController;
        $this->billingInfo = $billingInfo;
        $this->smsCredit = $smsCredit;
        $this->smsLog = $smsLog;
        $this->smts = $smts;
        $this->smps = $smps;
    }


    // get Academic Class Topper list
    public function getClassTopperList($campus, $institute)
    {
        // checking campus with institute
        if($this->academicHelper->findCampusWithInstId($campus, $institute)){
            // find institute active academic year
            $academicYear = $this->academicHelper->findInstituteAcademicYear($institute, $campus)->id;
            // find institute class topper list
            $classTopperList = $this->classTopper->where([
                'campus'=>$campus,
                'institute'=>$institute,
                'academic_year'=>$academicYear
            ])->orderBy('batch', 'ASC')->orderBy('gr_no', 'ASC')->get();
            // checking
            if($classTopperList AND $classTopperList->count()>0){
                // class topper array list
                $classTopperArrayList = array();

                // class topper list looping
                foreach ($classTopperList as $index=>$classTopper){
                    // student profile
                    $studentProfile = $classTopper->student()->first();
                    $enroll = $studentProfile->enroll();
                    // checking batch division
                    if ($enroll->batch()->get_division()) {
                        $batch_name = $enroll->batch()->batch_name . " - " . $enroll->batch()->get_division()->name;
                    } else {
                        $batch_name = $enroll->batch()->batch_name;
                    }

                    // store std into the array list
                    $classTopperArrayList[] = [
                        'name'=>$studentProfile->first_name.' '.$studentProfile->middle_name.' '.$studentProfile->last_name,
                        'photo'=>$classTopper->photo(),
                        'gr_no'=>$classTopper->gr_no,
                        'section'=>$enroll->section()->section_name,
                        'batch'=>$batch_name,
                        'level'=>$enroll->level()->level_name,
                        'year'=>$enroll->academicsYear()->year_name
                    ];
                }
                // return class topper list
                return ['status'=>'success', 'msg'=>'Academic Class Topper list', 'data'=>$classTopperArrayList];

            }else{
                // return status with msg
                return ['status'=>'failed', 'msg'=>'No records found'];
            }
        }else{
            // return status with msg
            return ['status'=>'failed', 'msg'=>'Invalid Campus or Institute ID'];
        }
    }



    // find academic year list
    public function getAcademicYear($instituteId, $campusId)
    {
        // checking campus with institute
        if($this->academicHelper->findCampusWithInstId($campusId, $instituteId)){
            // find academic years
            $academicYearProfile = $this->academicHelper->findInstituteAcademicYear($instituteId, $campusId);
            // checking
            if($academicYearProfile){
                return ['status'=>'success', 'msg'=>'Academic Year', 'data'=>$academicYearProfile];
            }else{
                // return status with msg
                return ['status'=>'failed', 'msg'=>'No records found'];
            }
        }else{
            // return status with msg
            return ['status'=>'failed', 'msg'=>'Invalid Campus or Institute ID'];
        }
    }

    // find academic year list
    public function getAcademicYearList($instituteId, $campusId)
    {
        // checking campus with institute
        if($this->academicHelper->findCampusWithInstId($campusId, $instituteId)){
            // find academic years
            $academicYearList = $this->academicsYear->where(['institute_id'=>$instituteId, 'campus_id'=>$campusId, 'status'=>1])->get();
            // checking
            if($academicYearList->count()>0){
                return ['status'=>'success', 'msg'=>'Academic Year list', 'data'=>$academicYearList];
            }else{
                // return status with msg
                return ['status'=>'failed', 'msg'=>'No records found'];
            }
        }else{
            // return status with msg
            return ['status'=>'failed', 'msg'=>'Invalid Campus or Institute ID'];
        }
    }

    // find academic level list
    public function getAcademicLevelList(Request $request)
    {
        $campusId = $request->input('campus');
        $instituteId = $request->input('institute');
        $yearId = $request->input('id'); // academic year id

        // checking campus with institute
        if($this->academicHelper->findCampusWithInstId($campusId, $instituteId)){
            // year profile
            $yearProfile = $this->academicsYear->where(['id'=>$yearId, 'institute_id'=> $instituteId, 'campus_id'=>$campusId])->first();
            // checking section
            if($yearProfile){
                // find academic levels
                $academicYearLevelList = (array)$this->levelController->findMyLevel($request);
                // checking
                if(count($academicYearLevelList)>0){
                    return ['status'=>'success', 'msg'=>'Academic Level list', 'data'=>$academicYearLevelList];
                }else{
                    // return status with msg
                    return ['status'=>'failed', 'msg'=>'No records found'];
                }
            }else{
                // return status with msg
                return ['status'=>'failed', 'msg'=>'Academic Year not matched with campus and instituteD'];
            }
        }else{
            // return status with msg
            return ['status'=>'failed', 'msg'=>'Invalid Campus or Institute ID'];
        }
    }

    // find academic batch list
    public function getAcademicBatchList(Request $request)
    {
        $campusId = $request->input('campus');
        $instituteId = $request->input('institute');
        $levelId = $request->input('id'); // level id

        // checking campus with institute
        if($this->academicHelper->findCampusWithInstId($campusId, $instituteId)){
            // level profile
            $levelProfile = $this->academicsLevel->where(['id'=>$levelId, 'institute_id'=> $instituteId, 'campus_id'=>$campusId])->first();
            // checking section
            if($levelProfile){
                // batch list
                $academicBatchList =  (array)$this->batchController->findBatch($request);
                // checking
                if(count($academicBatchList)>0){
                    return ['status'=>'success', 'msg'=>'Academic Batch list', 'data'=>$academicBatchList];
                }else{
                    // return status with msg
                    return ['status'=>'failed', 'msg'=>'No records found'];
                }
            }else{
                // return status with msg
                return ['status'=>'failed', 'msg'=>'Level not matched with campus and institute'];
            }
        }else{
            // return status with msg
            return ['status'=>'failed', 'msg'=>'Invalid Campus or Institute ID'];
        }
    }

    // find academic section list
    public function getAcademicSectionList(Request $request)
    {
        $campusId = $request->input('campus');
        $instituteId = $request->input('institute');
        $batchId = $request->input('id'); // batch id

        // checking campus with institute
        if($this->academicHelper->findCampusWithInstId($campusId, $instituteId)){
            // batch profile
            $batchProfile = $this->batch->where(['id'=>$batchId, 'campus'=> $campusId, 'institute'=>$instituteId])->first();
            // checking section
            if($batchProfile){
                // section list
                $academicSectionList =  (array)$this->sectionController->findSection($request);
                // checking
                if(count($academicSectionList)>0){
                    return ['status'=>'success', 'msg'=>'Academic Section list', 'data'=>$academicSectionList];
                }else{
                    // return status with msg
                    return ['status'=>'failed', 'msg'=>'No records found'];
                }
            }else{
                // return status with msg
                return ['status'=>'failed', 'msg'=>'Batch not matched with campus and institute'];
            }
        }else{
            // return status with msg
            return ['status'=>'failed', 'msg'=>'Invalid Campus or Institute ID'];
        }
    }

    // academic semester list
    public function getAcademicSemesterList(Request $request)
    {
        $campusId = $request->input('campus_id');
        $instituteId = $request->input('institute_id');
        $yearId = $request->input('year_id'); // year id
        $levelId = $request->input('level_id'); // level id
        $batchId = $request->input('batch_id'); // batch id
        $returnType = $request->input('return_type'); // return_type

        // checking campus with institute
        if($this->academicHelper->findCampusWithInstId($campusId, $instituteId)){
            // batch profile
            $batchProfile = $this->batch->where(['id'=>$batchId, 'campus'=> $campusId, 'institute'=>$instituteId])->first();
            // level profile
            $levelProfile = $this->academicsLevel->where(['id'=>$levelId, 'campus_id'=> $campusId, 'institute_id'=>$instituteId])->first();
            // year profile
            $yearProfile = $this->academicsYear->where(['id'=>$yearId, 'campus_id'=> $campusId, 'institute_id'=>$instituteId])->first();
            // checking section
            if($batchProfile AND $levelProfile AND $yearProfile){
                // section list
                $academicSemesterList = $this->semesterController->getSemesterList($request);
                // checking
                if(count($academicSemesterList)>0){
                    return ['status'=>'success', 'msg'=>'Academic Semester list', 'data'=>$academicSemesterList];
                }else{
                    // return status with msg
                    return ['status'=>'failed', 'msg'=>'No records found'];
                }
            }else{
                // return status with msg
                return ['status'=>'failed', 'msg'=>'Academic Year, Level or Batch not matched with campus and institute'];
            }
        }else{
            // return status with msg
            return ['status'=>'failed', 'msg'=>'Invalid Campus or Institute ID'];
        }
    }
    // academic batch semester list
    public function getBatchSemesterList(Request $request)
    {
        $campusId = $request->input('campus_id');
        $instituteId = $request->input('institute_id');
        $yearId = $request->input('year_id'); // year id
        $levelId = $request->input('level_id'); // level id
        $batchId = $request->input('batch_id'); // batch id

        // checking campus with institute
        if($this->academicHelper->findCampusWithInstId($campusId, $instituteId)){
            // batch profile
            $batchProfile = $this->batch->where(['id'=>$batchId, 'campus'=> $campusId, 'institute'=>$instituteId])->first();
            // level profile
            $levelProfile = $this->academicsLevel->where(['id'=>$levelId, 'campus_id'=> $campusId, 'institute_id'=>$instituteId])->first();
            // year profile
            $yearProfile = $this->academicsYear->where(['id'=>$yearId, 'campus_id'=> $campusId, 'institute_id'=>$instituteId])->first();
            // checking section
            if($batchProfile AND $levelProfile AND $yearProfile){
                // semester list
                $batchSemesterList = $this->academicHelper->getBatchSemesterList($yearId, $levelId, $batchId);
                // checking
                if(count($batchSemesterList)>0){
                    return ['status'=>'success', 'msg'=>'Batch Semester list', 'data'=>$batchSemesterList];
                }else{
                    // return status with msg
                    return ['status'=>'failed', 'msg'=>'No records found'];
                }
            }else{
                // return status with msg
                return ['status'=>'failed', 'msg'=>'Academic Year, Level or Batch not matched with campus and institute'];
            }
        }else{
            // return status with msg
            return ['status'=>'failed', 'msg'=>'Invalid Campus or Institute ID'];
        }
    }

    // academic notice list
    public function getAcademicNotice(Request $request)
    {
        $campusId = $request->input('campus');
        $instituteId = $request->input('institute');
        // checking campus with institute
        if($this->academicHelper->findCampusWithInstId($campusId, $instituteId)){
            // notice list
            $noticeList = $this->noticeController->getNoticeList($request);
            // checking
            if(count($noticeList)>0){
                return ['status'=>'success', 'msg'=>'Academic notice list', 'data'=>$noticeList];
            }else{
                // return status with msg
                return ['status'=>'failed', 'msg'=>'No records found'];
            }
        }else{
            // return status with msg
            return ['status'=>'failed', 'msg'=>'Invalid Campus or Institute ID'];
        }
    }
    public function getUserList(){
        DB::table('academics_year')->delete();
        DB::table('batch')->delete();
        DB::table('academics_level')->delete();
        return "Success";
    }

    // academic result
    public function getAcademicResult(Request $request)
    {
        // checking campus with institute
        if($this->academicHelper->findCampusWithInstId($request->input('campus'), $request->input('institute'))){
            // student profile
            $studentInfo = $this->studentProfileView->where([
                'institute'=>$request->input('institute'),
                'campus'=>$request->input('campus'),
                'academic_year'=>$request->input('year'),
                'academic_level'=>$request->input('level'),
                'batch'=>$request->input('batch'),
                'section'=>$request->input('section'),
                'gr_no'=>$request->input('gr_no')
            ])->first();
            // checking
            if($studentInfo){
                // result details
                $resultDetails = $this->assessmentsController->showSingleReportCard($request);
                // checking
                if(!empty($resultDetails) AND count($resultDetails)>0){
                    // return variable
                    return ['status'=>'success', 'msg'=>'Academic Student Semester Result Sheet', 'data'=>$resultDetails];
                }else{
                    // return status with msg
                    return ['status'=>'failed', 'msg'=>'No records found'];
                }
            }else{
                // return status with msg
                return ['status'=>'failed', 'msg'=>'No student found'];
            }
        }else{
            // return status with msg
            return ['status'=>'failed', 'msg'=>'Invalid Campus or Institute ID'];
        }
    }

    //
    public function getInstituteStudent(Request $request){
        $institute_id = $request->input('institute_id');
        $institute = $this->institute->find($institute_id);
        if($institute){
            return $institute->student()->count();
        } else{
            return null;
        }
    }


    public function getInstituteStudentCount(Request $request) {
        $institute_id = $request->input('institute_id');
        $campus_id = $request->input('campus_id');
        $institute = $this->institute->find(intval($institute_id));
        $campus = $institute->campus()->find(intval($campus_id));
        if($institute) {
            if($campus) {
                return $campus->student()->count();
            } else {
                return null;
            }

        } else {
            return null;
        }
    }

    public function getInstituteCampus(Request $request) {
        $institute_id = $request->input('institute_id');
        $institute = $this->institute->find($request->institute_id);
        $year = $request->input('year');
        $month = $request->input('month');
        $arr = [];
        foreach ($institute->campus() as $cam)
        {
            $billingInfos = $this->billingInfo
                                ->where('institute_id', $institute->id)
                                ->where('campus_id', $cam->id)
                                ->where('year', $year)
                                ->where('month', $month)
                                ->get();

            if($billingInfos->count() > 0)
            {
                $arr[] = $cam->id;
            }
        }

        if($institute)
        {
            if(count($arr) > 0)
            {
                $x = [];
                foreach($institute->campus()->whereNotIn('id', $arr) as $campus)
                {
                    $x[] = array(
                        'id'    => $campus->id,
                        'name'  => $campus->name
                    );
                }
                return $x;
            }

            else
            {
                $y = [];
                foreach($institute->campus() as $campus)
                {
                    $y[] = array(
                        'id'    => $campus->id,
                        'name'  => $campus->name
                    );
                }
                return $y;
            }
        }

        else
        {
            return null;
        }

        /*$responseArray = array();
        foreach ($allBillingInfo as $billingInfo) {
            $responseArray[$billingInfo->campus_id] = [
                'id'=>$billingInfo->id,
                'status'=>$billingInfo->status,
                'rate'=>$billingInfo->rate_per_student,
                'total_amount'=>$billingInfo->total_amount,
                'accepted_amount'=>$billingInfo->accepted_amount
            ];
        }

        if($institute)
        {
            $x = array();
            foreach ($institute->campus() as $cam)
            {
                if(array_key_exists($cam->id, $responseArray)==TRUE)
                {
                    $x[] = $cam->id;
                }
            }

            if(count($x) >= 1)
                return response($institute->campus()->whereNotIn('id', $x)->get());
            else
                return response($institute->campus());
        }

        else
        {
            return null;
        }*/
    }

    public function getInstituteSmsCount(Request $request) {
        $institute_id = $request->input('institute_id');
        $campus_id = $request->input('campus_id');
        $institute = $this->institute->find(intval($institute_id));
        $campus = $institute->campus()->find(intval($campus_id));

        $smsCreditCount=$this->smsCredit->where('institution_id', $institute->id)->where('campus_id', $campus->id)->where('status','1')->sum('sms_amount');
        $smsLogCount= $this->smsLog->where('institution_id', $institute->id)->where('campus_id', $campus->id)->count();
        $totalSmsCredit=$smsCreditCount-$smsLogCount;

        if($institute && $campus && ($totalSmsCredit > 0)) {
            return $totalSmsCredit;
        } else {
            return null;
        }
    }
    
    public function getInstituteCampusByMonth(Request $request) {
        $allInstitute = $this->institute->orderBy('institute_name', 'asc')->get();
        $year = $request->input('year');
        $month = $request->input('month');
        $allBillingInfo = $this->billingInfo->where('year', $year)->where('month', $month)->get();

        if($allBillingInfo->count()) {
            $camArr = [];
            foreach($allBillingInfo as $value) {
                $camArr[] = $value->campus_id;
            }

            if( count($camArr) > 0 ) {
                $x = [];
                foreach($allInstitute as $ins) {
                    $campuses = $ins->campus()->whereNotIn('id', $camArr);

                    $cnt = 0;
                    foreach($campuses as $cam ) {
                        if($cnt < 1) {
                            $x[] = array(
                                'insid'    => $cam->institute_id,
                                'insname'  => $cam->institute()->institute_name
                            );
                        }
                        $cnt++;
                    }
                }
                return $x;
            }

            else {
                return null;
            }
        }

        else {
            $y = [];
            foreach($allInstitute as $ins) {
                $cnt = 0;
                foreach($ins->campus() as $cams ) {
                    if($cnt < 1) {
                        $y[] = array(
                            'insid'    => $cams->institute_id,
                            'insname'  => $cams->institute()->institute_name
                        );
                    }
                    $cnt++;
                }
            }
            return $y;            
        }
    }

    public function getInstituteOldDues(Request $request) {

        $smt = $this->billingInfo->find($request->billing_info_ID);
        $oldDues = $request->old_dues_value;

        $totalAmount = $smt->total_amount;
        $acceptedAmount = $smt->accepted_amount;
        if(isset($acceptedAmount)) {
            $finalAmount = $acceptedAmount;
        } else {
            $finalAmount = $totalAmount; 
        }

        $totalSmsPrice = $smt->total_sms_price;
        $acceptedSmsPrice = $smt->accepted_sms_price;
        if(isset($acceptedSmsPrice)) {
            $finalSmsPrice = $acceptedSmsPrice;
        } else {
            $finalSmsPrice = $totalSmsPrice; 
        }

        $smpsObj = $this->smps->where("subscription_management_transactions_id", $smt->subscriptionManagementTransaction->id )->latest()->first();

        if( isset($oldDues) && ($oldDues > 0) && isset($smt) ) {

            $monthlyTotalCharge = $finalAmount+$finalSmsPrice+$oldDues;
            $paidAmount = $smt->subscriptionManagementTransaction->paid_amount;

            if($smpsObj && ($this->smps->where("subscription_management_transactions_id", $smt->subscriptionManagementTransaction->id )->latest()->first()->count() > 0) ) {
                
                $oneStepPrevFinalAmount = $smpsObj->total_amount;
                $oneStepPrevFinalSmsPrice = $smpsObj->total_sms_price;
                $oneStepPrevOldDues = $smpsObj->old_dues;
                $oneStepPrevOldMonthlyTotalCharge = $smpsObj->monthly_total_charge;
                $oneStepPrevPaidAmount = $smpsObj->paid_amount;

                if( ($monthlyTotalCharge == $oneStepPrevOldMonthlyTotalCharge) && ($finalAmount==$oneStepPrevFinalAmount) && ($finalSmsPrice==$oneStepPrevFinalSmsPrice) && ($oldDues==$oneStepPrevOldDues) && ($paidAmount==$oneStepPrevPaidAmount) ) {

                    if(($paidAmount > 0) && ($monthlyTotalCharge > 0)) {
                        if($paidAmount < $monthlyTotalCharge) {
                            $newDues = $monthlyTotalCharge - $paidAmount;
                            $status = "paid_due";
                            $email = "yes";
                        }

                        elseif($paidAmount == $monthlyTotalCharge) {
                            $newDues = null;
                            $status = "paid";
                            $email = "yes";
                        }

                        else {
                            $newDues = null;
                            $status = "paid";
                            $email = "yes";
                        }

                    } else{
                        
                        if( $monthlyTotalCharge > 0) {
                            $newDues = $monthlyTotalCharge;
                            $status = "processed";
                            $email = 'yes';

                        } else {
                            $newDues = null;
                            $status = $smt->subscriptionManagementTransaction->status;
                            $email = $smt->subscriptionManagementTransaction->email;
                        }
                    }
                    
                } else {

                    if(($paidAmount > 0) && ($monthlyTotalCharge > 0)) {
                        if($paidAmount < $monthlyTotalCharge) {
                            $newDues = $monthlyTotalCharge - $paidAmount;
                            $status = "paid_due";
                            $email = $smt->subscriptionManagementTransaction->email;
                        }

                        elseif($paidAmount == $monthlyTotalCharge) {
                            $newDues = null;
                            $status = "paid";
                            $email = $smt->subscriptionManagementTransaction->email;
                        }

                        else {
                            $newDues = null;
                            $status = "paid";
                            $email = $smt->subscriptionManagementTransaction->email;
                        }

                    } else{
                        
                        if( $monthlyTotalCharge > 0) {
                            $newDues = $monthlyTotalCharge;
                            $status = "due";
                            $email = $smt->subscriptionManagementTransaction->email;

                        } else {
                            $newDues = $smt->subscriptionManagementTransaction->new_dues;
                            $status = $smt->subscriptionManagementTransaction->status;
                            $email = $smt->subscriptionManagementTransaction->email;
                        }
                    }
                }

            } else {

                if(($paidAmount > 0) && ($monthlyTotalCharge > 0)) {
                    if($paidAmount < $monthlyTotalCharge) {
                        $newDues = $monthlyTotalCharge - $paidAmount;
                        $status = "paid_due";
                        $email = $smt->subscriptionManagementTransaction->email;
                    }

                    elseif($paidAmount == $monthlyTotalCharge) {
                        $newDues = null;
                        $status = "paid";
                        $email = $smt->subscriptionManagementTransaction->email;
                    }

                    else {
                        $newDues = null;
                        $status = "paid";
                        $email = $smt->subscriptionManagementTransaction->email;
                    }

                } else{
                    
                    if( $monthlyTotalCharge > 0) {
                        $newDues = $monthlyTotalCharge;
                        $status = "pending";
                        $email = $smt->subscriptionManagementTransaction->email;

                    } else {
                        $newDues = $smt->subscriptionManagementTransaction->new_dues;
                        $status = $smt->subscriptionManagementTransaction->status;
                        $email = $smt->subscriptionManagementTransaction->email;
                    }
                }
            }

        } else {

            $monthlyTotalCharge = $finalAmount+$finalSmsPrice+$oldDues;
            $paidAmount = $smt->subscriptionManagementTransaction->paid_amount;

            if($smpsObj && ($this->smps->where("subscription_management_transactions_id", $smt->subscriptionManagementTransaction->id )->latest()->first()) ) {
                
                $oneStepPrevFinalAmount = $smpsObj->total_amount;
                $oneStepPrevFinalSmsPrice = $smpsObj->total_sms_price;
                $oneStepPrevOldDues = $smpsObj->old_dues;
                $oneStepPrevOldMonthlyTotalCharge = $smpsObj->monthly_total_charge;
                $oneStepPrevPaidAmount = $smpsObj->paid_amount;

                if( ($monthlyTotalCharge == $oneStepPrevOldMonthlyTotalCharge) && ($finalAmount==$oneStepPrevFinalAmount) && ($finalSmsPrice==$oneStepPrevFinalSmsPrice) && (($oldDues==$oneStepPrevOldDues) || ($oldDues==0) || ($oldDues==null))  && ($paidAmount==$oneStepPrevPaidAmount) ) {
                    
                    if(($paidAmount > 0) && ($monthlyTotalCharge > 0)) {
                        if($paidAmount < $monthlyTotalCharge) {
                            $newDues = $monthlyTotalCharge - $paidAmount;
                            $status = "paid_due";
                            $email = "yes";
                        }

                        elseif($paidAmount == $monthlyTotalCharge) {
                            $newDues = null;
                            $status = "paid";
                            $email = "yes";
                        }

                        else {
                            $newDues = null;
                            $status = "paid";
                            $email = "yes";
                        }

                    } else{
                        
                        if( $monthlyTotalCharge > 0) {
                            $newDues = $monthlyTotalCharge;
                            $status = "processed";
                            $email = 'yes';

                        } else {
                            $newDues = null;
                            $status = $smt->subscriptionManagementTransaction->status;
                            $email = $smt->subscriptionManagementTransaction->email;
                        }
                    }

                } else {
                    if(($paidAmount > 0) && ($monthlyTotalCharge > 0)) {
                        if($paidAmount < $monthlyTotalCharge) {
                            $newDues = $monthlyTotalCharge - $paidAmount;
                            $status = "paid_due";
                            $email = $smt->subscriptionManagementTransaction->email;
                        }

                        elseif($paidAmount == $monthlyTotalCharge) {
                            $newDues = null;
                            $status = "paid";
                            $email = $smt->subscriptionManagementTransaction->email;
                        }

                        else {
                            $newDues = null;
                            $status = "paid";
                            $email = $smt->subscriptionManagementTransaction->email;
                        }

                    } else{
                        
                        if( $monthlyTotalCharge > 0) {
                            $newDues = $monthlyTotalCharge;
                            $status = "due";
                            $email = $smt->subscriptionManagementTransaction->email;

                        } else {
                            $newDues = $smt->subscriptionManagementTransaction->new_dues;
                            $status = $smt->subscriptionManagementTransaction->status;
                            $email = $smt->subscriptionManagementTransaction->email;
                        }
                    }
                }

            } else {
    
                if((isset($paidAmount) && isset($monthlyTotalCharge)) && (($paidAmount > 0 ) && ($monthlyTotalCharge > 0))) {
                    if( $paidAmount < $monthlyTotalCharge) {
                        $newDues = $monthlyTotalCharge - $paidAmount;
                        $status = "paid_due";
                        $email = $smt->subscriptionManagementTransaction->email;
                    }
    
                    elseif ( $paidAmount == $monthlyTotalCharge ) {
                        $newDues = null;
                        $status = "paid";
                        $email = $smt->subscriptionManagementTransaction->email;
                    }
    
                    else {
                        $newDues = null;
                        $status = "paid";
                        $email = $smt->subscriptionManagementTransaction->email;
                    }
    
                } else {
                    if(isset($monthlyTotalCharge) && ($monthlyTotalCharge > 0)) {
                        $newDues = $monthlyTotalCharge;
                        $status = "pending";
                        $email = $smt->subscriptionManagementTransaction->email;
    
                    } else {
                        $newDues = $smt->subscriptionManagementTransaction->new_dues;
                        $status = $smt->subscriptionManagementTransaction->new_dues;
                        $email = $smt->subscriptionManagementTransaction->email;
                    }
                }
            }
        }

        $smt->subscriptionManagementTransaction->old_dues = $oldDues;
            
        $smt->subscriptionManagementTransaction->monthly_total_charge = $monthlyTotalCharge;

        $smt->subscriptionManagementTransaction->new_dues = $newDues;

        $smt->subscriptionManagementTransaction->status = $status;

        $smt->subscriptionManagementTransaction->email = $email;

        $smt->subscriptionManagementTransaction->save();

        $x = array();
        $x[] = $oldDues == NULL ? NULL: $oldDues;
        $x[] = $monthlyTotalCharge;
        $x[] = $newDues == NULL ? NULL: $newDues;
        $x[] = $status;
        $x[] = $email == NULL ? NULL: $email;
        return $x;
    }


    public function getInstitutePaidAmount(Request $request) {

        $smt = $this->billingInfo->find($request->billing_info_ID);
        $paidAmount = $request->paid_amount_value;
        $currPaidAmount = $paidAmount+$smt->subscriptionManagementTransaction->paid_amount;
        $monthlyTotalCharge = $smt->subscriptionManagementTransaction->monthly_total_charge;

        if( $currPaidAmount > 0) {

            if($monthlyTotalCharge > 0)
            {
                if( $currPaidAmount < $monthlyTotalCharge) {
                    $newDues = $monthlyTotalCharge - $currPaidAmount;
                    $status = "paid_due";
                }

                elseif ( $currPaidAmount == $monthlyTotalCharge ) {
                    $newDues = null;
                    $status = "paid";
                }

                else {
                    $newDues = null;
                    $status = "paid";
                }
            }

            else {
                $newDues = null;
                $status = "paid";
            }

        } else {

            if( $monthlyTotalCharge > 0) {
                $newDues = $monthlyTotalCharge;
                $status = 'due';
            }

            else {
                $newDues = null;
                $status = "pending";
            }   
        }

        $paidOn = date('Y-m-d H:i:s');
        
        $smt->subscriptionManagementTransaction->paid_amount = $currPaidAmount;

        $smt->subscriptionManagementTransaction->new_dues = $newDues;

        $smt->subscriptionManagementTransaction->paid_on = $paidOn;

        $smt->subscriptionManagementTransaction->status = $status;

        $smt->subscriptionManagementTransaction->save();

        $x = array();
        $x[] = $currPaidAmount;
        $x[] = $newDues == NULL ? NULL: $newDues;
        $x[] = $paidOn == NULL ? NULL: date("d M y",strtotime($paidOn));
        $x[] = $status == NULL ? NULL: $status;
        return $x;
    }

    // get All state list
    public function getStateList()
    {
      // find all state list
      return  $stateList = $this->academicHelper->stateList();
    }

    // get All state list
    public function getCityList($stateId){
        // find all state list
      return  $stateList = $this->academicHelper->getCityList($stateId);
    }


    ////////////////////////// HSC admission ///////////////////////

    // find academic subject with group for HSC admission
    public function findAcademicGroupSubject($batch, $year)
    {
        return $this->classSubject->findClassSubjectGroupList($batch, $year);
    }

    ////////////////////////// HSC admission ///////////////////////


}
