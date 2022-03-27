<?php

namespace Modules\Employee\Http\Controllers;


use App\Address;
use App\Http\Controllers\Helpers\AcademicHelper;
use App\Models\Role;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Employee\Entities\EmployeeDepartment;
use Modules\Employee\Entities\EmployeeDesignation;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\Employee\Entities\EmployeeInformationHistory;
use Modules\Setting\Entities\Country;

class EmployeeBulkEditController extends Controller
{
    private $role;
    private $department;
    private $designation;
    private $employeeInformation;
    private $academicHelper;
    private $employeeInformationHistory;
    public function __construct(EmployeeInformationHistory $employeeInformationHistory, Role $role, EmployeeDepartment $department, EmployeeDesignation $designation, EmployeeInformation $employeeInformation, AcademicHelper $academicHelper)
    {
        $this->employeeInformationHistory = $employeeInformationHistory;
        $this->role = $role;
        $this->department = $department;
        $this->designation = $designation;
        $this->employeeInformation = $employeeInformation;
        $this->academicHelper = $academicHelper;
    }
    public function index()
    {
        $employeeRole = $this->role->orderBy('name', 'ASC')->get();
        $allDepartment = $this->department->orderBy('name', 'ASC')->get();
        $allDesignation = $this->designation->orderBy('name', 'ASC')->get();
        return view('employee::pages.employee-bulk-edit.employee-bulk-edit', compact('employeeRole', 'allDepartment', 'allDesignation'));
    }

    public function employeeSearch(Request $request)
    {

        $designation = $request->input('designation');
        $department  = $request->input('department');
        $category    = $request->input('category');

        $allSearchInputs = array();
        if ($request->showNull) {

            $selectForms = array();
            $searchNullValue = [];
            $searchEmptyValue = [];
            //    return $request->selectForm ;
            foreach ($request->selectForm as $form) {
                if ($form == 'department') {
                    array_push($selectForms, 'department');
                } else if ($form == 'designation') {
                    array_push($selectForms, 'designation');
                }
            }
            foreach ($selectForms as $form) {
                if ($form == 'department') {
                    $searchNullValue['department'] = null;
                    $searchEmptyValue['department'] = 0;
                }
                if ($form == 'designation') {
                    $searchNullValue['designation'] = null;
                    $searchEmptyValue['designation'] = 0;
                }
            }
            $nullValueUserId = $this->employeeInformation->where($searchNullValue)->where([
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ])->pluck('user_id')->toArray();
            $emptyVlueUserId = $this->employeeInformation->where($searchEmptyValue)->where([
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ])->pluck('user_id')->toArray();
            if (sizeof($nullValueUserId) > 0 && sizeof($emptyVlueUserId) > 0) {
                $arrayMerge = array_merge($nullValueUserId, $emptyVlueUserId);
            } else if (sizeof($nullValueUserId) > 0) {
                $arrayMerge = $nullValueUserId;
            } else if (sizeof($emptyVlueUserId) > 0) {
                $arrayMerge = $emptyVlueUserId;
            } else {
                $arrayMerge = [];
            }

            // return $searchNullValue;
            $allEmployee = $this->employeeInformation->with('singleUser', 'getEmployeAddress')
                ->where([
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                ])->whereIn('user_id', $arrayMerge)->orderByRaw('LENGTH(position_serial) asc')->orderBy('position_serial', 'ASC')->get();
        } else {

            $allSearchInputs['campus_id'] = $this->academicHelper->getCampus();
            $allSearchInputs['institute_id'] = $this->academicHelper->getInstitute();

            // check department
            if ($department) $allSearchInputs['department'] = $department;
            // check designation
            if ($designation) $allSearchInputs['designation'] = $designation;
            // check category
            if (!empty($category) || $category != null) $allSearchInputs['category'] = $category;


            // search result
            $allEmployee = $this->employeeInformation->with('singleUser', 'getEmployeAddress')->where($allSearchInputs)->orderByRaw('LENGTH(position_serial) asc')->orderBy('position_serial', 'ASC')->get();
        }

        $allRole = $this->role->orderBy('name', 'ASC')->whereNotIn('name', ['parent', 'student', 'admin'])->get();
        $selectForm = $request->selectForm;
        $allDepartment = $this->department->orderBy('name', 'ASC')->get();
        $allDesignation = $this->designation->orderBy('name', 'ASC')->get();
        $allNationality = Country::all();
        return view('employee::pages.employee-bulk-edit.employee-bulk-edit-form', compact('allEmployee', 'allRole', 'allDepartment', 'allDesignation', 'allNationality', 'selectForm'));
    }

    public function employeEdit(Request $request)
    {
        // return $this->employeeInformationHistory;
        if ($request->drag == 1 || isset($request->employee_id)) {
            if ($request->drag == 1) {
                DB::beginTransaction();
                try {
                    foreach ($request->position_serial as $id => $value) {

                        $employeeInfo = $this->employeeInformation->where([
                            'id' => $id,
                            'institute_id' => $this->academicHelper->getInstitute(),
                            'campus_id' => $this->academicHelper->getCampus(),
                        ])->first();
                        $old_value = (int) $employeeInfo->position_serial;
                        $new_value = $value;
                        if ($old_value != $new_value) {
                            if (empty($old_value)) {
                                $this->employeeInformationHistory->create([
                                    'user_id' => $employeeInfo->user_id,
                                    'employee_id' => $employeeInfo->id,
                                    'operation' => "CREATE",
                                    'value_type' => "position_serial",
                                    'new_value' => $new_value,
                                    'institute_id' =>  $this->academicHelper->getInstitute(),
                                    'campus_id' => $this->academicHelper->getCampus(),
                                    'created_by' => Auth::user()->id
                                ]);
                            } else {
                                $this->employeeInformationHistory->create([
                                    'user_id' => $employeeInfo->user_id,
                                    'employee_id' => $employeeInfo->id,
                                    'operation' => "UPDATE",
                                    'value_type' => "position_serial",
                                    'old_value' => $old_value,
                                    'new_value' => $new_value,
                                    'institute_id' =>  $this->academicHelper->getInstitute(),
                                    'campus_id' => $this->academicHelper->getCampus(),
                                    'updated_by' => Auth::user()->id
                                ]);
                            }
                        }
                        $employeeInfo->update([
                            'position_serial' => $value
                        ]);
                    }

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                }
            }
            if (isset($request->employee_id)) {
                DB::beginTransaction();
                try {
                    foreach ($request->employee_id as $id => $value) {

                        $employeeInf = $this->employeeInformation->with('singleUser.roles')->where([
                            'id' => $id,
                            'institute_id' => $this->academicHelper->getInstitute(),
                            'campus_id' => $this->academicHelper->getCampus(),
                        ])->first();


                        $presentAddress = Address::where(['user_id' => $employeeInf->user_id, 'type' => 'EMPLOYEE_PRESENT_ADDRESS'])->first();
                        $permanentAddress = Address::where(['user_id' => $employeeInf->user_id, 'type' => 'EMPLOYEE_PERMANENT_ADDRESS'])->first();

                        $inputField = array('userName', 'role', 'title', 'first_name', 'middle_name', 'present_address', 'permanent_address', 'last_name', 'employee_no', 'alias', 'gender', 'dob', 'doj', 'dor', 'department', 'designation', 'category', 'email', 'phone', 'alt_mobile', 'religion', 'blood_group', 'birth_place', 'marital_status', 'nationality', 'experience_year', 'experience_month');

                        if (isset($request->selectForms)) {
                            // return "ache";
                            $inputField =  $request->selectForms;
                        }

                        $alias = isset($request->alias) ? $request->alias[$id] : $employeeInf->alias;
                        $alt_mobile = isset($request->alt_mobile) ? $request->alt_mobile[$id] : $employeeInf->alt_mobile;
                        $birth_place = isset($request->birth_place) ? $request->birth_place[$id] : $employeeInf->birth_place;
                        $blood_group = isset($request->blood_group) ? $request->blood_group[$id] : $employeeInf->blood_group;
                        $category = isset($request->category) ? $request->category[$id] : $employeeInf->category;
                        $department = isset($request->department) ? $request->department[$id] : $employeeInf->department;
                        $designation = isset($request->designation) ? $request->designation[$id] : $employeeInf->designation;
                        $dob = isset($request->dob) ? $request->dob[$id] : $employeeInf->dob;
                        $doj = isset($request->doj) ? $request->doj[$id] : $employeeInf->doj;
                        $dor = isset($request->dor) ? $request->dor[$id] : $employeeInf->dor;
                        $email = isset($request->email) ? $request->email[$id] : $employeeInf->email;
                        $employee_no = isset($request->employee_no) ? $request->employee_no[$id] : $employeeInf->employee_no;
                        $experience_month = isset($request->experience_month) ? $request->experience_month[$id] : $employeeInf->experience_month;
                        $experience_year = isset($request->experience_year) ? $request->experience_year[$id] : $employeeInf->experience_year;
                        $first_name = isset($request->first_name) ? $request->first_name[$id] : $employeeInf->first_name;
                        $gender = isset($request->gender) ? $request->gender[$id] : $employeeInf->gender;
                        $last_name = isset($request->last_name) ? $request->last_name[$id] : $employeeInf->last_name;
                        $marital_status = isset($request->marital_status) ? $request->marital_status[$id] : $employeeInf->marital_status;
                        $middle_name = isset($request->middle_name) ? $request->middle_name[$id] : $employeeInf->middle_name;
                        $nationality = isset($request->nationality) ? $request->nationality[$id] : $employeeInf->nationality;
                        $phone = isset($request->phone) ? $request->phone[$id] : $employeeInf->phone;
                        $position_serial = isset($request->position_serial) ? $request->position_serial[$id] : $employeeInf->position_serial;
                        $religion = isset($request->religion) ? (int) $request->religion[$id] : $employeeInf->religion;
                        $title = isset($request->title) ? $request->title[$id] : $employeeInf->title;

                        $present_address = "";
                        if (isset($request->present_address)) {
                            $present_address = $request->present_address[$id];
                        } else if ($presentAddress) {
                            $present_address = $presentAddress->address;
                        } else {
                            $present_address = "";
                        }
                        $permanent_address = "";
                        if (isset($request->permanent_address)) {
                            $permanent_address = $request->permanent_address[$id];
                        } else if ($permanentAddress) {
                            $permanent_address = $permanentAddress->address;
                        } else {
                            $permanent_address = "";
                        }

                        $employeeInf->user()->roles()->detach();

                        if (isset($request->role[$id])) {
                            $employeeRoleProfile = $this->role->where('id', $request->role[$id])->first();
                            $employeeInf->user()->attachRole($employeeRoleProfile);
                        }
                        foreach ($inputField as $key => $value) {
                            // return 
                            $new_value = ${"new_" . $value} = isset($request->$value) ? $request->$value[$id] : "";

                            if ($value == "present_address") {
                                $old_value = ${"old_" . $value} = ($presentAddress) ? $presentAddress->address : "";
                            } elseif ($value == "permanent_address") {
                                $old_value = ${"old_" . $value} = ($permanentAddress) ? $permanentAddress->address : "";
                            } elseif ($value == "role") {
                                $old_value = ${"old_" . $value} =  isset($employeeInf->singleUser->roles[0]) ? $employeeInf->singleUser->roles[0]->id : null;
                            } else {
                                $old_value = ${"old_" . $value} = $employeeInf->$value;
                            }

                            if ($old_value != $new_value) {
                                if (empty($new_value)) {
                                    if (empty($new_value) != empty($old_value)) {
                                        $this->employeeInformationHistory->create([
                                            'user_id' => $employeeInf->user_id,
                                            'employee_id' => $employeeInf->id,
                                            'operation' => "DELETE",
                                            'value_type' => $value,
                                            'old_value' => $old_value,
                                            'new_value' => $new_value,
                                            'institute_id' =>  $this->academicHelper->getInstitute(),
                                            'campus_id' => $this->academicHelper->getCampus(),
                                            'deleted_by' => Auth::user()->id
                                        ]);
                                    }
                                } elseif (empty($old_value)) {
                                    $this->employeeInformationHistory->create([
                                        'user_id' => $employeeInf->user_id,
                                        'employee_id' => $employeeInf->id,
                                        'operation' => "CREATE",
                                        'value_type' => $value,
                                        'new_value' => $new_value,
                                        'institute_id' =>  $this->academicHelper->getInstitute(),
                                        'campus_id' => $this->academicHelper->getCampus(),
                                        'created_by' => Auth::user()->id
                                    ]);
                                } else {
                                    $this->employeeInformationHistory->create([
                                        'user_id' => $employeeInf->user_id,
                                        'employee_id' => $employeeInf->id,
                                        'operation' => "UPDATE",
                                        'value_type' => $value,
                                        'old_value' => $old_value,
                                        'new_value' => $new_value,
                                        'institute_id' =>  $this->academicHelper->getInstitute(),
                                        'campus_id' => $this->academicHelper->getCampus(),
                                        'updated_by' => Auth::user()->id
                                    ]);
                                }
                            }
                        }
                        $employeeInf->update([
                            'title' => $title,
                            'first_name' => $first_name,
                            'middle_name' => $middle_name,
                            'last_name' => $last_name,
                            'alias' => $alias,
                            'alt_mobile' => $alt_mobile,
                            'birth_place' => $birth_place,
                            'blood_group' => $blood_group,
                            'category' => $category,
                            'department' => $department,
                            'designation' => $designation,
                            'dob' => $dob,
                            'doj' => $doj,
                            'dor' => $dor,
                            'email' => $email,
                            'employee_no' => $employee_no,
                            'experience_month' => $experience_month,
                            'experience_year' => $experience_year,
                            'gender' => $gender,
                            'nationality' => $nationality,
                            'marital_status' => $marital_status,
                            'phone' => $phone,
                            'religion' => $religion,
                            'updated_at' => Carbon::now(),
                        ]);
                        if ($presentAddress) {
                            $presentAddress->update(['address' => $present_address]);
                        } else {
                            Address::create([
                                'user_id' => $employeeInf->user_id,
                                'type' => 'EMPLOYEE_PRESENT_ADDRESS',
                                'address' => $present_address,
                            ]);
                        }
                        if ($permanentAddress) {
                            $permanentAddress->update(['address' => $permanent_address]);
                        } else {
                            Address::create([
                                'user_id' => $employeeInf->user_id,
                                'type' => 'EMPLOYEE_PERMANENT_ADDRESS',
                                'address' => $permanent_address,
                            ]);
                        }
                    }

                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();
                }
            }
        } else {
            return response()->json([
                'errors' => "Please Min 1 row Select !"
            ]);
        }
        return back();
    }
}
