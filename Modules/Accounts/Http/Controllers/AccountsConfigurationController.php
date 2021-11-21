<?php

namespace Modules\Accounts\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Modules\Accounts\Entities\ChartOfAccount;
use Modules\Accounts\Entities\ChartOfAccountsConfigModel;
use Modules\Accounts\Entities\AccountsConfigurationModel;
use App\Helpers\AccountsHelper;


class AccountsConfigurationController extends Controller
{

    use AccountsHelper;
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $checkAccountsConfig = AccountsConfigurationModel::module()->get();
        $user_id = Auth::user()->id;
        $created_at = date('Y-m-d H:i:s');
        $institute_id = self::getInstituteId();
        $campus_id = self::getCampusId();
        $particulars = $checkAccountsConfig->pluck('particular')->all();
        if(count($particulars)>0){
            $accountConfigStaticData = DB::table('accounts_configuration_static_data')->whereNotIn('particular', $particulars)->get();
        }else{
            $accountConfigStaticData = DB::table('accounts_configuration_static_data')->get();
        }
        if(count($accountConfigStaticData)>0){
            DB::beginTransaction();
            try {
                $acc_config_data = [];
                foreach ($accountConfigStaticData as $v) {
                    $acc_config_data[] = ['order_no'=>$v->order_no, 'label_name'=>$v->label_name,'display_label_name'=>$v->display_label_name,'particular'=>$v->particular,'particular_name'=>$v->particular_name,'account_type'=>$v->account_type,'increase_by'=>$v->increase_by,'institute_id'=>$institute_id,'campus_id'=>$campus_id,'created_by'=>$user_id,'created_at'=>$created_at];
                }
                //dd($acc_config_data);
                AccountsConfigurationModel::insert($acc_config_data);
                DB::commit();
            } catch (Throwable $e) {
                DB::rollback();
                throw $e;
            }  
        }

        $acc_config = AccountsConfigurationModel::module()->select('label_name', 'display_label_name', 'order_no')->orderBy('order_no', 'asc')->get();
        $accounts_config=[]; $check_array=[];
        foreach($acc_config as $v){
            if(!array_key_exists($v->label_name, $check_array)){
                $check_array[$v->label_name] = $v;
                $accounts_config[] = $v;
            }
        }
        $data['accounts_config'] = $accounts_config;
        return view('accounts::accounts-config.accounts-config', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('accounts::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('accounts::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($label_name)
    {
        $data['accountInfo'] = AccountsConfigurationModel::module()->where('label_name',$label_name)->orderBy('order_no', 'asc')->get();
        $data['label_name'] = $label_name;
        $data['accounts'] = ChartOfAccount::module()->get()->keyBy('id');
        $data['auto_permission'] = ['vendor_ledger','customer_ledger'];
        $coaConfig = ChartOfAccountsConfigModel::module()->first();
        if(!empty($coaConfig) && $coaConfig->code_type=='Manual'){
           $data['code_type'] = $coaConfig->code_type;
        }else{
           $data['code_type'] = 'Auto'; 
        }
        $data['nature'] = ChartOfAccount::module()->where('parent_id',0)->orderBy('id', 'asc')->get(); 
        return view('accounts::accounts-config.modal.edit-accounts-config', $data);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $label_name)
    {
        // check all data 
        $flag=true; $msg = '';
        $particular_ids = $request->particular_id;
        $code_type = $request->code_type;
        $ids = $request->id; 
        foreach($particular_ids as $k=>$v){
            if(!empty($v)){
                $checkCode = DB::table('accounts_chart_of_accounts')->where('id', $v)->first();
                if(empty($checkCode)){
                    $msg = 'Invalid account code.';
                    $flag=false;
                    break;
                }
            }else{
                $msg = 'Fill all label account code.';
                $flag=false;
                break;
            }
        }
        if($flag){
            DB::beginTransaction();
            try {
                $particular_code_col = ($code_type=='Manual')?'manual_account_code':'account_code';
                foreach($particular_ids as $k=>$v){
                    $update_id = $ids[$k];
                    $checkCode = DB::table('accounts_chart_of_accounts')->where('id', $v)->first();
                    if(!empty($checkCode)){
                        AccountsConfigurationModel::where('id', $update_id)->update([
                            'particular_code'=>$checkCode->{$particular_code_col},
                            'particular_id'=>$checkCode->id,
                            'account_type'=>$checkCode->account_type,
                            'increase_by'=>$checkCode->increase_by,
                            'updated_by'=>Auth::user()->id
                        ]);
                    }
                }
                Session::flash('message', 'Accounts configuration updated successfully.');
                DB::commit();
            } catch (Throwable $e) {
                DB::rollback();
                throw $e;
            }  
        }else{
           Session::flash('errorMessage', $msg); 
        }
     
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
