<?php

namespace Modules\Accounts\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Accounts\Entities\AccountsTransactionModel;
use Modules\Accounts\Entities\SubsidiaryCalculationModel;
use App\UserVoucherApprovalModel;
use Modules\Accounts\Entities\AccountsVoucherApprovalLogModel;
use Modules\Accounts\Entities\AccountsConfigurationModel;
use Modules\Accounts\Entities\ChartOfAccount;
use Modules\Setting\Entities\Campus;
use Modules\Setting\Entities\Institute;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\AccountsHelper;
use App\User;
use Illuminate\Validation\Rule;
use DateTime;

class ContraVoucherController extends Controller
{

    use AccountsHelper;

    public function __construct(Request $request)
    {
        $this->middleware(function ($request, $next) {
            $user_id = Auth::user()->id;
            $this->campus_id = self::getCampusId();
            $this->institute_id = self::getInstituteId();
            $this->acc_code_type = self::getAccCodeType();
            $this->acc_code_col = ($this->acc_code_type=='Manual')?'manual_account_code':'account_code';
            return $next($request);
        });

        date_default_timezone_set('Asia/Dhaka');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $listPerPage = $request->input('listPerPage');
        $search_key = $request->input('search_key');
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $status = $request->status;
        $order = $request->input('order');
        $sort = $request->input('sort');
        if($sort=='id') $sort='accounts_transaction.id';

        if(!empty($from_date)){
            $from_date = DateTime::createFromFormat('d/m/Y', $from_date)->format('Y-m-d');
        }
        if(!empty($to_date)){
            $to_date = DateTime::createFromFormat('d/m/Y', $to_date)->format('Y-m-d');
        }

        $paginate_data_query = AccountsTransactionModel::module()
            ->select('accounts_transaction.*',DB::raw("DATE_FORMAT(trans_date,'%d/%m/%Y') AS trans_date_formate"))
            ->where('voucher_type',4)
            ->when($status, function($query, $status){
                if($status=='p') $status=0;
                $query->where('accounts_transaction.status',$status);
            })
            ->when($from_date, function($query, $from_date){
                $query->where('accounts_transaction.trans_date','>=',$from_date);
            })
            ->when($to_date, function($query, $to_date){
                $query->where('accounts_transaction.trans_date','<=',$to_date);
            })
            ->where(function($query)use($search_key){
                if(!empty($search_key)){
                    $query->where('accounts_transaction.voucher_no','LIKE','%'.$search_key.'%')
                    ->orWhere('accounts_transaction.amount','LIKE','%'.$search_key.'%');
                }
            })
            ->orderBy($sort,$order);

        $paginate_data = ($listPerPage=='All')? $paginate_data_query->get():$paginate_data_query->paginate($listPerPage);

        if(count($paginate_data)>0){
            $auth_user_id = Auth::user()->id;
            $UserVoucherApprovalLayer = UserVoucherApprovalModel::module()->valid()->where('approval_name', 'contra_voucher')->where('is_role', 0)->get();
            $step=1; $approval_access=true; $approval_log_group = []; $approval_step_log=[];
            if(count($UserVoucherApprovalLayer)>0){
                $UserVoucherApprovalKeyBy = $UserVoucherApprovalLayer->keyBy('approval_id')->all();
                if(array_key_exists($auth_user_id, $UserVoucherApprovalKeyBy)){
                    $step = $UserVoucherApprovalKeyBy[$auth_user_id]->step;
                }else{
                   $approval_access=false; 
                }
            }
            //dd($approval_access);
            $voucher_ids = $paginate_data->pluck('id')->all(); 
            // for approval text
            $approval_log_group = AccountsVoucherApprovalLogModel::module()
                ->join('users', 'users.id', '=', 'accounts_voucher_approval_log.approval_id')
                ->select('accounts_voucher_approval_log.*', 'users.name')
                ->where('voucher_type', 4)
                ->where('is_role', 0)
                ->whereIn('voucher_id', $voucher_ids)
                ->orderBy('accounts_voucher_approval_log.approval_layer', 'asc')
                ->get()->groupBy('voucher_id')->all();
            // check if his step is approval or not
            $approval_step_log = AccountsVoucherApprovalLogModel::module()
                ->where('voucher_type', 4)
                ->whereIn('voucher_id', $voucher_ids)
                ->where('approval_layer', $step)
                ->where('is_role', 0)
                ->where('approval_id', $auth_user_id)
                ->get()->keyBy('voucher_id')->all();

            foreach ($paginate_data as $v){
                if($approval_access && $v->approval_level==$step && !array_key_exists($v->id, $approval_step_log)){
                    $v->has_approval = 'yes';
                }else{
                    $v->has_approval = 'no';
                }
                if(array_key_exists($v->id, $approval_log_group)){
                    $approved_by = [];
                    foreach ($approval_log_group[$v->id] as $log) {
                        $status = '';
                        if($log->action_status==1){
                            $status = 'approved';
                        }else if($log->action_status==2){
                            $status = 'reject';
                        }
                        $approved_by[] = "Step ".$log->approval_layer.' '.$status.' By '.$log->name.' on '.$log->date;
                    }
                    $v->approved_text = implode(" , ",$approved_by);
                }else{
                    $v->approved_text = '';
                }
            }
        }

        $data['paginate_data'] = $paginate_data;
        return response()->json($data);
    }

    public function page(){
        return view('accounts::contra-voucher.contra-voucher');
    }


    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $voucherInfo = self::checkAccVoucher(6);
        if($voucherInfo['voucher_conf']){
            $cash_bank_ledger = self::cashBankLedgerId();
            $ledgerClosingBalanceData = self::ledgerClosingBalance();
            $ledger = self::getAccLedger($this->acc_code_type,$cash_bank_ledger,[]);

            $data['ledger'] =  self::ledgerClosingDataFormate($ledger, $ledgerClosingBalanceData, ['id'=>0, 'accountCode'=>'Select ledger','accountCodeView'=>'Select ledger']);
            
            $data['formData'] = ['voucher_no'=>$voucherInfo['voucher_no'],'voucher_config_id'=>$voucherInfo['voucher_config_id'],'auto_voucher'=>$voucherInfo['auto_voucher'], 'trans_date'=>date('d/m/Y'),'voucherDebitData'=>[],'voucherCreditData'=>[], 'itemAdded'=>'no'];
            $data['current_date'] = date('d/m/Y');
        }else{
            $data = ['status'=>0, 'message'=>"Setup voucher configuration first"];
        }
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $id = $request->id;
        $validated = $request->validate([
            'voucher_no' => 'required|max:100',
            'trans_date' => 'required|date_format:d/m/Y',
            'remarks' => 'required|max:255',
        ]);
        $trans_date = \DateTime::createFromFormat('d/m/Y', $request->trans_date)->format('Y-m-d');
        $voucherDebitData = $request->voucherDebitData;
        $voucherCreditData = $request->voucherCreditData;
        $totalDebit = $request->totalDebit;
        $totalCredit = $request->totalCredit;

        if(count($voucherDebitData)>0 && count($voucherCreditData)>0 && $totalDebit==$totalCredit){
            DB::beginTransaction();
            try {
                $flag = true; $msg='';
                $trans_date_time = date('Y-m-d H:i:s');
                $manual_ref_no = $request->manual_ref_no;
                if(!empty($id)){
                    $acc_transaction_id = $id;
                    $accTransaction = AccountsTransactionModel::findOrFail($acc_transaction_id);
                    if($accTransaction->status==0){
                        if($trans_date == $accTransaction->trans_date){
                            $trans_date_time = $accTransaction->trans_date_time;
                        }else{
                            $trans_date_time = $trans_date.' '.date('H:i:s');
                            // update accounts subsidiary calculation
                            SubsidiaryCalculationModel::module()->where('transaction_id', $id)->update([
                                'trans_date'=>$trans_date,
                                'trans_date_time'=>$trans_date_time
                            ]);
                        }

                        $accTransaction->update([
                            'trans_date'=>$trans_date,
                            'trans_date_time'=>$trans_date_time,
                            'manual_ref_no'=>$manual_ref_no,
                            'amount'=>$totalDebit,
                            'remarks'=>$request->remarks
                        ]);

                        $voucher_details_db = $accTransaction->details;
                        $vou_dtl_db_ids = $voucher_details_db->pluck('id')->all();
                        $vou_debit_ids = collect($voucherDebitData)->pluck('id')->all();
                        $vou_credit_ids = collect($voucherCreditData)->pluck('id')->all();
                        $vou_dtl_ids = collect($vou_debit_ids)->merge($vou_credit_ids)->all();
                        
                        $vou_dtl_ids_diff = collect($vou_dtl_db_ids)->diff($vou_dtl_ids)->all();

                        // details data delete 
                        if(count($vou_dtl_ids_diff)>0){
                            $particular_sub_ledger=[];
                            foreach($vou_dtl_ids_diff as $del_id){
                                $subsidiary_info = SubsidiaryCalculationModel::find($del_id);
                                if(!empty($subsidiary_info->particular_sub_ledger_id)){
                                    $particular_sub_ledger[] = $subsidiary_info->sub_ledger;
                                }
                                $subsidiary_info->delete();
                            }
                            // subsidiary calculation particular update
                            if(count($particular_sub_ledger)>0){
                                SubsidiaryCalculationModel::where('transaction_id',$id)->whereIn('particular_sub_ledger_id', $particular_sub_ledger)->update(['particular_sub_ledger_id'=>NULL]);
                            }

                        }

                    }else{
                        $flag = false; 
                        $msg = 'Sorry! voucher already approved';
                    }
                }
                else{
                    $auto_voucher = $request->auto_voucher;  // voucher type 

                    if($auto_voucher){
                        $voucher_info = self::getAccVoucher(4);
                        $voucher_no = $voucher_info['voucher_no'];
                        if($voucher_no){
                            $voucher_int_no = $voucher_info['voucher_int_no'];
                            $voucher_config_id = $voucher_info['voucher_config_id']; 
                        }else{
                            $flag = false;
                            $msg = $voucher_info['msg'];
                        }

                    }else{ // menual voucher 
                        $checkVoucher = AccountsTransactionModel::module()->where('voucher_no', $request->voucher_no)->first();
                        if(empty($checkVoucher)){
                            $voucher_no = $request->voucher_no;
                            $voucher_int_no = 0;
                            $voucher_config_id = $request->voucher_config_id;
                        }else{
                           $flag=false;
                           $msg = "Voucher no already exists";   
                        }
                    }
                    if($flag){
                        $save = AccountsTransactionModel::create([
                            'voucher_no'=>$voucher_no,
                            'voucher_int_no'=>$voucher_int_no,
                            'voucher_config_id'=>$voucher_config_id,
                            'trans_date'=>$trans_date,
                            'trans_date_time'=>$trans_date_time,
                            'amount'=>$totalDebit,
                            'voucher_type'=>4,
                            'manual_ref_no'=>$manual_ref_no,
                            'remarks'=>$request->remarks
                        ]);
                        $acc_transaction_id = $save->id;
                    }
                    
                }
                // details
                if($flag){
                    $dr_ledger_ids = collect($voucherDebitData)->pluck('dr_sub_ledger')->all(); 
                    $cr_ledger_ids = collect($voucherCreditData)->pluck('cr_sub_ledger')->all(); 
                    $merge_ledger_ids = collect($dr_ledger_ids)->merge($cr_ledger_ids)->all();
                    $ledger_ids = collect($merge_ledger_ids)->unique()->values()->all();
                    $chartOfAccounts = ChartOfAccount::whereIn('id',$ledger_ids)->get()->keyBy('id')->all();

                    // debit data entry
                    foreach($voucherDebitData as $v){
                        if($v['id']==0){
                            SubsidiaryCalculationModel::create([
                                'particular_sub_ledger_id'=>(!empty($v['cr_sub_ledger']) && isset($v['cr_sub_ledger']))?$v['cr_sub_ledger']:NULL,
                                'trans_date'=>$trans_date,
                                'trans_date_time'=>$trans_date_time,
                                'sub_ledger'=>$v['dr_sub_ledger'],
                                'increase_by'=>$chartOfAccounts[$v['dr_sub_ledger']]->increase_by,
                                'debit_amount'=>$v['dr_amount'],
                                'credit_amount'=>0,
                                'transaction_id'=>$acc_transaction_id,
                                'tras_ledger_type'=>'debit',
                                'remarks'=>(!empty($v['narration']))?$v['narration']:''
                            ]);
                        }
                    }
                    // credit data entry
                    foreach($voucherCreditData as $v){
                        if($v['id']==0){
                            SubsidiaryCalculationModel::create([
                                'particular_sub_ledger_id'=>(!empty($v['dr_sub_ledger']) && isset($v['dr_sub_ledger']))?$v['dr_sub_ledger']:NULL,
                                'trans_date'=>$trans_date,
                                'trans_date_time'=>$trans_date_time,
                                'sub_ledger'=>$v['cr_sub_ledger'],
                                'increase_by'=>$chartOfAccounts[$v['cr_sub_ledger']]->increase_by,
                                'debit_amount'=>0,
                                'credit_amount'=>$v['cr_amount'],
                                'transaction_id'=>$acc_transaction_id,
                                'tras_ledger_type'=>'credit',
                                'remarks'=>(!empty($v['narration']))?$v['narration']:''
                            ]);
                        }
                    }
                    $output = ['status'=>1,'message'=>'Contra voucher successfully saved'];
                }else{
                    $output = ['status'=>0,'message'=>$msg];
                }
                
                DB::commit();
            } catch (Throwable $e) {
                DB::rollback();
                throw $e;
            }
        }else{
            if(count($voucherDebitData)<1 || count($voucherCreditData)<1){
                $output = ['status'=>0,'message'=>"Please add at least one voucher transaction"];
            }else{
               $output = ['status'=>0,'message'=>"Total debit amount and total credit amount should be same"]; 
            }
        }
        return response()->json($output);
    }


    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {

        $voucherInfo = AccountsTransactionModel::findOrFail($id);
        $voucherInfo->trans_date = DateTime::createFromFormat('Y-m-d', $voucherInfo->trans_date)->format('d/m/Y');
        $voucherDebitData = SubsidiaryCalculationModel::where('transaction_id', $id)->where('tras_ledger_type', 'debit')->get();
        $voucherCreditData = SubsidiaryCalculationModel::where('transaction_id', $id)->where('tras_ledger_type', 'credit')->get(); 

        $dr_ledger_ids = $voucherDebitData->pluck('sub_ledger')->all();
        $cr_ledger_ids = $voucherCreditData->pluck('sub_ledger')->all();
        $merge_ledger_ids = collect($dr_ledger_ids)->merge($cr_ledger_ids)->all();
        $ledger_ids = collect($merge_ledger_ids)->unique()->values()->all();
        $chartOfAccounts = ChartOfAccount::whereIn('id',$ledger_ids)->get()->keyBy('id')->all();
        $totalDebit = 0;
        foreach($voucherDebitData as $v){
            $totalDebit+=$v->debit_amount;
            $dr_accounts = $chartOfAccounts[$v->sub_ledger];
            $v->dr_accountCode = '['.$dr_accounts->{$this->acc_code_col}.'] '.$dr_accounts->account_name;
            $v->dr_amount = $v->debit_amount;
            $v->dr_sub_ledger = $v->sub_ledger;
            $v->cr_sub_ledger = (!empty($v->particular_sub_ledger_id))?$v->particular_sub_ledger_id:0;
        }
        $totalCredit = 0;
        foreach($voucherCreditData as $v){
            $totalCredit+=$v->credit_amount;
            $cr_accounts = $chartOfAccounts[$v->sub_ledger];
            $v->cr_accountCode = '['.$cr_accounts->{$this->acc_code_col}.'] '.$cr_accounts->account_name;
            $v->cr_amount = $v->credit_amount;
            $v->cr_sub_ledger = $v->sub_ledger;
            $v->dr_sub_ledger = (!empty($v->particular_sub_ledger_id))?$v->particular_sub_ledger_id:0;
        }
        $voucherInfo->voucherDebitData = $voucherDebitData;
        $voucherInfo->voucherCreditData = $voucherCreditData;
        $voucherInfo->totalDebit = $totalDebit;
        $voucherInfo->totalCredit = $totalCredit;
        $data['formData'] = $voucherInfo;
        $data['formData']['institute'] = Institute::findOrFail(self::getInstituteId())->institute_name;
        $data['formData']['campus'] = Campus::findOrFail(self::getCampusId())->name;

        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $ledgerClosingBalanceData = self::ledgerClosingBalance();
        $cash_bank_ledger = self::cashBankLedgerId();
        $ledger = self::getAccLedger($this->acc_code_type,$cash_bank_ledger,[]);
        $data['ledger'] =  self::ledgerClosingDataFormate($ledger, $ledgerClosingBalanceData, ['id'=>0, 'accountCode'=>'Select ledger','accountCodeView'=>'Select ledger']);
        $voucherInfo = AccountsTransactionModel::findOrFail($id);
        $voucherDebitData = SubsidiaryCalculationModel::where('transaction_id', $id)->where('tras_ledger_type', 'debit')->get();
        $voucherCreditData = SubsidiaryCalculationModel::where('transaction_id', $id)->where('tras_ledger_type', 'credit')->get(); 

        $dr_ledger_ids = $voucherDebitData->pluck('sub_ledger')->all();
        $cr_ledger_ids = $voucherCreditData->pluck('sub_ledger')->all();
        $merge_ledger_ids = collect($dr_ledger_ids)->merge($cr_ledger_ids)->all();
        $ledger_ids = collect($merge_ledger_ids)->unique()->values()->all();
        $chartOfAccounts = ChartOfAccount::whereIn('id',$ledger_ids)->get()->keyBy('id')->all();

        $totalDebit = 0;
        foreach($voucherDebitData as $v){
            $totalDebit+=$v->debit_amount;
            $dr_accounts = $chartOfAccounts[$v->sub_ledger];
            $v->dr_accountCode = '['.$dr_accounts->{$this->acc_code_col}.'] '.$dr_accounts->account_name;
            $v->dr_amount = $v->debit_amount;
            $v->dr_sub_ledger = $v->sub_ledger;
            $v->cr_sub_ledger = (!empty($v->particular_sub_ledger_id))?$v->particular_sub_ledger_id:0;
            $v->narration = $v->remarks;
        }
        $totalCredit = 0;
        foreach($voucherCreditData as $v){
            $totalCredit+=$v->credit_amount;
            $cr_accounts = $chartOfAccounts[$v->sub_ledger];
            $v->cr_accountCode = '['.$cr_accounts->{$this->acc_code_col}.'] '.$cr_accounts->account_name;
            $v->cr_amount = $v->credit_amount;
            $v->cr_sub_ledger = $v->sub_ledger;
            $v->dr_sub_ledger = (!empty($v->particular_sub_ledger_id))?$v->particular_sub_ledger_id:0;
            $v->narration = $v->remarks;
        }
        $voucherInfo->voucherDebitData = $voucherDebitData;
        $voucherInfo->voucherCreditData = $voucherCreditData;
        $voucherInfo->totalDebit = $totalDebit;
        $voucherInfo->totalCredit = $totalCredit;
        $voucherInfo->itemAdded = 'yes';
        $voucherInfo->auto_voucher = true;
        $trans_date = DateTime::createFromFormat('Y-m-d', $voucherInfo->trans_date)->format('d/m/Y');
        $voucherInfo->trans_date = $trans_date;

        $data['current_date'] = date('d/m/Y');
        $data['formData'] = $voucherInfo;
        return response()->json($data);
    }


    public function voucherApproval($id)
    {
        DB::beginTransaction();
        try{
            $auth_user_id = Auth::user()->id;
            $approvalData = AccountsTransactionModel::module()->find($id);
            if(!empty($approvalData)){
                if($approvalData->status==0){
                    $details = $approvalData->details;
                    $approval_info = self::getApprovalInfo('contra_voucher');
                    $step = $approval_info['step'];
                    $approval_access = $approval_info['approval_access'];
                    $last_step = $approval_info['last_step'];
                    if($approval_access && $approvalData->approval_level==$step){
                        if($step==$last_step){
                            $updateData = [
                                'status'=>1,
                                'approval_level'=>$step+1
                            ];
                        }else{ // end if($step==$last_step){
                            $updateData = [
                                'approval_level'=>$step+1
                            ];
                        }
                        $approvalData->update($updateData); 
                        // details 
                        foreach($details as $d){
                            SubsidiaryCalculationModel::find($d->id)->update($updateData);
                        }
                        AccountsVoucherApprovalLogModel::create([
                            'date'=>date('Y-m-d H:i:s'),
                            'voucher_id'=>$approvalData->id,
                            'voucher_type'=>4,
                            'approval_id'=>$auth_user_id,
                            'approval_layer'=>$step,
                            'action_status'=>1,
                            'institute_id'=>self::getInstituteId(),
                            'campus_id'=>self::getCampusId(),
                        ]);
                        DB::commit();
                        $output = ['status'=>1, 'message'=>'Journal voucher successfully approved'];
                    }else{ // end if($approval_access && $approvalData->approval_level==$step){
                        $output = ['status'=>0, 'message'=>'Sory you have no approval'];    
                    }
                }else{ // end if($approvalData->status==0)
                    if($approvalData->status==2){
                        $output = ['status'=>0, 'message'=>'Journal voucher already reject'];
                    }else{
                        $output = ['status'=>0, 'message'=>'Journal voucher already approved'];  
                    }
                }
            }else{   // end if(!empty($approvalData))
                $output = ['status'=>0, 'message'=>'Journal Voucher not found'];
            }
        } catch (Throwable $e) {
            DB::rollback();
            throw $e;
        } 
        return response()->json($output); 
    }


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {

        DB::beginTransaction();
        try{
            $deleteData = AccountsTransactionModel::findOrFail($id);
            if($deleteData->status==0){
                $voucherDetails = $deleteData->details;
                foreach ($voucherDetails as $delInfo){
                    SubsidiaryCalculationModel::find($delInfo->id)->delete();
                }
                $deleteData->delete();
                $output = ['status'=>1,'message'=>'Contra voucher deleted successfully'];
                DB::commit();
            }else{
                $output = ['status'=>1,'message'=>'Contra voucher deleted successfully'];  
            }
        } catch (Throwable $e) {
            DB::rollback();
            throw $e;
        }  
        return response()->json($output);
    }


}
