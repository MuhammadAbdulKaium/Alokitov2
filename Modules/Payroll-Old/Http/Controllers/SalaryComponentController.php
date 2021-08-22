<?php

namespace Modules\Payroll\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Accounting\Entities\AccCharts;
use Modules\Payroll\Entities\SalaryComponent;
use Modules\Payroll\Entities\SalaryStructureDetails;
use Modules\Payroll\Entities\EmpMonthlyDedAllo;
use Modules\Payroll\Entities\EmpSalaryAssignExtra;
use Redirect;
use Session;
use Validator;

class SalaryComponentController extends Controller
{

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {

        $SalaryComponent = SalaryComponent::where([['company_id', institution_id()],['brunch_id', campus_id()]])->get();
        return view('payroll::pages.salaryCategory.salary-category',compact('SalaryComponent'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $accChart = AccCharts::whereraw('chart_parent = (select id from acc_charts where chart_code = \'Employee Expense\')')
            ->get(['id', 'chart_code', 'chart_name']);

        return view('payroll::pages.salaryCategory.salary-category_create',compact('accChart'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'salaryComponentName' => 'required',
            'code' => 'required',
            'type' => 'required',
        ]);

        if ($validator->passes()){
            $salaryComp = new SalaryComponent();
            $salaryComp->name = $request->salaryComponentName;
            $salaryComp->code = $request->code;
            $salaryComp->type = $request->type;
            $salaryComp->amount_type = $request->amountType;
            $salaryComp->fixed_amount = $request->fixedAmount;
            $salaryComp->fixed_percent = $request->fixedPercentage;
            $salaryComp->percent_base = $request->percentageBase;
            $salaryComp->company_id = institution_id();
            $salaryComp->brunch_id = campus_id();
            $salaryComp->save();

            // checking
            if ($salaryComp) {
                Session::flash('success', 'Salary Component added');
                // return redirect
                return redirect()->back();
            } else {
                Session::flash('warning', 'Uabale to perform the actions');
                // return redirect
                return redirect()->back();
            }
        }else {
            Session::flash('warning', 'Invalid Information. please try with correct Information');
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
        $SalaryComponent = SalaryComponent::where('id',$request->id)->first();
        return view('payroll::pages.salaryCategory.salary-category_view',compact('SalaryComponent'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(Request $request)
    {   $SalaryComponent = SalaryComponent::where('id',$request->id)->first();
        return view('payroll::pages.salaryCategory.salary-category_edit',compact('SalaryComponent'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'salaryComponentName' => 'required',
            'code' => 'required',
            'type' => 'required',
            'amountType' => 'required',
        ]);
        if ($validator->passes()) {
            $SalaryComponent = SalaryComponent::where('id', $request->id)
                ->update([
                    "name" => $request->salaryComponentName,
                    "code" => $request->code,
                    "type" => $request->type,
                    "amount_type" => $request->amountType,
                    "fixed_amount" => $request->fixedAmount,
                    "fixed_percent" => $request->fixedPercentage,
                    "percent_base" => $request->percentageBase,
                ]);
            // checking
            if ($SalaryComponent) {
                Session::flash('success', 'Salary Component Updated');
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

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {   $id = $request->id;
        $salaryComp = SalaryComponent::FindOrFail($id);
        // checking
        if ($salaryComp) {
            //fk checking
            $chkFk1 = SalaryStructureDetails::where('component_id',$id)->get();
            $chkFk2 = EmpMonthlyDedAllo::where('component_id',$id)->get();
            $chkFk3 = EmpSalaryAssignExtra::where('component_id',$id)->get();
            if(count($chkFk1) || count($chkFk2) || count($chkFk3))
                return "Unable to delete. Someone is using it." ;
            else
                $salaryCompDeleted = $salaryComp->delete();

            // checking
            if ($salaryCompDeleted) {
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
