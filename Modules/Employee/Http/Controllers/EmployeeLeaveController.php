<?php

namespace Modules\Employee\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Academics\Entities\AcademicsYear;
use Modules\Employee\Entities\EmployeeDepartment;
use Modules\Employee\Entities\EmployeeDesignation;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\Employee\Entities\EmployeeLeaveApplication;
use Modules\Employee\Entities\EmployeeLeaveAssign;
use Modules\Employee\Entities\EmployeeLeaveType;
use Modules\RoleManagement\Entities\Role;
use Modules\Student\Entities\StudentProfileView;
use Carbon\Carbon;
use Session;

class EmployeeLeaveController extends Controller
{
    private  $academicHelper;
    private  $academicsYear;
    private $studentProfileView;
    private $department;
    private $designation;
    private $employeeInformation;
    private $employeeLeaveType;
    private $employeeLeaveApplication;

    public function __construct(EmployeeLeaveApplication $employeeLeaveApplication,EmployeeLeaveType $employeeLeaveType,AcademicHelper $academicHelper, AcademicsYear $academicsYear, StudentProfileView $studentProfileView, EmployeeDesignation $designation, EmployeeDepartment $department,EmployeeInformation $employeeInformation)
    {
        $this->academicHelper                 = $academicHelper;
        $this->academicsYear                 = $academicsYear;
        $this->studentProfileView                 = $studentProfileView;
        $this->department = $department;
        $this->designation = $designation;
        $this->employeeInformation = $employeeInformation;
        $this->employeeLeaveType = $employeeLeaveType;
        $this->employeeLeaveApplication           = $employeeLeaveApplication;
    }


    public function index()
    {
        return view('employee::pages.leave.leave-type');
    }


    public function add_leave_type()
    {
        return view('employee::pages.leave.modals.leave-type-add');
    }

    public function leave_structure()
    {
        return view('employee::pages.leave.leave-structure');
    }


    public function add_leave_structure()
    {
        return view('employee::pages.leave.modals.leave-structure-add');
    }

    public function leave_entitile()
    {
        return view('employee::pages.leave.leave-entitle');
    }


    public function store(Request $request)
    {
    }


    public function show()
    {
        return view('employee::show');
    }


    public function edit()
    {
        return view('employee::edit');
    }


    public function update(Request $request)
    {
    }


    public function destroy()
    {
    }

    public function LeaveAssign()
    {
        $currentYear=$now = Carbon::now()->year;
        // campus and institute id
        $campusId= $this->academicHelper->getCampus();
        $instituteId= $this->academicHelper->getInstitute();

        // employee designations
        $allDesignaitons = $this->designation->where('institute_id',$instituteId)->where('campus_id',$campusId)->orderBy('name', 'ASC')->get();
        // employee departments
        $allDepartments = $this->department->where(['institute_id'=>$instituteId, 'dept_type'=>0])->orderBy('name', 'ASC')->get();
        // employee Leave Type
        $allLeaveType=$this->employeeLeaveType->where('institute_id',$instituteId)->where('campus_id',$campusId)->orderBy('name', 'ASC')->get();
        return view('employee::pages.leave.user-leave-assign',compact('allDesignaitons','allDepartments','allLeaveType','campusId','currentYear'));
    }

    public function userLeaveAssign()
    {
        $leave_type=EmployeeLeaveType::all();
        return view('employee::pages.leave.modals.user-leave-assign-add',compact('leave_type'));

    }

    public function roleLeaveAssign()
    {
        $roles=Role::all();
        $leave_type=EmployeeLeaveType::all();
        return view('employee::pages.leave.modals.role-leave-assign-add',compact('roles','leave_type'));

    }

    public function getAjaxDepartmentDesignation($id)
    {
        // campus and institute id
        $campusId= $this->academicHelper->getCampus();
        $instituteId= $this->academicHelper->getInstitute();
        $allDesignaitons = $this->designation->where('institute_id',$instituteId)->where('campus_id',$campusId)->where('dept_id',$id)->orderBy('name', 'ASC')->get();
            $data = [];
            if($allDesignaitons->count() > 0) {
                array_push($data, '<option value="">-- Select --</option>');
                foreach ($allDesignaitons as $item) {
                    array_push($data, '<option value="' . $item->id . '" data-point="' . $item->id . '">' . $item->name . '</option>');
                }
            }
        return json_encode($data) ;
    }

    public function searchEmployee(Request $request)
    {

        $dept_id  = $request->input('dept_id');
        $designation_id  = $request->input('designation_id');
        $emp_id  = $request->input('emp_id');
        $emp_name  = $request->input('emp_name');
        $duration= $request ->input('duration');
        $leave_type_id = $request ->input('leave_type_id');
        $leave_process_procedure = $request ->input('leave_process_procedure');
        $leave_year = $request ->input('leave_year');

        $currentYear=$now = Carbon::now()->year;

        $searchData = [];
        $allSearchInputs = array();

        $allSearchInputs['campus_id '] = $this->academicHelper->getCampus();
        $allSearchInputs['institute_id '] = $this->academicHelper->getInstitute();

        // check data
        if ($dept_id) $allSearchInputs['department'] = $dept_id ;
        if ($designation_id) $allSearchInputs['designation'] = $designation_id ;
        if ($emp_id) $allSearchInputs['user_id'] = $emp_id;
        if ($emp_name) $allSearchInputs['first_name'] = $emp_name;

        $searchData=$this->employeeInformation->where($allSearchInputs)->get();
        $stdListView = view('employee::pages.leave.employee-list', compact('searchData','duration','leave_type_id','dept_id','designation_id','leave_process_procedure','currentYear','leave_year'))->render();
        return ['status'=>'success', 'msg'=>'Student List found', 'html'=>$stdListView];
    }

    public function assignSubmitEmployee(Request $request)
    {
        foreach ($request['selectedData'] as $value)
        {
            //return $value['emp'];
            $leaveAssign=new EmployeeLeaveAssign();
            $leaveAssign->emp_id=$value['emp'];
            $leaveAssign->dept_id=$value['dpt'];
            $leaveAssign->designation_id=$value['dsg'];
            $leaveAssign->leave_type_id=$value['leaveType'];
            $leaveAssign->duration= $value['duration'];
            $leaveAssign->leave_process_procedure= $value['leaveProcess'];
            $leaveAssign->inst_id=$this->academicHelper->getInstitute();
            $leaveAssign->campus_id=$this->academicHelper->getCampus();
            $leaveAssign->created_by=Auth::user()->id;
            $leaveAssign->leave_status=1;
            $leave_record=$leaveAssign->save();

            if ($leave_record) {
                Session::flash('message', 'Success!Data has been saved successfully.');
            } else {
                Session::flash('message', 'Success!Data has not been saved successfully.');
            }
        }


    }

    public function AllLeaveAssign()
    {
        // campus and institute id
        $campusId= $this->academicHelper->getCampus();
        $instituteId= $this->academicHelper->getInstitute();

        // employee designations
        $allDesignaitons = $this->designation->where('institute_id',$instituteId)->where('campus_id',$campusId)->orderBy('name', 'ASC')->get();
        // employee departments
        $allDepartments = $this->department->where(['institute_id'=>$instituteId, 'dept_type'=>0])->orderBy('name', 'ASC')->get();
        // employee Leave Type
        $allLeaveType=$this->employeeLeaveType->where('institute_id',$instituteId)->where('campus_id',$campusId)->orderBy('name', 'ASC')->get();
        $allLeaveApplications = $this->employeeLeaveApplication->where(['campus_id'=>$campusId, 'institute_id'=>$instituteId])->get();
        return view('employee::pages.leave.all-leave-application',compact('allDesignaitons','allDepartments','allLeaveType','campusId','allLeaveApplications'));
    }

    public function LeaveEncashment()
    {

        $currentYear=$now = Carbon::now()->year;
        // campus and institute id
        $campusId= $this->academicHelper->getCampus();
        $instituteId= $this->academicHelper->getInstitute();

        // employee designations
        $allDesignaitons = $this->designation->where('institute_id',$instituteId)->where('campus_id',$campusId)->orderBy('name', 'ASC')->get();
        // employee departments
        $allDepartments = $this->department->where(['institute_id'=>$instituteId, 'dept_type'=>0])->orderBy('name', 'ASC')->get();
        // employee Leave Type
        $allLeaveType=$this->employeeLeaveType->where('institute_id',$instituteId)->where('campus_id',$campusId)->orderBy('name', 'ASC')->get();
//        Employee List
        $employeeData=$this->employeeInformation->get();
        return view('employee::pages.leave-management.user-leave-encashment',compact('employeeData','allDesignaitons','allDepartments','allLeaveType','campusId','currentYear'));
//        return view('employee::pages.leave-management.user-leave-encashment')->with('leaveStructureProfile', null);
    }

    public function searchEmployeeEncahment(Request $request)
    {
        $dept_id  = $request->input('dept_id');
        $designation_id  = $request->input('designation_id');
        $emp_id  = $request->input('emp_id');
        $emp_name  = $request->input('emp_name');
        $duration= $request ->input('duration');
        $leave_type_id = $request ->input('leave_type_id');
        $leave_process_procedure = $request ->input('leave_process_procedure');
        $leave_year = $request ->input('leave_year');

        $currentYear=$now = Carbon::now()->year;

        $searchData = [];
        $allSearchInputs = array();

        $allSearchInputs['campus_id '] = $this->academicHelper->getCampus();
        $allSearchInputs['institute_id '] = $this->academicHelper->getInstitute();

        // check data
        if ($dept_id) $allSearchInputs['department'] = $dept_id ;
        if ($designation_id) $allSearchInputs['designation'] = $designation_id ;
        if ($emp_id) $allSearchInputs['user_id'] = $emp_id;
        if ($emp_name) $allSearchInputs['first_name'] = $emp_name;

        $searchData=$this->employeeInformation->where($allSearchInputs)->get();
        $stdListView = view('employee::pages.leave-management.employee-encashment-list', compact('searchData','duration','leave_type_id','dept_id','designation_id','leave_process_procedure','currentYear','leave_year'))->render();
        return ['status'=>'success', 'msg'=>'Student List found', 'html'=>$stdListView];

    }

}
