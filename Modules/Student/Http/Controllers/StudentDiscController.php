<?php

namespace Modules\Student\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;
use Modules\Setting\Entities\CadetPerformanceCategory;
use Modules\Student\Entities\CadetAssesment;
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

class StudentDiscController extends Controller
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
    public function getStudentPsyco($id)
    {
        $personalInfo = StudentInformation::findOrFail($id);
        $recordInfo = CadetAssesment::where([
            'type'=> 5,
            'student_id' => $id
        ])->orderBy('created_at', 'desc')->get();

        return view('student::pages.student-profile.student-discipline', compact('personalInfo','recordInfo'))->with('page', 'discipline');
    }

    //get cariculam performance
    public function getStudentPerfCurriculam($id){
        $personalInfo = StudentInformation::findOrFail($id);
        return view('student::pages.student-profile.student-performance-cariculam', compact('personalInfo'))->with('page', 'performance')->with('performance','curriculum');
    }

    // get student profile personal date edit/update view
    public function createStudentPerformanceCaruculam($id)
    {
        return view('student::pages.student-profile.modals.cariculam-info-create')->with('std_id', $id);
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
        ]);

        // storing requesting input data
        if ($validator->passes()) {
            // Start transaction!
            DB::beginTransaction();

            try {

                // std id
                $stdId = $request->input('std_id');
                // guardian profile
                $guardianProfile = $this->studentGuardian->create($request->all());
                // checking
                if($guardianProfile){
                    // new user profile
                    $newUserProfile = new $this->user();
                    // store user details
                    $newUserProfile->name = $guardianProfile->first_name." ".$guardianProfile->last_name;
                    $newUserProfile->email = $guardianProfile->email;
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

// student profile guardian update
    public function updateStudentGuardian(Request $request, $id)
    {

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
                        // update guardian profile
                        $guardianProfileUpdated = $guardianProfile->update($request->all());
                    }else{
                        // success message
                        Session::flash('warning', 'email already exits');
                        // return back
                        return redirect()->back();
                    }
                }else{
                    $guardianProfileUpdated = $guardianProfile->update($request->except('email'));
                }
            }else{
                // update guardian profile
                $guardianProfileUpdated = $guardianProfile->update($request->all());
            }

            // checking
            if ($guardianProfileUpdated) {
                // checking guardian user profile
                if($guardianUserProfile){
                    // update user email
                    if($guardianUserProfile->email != $request->input('email')){
                        // update user email address
                        $guardianUserProfile->email = $request->email;
                        $guardianUserProfile->save();
                    }
                }
                // If we reach here, then data is valid and working.  Commit the queries!
                DB::commit();
                // success message
                Session::flash('success', 'Student Guardian Updated');
                // return back
                return redirect()->back();
            } else {
                DB::rollback();
                // success message
                Session::flash('warning', 'Unable to perform the action');
                // return back
                return redirect()->back();
            }
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
        if ($guardianProfile->is_emergency == 0) {
            // delete guardain profile
            $guardianProfileDeleted = $guardianProfile->delete();
        } else {

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

            // delete guardain profile
            $guardianProfileDeleted = $guardianProfile->delete();
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
