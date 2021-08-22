<?php

namespace Modules\Payroll\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Payroll\Entities\SalaryComponent;
use Modules\Payroll\Entities\SalaryOtRule;
use Redirect;
use Session;
use Validator;

use Modules\Payroll\Entities\SalaryStructureDetails;
use Modules\Payroll\Entities\EmpMonthlyDedAllo;
use Modules\Payroll\Entities\EmpSalaryAssignExtra;
class SalaryOtRuleController extends Controller
{
    private $companyId = 1;
    private $branchId = 1;
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $SalaryOtRule = SalaryOtRule::where([['company_id', institution_id()],['brunch_id', campus_id()]])->get();
        return view('payroll::pages.salaryOt.salary_ot',compact('SalaryOtRule'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $SalaryOtLists = SalaryComponent::where('amount_type','OT')->where([['company_id', institution_id()],['brunch_id', campus_id()]])->get();
        return view('payroll::pages.salaryOt.salary_ot_create',compact('SalaryOtLists'));
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
            'effectiveDate'=> 'required',
            'otType'=> 'required',
            'otRate'=> 'required',
            'otStart'=> 'required',
            'otEnd'=> 'required',
        ]);

        if ($validator->passes()){
            $approveDate = $request->approveDate != '' ? date('Y-m-d',strtotime($request->input('approveDate'))) : '';
            $effectiveDate = $request->effectiveDate != '' ? date('Y-m-d',strtotime($request->input('effectiveDate'))) : '';

            $SalaryOtRule = new SalaryOtRule();
            $SalaryOtRule->approve_date = $approveDate;
            $SalaryOtRule->effective_date = $effectiveDate;
            $SalaryOtRule->ot_type_id = $request->otType;
            $SalaryOtRule->ot_rate = $request->otRate;
            $SalaryOtRule->ot_start = $request->otStart;
            $SalaryOtRule->ot_end = $request->otEnd;
            $SalaryOtRule->min_ot = $request->minOt;
            $SalaryOtRule->max_ot = $request->maxOt;
            $SalaryOtRule->ot_grace = $request->otGrace;
            $SalaryOtRule->remarks = $request->remarks;
            $SalaryOtRule->company_id = institution_id();
            $SalaryOtRule->brunch_id = campus_id();
            $SalaryOtRule->save();

            // checking
            if ($SalaryOtRule){
                //End transaction
                Session::flash('success', 'Overtime Structure added');
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
     * Show the specified resource.
     * @return Response
     */
    public function show(Request $request)
    {
        $otRule = SalaryOtRule::where('id',$request->id)->first();
        return view('payroll::pages.salaryOt.salary_ot_view',compact('otRule'));
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
        $id = $request->id;
        $SalaryOtRuleData = SalaryOtRule::where('id',$id)->first();
        $salaryOtRule = SalaryOtRule::FindOrFail($id);

        $comp_id =  $SalaryOtRuleData->ot_type_id;
        // checking
        if ($salaryOtRule) {
            //fk checking
            $chkFk1 = SalaryStructureDetails::where('component_id',$comp_id)->get();
            $chkFk2 = EmpMonthlyDedAllo::where('component_id',$comp_id)->get();
            $chkFk3 = EmpSalaryAssignExtra::where('component_id',$comp_id)->get();
            if(count($chkFk1) || count($chkFk2) || count($chkFk3))
                return "Unable to delete. Someone is using it." ;
            else
                $salaryOtDeleted = $salaryOtRule->delete();

            // checking
            if ($salaryOtDeleted) {
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