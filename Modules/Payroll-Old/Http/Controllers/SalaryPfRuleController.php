<?php

namespace Modules\Payroll\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Payroll\Entities\SalaryComponent;
use Modules\Payroll\Entities\SalaryPfRule;
use Redirect;
use Session;
use Validator;
use Modules\Payroll\Entities\SalaryStructureDetails;
use Modules\Payroll\Entities\EmpMonthlyDedAllo;
use Modules\Payroll\Entities\EmpSalaryAssignExtra;
class SalaryPfRuleController extends Controller
{
    private $companyId = 1;
    private $branchId = 1;
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $SalaryPfRule = SalaryPfRule::where([['company_id', institution_id()],['brunch_id', campus_id()]])->get();
        return view('payroll::pages.salaryPf.salary_pf',compact('SalaryPfRule'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $SalaryPfLists = SalaryComponent::where('amount_type','PF')->where([['company_id', institution_id()],['brunch_id', campus_id()]])->get();
        return view('payroll::pages.salaryPf.salary_pf_create',compact('SalaryPfLists'));
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
            'pfType'=> 'required',
            'pfDedRule'=> 'required',
            'dedType'=> 'required',
            'pfDedFrom'=> 'required',
            'amtVal'=> 'required|numeric',
            'compContributeType'=> 'required',
            'compContribute'=> 'required|numeric',
            'minEligableTime'=> 'required',
            'deductionDate'=> 'required',
        ]);

        if ($validator->passes()){
            $approveDate = $request->approveDate != '' ? date('Y-m-d',strtotime($request->input('approveDate'))) : '';
            $deductionDate = $request->deductionDate != '' ? date('Y-m-d',strtotime($request->input('deductionDate'))) : '';

            $SalaryPfRule = new SalaryPfRule();
            $SalaryPfRule->approve_date = $approveDate;
            $SalaryPfRule->pf_type_id = $request->pfType;
            $SalaryPfRule->pf_ded_rule = $request->pfDedRule;
            $SalaryPfRule->pf_ded_type = $request->dedType;
            $SalaryPfRule->pf_ded_from = $request->pfDedFrom;
            $SalaryPfRule->amt_val = $request->amtVal;
            $SalaryPfRule->comp_contribute_type = $request->compContributeType;
            $SalaryPfRule->comp_contribute = $request->compContribute;
            $SalaryPfRule->min_eligable_time = $request->minEligableTime;
            $SalaryPfRule->deduction_date = $deductionDate;
            $SalaryPfRule->remarks = $request->remarks;
            $SalaryPfRule->company_id = institution_id();
            $SalaryPfRule->brunch_id = campus_id();
            $SalaryPfRule->save();

            // checking
            if ($SalaryPfRule){
                //End transaction
                Session::flash('success', 'Provident fund Structure added');
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
        $id = $request->id;
        $salPfRule = SalaryPfRule::where('id',$id)->first();
        return view('payroll::pages.salaryPf.salary_pf_view',compact('salPfRule'));
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
        $salaryPf = SalaryPfRule::FindOrFail($id);
        $salaryPfData = SalaryPfRule::where('id',$id)->first();
        $compId = $salaryPfData->pf_type_id;
        // checking
        if ($salaryPf) {
            //fk checking
            $chkFk1 = SalaryStructureDetails::where('component_id',$compId)->get();
            $chkFk2 = EmpMonthlyDedAllo::where('component_id',$compId)->get();
            $chkFk3 = EmpSalaryAssignExtra::where('component_id',$compId)->get();
            if(count($chkFk1) || count($chkFk2) || count($chkFk3))
                return "Unable to delete. Someone is using it." ;
            else
                $salaryPfDeleted = $salaryPf->delete();

            // checking
            if ($salaryPfDeleted) {
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