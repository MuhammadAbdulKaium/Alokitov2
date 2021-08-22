<?php

namespace Modules\Admission\Http\Controllers;

use App\Address;
use App\Content;
use App\Models\Role;
use App\User;
use App\UserInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Student\Entities\StdRegister;
use Modules\Student\Entities\StudentAttachment;
use Modules\Student\Entities\StudentInformation;
use Modules\Student\Entities\StudentEnrollment;
use Modules\Student\Entities\StdEnrollHistory;
use Modules\Student\Entities\StudentParent;
use Modules\Student\Entities\StudentGuardian;
use Modules\Admission\Entities\HscApplicant;
use Modules\Academics\Entities\ClassSubject;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Academics\Entities\AdditionalSubject;
use Modules\Academics\Entities\Batch;
use App;

class HscAppController extends Controller
{
    private $user;
    private $role;
    private $carbon;
    private $userInfo;
    private $hscApplicant;
    private $classSubject;
    private $academicHelper;
    private $studentParent;
    private $studentGuardian;
    private $stdEnrollHistory;
    private $studentEnrollment;
    private $studentInformation;
    private $additionalSubject;
    private $batch;
    private $stdRegister;

    public function __construct(User $user, UserInfo $userInfo, Role $role, StudentInformation $studentInformation, StudentEnrollment $studentEnrollment, StdEnrollHistory $stdEnrollHistory, HscApplicant $hscApplicant, ClassSubject $classSubject, AcademicHelper $academicHelper, StudentParent $studentParent, StudentGuardian $studentGuardian, Carbon $carbon, AdditionalSubject $additionalSubject, Batch $batch, StdRegister $stdRegister)
    {
        $this->user = $user;
        $this->role = $role;
        $this->carbon = $carbon;
        $this->userInfo = $userInfo;
        $this->hscApplicant = $hscApplicant;
        $this->classSubject = $classSubject;
        $this->academicHelper = $academicHelper;
        $this->studentInformation = $studentInformation;
        $this->studentEnrollment = $studentEnrollment;
        $this->stdEnrollHistory = $stdEnrollHistory;
        $this->studentParent = $studentParent;
        $this->studentGuardian = $studentGuardian;
        $this->additionalSubject = $additionalSubject;
        $this->batch = $batch;
        $this->stdRegister = $stdRegister;
    }

    // manage enquiry view page
    public function manageEnquiry()
    {
        // academic year list
        $academicYears = $this->academicHelper->getAllAcademicYears();
        // return view with variables
        return view('admission::hsc-application.enquiry', compact('academicYears'));
    }

    // find applicant list
    public function findApplicantList(Request $request)
    {
        // request details
        $year = $request->input('year', null);
        $level = $request->input('level', null);
        $batch = $request->input('batch', null);
        $status = $request->input('status', null);
        $searchType = $request->input('search_type', 'enquiry');
        // institute details
        $campus = $this->academicHelper->getCampus();
        $institute = $this->academicHelper->getInstitute();
        // qry maker
        $qry = ['year'=>$year,'campus_id'=>$campus, 'institute_id'=>$institute];
        // checking status
        if($level){ $qry['level'] = $level;}
        if($batch){ $qry['batch'] = $batch;}
        // checking
        if($status!=null AND $searchType=='enquiry'){
            $qry['p_status'] = $status;
        }else if($status!=null AND $searchType=='admission'){
            // $qry['p_status'] = 1;
            $qry['a_status'] = $status;
        }

        // find applicant list
        $applicantList = $this->hscApplicant->where($qry)->orderBy('a_status', 'DSC')->orderBy('a_no', 'ASC')->limit(1000)->get();

        //query for get batch where
        $batchQuery = ['academics_level_id'=>$level, 'campus'=>$campus, 'institute'=>$institute];
        // get college batch here
        $collegeBatchs=$this->batch->where($batchQuery)->get();

        // checking search type
        if($searchType=='admission'){
            // return view with variables
            return view('admission::hsc-application.modals.admission', compact('applicantList','collegeBatchs'));
        }else{
            // return view with variables
            return view('admission::hsc-application.modals.enquiry', compact('applicantList'));
        }

    }

    // find single applicant profile
    public function findSingleApplicant($applicantId)
    {
        // institute details
        $campus = $this->academicHelper->getCampus();
        $institute = $this->academicHelper->getInstitute();
        // find applicant profile
        if($appProfile = $this->hscApplicant->find($applicantId)){
            // checking applicant institute
            if(($appProfile->campus_id!=$campus) AND ($appProfile->institute_id!=$institute)) return abort(404);
            // class subject list
            $classSubject = (array) $this->classSubject->getClassSubjectList($appProfile->batch);
            // class group subject list
            $groupSubject = (array) $this->classSubject->findClassSubjectGroupList($appProfile->batch);
            // return applicant profile
            return view('admission::hsc-application.applicant', compact('appProfile', 'groupSubject', 'classSubject'));
        }else{
            return abort(404);
        }
    }


    // downlaod single applicant
    public function downloadSingleApplicant($applicantId)
    {
        // institute details
        // $campus = $this->academicHelper->getCampus();
        // $institute = $this->academicHelper->getInstitute();

        //institute Proifle
        // $instituteInfo=$this->academicHelper->getInstituteProfile();
        // find applicant profile
        if($appProfile = $this->hscApplicant->find($applicantId)){
            //institute Profile
            $instituteInfo=$this->academicHelper->findInstitute($appProfile->institute_id);
            // checking applicant institute
            // if(($appProfile->campus_id!=$campus) AND ($appProfile->institute_id!=$institute)) return abort(404);
            // class subject list
            $classSubject = (array) $this->classSubject->getClassSubjectList($appProfile->batch);
            // class group subject list
            $groupSubject = (array) $this->classSubject->findClassSubjectGroupList($appProfile->batch);
            // return a pplicant profileview()->share(compact('fpt_list', 'instituteInfo','report_type'));

//            return view('admission::hsc-application.reports.applicant_profile',compact('appProfile','groupSubject'));
            //generate PDf
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView('admission::hsc-application.reports.applicant_profile',compact('appProfile', 'groupSubject','instituteInfo', 'classSubject'))->setPaper('a4', 'portrait');
            // return $pdf->stream();
            $downloadFileName = $appProfile->username.'_hsc_application.pdf';
            return $pdf->download($downloadFileName);

        }else{
            return abort(404);
        }
    }


    // admission report
    public function downloadHscAdmissionReport(Request $request)
    {
        // request details
        $year = $request->input('year');
        $level = $request->input('level');
        $batch = $request->input('batch');
        $status = $request->input('status');
        $reportType = $request->input('report_type');
        $inputs = json_encode($request->all());
        // institute details
        $campus = $this->academicHelper->getCampus();
        $institute = $this->academicHelper->getInstitute();
        // 'year'=>$year,'level'=>$level,'batch'=>$batch,'a_status'=>$status, 'p_status'=>1,'campus_id'=>$campus,'institute_id'=>$institute
        // find applicant list
        $applicantList = $this->hscApplicant->where([
            'year'=>$year,'level'=>$level,'batch'=>$batch,'a_status'=>$status,'campus_id'=>$campus,'institute_id'=>$institute
        ])->limit(500)->orderBy('a_no','ASC')->get();
        //institute Profile
        $instituteInfo = $this->academicHelper->findInstitute($institute);
        // class subject list
        $classSubList = (array) $this->classSubject->getClassSubjectList($batch);
        // class group subject list
        $groupSubList = (array) $this->classSubject->findClassSubjectGroupList($batch);

        // view share
        view()->share(compact('applicantList', 'instituteInfo','classSubList', 'groupSubList', 'inputs'));
        //generate PDf
        $pdf = App::make('dompdf.wrapper');
        $pdf->setPaper('a4', 'portrait');
        $pdf->loadView('admission::hsc-application.reports.general-admission-report');


//        // checking report type
//        if($reportType=='board_1'){
//            $pdf->loadView('admission::hsc-application.reports.board-admission-report');
//        }else if($reportType=='board_2'){
//            $pdf->loadView('admission::hsc-application.reports.board-admission-report');
//        }else{
//            $pdf->loadView('admission::hsc-application.reports.general-admission-report');
//        }

        // return
        return $pdf->stream();
    }


    // manage applicant promotion
    public function manageApplicantPromotion()
    {
        // academic year list
        $academicYears = $this->academicHelper->getAllAcademicYears();
        // return view with variables
        return view('admission::hsc-application.admission', compact('academicYears'));
    }

    // store applicant promotion
    public function storeApplicantPromotion(Request $request)
    {
        // request details
        $batch = $request->input('batch');
        $batch_id = $request->input('batch_id');
        $section = $request->input('section');
        $year = $request->input('academic_year');
        $level = $request->input('academic_level');
        $appStatus = $request->input('applicant_status');
        $promoteType = $request->input('promote_type');
        // applicant list
        $applicantList = $request->input('app_list');
        // institute details
        $campus = $this->academicHelper->getCampus();
        $institute = $this->academicHelper->getInstitute();
        $admissionYear = $this->academicHelper->getAdmissionYear();
        $instituteProfile = $this->academicHelper->getInstituteProfile();
        // qry maker
        $qry = ['year'=>$year,'level'=>$level,'batch'=>$batch, 'campus_id'=>$campus, 'institute_id'=>$institute];
        // student admission loop counter
        $stdAddCounter = 0;

        // Start transaction!
        DB::beginTransaction();

        try {

            // applicant list looping
            foreach ($applicantList as $applicantId=>$applicationNo){
                // find checking applicant profile
                if($applicantProfile = $this->hscApplicant->where('id', $applicantId)->where($qry)->first()){
                    // checking promote type
                    if($promoteType=='APPROVED'){
                        // create new std user account for the applicant
                        $userFullName = $applicantProfile->s_name;
                        // institute alias
                        $instAlias = $instituteProfile->institute_alias;
                        // create user profile for student
                        $userProfile = $this->user->create([
                            'name' =>$userFullName,
                            'email' => $instAlias.$applicantProfile->username.'@gmail.com',
                            'username' => $instAlias.$applicantProfile->username,
                            'password'=> bcrypt(123456)
                        ]);
                        // checking user profile
                        if($userProfile){
                            // create userInfo profile for student
                            if($userInfoProfile = $this->userInfo->create(['user_id' =>$userProfile->id, 'campus_id' =>$campus, 'institute_id' =>$institute])){
                                // studentRoleProfile
                                $studentRoleProfile = $this->role->where('name', 'student')->first();
                                // assigning student role to this user
                                $userProfile->attachRole($studentRoleProfile);
                                // create std profile
                                $studentProfile = $studentProfile = $this->studentInformation->create([
                                    'user_id'     => $userProfile->id,
                                    'type'        => 1,
                                    //'title'       => $applicantProfile->title,
                                    'first_name'  => $applicantProfile->s_name,
                                    'middle_name' => '',
                                    'last_name'   => '',
                                    'bn_fullname'   => $applicantProfile->s_name_bn,
                                    'gender'      => $applicantProfile->gender=='0'?'Male':'Female',
                                    'dob'         => date('Y-m-d', strtotime($applicantProfile->b_date)),
                                    'blood_group' => $applicantProfile->b_group,
                                    'email'       => $instAlias.$applicantProfile->username.'@gmail.com',
                                    'phone'       => $applicantProfile->s_mobile,
                                    'religion'       => $applicantProfile->religion,
                                    'nationality' => 1,
                                    'passport_no' => $applicantProfile->a_no,
                                    'campus'      => $applicantProfile->campus_id,
                                    'institute'   => $applicantProfile->institute_id,
                                ]);
                                // checking user profile
                                if($studentProfile){
                                    // create student enrollment
                                    $studentEnrollment = $this->studentEnrollment->create([
                                        'std_id'         => $studentProfile->id,
                                        // 'gr_no'          => $singleStudentInfo['gr_no'],
                                        'academic_level' => $applicantProfile->level,
                                        'batch'          => $batch_id,
                                        'section'        => $section,
                                        'academic_year'  => $applicantProfile->year,
                                        'admission_year' => $admissionYear,
                                        'enroll_status' => 'IN_PROGRESS',
                                        'batch_status' => 'IN_PROGRESS',
                                        'enrolled_at'    => date('Y-m-d', strtotime($this->carbon->now()))
                                    ]);
                                    // checking enrollment
                                    if($studentEnrollment){
                                        // create enrollment history
                                        $studentEnrollment = $this->stdEnrollHistory->create([
                                            'enroll_id'         => $studentEnrollment->id,
                                            // 'gr_no'          => $singleStudentInfo['gr_no'],
                                            'batch'          => $batch_id,
                                            'section'        => $section,
                                            'academic_level' => $applicantProfile->level,
                                            'academic_year'  => $applicantProfile->year,
                                            'enroll_status' => 'IN_PROGRESS',
                                            'batch_status' => 'IN_PROGRESS',
                                            'admission_year' => $admissionYear,
                                            'enrolled_at'    => date('Y-m-d', strtotime($this->carbon->now()))
                                        ]);
                                        // checking student enrollment history
                                        if($studentEnrollment){
                                            // create guardian profile
                                            if($this->storeHscStudentGuardian($studentProfile->id, $applicantProfile)){
                                                // create student address
                                                if($this->storeHscStudentAddress($userProfile->id, $applicantProfile)){
                                                    // upload Applicant's photo
                                                    if($this->applicantPhotoUploader($studentProfile->id, $applicantProfile->std_photo)){
                                                        // std ssc info
                                                        if($this->storeSscInfo($studentProfile->id, $applicantProfile)){
                                                            // now create student additional subject list
                                                            $additionalSubject = $this->additionalSubject->create([
                                                                'std_id'         => $studentProfile->id,
                                                                'batch'          => $batch_id,
                                                                'section'        => $section,
                                                                'a_year' => $applicantProfile->year,
                                                                'sub_list' => $applicantProfile->sub_list,
                                                                'group_list' => $applicantProfile->group_list,
                                                                'campus' => $applicantProfile->campus_id,
                                                                'institute' => $applicantProfile->institute_id
                                                            ]);
                                                            // checking additional subject
                                                            if($additionalSubject){
                                                                // update application status
                                                                $applicantProfile->a_status = 1;
                                                                // checking applicant profile
                                                                if($applicantProfile->save()){
                                                                    // student add loop counter
                                                                    $stdAddCounter += 1;
                                                                }else{
                                                                    return ['status'=>false, 'msg'=>'Unable to update application status profile'];
                                                                }
                                                            }else{
                                                                return ['status'=>false, 'msg'=>'Unable to create additional subject list'];
                                                            }
                                                        }else{
                                                            return ['status'=>false, 'msg'=>'Unable to store std register'];
                                                        }
                                                    }else{
                                                        return ['status'=>false, 'msg'=>'Unable to upload student profile photo'];
                                                    }
                                                }else{
                                                    return ['status'=>false, 'msg'=>'Unable to create student Address profile'];
                                                }
                                            }else{
                                                return ['status'=>false, 'msg'=>'Unable to create student guardian profile'];
                                            }
                                        }else{
                                            return ['status'=>false, 'msg'=>'Unable to create student enrollment history profile'];
                                        }
                                    }else{
                                        return ['status'=>false, 'msg'=>'Unable to create student enrollment profile'];
                                    }
                                }else{
                                    return ['status'=>false, 'msg'=>'Unable to create student info profile'];
                                }
                            }else{
                                return ['status'=>false, 'msg'=>'Unable to create user info profile'];
                            }
                        }else{
                            return ['status'=>false, 'msg'=>'Unable to create user profile'];
                        }
                    }else{ // student disapproved action
                        // delete student and checking
                        if($applicantProfile->delete()){
                            $stdAddCounter+=1;
                        }else{
                            return ['status'=>false, 'msg'=>'Unable to delete applicant profile'];
                        }
                    }
                }else{
                    return ['status'=>false, 'msg'=>'Unable to find applicant profile'];
                }
            }
        } catch (\Exception $e) {
            // rollback
            DB::rollback();
            // throw $e;
            return ['status'=>false, 'msg'=>$e->getMessage()];
        }


        // checking student loop counter
        if($stdAddCounter==count($applicantList)){
            // If we reach here, then data is valid and working. Commit the queries!
            DB::commit();
            // return
            return ['status'=>true, 'msg'=>'Selected Student(s) Promotion Submitted Successfully !!!'];
        }else{
            // rollback
            DB::rollback();

            // return
            return ['status'=>false, 'msg'=>'Unable to perform the action  (final)'];
        }
    }


    // student Photo uploader
    public function applicantPhotoUploader($stdId, $imageData)
    {
        // Extract base64 file for standard data
        $fileBin = file_get_contents($imageData);
        $fileExtension = str_replace('image/','', mime_content_type($imageData));
        $fileName = $stdId.uniqid().'.'.$fileExtension;
        $destinationPath = 'assets/users/images/'.$fileName;
        // move file to the destination path
        if(file_put_contents($destinationPath, $fileBin)){
            // user document
            $userDocument = new Content();
            // storing user document
            $userDocument->name      = $fileName;
            $userDocument->file_name = $fileName;
            $userDocument->path      = $destinationPath;
            $userDocument->mime      = $fileExtension;
            // save and checking
            if($userDocument->save()){
                // new student attachment
                $studentAttachment = new StudentAttachment();
                // storing student attachment
                $studentAttachment->std_id     = $stdId;
                $studentAttachment->doc_id     = $userDocument->id;
                $studentAttachment->doc_type   = "PROFILE_PHOTO";
                $studentAttachment->doc_status = 0;
                // save student attachment profile
                if($studentAttachment->save()){
                    // return true
                    return true;
                }
            }
        }
        // return false
        return true;
    }

    // storeHscStudentGuardian
    public function storeHscStudentGuardian($stdId, $applicantProfile)
    {
        // response data
        $guardianCount = 0;
        // student guardian creation loop
        for ($i=0; $i<2; $i++) {
            // new guardian user profile
            $newGuardUserProfile = new $this->user();
            // store user details
            $newGuardUserProfile->name = ($i == 0 ? $applicantProfile->m_name : $applicantProfile->f_name);
            $newGuardUserProfile->email = $stdId.$stdId . $i . '_gud@gmail.com';
            $newGuardUserProfile->password = bcrypt(123456);
            // saving parent user profile
            $newGuardUserProfile->save();

            // create new guardian student
            $guardianProfile = new $this->studentGuardian();
            // store guardian details
            $guardianProfile->user_id = $newGuardUserProfile->id;
            // $guardianProfile->title =
            $guardianProfile->type = $i;
            $guardianProfile->first_name = ($i == 0 ? $applicantProfile->m_name : $applicantProfile->f_name);
            //$guardianProfile->last_name =
            $guardianProfile->bn_fullname = ($i == 0 ? $applicantProfile->m_name_bn : $applicantProfile->f_name_bn);
            // $guardianProfile->bn_edu_qualification =
            $guardianProfile->email = $stdId.$stdId. $i.'_gud@gmail.com';
            $guardianProfile->mobile = ($i == 0 ? $applicantProfile->m_mobile : $applicantProfile->f_mobile);
            $guardianProfile->phone = ($i == 0 ? $applicantProfile->m_mobile : $applicantProfile->f_mobile);
            $guardianProfile->income = ($i == 0 ? '' : $applicantProfile->f_income);
            $guardianProfile->occupation = ($i == 0 ? $applicantProfile->m_occupation : $applicantProfile->f_occupation);
            $guardianProfile->qualification = ($i == 0 ? $applicantProfile->m_education : $applicantProfile->f_education);
            $guardianProfile->nid = ($i == 0 ? $applicantProfile->m_nid : $applicantProfile->f_nid);
            // $guardianProfile->home_address = ($i == 0 ? '' : $applicantPersonalInfo->add_pre_address);
            // $guardianProfile->office_address = ($i == 0 ? '' : $applicantPersonalInfo->add_pre_address);
            // save guardian profile and  checking
            $guardianProfile->save();

            // assigning student role to this user
            $newGuardUserProfile->attachRole($this->role->where('name', 'parent')->first());
            // add user info
            $this->userInfo->create([
                'user_id'=>$newGuardUserProfile->id, 'campus_id' => $applicantProfile->campus_id, 'institute_id' => $applicantProfile->institute_id
            ]);
            // add this guardian as student parent
            if($this->studentParent->create(['gud_id' => $guardianProfile->id, 'std_id' => $stdId, 'is_emergency' => $i])){
                $guardianCount += 1;
            }
        }
        // checking guardian creation
        if($guardianCount==2){
            return true;
        }else{
            return false;
        }
    }

    // storeOnlineStudentAddress
    public function storeHscStudentAddress($userId, $applicantProfile)
    {
        // response data
        $addressCount = 0;
        // student address creation loop
        for ($i=0; $i<2; $i++) {
            // address
            $presentAddress = new Address();
            // store address details
            $presentAddress->user_id    = $userId;
            $presentAddress->type       = ($i==0?"STUDENT_PRESENT_ADDRESS":"STUDENT_PERMANENT_ADDRESS");
            $presentAddress->address    = $applicantProfile->vill;
            $presentAddress->street     = "Not available";
            $presentAddress->city_id    = $applicantProfile->a_thana;
            $presentAddress->state_id   = $applicantProfile->a_zilla;
            $presentAddress->house      = "Not available";
            $presentAddress->phone      = $applicantProfile->s_mobile;
            $presentAddress->zip        = $applicantProfile->post;
            $presentAddress->country_id = 1;
            $presentAddress->bn_village = '';
            $presentAddress->bn_postoffice = '';
            $presentAddress->bn_upzilla = '';
            $presentAddress->bn_zilla = '';
            // save present address
            if($presentAddress->save()){
                $addressCount += 1;
            }
        }
        // checking address creation
        if($addressCount==2){
            return true;
        }else{
            return false;
        }
    }


    // storeOnlineStudentAddress
    public function storeSscInfo($stdId, $applicantProfile)
    {
        // ssc exam info as json array
        $sscInfo = [
            'exam_info'=>[
                'exam_name'=>$applicantProfile->exam_name,
                'exam_board'=>$applicantProfile->exam_board,
                'exam_year'=>$applicantProfile->exam_year,
                'exam_reg'=>$applicantProfile->exam_reg,
                'exam_roll'=>$applicantProfile->exam_roll,
                'exam_session'=>$applicantProfile->exam_session,
                'exam_group'=>$applicantProfile->exam_session,
                'exam_gpa'=>$applicantProfile->exam_gpa,
                'exam_institute'=>$applicantProfile->exam_institute,
            ],
            'exam_result'=>[]
        ];

        // new student register object
        $stdRegister = new $this->stdRegister();
        // input std details
        $stdRegister->std_id = $stdId;
        $stdRegister->ssc = json_encode($sscInfo);
        // save and checking
        if($stdRegister->save()){
            return true;
        }else{
            return false;
        }

    }

}
