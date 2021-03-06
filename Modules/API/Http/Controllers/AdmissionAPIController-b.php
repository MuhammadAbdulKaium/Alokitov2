<?php

namespace Modules\API\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Carbon\Carbon;
use Dompdf\Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\Academics\Entities\AcademicsYear;
use Modules\Academics\Entities\ReportCardSetting;
use Modules\Admission\Entities\ApplicantAddress;
use Modules\Admission\Entities\ApplicantDocument;
use Modules\Admission\Entities\ApplicantEnrollment;
use Modules\Admission\Entities\ApplicantExamSetting;
use Modules\Admission\Entities\ApplicantInformation;
use Modules\Admission\Entities\ApplicantManageView;
use Modules\Admission\Entities\ApplicantRelative;
use Modules\Admission\Entities\ApplicantUser;
use Modules\Setting\Entities\Campus;
use Modules\Setting\Entities\Country;
use Modules\Setting\Entities\Institute;
use App\Http\Controllers\SmsSender;
use Modules\Setting\Entities\BkashTransaction;
use Modules\Setting\Entities\BkashToken;

use Modules\Admission\Entities\HscApplicant;

use GuzzleHttp\Client;

class AdmissionAPIController extends Controller
{
    private $academicHelper;
    private $applicant;
    private $applicantView;
    private $address;
    private $document;
    private $enrollment;
    private $personalInfo;
    private $country;
    private $campus;
    private $institute;
    private $smsSender;
    private $hscApplicant;

    // constructor
    public function __construct(AcademicHelper $academicHelper, SmsSender $smsSender, ApplicantUser $applicant, ApplicantManageView $applicantView, ApplicantAddress $address, ApplicantDocument $document, ApplicantEnrollment $enrollment, ApplicantInformation $personalInfo, Country $country, Campus $campus, Institute $institute, HscApplicant $hscApplicant)
    {
        $this->academicHelper = $academicHelper;
        $this->applicant = $applicant;
        $this->applicantView  = $applicantView;
        $this->address = $address;
        $this->document = $document;
        $this->enrollment = $enrollment;
        $this->personalInfo = $personalInfo;
        $this->country = $country;
        $this->campus = $campus;
        $this->institute = $institute;
        $this->smsSender = $smsSender;
        $this->hscApplicant = $hscApplicant;
    }
    public function examSetting(Request $request){
        $acadenicsYear=AcademicsYear::where([
            'institute_id'=>$request->campus,
            'campus_id'=>$request->institute,
            'status'=>1
        ])->first();


        $examSetting=ApplicantExamSetting::where([
            'institute_id'=>$request->campus,
            'campus_id'=>$request->institute,
            'batch'=>$request->batch,
            'academic_year'=>$acadenicsYear->id
        ])->first();
        if($examSetting){
            $arrExam=[];
            $arrExam['Exam Fees']=$examSetting->exam_fees;

            $arrExam['Time']= Carbon::parse($examSetting->exam_date)->format('d F y') ." ".$examSetting->exam_start_time
            ." from ".$examSetting->exam_end_time;
                $applicant_count=ApplicantManageView::where([
                    'institute_id'=>$request->campus,
                    'campus_id'=>$request->institute,
                    'batch'=>$request->batch,
                    'academic_year'=>$acadenicsYear->id
                ])->get()->count();


            $arrExam['Venue']=$examSetting->exam_venue;
            $arrExam['Total Number of Applicant']=$examSetting->max_applicants;
            $arrExam['Merit List']=$examSetting->merit_list_std_no;
            $arrExam['Waiting List']=$examSetting->waiting_list_std_no;
            $arrExam['Exam Marks']=$examSetting->exam_marks;
            $arrExam['Pass Marks']=$examSetting->exam_passing_marks;
            $arrExam['subjectMarks']=json_decode( $examSetting->exam_subjects_marks);
            if($examSetting->last_date_of_submission){
                if(Carbon::parse($examSetting->last_date_of_submission)->isPast() || $applicant_count>$examSetting->max_applicants){
                    $arrExam['exam_date_passed']=true;
                }else{

                    $arrExam['exam_date_passed']=false;
                }
            }
        }else{
            $arrExam=null;
        }
            return ['status'=>true, 'data'=>$arrExam,'year'=>$acadenicsYear->id];
    }

    public function studentUserLogin(Request $request)
    {
        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'username' => 'required|max:100', 'password' => 'required',
        ]);
        // storing requesting input data
        if ($validator->passes()) {
            // checking applicant username and password
            if($applicantProfile = $this->checkApplicantUser($request->username, $request->password)){
                // return
                return ['status'=>true, 'data'=>$applicantProfile];
            }else{
                // return
                return ['status'=>false, 'msg'=>'Invalid Username or Password'];
            }
        }else{
            // return
            return ['status'=>false, 'msg'=>'please check all inputs are selected ????'];
        }
    }


    // applicant password reset
    public function  applicantResetPassword(Request $request) {
        // validator
        $validator = Validator::make($request->all(), ['email'=>'required']);
        // checking validator
        if ($validator->passes()) {
            // find applicant profile
            $applicantProfile = $this->applicant->where('email', $request->email)->first();
            // checking applicant profile
            if (!empty($applicantProfile)) {
                // new password
                $newPassword="123456";
                // send new reset password
                $this->smsSender->applicatanPasswordRestSmsJob($applicantProfile,$newPassword);
                // update applicant password and save
                $applicantProfile->password = bcrypt($newPassword);
                $applicantProfile->save();
                // return
                return ['status' => 200, 'msg' => "Password Reset Successfully"];
            } else {
                return ['status' => 200, 'msg' => "Applicant Not Found Try Again"];
            }
        } else {
            return ['status' => 400, 'msg' => $validator->errors()];
        }
    }
    //For testing By mazharul

    // store applicant personal information
    public function storeApplicantPersonalInfo($applicantId, $applicationNo, $request)
    {
        // student photo
        $stdPhoto = $request->input('image', null);
        // applicant new personal profile
        $personalInoProfile = new $this->personalInfo();
        // input details
        $personalInoProfile->applicant_id        = $applicantId;
        $personalInoProfile->std_name            = $request->input('std_name');
        $personalInoProfile->invoice_id            = uniqid();
        $personalInoProfile->amount               = 03;
        $personalInoProfile->std_name_bn         = $request->input('std_name_bn');
        // checking student photo
        if($stdPhoto){
            $personalInoProfile->std_photo = $stdPhoto;
        }



        $personalInoProfile->birth_date          = date('Y-m-d', strtotime($request->input('birth_date')));
        $personalInoProfile->gender              = $request->input('gender');

        $personalInoProfile->add_per_address      = $request->input('add_per_address');
        $personalInoProfile->add_per_post        = $request->input('add_per_post');
        $personalInoProfile->add_per_city        = $request->input('add_per_city');
        $personalInoProfile->add_per_state       = $request->input('add_per_state');
        $personalInoProfile->add_per_phone       = $request->input('add_per_phone');

        $personalInoProfile->add_pre_address     = $request->input('add_pre_address');
        $personalInoProfile->add_pre_post        = $request->input('add_pre_post');
        $personalInoProfile->add_pre_city        = $request->input('add_pre_city');
        $personalInoProfile->add_pre_state       = $request->input('add_pre_state');
        $personalInoProfile->add_pre_phone       = $request->input('add_pre_phone');

        // romeshshil code 5 new column

        $personalInoProfile->special_care        = $request->input('special_care');
        $personalInoProfile->tribal              = $request->input('tribal');
        $personalInoProfile->nationality            = $request->input('nationality');
        $personalInoProfile->religion          = $request->input('religion', null);
        $personalInoProfile->area_of_land          = $request->input('area_of_land', null);

        // save and checking
        if($personalInoProfile->save()){
            // checking student photo
            if($stdPhoto){
                // upload student photo
                if($this->applicantPhotoUploader($applicantId, $applicationNo, $stdPhoto)==false){
                    // response success
                    return ['status'=>false, 'msg'=>'Unable to Store Applicant Photo'];
                }
            }
            // response success
            return ['status'=>true, 'id'=>$personalInoProfile->id,'invoice_id'=>$personalInoProfile->invoice_id];
        }else{
            // response failed
            return ['status'=>false, 'msg'=>'Unable to Store Applicant personal information'];
        }
    }
    public function saveProfile(Request  $request){
       // return ['status'=>true, 'data'=>$request->all()];
       return $this->saveProfileImage($request->applicantId,2121,$request);
    }

    public function saveProfileImage($applicantId,$applicant_user,$request){
        if($file = $request->hasFile('image')) {

            $file = $request->file('image') ;
            $fileName = $applicantId.$file->getClientOriginalName() ;

            $destinationPath = 'assets/admission/images/';
            $file->move($destinationPath,$fileName);

            try{
                $applicantDocument = new $this->document();

                // storing user document
                $applicantDocument->applicant_id = $applicantId;
                $applicantDocument->doc_name     = $fileName;
                $applicantDocument->doc_type     = "PROFILE_PHOTO";
                $applicantDocument->doc_path     = $destinationPath;
                $applicantDocument->doc_mime     = $file->getClientOriginalExtension();
                $applicantDocument->doc_details     = "dfaskdfs      ";
                $applicantDocument->save();




                return ['status'=>true,'data'=>$applicantDocument];

            }catch (\Exception $e){
                return ['status'=>true,'data'=>$e];
            }


        }
        return ['status'=>false,'data'=>"Problem"];
    }


    public function saveStudentsData(Request  $request)
    {

//return $this->saveProfileImage(1221035,222,$request);


//return ['status'=>true,'data'=>$request->all()];


       // return ['status'=>true,'data'=>$request->all()];
        $validator = Validator::make($request->all(), [
         'firstName'=>'required',

        'lastName'=>'required',
        'banglaName'=>'required',
        'dateOfBirth'=>'required',
        'religion'=>'required',
        'bloodGroup'=>'required',
        'presentAddress'=>'required',
        'permanentAddress'=>'required',
        'birthCertificate'=>'required',
        'country'=>'required',

        ]);
        $validator = Validator::make($request->father, [
            'name'=>'required',
            'contactAddress'=>'required',
            'contactPhone'=>'required',
        ]);
        $validator = Validator::make($request->mother, [
            'name'=>'required',
            'contactAddress'=>'required',
            'contactPhone'=>'required',
        ]);
        $validator = Validator::make($request->guardian, [
            'name'=>'required',
            'contactAddress'=>'required',
            'contactPhone'=>'required',
        ]);

        
        if($validator->passes()){

            DB::beginTransaction();
            try {
                $campus = $request->input('campus_id');
                $institute = $request->input('institute_id');

                $campusProfile = $this->campus->find($campus);

                $applicationYear = '22';
                // username and email


                $applicationId = $campus.$applicationYear.($campusProfile->app_counter+1);
                $email = $applicationId;

                // application new profile
                $applicantProfile = new $this->applicant();
                // input details
                // $applicantProfile->email       = $request->input('email', $email);
                $applicantProfile->email          = $email;
                $applicantProfile->username       = $applicationId;
                //$applicantProfile->username     = $request->input('username', $username);
                $applicantProfile->password       = bcrypt($applicationId);
                $applicantProfile->campus_id      = $campus;
                $applicantProfile->institute_id   = $institute;
                $applicantProfile->application_no = $applicationId;
                // save applicant profile and checking
                // save applicant profile and checking
                if($applicantProfile->save()){

                    $personalInoProfile = (object) $this->storeApplicantPersonalInformation($applicantProfile->id, $applicationId, $request);
                 // return  ['status'=>200,'msg'=>"Saved the data",'data'=>$personalInoProfile];

                    $fatherGuardian=false;
                    $motherGuardian=false;
                    $otherGuardian=false;
                   if($request->relation){
                       if($request->relation==1){
                           $fatherGuardian=true;
                       }elseif ($request->relation==2){
                           $motherGuardian=true;
                       }else{
                           $otherGuardian=true;
                       }
                   }


                    $father=$this->saveGuardian($request->father,"father",$applicantProfile->id,$fatherGuardian);
                    $mother=$this->saveGuardian($request->mother,"mother",$applicantProfile->id,$motherGuardian);
                    if($otherGuardian)
                    {
                        $guardian=$this->saveGuardian($request->guardian,$request->relation,$applicantProfile->id,
                            $otherGuardian);
                    }

                    $relatives=[];
                    $relatives['student']=$personalInoProfile;
                    $relatives['f']=$father;
                    $relatives['m']=$mother;
                   // $relatives['g']=$guardian;
                    $relatives['r']=$request->all();



                        $enrollmentProfile = new ApplicantEnrollment();
                        // input details
                        $enrollmentProfile->applicant_id   = $applicantProfile->id;
                        $enrollmentProfile->academic_year  = $request->input('academic_year');
                        $enrollmentProfile->academic_level = $request->input('academic_level');
                        $enrollmentProfile->batch          = $request->input('batch');
                        // checking choice list
                      /*  if($choiceList = $request->input('choice_list', null)){
                            $enrollmentProfile->choice_list  = json_encode($choiceList);
                        }*/
                        $enrollmentProfile->save();

                    $campusProfile->app_counter = ($campusProfile->app_counter+1);
                    $campusProfile->save();
                    // If we reach here, then data is valid and working. Commit the queries!
                    DB::commit();
                    return  ['status'=>200,'msg'=>"Father Save",'userName'=>$applicationId,'applicantId'=>$applicantProfile->id,
                        'data'=>$relatives];

                }

            }catch (\Exception $e){

                return  ['status'=>500,'msg'=>"Something went Wrong k",'data'=>$e];
            }



            return  ['status'=>200,'msg'=>"Got the student data",'data'=>$request->all()];
        }else {
            return  ['status'=>200,'msg'=>"Error on data",'data'=>$request->all()];
        }




    }
    public function downloadAdmitCard(Request $request)
    {
        //return ['status'=>true,'data'=>$request->all()];

        try {


        $applicantId=$request->applicantId;
        // application new profile
        $applicantProfile = ApplicantManageView::where(['applicant_id'=>$applicantId])->first();
        $applicantUser=ApplicantUser::find($applicantId);
        $reportCardSetting = ReportCardSetting::where(['institute'=>$applicantProfile->institute_id,
            'campus'=>$applicantProfile->campus_id])->first();
        //institute profile
        $instituteInfo = $applicantProfile->institute();
        // share all variables with the view
        view()->share(compact('instituteInfo','applicantUser', 'applicantProfile', 'reportCardSetting'));
        // generate pdf
        $pdf = App::make('dompdf.wrapper');
        // load view
        $pdf->loadView('admission::application.reports.report-admit-card')->setPaper('a4', 'portrait');
        return $pdf->download('admit_card_no_'.$applicantProfile->application_no.'.pdf');
        //return $pdf->stream();
        }
        catch (\Exception $e){
            return $e;
        }
    }

    public function downloadApplication($applicantId)
    {
        $applicantProfile=ApplicantUser::where('id',$applicantId)->with('singlePersonInfo','applicantManageView','father','mother')->first();

        $father=$applicantProfile->father;
        $mother=$applicantProfile->mother;

        $instituteId= $applicantProfile->institute_id;
        //institute profile
        $instituteInfo =Institute::find($instituteId);
        // share all variables with the view
        view()->share(compact('instituteInfo', 'father','mother','applicantProfile'));

        // use mPDF

        /* $pdf = App::make('mpdf.wrapper');
         $pdf->loadView('admission::application.reports.report-application');
         $view = View::make('admission::application.reports.report-application');
         $html = $view->render();
         $mpdf = new  MPDF('utf-8',   'Legal', 14,'SolaimanLipi','10','5','5','0');
         $mpdf->autoScriptToLang = true;// Mandatory
         $mpdf->autoLangToFont = true;//Mandatory
         $mpdf->WriteHTML($html);
         $mpdf->Output('application_no_'.$applicantProfile->application_no.'.pdf', 'D');*/

        // generate pdf
        $pdf = App::make('dompdf.wrapper');
        // load view
        $pdf->loadView('admission::application.reports.report-application')->setPaper('a4', 'portrait');
        return $pdf->download('application_no_'.$applicantProfile->application_no.'.pdf');
        return $pdf->stream();
    }


    public function saveGuardian($relative,$type,$applicant_id,$isGuardian){

        $relativeData=new ApplicantRelative();
        $relativeData->name=$relative['name'];
        $relativeData->applicant_id=$applicant_id;
        $relativeData->relation=$type;
        $relativeData->bengali_name=$relative['bengaliName'];
        $relativeData->nationality=$relative['nationality'];
        $relativeData->profession=$relative['profession'];
        $relativeData->designation=$relative['designation'];
        $relativeData->department=$relative['department'];
        $relativeData->organization=$relative['organization'];
        $relativeData->address=$relative['address'];
        $relativeData->reference_contact=$relative['referenceContact'];
        $relativeData->total_year_of_working=$relative['totalYearOfWorking'];
        $relativeData->income_yearly=$relative['incomeYearly'];
        $relativeData->nid_no=$relative['nidNo'];
        $relativeData->tin_no=$relative['tinNo'];
        $relativeData->passport=$relative['passport'];
        $relativeData->birth_certificateNo=$relative['birthCertificateNo'];
        $relativeData->driving_license=$relative['drivingLicense'];
        $relativeData->contact_address=$relative['contactAddress'];
        $relativeData->contact_phone=$relative['contactPhone'];
        $relativeData->contact_email=$relative['contactEmail'];
        $relativeData->remarks=$relative['remarks'];
        $relativeData->is_guardian=$isGuardian;
        $relativeData->save();
        return $relativeData;




        

    }





    // store applicant personal information by dev9
    public function storeApplicantPersonalInformation($applicantId, $applicationNo, $request)
    {
        // student photo
        $stdPhoto = $request->input('image', null);
        // applicant new personal profile
        $personalInoProfile = new $this->personalInfo();
        // input details
        $personalInoProfile->applicant_id        = $applicantId;
        $personalInoProfile->first_name            = $request->input('firstName');
        $personalInoProfile->last_name            = $request->input('lastName');

        $personalInoProfile->invoice          = uniqid();
        $personalInoProfile->amount               = 03;
        $personalInoProfile->std_name_bn         = $request->input('banglaName');
        // checking student photo
        if($stdPhoto){
            $personalInoProfile->std_photo = $stdPhoto;
        }



        $personalInoProfile->birth_date          = date('Y-m-d', strtotime($request->input('dateOfBirth')));
        $personalInoProfile->gender              = $request->input('gender');
        $personalInoProfile->blood_group        = $request->input('bloodGroup');
        $personalInoProfile->present_address      = $request->input('permanentAddress');
        $personalInoProfile->permanent_address	        = $request->input('presentAddress');
        $personalInoProfile->religion	      = $request->input('religion');

        $personalInoProfile->nationality            = $request->input('country');
        $personalInoProfile->religion          = $request->input('religion', null);
    /*    lastCertification: null,
            group_id: null,
            : null,
            rollNumber: null,
            passingYear: null,
            boardName: null,
            instituteFullName: null,
            gpa: null,*/

        $personalInoProfile->last_certificate_name= $request->academicsData['lastCertification'];
        $personalInoProfile->group_id= $request->academicsData['group_id'];
        $personalInoProfile->registration_no= $request->academicsData['registrationNumber'];
        $personalInoProfile->roll_no= $request->academicsData['rollNumber'];
        $personalInoProfile->passing_year= $request->academicsData['passingYear'];
        $personalInoProfile->board_name= $request->academicsData['boardName'];
        $personalInoProfile->institute_full_name= $request->academicsData['instituteFullName'];
        $personalInoProfile->gpa= $request->academicsData['gpa'];
        $personalInoProfile->subject_wise_mark= json_encode($request->academicsData['subjectWiseMark']);








        // save and checking
        if($personalInoProfile->save()){
            // checking student photo
            if($stdPhoto){
                // upload student photo
                if($this->applicantPhotoUploader($applicantId, $applicationNo, $stdPhoto)==false){
                    // response success
                    return ['status'=>false, 'msg'=>'Unable to Store Applicant Photo'];
                }
            }
            // response success
            return ['status'=>true, 'profile'=>$personalInoProfile,'id'=>$personalInoProfile->id,
                'invoice_id'=>$personalInoProfile->invoice_id];
        }else{
            // response failed
            return ['status'=>false, 'msg'=>'Unable to Store Applicant personal information'];
        }
    }

//End Testing by DEV9







    // find academic section list
    public function storeOnlineStudent(Request $request)
    {

        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'std_name'              => 'required',
            'father_name'           => 'required',
            'mother_name'           => 'required',
            'add_per_address'       => 'required',
            'add_per_post'          => 'required',
            'add_per_city'          => 'required',
            'add_per_state'         => 'required',
            'add_pre_address'       => 'required',
            'add_pre_post'          => 'required',
            'add_pre_city'          => 'required',
            'add_pre_state'         => 'required',
            'gud_name'              => 'required',
            'gud_phone'             => 'required',
            'gud_income'            => 'required',
            'gud_income_bn'         => 'required',
//            'psc_gpa'               => 'required',
//            'psc_roll'              => 'required',
//            'psc_year'              => 'required',
//            'psc_school'            => 'required',
//            'psc_tes_no'            => 'required',
//            'psc_tes_date'          => 'required',
            'gender'                => 'required',
            'birth_date'            => 'required',
            'academic_year'         => 'required',
            'academic_level'        => 'required',
            'batch'                 => 'required',
            'campus_id'             => 'required',
            'institute_id'          => 'required',
//            'email'            => 'required|max:100|email|unique:applicant_user',
//            'username'            => 'required|max:100|unique:applicant_user',
            //'password'              => 'required',
            // 'confirm_password'      => 'required|same:password',
        ]);

        // storing requesting input data
        if ($validator->passes()) {

            // Start transaction!
            DB::beginTransaction();

            // create application profile
            try {
                // applicant institute
                $campus = $request->input('campus_id');
                $institute = $request->input('institute_id');
                // campus id
                $campusProfile = $this->campus->find($campus);
                // application Year
                $applicationYear = '20';
                // username and email
                $applicationId = $campus.$applicationYear.($campusProfile->app_counter+1);
                $email = $applicationId;

                // application new profile
                $applicantProfile = new $this->applicant();
                // input details
                // $applicantProfile->email       = $request->input('email', $email);
                $applicantProfile->email          = $email;
                $applicantProfile->username       = $applicationId;
                //$applicantProfile->username     = $request->input('username', $username);
                $applicantProfile->password       = bcrypt($applicationId);
                $applicantProfile->campus_id      = $campus;
                $applicantProfile->institute_id   = $institute;
                $applicantProfile->application_no = $applicationId;
                //$applicantProfile->application_no = $institute.sprintf("%08d", mt_rand(10000, (date('YmdHi')-date('YmdHi', strtotime('-30 days'))))).$campus;

                // save applicant profile and checking
                if($applicantProfile->save()){
                    // applicant new personal profile
                    $personalInoProfile = (object) $this->storeApplicantPersonalInfoNew($applicantProfile->id, $applicationId, $request);
                    // save applicant personalIno Profile and checking
                    // return ['status'=> true, 'msg'=>$personalInoProfile];
                    if($personalInoProfile->status){
                        // enrollment new profile
                        $enrollmentProfile = new $this->enrollment();
                        // input details
                        $enrollmentProfile->applicant_id   = $applicantProfile->id;
                        $enrollmentProfile->academic_year  = $request->input('academic_year');
                        $enrollmentProfile->academic_level = $request->input('academic_level');
                        $enrollmentProfile->batch          = $request->input('batch');
                        // checking choice list
                        if($choiceList = $request->input('choice_list', null)){
                            $enrollmentProfile->choice_list  = json_encode($choiceList);
                        }
                        // save enrollment profile and checking
                        if($enrollmentProfile->save()){
                            // update campus applicant counter
                            $campusProfile->app_counter = ($campusProfile->app_counter+1);
                            $campusProfile->save();
                            // If we reach here, then data is valid and working. Commit the queries!
                            DB::commit();
                            // return response with success msg
                            return ['status'=> true, 'msg'=>'Student Information Submitted Successfully', 'applicant_id'=>$applicantProfile->id, 'invoice_id'=>$personalInoProfile->invoice_id];

                        }else{
                            DB::rollback();
                            // return response with failed msg
                            return ['status'=> false, 'msg'=>'Unable to Submit Student Enroll Profile'];
                        }
                    }else{
                        DB::rollback();
                        // return response with failed msg
                        return ['status'=> false, 'msg'=>'Unable to Submit Applicant Profile'];
                    }
                }else{
                    DB::rollback();
                    // return response with failed msg
                    return ['status'=> false, 'msg'=>'Unable to Submit Applicant User Profile for the Student'];
                }
            } catch (ValidationException $e) {
                // Rollback and then redirect back to form with errors
                DB::rollback();
                return redirect()->back()->withErrors($e->getErrors())->withInput();
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }
        } else {
            // return response with failed msg
            return [ 'status'=> false, 'errors'=>$validator->errors()];
        }
    }

    // student Photo uploader
    public function applicantPhotoUploader($applicantId, $applicationNo, $imageData)
    {
        // Extract base64 file for standard data
        $fileBin = file_get_contents($imageData);
        $fileExtension = str_replace('image/','', mime_content_type($imageData));
        $fileName = $applicationNo.'.'.$fileExtension;
        $destinationPath = 'assets/admission/images/'.$fileName;
        // move file to the destination path
        if(file_put_contents($destinationPath, $fileBin)){
            // user document
            $applicantDocument = new $this->document();

            // storing user document
            $applicantDocument->applicant_id = $applicantId;
            $applicantDocument->doc_name     = $fileName;
            $applicantDocument->doc_type     = "PROFILE_PHOTO";
            $applicantDocument->doc_path     = $destinationPath;
            $applicantDocument->doc_mime     = $fileExtension;
            // save student attachment profile
            if($applicantDocument->save()){
                // return true
                return true;
            }
        }
        // return false
        return false;
    }


    // applicant username and password Checking for login
    public function checkApplicantUser($username, $password)
    {
        // find applicant and check applicant password
        if($applicantProfile = $this->applicant->where(['username'=>$username])->first()){
            // checking user password
            if(Hash::check($password, $applicantProfile->password)) {
                // return applicant profile details
                return $this->applicantProfileDetails($applicantProfile);
            }
        }
        // return
        return false;
    }

    // check HSC applicant
    // applicant username and password Checking for login
    public function checkHscApplicantUser($username, $password)
    {
        // find applicant and check applicant password
        if($applicantProfile = $this->hscApplicant->where(['username'=>$username])->first()){
            // checking user password
            if(Hash::check($password, $applicantProfile->password)) {
                // return applicant profile details
                return $applicantProfile;
            }
        }
        // return
        return false;
    }

    // prepare applicant data set
    public function applicantProfileDetails($applicantProfile)
    {
        // applicant personal information
        $personalInfo = $applicantProfile->personalInfo();
        // applicant enroll details
        $enroll = $applicantProfile->enroll();
        // response data set
        $responseData = [];
        // input user details
        $responseData['user'] = [
            "id"=>$applicantProfile->id,
            "email"=>$applicantProfile->email,
            "username"=> $applicantProfile->username,
            "campus_id"=> $applicantProfile->campus_id,
            "institute_id"=>$applicantProfile->institute_id
        ];
        // input applicant details
        $responseData['application'] = [
            "application_no"=> $applicantProfile->application_no,
            "app_status"=> $applicantProfile->application_status,
            "application_status"=> $applicantProfile->application_status==1?'Active':'Pending',
            "pay_status"=> $applicantProfile->payment_status,
            "payment_status"=> $applicantProfile->payment_status==1?"Paid":"Un Paid"
        ];
        // input applicant details
        $responseData['personal'] = [
            "std_name"=>$personalInfo->std_name,
            "std_name_bn"=>$personalInfo->std_name_bn,
            "father_name"=>$personalInfo->father_name,
            "father_name_bn"=>$personalInfo->father_name_bn,
            "father_occupation"=>$personalInfo->father_occupation,
            "father_education"=>$personalInfo->father_education,
            "mother_name"=>$personalInfo->mother_name,
            "mother_name_bn"=>$personalInfo->mother_name_bn,
            "mother_occupation"=>$personalInfo->mother_occupation,
            "mother_education"=>$personalInfo->mother_education,
            "gender"=>$personalInfo->gender==0?'Male':'Female',
            "birth_date"=>date('d M, Y', strtotime($personalInfo->birth_date)),
            "special_care" => $personalInfo->special_care,
            "tribal" => $personalInfo->tribal,
            "nationality" => $personalInfo->nationality,
            "nationality_name" => $personalInfo->nationality()->nationality,
            "religion" => $personalInfo->religion,
            'profile_pic'=>$applicantProfile->document('PROFILE_PHOTO')->doc_path ?? null
        ];

        // input guardian details
        $responseData['guardian'] = [
            "gud_name"=>$personalInfo->gud_name,
            "gud_phone"=>$personalInfo->gud_phone,
            "gud_income"=>$personalInfo->gud_income,
            "gud_income_bn"=>$personalInfo->gud_income_bn,
            "area_of_land"=>$personalInfo->area_of_land,
        ];

        // input address details
        $responseData['address'] = [
            'present' => [
                "address"=>$personalInfo->add_pre_address,
                "post"=>$personalInfo->add_pre_post,
                "city"=>$personalInfo->preCity()->name,
                "state"=>$personalInfo->preState()->name,
                "phone"=>$personalInfo->add_pre_phone,
            ],
            'permanent' => [
                "address"=>$personalInfo->add_per_address,
                "post"=>$personalInfo->add_per_post,
                "city"=>$personalInfo->perCity()->name,
                "state"=>$personalInfo->perState()->name,
                "phone"=>$personalInfo->add_per_phone,
            ],
        ];
        // input previous school details
        $responseData['previous_school'] = [
            "psc_gpa"=>$personalInfo->psc_gpa,
            "psc_roll"=>$personalInfo->psc_roll,
            "psc_year"=>$personalInfo->psc_year,
            "psc_school"=>$personalInfo->psc_school,
            "psc_tes_no"=>$personalInfo->psc_tes_no
        ];
        // input enrollment details
        $responseData['enrollment'] = [
            "academic_year"=>$enroll->academicYear()->year_name,
            "academic_level"=>$enroll->academicLevel()->level_name,
            "academic_batch"=>$enroll->batch()->batch_name,
        ];
        // return response date
        return $responseData;
    }



    ////////////////////////////////////// HSC  Admission //////////////////////////////////////

    //  store HSC online admission form
    public function storeHscStudent(Request $request)
    {
//        return $request->all();

        // validating all requested input data
        $validator = Validator::make($request->all(), [
            's_name' => 'required',
            'campus_id' => 'required',
            'secret_code' => 'required',
            'exam_reg' => 'required|unique:hsc_applicants',
            'exam_roll' => 'required|unique:hsc_applicants',
            'institute_id' => 'required',
            'image' => 'required',
        ]);

        // storing requesting input data
        if ($validator->passes()) {

            // Start transaction!
            DB::beginTransaction();

            // create application profile
            try {
                // applicant new user profile
                $appUserProfile = new $this->hscApplicant();
                // applicant campus details
                $campus = $request->input('campus_id');
                $subList = $request->input('sub_list', null);
                $groupList = $request->input('group_list', null);
                $examResult = $request->input('exam_result', null);
                // secret_code
                //$secretCode = $request->input('secret_code');
                // campus id
                $campusProfile = $this->campus->find($campus);
                // application Year
                $applicationYear = date('y');
                // username and email
                $username = $campus.$applicationYear.($campusProfile->hsc_app_counter+1);
                // $email = $applicationId.'@gmail.com';
                $rand_password= $this->randPasswordGen();
                // store applicant user details
                $appUserProfile->a_no = $username;
                $appUserProfile->username = $username;
                $appUserProfile->password_rand =$rand_password;
                $appUserProfile->password = bcrypt($rand_password);
                $appUserProfile->std_photo = $request->image;
                // $appUserProfile->a_status = $request->input('name', null);
                // $appUserProfile->p_status = $request->input('name', null);
                if($subList) $appUserProfile->sub_list = json_encode($subList);
                if($groupList) $appUserProfile->group_list = json_encode($groupList);
                if($examResult) $appUserProfile->exam_result = json_encode($examResult);
                $appUserProfile->year = $request->input('year', null);
                $appUserProfile->level = $request->input('level', null);
                $appUserProfile->batch = $request->input('batch', null);
                $appUserProfile->s_name = $request->input('s_name', null);
                $appUserProfile->s_name_bn = $request->input('s_name_bn', null);
                $appUserProfile->s_nid = $request->input('s_nid', null);
                $appUserProfile->gender = $request->input('gender', null);
                $appUserProfile->b_date = date('Y-m-d', strtotime($request->input('b_date', null)));
                $appUserProfile->b_group = $request->input('b_group', null);
                $appUserProfile->s_mobile = $request->input('s_mobile', null);
                $appUserProfile->nationality = $request->input('nationality', null);
                $appUserProfile->religion = $request->input('religion', null);
                // $appUserProfile->photo = $request->input('photo', null);
                $appUserProfile->f_name = $request->input('f_name', null);
                $appUserProfile->f_name_bn = $request->input('f_name_bn', null);
                $appUserProfile->f_occupation = $request->input('f_occupation', null);
                $appUserProfile->f_education = $request->input('f_education', null);
                $appUserProfile->f_income = $request->input('f_income', null);
                $appUserProfile->f_income_bn = $request->input('f_income_bn', null);
                $appUserProfile->f_mobile = $request->input('f_mobile', null);
                $appUserProfile->f_nid = $request->input('f_nid', null);
                $appUserProfile->m_name = $request->input('m_name', null);
                $appUserProfile->m_name_bn = $request->input('m_name_bn', null);
                $appUserProfile->m_occupation = $request->input('m_occupation', null);
                $appUserProfile->m_education = $request->input('m_education', null);
                $appUserProfile->m_mobile = $request->input('m_mobile', null);
                $appUserProfile->m_nid = $request->input('m_nid', null);
                $appUserProfile->a_thana = $request->input('a_thana', null);
                $appUserProfile->a_zilla = $request->input('a_zilla', null);
                $appUserProfile->post = $request->input('post', null);
                $appUserProfile->vill = $request->input('vill', null);


                $appUserProfile->address = $request->input('address', null);
                $appUserProfile->exam_name = $request->input('exam_name', null);
                $appUserProfile->exam_board = $request->input('exam_board', null);
                $appUserProfile->exam_year = $request->input('exam_year', null);
                $appUserProfile->exam_reg = $request->input('exam_reg', null);
                $appUserProfile->exam_roll = $request->input('exam_roll', null);
                $appUserProfile->exam_session = $request->input('exam_session', null);
                $appUserProfile->exam_gpa = $request->input('exam_gpa', null);
                $appUserProfile->exam_institute = $request->input('exam_institute', null);
                $appUserProfile->campus_id = $request->input('campus_id', null);
                $appUserProfile->institute_id = $request->input('institute_id', null);
                // save and checking
                if($appUserProfile->save()){
                    // update campus applicant counter
                    $campusProfile->hsc_app_counter = ($campusProfile->hsc_app_counter+1);
                    $campusProfile->save();
                    // If we reach here, then data is valid and working. Commit the queries!
                    DB::commit();
                    // return success with message
                    return ['status' => true, 'type'=>'success', 'msg' => 'Application Submitted Successfully !!!','applicant_id'=>$appUserProfile->id];
                }else{
                    // Rollback and then redirect back to form with errors
                    DB::rollback();
                    // return failed with message
                    return ['status' => false, 'type'=>'failed', 'msg' => 'Unable to submit application'];
                }
            } catch (\Exception $e) {
                DB::rollback();
                // throw $e;
                return ['status' => false, 'type'=>'exception', 'msg' => $e->getMessage()];
            }
        } else {
            // return response with failed msg
            return ['status'=> false,  'type'=>'error', 'errors'=>$validator->errors()];
        }
    }
    // update

    // roll number check
    public function checkRollnumber($rollNumber){

        $hscApplicantProifle=$this->hscApplicant->where('exam_roll',$rollNumber)->first();
        if(!empty($hscApplicantProifle)){
            return 'success';
        } else {
            return 'error';
        }
    }


    // Registration number check
    public function checkRegnumber($regNumber){

        $hscApplicantProifle=$this->hscApplicant->where('exam_reg',$regNumber)->first();
        if(!empty($hscApplicantProifle)){
            return 'success';
        } else {
            return 'error';
        }
    }

    // Secret Number Check

    public function checkSecretCode($secretCode){
        $hscApplicantProifle=$this->hscApplicant->where('secret_code',$secretCode)->first();
        if(!empty($hscApplicantProifle)){
            return 'success';
        } else {
            return 'error';
        }
    }


    // HSC applicant Student login
    public function HscStudentLogin(Request $request)
    {
        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'username' => 'required|max:100', 'password' => 'required',
        ]);
        // storing requesting input data
        if ($validator->passes()) {
            // applicant username and password
            $userName = $request->input('username');
            $passWord = $request->input('password');
            // checking applicant username and password
//            if($applicantProfile = $this->hscApplicant->where(['username'=>$userName])->first()){
            if($applicantProfile = $this->checkHscApplicantUser($userName, $passWord)){
                // return
                return ['status'=>true, 'data'=>$applicantProfile];
            }else{
                // return
                return ['status'=>false, 'msg'=>'Invalid Username or Password'];
            }
        }else{
            // return
            return ['status'=>false, 'msg'=>'please check all inputs are selected ????'];
        }
    }

    ////////////////////////////////////// HSC  Admission //////////////////////////////////////

    public function randPasswordGen(){
        $letters='abcdefghijklmnopqrstuvwxyz';
        $string='';
        for($x=0; $x<3; ++$x){
            $string.=$letters[rand(0,25)].rand(0,9);
        }
        return  $string;
    }


    // applicant payment verification
    public function applicantPaymentVerify(Request $request){
        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'std_id' => 'required', 'amount' => 'required', 'username' => 'required', 'transaction' => 'required',
            'campus_id' => 'required', 'institute_id' => 'required', 'mobile' => 'required',
        ]);

        // storing requesting input data
        if($validator->passes()) {


            // request txn details
            $stdId = $request->std_id;
            $txnAmount = $request->amount;
            $txnId = $request->transaction;
            $txnMobile = $request->mobile;
            // request applicant details
            $userName = $request->username;
            $userCampus = $request->campus_id;
            $userInstitute = $request->institute_id;

            // checking transaction id
            if($findTransactionProfile = $this->hscApplicant->where('transaction_id',$txnId)->first()){
                // response data
                return ['status'=>false, 'msg'=>'Invalid Txn ID, this transactionId already used.'];
            }else{
                // find applicant profile
                if($applicantProfile = $this->hscApplicant->where(['username'=>$userName, 'campus_id'=>$userCampus, 'institute_id'=>$userInstitute])->first()){
                    // sure cash credentials
                    $credentials = base64_encode('sacbhsc:sacbhsc@SureCash');
                    // SureCash API URL for transaction status checking
                    $requestURL = 'https://api.surecashbd.com/api/payment/status/SACBHSC/'.$txnId.'/'.$stdId;
                    // $requestURL ='https://api.surecashbd.com/api/payment/status/SACBHSC/RB00309677602/20180000';
                    // request herder
                    $requestDetails = [
                        'headers' => [
                            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                            // 'Content-type' => 'application/json',
                            'Authorization' => 'Basic ' .$credentials,
                            'Host' => 'api.surecashbd.com',
                            'User-Agent' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:66.0) Gecko/20100101 Firefox/66.0',
                            'Accept-Language' => 'en-US,en;q=0.5',
                            'Accept-Encoding' => 'gzip, deflate, br',
                            'Connection' => 'keep-alive',
                            'Cookie' => 'hibext_instdsigdipv2=1',
                            'Upgrade-Insecure-Requests' => '1',
                            'Cache-Control' => 'max-age=0',
                        ],
                        'body' => null,
                        'timeout'  => 30.0,
                        'debug' => false,
                    ];

                    // http guzzle client
                    $client = new Client();
                    // guzzle client request for transaction profile
                    $txnProfile = json_decode((string) $client->get($requestURL, $requestDetails)->getBody());
                    // response status checking
                    if ($txnProfile->statusCode == '200' AND $txnProfile->status == 'PROCESSED' ) {
                        // checking transaction details
                        if (($txnProfile->amount==$txnAmount) AND ($txnProfile->transactionId==$txnId) AND ($txnProfile->customerMobileNumber==$txnMobile)) {
                            // update applicant payment information
                            $applicantProfile->amount = $txnAmount;
                            $applicantProfile->p_status = 1;
                            $applicantProfile->transaction_id = $txnId;
                            $applicantProfile->response_token = json_encode($txnProfile);
                            // save and checking
                            if($applicantProfile->save()){
                                // response data
                                return ['status'=>true, 'msg'=>'Payment Verified'];
                            }else{
                                // response data
                                return ['status'=>false, 'msg'=>'Unable to update payment information'];
                            }
                        } else {
                            // response data
                            return ['status'=>false, 'msg'=>'Invalid input, please provide valid transaction amount with customer mobile number'];
                        }
                    } else {
                        // response data
                        return ['status'=>false, 'msg'=>$txnProfile->description];
                    }
                }else{
                    // response data
                    return ['status'=>false, 'msg'=>'Invalid Applicant, Please provide with valid application information'];
                }
            }
        }else{
            return ['status'=>false, 'msg'=>'Invalid Information, Please provide with valid information'];
        }
    }

    // get student bye invoice id
    public function getStudentByInvoiceId(Request $request){

//        return "$request";
        $studentProfile= $this->personalInfo->where('invoice_id',$request->invoice_id)->first();
        if(!empty($studentProfile)){
//            return "status";
            $data= json_decode($studentProfile);
            return ['status'=>'success', 'data'=>$data];
        } else {
            return ['status'=>'false', 'msg'=>'Student Invoice ID Not Found'];
        }
    }


    public function tokenStore(Request $request){
        $bkashTokenObj = new BkashToken;
        $bkashTokenObj->token = $request->id_token;
        $bkashTokenObj->expair_time = time() + intval($request->expires_in) - 35;
        $result=$bkashTokenObj->save();
        if($result) {
            return ['status'=>'success', 'token'=>$request->id_token];
        } else {
            return ['status'=>'false', 'msg'=>'Token Not Stored'];
        }
    }

    // get bkaash token
    public function getBkashToken(){
        $tokenProfile = BkashToken::where('expair_time', '>=', time())->first();
        if(!empty($tokenProfile)) {
            return ['status'=>'success', 'token'=>$tokenProfile->token];
        } else {
            return ['status'=>'false', 'msg'=>'Token Not Stored'];
        }
    }

    // store bkash transaction
    public function storeBkashTransaction(Request $request){
        $bkashTransactionObj = new BkashTransaction;
        $bkashTransactionObj->marcent_invoice_num = $request->merchantInvoiceNumber;
        $bkashTransactionObj->transaction_status =$request->transactionStatus;
        $bkashTransactionObj->payment_id =$request->paymentID;
        $bkashTransactionObj->uuid =gen_uuid();
        $result=$bkashTransactionObj->save();
        if(($result)) {
            return ['status'=>'success', 'uuid'=> $bkashTransactionObj->uuid];
        } else {
            return ['status'=>'false', 'msg'=>'Bkash Transaction Not Submit'];
        }
    }


    // store bkash transaction
    public function updateBkashTransaction(Request $request){
//        return $request->all();
        $bkashTransactionProfile =BkashTransaction::where('uuid',$request->id)->first();
        $bkashTransactionProfile->transaction_status =$request->transactionStatus;
        $bkashTransactionProfile->transaction_id =$request->trxID;
        $result=$bkashTransactionProfile->save();
        $personalInfo = $this->personalInfo->where('invoice_id',$bkashTransactionProfile->marcent_invoice_num)->first();
        $applicant = $this->applicant->find($personalInfo->applicant_id);
        $applicant->application_status = 1;
        $applicant->payment_status = 1;
        $applicant->save();

        if(($result)) {
            return ['status'=>'success', 'msg'=>'Bkash Transaction Successfully Updated'];
        } else {
            return ['status'=>'false', 'msg'=>'Bkash Transaction Not Update\''];
        }
    }
    // store bkash transaction
    public function updateBkashTransactionTest(Request $request){
//        return $request->all();
        $bkashTransactionProfile =BkashTransaction::where('uuid',$request->id)->first();
        $data = $this->personalInfo->where('invoice_id',$bkashTransactionProfile->marcent_invoice_num)->first();
        $applicant = $this->applicant->find($data->applicant_id);
        $applicant->application_status = 1;
        $applicant->payment_status = 1;
        $applicant->save();
        return $applicant;

        if(($result)) {
            return ['status'=>'success', 'msg'=>'Bkash Transaction Successfully Updated'];
        } else {
            return ['status'=>'false', 'msg'=>'Bkash Transaction Not Update\''];
        }
    }

    // get bkaash token
    public function findTransaction(Request $request){
        $transactionProfile = BkashTransaction::where('uuid', $request->id)->first();
        if(!empty($transactionProfile)) {
            $data= json_decode($transactionProfile);
            return ['status'=>'success', 'data'=>$data];
        } else {
            return ['status'=>'false', 'msg'=>'Token Not Stored'];
        }
    }



}
