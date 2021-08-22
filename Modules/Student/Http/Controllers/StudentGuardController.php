<?php

namespace Modules\Student\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;
use Modules\Student\Entities\StudentGuardian;
use Modules\Student\Entities\StudentParent;
use Modules\Student\Entities\StudentInformation;
use Modules\Student\Entities\StudentEnrollment;
use App\UserInfo;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Student\Entities\StudentProfileView;
use Redirect;
use Session;
use Validator;
use App\User;
use App\Models\Role;
use Carbon\Carbon;

class StudentGuardController extends Controller
{

    private $studentGuardian;
    private $studentParent;
    private $studentInformation;
    private $userInfo;
    private $academicHelper;
    private $role;
    private $user;
    // constructor
    public function __construct(User $user, StudentGuardian $studentGuardian, StudentParent $studentParent, StudentInformation $studentInformation, UserInfo $userInfo, AcademicHelper $academicHelper,Role $role)
    {
        $this->user = $user;
        $this->role = $role;
        $this->userInfo = $userInfo;
        $this->academicHelper = $academicHelper;
        $this->studentGuardian = $studentGuardian;
        $this->studentParent = $studentParent;
        $this->studentInformation = $studentInformation;

    }

    ////////////////////     Student Guardian Information    ////////////////////

    // student profile guardian main page
    public function getStudentGuardians($id)
    {
        $personalInfo = StudentInformation::findOrFail($id);
        return view('student::pages.student-profile.student-guardians', compact('personalInfo'))->with('page', 'guardians');
    }

    // get student profile personal date edit/update view
    public function createStudentGuardian($id)
    {
        return view('student::pages.student-profile.modals.guardian-info-create')->with('std_id', $id);
    }


/////////////////////// online application guardian creation starts here  ////////////////////////////

    public function storeOnlineStudentGuardian($stdId, $applicantProfile, $applicantPersonalInfo)
    {
        // response data
        $guardianCount = 0;
        // student guardian creation loop
        for ($i=0; $i<2; $i++) {
            // new guardian user profile
            $newGuardUserProfile = new $this->user();
            // store user details
            $newGuardUserProfile->name = ($i == 0 ? $applicantPersonalInfo->mother_name : $applicantPersonalInfo->father_name);
            $newGuardUserProfile->email = $stdId . $i . '@gmail.com';
            $newGuardUserProfile->password = bcrypt(123456);
            // saving parent user profile
            $newGuardUserProfile->save();

            // create new guardian student
            $guardianProfile = new $this->studentGuardian();
            // store guardian details
            $guardianProfile->user_id = $newGuardUserProfile->id;
            // $guardianProfile->title =
            $guardianProfile->type = $i;
            $guardianProfile->first_name = ($i == 0 ? $applicantPersonalInfo->mother_name : $applicantPersonalInfo->father_name);
            //$guardianProfile->last_name =
            $guardianProfile->bn_fullname = ($i == 0 ? $applicantPersonalInfo->mother_name_bn : $applicantPersonalInfo->father_name_bn);
            // $guardianProfile->bn_edu_qualification =
            $guardianProfile->email = $stdId . $i . '@gmail.com';
            $guardianProfile->mobile = $applicantPersonalInfo->gud_phone;
            $guardianProfile->phone = $applicantPersonalInfo->gud_phone;
            //$guardianProfile->relation =
            $guardianProfile->income = ($i == 0 ? '' : $applicantPersonalInfo->gud_income);
            $guardianProfile->occupation = ($i == 0 ? $applicantPersonalInfo->mother_occupation : $applicantPersonalInfo->father_occupation);
            $guardianProfile->qualification = ($i == 0 ? $applicantPersonalInfo->mother_education : $applicantPersonalInfo->father_education);
            $guardianProfile->home_address = ($i == 0 ? '' : $applicantPersonalInfo->add_pre_address);
            $guardianProfile->office_address = ($i == 0 ? '' : $applicantPersonalInfo->add_pre_address);
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

/////////////////////// online application guardian creation ends here  ////////////////////////////


// store guardian
    public function storeStudentGuardian(Request $request)
    {
        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'first_name'     => 'required',
            'last_name'      => 'required|max:100',
            'email'          => 'required|email|max:100|unique:users',
            'guardian_photo'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:200',
            'guardian_signature'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:200',
            'nid_file'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:200',
            'birth_file'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:200',
            'tin_file'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:200',
            'passport_file'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:200',
            'driving_lic_file'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:200',
        ]);

        // storing requesting input data
        if ($validator->passes()) {
            // Start transaction!
            DB::beginTransaction();

            try {
                // std id
                $stdId = $request->input('std_id');
                $studentUser = StudentInformation::findOrFail($stdId)->user();

                // Guardian Institute
                $institute_info = [];
                foreach ($request->institute_name as $key => $institute){
                    $thisInstitute = [];
                    $thisInstitute['institute_name'] = $institute;
                    $thisInstitute['certificate_name'] = $request->certificate_name[$key];
                    $thisInstitute['passing_year'] = $request->passing_year[$key];
                    if (isset($request->certificate_file[$key])){
                        $photoFileName = $institute.'-'.time().'.'.$request->certificate_file[$key]->extension();
                        $request->certificate_file[$key]->move(public_path('family-attachment'), $photoFileName);
                        $thisInstitute['certificate_file'] = $photoFileName;
                    }else{
                        $thisInstitute['certificate_file'] = null;
                    }

                    array_push($institute_info, $thisInstitute);
                }

                // guardian profile
                $photoFileName = null;
                $signatureFileName = null;
                $nidFileName = null;
                $birthFileName = null;
                $tinFileName = null;
                $passportFileName = null;
                $drivingLicFileName = null;
                // Guardian Photo
                if ($request->hasFile('guardian_photo')) {
                    $photoFileName = 'photo-'.time().'.'.$request->guardian_photo->extension();  
                    $request->guardian_photo->move(public_path('family'), $photoFileName);
                }
                // Guardian Signature
                if ($request->hasFile('guardian_signature')) {
                    $signatureFileName = 'signature-'.time().'.'.$request->guardian_signature->extension();  
                    $request->guardian_signature->move(public_path('family'), $signatureFileName);
                }
                // NID File
                if ($request->hasFile('nid_file')) {
                    $nidFileName = 'nid-'.time().'.'.$request->nid_file->extension();  
                    $request->nid_file->move(public_path('family-attachment'), $nidFileName);
                }
                // Birth File
                if ($request->hasFile('birth_file')) {
                    $birthFileName = 'birth-'.time().'.'.$request->birth_file->extension();  
                    $request->birth_file->move(public_path('family-attachment'), $birthFileName);
                }
                // Tin File
                if ($request->hasFile('tin_file')) {
                    $tinFileName = 'tin-'.time().'.'.$request->tin_file->extension();  
                    $request->tin_file->move(public_path('family-attachment'), $tinFileName);
                }
                // Passport File
                if ($request->hasFile('passport_file')) {
                    $passportFileName = 'passport-'.time().'.'.$request->passport_file->extension();  
                    $request->passport_file->move(public_path('family-attachment'), $passportFileName);
                }
                // Driving Lic File
                if ($request->hasFile('driving_lic_file')) {
                    $drivingLicFileName = 'drivingLic-'.time().'.'.$request->driving_lic_file->extension();  
                    $request->driving_lic_file->move(public_path('family-attachment'), $drivingLicFileName);
                }
                
                $guardianProfile = $this->studentGuardian->create($request->except('date_of_birth', 'guardian_photo', 'guardian_signature', 'nid_file', 'birth_file', 'tin_file', 'passport_file', 'driving_lic_file', 'institute_info') + [
                    'date_of_birth' => Carbon::parse($request->date_of_birth),
                    'guardian_photo' => $photoFileName,
                    'guardian_signature' => $signatureFileName,
                    'nid_file' => $nidFileName,
                    'birth_file' => $birthFileName,
                    'tin_file' => $tinFileName,
                    'passport_file' => $passportFileName,
                    'driving_lic_file' => $drivingLicFileName,
                    'institute_info' => json_encode($institute_info)
                ]);

                // checking
                if($guardianProfile){
                    // new user profile
                    $newUserProfile = new $this->user();
                    // store user details
                    $newUserProfile->name = $guardianProfile->first_name." ".$guardianProfile->last_name;
                    $newUserProfile->email = $guardianProfile->email;
                    $guardianTypeLetter = "";
                    switch($request->type) {
                        case '0': $guardianTypeLetter = "M"; break;
                        case '1': $guardianTypeLetter = "F"; break;
                        case '2': $guardianTypeLetter = "S"; break;
                        case '3': $guardianTypeLetter = "B"; break;
                        case '4': $guardianTypeLetter = "R"; break;
                        case '5': $guardianTypeLetter = "O"; break;
                    }
                    for ($i=1; ; $i++) {
                        $guardianUsername = $guardianTypeLetter."-".$i."-".$studentUser->username;
                        $sameUser = User::where('username', $guardianUsername)->first();
                        if (!$sameUser) {
                            break;
                        }
                    }
                    $newUserProfile->username = $guardianUsername;
                    $newUserProfile->password = bcrypt(123456);
                    // saving parent user profile
                    $parentUserCreated = $newUserProfile->save();
                    // checking
                    if($parentUserCreated){
                        // create guardian user info
                        $userInfoProfile = new $this->userInfo();
                        // add user details
                        $userInfoProfile->user_id = $newUserProfile->id;
                        $userInfoProfile->institute_id = $this->academicHelper->getInstitute();
                        $userInfoProfile->campus_id = $this->academicHelper->getCampus();
                        // save user Info profile
                        $userInfoProfileSaved = $userInfoProfile->save();
                        // checking info profile
                        if($userInfoProfileSaved){
                            // studentRoleProfile
                            $studentRoleProfile = $this->role->where('name', 'parent')->first();
                            // assigning student role to this user
                            $newUserProfile->attachRole($studentRoleProfile);
                            // add this guardian as student parent
                            $studentParentProfile = $this->studentParent->create([
                                'gud_id'=>$guardianProfile->id,
                                'std_id'=>$stdId,
                            ]);
                            // checking
                            if($studentParentProfile){
                                // update guardian profile
                                $guardianProfile->user_id = $newUserProfile->id;
                                $guardianProfileUpdate = $guardianProfile->save();
                                // checking
                                if($guardianProfileUpdate){
                                    // If we reach here, then data is valid and working.  Commit the queries!
                                    DB::commit();
                                    // success message
                                    Session::flash('success', 'Student Guardian Added Successfully');
                                    // return back
                                    return redirect()->back();
                                }else {
                                    DB::rollback();
                                    // success message
                                    Session::flash('warning', 'Unable to update guardian');
                                    // return back
                                }
                            }else{
                                DB::rollback();
                                // success message
                                Session::flash('warning', 'Unable to add Guardian');
                                // return back
                                return redirect()->back();
                            }
                        }else{
                            DB::rollback();
                            // success message
                            Session::flash('warning', 'Unable to add parent user info');
                            // return back
                            return redirect()->back();
                        }
                    }else{
                        DB::rollback();
                        // success message
                        Session::flash('warning', 'unable to add guardian user');
                        // return back
                        return redirect()->back();
                    }
                }else{
                    DB::rollback();
                    // success message
                    Session::flash('warning', 'unable to add guardian');
                    // return back
                    return redirect()->back();
                }
            } catch (ValidationException $e) {
                // Rollback and then redirect
                // back to form with errors
                DB::rollback();
                return redirect()->back()->withErrors($e->getErrors())->withInput();
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }
        } else {
            // warning message
            Session::flash('warning', "invalid Information. Please try with correct Information");
            // return to the previous link
            return redirect()->back();
        }
    }

// student profile guardian update
    public function editStudentGuardian($id)
    {
        // guardian profile
        $guardian = StudentGuardian::findOrFail($id);
        return view('student::pages.student-profile.modals.guardian-info-update', compact('guardian'));
    }

    public function makeFamilyGuardian($stdId, $gudId){
        $student = StudentInformation::findOrFail($stdId);
        $familyMember = StudentGuardian::findOrFail($gudId);

        DB::beginTransaction();
        try {
            foreach ($student->myGuardians() as $parent){
                $guardian = $parent->guardian();
                if ($guardian){
                    $guardian->update([
                        'is_guardian' => 0
                    ]);
                }
            }

            $familyMember->update([
                'is_guardian' => 1
            ]);

            DB::commit();
            Session::flash('success', 'Student Guardian Selected Successfully');
            return redirect()->back();
        } catch (\Exception $e){
            DB::rollBack();
            Session::flash('warning', 'Error Selecting Student Guardian');
            return redirect()->back();
        }
    }

// student profile guardian update
    public function updateStudentGuardian(Request $request, $id)
    {
        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'first_name'     => 'required',
            'last_name'      => 'required|max:100',
            'email'          => 'required|email|max:100|unique:users',
            'guardian_photo'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:200',
            'guardian_signature'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:200',
            'nid_file'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:200',
            'birth_file'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:200',
            'tin_file'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:200',
            'passport_file'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:200',
            'driving_lic_file'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:200',
        ]);

        // guardian profile
        $guardianProfile = StudentGuardian::findOrFail($id);
        // checking
        if ($guardianProfile) {
            // Start transaction!
            DB::beginTransaction();

            // user profile
            $guardianUserProfile = $guardianProfile->user();
            // checking guardian user profile
            if($guardianUserProfile){
                // checking
                if($guardianUserProfile->email != $request->input('email')){
                    // validating all requested input data
                    $validator = Validator::make(['email'=>$request->input('email')], [
                        'email' => 'required|email|max:100|unique:users',
                    ]);
                    // storing requesting input data
                    if ($validator->passes()) {
                        // update guardian email
                        $guardianProfileUpdated = $guardianProfile->update([
                            'email' => $request->email
                        ]);

                        if ($guardianProfileUpdated) {
                            $guardianUserProfile->email = $request->email;
                            $guardianUserProfile->save();
                        }
                    }else{
                        Session::flash('warning', 'email already exits');
                        return redirect()->back();
                    }
                }
            }

            // Guardian Institute
            $oldInstituteInfos = json_decode($guardianProfile->institute_info,1);
            if ($oldInstituteInfos){
                foreach($oldInstituteInfos as $oldInstituteInfo){
                    if ($oldInstituteInfo['certificate_file']){
                        $file_path = public_path().'/family-attachment/'.$oldInstituteInfo['certificate_file'];
                        unlink($file_path);
                    }
                }
            }

            $institute_info = [];
            if ($request->institute_name){
                foreach ($request->institute_name as $key => $institute){
                    $thisInstitute = [];
                    $thisInstitute['institute_name'] = $institute;
                    $thisInstitute['certificate_name'] = $request->certificate_name[$key];
                    $thisInstitute['passing_year'] = $request->passing_year[$key];
                    if ($request->certificate_file[$key]){
                        $photoFileName = $institute.'-'.time().'.'.$request->certificate_file[$key]->extension();
                        $request->certificate_file[$key]->move(public_path('family-attachment'), $photoFileName);
                        $thisInstitute['certificate_file'] = $photoFileName;
                    }else{
                        $thisInstitute['certificate_file'] = null;
                    }

                    array_push($institute_info, $thisInstitute);
                }
            }

            // guardian profile
            $photoFileName = $guardianProfile->guardian_photo;
            $signatureFileName = $guardianProfile->guardian_signature;
            $nidFileName = $guardianProfile->nid_file;
            $birthFileName = $guardianProfile->birth_file;
            $tinFileName = $guardianProfile->tin_file;
            $passportFileName = $guardianProfile->passport_file;
            $drivingLicFileName = $guardianProfile->driving_lic_file;
            // Guardian Photo
            if ($request->hasFile('guardian_photo')) {
                if ($guardianProfile->guardian_photo) {
                    $file_path = public_path().'/family/'.$guardianProfile->guardian_photo;
                    unlink($file_path);
                }
                $photoFileName = 'photo-'.time().'.'.$request->guardian_photo->extension();
                $request->guardian_photo->move(public_path('family'), $photoFileName);
            }
            // Guardian Signature
            if ($request->hasFile('guardian_signature')) {
                if ($guardianProfile->guardian_signature) {
                    $file_path = public_path().'/family/'.$guardianProfile->guardian_signature;
                    unlink($file_path);
                }
                $signatureFileName = 'signature-'.time().'.'.$request->guardian_signature->extension();
                $request->guardian_signature->move(public_path('family'), $signatureFileName);
            }
            // NID File
            if ($request->hasFile('nid_file')) {
                if ($guardianProfile->nid_file) {
                    $file_path = public_path().'/family-attachment/'.$guardianProfile->nid_file;
                    unlink($file_path);
                }
                $nidFileName = 'nid-'.time().'.'.$request->nid_file->extension();
                $request->nid_file->move(public_path('family-attachment'), $nidFileName);
            }
            // Birth File
            if ($request->hasFile('birth_file')) {
                if ($guardianProfile->birth_file) {
                    $file_path = public_path().'/family-attachment/'.$guardianProfile->birth_file;
                    unlink($file_path);
                }
                $birthFileName = 'birth-'.time().'.'.$request->birth_file->extension();
                $request->birth_file->move(public_path('family-attachment'), $birthFileName);
            }
            // Tin File
            if ($request->hasFile('tin_file')) {
                if ($guardianProfile->tin_file) {
                    $file_path = public_path().'/family-attachment/'.$guardianProfile->tin_file;
                    unlink($file_path);
                }
                $tinFileName = 'tin-'.time().'.'.$request->tin_file->extension();
                $request->tin_file->move(public_path('family-attachment'), $tinFileName);
            }
            // Passport File
            if ($request->hasFile('passport_file')) {
                if ($guardianProfile->passport_file) {
                    $file_path = public_path().'/family-attachment/'.$guardianProfile->passport_file;
                    unlink($file_path);
                }
                $passportFileName = 'passport-'.time().'.'.$request->passport_file->extension();
                $request->passport_file->move(public_path('family-attachment'), $passportFileName);
            }
            // Driving Lic File
            if ($request->hasFile('driving_lic_file')) {
                if ($guardianProfile->driving_lic_file) {
                    $file_path = public_path().'/family-attachment/'.$guardianProfile->driving_lic_file;
                    unlink($file_path);
                }
                $drivingLicFileName = 'drivingLic-'.time().'.'.$request->driving_lic_file->extension();
                $request->driving_lic_file->move(public_path('family-attachment'), $drivingLicFileName);
            }

            $guardianProfileUpdated = $guardianProfile->update($request->except('email', 'date_of_birth', 'guardian_photo', 'guardian_signature', 'nid_file', 'birth_file', 'tin_file', 'passport_file', 'driving_lic_file') + [
                'date_of_birth' => Carbon::parse($request->date_of_birth),
                'guardian_photo' => $photoFileName,
                'guardian_signature' => $signatureFileName,
                'nid_file' => $nidFileName,
                'birth_file' => $birthFileName,
                'tin_file' => $tinFileName,
                'passport_file' => $passportFileName,
                'driving_lic_file' => $drivingLicFileName,
                'institute_info' => json_encode($institute_info)
            ]);

            // If we reach here, then data is valid and working.  Commit the queries!
            DB::commit();
            // success message
            Session::flash('success', 'Student Guardian Updated');
            // return back
            return redirect()->back();
        } else {
            // success message
            Session::flash('warning', 'Unable to update Guardian');
            // return back
            return redirect()->back();
        }
    }

// student profile guardian delete
    public function destroyStudnetGuardian($id)
    {
        // guardian profile
        $guardianProfile = StudentGuardian::findOrFail($id);
        // checking
        if ($guardianProfile->is_emergency == 1) {
            $studentProfile = $guardianProfile->student();
            //variable
            $count = 1;
            // looping
            foreach ($studentProfile->allGuardian() as $guardian) {
                if ($guardian->id == $id) {
                    continue;
                } else {
                    $guardian->is_emergency = 1;
                    // save update
                    $guardianUpdated = $guardian->save();

                    if ($count == 1) {
                        break;
                    }
                }
            }
        }

        if ($guardianProfile->user_id){
            $deleteUser = User::findOrFail($guardianProfile->user_id)->delete();
        }else{
            $deleteUser = true;
        }

        $deleteStudentParent = StudentParent::where('gud_id', $guardianProfile->id)->first()->delete();

        if ($deleteUser && $deleteStudentParent) {
            // delete guardain profile
            // Guardian Photo
            if ($guardianProfile->guardian_photo) {
                $file_path = public_path().'/family/'.$guardianProfile->guardian_photo;
                unlink($file_path);
            }
            // Guardian Signature
            if ($guardianProfile->guardian_signature) {
                $file_path = public_path().'/family/'.$guardianProfile->guardian_signature;
                unlink($file_path);
            }
            // NID File
            if ($guardianProfile->nid_file) {
                $file_path = public_path().'/family-attachment/'.$guardianProfile->nid_file;
                unlink($file_path);
            }
            // Birth File
            if ($guardianProfile->birth_file) {
                $file_path = public_path().'/family-attachment/'.$guardianProfile->birth_file;
                unlink($file_path);
            }
            // Tin File
            if ($guardianProfile->tin_file) {
                $file_path = public_path().'/family-attachment/'.$guardianProfile->tin_file;
                unlink($file_path);
            }
            // Passport File
            if ($guardianProfile->passport_file) {
                $file_path = public_path().'/family-attachment/'.$guardianProfile->passport_file;
                unlink($file_path);
            }
            // Driving Lic File
            if ($guardianProfile->driving_lic_file) {
                $file_path = public_path().'/family-attachment/'.$guardianProfile->driving_lic_file;
                unlink($file_path);
            }
            $guardianProfileDeleted = $guardianProfile->delete();
        } else {
            $guardianProfileDeleted = null;
        }

        // checking
        if ($guardianProfileDeleted) {
            // success message
            Session::flash('success', 'Student Guardian Dleleted Successfully');
            // return back
            return redirect()->back();
        } else {
            // warning message
            Session::flash('warning', 'Unable to delete Guardian Profile');
            // return back
            return redirect()->back();
        }
    }



//get all guardian name and phone number
    public  function getallStudentGuardian(){


        $institute_id=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();


        $std_List=$this->studentInformation->where('institute',$institute_id)->where('campus',$campus_id)->where('status',1)->get();
        if($std_List) {
            $i=1;
            $studentIdList=array();
            foreach ($std_List as $std) {
                $studentIdList[] = $std->id;
            }
            $parents=$this->studentParent->whereIn('std_id',$studentIdList)->where('is_emergency',1)->get();

            if(!empty($parents)) {
                $parentArray=[];
                foreach ($parents as $parent) {
                    $parentArray[] = $parent->gud_id;
                }

                $parentList = StudentGuardian::whereIn('id', $parentArray)->get();

                if ($parentList->count() > 0) {
                    foreach ($parentList as $parent) {
                        $data[] = array(
                            'id' => $parent->id,
                            'user_id' => $parent->id,
                            'name' => $parent->first_name . '' . $parent->last_name . ' ( ' . $parent->mobile . ' )',
                            'phone' => $parent->mobile,
                            'parents_count' => $i++
                        );
                    }
                    return json_encode($data);
                } else {
                    return json_encode([]);
                }
            } else {
                return json_encode([]);
            }

        }
    }


//get batch section by gurdian list
    public function getParentByBatchSection($batch,$section){

//        $academics_years=session()->get('academic_year');
        $std_enrollments=StudentProfileView::where(['batch'=>$batch,'section'=>$section])->get();
        $studentIdList = array();
        $i=1;
        if($std_enrollments){
            foreach ($std_enrollments as $enrollment){
                $studentinfo=$enrollment->student();
                $studentIdList[]=$studentinfo->id;
            }

            $parents=$this->studentParent->whereIn('std_id',$studentIdList)->where('is_emergency',1)->get();

            if(!empty($parents)) {
                $parentArray=[];
                foreach ($parents as $parent) {
                    $parentArray[] = $parent->gud_id;
                }

                $parentList = StudentGuardian::whereIn('id', $parentArray)->get();

                if ($parentList->count() > 0) {
                    foreach ($parentList as $parent) {
                        $data[] = array(
                            'id' => $parent->id,
                            'user_id' => $parent->id,
                            'name' => $parent->first_name . '' . $parent->last_name . ' ( ' . $parent->mobile . ' )',
                            'phone' => $parent->mobile,
                            'parents_count' => $i++
                        );
                    }
                    return json_encode($data);
                } else {
                    return json_encode([]);
                }
            } else {
                return json_encode([]);
            }
        }
    }

//////////////////////  ajax request ///////////////////

// find student guardian
    public function findGuardian(Request $request)
    {
        //get search term
        $searchTerm = $request->input('term');
        // all guardians list
        $allGuardians = $this->studentGuardian->where('first_name', 'like', "%".$searchTerm."%")->orwhere('last_name', 'like', "%".$searchTerm."%")->get();
        // checking
        if ($allGuardians) {
            $data = array();
            foreach ($allGuardians as $guardian) {
                $data[] = array('id' => $guardian->id, 'name' => $guardian->first_name." ".$guardian->last_name . " (".$guardian->email.")");
            }
            // return data
            return json_encode($data);
        }

    }

}
