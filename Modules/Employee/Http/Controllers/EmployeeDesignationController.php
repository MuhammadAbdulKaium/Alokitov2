<?php

namespace Modules\Employee\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Employee\Entities\EmployeeDepartment;
use Modules\Employee\Entities\EmployeeDesignation;
use Redirect;
use Session;
use Validator;

class EmployeeDesignationController extends Controller
{

    private $academicHelper;
    private $employeeDepartment;
    private $employeeDesignation;

    // constructor
    public function __construct(AcademicHelper $academicHelper, EmployeeDepartment $employeeDepartment, EmployeeDesignation $employeeDesignation)
    {
        $this->academicHelper = $academicHelper;
        $this->employeeDepartment = $employeeDepartment;
        $this->employeeDesignation = $employeeDesignation;
    }


    // Display a listing of the resource.
    public function index()
    {
        $campusId = $this->academicHelper->getCampus();
        $instituteId = $this->academicHelper->getInstitute();
        // all designation list
        $allDesignations = $this->employeeDesignation->where(['institute_id' => $instituteId])->orderBy('name', 'ASC')->get();
        // return view with allDseignation variable
        return view('employee::pages.designation', compact('allDesignations'));
    }

    // Show the form for creating a new resource.
    public function create()
    {
        $campusId = $this->academicHelper->getCampus();
        $instituteId = $this->academicHelper->getInstitute();
        // return view
        return view('employee::pages.modals.designation-create');
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'name'  => 'required',
            'alias' => 'required',
        ]);

        // storing requesting input data
        if ($validator->passes()) {
            // create new designation
            $designationProfile = new EmployeeDesignation();
            // input detils
            $designationProfile->name = $request->input('name');
            $designationProfile->alias = $request->input('alias');
            $designationProfile->campus_id = $this->academicHelper->getCampus();
            $designationProfile->institute_id = $this->academicHelper->getInstitute();
            // save designationProfile
            $designationCreated = $designationProfile->save();
            // checking
            if ($designationCreated) {
                Session::flash('success', 'Designation added');
                // return redirect
                return redirect()->back();
            } else {
                Session::flash('warning', 'Uabale to perform the actions');
                // return redirect
                return redirect()->back();
            }
        } else {
            Session::flash('warning', 'invalid Information. please try with correct Information');
            // receiving page action
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    // Show the specified resource.
    public function show($id)
    {
        // find designation
        $designationProfile = $this->employeeDesignation->FindOrFail($id);
        // return view with designationProfile variable
        return view('employee::pages.modals.designation-view', compact('designationProfile'));
    }

    // Show the form for editing the specified resource.
    public function edit($id)
    {
        $campusId = $this->academicHelper->getCampus();
        $instituteId = $this->academicHelper->getInstitute();
        // find designation
        $designationProfile = EmployeeDesignation::FindOrFail($id);
        // return view with designationProfile variable
        return view('employee::pages.modals.designation-update', compact('designationProfile'));
    }

    // Update the specified resource in storage.
    public function update(Request $request, $id)
    {
        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'name'  => 'required',
            'alias' => 'required',
        ]);

        // storing requesting input data
        if ($validator->passes()) {
            // find designation
            $designationProfile = $this->employeeDesignation->FindOrFail($id);
            // input detils
            $designationProfile->name = $request->input('name');
            $designationProfile->alias = $request->input('alias');
            // save designationProfile
            $designationProfileUpdated = $designationProfile->save();
            // checking
            if ($designationProfileUpdated) {
                Session::flash('success', 'Designation Updated');
                // return redirect
                return redirect()->back();
            } else {
                Session::flash('warning', 'Uabale to perform the actions');
                // return redirect
                return redirect()->back();
            }
        } else {
            Session::flash('warning', 'invalid Information. please try with correct Information');
            // receiving page action
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    // Remove the specified resource from storage.
    public function destroy($id)
    {
        // designaiton profile
        $designationProfile = $this->employeeDesignation->FindOrFail($id);

        // checking
        if ($designationProfile) {

            $designationProfileDeleted = $designationProfile->delete();
            // checking
            if ($designationProfileDeleted) {
                Session::flash('success', 'Designation Deleted');
                // return redirect
                return redirect()->back();
            } else {
                Session::flash('warning', 'Uabale to delete designation');
                // return redirect
                return redirect()->back();
            }
        } else {
            Session::flash('warning', 'Unabale to perform the actions');
            // return redirect
            return redirect()->back();
        }
    }


    // find designation list with department id
    public function findDesignationList($deptId)
    {
        $campusId = $this->academicHelper->getCampus();
        $instituteId = $this->academicHelper->getInstitute();
        // response data
        $responseData = array();
        // find designation list with department id
        $designationList = $this->employeeDesignation->where([
            'institute_id' => $instituteId, 'dept_id' => $deptId
        ])->get();
        // looping
        foreach ($designationList as $designation) {
            $responseData[] = ['id' => $designation->id, 'name' => $designation->name];
        }

        // return response data
        return $responseData;
    }
}
