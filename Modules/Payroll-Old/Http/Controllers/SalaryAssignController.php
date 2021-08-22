<?php

namespace Modules\Payroll\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Payroll\Entities\EmpSalaryAssign;
use Modules\Payroll\Entities\EmpSalaryAssignExtra;
use Modules\Payroll\Entities\SalaryComponent;
use Modules\Payroll\Entities\SalaryStructure;
use Modules\Payroll\Entities\SalaryStructureDetails;
use Modules\Employee\Entities\EmployeeInformation;
use Redirect;
use Session;
use Validator;

class SalaryAssignController extends Controller
{
    private $companyId = 1;
    private $branchId = 1;
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $empSalaryAssign = EmpSalaryAssign::where([['company_id', institution_id()],['brunch_id', campus_id()]])->get();
        return view('payroll::pages.salaryAssign.salary-assign',compact('empSalaryAssign'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {   //SalaryStructure
        $emp = EmployeeInformation::where([['institute_id', institution_id()],['campus_id', campus_id()]])->get();
        $salaryStructure = SalaryStructure::where([['company_id', institution_id()],['brunch_id', campus_id()]])->get();
        return view('payroll::pages.salaryAssign.salary-assign_create',compact('emp','salaryStructure'));
    }

    public function salarySegregation(Request $request)
    {
        $formData = $request->all();

        $alloSalComp = SalaryComponent::where([['amount_type', null],['type', 'A']])->where([['company_id', institution_id()],['brunch_id', campus_id()]])->get();
        $dedSalComp = SalaryComponent::where([['amount_type', null],['type', 'D']])->where([['company_id', institution_id()],['brunch_id', campus_id()]])->get();

        $SalaryStructure = SalaryStructure::where('id', $request->salaryStructure)->first();
        $SalaryStructureDetails =  SalaryStructureDetails::
        leftJoin('pay_salary_component', 'pay_salary_component.id', 'pay_salary_structure_detail.component_id')
            ->select('name', 'type', 'amount_type', 'amount', 'percent')
            ->where([['structure_id',$request->salaryStructure]])->get();
        return view('payroll::pages.salaryAssign.salary-assign_segregation', compact('SalaryStructure','SalaryStructureDetails', 'formData','alloSalComp','dedSalComp'));
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee' => 'required',
            'salaryStructure' => 'required',
            'basic' => 'required',
            'structureType' => 'required',
            'effectiveDate' => 'required',
        ]);
        $i = $request->maxValI;
        $ii = $request->maxValII;
        if ($validator->passes()){
            // Start transaction
            DB::beginTransaction();

            try{
                $empSalaryAssign = new EmpSalaryAssign();
                $empSalaryAssign->employee_id = $request->employee;
                $empSalaryAssign->salary_structure_id = $request->salaryStructure;
                $empSalaryAssign->salary_amount = $request->salary;
                $empSalaryAssign->salary_type = $request->structureType;
                $empSalaryAssign->effective_date = $request->effectiveDate;
                $empSalaryAssign->company_id = institution_id();
                $empSalaryAssign->brunch_id = campus_id();
                $empSalaryAssign->save();
                $empSalaryAssignId = DB::getPdo()->lastInsertId();
            }catch (ValidationException $e) {
                DB::rollback();
                return redirect()->back()->withErrors($e->getErrors())->withInput();
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }
            try{
                if($empSalaryAssign){
                    if( $i>0) {
                        for ($i_ = 1; $i_ <= $i; $i_++) {
                            $salComp = 'salComp_' . $i_;
                            $salComp = $request->$salComp;
                            $salaryAmtVal = 'salaryAmtVal_' . $i_;
                            $salaryAmtVal = $request->$salaryAmtVal;
                            $percent = 'percent_' . $i_;
                            $percent = ($request->$percent == 'P') ? 'P' : '';

                            if (!empty($salComp) && !empty($salaryAmtVal)) {
                                $empSalaryAssignExtra1 = new EmpSalaryAssignExtra();
                                $empSalaryAssignExtra1->assign_id = $empSalaryAssignId;
                                $empSalaryAssignExtra1->component_id = $salComp;
                                $empSalaryAssignExtra1->amount = $salaryAmtVal;
                                $empSalaryAssignExtra1->percent = $percent;
                                $empSalaryAssignExtra1->company_id = institution_id();
                                $empSalaryAssignExtra1->save();
                            }else{
                                $empSalaryAssignExtra1 = 1;
                            }
                        }
                    }else{
                        $empSalaryAssignExtra1 = 1;
                    }
                    if($ii>1000){
                        for($ii_=1001; $ii_<=$ii; $ii_++){
                            $salComp = 'salComp_'.$ii_;
                            $salComp = $request->$salComp;
                            $salaryAmtVal = 'salaryAmtVal_'.$ii_;
                            $salaryAmtVal = $request->$salaryAmtVal;
                            $percent = 'percent_'.$ii_;
                            $percent = ($request->$percent == 'P')? 'P' : '';

                            if(!empty($salComp) && !empty($salaryAmtVal)){
                                $empSalaryAssignExtra2 = new EmpSalaryAssignExtra();
                                $empSalaryAssignExtra2->assign_id = $empSalaryAssignId;
                                $empSalaryAssignExtra2->component_id = $salComp;
                                $empSalaryAssignExtra2->amount = $salaryAmtVal;
                                $empSalaryAssignExtra2->percent = $percent;
                                $empSalaryAssignExtra2->company_id = institution_id();
                                $empSalaryAssignExtra2->save();
                            }else{
                                $empSalaryAssignExtra2 = 1;
                            }
                        }
                    }else{
                        $empSalaryAssignExtra2 = 1;
                    }
                }
            }catch (ValidationException $e) {
                DB::rollback();
                return redirect()->back()->withErrors($e->getErrors())->withInput();
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

            // checking
            if ($empSalaryAssignExtra1 && $empSalaryAssignExtra2){
                //End transaction
                DB::commit();
                Session::flash('success', 'Salary Assign Successful');
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
        $empSalaryAssign = EmpSalaryAssign::where('id',$request->id)->first();
        return view('payroll::pages.salaryAssign.salary-assign_show',compact('empSalaryAssign'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(Request $request)
    {
        return $request->all();
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
        $salaryAssign = EmpSalaryAssign::FindOrFail($id);

        // checking
        if ($salaryAssign) {
            $salAss = EmpSalaryAssign::where('id',$id)->first();
            $date1=date_create($salAss->created_at);
            $date2=date_create(date('Y-m-d'));
            $diff=date_diff($date1,$date2);
            if($diff->format("%a") < 15){
                $salaryAssignDeleted = $salaryAssign->delete();
            }
            // checking
            if (isset($salaryAssignDeleted)) {
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
