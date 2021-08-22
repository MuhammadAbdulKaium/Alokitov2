<?php

namespace Modules\Employee\Http\Controllers;

use App\Address;
use App\User;
use App\Content;
use File;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Employee\Entities\EmployeeAttachment;
use Modules\Employee\Entities\EmployeeDepartment;
use Modules\Employee\Entities\EmployeeDesignation;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\Setting\Entities\Country;
use Redirect;
use Session;
use Validator;
use App\Models\Role;
use App\Http\Controllers\Helpers\AcademicHelper;

class EmployeeInfoController extends Controller
{

    private $user;
    private $role;
    private $country;
    private $department;
    private $designation;
    private $academicHelper;

    // constructor
    public function __construct(User $user, Role $role, Country $country, AcademicHelper $academicHelper, EmployeeDepartment $department, EmployeeDesignation $designation)
    {
        $this->user = $user;
        $this->role = $role;
        $this->country = $country;
        $this->department = $department;
        $this->designation = $designation;
        $this->academicHelper = $academicHelper;
    }

    // show function
    public function showEmployeeInfo($id)
    {
        // find employee information
        $employeeInfo = EmployeeInformation::findOrFail($id);
        // return view
        return view('employee::pages.profile.personal', compact('employeeInfo'))->with('page', 'personal');
    }

    // edit function
    public function editEmployeeInfo($id)
    {
        // institute and campus details
        $campusId = $this->academicHelper->getCampus();
        $instituteId = $this->academicHelper->getInstitute();
        // all roles
        $allRole = $this->role->orderBy('name', 'ASC')->whereNotIn('name', ['parent', 'student', 'admin'])->get();
        // all nationality
        $allNationality = $this->country->orderBy('nationality', 'ASC')->get(['id', 'nationality']);
        // employee designations
        $allDesignaitons = $this->designation->where(['campus_id'=>$campusId, 'institute_id'=>$instituteId])->orderBy('name', 'ASC')->get();
        // employee departments
        $allDepartments = $this->department->where(['campus_id'=>$campusId, 'institute_id'=>$instituteId, 'dept_type'=>0])->orderBy('name', 'ASC')->get();
        // employee profile
        $employeeInfo = EmployeeInformation::findOrFail($id);
        // return view with variable
        return view('employee::pages.modals.personal-update', compact('allRole','employeeInfo', 'allDesignaitons', 'allDepartments', 'allNationality'));
    }

    // update function
    public function updateEmployeeInfo(Request $request, $id)
    {
        // find employee

        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'first_name'       => 'max:100',
           // 'middle_name'      => 'required|max:100',
            //'last_name'        => 'max:100',
            //'alias'            => 'max:100',
            'gender'           => 'required',
            'dob'              => 'required',
            'department'       => 'required|numeric',
            'designation'      => 'required|numeric',
            'category'         => 'required|numeric',
            //'phone'            => 'required|max:20',
           // 'nationality'      => 'numeric',
            //'experience_year'  => 'numeric',
            //'experience_month' => 'numeric',
        ]);

        // vaildator checker
        if ($validator->passes()) {
            // find employee
            $employeeInfo = EmployeeInformation::findOrFail($id);
            // storing student profile
            $employeeInfo->title            = $request->input('title');
            $employeeInfo->first_name       = $request->input('first_name');
            $employeeInfo->last_name        = $request->input('last_name');
            $employeeInfo->employee_no        = $request->input('employee_no');
            $employeeInfo->position_serial        = $request->input('position_serial');
            $employeeInfo->alias            = $request->input('alias');
            $employeeInfo->gender           = $request->input('gender');
            $employeeInfo->dob              = date('Y-m-d', strtotime($request->input('dob')));
            $employeeInfo->doj              = date('Y-m-d', strtotime($request->input('doj')));
            $employeeInfo->dor              = date('Y-m-d', strtotime($request->input('dor')));
            $employeeInfo->department       = $request->input('department');
            $employeeInfo->designation      = $request->input('designation');
            $employeeInfo->category         = $request->input('category');
            $employeeInfo->phone            = $request->input('phone');
            $employeeInfo->blood_group      = $request->input('blood_group');
            $employeeInfo->birth_place      = $request->input('birth_place');
            $employeeInfo->marital_status      = $request->input('marital_status');
            $employeeInfo->religion         = $request->input('religion');
            $employeeInfo->nationality      = $request->input('nationality');
            $employeeInfo->experience_year  = $request->input('experience_year');
            $employeeInfo->experience_month = $request->input('experience_month');
            $employeeInfo->sort_order = $request->input('sort_order', null);
            // save employee profile
            $employeeInfoUpdated = $employeeInfo->save();

            // Address pdate
            if ($request->present_address){
                $previousAddress = Address::where([
                    'user_id' => $employeeInfo->user()->id,
                    'type' => 'EMPLOYEE_PRESENT_ADDRESS',
                ])->first();

                if ($previousAddress){
                    $previousAddress->update([
                        'address' => $request->present_address
                    ]);
                } else{
                    Address::create([
                        'user_id' => $employeeInfo->user()->id,
                        'type' => 'EMPLOYEE_PERMANENT_ADDRESS',
                        'address' => $request->present_address
                    ]);
                }
            }
            if ($request->permanent_address){
                $previousAddress = Address::where([
                    'user_id' => $employeeInfo->user()->id,
                    'type' => 'EMPLOYEE_PERMANENT_ADDRESS',
                ])->first();

                if ($previousAddress){
                    $previousAddress->update([
                        'address' => $request->permanent_address
                    ]);
                } else{
                    Address::create([
                        'user_id' => $employeeInfo->user()->id,
                        'type' => 'EMPLOYEE_PERMANENT_ADDRESS',
                        'address' => $request->permanent_address
                    ]);
                }
            }

            // checking
            if ($employeeInfoUpdated) {
                // detach all roles from this employee
                $employeeInfo->user()->roles()->detach();
                // employeeRoleProfile
                $employeeRoleProfile = $this->role->where('id', $request->input('role'))->first();
                // assigning student role to this user
                $employeeRoleProfileAssignment = $employeeInfo->user()->attachRole($employeeRoleProfile);
            }
            // checking
            if ($employeeInfoUpdated) {
                Session::flash('success', 'Employee personal information updated');
                // receiving page action
                return redirect()->back();
            } else {
                Session::flash('warning', 'Unable to update employeeInfo information');
                // receiving page action
                return redirect()->back();
            }
        } else {
            Session::flash('warning', 'Invalid information');
            // receiving page action
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    ///////////////////////////////////////   Employee photo and email //////////////////////////////

    public function employeeEmailEdit($id)
    {
        //find employee
        $employeeInfo = EmployeeInformation::findOrFail($id);
        // return view
        return View('employee::pages.modals.employee-email-update', compact('employeeInfo'));
    }
    public function employeeEmailUpdate(Request $request, $id)
    {
        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:100|unique:users',
        ]);

        // storing requesting input data
        if ($validator->passes()) {

            // student profile
            $employeeInfo = EmployeeInformation::findOrFail($id);

            // user profile
            $userInfo = $employeeInfo->user();
            // update user profile
            $userInfo->email  = $request->input('email');
            $userEmailUpdated = $userInfo->save();
            // checking
            if ($userEmailUpdated) {
                // update student profile
                $employeeInfo->email = $request->input('email');
                // save update
                $employeeInfoUpdated = $employeeInfo->save();

                // checking
                if ($employeeInfoUpdated) {
                    // session success message
                    Session::flash('success', 'Employee email updated');
                    return redirect()->back();
                } else {
                    // session success message
                    Session::flash('warning', 'unable to perform the action');
                    return redirect()->back();
                }
            } else {
                // session warning mess
                Session::flash('warning', 'unable to update employee email');
                return redirect()->back();
            }

        } else {
            Session::flash('warning', 'Email already exits');
            // receiving page action
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    public function employeePhotoEdit($id)
    {
        //find employee
        $employeeInfo = EmployeeInformation::findOrFail($id);
        // return view
        return View('employee::pages.modals.employee-photo-update', compact('employeeInfo'));
    }

    public function storeEmployeePhoto(Request $request)
    {
        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'image'  => 'required',
            'emp_id' => 'required',
        ]);

        // storing requesting input data
        if ($validator->passes()) {

            // photo storing
            $photoFile       = $request->file('image');
            $fileExtension   = $photoFile->getClientOriginalExtension();
            $contentName     = $photoFile->getClientOriginalName();
            $contentFileName = $photoFile->getClientOriginalName();
            $destinationPath = 'assets/users/images/';

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
                    $userDocument->save();
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
                $employeeAttachment = new EmployeeAttachment();
                // storing student attachment
                $employeeAttachment->emp_id     = $request->input('emp_id');
                $employeeAttachment->doc_id     = $userDocument->id;
                $employeeAttachment->doc_type   = "PROFILE_PHOTO";
                $employeeAttachment->doc_status = 0;
                // save student attachment profile
                $attachmentUploaded = $employeeAttachment->save();

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
    public function employeePhotoUpdate(Request $request, $id)
    {
        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'image' => 'required',
        ]);

        // storing requesting input data
        if ($validator->passes()) {

            // receiving photo file
            $photoFile = $request->file('image');

            // student attachment
            $photoProfile = EmployeeAttachment::findOrFail($id);

            // update photo
            if (!empty($photoFile) && $photoProfile) {

                // content profile
                $contentProfile = $photoProfile->singleContent();
                // old image path
                $oldImagePath = $contentProfile->path . $contentProfile->name;
                // photo storing
                $fileExtension   = $photoFile->getClientOriginalExtension();
                $contentName     = $photoFile->getClientOriginalName();
                $contentFileName = $photoFile->getClientOriginalName();
                $uploaded        = $photoFile->move($contentProfile->path, $contentFileName);

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
                        // check image path
                        if ($oldImagePath != $contentProfile->path . $contentProfile->name) {
                            // now deleting the post image
                            File::delete($oldImagePath);
                        }
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

    // //////////////////  ajax request //////////////////
    // public function findAllEmployee()
    // {
    //     // all employee
    //     $allEmployee = array();
    //     // find all employee
    //     $allEmployeeInfo = EmployeeInformation::orderBy('first_name', 'ASC')->get(['id', 'first_name', 'middle_name', 'last_name']);
    //     // looping
    //     foreach ($allEmployeeInfo as $employee) {
    //         $allEmployee[] = array('id' => $employee->id, 'name' => $employee->first_name . " " . $employee->middle_name . " " . $employee->last_name);
    //     }

    //     // return all employee
    //     return $allEmployee;
    // }


    public  function  getOnlyTeacher(){

        $teachers=EmployeeInformation::where(['category'=>'1', 'institute_id' => $this->academicHelper->getInstitute()])->get();

        foreach ($teachers as $teacher){
            $data[] = array(
                'id'=>$teacher->id,
                'user_id'=>$teacher->user_id,
                'phone'=>$teacher->phone,
                'name'=>$teacher->first_name.' '.$teacher->middle_name.' '.$teacher->last_name." ( ".$teacher->phone." )",
                'teacher_count'=>$teachers->count()
            );
        }

        return json_encode($data);
    }


    public  function  getAllStuff(){

        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

        $stuffs= $teachers=EmployeeInformation::where('institute_id',$instituteId)->where('campus_id',$campus_id)->where('category','0')->get();
        $i=1;
        foreach ($stuffs as $stuff){
            $data[] = array(
                'id'=>$stuff->id,
                'user_id'=>$stuff->user_id,
                'phone'=>$stuff->phone,
                'name'=>$stuff->first_name.' '.$stuff->middle_name.' '.$stuff->last_name." ( ".$stuff->phone." )",
                'stuff_count'=>$i++
            );
        }

        return json_encode($data);
    }

    public  function  getStuffByDepartment($department_id){

        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();
        $stuffs=EmployeeInformation::where('institute_id',$instituteId)->where('campus_id',$campus_id)->where('department',$department_id)->where('category','0')->get();

        if($stuffs->count()>0) {
            $i = 1;
            foreach ($stuffs as $stuff) {
                $data[] = array(
                    'id' => $stuff->id,
                    'user_id' => $stuff->user_id,
                    'phone' => $stuff->phone,
                    'name' => $stuff->first_name . ' ' . $stuff->middle_name . ' ' . $stuff->last_name . " ( " . $stuff->phone . " )",
                    'stuff_count' => ++$i
                );
            }

//            return $data= json_encode($data);

            return response()->json(['data' => $data, 'status' => 'success']);

        } else {
            return response()->json(['status' => 'error']);
        }
    }


    // find all teacher and staff
    public function getAllEmployee(Request $request)
    {

        $institute=$this->academicHelper->getInstitute();
        $campus=$this->academicHelper->getCampus();


        //get search term
        $searchTerm = $request->input('term');

        $allEmployee = EmployeeInformation::where('first_name', 'like', "%" . $searchTerm . "%")
            ->orwhere('middle_name', 'like', "%" . $searchTerm . "%")->orwhere('last_name', 'like', "%" . $searchTerm . "%")->limit(300)->get();

        $allEmployee = $this->campusSorting($campus, $this->instituteSorting($institute, $allEmployee));
        // checking
        if ($allEmployee) {
            $data = array();
            foreach ($allEmployee as $employee) {
                $data[] = array('id' => $employee->id, 'name' => $employee->first_name . " " . $employee->middle_name . " " .$employee->last_name."( ".$employee->designation()->name." )");
            }

            return json_encode($data);
        }

    }

//    // employee find
//
//    ////////////////// ajax request /////////////////////
//    public function findEmployee(Request $request)
//    {
//        //get search term and request details
//        $searchTerm = $request->input('term');
//        $campus =$this->academicHelper->getCampus();
//        $institute = $this->academicHelper->getInstitute();
//
//        $allEmployee =EmployeeInformation::where(['campus'=>$campus,'institute'=>$institute])->where('first_name', 'like', "%" . $searchTerm . "%")->orwhere('middle_name', 'like', "%" . $searchTerm . "%")->orwhere('last_name', 'like', "%" . $searchTerm . "%")->get();
//
//        // checking
//        if ($allEmployee) {
//            $data = array();
//            foreach ($allEmployee as $employee) {
//                // store into data set
//                $data[] = array(
//                    'id' => $employee->id,
//                    'name' => $employee->first_name . " " . $employee->middle_name . " " . $employee->last_name .' - '.$employee->username,
//                    'name_id' => $employee->first_name . " " . $employee->middle_name . " " . $employee->last_name,
//                );
//            }
//
//            return json_encode($data);
//        }
//
//    }
//

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




}
