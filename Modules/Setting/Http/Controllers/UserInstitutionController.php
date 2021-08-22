<?php

namespace Modules\Setting\Http\Controllers;

use Redirect;
use Session;
use Validator;
use App\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\Setting\Entities\Campus;
use Modules\Setting\Entities\Institute;
use App\UserInfo;

class UserInstitutionController extends Controller
{
    private $role;
    private $user;
    private $userInfo;
    private $campus;
    private $institute;
    private $academicHelper;
    private $employeeInformation;

    public function __construct(User $user, AcademicHelper $academicHelper, EmployeeInformation $employeeInformation, Campus $campus, UserInfo $userInfo, Role $role, Institute $institute)
    {
        $this->role = $role;
        $this->user = $user;
        $this->userInfo = $userInfo;
        $this->campus = $campus;
        $this->institute = $institute;
        $this->academicHelper = $academicHelper;
        $this->employeeInformation = $employeeInformation;
    }

    //////////////////////////////////// user (admin) campus assignment ////////////////////////////////////

    // find all users (admin) of this institute and campus
    public function index()
    {
        // institute and campus details
        $campusId = $this->academicHelper->getCampus();
        $instituteId = $this->academicHelper->getInstitute();
        // find all users using campus and institute id
        $allUsers = $this->userInfo->where([
            'campus_id'=>$campusId,
            'institute_id'=>$instituteId
        ])->distinct()->get(['user_id']);

        // campus admin array list
        $campusAdminList = array();
        // looping
        foreach ($allUsers as $campusUser){
            // find user profile
            $user = $campusUser->user();
            // user role checking
            if($user->hasRole('admin')==false) continue;
            // store user to the admin array list
            $campusAdminList[] = $user;
        }
        // return campus admin array list
        return view('setting::manage-users.users', compact('campusAdminList'));
    }

    // create admin user
    public function createAdminUser()
    {
        // return campus admin array list
        return view('setting::manage-users.modals.user');
    }

    // store admin user
    public function storeAdminUser(Request $request)
    {
        // request details
        $userId = $request->input('user_id', 0);
        $name = $request->input('name');
        $email = $request->input('email');
        $campusId = $this->academicHelper->getCampus();
        $instituteId = $this->academicHelper->getInstitute();

        // checking user id
        if($userId>0){ // update statements will be here

            // admin user update statements
            Session::flash('warning', 'Invalid Information');
            // receiving page action
            return redirect()->back();

        }else{ // create statements will be here

            // validating all requested input data
            $validator = Validator::make($request->all(), ['email'=>'required|email|max:100|unique:users']);
            // validation checker
            if ($validator->passes()) {
                // Start transaction!
                DB::beginTransaction();
                // start to try crating admin user
                try {
                    // now add campus admin user profile
                    $userProfile = $this->user->create(['name' =>$name, 'email' => strtolower($email), 'password'=> bcrypt(123456)]);
                    // checking
                    if($userProfile){
                        // create user_info for the newly created user
                        $userInfoProfile = new $this->userInfo();
                        // add user details
                        $userInfoProfile->user_id = $userProfile->id;
                        $userInfoProfile->institute_id = $instituteId;
                        $userInfoProfile->campus_id = $campusId;
                        // save user Info profile
                        if($userInfoProfile->save()){
                            // find admin profile
                            $adminRoleProfile = $this->role->where('name', 'admin')->first();
                            // assigning student role to this user
                            $userProfile->attachRole($adminRoleProfile);

                            // If we reach here, then data is valid and working.
                            // Commit the queries!
                            DB::commit();
                            //session data
                            Session::flash('success', 'Admin User Created');
                            // receiving page action
                            return redirect()->back();

                        }else{
                            Session::flash('warning', 'Unable to create user Information profile');
                            // receiving page action
                            return redirect()->back();
                        }
                    }else{
                        Session::flash('warning', 'Unable to create user profile');
                        // receiving page action
                        return redirect()->back();
                    }
                } catch (ValidationException $e) {
                    // Rollback and then redirect back to form with errors
                    // Redirecting with error message
                    DB::rollback();
                    return redirect()->back()->withErrors($e->getErrors())->withInput();
                } catch (\Exception $e) {
                    DB::rollback();
                    throw $e;
                }
            }else{
                Session::flash('warning', 'Email Already Used, Please Try with another email');
                // receiving page action
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }
    }

    // show admin user campus list
    public function showAdminUserCampusList($userId)
    {
        $campusId = $this->academicHelper->getCampus();
        $instituteId = $this->academicHelper->getInstitute();

        // user profile
        $userProfile = $this->user->find($userId);
        // institute campus list
        $campusList = $this->campus->where(['institute_id'=>$instituteId])->orderBy('name', 'ASC')->get();
        // return view
        return view('setting::manage-users.modals.user-campus-list', compact('campusList', 'userProfile', 'userId','campusId','instituteId'));
    }



    //////////////////////////////////// user (employee) campus assignment ////////////////////////////////////

    // assign institute campus to a specific user
    public function assignCampus()
    {
        // get institute list
        $instituteList = $this->academicHelper->getInstituteList();
        // return view with variables
        return view('setting::manage-user-institution.index', compact('instituteList'));
    }

    // change institute campus
    public function changeCampus($id)
    {
        // forget campus id from the session
        session()->forget('campus');
        // set campus id in the session
        session(['campus' => $id]);
        // redirect
        return redirect('/admin');
    }

    public function getUserCampus(Request $request)
    {
        $userId = $request->input('user_id');
        $campusId = $request->input('campus');
        $instituteId = $request->input('institute');

        // user profile
        $userProfile = $this->user->find($userId);
        // institute campus list
        $campusList = $this->campus->where(['institute_id'=>$instituteId])->orderBy('name', 'ASC')->get();
        // return view
        return view('setting::manage-user-institution.modals.user-assignment', compact('campusList', 'userProfile', 'userId','campusId','instituteId'));
    }

    // employee campus assignment
    public function assignUserCampus(Request $request)
    {
        $userId = $request->input('user_id');
        $campusId = $request->input('campus_id');
        $instituteId = $request->input('institute_id');
        $assignmentType = $request->input('assignment_type');
        // checking
        if($assignmentType=='assign'){
            $assignment = $this->userInfo->create([
                'user_id'=>$userId,
                'campus_id'=>$campusId,
                'institute_id'=>$instituteId
            ]);
        }else{
            $assignment =  $this->userInfo->where([
                'user_id'=>$userId,
                'campus_id'=>$campusId,
                'institute_id'=>$instituteId
            ])->delete();
        }
        // return msg
        if($assignment){
            return ['status'=>'success', 'msg'=>'success'];
        }else{
            return ['status'=>'failed', 'msg'=>'failed'];
        }
    }

    // find employee with search terms
    public function findInstituteUser(Request $request){
        // request details
        $searchTerm = $request->input('term');
        $campusId = $request->input('campus_id');
        $instituteId = $request->input('institute_id');
        // employee list
        $employeeList = $this->employeeInformation
            ->where('first_name', 'like', "%" . $searchTerm . "%")
            ->orwhere('middle_name', 'like', "%" . $searchTerm . "%")
            ->orwhere('last_name', 'like', "%" . $searchTerm . "%")
            ->orwhere('email', 'like', "%" . $searchTerm . "%")->get();
        // filter using institute id and campus id
        $employeeList = $this->campusSorting($campusId, $this->instituteSorting($instituteId, $employeeList));
        // employee array list
        $data = array();
        foreach ($employeeList as $employee){
            $data[]=array(
                'id'=>$employee->user_id,
                'name'=>$employee->first_name.' '.$employee->middle_name.''.$employee->last_name.' ( '.$employee->email.' )',
            );
        }
        return json_encode($data);
    }




    //////////// UNO Institute assignment ////////////
    public function getUNOInstitute(Request $request)
    {
        // inst and user id
        $userId = $request->input('user_id');
        $instId = $request->input('institute_id');
        // instituteProfile
        $userProfile = $this->user->find($userId);
        $instituteProfile = $this->institute->find($instId);
        // checking
        if($instituteProfile){
            // view rendering
            $html = view('setting::manage-user-institution.modals.uno-institute-list', compact('instituteProfile', 'userProfile'))->render();
            // return view with variable
            return ['status'=>'success', 'content'=>$html];
        }else{
            // return view with variable
            return ['status'=>'failed', 'msg'=>'Institute Profile not found'];
        }
    }

    public function assignUNOInstitute(Request $request)
    {
        // inst and user id
        $userId = $request->input('user_id');
        $instId = $request->input('institute_id');
        $assignmentType = $request->input('assignment_type');
        // find institute profile
        $instituteProfile = $this->institute->find($instId);
        // checking institute profile
        if($instituteProfile){
            // find institute campus list
            $campusList = $instituteProfile->campus();
            // campus list checking
            if($campusList->count()>0){
                // campus loop counter
                $campusLoopCounter = 0;
                // campus looping
                foreach ($campusList as $campus){
                    // now checking assignment type
                    if($assignmentType=='assign'){
                        // now start to assigning campus to the user
                        $assignment = $this->userInfo->create([
                            'user_id'=>$userId,
                            'campus_id'=>$campus->id,
                            'institute_id'=>$instituteProfile->id
                        ]);
                        // checking
                        if($assignment){
                            // loop count
                            $campusLoopCounter += 1;
                        }
                    }elseif($assignmentType=='remove'){
                        $assignment =  $this->userInfo->where([
                            'user_id'=>$userId,
                            'campus_id'=>$campus->id,
                            'institute_id'=>$instituteProfile->id
                        ])->delete();
                        // checking
                        if($assignment){
                            // loop count
                            $campusLoopCounter += 1;
                        }
                    }else{
                        return ['status'=>'failed', 'msg'=>'Assignment Type not matched'];
                    }
                }
                // checking
                if($campusLoopCounter==$campusList->count()){
                    return ['status'=>'success', 'msg'=>'Institute Assigned'];
                }else{
                    return ['status'=>'failed', 'msg'=>'Unable to assign institute'];
                }
            }else{
                return ['status'=>'failed', 'msg'=>'No campus found for the institute'];
            }
        }else{
            return ['status'=>'failed', 'msg'=>'Institute profile not found'];
        }
    }


    // institute sorter
    public function instituteSorting($instituteId, $collections)
    {
        return $collections->filter(function($singleProfile) use ($instituteId)
        {
            return $singleProfile->institute_id == $instituteId;
        });
    }

    // campus sorter
    public function campusSorting($campusId, $collections)
    {
        return $collections->filter(function($singleProfile) use ($campusId)
        {
            return $singleProfile->campus_id == $campusId;
        });
    }
    public function uno(){
            return view('setting::manage-users.pieChart')->with('page','institute');
    }
    public function unoCompare(){
        return view('setting::manage-users.pieChartCompare')->with('page','institute');
    }
}
