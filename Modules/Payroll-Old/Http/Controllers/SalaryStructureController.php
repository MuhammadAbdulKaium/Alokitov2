<?php

namespace Modules\Payroll\Http\Controllers;

use Illuminate\Contracts\Encryption\EncryptException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Payroll\Entities\SalaryComponent;
use Modules\Payroll\Entities\SalaryStructure;
use Modules\Payroll\Entities\SalaryStructureDetails;
use Modules\Payroll\Entities\EmpSalaryAssign;
use Illuminate\Support\Facades\DB;
use Redirect;
use Session;
use Validator;
class SalaryStructureController extends Controller
{
    private $companyId = 1;
    private $branchId = 1;
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $SalaryStructures = SalaryStructure::where([['company_id', institution_id()],['brunch_id', campus_id()]])->get();
        return view('payroll::pages.salaryStructure.salary-structure',compact('SalaryStructures'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $BasicSalComp = SalaryComponent::where('amount_type', 'B')->where([['company_id', institution_id()],['brunch_id', campus_id()]])->get();
        $alloSalComp = SalaryComponent::where([['amount_type', null],['type', 'A']])->orWhere('amount_type', 'OT')->where([['company_id', institution_id()],['brunch_id', campus_id()]])->get();
        $dedSalComp = SalaryComponent::where([['amount_type', null],['type', 'D']])->orWhere('amount_type', 'LN')->orWhere('amount_type', 'PF')->where([['company_id', institution_id()],['brunch_id', campus_id()]])->get();
        return view('payroll::pages.salaryStructure.salary-structure_create',compact('BasicSalComp','alloSalComp','dedSalComp'));
    }


    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function store(Request $request)
    {   //return $request->all();
        $validator = Validator::make($request->all(), [
            'salaryStructureName' => 'required|max:100',
            'salComp_1' => 'required|numeric',
            'salaryAmtVal_1' => 'required|numeric',
            'percent_1' => 'required',
        ]);
        $i = $request->maxValI;
        $ii = $request->maxValII;
        if ($validator->passes()){
            // Start transaction
            DB::beginTransaction();

            try{
                $salaryStruc = new SalaryStructure();
                $salaryStruc->name = $request->salaryStructureName;
                $salaryStruc->details = $request->details;
                $salaryStruc->company_id = institution_id();
                $salaryStruc->brunch_id = campus_id();
                $salaryStruc->save();
                $salaryStrucId = DB::getPdo()->lastInsertId();
            }catch (ValidationException $e) {
                DB::rollback();
                return redirect()->back()->withErrors($e->getErrors())->withInput();
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

            try{
                if($salaryStruc){
                    for($i_=1; $i_<=$i; $i_++) {
                        $salComp = 'salComp_' . $i_;
                        $salComp = $request->$salComp;
                        $salaryAmtVal = 'salaryAmtVal_' . $i_;
                        $salaryAmtVal = $request->$salaryAmtVal;
                        $percent = 'percent_' . $i_;
                        $percent = ($request->$percent == 'P')? 'P' : '';

                        if(!empty($salComp)){
                            $salaryStrucDtl1 = new SalaryStructureDetails();
                            $salaryStrucDtl1->structure_id = $salaryStrucId;
                            $salaryStrucDtl1->component_id = $salComp;
                            $salaryStrucDtl1->amount = $salaryAmtVal;
                            $salaryStrucDtl1->percent = $percent;
                            $salaryStrucDtl1->company_id = institution_id();
                            $salaryStrucDtl1->brunch_id = campus_id();
                            $salaryStrucDtl1->save();
                        }
                    }
                    if($ii>1000){
                        for($ii_=1000; $ii_<=$ii; $ii_++){
                            $salComp = 'salComp_'.$ii_;
                            $salComp = $request->$salComp;
                            $salaryAmtVal = 'salaryAmtVal_'.$ii_;
                            $salaryAmtVal = $request->$salaryAmtVal;
                            $percent = 'percent_'.$ii_;
                            $percent = ($request->$percent == 'P')? 'P' : '';

                            if(!empty($salComp)){
                                $salaryStrucDtl2 = new SalaryStructureDetails();
                                $salaryStrucDtl2->structure_id = $salaryStrucId;
                                $salaryStrucDtl2->component_id = $salComp;
                                $salaryStrucDtl2->amount = $salaryAmtVal;
                                $salaryStrucDtl2->percent = $percent;
                                $salaryStrucDtl2->company_id = institution_id();
                                $salaryStrucDtl2->brunch_id = campus_id();
                                $salaryStrucDtl2->save();
                            }
                        }
                    }else{
                        $salaryStrucDtl2=1;
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
            if ($salaryStrucDtl1 && $salaryStrucDtl2){
                //End transaction
                DB::commit();
                Session::flash('success', 'Salary Structure added');
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
    public function show(Request $request)
    {
        $SalaryStructures = SalaryStructure::where('id',$request->id)->where([['company_id', institution_id()],['brunch_id', campus_id()]])->first();
        //return $SalaryStructuresAloDet = SalaryStructureDetails::where('structure_id','=',$SalaryStructures->id)->get();

        $SalaryStructuresBasic = SalaryStructureDetails::
        leftJoin('pay_salary_component', 'pay_salary_component.id', 'pay_salary_structure_detail.component_id')
            ->select('name', 'type', 'amount_type', 'amount', 'percent')
            ->where([['amount_type', 'B'],['structure_id',$request->id]])
            ->where([['pay_salary_component.company_id', institution_id()],['pay_salary_component.brunch_id', campus_id()]])
            ->where([['pay_salary_structure_detail.company_id', institution_id()],['pay_salary_structure_detail.brunch_id', campus_id()]])
            ->get();

        $SalaryStructuresAlo = SalaryStructureDetails::
        leftJoin('pay_salary_component', 'pay_salary_component.id', 'pay_salary_structure_detail.component_id')
            ->select('name', 'type', 'amount_type', 'amount', 'percent')
            ->where([['type','A'],['structure_id',$request->id]])
            ->where( function ($q){
                $q->where('amount_type', null);
                $q->orWhere('amount_type', 'OT');
            })->where([['pay_salary_component.company_id', institution_id()],['pay_salary_component.brunch_id', campus_id()]])
            ->where([['pay_salary_structure_detail.company_id', institution_id()],['pay_salary_structure_detail.brunch_id', campus_id()]])
            ->get();

        $SalaryStructuresDed = SalaryStructureDetails::
        leftJoin('pay_salary_component', 'pay_salary_component.id', 'pay_salary_structure_detail.component_id')
            ->select('name', 'type', 'amount_type', 'amount', 'percent')
            ->where([['type','D'],['structure_id',$request->id]])
            ->where( function ($q){
                $q->where('amount_type', null);
                $q->orWhere('amount_type', 'LN');
                $q->orWhere('amount_type', 'PF');
            })->where([['pay_salary_component.company_id', institution_id()],['pay_salary_component.brunch_id', campus_id()]])
            ->where([['pay_salary_structure_detail.company_id', institution_id()],['pay_salary_structure_detail.brunch_id', campus_id()]])
            ->get();
        return view('payroll::pages.salaryStructure.salary-structure_view',compact('SalaryStructures','SalaryStructuresBasic','SalaryStructuresAlo','SalaryStructuresDed'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(Request $request)
    {
        $BasicSalComp = SalaryComponent::where('amount_type', 'B')
            ->where([['company_id', institution_id()],['brunch_id', campus_id()]])->get();
        $alloSalComp = SalaryComponent::where([['amount_type', null],['type', 'A']])->orWhere('amount_type', 'OT')
            ->where([['company_id', institution_id()],['brunch_id', campus_id()]])->get();
        $dedSalComp = SalaryComponent::where([['amount_type', null],['type', 'D']])->orWhere('amount_type', 'LN')->orWhere('amount_type', 'PF')
            ->where([['company_id', institution_id()],['brunch_id', campus_id()]])->get();

        $SalaryStructures = SalaryStructure::where('id',$request->id)
            ->where([['company_id', institution_id()],['brunch_id', campus_id()]])->first();

        $SalaryStructuresBasic = SalaryStructureDetails::
        leftJoin('pay_salary_component', 'pay_salary_component.id', 'pay_salary_structure_detail.component_id')
            ->select('structure_id','name', 'type', 'amount_type', 'amount', 'percent')
            ->where([['amount_type', 'B'],['structure_id',$request->id]])
            ->where([['pay_salary_component.company_id', institution_id()],['pay_salary_component.brunch_id', campus_id()]])
            ->where([['pay_salary_structure_detail.company_id', institution_id()],['pay_salary_structure_detail.brunch_id', campus_id()]])
            ->get();

        $SalaryStructuresAlo = SalaryStructureDetails::
        leftJoin('pay_salary_component', 'pay_salary_component.id', 'pay_salary_structure_detail.component_id')
            ->select('name', 'type', 'amount_type', 'amount', 'percent')
            ->where([['type','A'],['structure_id',$request->id]])
            ->where( function ($q){
                $q->where('amount_type', null);
                $q->orWhere('amount_type', 'OT');
            })->where([['pay_salary_component.company_id', institution_id()],['pay_salary_component.brunch_id', campus_id()]])
            ->where([['pay_salary_structure_detail.company_id', institution_id()],['pay_salary_structure_detail.brunch_id', campus_id()]])
            ->get();

        $SalaryStructuresDed = SalaryStructureDetails::
        leftJoin('pay_salary_component', 'pay_salary_component.id', 'pay_salary_structure_detail.component_id')
            ->select('name', 'type', 'amount_type', 'amount', 'percent')
            ->where([['type','D'],['structure_id',$request->id]])
            ->where( function ($q){
                $q->where('amount_type', null);
                $q->orWhere('amount_type', 'LN');
                $q->orWhere('amount_type', 'PF');
            })->where([['pay_salary_component.company_id', institution_id()],['pay_salary_component.brunch_id', campus_id()]])
            ->where([['pay_salary_structure_detail.company_id', institution_id()],['pay_salary_structure_detail.brunch_id', campus_id()]])
            ->get();
        return view('payroll::pages.salaryStructure.salary-structure_edit',compact('SalaryStructures','SalaryStructuresBasic','SalaryStructuresAlo','SalaryStructuresDed','BasicSalComp','alloSalComp','dedSalComp'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function update(Request $request)
    {
        //return $request->all();
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'salaryStructureName' => 'required|max:100',
            'salComp_1' => 'required|numeric',
            'salaryAmtVal_1' => 'required|numeric',
            'percent_1' => 'required',
        ]);
        $i = $request->maxValI;
        $ii = $request->maxValII;
        if ($validator->passes()){
            // Start transaction
            DB::beginTransaction();

            try{
                $salaryStruc = SalaryStructure::where('id', $request->id)
                    ->update([
                        "name" => $request->salaryStructureName,
                        "details" => $request->details,
                    ]);
                $salaryStrucId = $request->id;
                SalaryStructureDetails::where('structure_id',$request->id)->delete();
            }catch (ValidationException $e) {
                DB::rollback();
                SalaryStructureDetails::where('structure_id',$request->id)->restore();
                return redirect()->back()->withErrors($e->getErrors())->withInput();
            } catch (\Exception $e) {
                DB::rollback();
                SalaryStructureDetails::where('structure_id',$request->id)->restore();
                throw $e;
            }

            try{
                if($salaryStruc){
                    for($i_=1; $i_<=$i; $i_++) {
                        $salComp = 'salComp_' . $i_;
                        $salComp = $request->$salComp;
                        $salaryAmtVal = 'salaryAmtVal_' . $i_;
                        $salaryAmtVal = $request->$salaryAmtVal;
                        $percent = 'percent_' . $i_;
                        $percent = ($request->$percent == 'P')? 'P' : '';

                        if(!empty($salComp)){
                            $salaryStrucDtl1 = new SalaryStructureDetails();
                            $salaryStrucDtl1->structure_id = $salaryStrucId;
                            $salaryStrucDtl1->component_id = $salComp;
                            $salaryStrucDtl1->amount = $salaryAmtVal;
                            $salaryStrucDtl1->percent = $percent;
                            $salaryStrucDtl1->company_id = institution_id();
                            $salaryStrucDtl1->brunch_id = campus_id();
                            $salaryStrucDtl1->save();
                        }
                    }
                    if($ii>1000){
                        for($ii_=1000; $ii_<=$ii; $ii_++){
                            $salComp = 'salComp_'.$ii_;
                            $salComp = $request->$salComp;
                            $salaryAmtVal = 'salaryAmtVal_'.$ii_;
                            $salaryAmtVal = $request->$salaryAmtVal;
                            $percent = 'percent_'.$ii_;
                            $percent = ($request->$percent == 'P')? 'P' : '';

                            if(!empty($salComp)){
                                $salaryStrucDtl2 = new SalaryStructureDetails();
                                $salaryStrucDtl2->structure_id = $salaryStrucId;
                                $salaryStrucDtl2->component_id = $salComp;
                                $salaryStrucDtl2->amount = $salaryAmtVal;
                                $salaryStrucDtl2->percent = $percent;
                                $salaryStrucDtl2->company_id = institution_id();
                                $salaryStrucDtl2->brunch_id = campus_id();
                                $salaryStrucDtl2->save();
                            }
                        }
                    }else{
                        $salaryStrucDtl2=1;
                    }
                }
            }catch (ValidationException $e){
                DB::rollback();
                SalaryStructureDetails::where('structure_id',$request->id)->restore();
                return redirect()->back()->withErrors($e->getErrors())->withInput();
            } catch (\Exception $e) {
                DB::rollback();
                SalaryStructureDetails::where('structure_id',$request->id)->restore();
                throw $e;
            }

            // checking
            if ($salaryStrucDtl1 && $salaryStrucDtl2 ){
                //End transaction
                DB::commit();
                SalaryStructureDetails::where('structure_id',$request->id)->whereNotNull('deleted_at')->forceDelete();
                Session::flash('success', 'Salary Structure Update.');
                // return redirect
                return redirect()->back();
            } else {
                Session::flash('warning', 'Uabale to perform the actions');
                // return redirect
                return redirect()->back();
            }
        } else {
            Session::flash('warning', 'Invalid Information. please try with correct Information');
            //SalaryStructureDetails::where('structure_id',$request->id)->restore();
            // receiving page action
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    /**
     * @param Request $request
     * @return mixed|string
     */
    public function destroy(Request $request)
    {
        //return $request->all();
        $id = $request->id;
        $SalaryStructure = SalaryStructure::FindOrFail($id);
        // checking
        if ($SalaryStructure) {
            //fk checking
            $chkFk1 = EmpSalaryAssign::where('salary_structure_id',$id)->get();
            if(count($chkFk1))
                return "Unable to delete. Someone is using it.";
            else
                $salaryEmpDeleted = $SalaryStructure->delete();

            // checking
            if ($salaryEmpDeleted) {
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
