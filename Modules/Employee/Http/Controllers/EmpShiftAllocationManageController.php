<?php

namespace Modules\Employee\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Employee\Entities\EmployeeDepartment;
use Modules\Employee\Entities\EmployeeDesignation;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\Employee\Entities\EmployeeShiftAllocation;
use Modules\Employee\Entities\Shift;
use Redirect;
use Session;
use Validator;

class EmpShiftAllocationManageController extends Controller
{
    private $companyId = 1;
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $allShiftEmp = EmployeeShiftAllocation::where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->get();
        return view('employee::pages.shift_allocation.shift_allocation_index',compact('allShiftEmp'));
    }

    public function shift_allocation(){
        $allDep = EmployeeDepartment::where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->get();
        $allDes = EmployeeDesignation::where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->get();
        $allShift = Shift::orderBy('shiftName', 'ASC')->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->get();
        return view('employee::pages.shift_allocation.shift_allocation',compact('allShift','allDep','allDes'));
    }

    public function emp_list(Request $request){

        $designation = $request->input('designation');
        $department  = $request->input('department');

        $allSearchInputs = array();
        // check department
        if ($department) {
            $allSearchInputs['department'] = $department;
        }
        // check designation
        if ($designation) {
            $allSearchInputs['designation'] = $designation;
        }
        // search result
        $allEmployee = EmployeeInformation::where($allSearchInputs)->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->get();
        return view('employee::pages.shift_allocation.shift_allocation_emp_list',compact('allEmployee'));
    }

    public function no_shift_emp(){
        return 'no_shift_emp';
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('employee::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'shiftName' => 'required',
            'effectiveDateFrom' => 'required',
            'effectiveDateTo' => 'required',
            'shiftAloChkBox' => 'required',
        ]);

        if ($validator->passes()) {
            $effectiveDateFrom = date('Y-m-d',strtotime($request->input('effectiveDateFrom')));
            $effectiveDateTo = date('Y-m-d',strtotime($request->input('effectiveDateTo')));

            foreach ($request->shiftAloChkBox as $data) {
                $shiftAllocation = new EmployeeShiftAllocation();
                $shiftAllocation->shift_id = $request->input('shiftName');
                $shiftAllocation->emp_id = $data;
                $shiftAllocation->effective_date_from = $effectiveDateFrom;
                $shiftAllocation->effective_date_to = $effectiveDateTo;
                $shiftAllocation->brunch_id = session()->get('campus');
                $shiftAllocation->company_id = session()->get('institute');
                $shiftAllocation->save();
            }
            // checking
            if ($shiftAllocation) {
                Session::flash('success', 'Shift Allocation Done');
                // return redirect
                return redirect()->back();
            } else {
                Session::flash('warning', 'Uabale to perform the actions');
                // return redirect
                return redirect()->back();
            }
        } else {
            Session::flash('warning', 'Invalid Information. please try with correct Information');
            // receiving page action
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('employee::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('employee::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}
