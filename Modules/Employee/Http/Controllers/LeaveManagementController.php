<?php

namespace Modules\Employee\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Employee\Entities\EmployeeDepartment;
use Modules\Employee\Entities\EmployeeDesignation;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\Employee\Entities\EmployeeLeaveStructure;
use Modules\Employee\Entities\EmployeeLeaveStructureType;
use Modules\Employee\Entities\EmployeeLeaveType;
use Modules\Employee\Entities\EmployeeLeaveEntitlement;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Employee\Entities\EmployeeLeaveApplication;
use Modules\Employee\Entities\EmployeeLeaveHistory;
use Redirect;
use Session;
use Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App;
use Maatwebsite\Excel\Facades\Excel;

class LeaveManagementController extends Controller
{

    private $employeeLeaveType;
    private $employeeInformation;
    private $employeeDepartment;
    private $employeeLeaveStructure;
    private $employeeLeaveStructureType;
    private $leaveEntitlement;
    private $academicHelper;
    private $employeeDesignation;
    private $employeeLeaveApplication;
    private $carbon;
    private $employeeLeaveHistory;

    // constructor
    public function __construct(EmployeeLeaveType $employeeLeaveType, EmployeeLeaveStructure $employeeLeaveStructure, EmployeeLeaveStructureType $employeeLeaveStructureType, EmployeeInformation $employeeInformation, EmployeeDepartment $employeeDepartment, EmployeeLeaveEntitlement $leaveEntitlement, AcademicHelper $academicHelper, EmployeeDesignation $employeeDesignation, EmployeeLeaveApplication $employeeLeaveApplication, Carbon $carbon, EmployeeLeaveHistory $employeeLeaveHistory)
    {
        $this->employeeLeaveType          = $employeeLeaveType;
        $this->employeeInformation        = $employeeInformation;
        $this->employeeDepartment         = $employeeDepartment;
        $this->employeeLeaveStructure     = $employeeLeaveStructure;
        $this->employeeLeaveStructureType = $employeeLeaveStructureType;
        $this->leaveEntitlement           = $leaveEntitlement;
        $this->academicHelper           = $academicHelper;
        $this->employeeDesignation           = $employeeDesignation;
        $this->employeeLeaveApplication           = $employeeLeaveApplication;
        $this->carbon           = $carbon;
        $this->employeeLeaveHistory           = $employeeLeaveHistory;
    }

    /////////////////////  Leave Type /////////////////////

    public function getType()
    {
        $allLeaveTypes = $this->employeeLeaveType->where(['institute_id'=>$this->academicHelper->getInstitute()])->orderBy('name', 'ASC')->get();
        return view('employee::pages.leave-management.leave-type-manage', compact('allLeaveTypes'));
    }

    public function createType()
    {
        // return view with null variable
        return view('employee::pages.leave-management.modals.leave-type')->with('leaveTypeProfile', null);
    }

    public function storeType(Request $request)
    {
        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'name'                        => 'required',
            'details'                     => 'required',
            'closing_cycle'               => 'required',
        ]);

        // storing requesting input data
        if ($validator->passes()) {
            $campusId= $this->academicHelper->getCampus();
            $type = new EmployeeLeaveType();
            $type->name = $request->name;
            $type->details = $request->details;
            $type->closing_cycle = $request->closing_cycle;
            $type->carray_forward = $request->carray_forward == true ? 1 : 0 ;
            $type->max_cf_amount = $request->max_cf_amount;
            $type->leave_encash = $request->leave_encash  == true ? 1 : 0 ;
            $type->salary_type = $request->salary_type;
            $type->percentage = $request->percentage;
            $type->campus_id = $campusId;
            $type->institute_id = $this->academicHelper->getInstitute();

            $saved = $type->save();
            if ($saved) {
                Session::flash('message', 'Success!Data has been saved successfully.');
                return redirect()->back()->with('message', 'Records saved correctly!!!');
            } else {
                Session::flash('message', 'Success!Data has not been saved successfully.');
            }

        }
//            $employeeLeaveTypeProfile = $this->employeeLeaveType->create($request->all());
//            // update profile
//            $employeeLeaveTypeProfile->institute_id = $this->academicHelper->getInstitute();
//            // save leave type profile
//            $employeeLeaveTypeCreated = $employeeLeaveTypeProfile->save();
//            // checking
//            if ($employeeLeaveTypeCreated) {
//                // Success message
//                Session::flash('success', "Leave Type Added");
//                // return to the previous link
//                return redirect()->back();
//            } else {
//                // warning message
//                Session::flash('warning', "Unable to perform the action");
//                // return to the previous link
//                return redirect()->back();
//            }
//        } else {
//            // warning message
//            Session::flash('warning', "invalid Information. Please try with correct Information");
//            // return to the previous link
//            return redirect()->back();
//        }
    }

    public function editType($id)
    {
        // leave type profile
        $leaveTypeProfile = $this->employeeLeaveType->where('id', $id)->first();
        // return view
        return view('employee::pages.leave-management.modals.leave-type', compact('leaveTypeProfile'));
    }

    public function updateType(Request $request, $id)
    {
        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'name'                        => 'required',
            'details'                     => 'required',
            'proportinate_on_joined_date' => 'required',
            'carray_forward'              => 'required',
            'percentage_of_cf'            => 'required',
            'max_cf_amount'               => 'required',
            'cf_availability_period'      => 'required',
        ]);

        // storing requesting input data
        if ($validator->passes()) {
            // employee leave type Profile
            $employeeLeaveTypeProfile = $this->employeeLeaveType->where('id', $id)->first();
            // update employee leave type
            $employeeLeaveTypeUpdated = $employeeLeaveTypeProfile->update($request->all());
            // checking
            if ($employeeLeaveTypeUpdated) {
                // Success message
                Session::flash('success', "Leave Type Updated");
                // return to the previous link
                return redirect()->back();
            } else {
                // warning message
                Session::flash('warning', "Unable to perform the action");
                // return to the previous link
                return redirect()->back();
            }
        } else {
            // warning message
            Session::flash('warning', "invalid Information. Please try with correct Information");
            // return to the previous link
            return redirect()->back();
        }
    }

    public function destroyType($id)
    {
        // employee leave type Profile
        $employeeLeaveTypeProfile = $this->employeeLeaveType->where('id', $id)->first();
        // delete leave type profile
        $employeeLeaveTypeProfileDeleted = $employeeLeaveTypeProfile->delete();
        // checking
        if ($employeeLeaveTypeProfileDeleted) {
            // Success message
            Session::flash('success', "Leave Type Deleted");
            // return to the previous link
            return redirect()->back();
        } else {
            // warning message
            Session::flash('warning', "Unable to perform the action");
            // return to the previous link
            return redirect()->back();
        }
    }

    /////////////////////  Leave Structure /////////////////////

    public function getStructure()
    {
        $allLeaveStructure = $this->employeeLeaveStructure->where(['institute_id'=>$this->academicHelper->getInstitute()])->orderBy('name', 'ASC')->get();
        return view('employee::pages.leave-management.leave-structure-manage', compact('allLeaveStructure'));
    }

    public function createStructure()
    {
        // return view with null variable
        return view('employee::pages.leave-management.modals.leave-structure')->with('leaveStructureProfile', null);
    }

    public function storeStructure(Request $request)
    {
        // return $request->all();
        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'name'       => 'required',
            'start_date' => 'required',
            'end_date'   => 'required',
        ]);

        // storing requesting input data
        if ($validator->passes()) {
            // all input requests
            $structureProfile['name']       = $request->input('name');
            $structureProfile['start_date'] = date('Y-m-d', strtotime($request->input('start_date')));
            $structureProfile['end_date']   = date('Y-m-d', strtotime($request->input('end_date')));
            // create leave structure
            $employeeLeaveStructureProfile = $this->employeeLeaveStructure->create($structureProfile);
            // update profile
            $employeeLeaveStructureProfile->institute_id = $this->academicHelper->getInstitute();
            // save leave type profile
            $employeeLeaveStructureCreated = $employeeLeaveStructureProfile->save();
            // checking
            if ($employeeLeaveStructureCreated) {
                // Success message
                Session::flash('success', "Leave Structure Added");
                // return to the previous link
                return redirect()->back();
            } else {
                // warning message
                Session::flash('warning', "Unable to perform the action");
                // return to the previous link
                return redirect()->back();
            }
        } else {
            // warning message
            Session::flash('warning', "invalid Information. Please try with correct Information");
            // return to the previous link
            return redirect()->back();
        }
    }

    public function editStructure($id)
    {
        // leave type profile
        $leaveStructureProfile = $this->employeeLeaveStructure->where('id', $id)->first();
        // return view
        return view('employee::pages.leave-management.modals.leave-structure', compact('leaveStructureProfile'));
    }

    public function updateStructure(Request $request, $id)
    {
        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'name'       => 'required',
            'start_date' => 'required',
            'end_date'   => 'required',
        ]);

        // storing requesting input data
        if ($validator->passes()) {
            // employee leave type Profile
            $employeeLeaveStructureProfile = $this->employeeLeaveStructure->where('id', $id)->first();
            // receving all input requests
            $structureProfile['name']       = $request->input('name');
            $structureProfile['start_date'] = date('Y-m-d', strtotime($request->input('start_date')));
            $structureProfile['end_date']   = date('Y-m-d', strtotime($request->input('end_date')));
            // update employee leave type
            $employeeLeaveStructureUpdated = $employeeLeaveStructureProfile->update($structureProfile);
            // checking
            if ($employeeLeaveStructureUpdated) {
                // Success message
                Session::flash('success', "Leave Structure Updated");
                // return to the previous link
                return redirect()->back();
            } else {
                // warning message
                Session::flash('warning', "Unable to perform the action");
                // return to the previous link
                return redirect()->back();
            }
        } else {
            // warning message
            Session::flash('warning', "invalid Information. Please try with correct Information");
            // return to the previous link
            return redirect()->back();
        }
    }

    public function destroyStructure($id)
    {
        // employee leave type Profile
        $employeeLeaveStructureProfile = $this->employeeLeaveStructure->where('id', $id)->first();
        // delete leave type profile
        $employeeLeaveStructureProfileDeleted = $employeeLeaveStructureProfile->delete();
        // checking
        if ($employeeLeaveStructureProfileDeleted) {
            // Success message
            Session::flash('success', "Leave Structure Deleted");
            // return to the previous link
            return redirect()->back();
        } else {
            // warning message
            Session::flash('warning', "Unable to perform the action");
            // return to the previous link
            return redirect()->back();
        }
    }

    public function statusStructure($id, $status)
    {
        // employee leave structure Profile
        $employeeLeaveStructureProfile = $this->employeeLeaveStructure->where('id', $id)->first();
        // change leave structure profile
        $employeeLeaveStructureProfile->status = $status;
        // update leave structure profile
        $employeeLeaveStructureUpdated = $employeeLeaveStructureProfile->save();

        // checking
        if ($employeeLeaveStructureUpdated) {
            // Success message
            Session::flash('success', "Leave Structure Updated");
            // return to the previous link
            return redirect()->back();
        } else {
            // warning message
            Session::flash('warning', "Unable to perform the action");
            // return to the previous link
            return redirect()->back();
        }
    }

    ///////////////////// Leave Structure Type /////////////////////

    public function createStructureType($id)
    {
        // all leave structure types
        $leaveStructureTypes = null;
        $strId               = $id;
        // all leave types
        $allLeaveTypes = $this->employeeLeaveType->where(['institute_id'=>$this->academicHelper->getInstitute()])->orderBy('name', 'ASC')->get();
        // return view with varibale
        return view('employee::pages.leave-management.modals.leave-structure-type', compact('allLeaveTypes', 'leaveStructureTypes', 'strId'));
    }

    public function storeStructureType(Request $request)
    {

        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'rows_no'      => 'required',
            'str_id'       => 'required',
            'delete_count' => 'required',
        ]);

        // storing requesting input data
        if ($validator->passes()) {
            // row counter
            $structureId   = $request->input('str_id');
            $rowCounter    = $request->input('rows_no');
            $deleteCounter = $request->input('delete_count');

            //structure type delete counter
            $typeDeleteCounter = 0;
            // now delete from deleted attendacne list
            if ($deleteCounter > 0) {
                // all deleted attendance date list
                $allDeletedTypes = $request->deleteList;
                // single subject
                for ($a = 1; $a <= $deleteCounter; $a++) {
                    $sinleStructureTypeProfile = $this->employeeLeaveStructureType->where('id', $allDeletedTypes['id_' . $a])->first();
                    // delete attendanceProfile
                    $sinleStructureTypeProfileDelete = $sinleStructureTypeProfile->delete();
                    // checking
                    if ($sinleStructureTypeProfileDelete) {
                        // leave type delete counter
                        $typeDeleteCounter = ($typeDeleteCounter + 1);
                    }
                }
            }

            // checking
            if ($rowCounter > 0) {
                $x = 0;
                // storing structure type one by one
                for ($i = 1; $i <= $rowCounter; $i++) {
                    // receiving single structure type
                    $structureType = $request->$i;

                    if ($structureType['row_id'] > 0) {
                        // structureType profile
                        $structureTypeProfile = $this->employeeLeaveStructureType->where('id', $structureType['row_id'])->first();
                        // store all parameters
                        $structureTypeProfile->structure_id = $structureId;
                        $structureTypeProfile->type_id      = $structureType['type_id'];
                        $structureTypeProfile->leave_days   = $structureType['days'];
                        // saving
                        $structureTypeProfileSubmitted = $structureTypeProfile->save();
                    } else {
                        // structureType profile
                        $structureTypeProfile = new $this->employeeLeaveStructureType();
                        // store all parameters
                        $structureTypeProfile->structure_id = $structureId;
                        $structureTypeProfile->type_id      = $structureType['type_id'];
                        $structureTypeProfile->leave_days   = $structureType['days'];
                        // saving
                        $structureTypeProfileSubmitted = $structureTypeProfile->save();
                    }

                    // checking
                    if ($structureTypeProfileSubmitted) {
                        $x = ($x+1);
                    }
                }
                // checking
                if ($x == $rowCounter) {
                    // success message
                    Session::flash('success', "Structure Type Added");
                    // return to the previous link
                    return redirect()->back();
                }
            } else {
                // warning message
                Session::flash('warning', "There is no type to be added");
                // return to the previous link
                return redirect()->back();
            }

        } else {
            // warning message
            Session::flash('warning', "invalid Information. Please try with correct Information");
            // return to the previous link
            return redirect()->back();
        }

    }

    public function editStructureType($id)
    {
        // strId
        $strId = $id;
        // all leave structure types
        $allLeaveStructureTypes = $this->employeeLeaveStructureType->where('structure_id', $id)->get(['id', 'structure_id', 'type_id', 'leave_days']);

        $leaveStructureTypes = array();
        //  looping
        foreach ($allLeaveStructureTypes as $type) {
            // input details for return
            $leaveStructureTypes[] = array(
                'id' => $type->id,
                'type_id' => $type->type_id,
                'type_name' => $type->leaveType()->name,
                'structure_id' => $type->structure_id,
                'leave_days' => $type->leave_days
            );
        }

        // all leave types
        $allLeaveTypes = $this->employeeLeaveType->where(['institute_id'=>$this->academicHelper->getInstitute()])->orderBy('name', 'ASC')->get();
        // return view with variable
        return view('employee::pages.leave-management.modals.leave-structure-type', compact('allLeaveTypes', 'leaveStructureTypes', 'strId'));
    }

    // find leave structure for ajax response
    public function findStructureTypes($id)
    {
        // all leave structure types
        $allLeaveStructureTypes = $this->employeeLeaveStructureType->where('structure_id', $id)->get(['id', 'type_id', 'leave_days']);

        $leaveStructureTypes = array();
        //  looping
        foreach ($allLeaveStructureTypes as $type) {
            $leaveStructureTypes[] = array('id' => $type->id, 'type_id' => $type->type_id, 'type_name' => $type->leaveType()->name, 'leave_days' => $type->leave_days);
        }

        return $leaveStructureTypes;
    }

    ///////////////////// Leave Entitlements /////////////////////

    public function getLeaveEntitlement()
    {
        // institute id
        $instituteId = $this->academicHelper->getInstitute();
        // campus id
        $campusId = $this->academicHelper->getCampus();

        // department list
        $allDepartments = $this->employeeDepartment->where(['institute_id'=>$instituteId, 'dept_type'=>0])->orderBy('name', 'ASC')->get(['id', 'name']);
        // designation list
        $allDesignations = $this->employeeDesignation->where(['institute_id'=>$instituteId])->orderBy('name', 'ASC')->get(['id', 'name']);
        // leave structure list
        $allLeaveStructures = $this->employeeLeaveStructure->where(['institute_id'=>$instituteId])->orderBy('name', 'ASC')->where('status', 1)->get();
        // employee list
        $allEmployeeList = $this->employeeInformation->where(['campus_id'=>$campusId, 'institute_id'=>$instituteId])->orderBy('first_name', 'ASC')->get(['id', 'first_name', 'middle_name', 'last_name']);

        // return view with variables
        return view('employee::pages.leave-management.leave-entitlement-manage', compact('allDepartments', 'allDesignations', 'allLeaveStructures', 'allEmployeeList'));
    }

    // returns leave entitled list according to the request
    public function getLeaveEntitledList(Request $request)
    {

//        return $request->all();
        // all input details
        $department = $request->input('department');
        $designation = $request->input('designation');
        $category = $request->input('category');
        $structure = $request->input('structure');
        $employee = $request->input('employee');

        // qry
        $qry = array();
        $qry2 = array();
        // checking
        if($department) $qry['department'] = $department;
        if($designation) $qry['designation'] = $designation;
        if($category) $qry['category'] = $category;
        if($structure) {if($structure=='custom') { $qry['is_custom'] = 1; }else{ $qry['structure'] = $structure;}};
        if($employee) $qry['employee'] = $employee;
        // input campus and institute id
        $qry['campus_id'] = $this->academicHelper->getCampus();
        $qry['institute_id'] = $this->academicHelper->getInstitute();

        // find employee leave entitlement list
        $employeeEntitlementList = $this->leaveEntitlement->where($qry)->paginate(25);

        // return view with variable
        return view('employee::pages.leave-management.modals.leave-allocation-list', compact('employeeEntitlementList'));
    }

    // create leave allocation
    public function createLeaveAllocation()
    {
        $instituteId = $this->academicHelper->getInstitute();
        $campusId = $this->academicHelper->getCampus();

        // employee info
        $allEmployee = $this->employeeInformation->where(['campus_id'=>$campusId, 'institute_id'=>$instituteId])->orderBy('first_name', 'ASC')->get(['id', 'first_name', 'middle_name', 'last_name']);
        // employee departments
        $allDepartment = $this->employeeDepartment->where(['institute_id'=>$instituteId])->orderBy('name', 'ASC')->get(['id', 'name']);
        // all leave structures
        $allLeaveStructures = $this->employeeLeaveStructure->where(['institute_id'=>$instituteId])->orderBy('name', 'ASC')->where('status', 1)->get();
        // return view with variable
        return view('employee::pages.leave-management.modals.leave-allocation', compact('allLeaveStructures', 'allEmployee', 'allDepartment'));
    }

    // store leave allocation
    public function storeLeaveAllocation(Request $request)
    {
        // return $request->all();
        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'category'  => 'required',
            'structure' => 'required',
        ]);

        // storing requesting input data
        if ($validator->passes()) {
            $categoryId   = $request->input('category');
            $employeeId   = $request->input('employee');
            $departmentId = $request->input('department');
            $structureId  = $request->input('structure');
            $customId     = $request->input('custom');

            if ($customId == 1) {
                $rowCounter = $request->input('row_counter');
                // structureProfile
                $oldStructureProfile = $this->employeeLeaveStructure->where('id', $structureId)->first();
                // extendCount for this institute and campus
                $extendCount = (int)$oldStructureProfile->childCount();
                // new StructureProfile
                $newStructureProfile = new $this->employeeLeaveStructure();
                // store inputs
                $newStructureProfile->name       = "Extended-" . ($extendCount+1);
                $newStructureProfile->start_date = $oldStructureProfile->start_date;
                $newStructureProfile->end_date   = $oldStructureProfile->end_date;
                $newStructureProfile->parent     = $oldStructureProfile->id;
                $newStructureProfile->campus_id = $this->academicHelper->getCampus();
                $newStructureProfile->institute_id = $this->academicHelper->getInstitute();
                // save newStructureProfile
                $newStructureProfileCreated = $newStructureProfile->save();
                // checking
                if ($newStructureProfileCreated) {
                    // now change the leave structure id
                    $structureId = $newStructureProfile->id;

                    // looping
                    for ($x = 1; $x<= $rowCounter; $x++) {
                        // single TypeProfile
                        $structureType = $request->$x;
                        // structureType profile
                        $structureTypeProfile = new $this->employeeLeaveStructureType();
                        // store all parameters
                        $structureTypeProfile->structure_id = $structureId;
                        $structureTypeProfile->type_id      = $structureType['type_id'];
                        $structureTypeProfile->leave_days   = $structureType['leave_days'];
                        // saving
                        $structureTypeProfileSubmitted = $structureTypeProfile->save();
                    }
                }
            }

            // ready made qry
            $qry = ['campus_id'=>$this->academicHelper->getCampus(), 'institute_id'=>$this->academicHelper->getInstitute()];

            // checking
            if($categoryId=='2'){
                // find employee Profile
                $employee = $this->employeeInformation->find($employeeId);
                // store employee leave allocation
                $leaveEntitlementProfileSubmitted =  $this->employeeLeaveAllocation($categoryId, $structureId, $employee->id, $employee->designation, $employee->department, $customId);
            }elseif ($categoryId=='3'){
                // modifying qry
                $qry['department'] = $departmentId;
                // find employee list using department id
                $employeeList = $this->employeeInformation->where($qry)->get();
                // entitle leave for the employee list
                $leaveEntitlementProfileSubmitted = $this->submitLeaveEntitlement($employeeList, $categoryId, $structureId, $customId);
            }else{
                // find employee list using department id
                $employeeList = $this->employeeInformation->where($qry)->get();
                // entitle leave for the employee list
                $leaveEntitlementProfileSubmitted = $this->submitLeaveEntitlement($employeeList, $categoryId, $structureId, $customId);
            }

            // checking
            if ($leaveEntitlementProfileSubmitted) {
                // Success message
                Session::flash('success', "Leave Successfully allocated");
                // return to the previous link
                return redirect()->back();
            }else{
                // warning message
                Session::flash('warning', "Unable to perform the action");
                // return to the previous link
                return redirect()->back();
            }

        } else {
            // warning message
            Session::flash('warning', "invalid Information. Please try with correct Information");
            // return to the previous link
            return redirect()->back();
        }
    }

    public function applyLeaveApplication()
    {
        $employeeId = Auth::user()->employee()->id;
        // employee profile
        $employeeProfile = $this->employeeInformation->find($employeeId);
        // leave structure types
        $leaveStructureTypes = $employeeProfile->leaveAllocation()->structure()->structureLeaveTypes();
        // return view with variable
        return view('employee::pages.leave-management.modals.leave-application', compact('leaveStructureTypes', 'employeeProfile'));
    }

    public function createLeaveApplication()
    {


        // institute and campus
        $campusId = $this->academicHelper->getCampus();
        $instituteId = $this->academicHelper->getInstitute();
        $allLeaveType=$this->employeeLeaveType->where('institute_id',$instituteId)->where('campus_id',$campusId)->orderBy('name', 'ASC')->get();
        // employee list
        if(!empty(Auth::user()->employee()->id)) {
            $employeeId = Auth::user()->employee()->id;

            $allEmployee = $this->employeeInformation->where(['campus_id'=>$campusId, 'institute_id'=>$instituteId])->Where('id',$employeeId)->orderBy('first_name', 'ASC')->get(['id', 'first_name', 'middle_name', 'last_name']);
        } else {
            $allEmployee = $this->employeeInformation->where(['campus_id' => $campusId, 'institute_id' => $instituteId])->orderBy('first_name', 'ASC')->get(['id', 'first_name', 'middle_name', 'last_name']);

        }  // return view with variable
        return view('employee::pages.leave-management.modals.leave-application', compact('allEmployee','allLeaveType'));
    }

    // find employee structure types
    public function findEmployeeStructureTypes($id)
    {
        // response data
        $data = array();
        // employee profile
        $employeeProfile = $this->employeeInformation->find($id);
        // leave structure types
        $leaveStructureTypes = $employeeProfile->leaveAllocation()->structure()->structureLeaveTypes();
        // looping
        foreach ($leaveStructureTypes as $structureType){
            $leaveType = $structureType->leaveType();
            $data[] = ['id'=>$leaveType->id, 'name'=>$leaveType->name, 'days'=>$structureType->leave_days];
        }
        // return data
        return $data;
    }


    public function storeLeaveApplication(Request $request)
    {
        $userID=Auth::user()->id;
        // employee profile
        $employeeProfile = $this->employeeInformation->find($userID);

        // employee leave application profile
        $leaveApplicationProfile = new  $this->employeeLeaveApplication();
        // input details
        $leaveApplicationProfile->leave_type = $request->input('leave_type');
        $leaveApplicationProfile->leave_reason = $request->input('leave_reason');
        $leaveApplicationProfile->req_start_date = date('Y-m-d', strtotime($request->input('req_start_date')));;
        $leaveApplicationProfile->req_end_date = date('Y-m-d', strtotime($request->input('req_end_date')));;
        $leaveApplicationProfile->applied_date = date('Y-m-d', strtotime($this->carbon->now()));
        $leaveApplicationProfile->req_for_date = $request->input('req_for_date');
        $leaveApplicationProfile->available_day = 20;
        $leaveApplicationProfile->remains = 20;
        $leaveApplicationProfile->status = 1;
        $leaveApplicationProfile->employee_id  = $userID;
        $leaveApplicationProfile->designation = $employeeProfile->designation;
        $leaveApplicationProfile->department = $employeeProfile->department;
        $leaveApplicationProfile->campus_id = $this->academicHelper->getCampus();
        $leaveApplicationProfile->institute_id = $this->academicHelper->getInstitute();
        // save $leaveApplicationProfile
        $leaveApplicationProfileSaved = $leaveApplicationProfile->save();

        // checking
        if ($leaveApplicationProfileSaved) {
            // Success message
            Session::flash('success', "Leave Application Submitted");
            // return to the previous link
            return redirect()->back();
        }else{
            // warning message
            Session::flash('warning', "Unable to perform the action");
            // return to the previous link
            return redirect()->back();
        }
    }


    // manage leave application form employee
    public function leaveApplication()
    {
        if(Auth::user()->hasRole(['super-admin','admin', 'hrms'])){
            // warning message
            Session::flash('warning', "Admin Can not access this page");
            // return to the previous link
            return redirect()->back();
        }else{
            // employee id
            $employeeId = Auth::user()->employee()->id;
            // employee profile
            $employeeProfile = $this->employeeInformation->find($employeeId);
            // leave structure
            $leaveAllocation = $employeeProfile->leaveAllocation();
            // structure
            if($leaveAllocation){
                $leaveStructure = $leaveAllocation->structure();
                // leave structure types
                $leaveStructureTypes = $leaveStructure->structureLeaveTypes();
            }else{
                $leaveStructure = [];
                $leaveStructureTypes = [];
            }

            // employee leave qry
            $empLeaveQry = ['employee'=>$employeeId, 'campus_id'=>$this->academicHelper->getCampus(), 'institute_id'=> $this->academicHelper->getInstitute()];
            // all leave history
            $allLeaveHistory = $this->employeeLeaveHistory->where($empLeaveQry)->get();
            // all leave applications
            $allLeaveApplications = $this->employeeLeaveApplication->where($empLeaveQry)->orderBy('created_at', 'DSC')->limit(500)->get();
            // return view with variables
            return view('employee::pages.leave-management.leave-application', compact('allLeaveApplications', 'leaveStructure', 'leaveStructureTypes', 'allLeaveHistory', 'employeeProfile'));
        }
    }


    // manage leave application for hrm
    public function manageLeaveApplication()
    {
        if(!Auth::user()->hasRole(['super-admin','admin', 'hrms','teacher'])){
            // warning message
            Session::flash('warning', "Attempt to view unauthorized content");
            // return to the previous link
            return redirect()->back();
        }
        elseif(Auth::user()->hasRole(['teacher'])) {
//            return "dd";
            // get employe id
            $empId=Auth::user()->employee()->id;
            $campus = $this->academicHelper->getCampus();
            $institute = $this->academicHelper->getInstitute();
            $allLeaveApplications = $this->employeeLeaveApplication->where(['campus_id'=>$campus, 'institute_id'=>$institute,'employee'=>$empId])->orderBy('created_at', 'DSC')->get();

            return view('employee::pages.leave-management.leave-application-manage', compact('allLeaveApplications'));

        }
        else{
            $campus = $this->academicHelper->getCampus();
            $institute = $this->academicHelper->getInstitute();
            $allLeaveApplications = $this->employeeLeaveApplication->where(['campus_id'=>$campus, 'institute_id'=>$institute])->orderBy('created_at', 'DESC')->get();

            return view('employee::pages.leave-management.leave-application-manage', compact('allLeaveApplications'));
        }
    }

    // show leave application
    public function showLeaveApplication($id)
    {
        // employee leave application profile
        $leaveApplicationProfile = $this->employeeLeaveApplication->find($id);
        // return view with variable
        return view('employee::pages.leave-management.modals.leave-application-manage', compact('leaveApplicationProfile'));
    }

    // manage leave application for hrm
    public function changeLeaveApplicationStatus(Request $request)
    {
        // input details
        $applicationId = $request->input('application_id');
        $applicationStatus = $request->input('application_status');
        $applicationRemark = $request->input('remarks');
        // employee leave application profile
        $leaveApplicationProfile = $this->employeeLeaveApplication->find($applicationId);
        // update application status
        $leaveApplicationProfile->status = $applicationStatus;
        $leaveApplicationProfile->remarks = $applicationRemark;
        // save updated application
        $leaveApplicationProfileUpdated = $leaveApplicationProfile->save();
        // checking and return
        if($leaveApplicationProfileUpdated){
            // checking status type
            if($applicationStatus=='1'){
                // new application history
                $historyProfile = new $this->employeeLeaveHistory();
                // input details
                $historyProfile->application_id = $leaveApplicationProfile->id;
                $historyProfile->leave_type = $leaveApplicationProfile->leave_type;
                $historyProfile->approved_date = date('Y-m-d', strtotime($this->carbon->now()));
                $historyProfile->start_date = $leaveApplicationProfile->start_date;
                $historyProfile->end_date = $leaveApplicationProfile->end_date;
                $historyProfile->approved_leave_days = $leaveApplicationProfile->leave_days;
                $historyProfile->employee = $leaveApplicationProfile->employee;
                $historyProfile->campus_id = $this->academicHelper->getCampus();
                $historyProfile->institute_id = $this->academicHelper->getInstitute();
                // save history profile
                // checking
                if($historyProfile->save()){
                    // return
                    return ['status'=>'success', 'msg'=>'Application Status Changed'];
                }
            }
            // return
            return ['status'=>'success', 'msg'=>'Application Status Changed'];
        }else{
            return ['status'=>'failed', 'msg'=>'Unable to perform the action'];
        }
    }



    //////////////////////////////////   Leave Reports Section ///////////////////////////////////////////

    public function getEmployeeLeave()
    {
        // institute details
        $campus = $this->academicHelper->getCampus();
        $institute = $this->academicHelper->getInstitute();
        // department list
        $allDepartments = $this->employeeDepartment->where(['institute_id'=>$institute, 'dept_type'=>0])->orderBy('name', 'ASC')->get(['id', 'name']);
        // return view with variables
        return view('employee::pages.leave-management.modals.report-leave', compact('allDepartments'));
    }


    // download employee leave report
    public function downloadEmployeeLeave(Request $request)
    {
        // request details
        $category = $request->input('category', null);
        $department = $request->input('department', null);
        $designation = $request->input('designation', null);
        // institute details
        $campus = $this->academicHelper->getCampus();
        $institute = $this->academicHelper->getInstitute();
        // institute profile
        $instituteInfo = $this->academicHelper->getInstituteProfile();
        // employee leave qry
        $qry = ['campus_id'=>$campus, 'institute_id'=> $institute];
        // checking
        if($category) $qry['category'] = $category;
        if($department) $qry['department'] = $department;
        if($designation) $qry['designation'] = $designation;
        // institute list
        $allEmployeeList = $this->employeeInformation->where($qry)->get();
        // generate pdf
        $pdf = App::make('dompdf.wrapper');
        view()->share(compact('allEmployeeList', 'instituteInfo'));
        $pdf->loadView('employee::pages.leave-management.reports.report-employee-leave')->setPaper('a4', 'portrait');
        // stream pdf
        return $pdf->stream();
    }


    //////////////////////////////////   Leave Reports Section ///////////////////////////////////////////








    public function submitLeaveEntitlement($employeeList, $categoryId, $structureId, $customId)
    {
        // loop counter
        $loopCount = 0;
        // looping
        foreach ($employeeList as $employee) {
            // store employee leave allocation
            $leaveEntitlement = $this->employeeLeaveAllocation($categoryId, $structureId, $employee->id, $employee->designation, $employee->department, $customId);
            // checking
            if($leaveEntitlement){
                $loopCount = ($loopCount+1);
            }
        }

        // checking
        if($loopCount == $employeeList->count()){
            return true;
        }else{
            return false;
        }
    }


    public function employeeLeaveAllocation($categoryId, $structureId, $employeeId, $designation, $department, $is_custom)
    {
        // find entitlement profile for the employee
        $leaveEntitlementProfile = $this->leaveEntitlement->where(['employee' => $employeeId])->first();
        // checking
        if ($leaveEntitlementProfile == null) {
            // new leave entitlement profile
            $leaveEntitlementProfile = new $this->leaveEntitlement();
        }
        // input details
        $leaveEntitlementProfile->category = $categoryId;
        $leaveEntitlementProfile->structure = $structureId;
        $leaveEntitlementProfile->employee = $employeeId;
        $leaveEntitlementProfile->designation = $designation;
        $leaveEntitlementProfile->department = $department;
        $leaveEntitlementProfile->is_custom = $is_custom;
        $leaveEntitlementProfile->campus_id = $this->academicHelper->getCampus();
        $leaveEntitlementProfile->institute_id = $this->academicHelper->getInstitute();
        // saving entitlement profile
        $leaveEntitlementProfileSaved = $leaveEntitlementProfile->save();
        // return
        return $leaveEntitlementProfileSaved;
    }

}
