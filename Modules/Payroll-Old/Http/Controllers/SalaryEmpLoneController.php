<?php

namespace Modules\Payroll\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\Payroll\Entities\SalaryComponent;
use Modules\Payroll\Entities\SalaryEmpLone;
use Redirect;
use Session;
use Validator;

class SalaryEmpLoneController extends Controller
{
    private $companyId = 1;
    private $branchId = 1;
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $SalaryEmpLone = SalaryEmpLone::where([['company_id', institution_id()],['brunch_id', campus_id()]])->get();
        return view('payroll::pages.salaryEmpLone.salary_emp_lone',compact('SalaryEmpLone'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $emp = EmployeeInformation::where([['institute_id', institution_id()],['campus_id', campus_id()]])->get();
        $SalaryLnLists = SalaryComponent::where('amount_type','LN')->where([['company_id', institution_id()],['brunch_id', campus_id()]])->get();
        return view('payroll::pages.salaryEmpLone.salary_emp_lone_create',compact('emp','SalaryLnLists'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //return $request->all();
        $validator = Validator::make($request->all(), [
            'emp_id'=> 'required',
            'loanType'=> 'required',
            'loanAmount'=> 'required',
            'installmentNo'=> 'required',
            'installmentAmount'=> 'required',
            'deductionDate'=> 'required',
        ]);

        if ($validator->passes()){
            $approveDate = $request->approveDate != '' ? date('Y-m-d',strtotime($request->input('approveDate'))) : '';
            $deductionDate = $request->deductionDate != '' ? date('Y-m-d',strtotime($request->input('deductionDate'))) : '';

            $SalaryEmpLone = new SalaryEmpLone();
            $SalaryEmpLone->approve_date = $approveDate;
            $SalaryEmpLone->employee_id = $request->emp_id;
            $SalaryEmpLone->loan_type_id = $request->loanType;
            $SalaryEmpLone->loan_amount = $request->loanAmount;
            $SalaryEmpLone->loan_fee_type = $request->loanFeeType;
            $SalaryEmpLone->installment_no = $request->installmentNo;
            $SalaryEmpLone->loan_fee_amount = $request->loanFeeAmount;
            $SalaryEmpLone->installment_amount = $request->installmentAmount;
            $SalaryEmpLone->deduction_date = $deductionDate;
            $SalaryEmpLone->remarks = $request->remarks;
            $SalaryEmpLone->company_id = institution_id();
            $SalaryEmpLone->brunch_id = campus_id();
            $SalaryEmpLone->save();

            // checking
            if ($SalaryEmpLone){
                //End transaction
                Session::flash('success', 'Employee Lone added');
                // return redirect
                return redirect()->back();
            } else {
                Session::flash('warning', 'Unable to perform the actions');
                // return redirect
                return redirect()->back();
            }
        } else {
            Session::flash('warning', 'Invalid Information. Please try with correct Information');
            // receiving page action
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function show(Request $request)
    {
        $empLone = SalaryEmpLone::where('id',$request->id)->first();
        return view('payroll::pages.salaryEmpLone.salary_emp_lone_view',compact('empLone'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('payroll::edit');
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
    public function destroy(Request $request)
    {
        //return $request->all();
        $id = $request->id;
        $empLone = SalaryEmpLone::FindOrFail($id);

        // checking
        if ($empLone) {
            $empLoneData = SalaryEmpLone::where('id',$id)->first();
            $date1=date_create($empLoneData->created_at);
            $date2=date_create(date('Y-m-d'));
            $diff=date_diff($date1,$date2);
            $diff->format("%a");
            if($diff->format("%a") < 15){
                $empLoneDelete = $empLone->delete();
            }
            // checking
            if (isset($empLoneDelete)){
                // return redirect
                return 'Deleted';
            } else {
                // return redirect
                return 'Unable to delete';
            }
        } else {
            // return redirect
            return 'Unable to perform the actions';
        }
    }
}
