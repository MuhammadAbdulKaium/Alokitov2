<?php

namespace Modules\Student\Http\Controllers;

use App\Content;
use App\User;
use File;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Academics\Entities\AcademicsAdmissionYear;
use Modules\Academics\Entities\AcademicsYear;
use Modules\Academics\Entities\Batch;
use Modules\Academics\Entities\Section;
use Modules\CadetFees\Entities\CadetFeesAssign;
use Modules\CadetFees\Entities\CadetFeesGenerate;
use Modules\CadetFees\Entities\FeesHead;
use Modules\CadetFees\Entities\FeesStructureDetails;
use Modules\CadetFees\Entities\StudentFeesCollctionHistory;
use Modules\CadetFees\Entities\StudentFeesCollection;
use Modules\Setting\Entities\Campus;
use Modules\Setting\Entities\Institute;
use Modules\Student\Entities\CadetPersonalPhoto;
use Modules\Student\Entities\StudentAttachment;
use Modules\Student\Entities\StudentEnrollment;
use Modules\Student\Entities\StdEnrollHistory;
use Modules\Student\Entities\StudentInformation;
use Modules\Fees\Entities\FeesInvoice;
use Modules\Setting\Entities\Country;
use App\Http\Controllers\Helpers\AcademicHelper;
use App\UserInfo;
use Redirect;
use Session;
use Validator;
use App\Models\Role;
use Modules\Fees\Http\Controllers\FeesInvoiceController;
use Modules\Student\Entities\StudentAttendanceFine;
use Modules\Student\Entities\StudentProfileView;
use Modules\Student\Entities\StudentWaiver;
use Modules\Fees\Entities\Fees;
use Modules\Fee\Entities\FeeInvoice;

use App;


class StudentInfoController extends Controller
{
    private $user;
    private $fees;
    private $userInfo;
    private $studentInformation;
    private $studentEnrollment;
    private $stdEnrollHistory;
    private $studentAttachment;
    private $academicsYear;
    private $admissionYear;
    private $section;
    private $batch;
    private $feesInvoice;
    private $role;
    private $country;
    private $academicHelper;
    private $campus;
    private $feesInvoiceController;
    private $studentAttendanceFine;
    private $studentProfileView;
    private $studentWaiver;
    private $feeInvoice;


    // constructor
    public function __construct(User $user, FeeInvoice $feeInvoice, StudentAttendanceFine $studentAttendanceFine, Fees $fees, StudentWaiver $studentWaiver, FeesInvoiceController $feesInvoiceController, UserInfo $userInfo, StudentInformation $studentInformation, StudentEnrollment $studentEnrollment, StdEnrollHistory $stdEnrollHistory, StudentAttachment $studentAttachment, AcademicsYear $academicsYear, AcademicsAdmissionYear $admissionYear,Batch $batch, Section $section,FeesInvoice $feesInvoice, Role $role, Country $country, AcademicHelper $academicHelper, Campus $campus, StudentProfileView $studentProfileView)
    {
        $this->user                     = $user;
        $this->userInfo                 = $userInfo;
        $this->studentInformation       = $studentInformation;
        $this->studentEnrollment        = $studentEnrollment;
        $this->stdEnrollHistory         = $stdEnrollHistory;
        $this->studentAttachment        = $studentAttachment;
        $this->admissionYear            = $admissionYear;
        $this->academicsYear            = $academicsYear;
        $this->section                  = $section;
        $this->batch                    = $batch;
        $this->feesInvoice              = $feesInvoice;
        $this->role                     = $role;
        $this->country                  = $country;
        $this->academicHelper           = $academicHelper;
        $this->campus                   = $campus;
        $this->feesInvoiceController    = $feesInvoiceController;
        $this->studentAttendanceFine    = $studentAttendanceFine;
        $this->studentProfileView       = $studentProfileView;
        $this->studentWaiver            = $studentWaiver;
        $this->fees                     = $fees;
        $this->feeInvoice               = $feeInvoice;
    }

    // student dashboard
    public function index()
    {
        return view('student::pages.index');
    }

    ////////////////////     Student Profile     ////////////////////

    public function createStudentInfo()
    {
        // qry
        $qry = [
            'institute_id'=>$this->academicHelper->getInstitute(),
            'campus_id'=>$this->academicHelper->getCampus()
        ];
        // all nationality
        $allNationality = $this->country->orderBy('nationality', 'ASC')->get(['id', 'nationality']);
        // all admission years
        $admissionYears = $this->admissionYear->where($qry)->get();
        // all academics year
        $academicYears  = $this->academicsYear->where($qry)->get();
        // institute all campus list
        $allCampus      = $this->campus->orderBy('name', 'ASC')->where('institute_id', $this->academicHelper->getInstitute())->get();
        // return view with all variables
        return view('student::pages.student-add', compact('admissionYears', 'academicYears', 'allCampus', 'allNationality'));
    }

    public function storeStudentInfo(Request $request)
    {
//        dd($request->all());
//        return $request->all();

        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'first_name'     => 'required|max:100',
            // 'middle_name'    => 'max:100',
            // 'last_name'      => 'required|max:100',
            'gender'         => 'required',
            'dob'            => 'required',
            // 'birth_place'    => 'max:100',
            'email'          => 'required|max:100|unique:users',
            //  'phone'          => 'required|numeric',
            //  'nationality'    => 'required|numeric',
            'gr_no'          => 'max:20',
            'campus'         => 'required|numeric',
            'academic_level' => 'required|numeric',
            'batch'          => 'required|numeric',
            'section'        => 'required|numeric',
            'enrolled_at'    => 'required',
            'academic_year'  => 'required|numeric',
            'admission_year' => 'required|numeric',
        ]);

        //waiver check get value
        $waiverCheck=$request->input('waivercheck');


        // storing requesting input data
        if ($validator->passes()) {

            // Start transaction!
            DB::beginTransaction();

            // student user creation
            try {
                $userFullName = $request->input('first_name')." ".$request->input('last_name');
                // create user profile for student
                // $manageUserProfile = $this->manageUserProfile($userId, $userData);
                $userProfile = $this->manageUserProfile(0, ['name' =>$userFullName,'username'=>$request->input('email'), 'email' => $request->input('email'), 'password'=> bcrypt(123456)]);
                // checking user profile
                if($userProfile){
                    $userInfoProfile = new $this->userInfo();
                    // add user details
                    $userInfoProfile->user_id = $userProfile->id;
                    $userInfoProfile->institute_id = $this->academicHelper->getInstitute();
                    $userInfoProfile->campus_id = $request->input('campus');
                    // save user Info profile
                    $userInfoProfileSaved = $userInfoProfile->save();
                }

            } catch (ValidationException $e) {
                // Rollback and then redirect
                // back to form with errors
                // Redirecting with error message
                DB::rollback();
                return redirect()->back()
                    ->withErrors($e->getErrors())
                    ->withInput();
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

            // student profile creation
            try {
                // create user profile
                // $$studentProfile = $this->manageStdProfile($stdId, $stdData);
                $studentProfile = $this->manageStdProfile(0, [
                    'user_id'     => $userProfile->id,
                    'type'        => $request->input('type'),
                    'title'       => $request->input('title'),
                    'first_name'  => $request->input('first_name'),
                    'last_name'   => $request->input('last_name'),
                    'bn_fullname' => $request->input('bn_fullname'),
                    'gender'      => $request->input('gender'),
                    'dob'         => date('Y-m-d', strtotime($request->input('dob'))),
                    'blood_group' => $request->input('blood_group'),
                    'religion'    => $request->input('religion'),
                    'birth_place' => $request->input('birth_place'),
                    'email'       => $request->input('email'),
                    'phone'       => $request->input('phone'),
                    'residency'   => $request->input('residency'),
                    'passport_no' => $request->input('passport_no'),
                    'nationality' => $request->input('nationality'),
                    'language' => $request->input('language'),
                    'batch_no' => $request->input('batch_no'),
                    'academic_group' => $request->input('academic_group'),
                    'campus'      => $request->input('campus'),
                    'institute'      => $this->academicHelper->getInstitute(),
                ]);

                // Address Creation
                if ($request->present_address){
                    App\Address::create([
                        'user_id' => $userProfile->id,
                        'type' => 'STUDENT_PRESENT_ADDRESS',
                        'address' => $request->present_address
                    ]);
                }
                if ($request->permanent_address){
                    App\Address::create([
                        'user_id' => $userProfile->id,
                        'type' => 'STUDENT_PERMANENT_ADDRESS',
                        'address' => $request->permanent_address
                    ]);
                }

                // Tution Fees creation
                if($request->tuition_fees){
                    CadetFeesAssign::create([
                        'std_id' => $studentProfile->id,
                        'fees' => $request->tuition_fees,
                        'academic_level' => $request->input('academic_level'),
                        'batch'          => $request->input('batch'),
                        'section'        => $request->input('section'),
                        'academic_year'  => $request->input('academic_year'),
                        'campus_id'      => $request->input('campus'),
                        'instittute_id'      => $this->academicHelper->getInstitute(),
                        'created_by' => Auth::id()
                    ]);
                }

            } catch (ValidationException $e) {
                // Rollback and then redirect
                // back to form with errors
                DB::rollback();
                return redirect()->back()
                    ->withErrors($e->getErrors())
                    ->withInput();
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }


            // student enrollment creation
            try {
                // create student enrollment
                // $enrollmentProfile = $this->manageStdEnrollment($enrollId, $enrollData);
                $enrollmentProfile = $this->manageStdEnrollment(0, [
                    'std_id'         => $studentProfile->id,
                    'gr_no'          => $request->input('gr_no'),
                    'academic_level' => $request->input('academic_level'),
                    'batch'          => $request->input('batch'),
                    'section'        => $request->input('section'),
                    'academic_year'  => $request->input('academic_year'),
                    'admission_year' => $request->input('admission_year'),
                    'enroll_status' => 'IN_PROGRESS',
                    'enrolled_at'    => date('Y-m-d', strtotime($request->input('enrolled_at'))),
                ]);

            } catch (ValidationException $e) {
                // Rollback and then redirect
                // back to form with errors
                DB::rollback();
                return redirect()->back()
                    ->withErrors($e->getErrors())
                    ->withInput();
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

            if($waiverCheck>0) {
                // student Waiver Add
                try {
                    $studentWaiver = new $this->studentWaiver;
                    $studentWaiver->std_id = $studentProfile->id;
                    $studentWaiver->institution_id = $this->academicHelper->getInstitute();
                    $studentWaiver->campus_id = $this->academicHelper->getCampus();
                    $studentWaiver->type = $request->input('type');
                    $studentWaiver->waiver_type = $request->input('waiver_type');
                    $studentWaiver->value = $request->input('value');
                    $studentWaiver->start_date = date('Y-m-d', strtotime($request->input('start_date')));
                    $studentWaiver->end_date = date('Y-m-d', strtotime($request->input('end_date')));
                    $studentWaiver->status = 1;
                    $saveStudentWaiver = $studentWaiver->save();


                } catch (ValidationException $e) {
                    // Rollback and then redirect
                    // back to form with errors
                    DB::rollback();
                    return redirect()->back()
                        ->withErrors($e->getErrors())
                        ->withInput();
                } catch (\Exception $e) {
                    DB::rollback();
                    throw $e;
                }
            }

            // student role assignment
            try {
                // studentRoleProfile
                $studentRoleProfile = $this->role->where('name', 'student')->first();
                // assigning student role to this user
                $studentRoleAssignment = $userProfile->attachRole($studentRoleProfile);
            } catch (ValidationException $e) {
                // Rollback and then redirect
                // back to form with errors
                DB::rollback();
                return redirect()->back()
                    ->withErrors($e->getErrors())
                    ->withInput();
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

            // If we reach here, then
            // data is valid and working.
            // Commit the queries!
            DB::commit();

            // checking and redirecting
            if ($enrollmentProfile) {
                Session::flash('success', 'Student profile created');
                return redirect('/student/profile/personal/' . $studentProfile->id);
            } else {
                Session::flash('warning', 'unable to crate student profile');
                // receiving page action
                return redirect()->back()->withErrors($validator)->withInput();
            }
        } else {
            Session::flash('warning', 'Invalid Information');
            // receiving page action
            return redirect()->back()->withErrors($validator)->withInput();
        }

    }

    ////////////////////     Student Profile Email and Photo    ////////////////////

    // student email edit
    public function editUserEmial($id)
    {
        $studentProfile = StudentInformation::findOrFail($id);
        return view('student::pages.student-profile.modals.email-update', compact('studentProfile'));
    }

    // Student email update
    public function updateUserEmial(Request $request, $id)
    {

        // validating all requested input data
        $validator = Validator::make($request->all(), ['username' => 'required|max:100']);

        // storing requesting input data
        if ($validator->passes()) {

            // student profile
            $studentProfile = StudentInformation::findOrFail($id);

            // user profile
            $userProfile = $studentProfile->user();
            // find user email
            $userEmail = $this->user->where(['username'=>$request->input('username')])->first();
            // checking Email already exits or not
            if(($userEmail==null) || ($userEmail->id==$userProfile->id)){
                // update user profile
                $userProfile->email = $request->input('username');
                $userProfile->username = $request->input('username');
                $userEmailUpdated   = $userProfile->save();

                if ($userEmailUpdated){
                    $parents = $studentProfile->myGuardians();

                    foreach($parents as $parent){
                        $guardianUser = $parent->guardian()->user();
                        $guardUserNameArr = explode("-", $guardianUser->username);
                        $newUserName = $guardUserNameArr[0]."-".$guardUserNameArr[1]."-".$request->input('username');
                        $guardianUser->email = $newUserName;
                        $guardianUser->username = $newUserName;
                        $guardianUser->save();
                    }
                }

                // session success message
                Session::flash('success', 'Cadet Number updated');
                return redirect()->back();
            }else{
                Session::flash('warning', 'Cadet Number already exits');
                // receiving page action
                return redirect()->back()->withErrors($validator)->withInput();
            }
        } else {
            Session::flash('warning', 'Invalid Information');
            // receiving page action
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    // edit student photo
    public function editStudentPhoto($id)
    {
        // student profile
        $studentProfile = StudentInformation::findOrFail($id);
        return view('student::pages.student-profile.modals.photo-upload', compact('studentProfile'));
    }

    // store studetn photo
    public function storeStudentPhoto(Request $request)
    {
        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'image'  => 'required',
            'std_id' => 'required',
        ]);

        // storing requesting input data
        if ($validator->passes()) {

            // photo storing
            $photoFile     = $request->file('image');
            $fileExtension = $photoFile->getClientOriginalExtension();
            //$contentName     = $photoFile->getClientOriginalName();
            $contentName     = "ems" . date("Ymdhis") . mt_rand(100000, 999999) . "." . $fileExtension;
            $contentFileName = $contentName;
            $destinationPath = 'assets/users/images/';
            $photoStore=new CadetPersonalPhoto;
            $personalInfo = StudentInformation::findOrFail($request->std_id);
            $enrollment = $personalInfo->enroll();

            // Start transaction!
            DB::beginTransaction();

            try {

                $uploaded = $photoFile->move($destinationPath, $contentFileName);
                // storing file name to the database
                if ($uploaded) {
                    // user documet
                    $userDocument = new Content();
                    // storing user documetn
                    $userDocument->name      = $contentName;
                    $userDocument->file_name = $contentFileName;
                    $userDocument->path      = $destinationPath;
                    $userDocument->mime      = $fileExtension;
                    $photo_store=$userDocument->save();
                    if($photo_store){
                        $photoStore->image=$contentName;
                        $photoStore->date=$request->date;
                        $photoStore->cadet_no=$personalInfo->email;
                        $photoStore->student_id=$request->std_id;
                        $photoStore->campus_id=$personalInfo->campus;
                        $photoStore->institute_id=$personalInfo->institute;
                        $photoStore->academics_year_id=$enrollment->academic_year;
                        $photoStore->section_id=$enrollment->section;
                        $photoStore->batch_id= $enrollment->batch;
                        $photoStorage=$photoStore->save();
                    }
                } else {
                    Session::flash('warning', 'unable to upload photo');
                }
            } catch (ValidationException $e) {
                // Rollback and then redirect
                // back to form with errors
                // Redirecting with error message
                DB::rollback();
                return redirect()->back()
                    ->withErrors($e->getErrors())
                    ->withInput();
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

            try {

                // Validate, then create if valid
                // upload flile to the student attachment database
                $studentAttachment = new StudentAttachment();
                // storing student attachment
                $studentAttachment->std_id     = $request->input('std_id');
                $studentAttachment->doc_id     = $userDocument->id;
                $studentAttachment->doc_type   = "PROFILE_PHOTO";
                $studentAttachment->doc_status = 0;
                // save student attachment profile
                $attachmentUploaded = $studentAttachment->save();

            }
            catch (ValidationException $e) {
                // Rollback and then redirect
                // back to form with errors
                DB::rollback();
                return redirect()->back()
                    ->withErrors($e->getErrors())
                    ->withInput();
            }
            catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

            // If we reach here, then data is valid and working.
            // Commit the queries!
            DB::commit();

            // session mes
            if ($attachmentUploaded) {
                Session::flash('success', 'Pofile picture added successfully');
                // receiving page action
                return redirect()->back();
            } else {
                Session::flash('warning', 'unable to perform the action. please try with correct Information');
                // receiving page action
                return redirect()->back();
            }

        } else {
            Session::flash('warning', 'invalid Information. please try with correct Information');
            // receiving page action
            return redirect()->back()->withErrors($validator)->withInput();
        }

    }

    // student photo update
    public function updateStudentPhoto(Request $request, $id)
    {
//        return $request->file('image');
        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'image' => 'required',
        ]);

        $personalInfo = StudentInformation::findOrFail($request->std_id);
        $enrollment = $personalInfo->enroll();
        // storing requesting input data
        if ($validator->passes()) {

            // receiving photo file
            $photoFile = $request->file('image');
            $photoStore=new CadetPersonalPhoto;
            // student attachment
            $photoProfile = StudentAttachment::findOrFail($id);
            // update photo
            if (!empty($photoFile) && $photoProfile) {

                // content profile
                $contentProfile = $photoProfile->singleContent();
                // old image path
                $oldImagePath = $contentProfile->path . $contentProfile->name;
                // photo storing
                $fileExtension = $photoFile->getClientOriginalExtension();
                //$contentName     = $photoFile->getClientOriginalName();
                $contentName     = "ems" . date("Ymdhis") . mt_rand(100000, 999999) . "." . $fileExtension;
                $contentFileName = $contentName;
                $uploaded        = $photoFile->move('assets/users/images/', $contentFileName);

                // checking
                if ($uploaded) {
                    // update content
                    $contentProfile->name      = $contentName;
                    $contentProfile->file_name = $contentFileName;
                    $contentProfile->mime      = $fileExtension;
                    // save content
                    $imageUddated = $contentProfile->save();

                    // checking and delete old photo form the image folder
                    if ($imageUddated) {
                        $photoStore->image=$contentName;
                        $photoStore->date=$request->date;
                        $photoStore->cadet_no=$personalInfo->email;
                        $photoStore->student_id=$request->std_id;
                        $photoStore->campus_id=$personalInfo->campus;
                        $photoStore->institute_id=$personalInfo->institute;
                        $photoStore->academics_year_id=$enrollment->academic_year;
                        $photoStore->section_id=$enrollment->section;
                        $photoStore->batch_id= $enrollment->batch;
                        $photoStorage=$photoStore->save();

                        // success message
                        Session::flash('success', 'photo updated successfully');
                        // return back
                        return redirect()->back();
                    } else {
                        // success message
                        Session::flash('warning', 'unablet to update photo');
                        // return back
                        return redirect()->back();
                    }
//                    delete part end
                } else {
                    Session::flash('warning', 'unable to upload image to the server');
                    // receiving page action
                    return redirect()->back();
                }
            } else {
                Session::flash('warning', 'unable to perform the action.');
                // receiving page action
                return redirect()->back();
            }
        } else {
            Session::flash('warning', 'invalid Information. please try with correct Information');
            // receiving page action
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    ////////////////////     Student Personal Information    ////////////////////

    // student profile personal main page
    public function getPersonalInfo($id)
    {
        $personalInfo = StudentInformation::findOrFail($id);

        $instInfo = DB::table('student_informations')
                    ->join('setting_institute','student_informations.institute','setting_institute.id')
                    ->where('student_informations.id',$id)
                    ->select('setting_institute.institute_name')->get();


        $campusInfo = DB::table('student_informations')
                    ->join('setting_campus','student_informations.campus','setting_campus.id')
                    ->where('student_informations.id',$id)
                    ->select('setting_campus.name')->get();

        return view('student::pages.student-profile.student-personal', compact('personalInfo','campusInfo','instInfo'))->with('page', 'personal');
    }

    // student profile edit page
    public function editPersonalInfo($id)
    {
        $personalInfo = StudentInformation::findOrFail($id);
        // all nationality
        $allNationality = $this->country->orderBy('nationality', 'ASC')->get(['id', 'nationality']);
        // return view with variables
        return view('student::pages.student-profile.modals.personal-info-update', compact('personalInfo', 'allNationality'));
    }
    // update student personal info
    public function updatePersonalInfo(Request $request, $id)
    {
        $personalInfo = StudentInformation::findOrFail($id);
        $userProfile = $personalInfo->user();

        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'gender'      => 'required',
            'dob'         => 'required',
            'email'          => 'required|max:100|unique:users,username,'.$userProfile->id,
        ]);

        $userFullName = $request->input('first_name')." ".$request->input('last_name');

        // storing requesting input data
        if ($validator->passes()) {
            // update personal info
            $dob = date('Y-m-d',strtotime($request->input('dob')));
            $updated = $personalInfo->update($request->all());
            $updateUserProfile = $userProfile->update([
                'name' => $userFullName,
                'username'=>$request->input('email'),
                'email' => $request->input('email')
            ]);

            // Address Creation
            if ($request->present_address){
                $presentAddress = $personalInfo->presentAddress();
                if ($presentAddress){
                    $presentAddress->update([
                        'address' => $request->present_address
                    ]);
                } else{
                    App\Address::create([
                        'user_id' => $userProfile->id,
                        'type' => 'STUDENT_PRESENT_ADDRESS',
                        'address' => $request->present_address
                    ]);
                }
            }else{
                $presentAddress = $personalInfo->presentAddress();
                if ($presentAddress){
                    $presentAddress->delete();
                }
            }
            if ($request->permanent_address){
                $permanentAddress = $personalInfo->permanentAddress();
                if ($permanentAddress){
                    $permanentAddress->update([
                        'address' => $request->permanent_address
                    ]);
                }else{
                    App\Address::create([
                        'user_id' => $userProfile->id,
                        'type' => 'STUDENT_PERMANENT_ADDRESS',
                        'address' => $request->permanent_address
                    ]);
                }
            }else{
                $permanentAddress = $personalInfo->permanentAddress();
                if ($permanentAddress){
                    $permanentAddress->delete();
                }
            }

            // Tution Fees creation
            if($request->tuition_fees){
                $previousTuitionFees = $personalInfo->tuitionFees;
                if ($previousTuitionFees) {
                    $previousTuitionFees->update([
                        'fees' => $request->tuition_fees
                    ]);
                } else {
                    CadetFeesAssign::create([
                        'std_id' => $personalInfo->id,
                        'fees' => $request->tuition_fees,
                        'fine_type' => $request->tuition_fees,
                        'academic_level' => $request->input('academic_level'),
                        'batch'          => $request->input('batch'),
                        'section'        => $request->input('section'),
                        'academic_year'  => $request->input('academic_year'),
                        'campus_id'      => $request->input('campus'),
                        'instittute_id'      => $this->academicHelper->getInstitute(),
                        'created_by' => Auth::id()
                    ]);
                }

            }

            // checking
            if ($updated) {
                // session alert message
                Session::flash('success', 'Student Personal Information Updated');
                // redirect back
                return redirect()->back();
            } else {
                // session alert message
                Session::flash('warning', 'Unable to update Student Personal Information');
                // redirect back
                return redirect()->back();
            }
        } else {
            Session::flash('warning', 'invalid Information. please try with correct Information');
            // receiving page action
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    ////////////////// Student Fees /////////////////////
    public function getStudentFees($id)
    {

        $personalInfo = StudentInformation::findOrFail($id);
        $generatedFees = CadetFeesGenerate::join('fees_structure','cadet_fees_generate.structure_id','fees_structure.id')
            ->where('std_id',$id)->select('cadet_fees_generate.*','fees_structure.structure_name')->get();
        $month_list = array(1 => 'Jan.', 2 => 'Feb.', 3 => 'Mar.', 4 => 'Apr.', 5 => 'May', 6 => 'Jun.', 7 => 'Jul.', 8 => 'Aug.', 9 => 'Sep.', 10 => 'Oct.', 11 => 'Nov.', 12 => 'Dec.');
        $feesCollection = StudentFeesCollection::join('cadet_fees_generate','student_fees_collection.fees_generate_id','cadet_fees_generate.id')
            ->where('student_fees_collection.std_id',$id)
            ->select('student_fees_collection.*','cadet_fees_generate.inv_id','cadet_fees_generate.status')->get();
        return view('student::pages.student-profile.student-fees', compact('feesCollection','month_list','personalInfo','generatedFees'))->with('page', 'fees');
    }
    public function getStudentFeesInvoice($id)
    {
        $generatedFees = CadetFeesGenerate::join('fees_structure','cadet_fees_generate.structure_id','fees_structure.id')
            ->where('cadet_fees_generate.id',$id)->select('cadet_fees_generate.*','fees_structure.structure_name')->first();
        $studentFeesAssign= CadetFeesAssign::where('structure_id',$generatedFees->structure_id)
            ->where('std_id',$generatedFees->std_id)->first();
        $personalInfo = StudentProfileView::where('std_id',$generatedFees->std_id)->first();
        $feesHeadDetails=json_decode($studentFeesAssign->fees_details,1);
        $feesHeads=FeesHead::where([
            'institute_id' => $this->academicHelper->getInstitute(),
            'campus_id' => $this->academicHelper->getCampus()
        ])->get();
        $institute = Institute::findOrFail($this->academicHelper->getInstitute());

        $month_list = array(1 => 'Jan.', 2 => 'Feb.', 3 => 'Mar.', 4 => 'Apr.', 5 => 'May', 6 => 'Jun.', 7 => 'Jul.', 8 => 'Aug.', 9 => 'Sep.', 10 => 'Oct.', 11 => 'Nov.', 12 => 'Dec.');
        return view('student::pages.student-profile.modals.generated-fees-invoice',compact('institute','personalInfo','feesHeads','feesHeadDetails','generatedFees','month_list'));
    }
    public function getStudentFeesCollectionInvoice($id)
    {
        $feesHeads=FeesHead::where([
            'institute_id' => $this->academicHelper->getInstitute(),
            'campus_id' => $this->academicHelper->getCampus()
        ])->get();

        $feeCollection = StudentFeesCollection::join('cadet_fees_generate','student_fees_collection.fees_generate_id','cadet_fees_generate.id')
            ->where('student_fees_collection.id',$id)->select('cadet_fees_generate.status','cadet_fees_generate.inv_id','cadet_fees_generate.structure_id','student_fees_collection.*','cadet_fees_generate.month_name')->first();
        $studentFeesAssign= CadetFeesAssign::where('structure_id',$feeCollection->structure_id)
            ->where('std_id',$feeCollection->std_id)->first();
        $feesHeadDetails=json_decode($studentFeesAssign->fees_details,1);
        $personalInfo = StudentProfileView::where('std_id',$feeCollection->std_id)->first();
        $institute = Institute::findOrFail($this->academicHelper->getInstitute());
        $month_list = array(1 => 'Jan.', 2 => 'Feb.', 3 => 'Mar.', 4 => 'Apr.', 5 => 'May', 6 => 'Jun.', 7 => 'Jul.', 8 => 'Aug.', 9 => 'Sep.', 10 => 'Oct.', 11 => 'Nov.', 12 => 'Dec.');
        return view('student::pages.student-profile.modals.collected-fees-invoice',compact('feesHeadDetails','feesHeads','month_list','feeCollection','institute','personalInfo','studentFeesAssign'));

    }
    public function getStudentFeesCollectionHistory($id,$genId)
    {

        $feeCollections = StudentFeesCollctionHistory::join('cadet_fees_generate','student_fees_collection_history.fees_generate_id','cadet_fees_generate.id')
            ->where('student_fees_collection_history.std_id',$id)->where('student_fees_collection_history.fees_generate_id',$genId)
            ->select('cadet_fees_generate.status','cadet_fees_generate.inv_id','cadet_fees_generate.structure_id','student_fees_collection_history.*','cadet_fees_generate.month_name')->get();
//        $studentFeesAssign= CadetFeesAssign::where('structure_id',$feeCollection->structure_id)
//            ->where('std_id',$feeCollection->std_id)->first();
        $month_list = array(1 => 'Jan.', 2 => 'Feb.', 3 => 'Mar.', 4 => 'Apr.', 5 => 'May', 6 => 'Jun.', 7 => 'Jul.', 8 => 'Aug.', 9 => 'Sep.', 10 => 'Oct.', 11 => 'Nov.', 12 => 'Dec.');
        return view('student::pages.student-profile.modals.collected-fees-history',compact('month_list','feeCollections'));

    }
    public function getStudentFeesCollectionInvoicePdf($id)
    {
        $feesHeads=FeesHead::where([
            'institute_id' => $this->academicHelper->getInstitute(),
            'campus_id' => $this->academicHelper->getCampus()
        ])->get();

        $feeCollection = StudentFeesCollection::join('cadet_fees_generate','student_fees_collection.fees_generate_id','cadet_fees_generate.id')
            ->where('student_fees_collection.id',$id)->select('cadet_fees_generate.status','cadet_fees_generate.inv_id','cadet_fees_generate.structure_id','student_fees_collection.*','cadet_fees_generate.month_name')->first();
        $studentFeesAssign= CadetFeesAssign::where('structure_id',$feeCollection->structure_id)
            ->where('std_id',$feeCollection->std_id)->first();
        $feesHeadDetails=json_decode($studentFeesAssign->fees_details,1);
        $personalInfo = StudentProfileView::where('std_id',$feeCollection->std_id)->first();
        $institute = Institute::findOrFail($this->academicHelper->getInstitute());
        $month_list = array(1 => 'Jan.', 2 => 'Feb.', 3 => 'Mar.', 4 => 'Apr.', 5 => 'May', 6 => 'Jun.', 7 => 'Jul.', 8 => 'Aug.', 9 => 'Sep.', 10 => 'Oct.', 11 => 'Nov.', 12 => 'Dec.');

        $pdf = App::make('dompdf.wrapper');

        $pdf->loadView('student::pages.student-profile.modals.collected-fees-invoice-pdf',compact('feesHeadDetails','feesHeads','month_list','feeCollection','institute','personalInfo','studentFeesAssign'))->setPaper('a4', 'portrait');
        return $pdf->stream();
    }

    public function  getStudentFeesInfo($id){

        $campus_id =$this->academicHelper->getCampus();
        $institute_id = $this->academicHelper->getInstitute();

        // this year and previous year
        $year = date("Y");
        $previousyear = $year -1;

        $feesMonthYearList=$this->fees->whereNotNull('month')->whereIn('year',[$year,$previousyear])->where('institution_id',$institute_id)->where('campus_id',$campus_id)->get();
        if(!empty($feesMonthYearList)) {
            // month and year wise feesid list
            $feesIdList=$feesMonthYearList->pluck('id');
            (object) $studentInviceByYearMonth=$this->feesInvoiceController->singleMonthInvoice($id,$feesIdList);
        } else {
            $studentInviceByYearMonth=[];
        }

        $studentInviceByYearMonth;

        // student all invoice list
        $personalInfo = StudentInformation::findOrFail($id);

        $studentId=$id;
        (object) $inviceByYearMonth= $this->feesInvoiceController->singleStudentAllInvoice($studentId);


        // attendance Fine List
        $attendanceFineList=$this->feesInvoice->where('payer_id',$studentId)->where('invoice_type',2)->orderBy('id','desc')->get();
        if(!empty($attendanceFineList)) {
            foreach ($attendanceFineList as $fine) {
                $year = date('Y', strtotime($fine->created_at));
                $month = date('m', strtotime($fine->created_at));
                $attenFineByYearMonth[$year][$month][] = $fine;
            }
        } else  {
            $attenFineByYearMonth=0;
        };

        return view('student::pages.student-profile.student-fees_info', compact('personalInfo','attenFineByYearMonth','inviceByYearMonth','studentInviceByYearMonth'))->with('page', 'fees_info');
    }


    public function  getStudentFeesInfoReportById($id){

        $instituteInfo=$this->academicHelper->getInstituteProfile();

        (object) $inviceByYearMonth= $this->feesInvoiceController->singleStudentAllInvoice($id);
        //generate PDf  snappy.pdf.wrapper
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('reports::pages.report.student_monthly_fees',compact('instituteInfo','inviceByYearMonth'))->setPaper('a4', 'portrait');
        // return $pdf->stream();
        view('reports::pages.report.student_monthly_fees',compact('instituteInfo','inviceByYearMonth'));
        $downloadFileName = "fees_invoice_report.pdf";
        return $pdf->download($downloadFileName);
    }




    ////////////////// ajax request /////////////////////
    public function findStudent(Request $request)
    {
        //get search term and request details
        $searchTerm = $request->input('term');
        $campus =$this->academicHelper->getCampus();
        $institute = $this->academicHelper->getInstitute();
        // find student
        $allStudents = $this->studentProfileView->whereRaw("campus = $campus AND institute = $institute AND (first_name LIKE '%$searchTerm%' OR middle_name LIKE '%$searchTerm%' OR last_name LIKE '%$searchTerm%' OR username LIKE '%$searchTerm%')")->get();

        // checking
        if ($allStudents) {
            $data = array();
            foreach ($allStudents as $student) {
                $stdEnroll = $student->enroll();
                if($stdEnroll == null) continue;
                $batch = $stdEnroll->batch();
                // checking
                if($division = $batch->division()){
                    $batchName = $batch->batch_name." - ".$division->name;
                }else{
                    $batchName = $batch->batch_name;
                }
                // store into data set
                $data[] = array(
                    'id' => $student->std_id,
                    'name' =>$student->first_name . " " . $student->middle_name . " " . $student->last_name .' - '.$student->username. " (" . $batchName .', '. $stdEnroll->section()->section_name . ")",
                    'name_id' => $student->first_name . " " . $student->middle_name . " " . $student->last_name .' - '.$student->username,
                );
            }

            return json_encode($data);
        }

    }

    // find  user
    public function findUser(Request $request)
    {
        //get search User
        $searchUser = $request->input('search_user');

        $allStudents = StudentInformation::where('first_name', 'like', "%" . $searchUser . "%")->orwhere('middle_name', 'like', "%" . $searchUser . "%")->orwhere('last_name', 'like', "%" . $searchUser . "%")->get();
        // checking
        if ($allStudents) {
            $data = array();
            foreach ($allStudents as $student) {
                $data[] = array('id' => $student->user_id, 'name' => $student->first_name . " " . $student->middle_name . " " . $student->last_name);
            }

            return json_encode($data);
        }

    }

    // create or update user profile
    public function manageUserProfile($userId, $userData)
    {
        // userId checking
        if($userId>0){
            $userProfile = $this->user->findOrFail($userId)->update($userData);
        }else{
            $userProfile = $this->user->create($userData);
        }

        // userProfile checking
        if ($userProfile) {
            return $userProfile;
        } else {
            return false;
        }

    }

    // create or update student profile
    public function manageStdProfile($stdId, $stdData)
    {
        // stdId checking
        if($stdId>0){
            $studentProfile = $this->studentInformation->findOrFail($stdId)->update($stdData);
        }else{
            $studentProfile = $this->studentInformation->create($stdData);
        }

        // studentProfile checking
        if ($studentProfile) {
            return $studentProfile;
        } else {
            return false;
        }
    }

    // create or update std enrollment profile
    public function manageStdEnrollment($enrollId, $enrollmentData)
    {
        // enrollId checking
        if($enrollId>0){
            $enrollProfile = $this->studentEnrollment->findOrFail($enrollId)->update($enrollmentData);
        }else{
            $enrollProfile = $this->studentEnrollment->create($enrollmentData);
            // checking
            if($enrollProfile){
                $enrollmentData = (object)$enrollmentData;
                // create new stdEnrollHistory
                $stdEnrollHistoryProfile = new $this->stdEnrollHistory();
                // input details
                $stdEnrollHistoryProfile->enroll_id = $enrollProfile->id;
                $stdEnrollHistoryProfile->gr_no = $enrollmentData->gr_no;
                $stdEnrollHistoryProfile->section = $enrollmentData->section;
                $stdEnrollHistoryProfile->batch = $enrollmentData->batch;
                $stdEnrollHistoryProfile->academic_level = $enrollmentData->academic_level;
                $stdEnrollHistoryProfile->academic_year =$enrollmentData->academic_year;
                $stdEnrollHistoryProfile->batch_status = 'IN_PROGRESS';
                $stdEnrollHistoryProfile->enrolled_at = date('Y-m-d', strtotime($enrollmentData->enrolled_at));
                // save $stdEnrollHistory
                $stdEnrollHistoryProfile->save();
            }
        }

        // enrollProfile checking
        if ($enrollProfile) {
            return $enrollProfile;
        } else {
            return false;
        }
    }


    // student Photo uploader (from base64 to image)
    public function applicantPhotoUploader($stdId, $imageData)
    {
        // checking image data
        if($imageData!=null) {
            // Extract base64 file for standard data
            $fileBin = file_get_contents($imageData);
            $fileExtension = str_replace('image/', '', mime_content_type($imageData));
            $fileName = $stdId . uniqid() . '.' . $fileExtension;
            $destinationPath = 'assets/users/images/' . $fileName;
            // move file to the destination path
            if (file_put_contents($destinationPath, $fileBin)) {
                // user document
                $userDocument = new Content();
                // storing user document
                $userDocument->name = $fileName;
                $userDocument->file_name = $fileName;
                $userDocument->path = $destinationPath;
                $userDocument->mime = $fileExtension;
                // save and checking
                if ($userDocument->save()) {
                    // new student attachment
                    $studentAttachment = new StudentAttachment();
                    // storing student attachment
                    $studentAttachment->std_id = $stdId;
                    $studentAttachment->doc_id = $userDocument->id;
                    $studentAttachment->doc_type = "PROFILE_PHOTO";
                    $studentAttachment->doc_status = 0;
                    // save student attachment profile
                    if ($studentAttachment->save()) {
                        // return true
                        return true;
                    }
                }
            }
        }
        // return false
        return true;
    }


    /// get all student id and phone number for sms
    public function getAllStudent(){
        $academics_years=session()->get('academic_year');
        $std_enrollments=$this->studentEnrollment->where('academic_year', $academics_years)->get();
        $data = array();
        $i=1;
        if($std_enrollments){
            foreach ($std_enrollments as $enrollment){
                $studentinfo=$enrollment->student();
                $data[]=array(
                    'id'=>$studentinfo->id,
                    'user_id'=>$studentinfo->user_id,
                    'name'=>$studentinfo->first_name.' '.$studentinfo->middle_name.''.$studentinfo->last_name.' ( '.$studentinfo->phone.' )',
                    'phone'=>$studentinfo->phone,
                    'student_count'=>$i++
                );
            }
            return json_encode($data);
        }
    }

    // change student status
    public function getStudentStatus($id)
    {
        // find student profile
        $studentProfile = $this->studentInformation->find($id);
        // return view with variable(s)
        return view('student::pages.modal.student-status', compact('studentProfile'));
    }


    // change student status
    public function storeStudentStatus(Request $request)
    {
        // request details
        $stdId = $request->input('std_id');
        $status = $request->input('status');
        // institute details
        $campus = $this->academicHelper->getCampus();
        $institute = $this->academicHelper->getInstitute();
        // find student profile
        $stdProfile = $this->studentInformation->where(['id'=>$stdId, 'campus'=>$campus, 'institute'=>$institute])->first();
        // checking student profile
        if($stdProfile){
            // update student profile
            $stdProfile->status = $status;
            // save update
            if($stdProfile->save()){
                // return success msg
                return ['status'=>true, 'request_type'=>$status, 'std_id'=>$stdProfile->id, 'msg'=>'Student Status Updated'];
            }else{
                // return success msg
                return ['status'=>false, 'msg'=>'Unable to update student status'];
            }
        }else{
            // return success msg
            return ['status'=>false, 'msg'=>'Student not found !!!!'];
        }
    }


    // get batch section by get student id and phone
    public function getStudentByBatchSection($batch,$section){

//        $academics_years=session()->get('academic_year');
        $studentProfileViewList=$this->studentProfileView->where(['batch'=>$batch,'section'=>$section])->where('status',1)->get();
//        $std_enrollments=$this->studentEnrollment->where(['batch'=>$batch,'section'=>$section])->where('enroll_status','IN_PROGRESS')->get();
        $data = array();
        $i=1;
        if($studentProfileViewList->count()>0){
            foreach ($studentProfileViewList as $studentProfile){
                $studentinfo=$studentProfile->student();
                $data[]=array(
                    'id'=>$studentinfo->id,
                    'user_id'=>$studentinfo->user_id,
                    'name'=>$studentinfo->first_name.' '.$studentinfo->middle_name.''.$studentinfo->last_name.' ( '.$studentinfo->phone.' )',
                    'phone'=>$studentinfo->phone,
                    'student_count'=>$i++
                );
            }
            return json_encode($data);
        } else {
            return ['status'=>false, 'msg'=>'Student not found !!!!'];
        }
    }

    // institute sorter
    public function instituteSorting($instituteId, $collections)
    {
        return $collections->filter(function($singleProfile) use ($instituteId)
        {
            return $singleProfile->institute == $instituteId;
        });
    }

    // campus sorter
    public function campusSorting($campusId, $collections)
    {
        return $collections->filter(function($singleProfile) use ($campusId)
        {
            return $singleProfile->campus == $campusId;
        });
    }


    // get all invoice for a single student
//    new fees modules
    public function getStudentNewFeesInfo($studentId){
        $personalInfo = StudentInformation::findOrFail($studentId);
        $studentInvoiceList=$this->feeInvoice->where('student_id',$studentId)->get();
        return view('student::pages.student-profile.student-fees-new', compact('personalInfo','studentInvoiceList'))->with('page', 'fees-new');

    }




}
