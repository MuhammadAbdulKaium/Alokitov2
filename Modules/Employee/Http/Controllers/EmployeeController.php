<?php

namespace Modules\Employee\Http\Controllers;

use App\Address;
use App\Content;
use App\RoleUser;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Employee\Entities\AttendanceDevice;
use Modules\Employee\Entities\EmployeeAttachment;
use Modules\Employee\Entities\EmployeeDepartment;
use Modules\Employee\Entities\EmployeeDesignation;
use Modules\Employee\Entities\EmployeeDocument;
use Modules\Employee\Entities\EmployeeInformation;
use App\UserInfo;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Employee\Entities\Imports\EmployeeImport;
use Modules\Setting\Entities\Country;
use Modules\Setting\Entities\Campus;
use Modules\Student\Entities\StudentGuardian;
use Modules\Student\Entities\StudentParent;
use Redirect;
use Session;
use Validator;
use App\Models\Role;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeController extends Controller
{

    private $user;
    private $userInfo;
    private $role;
    private $department;
    private $designation;
    private $employeeInformation;
    private $academicHelper;
    private $country;
    private $campus;


    // constructor
    public function __construct(User $user, Role $role, EmployeeDepartment $department, EmployeeDesignation $designation, EmployeeInformation $employeeInformation, UserInfo $userInfo, AcademicHelper $academicHelper, Country $country, Campus $campus)
    {
        $this->user = $user;
        $this->userInfo = $userInfo;
        $this->role = $role;
        $this->department = $department;
        $this->designation = $designation;
        $this->employeeInformation = $employeeInformation;
        $this->academicHelper = $academicHelper;
        $this->country = $country;
        $this->campus = $campus;
    }

    // employee index function
    public function index()
    {
        // return view
        return view('employee::pages.index');
    }

    public function importEmployee()
    {
        return view('employee::pages.employee-import.employee-import-info');
    }

    public function validateDate($date, $format = 'Y-m-d')
    {
        $d = \DateTime::createFromFormat($format, $date);
        // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
        return $d && $d->format($format) === $date;
    }

    //
    public function uploadEmployee(Request $request)
    {
        $employeeCounter = $request->input('emp_count');
        // checking
        if ($employeeCounter > 0) {
            // Start transaction!
            DB::beginTransaction();
            // loop counter
            $loopCounter = 0;
            // looping
            for ($i = 1; $i <= $employeeCounter; $i++) {
                // receive single employee
                $singleEmployee = $request->$i;

                // employee details
                $campus = $singleEmployee['campus'];
                $role = $singleEmployee['role'];
                $category = $singleEmployee['category'];
                // $title = $singleEmployee['title'];
                $firstName = $singleEmployee['first_name'];
                $middleName = $singleEmployee['middle_name'];
                $lastName = $singleEmployee['last_name'];
                $email = strtolower($singleEmployee['email']);
                $department = $singleEmployee['department'];
                $designation = $singleEmployee['designation'];
                $gender = $singleEmployee['gender'];
                $birthDate = $singleEmployee['birth_date'];
                $joiningDate = $singleEmployee['joining_date'];

                // validating all requested input data
                $validator = Validator::make(['email' => $email], [
                    'email' => 'required|unique:users'
                ]);

                // storing requesting input data
                if ($validator->passes()) {

                    // employee user creation
                    try {
                        $userFullName = $firstName . " " . $middleName . " " . $lastName;
                        // create user profile for student
                        $userProfile = $this->manageUserProfile(0, [
                            'name' => $userFullName,
                            'email' => $email,
                            'password' => bcrypt(123456)
                        ]);
                        // checking user profile
                        if ($userProfile) {
                            $userInfoProfile = new $this->userInfo();
                            // add user details
                            $userInfoProfile->user_id = $userProfile->id;
                            $userInfoProfile->institute_id = $this->academicHelper->getInstitute();
                            $userInfoProfile->campus_id = $campus;
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
                        $employeeInfo = $this->manageEmployeeProfile(0, [
                            'user_id'     => $userProfile->id,
                            //                            'title'        => $title,
                            'first_name'  => $firstName,
                            'middle_name' => $middleName,
                            'last_name'   => $lastName,
                            'alias'      => strtolower($middleName),
                            'gender'      => $gender,
                            'dob'         => date('Y-m-d', strtotime($birthDate)),
                            'doj'         => date('Y-m-d', strtotime($joiningDate)),
                            'department' => $department,
                            'designation'    => $designation,
                            'category' => $category,
                            'email'       => $email,
                            'phone'       => 0,
                            'campus_id'       => $this->academicHelper->getCampus(),
                            'institute_id'       => $this->academicHelper->getInstitute(),
                            //                           'nationality'   => 0,
                            //                        'experience_year' => $request->input('experience_year'),
                            //                        'experience_month' => $request->input('experience_month'),
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

                    // student role assignment
                    try {
                        // set employee role
                        $employeeRoleProfileAssignment =  $this->setEmpRole($role, $userProfile);
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

                    // loop counter
                    $loopCounter = ($loopCounter + 1);
                } else {
                    Session::flash('warning', 'Duplicate email found');
                    // receiving page action
                    return redirect('/employee/import');
                }
            }
            // If we reach here, then
            // data is valid and working.
            // Commit the queries!
            DB::commit();
            // looping checking
            if ($loopCounter == $employeeCounter) {
                Session::flash('success', 'employee list uploaded');
                // return redirect
                return redirect('/employee/manage');
            }
        } else {
            Session::flash('warning', 'Employee List is empty');
            // return redirect
            return redirect()->back();
        }
    }
    //
    public function showImportedEmployeeList(Request $request)
    {
        $users = User::all();
        $data = Excel::toArray(new EmployeeImport(), $request->file('employee_list'));
        //        return $data;
        return view('employee::pages.employee-import.employee-import-list', compact('data', 'users'));
    }
    public function checkImportedEmployeeList(Request $request)
    {
        $array = array();
        $array2 = array();
        $array3 = array();
        $totalRow = array();
        $currentUserEmailArray = array();
        for ($i = 0; $i < count($request->employee_no); $i++) {
            if (isset($array2[$request['employee_no'][$i]])) {
                $array3[$array2[$request['employee_no'][$i]] + 1] = $request['employee_no'][$i];
                $array3[$i + 1] = $request['employee_no'][$i];
            } else {
                $array2[$request['employee_no'][$i]] = $i;
            }
        }
        if (sizeof($array3)) {
            return ['status' => 'inlineDuplicate', 'msg' => 'Inline Duplicated Data', 'inlineUser' => $array3];
        } else {
            for ($i = 0; $i < count($request->employee_no); $i++) {
                $currentUser = User::where('username', $request['employee_no'][$i])->first();
                if ($currentUser) {
                    array_push($array, $currentUser);
                }
            }
            if (sizeof($array)) {
                return ['status' => 'duplicate', 'msg' => 'Duplicated Data', 'currentUser' => $array];
            } else {
                for ($i = 0; $i < count($request->employee_no); $i++) {
                    if ($request['email_login_id'][$i]) {
                        $currentUserEmail = User::where('email', $request['email_login_id'][$i])->first();
                        if ($currentUserEmail) {
                            array_push($currentUserEmailArray, $currentUserEmail);
                        }
                    }
                }
                if (sizeof($currentUserEmailArray)) {
                    return ['status' => 'emailDuplicate', 'msg' => 'Email Duplicated Data', 'duplicateEmail' => $currentUserEmailArray];
                } else {
                    DB::beginTransaction();
                    try {
                        for ($i = 0; $i < count($request['employee_no']); $i++) {
                            //Get Department Value
                            $deptInput = $request['department'][$i];
                            if ($deptInput) {
                                $deptInfo = EmployeeDepartment::where([
                                    'institute_id' => $this->academicHelper->getInstitute(),
                                    'campus_id' => $this->academicHelper->getCampus(),
                                    'name' => $deptInput
                                ])->first();

                                if ($deptInfo) {
                                    $department = $deptInfo->id;
                                } else {
                                    $department = 0;
                                }
                            }
                            //Get Designation Value
                            $desigInput = $request['designation'][$i];
                            if ($desigInput) {
                                $desigInfo = EmployeeDesignation::where([
                                    'institute_id' => $this->academicHelper->getInstitute(),
                                    'campus_id' => $this->academicHelper->getCampus(),
                                    'name' => $desigInput
                                ])->first();
                                if ($desigInfo) {
                                    $designation = $desigInfo->id;
                                } else {
                                    $designation = 0;
                                }
                            }

                            //Get category Value
                            $getCategoryInput = $request['category'][$i];
                            if ($getCategoryInput) {
                                if ($getCategoryInput == 'Teaching') {
                                    $category = 1;
                                } else {
                                    $category = 2;
                                }
                            }

                            $userName = $request['employee_no'][$i];
                            $userEmail = $request['email_login_id'][$i];
                            $checkEmployee = User::where('username', '=', $userName)->first();
                            $checkEmployeeEmail = User::where('email', '=', $userEmail)->first();
                            if (!$checkEmployee) {
                                $empStore = new User();
                                $empStore->name = $request['first_name'][$i] . ' ' . $request['last_name'][$i];
                                $empStore->email = $request['email_login_id'][$i];
                                $empStore->username = $request['employee_no'][$i];
                                $empStore->password = bcrypt(123456);
                                $storeRecordID = $empStore->save();
                                if ($storeRecordID) {
                                    if ($storeRecordID) {
                                        $role_user = new RoleUser();
                                        $role_user->user_id = $empStore->id;
                                        $role_user->role_id  = 5;
                                        $role_user->save();

                                        $user_campus_inst = new UserInfo();
                                        $user_campus_inst->user_id = $empStore->id;
                                        $user_campus_inst->campus_id = $this->academicHelper->getCampus();
                                        $user_campus_inst->institute_id = $this->academicHelper->getInstitute();
                                        $user_campus_inst->save();

                                        // Generating date
                                        $dob = ($this->validateDate($request['date_of_birth'][$i])) ? $request['date_of_birth'][$i] : null;
                                        $doj = ($this->validateDate($request['date_of_joining'][$i])) ? $request['date_of_joining'][$i] : null;
                                        $dor = ($this->validateDate($request['date_of_retirement'][$i])) ? $request['date_of_retirement'][$i] : null;

                                        $employeeInfo = new EmployeeInformation();
                                        $employeeInfo->user_id = $empStore->id;
                                        $employeeInfo->title = 'FM';
                                        $employeeInfo->first_name = $request['first_name'][$i];
                                        $employeeInfo->last_name = $request['last_name'][$i];
                                        $employeeInfo->alias = $request['alias'][$i];
                                        $employeeInfo->gender = $request['gender'][$i];
                                        $employeeInfo->dob = $dob;
                                        $employeeInfo->doj = $doj;
                                        $employeeInfo->dor = $dor;
                                        $employeeInfo->department = $department;
                                        $employeeInfo->designation = $designation;
                                        $employeeInfo->category = $category;
                                        $employeeInfo->email = $request['email_login_id'][$i];
                                        $employeeInfo->phone = $request['phone'][$i];
                                        $employeeInfo->alt_mobile = $request['alternative_mobile'][$i];
                                        $employeeInfo->marital_status = $request['marital_status'][$i];
                                        $employeeInfo->institute_id = $this->academicHelper->getInstitute();
                                        $employeeInfo->campus_id = $this->academicHelper->getCampus();
                                        $employeeInfo->save();


                                        $presentAddress = new Address();
                                        $presentAddress->user_id = $empStore->id;
                                        $presentAddress->type = 'EMPLOYEE_PRESENT_ADDRESS';
                                        $presentAddress->address = $request['present_address'][$i];
                                        $presentAddress->save();

                                        $permanentAddress = new Address();
                                        $permanentAddress->user_id = $empStore->id;
                                        $permanentAddress->type = 'EMPLOYEE_PERMANENT_ADDRESS';
                                        $permanentAddress->address = $request['permanent_address'][$i];
                                        $permanentAddress->save();

                                        $last_qft = new EmployeeDocument();
                                        $last_qft->employee_id = $employeeInfo->id;
                                        $last_qft->document_type = 1;
                                        $last_qft->qualification_type = 3;
                                        $last_qft->document_details = $request['last_academic_qualification'][$i];
                                        $last_qft->created_by = Auth::id();
                                        $last_qft->campus_id = $this->academicHelper->getCampus();
                                        $last_qft->institute_id = $this->academicHelper->getInstitute();

                                        $last_qft->save();

                                        $spcl_qft = new EmployeeDocument();
                                        $spcl_qft->employee_id = $employeeInfo->id;
                                        $spcl_qft->document_type = 1;
                                        $spcl_qft->qualification_type = 2;
                                        $spcl_qft->document_details = $request['special_qualification'][$i];
                                        $spcl_qft->created_by = Auth::id();
                                        $spcl_qft->campus_id = $this->academicHelper->getCampus();
                                        $spcl_qft->institute_id = $this->academicHelper->getInstitute();
                                        $spcl_qft->save();

                                        if ($request['nid_no'][$i]) {
                                            $employeeDocument = new EmployeeDocument();
                                            $employeeDocument->employee_id = $employeeInfo->id;
                                            $employeeDocument->document_type = 3;
                                            $employeeDocument->document_category = 'nid';
                                            $employeeDocument->document_details = $request['nid_no'][$i];
                                            $employeeDocument->created_by = Auth::id();
                                            $employeeDocument->campus_id = $this->academicHelper->getCampus();
                                            $employeeDocument->institute_id = $this->academicHelper->getInstitute();
                                            $employeeDocument->save();
                                        }
                                        if ($request['passport_no'][$i]) {
                                            $employeeDocument = new EmployeeDocument();
                                            $employeeDocument->employee_id = $employeeInfo->id;
                                            $employeeDocument->document_type = 3;
                                            $employeeDocument->document_category = 'passport';
                                            $employeeDocument->document_details = $request['passport_no'][$i];
                                            $employeeDocument->created_by = Auth::id();
                                            $employeeDocument->campus_id = $this->academicHelper->getCampus();
                                            $employeeDocument->institute_id = $this->academicHelper->getInstitute();
                                            $employeeDocument->save();
                                        }

                                        if ($request['birth_certificate_no'][$i]) {
                                            $employeeDocument = new EmployeeDocument();
                                            $employeeDocument->employee_id = $employeeInfo->id;
                                            $employeeDocument->document_type = 3;
                                            $employeeDocument->document_category = 'birth';
                                            $employeeDocument->document_details = $request['birth_certificate_no'][$i];
                                            $employeeDocument->created_by = Auth::id();
                                            $employeeDocument->campus_id = $this->academicHelper->getCampus();
                                            $employeeDocument->institute_id = $this->academicHelper->getInstitute();
                                            $employeeDocument->save();
                                        }

                                        if ($request['tin_no'][$i]) {
                                            $employeeDocument = new EmployeeDocument();
                                            $employeeDocument->employee_id = $employeeInfo->id;
                                            $employeeDocument->document_type = 3;
                                            $employeeDocument->document_category = 'tin';
                                            $employeeDocument->document_details = $request['tin_no'][$i];
                                            $employeeDocument->campus_id = $this->academicHelper->getCampus();
                                            $employeeDocument->institute_id = $this->academicHelper->getInstitute();
                                            $employeeDocument->created_by = Auth::id();
                                            $employeeDocument->save();
                                        }
                                        if ($request['driving_license_no'][$i]) {
                                            $employeeDocument = new EmployeeDocument();
                                            $employeeDocument->employee_id = $employeeInfo->id;
                                            $employeeDocument->document_type = 3;
                                            $employeeDocument->document_category = 'dl';
                                            $employeeDocument->document_details = $request['driving_license_no'][$i];
                                            $employeeDocument->campus_id = $this->academicHelper->getCampus();
                                            $employeeDocument->institute_id = $this->academicHelper->getInstitute();
                                            $employeeDocument->created_by = Auth::id();
                                            $employeeDocument->save();
                                        }

                                        $father_info = new StudentGuardian();
                                        $father_info->type = 1;
                                        $father_info->gender = 1;
                                        $father_info->first_name = $request['fathers_name'][$i];
                                        $father_info_store = $father_info->save();
                                        if ($father_info_store) {
                                            $parent_info = new StudentParent();
                                            $parent_info->gud_id  = $father_info->id;
                                            $parent_info->emp_id  = $employeeInfo->id;
                                            $parent_info->save();
                                        }

                                        $mothers_info = new StudentGuardian();
                                        $mothers_info->type = 0;
                                        $mothers_info->gender = 2;
                                        $mothers_info->first_name = $request['mothers_name'][$i];
                                        $mothers_info_store = $mothers_info->save();
                                        if ($mothers_info_store) {
                                            $parent_info = new StudentParent();
                                            $parent_info->gud_id  = $mothers_info->id;
                                            $parent_info->emp_id  = $employeeInfo->id;
                                            $parent_info->save();
                                        }

                                        // Spouse Date
                                        $spouseDob = ($this->validateDate($request['spouse_date_of_birth'][$i])) ? $request['spouse_date_of_birth'][$i] : null;

                                        $spouse_details = new StudentGuardian();
                                        $spouse_details->type = 6;
                                        $spouse_details->gender = 2;
                                        $spouse_details->first_name = $request['spouse_name'][$i];
                                        $spouse_details->occupation = $request['spouse_occupation'][$i];
                                        $spouse_details->mobile = $request['spouse_mobile'][$i];
                                        $spouse_details->nid_number = $request['spouse_nid'][$i];
                                        $spouse_details->date_of_birth = $spouseDob;
                                        $spouse_details_save = $spouse_details->save();
                                        if ($spouse_details_save) {
                                            $parent_info = new StudentParent();
                                            $parent_info->gud_id  = $spouse_details->id;
                                            $parent_info->emp_id  = $employeeInfo->id;
                                            $parent_info->save();
                                        }

                                        if ($request['child_1_name'][$i]) {
                                            // Child1 Date
                                            $child1Dob = ($this->validateDate($request['child_1_date_of_birth'][$i])) ? $request['child_1_date_of_birth'][$i] : null;

                                            if ($request['child_1_gender'][$i] == 'Male') {
                                                $child_1_gender = 1;
                                            } else {
                                                $child_1_gender = 2;
                                            }

                                            $child1 = new StudentGuardian();
                                            $child1->type = $child_1_gender == 1 ? 7 : 8;
                                            $child1->gender = $child_1_gender;
                                            $child1->first_name = $request['child_1_name'][$i];
                                            $child1->date_of_birth = $child1Dob;
                                            $child1_store = $child1->save();
                                            if ($child1_store) {
                                                $parent_info = new StudentParent();
                                                $parent_info->gud_id  = $child1->id;
                                                $parent_info->emp_id  = $employeeInfo->id;
                                                $parent_info->save();
                                            }
                                        }
                                        if ($request['child_2_name'][$i]) {
                                            // Child2 Date
                                            $child2Dob = ($this->validateDate($request['child_2_date_of_birth'][$i])) ? $request['child_2_date_of_birth'][$i] : null;

                                            if ($request['child_2_gender'][$i] == 'Male') {
                                                $child_2_gender = 1;
                                            } else {
                                                $child_2_gender = 2;
                                            }

                                            $child2 = new StudentGuardian();
                                            $child2->type = $child_2_gender == 1 ? 7 : 8;
                                            $child2->gender = $child_2_gender;
                                            $child2->first_name = $request['child_2_name'][$i];
                                            $child2->date_of_birth = $child2Dob;
                                            $child2_store = $child2->save();
                                            if ($child2_store) {
                                                $parent_info = new StudentParent();
                                                $parent_info->gud_id  = $child2->id;
                                                $parent_info->emp_id  = $employeeInfo->id;
                                                $parent_info->save();
                                            }
                                        }

                                        if ($request['child_3_name'][$i]) {
                                            // Child3 Date
                                            $child3Dob = ($this->validateDate($request['child_3_date_of_birth'][$i])) ? $request['child_3_date_of_birth'][$i] : null;

                                            if ($request['child_3_gender'][$i] == 'Male') {
                                                $child_3_gender = 1;
                                            } else {
                                                $child_3_gender = 2;
                                            }

                                            $child_3 = new StudentGuardian();
                                            $child_3->type = $child_3_gender == 1 ? 7 : 8;
                                            $child_3->gender = $child_3_gender;
                                            $child_3->first_name = $request['child_3_name'][$i];
                                            $child_3->date_of_birth = $child3Dob;
                                            $child3_store = $child_3->save();
                                            if ($child3_store) {
                                                $parent_info = new StudentParent();
                                                $parent_info->gud_id  = $child_3->id;
                                                $parent_info->emp_id  = $employeeInfo->id;
                                                $parent_info->save();
                                            }
                                        }

                                        if ($request['child_4_name'][$i]) {
                                            // Child4 Date
                                            $child4Dob = ($this->validateDate($request['child_4_date_of_birth'][$i])) ? $request['child_4_date_of_birth'][$i] : null;

                                            if ($request['child_4_gender'][$i] == 'Male') {
                                                $child_4_gender = 1;
                                            } else {
                                                $child_4_gender = 2;
                                            }

                                            $child4 = new StudentGuardian();
                                            $child4->type = $child_4_gender == 1 ? 7 : 8;
                                            $child4->gender = $child_4_gender;
                                            $child4->first_name = $request['child_4_name'][$i];
                                            $child4->date_of_birth = $child4Dob;
                                            $child4_store = $child4->save();

                                            if ($child4_store) {
                                                $parent_info = new StudentParent();
                                                $parent_info->gud_id  = $child4->id;
                                                $parent_info->emp_id  = $employeeInfo->id;
                                                $parent_info->save();
                                            }
                                        }
                                    }
                                }
                                if ($storeRecordID) {
                                    array_push($totalRow, $i);
                                }
                            }
                        }
                        if (sizeof($totalRow)) {
                            DB::commit();
                            return ['status' => 'recordSuccessfull', 'msg' => 'Data record Successfully', 'recordData' => $totalRow];
                        }
                    } catch (\Exception $e) {
                        // Rollback
                        DB::rollback();
                        // throw exceptions
                        throw $e;
                        return 420;
                    }
                }
            }
        }



        //
        ////        $data = Excel::toArray(new EmployeeImport(),$request->file('employee_list'));
        ////        return view('employee::pages.employee-import.employee-import-list', compact('data'));
    }


    // create employee function
    public function createEmployee()
    {
        $campusId = $this->academicHelper->getCampus();
        $instituteId = $this->academicHelper->getInstitute();
        // all department list
        $allDepartments = $this->department->where([
            'campus_id' => $campusId, 'institute_id' => $instituteId, 'dept_type' => 0
        ])->orderBy('name', 'ASC')->get();
        // all nationality
        $allNationality = $this->country->orderBy('nationality', 'ASC')->get(['id', 'nationality']);
        // institute all campus list
        $allCampus      = $this->campus->orderBy('name', 'ASC')->where('institute_id', $this->academicHelper->getInstitute())->get();
        // all roles
        $allRole = $this->role->orderBy('name', 'ASC')->whereNotIn('name', ['parent', 'student', 'admin'])->get();

        return view('employee::pages.employee-add', compact('allDepartments', 'allRole', 'allNationality', 'allCampus'));
    }

    // store employee function
    public function storeEmployee(Request $request)
    {
        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'campus'            => 'required',
            'role'            => 'required',
            // 'title'            => 'required',
            'first_name'       => 'required|max:100',
            // 'last_name'        => 'required|max:100',
            // 'alias'            => 'required|max:100',
            'gender'           => 'required',
            'dob'              => 'required',
            //'doj'              => 'required',
            'department'       => 'required|numeric',
            'designation'      => 'required|numeric',
            'category'         => 'required|numeric',
            'email'            => 'required|email|max:100|unique:users',
            // 'phone'            => 'required|max:20',
            // 'marital_status'   => 'required',
            // 'nationality'      => 'required|numeric',
            // 'experience_year'  => 'required|numeric',
            // 'experience_month' => 'required|numeric',
        ]);

        // vaildator checker
        if ($validator->passes()) {

            // Start transaction!
            DB::beginTransaction();

            // student user cration
            try {

                $userFullName = $request->input('first_name') . " " . $request->input('middle_name') . " " . $request->input('last_name');
                // create user profile for student
                // $manageUserProfile = $this->manageUserProfile($userId, $userData);
                $userProfile = $this->manageUserProfile(0, ['name' => $userFullName, 'email' => $request->input('email'), 'password' => bcrypt(123456)]);

                // checking user profile
                if ($userProfile) {
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

                $employeeInfo = $this->manageEmployeeProfile(0, [
                    'user_id'     => $userProfile->id,
                    'title'        => $request->input('title'),
                    'first_name'  => $request->input('first_name'),
                    'middle_name' => $request->input('middle_name'),
                    'last_name'   => $request->input('last_name'),
                    'employee_no'   => $request->input('employee_no'),
                    'alias'      => $request->input('alias'),
                    'gender'      => $request->input('gender'),
                    'dob'         => date('Y-m-d', strtotime($request->input('dob'))),
                    'doj'         => date('Y-m-d', strtotime($request->input('doj'))),
                    'dor'         => date('Y-m-d', strtotime($request->input('dor'))),
                    'department' => $request->input('department'),
                    'designation'    => $request->input('designation'),
                    'category' => $request->input('category'),
                    'email'       => $request->input('email'),
                    'phone'       => $request->input('phone'),
                    'nationality'   => $request->input('nationality'),
                    'experience_year' => $request->input('experience_year'),
                    'experience_month' => $request->input('experience_month'),
                    'position_serial' => $request->input('position_serial'),
                    'campus_id' => $request->input('campus'),
                    'institute_id' => $this->academicHelper->getInstitute(),
                ]);

                // Address Creation
                if ($request->present_address) {
                    Address::create([
                        'user_id' => $userProfile->id,
                        'type' => 'EMPLOYEE_PRESENT_ADDRESS',
                        'address' => $request->present_address
                    ]);
                }
                if ($request->permanent_address) {
                    Address::create([
                        'user_id' => $userProfile->id,
                        'type' => 'EMPLOYEE_PERMANENT_ADDRESS',
                        'address' => $request->permanent_address
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

            // student role assignment
            try {
                // set employee role
                $employeeRoleProfileAssignment =  $this->setEmpRole($request->input('role'), $userProfile);
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
            if ($employeeInfo) {
                Session::flash('success', 'Employee profile created');
                return redirect('/employee/profile/personal/' . $employeeInfo->id);
            } else {
                Session::flash('warning', 'unable to crate employee profile');
                // receiving page action
                return redirect()->back()->withErrors($validator)->withInput();
            }
        } else {
            Session::flash('warning', 'Invalid Information');
            // receiving page action
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    // manage employee
    public function manageEmployee()
    {
        // campus and institute id
        $instituteId = $this->academicHelper->getInstitute();
        // employee departments
        $allDepartments = $this->department->where(['institute_id' => $instituteId, 'dept_type' => 0])->orderBy('name', 'ASC')->get();
        // // all inputs as objects
        // return view
        return view('employee::pages.employee-manage', compact('allDepartments'));
    }
    public function imagePage()
    {
        return view('employee::pages.employee-import.employee-import-image');
    }

    public function imageUpload(Request $request)
    {
        $imageName = "";
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            //            $file_name_timestamp = "ems";
            foreach ($image as $files) {
                $file_name_timestamp = "ems" . $files->getClientOriginalName() . rand(1, 10);
                //Image Info
                $user_id = trim($files->getClientOriginalName(), '.' . $files->getClientOriginalExtension());
                //User Info
                $userID = User::where('username', $user_id)->first();
                $user = $userID['id'];
                //                echo $user;
                //                echo '<br>';
                $destinationPath = 'assets/users/images/';
                $imageName = $file_name_timestamp . "." . $files->getClientOriginalExtension();
                $ext = $files->getClientOriginalExtension();
                $files->move($destinationPath, $imageName);
                $data[] = $imageName;

                //End Image Info

                //Personal Info
                $personalInfo = EmployeeInformation::where('user_id', $user)->first();
                $enrollment = $personalInfo->id;
                //                return $enrollment;
                //
                $campus = $this->academicHelper->getCampus();
                $institute = $this->academicHelper->getInstitute();
                //                //Check Image Exist or not
                $imageAttachmentUpdate = EmployeeAttachment::where('emp_id', $enrollment)->where('doc_type', 'PROFILE_PHOTO')->first();
                echo $imageAttachmentUpdate;

                DB::beginTransaction();
                if ($imageAttachmentUpdate) {
                    try {
                        $contentFind = Content::where('id', $imageAttachmentUpdate->doc_id)->first();
                        $contentFind->name = $imageName;
                        $contentFind->file_name = $imageName;
                        $contentFind->path = $destinationPath;
                        $contentFind->mime = $ext;
                        $content_update = $contentFind->save();
                        //
                        ////                        if($content_update)
                        ////                        {
                        ////                            $photoStore=new CadetPersonalPhoto;
                        ////                            $photoStore->image = $imageName;
                        ////                            $photoStore->date = date('Y-m-d');
                        ////                            $photoStore->cadet_no = $user;
                        ////                            $photoStore->student_id = $enrollment->std_id;
                        ////                            $photoStore->campus_id = $campus;
                        ////                            $photoStore->institute_id = $institute;
                        ////                            $photoStore->academics_year_id=$enrollment->academic_year;
                        ////                            $photoStore->section_id=$enrollment->section;
                        ////                            $photoStore->batch_id= $enrollment->batch;
                        ////                            $photoStorage=$photoStore->save();
                        ////                        }
                        DB::commit();
                    } catch (\Exception $e) {
                        DB::rollback();
                        return redirect()->back($e->getMessage());
                    }
                    //
                    //
                    //
                } else {
                    try {
                        $userDocument = new Content();
                        // storing user document
                        $userDocument->name = $imageName;
                        $userDocument->file_name = $imageName;
                        $userDocument->path = $destinationPath;
                        $userDocument->mime = $ext;
                        $insertDocument = $userDocument->save();
                        //
                        //
                        if ($insertDocument) {
                            $studentAttachment = new EmployeeAttachment();
                            // storing student attachment
                            $studentAttachment->emp_id     = $enrollment;
                            $studentAttachment->doc_id     = $userDocument->id;
                            $studentAttachment->doc_type   = "PROFILE_PHOTO";
                            $studentAttachment->doc_status = 0;
                            // save student attachment profile
                            $attachmentUploaded = $studentAttachment->save();
                        }
                        ////                        if($insertDocument)
                        ////                        {
                        ////                            $photoStore=new CadetPersonalPhoto;
                        ////                            $photoStore->image = $imageName;
                        ////                            $photoStore->date = date('Y-m-d');
                        ////                            $photoStore->cadet_no = $user;
                        ////                            $photoStore->student_id = $enrollment->std_id;
                        ////                            $photoStore->campus_id = $campus;
                        ////                            $photoStore->institute_id = $institute;
                        ////                            $photoStore->academics_year_id=$enrollment->academic_year;
                        ////                            $photoStore->section_id=$enrollment->section;
                        ////                            $photoStore->batch_id= $enrollment->batch;
                        ////                            $photoStorage=$photoStore->save();
                        ////                        }
                        //                        if($insertDocument){
                        //                            // If we reach here, then data is valid and working. Commit the queries!
                        //                            DB::commit();
                    }
                    //
                    //
                    catch (ValidationException $e) {
                        // Rollback and then redirect
                        // back to form with errors
                        DB::rollback();
                        return redirect()->back($e->getMessage());
                    }
                    //
                }
            }
            $imageName = "";
        }
        // return
        Session::flash('success', 'Employee Image Uploaded !!!');
        // receiving page action
        return redirect()->back();
    }

    // manage teacher
    public function manageTeacher()
    {
        // campus and institute id
        $campusId = $this->academicHelper->getCampus();
        $instituteId = $this->academicHelper->getInstitute();

        // employee designations
        $allDesignaitons = $this->designation->where(['institute_id' => $instituteId,])->orderBy('name', 'ASC')->get();
        // employee departments
        $allDepartments = $this->department->where(['institute_id' => $instituteId, 'dept_type' => 0])->orderBy('name', 'ASC')->get();
        // return view
        return view('employee::pages.manage-teacher', compact('allDesignaitons', 'allDepartments'));
    }

    // find teacher
    public function findTeacherList(Request $request)
    {
        $designation = $request->input('designation');
        $department  = $request->input('department');
        $category    = $request->input('category');
        $email       = $request->input('email');
        $empId       = $request->input('emp_id');

        // qry
        $allSearchInputs = array();

        // checking return type
        // status
        $allSearchInputs['status'] = 1;
        // input institute and campus id
        $allSearchInputs['campus_id'] = $this->academicHelper->getCampus();
        $allSearchInputs['institute_id'] = $this->academicHelper->getInstitute();

        // check department
        if ($department) $allSearchInputs['department'] = $department;
        // check designation
        if ($designation) $allSearchInputs['designation'] = $designation;
        // check category
        if ($category) $allSearchInputs['category'] = $category;
        // check email
        if ($email) $allSearchInputs['email'] = $email;
        // check empId
        if ($empId) $allSearchInputs['id'] = $empId;

        // search result
        $allEmployee = $this->employeeInformation->where($allSearchInputs)->orderBy('status', 'DESC')->orderBy('sort_order', 'ASC')->get();
        // return view with variable
        return view('employee::pages.includes.teacher-list', compact('allEmployee', 'allSearchInputs'));
    }

    // search employee
    public function searchEmployee(Request $request)
    {

        $instituteId  = $request->input('institute');
        $campusId = $request->input('campus');
        $designation = $request->input('designation');
        $department  = $request->input('department');
        $category    = $request->input('category');
        $email       = $request->input('email');
        $empId       = $request->input('emp_id');
        // return type
        $returnType = $request->input('return_type', 'view');

        // qry
        $allSearchInputs = array();

        // checking return type
        if ($returnType == "json") {
            // status
            $allSearchInputs['status'] = 1;
            // input institute and campus id
            $allSearchInputs['campus_id'] = $campusId;
            $allSearchInputs['institute_id'] = $instituteId;
        } else {
            // input institute and campus id
            $allSearchInputs['campus_id'] = $this->academicHelper->getCampus();
            $allSearchInputs['institute_id'] = $this->academicHelper->getInstitute();
        }

        // check department
        if ($department) $allSearchInputs['department'] = $department;
        // check designation
        if ($designation) $allSearchInputs['designation'] = $designation;
        // check category
        if (!empty($category) || $category != null) $allSearchInputs['category'] = $category;
        // check email
        if ($email) $allSearchInputs['email'] = $email;
        // check empId
        if ($empId) $allSearchInputs['id'] = $empId;

        // search result
        $allEmployee = $this->employeeInformation->where($allSearchInputs)->orderBy('status', 'DESC')->orderBy('sort_order', 'ASC')->get();
        // checking
        if ($returnType == "json") {
            // return with variables
            return $allEmployee;
        } else {
            // checking
            if ($allEmployee) {
                return view('employee::pages.includes.teacher-list', compact('allEmployee', 'allSearchInputs'));
            } else {
                Session::flash('warning', 'ubable to perform the action');
                // return redirect
                return redirect()->back();
            }
        }
    }


    // employee download excel file

    public function searchEmployeeDownload(Request $request)
    {

        $designation = $request->input('designation');
        $department  = $request->input('department');
        $category    = $request->input('category');
        $email       = $request->input('email');
        $empId       = $request->input('emp_id');

        // qry
        $allSearchInputs = array();

        // status
        $allSearchInputs['status'] = 1;
        // input institute and campus id
        $allSearchInputs['campus_id'] = $this->academicHelper->getCampus();
        $allSearchInputs['institute_id'] = $this->academicHelper->getInstitute();

        // check department
        if ($department) $allSearchInputs['department'] = $department;
        // check designation
        if ($designation) $allSearchInputs['designation'] = $designation;
        // check category
        if ($category) $allSearchInputs['category'] = $category;
        // check email
        if ($email) $allSearchInputs['email'] = $email;
        // check empId
        if ($empId) $allSearchInputs['id'] = $empId;

        // search result
        $allEmployee = EmployeeInformation::where($allSearchInputs)->orderBy('status', 'DESC')->get();
        // checking
        // checking
        if ($allEmployee) {

            view()->share(compact('allEmployee'));
            //generate excel
            Excel::create('Employee List', function ($excel) {
                $excel->sheet('Employee List', function ($sheet) {
                    // Font family
                    $sheet->setFontFamily('Comic Sans MS');
                    // Set font with ->setStyle()
                    $sheet->setStyle(array('font' => array('name' => 'Calibri', 'size' => 12)));
                    // cell formatting
                    $sheet->setAutoSize(true);
                    // Set all margins
                    $sheet->setPageMargin(0.25);
                    // mergeCell
                    // $sheet->mergeCells(['C3:D1', 'E1:H1']);

                    $sheet->loadView('employee::reports.employee_list');
                });
            })->download('xlsx');
        } else {
            Session::flash('warning', 'ubable to perform the action');
            // return redirect
            return redirect()->back();
        }
    }




    // create or update user profile
    public function manageUserProfile($userId, $userData)
    {
        // userId checking
        if ($userId > 0) {
            $userProfile = $this->user->findOrFail($userId)->update($userData);
        } else {
            $userProfile = $this->user->create($userData);
        }

        // userProfile checking
        if ($userProfile) {
            return $userProfile;
        } else {
            return false;
        }
    }

    // create or update employee profile
    public function manageEmployeeProfile($empId, $empData)
    {
        // $empId checking
        if ($empId > 0) {
            $employeeProfile = $this->employeeInformation->findOrFail($empId)->update($empId);
        } else {
            $employeeProfile = $this->employeeInformation->create($empData);
        }

        // employeeProfile checking
        if ($employeeProfile) {
            return $employeeProfile;
        } else {
            return false;
        }
    }

    // set employee role using user profile
    public function setEmpRole($roleName, $userProfile)
    {
        // roleProfile
        $employeeRoleProfile = $this->role->where('id', $roleName)->first();
        // assigning student role to this user
        return $userProfile->attachRole($employeeRoleProfile);
    }

    //check Employee Email Exists or not
    public function checkEmployeeEmail(Request $request)
    {
        $emails = $request->input('form_data');
        $emails = json_decode($emails);
        $result_same_sheet = array();
        $result = array();

        for ($i = 1; $i < count($emails); $i++) {

            $user = User::where('email', '=', $emails[$i])->get();
            if (empty($result_same_sheet[$emails[$i]])) {

                if ($user->count() > 0) {
                    $result[$i] = 0;
                } else {
                    $result_same_sheet[$emails[$i]] = 1;
                    $result[$i] = 1;
                }
            } else {
                $result[$i] = 0;
            }
        }
        return $result;
    }

    // create or update user profile
    public function updateWebPosition(Request $request)
    {
        // employee id
        $empId = $request->input('emp_id');
        // employee id
        $position = $request->input('sort_order');
        // checking
        if (!empty($empId) and $empId > 0 and !empty($position) and $position > 0) {
            // employee profile
            $employeeProfile = $this->employeeInformation->find($empId);
            // update web position
            $employeeProfile->sort_order = (int)$position;
            // checking
            if ($employeeProfile->save()) {
                // teacher list
                return 'success';
            } else {
                return 'failed';
            }
        } else {
            return 'failed';
        }
    }


    public function  getAllEmployId()
    {
        //        return "test";
        $attendaceDevice = AttendanceDevice::where('institute_id', 13)->get();
        return $registerEmplyeeId = $attendaceDevice->pluck('registration_id');

        $emplyeeList = EmployeeInformation::wherenotin('id', $registerEmplyeeId)->where('institute_id', 13)->get();
        return $ids =  $emplyeeList->pluck('id');
    }

    //change emplyee status
    public function changeEmployeeStatus($empID)
    {
        $employeeProfile = $this->employeeInformation->find($empID);
        if ($employeeProfile->status == 1) {
            $employeeProfile->status = 0;
        } else {
            $employeeProfile->status = 1;
        }
        $employeeProfile->save();
        return redirect()->back();
    }
}
