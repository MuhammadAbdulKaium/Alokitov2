<?php

namespace Modules\Employee\Http\Controllers;


use App\Address;
use App\Http\Controllers\Helpers\AcademicHelper;
use App\Models\Role;
use App\RoleUser;
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
use App;
use Modules\Setting\Entities\Institute;

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
        $allDepartment = $this->department->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->orderBy('name', 'ASC')->get();
        $allDesignation = $this->designation->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->orderBy('name', 'ASC')->get();
        return view('employee::pages.employee-bulk-edit.employee-bulk-edit', compact('employeeRole', 'allDepartment', 'allDesignation'));
    }

    public function employeeSearch(Request $request)
    {
        // return $request->all();

        $designation = $request->designation;
        $department  = $request->department;
        $category    = $request->category;
        $searchInput = [];
        // check department
        if ($department) $searchInput['department'] = $department;
        // check designation
        if ($designation) $searchInput['designation'] = $designation;
        if (!empty($category) || $category != null) $searchInput['category'] = $category;
        $searchInput['campus_id'] = $this->academicHelper->getCampus();
        $searchInput['institute_id'] = $this->academicHelper->getInstitute();

        $allSearchInputs = [];
        if($request->showNull){

            if ($request->selectForm) {
                foreach ($request->selectForm as $form) {
                    if ($form == "role") {
                        $users = User::pluck('id')->toArray();
                        $roleUser = RoleUser::pluck('user_id')->toArray();
                        $userRoleDiff = array_diff($users,$roleUser);
                        $allSearchInputs = array_merge($allSearchInputs, $userRoleDiff);
                    }
                    if ($form == "title") {
                        $title =  $this->employeeInformation->where([
                            'title' => null,
                            'campus_id' => $this->academicHelper->getCampus(),
                            'institute_id' => $this->academicHelper->getInstitute(),
                        ])->pluck('user_id')->toArray();
                        $allSearchInputs = array_merge($allSearchInputs, $title);
                    }
                    if ($form == "first_name") {
                        $first_name =  $this->employeeInformation->where([
                            'first_name' => null,
                            'campus_id' => $this->academicHelper->getCampus(),
                            'institute_id' => $this->academicHelper->getInstitute(),
                        ])->pluck('user_id')->toArray();
                        $allSearchInputs = array_merge($allSearchInputs, $first_name);
                    }
                    if ($form == "middle_name") {
                        $middle_name =  $this->employeeInformation->where([
                            'middle_name' => null,
                            'campus_id' => $this->academicHelper->getCampus(),
                            'institute_id' => $this->academicHelper->getInstitute(),
                        ])->pluck('user_id')->toArray();
                        $allSearchInputs = array_merge($allSearchInputs, $middle_name);
                    }
                    if ($form == "last_name") {
                        $last_name =  $this->employeeInformation->where([
                            'last_name' => null,
                            'campus_id' => $this->academicHelper->getCampus(),
                            'institute_id' => $this->academicHelper->getInstitute(),
                        ])->pluck('user_id')->toArray();
                        $allSearchInputs = array_merge($allSearchInputs, $last_name);
                    }
                    if ($form == "employee_no") {
                        $employee_no =  $this->employeeInformation->where([
                            'employee_no' => null,
                            'campus_id' => $this->academicHelper->getCampus(),
                            'institute_id' => $this->academicHelper->getInstitute(),
                        ])->pluck('user_id')->toArray();
                        $allSearchInputs = array_merge($allSearchInputs, $employee_no);
                    }
                    if ($form == "position_serial") {
                        $position_serial =  $this->employeeInformation->where([
                            'position_serial' => null,
                            'campus_id' => $this->academicHelper->getCampus(),
                            'institute_id' => $this->academicHelper->getInstitute(),
                        ])->pluck('user_id')->toArray();
                        $allSearchInputs = array_merge($allSearchInputs, $position_serial);
                    }
                    if ($form == "alias") {
                        $alias =  $this->employeeInformation->where([
                            'alias' => null,
                            'campus_id' => $this->academicHelper->getCampus(),
                            'institute_id' => $this->academicHelper->getInstitute(),
                        ])->pluck('user_id')->toArray();
                        $allSearchInputs = array_merge($allSearchInputs, $alias);
                    }
                    if ($form == "gender") {
                        $gender =  $this->employeeInformation->where([
                            'gender' => null,
                            'campus_id' => $this->academicHelper->getCampus(),
                            'institute_id' => $this->academicHelper->getInstitute(),
                        ])->pluck('user_id')->toArray();
                        $allSearchInputs = array_merge($allSearchInputs, $gender);
                    }
                    if ($form == "dob") {
                        $dob =  $this->employeeInformation->where([
                            'dob' => null,
                            'campus_id' => $this->academicHelper->getCampus(),
                            'institute_id' => $this->academicHelper->getInstitute(),
                        ])->pluck('user_id')->toArray();
                        $allSearchInputs = array_merge($allSearchInputs, $dob);
                    }
                    if ($form == "doj") {
                        $doj =  $this->employeeInformation->where([
                            'doj' => null,
                            'campus_id' => $this->academicHelper->getCampus(),
                            'institute_id' => $this->academicHelper->getInstitute(),
                        ])->pluck('user_id')->toArray();
                        $allSearchInputs = array_merge($allSearchInputs, $doj);
                    }
                    if ($form == "dor") {
                        $dor =  $this->employeeInformation->where([
                            'dor' => null,
                            'campus_id' => $this->academicHelper->getCampus(),
                            'institute_id' => $this->academicHelper->getInstitute(),
                        ])->pluck('user_id')->toArray();
                        $allSearchInputs = array_merge($allSearchInputs, $dor);
                    }
                    if ($form == "department") {
                        $departmentNull =  $this->employeeInformation->where([
                            'department' => null,
                            'campus_id' => $this->academicHelper->getCampus(),
                            'institute_id' => $this->academicHelper->getInstitute(),
                        ])->pluck('user_id')->toArray();
                        $departmentEmpty =  $this->employeeInformation->where([
                            'department' => 0,
                            'campus_id' => $this->academicHelper->getCampus(),
                            'institute_id' => $this->academicHelper->getInstitute(),
                        ])->pluck('user_id')->toArray();
                        $allSearchInputs = array_merge($allSearchInputs, $departmentNull);
                        $allSearchInputs = array_merge($allSearchInputs, $departmentEmpty);
                    }
                    if ($form == "designation") {
                        $designationNull =  $this->employeeInformation->where([
                            'designation' => null,
                        ])->pluck('user_id')->toArray();
                        $designationEmpty =  $this->employeeInformation->where([
                            'designation' => 0,
                        ])->pluck('user_id')->toArray();
                        $allSearchInputs = array_merge($allSearchInputs, $designationNull);
                        $allSearchInputs = array_merge($allSearchInputs, $designationEmpty);
                    }
                    if ($form == "category") {
                        $categoryId =  $this->employeeInformation->where([
                            'category' => null,
                            'campus_id' => $this->academicHelper->getCampus(),
                            'institute_id' => $this->academicHelper->getInstitute(),
                        ])->pluck('user_id')->toArray();
                        $allSearchInputs = array_merge($allSearchInputs, $categoryId);
                    }
                    if ($form == "email") {
                        $email =  $this->employeeInformation->where([
                            'email' => null,
                            'campus_id' => $this->academicHelper->getCampus(),
                            'institute_id' => $this->academicHelper->getInstitute(),
                        ])->pluck('user_id')->toArray();
                        $allSearchInputs = array_merge($allSearchInputs, $email);
                    }
                    if ($form == "phone") {
                        $phone =  $this->employeeInformation->where([
                            'phone' => null,
                            'campus_id' => $this->academicHelper->getCampus(),
                            'institute_id' => $this->academicHelper->getInstitute(),
                        ])->pluck('user_id')->toArray();
                        $allSearchInputs = array_merge($allSearchInputs, $phone);
                    }
                    if ($form == "alt_mobile") {
                        $alt_mobile =  $this->employeeInformation->where([
                            'alt_mobile' => null,
                            'campus_id' => $this->academicHelper->getCampus(),
                            'institute_id' => $this->academicHelper->getInstitute(),
                        ])->pluck('user_id')->toArray();
                        $allSearchInputs = array_merge($allSearchInputs, $alt_mobile);
                    }
                    if ($form == "religion") {
                        $religion =  $this->employeeInformation->where([
                            'religion' => null,
                            'campus_id' => $this->academicHelper->getCampus(),
                            'institute_id' => $this->academicHelper->getInstitute(),
                        ])->pluck('user_id')->toArray();
                        $religionEmpty =  $this->employeeInformation->where([
                            'religion' => 0,
                            'campus_id' => $this->academicHelper->getCampus(),
                            'institute_id' => $this->academicHelper->getInstitute(),
                        ])->pluck('user_id')->toArray();
                        $allSearchInputs = array_merge($allSearchInputs, $religion);
                        $allSearchInputs = array_merge($allSearchInputs, $religionEmpty);
                    }
                    if ($form == "blood_group") {
                        $blood_group =  $this->employeeInformation->where([
                            'blood_group' => null,
                            'campus_id' => $this->academicHelper->getCampus(),
                            'institute_id' => $this->academicHelper->getInstitute(),
                        ])->pluck('user_id')->toArray();
                        $allSearchInputs = array_merge($allSearchInputs, $blood_group);
                    }
                    if ($form == "birth_place") {
                        $birth_place =  $this->employeeInformation->where([
                            'birth_place' => null,
                            'campus_id' => $this->academicHelper->getCampus(),
                            'institute_id' => $this->academicHelper->getInstitute(),
                        ])->pluck('user_id')->toArray();
                        $allSearchInputs = array_merge($allSearchInputs, $birth_place);
                    }
                    if ($form == "marital_status") {
                        $marital_status =  $this->employeeInformation->where([
                            'marital_status' => null,
                            'campus_id' => $this->academicHelper->getCampus(),
                            'institute_id' => $this->academicHelper->getInstitute(),
                        ])->pluck('user_id')->toArray();
                        $allSearchInputs = array_merge($allSearchInputs, $marital_status);
                    }
                    if ($form == "nationality") {
                        $nationality =  $this->employeeInformation->where([
                            'nationality' => null,
                            'campus_id' => $this->academicHelper->getCampus(),
                            'institute_id' => $this->academicHelper->getInstitute(),
                        ])->pluck('user_id')->toArray();
                        $allSearchInputs = array_merge($allSearchInputs, $nationality);
                    }
                    if ($form == "experience_year") {
                        $experience_year =  $this->employeeInformation->where([
                            'experience_year' => null,
                            'campus_id' => $this->academicHelper->getCampus(),
                            'institute_id' => $this->academicHelper->getInstitute(),
                        ])->pluck('user_id')->toArray();
                        $allSearchInputs = array_merge($allSearchInputs, $experience_year);
                    }
                    if ($form == "experience_month") {
                        $experience_month =  $this->employeeInformation->where([
                            'experience_month' => null,
                            'campus_id' => $this->academicHelper->getCampus(),
                            'institute_id' => $this->academicHelper->getInstitute(),
                        ])->pluck('user_id')->toArray();
                        $allSearchInputs = array_merge($allSearchInputs, $experience_month);
                    }
                    if ($form == "present_address") {
                        $PresentAddress = Address::where(['address' => null, 'type' => 'EMPLOYEE_PRESENT_ADDRESS'])->pluck('user_id')->toArray();
                        $allSearchInputs = array_merge($allSearchInputs, $PresentAddress);
                    }
                    if ($form == "permanent_address") {
                        $PermanentAddress = Address::where(['address' => null, 'type' => 'EMPLOYEE_PERMANENT_ADDRESS'])->pluck('user_id')->toArray();
                        $allSearchInputs = array_merge($allSearchInputs, $PermanentAddress);
                    }
                }
            }
        }
        $allSearchUniqueInputs = array_unique($allSearchInputs);

        if ($request->showNull && $category && $department && $designation) {
            return "Alll";
            if ($allSearchUniqueInputs) {
                $allEmployee = $this->employeeInformation->with('singleUser','singleDesignation','singleDepartment', 'singleUser.singleroleUser.singleRole', 'getEmployeAddress')
                    ->where([
                        'category' => $category,
                        'department' => $department,
                        'designation' => $designation,
                        'campus_id' => $this->academicHelper->getCampus(),
                        'institute_id' => $this->academicHelper->getInstitute(),
                    ])->whereIn('user_id', $allSearchUniqueInputs)->orderByRaw('LENGTH(position_serial) asc')->orderBy('position_serial', 'ASC')->get();
            } else {
                $allEmployee = [];
            }
        } else if ($request->showNull && $category && $department) {
            if ($allSearchUniqueInputs) {
                $allEmployee = $this->employeeInformation->with('singleUser','singleDesignation','singleDepartment', 'singleUser.singleroleUser.singleRole', 'getEmployeAddress')
                    ->where([
                        'category' => $category,
                        'department' => $department,
                        'campus_id' => $this->academicHelper->getCampus(),
                        'institute_id' => $this->academicHelper->getInstitute(),
                    ])->whereIn('user_id', $allSearchUniqueInputs)->orderByRaw('LENGTH(position_serial) asc')->orderBy('position_serial', 'ASC')->get();
            } else {
                $allEmployee = [];
            }
        } else if ($request->showNull && $category) {
            
            if ($allSearchUniqueInputs) {
                $allEmployee = $this->employeeInformation->with('singleUser','singleDesignation','singleDepartment', 'singleUser.singleroleUser.singleRole', 'getEmployeAddress')
                    ->where([
                        'category' => $category,
                        'campus_id' => $this->academicHelper->getCampus(),
                        'institute_id' => $this->academicHelper->getInstitute(),
                    ])->whereIn('user_id', $allSearchUniqueInputs)->orderByRaw('LENGTH(position_serial) asc')->orderBy('position_serial', 'ASC')->get();
            } else {
                $allEmployee = [];
            }
        } else if ($request->showNull) {
            
            if ($allSearchUniqueInputs) {
                $allEmployee = $this->employeeInformation->with('singleUser','singleDesignation','singleDepartment', 'singleUser.singleroleUser.singleRole', 'getEmployeAddress')
                    ->where([
                        'campus_id' => $this->academicHelper->getCampus(),
                        'institute_id' => $this->academicHelper->getInstitute(),
                    ])->whereIn('user_id', $allSearchUniqueInputs)->orderByRaw('LENGTH(position_serial) asc')->orderBy('position_serial', 'ASC')->get();
            } else {
                $allEmployee = [];
            }
        } else {
            $allEmployee = $this->employeeInformation->with('singleUser','singleDesignation','singleDepartment', 'singleUser.singleroleUser.singleRole', 'getEmployeAddress')
                ->where($searchInput)->orderByRaw('LENGTH(position_serial) asc')->orderBy('position_serial', 'ASC')->get();
        }


        $allRole = $this->role->orderBy('name', 'ASC')->whereNotIn('name', ['parent', 'student', 'admin'])->get();
        $selectForm = $request->selectForm;
        $allDepartment = $this->department->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->orderBy('name', 'ASC')->get();
        $allDesignation = $this->designation->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->orderBy('name', 'ASC')->get();
        $allNationality = Country::all();
        // return $allEmployee;
        if($request->search_type == "Print"){
            $user = Auth::user();
             $institute = Institute::findOrFail($this->academicHelper->getInstitute());
             $pdf = App::make('dompdf.wrapper');
             $pdf->getDomPDF()->set_option("enable_php", true);
            $pdf->loadView('employee::pages.employee-bulk-edit.employee-bulk-print', compact('user','institute','allEmployee', 'allRole', 'allDepartment', 'allDesignation', 'allNationality', 'selectForm'))
            ->setPaper('a1', 'landscape');
            return $pdf->stream();
            // return view('employee::pages.employee-bulk-edit.employee-bulk-print', compact('user','institute','allEmployee', 'allRole', 'allDepartment', 'allDesignation', 'allNationality', 'selectForm'));
        }else{
            return view('employee::pages.employee-bulk-edit.employee-bulk-edit-form', compact('allEmployee', 'allRole', 'allDepartment', 'allDesignation', 'allNationality', 'selectForm'));

        }
    }

    public function employeEdit(Request $request)
    {
        // return $this->employeeInformationHistory;


        if ($request->drag == 1 || isset($request->employee_id)) {
            DB::beginTransaction();
            try {
                if ($request->drag == 1) {
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
                }
                if (isset($request->employee_id)) {
                    foreach ($request->employee_id as $id => $value) {

                        $employeeInf = $this->employeeInformation->with('singleUser.roles')->where([
                            'id' => $id,
                            'institute_id' => $this->academicHelper->getInstitute(),
                            'campus_id' => $this->academicHelper->getCampus(),
                        ])->first();


                        $presentAddress = Address::where(['user_id' => $employeeInf->user_id, 'type' => 'EMPLOYEE_PRESENT_ADDRESS'])->first();
                        $permanentAddress = Address::where(['user_id' => $employeeInf->user_id, 'type' => 'EMPLOYEE_PERMANENT_ADDRESS'])->first();

                        $inputField = array('role', 'title', 'first_name', 'middle_name', 'present_address', 'permanent_address', 'last_name', 'employee_no', 'alias', 'gender', 'dob', 'doj', 'dor', 'department', 'designation', 'category', 'email', 'phone', 'alt_mobile', 'religion', 'blood_group', 'birth_place', 'marital_status', 'nationality', 'experience_year', 'experience_month');

                        if (isset($request->selectForms)) {
                            // return "ache";
                            if ($request->selectForms[0] == 'userName') {
                                $inputField =  array_slice($request->selectForms, 1);
                            } else {

                                $inputField =  $request->selectForms;
                            }
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
                        $experience_month = isset($request->experience_month)? $request->experience_month[$id]: $employeeInf->experience_month;
                        $experience_year = isset($request->experience_year)?$request->experience_year[$id]:$employeeInf->experience_year;
                      
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
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
            }
        } else {
            return response()->json([
                'errors' => "Please Min 1 row Select !"
            ]);
        }
        return back();
    }
}
