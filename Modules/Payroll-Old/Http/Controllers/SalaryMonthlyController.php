<?php

namespace Modules\Payroll\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Accounting\Entities\AccCharts;
use Modules\Accounting\Entities\AccVoucherEntry;
use Modules\Accounting\Entities\AccVoucherType;
use Modules\Accounting\Http\Controllers\AccVoucherEntryController;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\Payroll\Entities\EmpMonthlyDedAllo;
use Modules\Payroll\Entities\EmpSalaryAssign;
use Modules\Payroll\Entities\SalaryComponent;
use Modules\Payroll\Entities\SalaryEmployeeMonthly;
use Modules\Payroll\Entities\SalaryStructure;
use Redirect;
use Session;
use Validator;
use Modules\Finance\Entities\FinancialAccount;
use Modules\Finance\Entities\Ledger;
use Modules\Finance\Entities\Group;
use Modules\Finance\Entities\Entries;
use Modules\Finance\Entities\EntriesItem;
class SalaryMonthlyController extends Controller
{


    private $cashHead = "cash";
    private $data;
    private $loanHead = "loan";
    private $pfHead = "provident";
    private $salaryHead = "salary";
    private $financialAccount;
    private $ledger;
    private $group;
    private $entries;
    private $entriesItem;

    public function __construct( FinancialAccount $financialAccount, Ledger $ledger, Group $group, EntriesItem $entriesItem, Entries $entries) {
        $this->financialAccount       = $financialAccount;
        $this->ledger       = $ledger;
        $this->group       = $group;
        $this->entries       = $entries;
        $this->entriesItem       = $entriesItem;
    }


    private function headToId($code){
        $activeAccountId= $this->financialAccount->getActiveAccount();
        $accChart =$this->ledger->where('code',$code)->where('account_id',$activeAccountId)->first(['id']);
        return $accChart['id'];
    }

    private function voucherId($vType){
        $vt = explode(' ----- ',$vType);

        $voucherType = AccVoucherType::where('voucher_code',$vt[0])
            ->where('voucher_name',$vt[1])
            ->get(['id']);
        return $vti = $voucherType[0]->id;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $empMonthlyDedAllo =  EmpMonthlyDedAllo::where([['company_id', institution_id()],['brunch_id', campus_id()]])->get();
        return view('payroll::pages.salaryMonthlyDedAllo.salary_monthly_ded_allo',compact('empMonthlyDedAllo'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $alloSalComp = SalaryComponent::where([['amount_type', null],['type', 'A']])->where([['company_id', institution_id()],['brunch_id', campus_id()]])->get();
        $dedSalComp = SalaryComponent::where([['amount_type', null],['type', 'D']])->where([['company_id', institution_id()],['brunch_id', campus_id()]])->get();
        $emp = EmployeeInformation::where('deleted_at',null)->where([['institute_id', institution_id()],['campus_id', campus_id()]])->get();
        $salaryStructure = SalaryStructure::where([['company_id', institution_id()],['brunch_id', campus_id()]])->get();
        return view('payroll::pages.salaryMonthlyDedAllo.salary_monthly_ded_allo_create',
            compact('emp','salaryStructure','alloSalComp','dedSalComp'));
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function store(Request $request)
    {
        return $request->all();
        $validator = Validator::make($request->all(), [
            'employee' => 'required',
            'structureType' => 'required',
            'maxValI' => 'required',
            'maxValII' => 'required',
            'effectiveMonth' => 'required',
            'effectiveYear' => 'required',
        ]);

        if ($validator->passes()){
            // Start transaction
            DB::beginTransaction();
            $i = $request->maxValI;
            $ii = $request->maxValII;
            try{
                if( $i>0) {
                    for ($i_ = 1; $i_ <= $i; $i_++) {
                        $salComp = 'salComp_' . $i_;
                        $salComp = $request->$salComp;
                        $salaryAmtVal = 'salaryAmtVal_' . $i_;
                        $salaryAmtVal = $request->$salaryAmtVal;
                        $percent = 'percent_' . $i_;
                        $percent = ($request->$percent == 'P') ? 'P' : '';

                        if (!empty($salComp) && !empty($salaryAmtVal)) {
                            $empMonthlyDedAllo = new EmpMonthlyDedAllo();
                            $empMonthlyDedAllo->employee_id = $request->employee;
                            $empMonthlyDedAllo->salary_type = $request->structureType;
                            $empMonthlyDedAllo->component_id = $salComp;
                            $empMonthlyDedAllo->amount = $salaryAmtVal;
                            $empMonthlyDedAllo->percent = $percent;
                            $empMonthlyDedAllo->effective_month = $request->effectiveMonth;
                            $empMonthlyDedAllo->effective_year = $request->effectiveYear;
                            $empMonthlyDedAllo->company_id = institution_id();
                            $empMonthlyDedAllo->brunch_id = campus_id();
                            $empMonthlyDedAllo->save();
                        }else{
                            $empMonthlyDedAllo = 1;
                        }
                    }
                }else{
                    $empMonthlyDedAllo = 1;
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
                            $empMonthlyDedAllo2 = new EmpMonthlyDedAllo();
                            $empMonthlyDedAllo2->employee_id = $request->employee;
                            $empMonthlyDedAllo2->salary_type = $request->structureType;
                            $empMonthlyDedAllo2->component_id = $salComp;
                            $empMonthlyDedAllo2->amount = $salaryAmtVal;
                            $empMonthlyDedAllo2->percent = $percent;
                            $empMonthlyDedAllo2->effective_month = $request->effectiveMonth;
                            $empMonthlyDedAllo2->effective_year = $request->effectiveYear;
                            $empMonthlyDedAllo2->company_id = institution_id();
                            $empMonthlyDedAllo2->brunch_id = campus_id();
                            $empMonthlyDedAllo2->save();
                        }else{
                            $empMonthlyDedAllo2 = 1;
                        }
                    }
                }else{
                    $empMonthlyDedAllo2 = 1;
                }
            }catch (ValidationException $e) {
                DB::rollback();
                return redirect()->back()->withErrors($e->getErrors())->withInput();
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }
            // checking
            if ($empMonthlyDedAllo || $empMonthlyDedAllo2){
                //End transaction
                DB::commit();
                Session::flash('success', 'Employee Monthly Deduction Allowance added.');
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
        $empMonDedAllo = EmpMonthlyDedAllo::where('id',$request->id)->where([['company_id', institution_id()],['brunch_id', campus_id()]])->first();
        return view('payroll::pages.salaryMonthlyDedAllo.salary_monthly_ded_allo_view',compact('empMonDedAllo'));
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
        $empMonthlyDedAllo = EmpMonthlyDedAllo::FindOrFail($id);

        // checking
        if ($empMonthlyDedAllo) {
            $empMonthlyDedAlloData = EmpMonthlyDedAllo::where('id',$id)->where([['company_id', institution_id()],['brunch_id', campus_id()]])->first();
            $date1=date_create($empMonthlyDedAlloData->created_at);
            $date2=date_create(date('Y-m-d'));
            $diff=date_diff($date1,$date2);
            if($diff->format("%a") < 15){
                $empMonthlyDedAlloDelete = $empMonthlyDedAllo->delete();
            }
            // checking
            if (isset($empMonthlyDedAlloDelete)){
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

    public function monthlySalaryCalc(){
        $date = date('Y-m-t');
        $dateFirst = date('Y-m-1');

        $emp_salary_assign = new EmpSalaryAssign(); $emp_salary_assign = $emp_salary_assign->empSalAssign();
        $salary_component = SalaryComponent::where(function ($q){
            $q->where('amount_type', null);
            $q->orwhere('amount_type','B');
        })->where([['company_id', institution_id()],['brunch_id', campus_id()]])->get();

        $salary_ot = SalaryComponent::where('amount_type','OT')->where([['company_id', institution_id()],['brunch_id', campus_id()]])->get();
        $salary_ln = SalaryComponent::where('amount_type','LN')->where([['company_id', institution_id()],['brunch_id', campus_id()]])->get();
        $salary_pf = SalaryComponent::where('amount_type','PF')->where([['company_id', institution_id()],['brunch_id', campus_id()]])->get();

        $salCalc = SalaryEmployeeMonthly::where('effective_date','>=',$dateFirst)
            ->where('effective_date','<=',$date)
            ->where([['company_id', institution_id()],['brunch_id', campus_id()]])
            ->get();

        if(count($salCalc) > 0){
            return view('payroll::pages.salaryMonthlyCalc.salary_monthly_emp_show',compact('emp_salary_assign','salary_component','salary_ot','salary_ln','salary_pf'));
        }else{
            $month = date('m',strtotime('-1 month'));
            $year =  date('Y',strtotime('-1 month'));
              $salary_monthly_assign = EmpMonthlyDedAllo::where('effective_month',$month)->where('effective_year',$year)->where([['company_id', institution_id()],['brunch_id', campus_id()]]);

//           return $emp_salary_assign;
            return view('payroll::pages.salaryMonthlyCalc.salary_monthly_calc',compact('emp_salary_assign','salary_component','salary_ot','salary_ln','salary_pf','salary_monthly_assign'));
        }
    }

    public function monthlySalaryCalcStore(Request $request)
    {
//        return   $activeAccountId= $this->financialAccount->getActiveAccount();
        if(count($request->empId) == 0){
            Session::flash('warning', 'Employee List empty.');
            return redirect()->back();
        }
        DB::beginTransaction();
        try {
            $date = date('Y-m-t');
            $dateFirst = date('Y-m-1');
            $salCalc = SalaryEmployeeMonthly::where('effective_date', '>=', $dateFirst)->where('effective_date', '<=', $date)->get();

            if (count($salCalc) == 0) {
                $effective_date = $request->date;
                $salaryComponents = SalaryComponent::get();
                foreach ($request->empId as $empId) {
                    $salary_structure = $request->salStrId[$empId];
                    foreach ($salaryComponents as $data) {
                        if (!empty($request->empSalComAmount[$empId][$data->id])) {
                            $salary_component_id = $data->id;
                            $amount = $request->empSalComAmount[$empId][$data->id];

                            $SalaryEmployeeMonthly = new SalaryEmployeeMonthly();
                            $SalaryEmployeeMonthly->employee_id = $empId;
                            $SalaryEmployeeMonthly->assign_id = $salary_structure;
                            $SalaryEmployeeMonthly->component_id = $salary_component_id;
                            $SalaryEmployeeMonthly->amount = $amount;
                            $SalaryEmployeeMonthly->effective_date = $effective_date;
                            $SalaryEmployeeMonthly->company_id = institution_id();
                            $SalaryEmployeeMonthly->brunch_id = campus_id();
                            $SalaryEmployeeMonthly->save();
                        }
                    }
                }

                $cashHeadId = $this->headToId('cash');

                //voucherDate
                $voucherDate = date('Y-m-d');
                   if (!empty($this->loanHead) && !(empty($request->totalLone))) {
                    $debitLedgerId = $cashHeadId;
                    $creditLedgerId = $this->headToId($this->loanHead);
                    $notes = "Loan Collection Receive Voucher.";
                    $this->accountingEntries($voucherDate,$request->totalLone,$debitLedgerId,$creditLedgerId,$notes,1);

                }
                if (!empty($this->pfHead) && !(empty($request->totalPf))) {
                    $debitLedgerId = $cashHeadId;
                    $creditLedgerId = $this->headToId($this->pfHead);
                    $notes = "Loan Collection Receive Voucher.";
                    $this->accountingEntries($voucherDate,$request->totalPf,$debitLedgerId,$creditLedgerId,$notes,1);


                }
                if (!empty($this->salaryHead) && !(empty($request->totalEmpSal))) {
                    $notes = "Salary Giving Payment Voucher.";
                     $creditLedgerId= $cashHeadId;
                    $debitLedgerId = $this->headToId($this->salaryHead);
                    $notes = "Loan Collection Receive Voucher.";
                    $this->accountingEntries($voucherDate,$request->totalEmpSal,$debitLedgerId,$creditLedgerId,$notes,2);
                }
            }
        }catch (ValidationException $e){
            DB::rollback();
            return redirect()->back()->withErrors($e->getErrors())->withInput();
        } catch (\Exception $e){
            DB::rollback();
            throw $e;
        }
        //dd('azad');
        DB::commit();
        Session::flash('success', 'Calculation Done');
        return redirect()->back();
    }

    public function empSalaryAll(){
        $month=date('m',strtotime('-0 month'));
        $year = date('Y',strtotime('-0 month'));
        $salaryEmployeeMonthly = SalaryEmployeeMonthly::allEmpSalSummery($month,$year);
        return view('payroll::pages.salaryMonthlyCalc.salary_employee_all',compact('salaryEmployeeMonthly','month','year'));
    }

    public function empSalaryMonthly(Request $request){
        $month = $request->month;
        $year = $request->year;
        $salaryEmployeeMonthly = SalaryEmployeeMonthly::allEmpSalSummery($month,$year);
        return view('payroll::pages.salaryMonthlyCalc.salary_employee_monthly',compact('salaryEmployeeMonthly','month','year'));
    }

    public function empSalaryDetails(Request $request){
        $month = $request->month;
        $year = $request->year;
        $date = $year.'-'.$month.'-31';
        $dateFirst = $year.'-'.$month.'-1';
        $empId = $request->id;
        $salaryComponents = SalaryComponent::where([['company_id', institution_id()],['brunch_id', campus_id()]])->get();
        $salCalc = SalaryEmployeeMonthly::where('employee_id',$empId)->where('effective_date','>=',$dateFirst)->where('effective_date','<=',$date)->get();
        return view('payroll::pages.salaryMonthlyCalc.employee_salary_details',compact('salaryComponents','salCalc','month','year'));
    }


    public function accountingEntries($paymentDate,$amount,$debitLedgerId,$creditLedgerId,$notes,$entryType){

        /* insert entry data array to entries table - DB1 */
        $entriesObj=new $this->entries;
        $entriesObj->account_id= $this->financialAccount->getActiveAccount();
        $entriesObj->tag_id='22';
        $entriesObj->number='333';
        $entriesObj->entrytype_id=$entryType;
        $entriesObj->date=$paymentDate;
        $entriesObj->dr_total=$amount;
        $entriesObj->cr_total=$amount;
        $entriesObj->notes=$notes;
        $saveEntries=$entriesObj->save();

        // if entry data is inserted
        if ($saveEntries)
        {
            $insert_id = $entriesObj->id; // get inserted entry id

            //create Debit Entries
            $entriesItemObj=new  $this->entriesItem;
            $entriesItemObj->entries_id=$entriesObj->id;
            $entriesItemObj->ledger_id=$debitLedgerId;
            $entriesItemObj->dc='D';
            $entriesItemObj->amount=$amount;
            $entriesItemObj->narration=$notes;
            $entriesItemObj->save();

            //create Credit Entries
            $entriesItemObj=new  $this->entriesItem;
            $entriesItemObj->entries_id=$entriesObj->id;
            $entriesItemObj->ledger_id=$creditLedgerId;
            $entriesItemObj->dc='C';
            $entriesItemObj->amount=$amount;
            $entriesItemObj->narration=$notes;
            $entriesItemObj->save();
        }


    }


}