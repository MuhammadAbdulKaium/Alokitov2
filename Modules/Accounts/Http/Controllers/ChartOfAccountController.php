<?php

namespace Modules\Accounts\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Modules\Accounts\Entities\ChartOfAccount;
use Modules\Accounts\Entities\SubsidiaryCalculationModel;
use Modules\Accounts\Entities\ChartOfAccountsConfigModel;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use Modules\Accounts\Entities\AccountsConfigurationModel;
use Modules\Inventory\Entities\VendorModel;
use App\Helpers\AccountsHelper;
use Illuminate\Validation\Rule;

class ChartOfAccountController extends Controller
{
    use AccountsHelper;
    public function index()
    {
        $user_id = Auth::user()->id;
        $created_at = date('Y-m-d H:i:s');
        $institute_id = self::getInstituteId();
        $campus_id = self::getCampusId();
        $checkCharOfAccounts = ChartOfAccount::module()->first();

        if(empty($checkCharOfAccounts)){
            $chartOfAccStaticData = DB::table('accounts_chart_of_accounts_static_data')->get();
            DB::beginTransaction();
            try {
                $coa_data = [];
                foreach ($chartOfAccStaticData as $v) {
                    $coa_data[] = ['account_code'=>$v->account_code, 'account_name'=>$v->account_name,'parent_id'=>$v->parent_id,'account_type'=>(!empty($v->account_type))?$v->account_type:NULL,'increase_by'=>$v->increase_by,'layer'=>$v->layer,'uid'=>$v->uid,'valid'=>$v->valid,'institute_id'=>$institute_id,'campus_id'=>$campus_id,'created_by'=>$user_id,'created_at'=>$created_at];
                }
                ChartOfAccount::insert($coa_data);
                DB::commit();
            } catch (Throwable $e) {
                DB::rollback();
                throw $e;
            }  
        }
        $nature = ChartOfAccount::module()->where('parent_id',0)->orderBy('id', 'asc')->get(); 
        $accounts = ChartOfAccount::module()->get()->keyBy('id');
        return view('accounts::chart-of-accounts.index', compact('accounts','nature'));
    }

    public function create()
    {
        $data['accounts'] = ChartOfAccount::module()
            ->where(function($query){
                $query->where('account_type','!=','ledger')
                      ->orWhere(function($q){
                          $q->whereNull('account_type');
                      });
            })
            ->get()->keyBy('id');
        $coaConfig = ChartOfAccountsConfigModel::module()->first();
        if(!empty($coaConfig) && $coaConfig->code_type=='Manual'){
           $data['code_type'] = $coaConfig->code_type;
        }else{
           $data['code_type'] = 'Auto'; 
        }
        $data['nature'] = ChartOfAccount::module()->where('parent_id',0)->orderBy('id', 'asc')->get(); 
        return view('accounts::chart-of-accounts.modal.create-group',$data);
    }

    public function chartOfAccountsConfig(){
        $data['coaConfig'] = ChartOfAccountsConfigModel::module()->first();
        $data['code_type'] = (!empty($data['coaConfig']))?$data['coaConfig']->code_type:'Auto';
        return view('accounts::chart-of-accounts.modal.chart-of-acc-config', $data);
    }

    public function chartOfAccountsConfigUpdate(Request $request){

        $id = $request->id;
        $code_type = $request->code_type;
        $data = [
            'code_type'=>$code_type,
            'institute_id'=>self::getInstituteId(),
            'campus_id'=>self::getCampusId()
        ];
        if(!empty($id)){
            ChartOfAccountsConfigModel::find($id)->update($data);
        }else{
            ChartOfAccountsConfigModel::create($data);
        }
        Session::flash('message', 'Chart of account config updated successfully.');
        return redirect()->back();
    }

    public function store(Request $request)
    {
        $institute_id = self::getInstituteId();
        $campus_id = self::getCampusId();
        $code_type = $request->code_type;
        $rules = [
            'account_type' => 'required',
            'account' => 'required',
            'account_name' => [
                'required',
                'max:255',
                Rule::unique('accounts_chart_of_accounts')->where(function ($query) use($campus_id, $institute_id) {
                    return $query->where('campus_id', $campus_id)->where('institute_id', $institute_id)->whereNull('deleted_at');
                })
            ]
        ];
        if($code_type=='Manual'){
            $rules['manual_account_code'] = [
                'required',
                'max:255',
                Rule::unique('accounts_chart_of_accounts')->where(function ($query) use($campus_id, $institute_id) {
                    return $query->where('campus_id', $campus_id)->where('institute_id', $institute_id)->whereNull('deleted_at');
                })
            ];
            $manual_account_code = $request->manual_account_code;
        }else{
            $manual_account_code = NULL;
        }
        $request->validate($rules);

        $parentAccount = ChartOfAccount::findOrFail($request->account);

        //Siblings check with different type Start
        $existingSiblings = ChartOfAccount::module()->where([
            ['parent_id', $parentAccount->id],
            ['account_type', '!=', $request['account_type']]
        ])->first();
        if ($existingSiblings) {
            Session::flash('errorMessage', 'Can not save dfferent account type at same layer!');
            return redirect()->back();
        }
        if (($parentAccount->layer + 1) > 6) {
            Session::flash('errorMessage', 'Can not make more than 6 layers!');
            return redirect()->back();
        }

        DB::beginTransaction();
        try {
            $ChartOfAccount = ChartOfAccount::create([
                'account_code' => 0,
                'manual_account_code' => $manual_account_code,
                'account_name' => $request->account_name,
                'parent_id' => 0,
                'account_type' => $request->account_type,
                'increase_by' => $parentAccount->increase_by,
                'layer' => 0,
                'uid' => 0,
                'institute_id' => self::getInstituteId(),
                'campus_id' => self::getCampusId(),
            ]);
            $sundry_creditors_config = AccountsConfigurationModel::module()->where('particular', 'sundry_creditors')->first();
            if(!empty($sundry_creditors_config)){
                $sundry_creditors_id = $sundry_creditors_config->particular_id;
            }else{
                $sundry_creditors_id = 0; 
            }
            if ($ChartOfAccount) {
                $accountCodeCredentials = $this->accountCodeGenerate($parentAccount, $request->account_type, null);
                $existingAccount = ChartOfAccount::module()->where('account_code', $accountCodeCredentials['accountCode'])->first();

                if (!$existingAccount) {
                    // auto vendor insert
                    if($sundry_creditors_id==$request->account && $request->account_type=='ledger'){
                        $vendorData = [
                            'category_id'=>1,
                            'type'=>1,
                            'name'=>$request->account_name,
                            'gl_code'=>($code_type=='Manual')?$manual_account_code:$accountCodeCredentials['accountCode'],
                            'institute_id' => self::getInstituteId(),
                            'campus_id' => self::getCampusId(),
                        ];
                        VendorModel::create($vendorData);
                    }
                    $ChartOfAccount->update([
                        'account_code' => $accountCodeCredentials['accountCode'],
                        'parent_id' => $request->account,
                        'layer' => ($parentAccount->layer + 1),
                        'uid' => $accountCodeCredentials['uid'],
                    ]);
                } else {
                    DB::rollback();
                    Session::flash('errorMessage', 'Auto Generated code duplicated!');
                    return redirect()->back();
                }
            }

            DB::commit();
            Session::flash('message', 'Chart of account created successfully.');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error creating chart of accounts.');
            return redirect()->back();
        }
    }

    public function show($id)
    {
        return view('accounts::show');
    }

    public function edit($id)
    {

        $data['account'] = ChartOfAccount::findOrFail($id);
        $coaConfig = ChartOfAccountsConfigModel::module()->first();
        if(!empty($coaConfig) && $coaConfig->code_type=='Manual'){
           $data['code_type'] = $coaConfig->code_type;
        }else{
           $data['code_type'] = 'Auto'; 
        }
        return view('accounts::chart-of-accounts.modal.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $institute_id = self::getInstituteId();
        $campus_id = self::getCampusId();
        $code_type = $request->code_type;
        $rules = [
            'account_name' => [
                'required',
                'max:255',
                Rule::unique('accounts_chart_of_accounts')->where(function ($query) use($campus_id, $institute_id) {
                    return $query->where('campus_id', $campus_id)->where('institute_id', $institute_id)->whereNull('deleted_at');
                })->ignore($id, 'id')
            ],
            'type' => 'required',
        ];
        if($code_type=='Manual'){
            $rules['manual_account_code'] = [
                'required',
                'max:255',
                Rule::unique('accounts_chart_of_accounts')->where(function ($query) use($campus_id, $institute_id) {
                    return $query->where('campus_id', $campus_id)->where('institute_id', $institute_id)->whereNull('deleted_at');
                })->ignore($id, 'id')
            ];
            $manual_account_code = $request->manual_account_code;
        }else{
            $manual_account_code = NULL;
        }
        $request->validate($rules);

        $account = ChartOfAccount::findOrFail($id);
        $auto_account_code_db = $account->account_code;
        $manual_account_code_db = $account->manual_account_code;
        $subsidaryCalculation = SubsidiaryCalculationModel::module()->where('sub_ledger', $id)->first();
        if(!empty($subsidaryCalculation) && $account->account_type != $request->type){
            Session::flash('errorMessage', 'Dependencies accounts transaction found, can not change this account type.');
            return redirect()->back();
        }

        $parentAccount = ChartOfAccount::findOrFail($account->parent_id);
        $accountCodeCredentials = $this->accountCodeGenerate($parentAccount, $request->type, $account->id);

        //Siblings check with different type Start
        $existingSiblings = ChartOfAccount::module()->where([
            ['id', '!=', $account->id],
            ['parent_id', $parentAccount->id],
            ['account_type', '!=', $request['type']]
        ])->first();
        if ($existingSiblings) {
            Session::flash('errorMessage', 'Can not save dfferent account type at same layer!');
            return redirect()->back();
        }
        //Layer Check
        if (($account->layer + 1) > 6) {
            Session::flash('errorMessage', 'Can not make more than 6 layers!');
            return redirect()->back();
        }

        if ($request->type == 'ledger') {
            $existingAccount = ChartOfAccount::module()->where('parent_id', $id)->first();
            if ($existingAccount) {
                Session::flash('errorMessage', 'This account has childs can not change the type to ledger.');
                return redirect()->back();
            }
        }

        DB::beginTransaction();
        try {
            $existingAccount = ChartOfAccount::module()->where('account_code', $accountCodeCredentials['accountCode'])->where('id', '!=', $account->id)->first();

            if (!$existingAccount) {
                // check and update vendor gl code start
                $sundry_creditors_config = AccountsConfigurationModel::module()->where('particular', 'sundry_creditors')->first();
                if(!empty($sundry_creditors_config)){
                    $sundry_creditors_id = $sundry_creditors_config->particular_id;
                }else{
                    $sundry_creditors_id = 0; 
                }
                if($sundry_creditors_id==$account->parent_id && $request->type=='ledger'){
                    VendorModel::module()->where(function($query)use($manual_account_code_db,$auto_account_code_db){
                        $query->where('gl_code',$manual_account_code_db)->orWhere('gl_code', $auto_account_code_db);
                    })->update(['gl_code'=>($code_type=='Manual')?$manual_account_code:$accountCodeCredentials['accountCode']]);
                }
                // check and update vendor gl code end
                $account->update([
                    'account_name' => $request->account_name,
                    'account_type' => $request->type,
                    'account_code' => $accountCodeCredentials['accountCode'],
                    'manual_account_code'=>($code_type=='Manual')?$manual_account_code:$manual_account_code_db,
                    'layer' => ($parentAccount->layer + 1),
                    'uid' => $accountCodeCredentials['uid'],
                ]);
            } else {
                Session::flash('errorMessage', 'Error updating chart of accounts.');
                return redirect()->back();
            }
            DB::commit();
            Session::flash('message', 'Chart of account updated successfully.');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error updating chart of accounts.');
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        $account = ChartOfAccount::findOrFail($id);
        $child = ChartOfAccount::module()->where('parent_id', $id)->first();
        $subsidaryCalculation = SubsidiaryCalculationModel::where('sub_ledger', $account->id)->first();

        if ($child) {
            Session::flash('errorMessage', 'It has child account, can not delete this account.');
            return redirect()->back();
        } else if ($subsidaryCalculation) {
            Session::flash('errorMessage', 'Dependencies accounts transaction found, can not delete this account.');
            return redirect()->back();
        } else {
            $account->delete();
            Session::flash('message', 'Account deleted successfully.');
            return redirect()->back();
        }
    }

    public function createLedger()
    {

        $data['accounts'] = ChartOfAccount::module()
            ->where(function($query){
                $query->where('account_type','!=','ledger')
                      ->orWhere(function($q){
                          $q->whereNull('account_type');
                      });
            })
            ->get()->keyBy('id');
        
        $coaConfig = ChartOfAccountsConfigModel::module()->first();
        if(!empty($coaConfig) && $coaConfig->code_type=='Manual'){
           $data['code_type'] = $coaConfig->code_type;
        }else{
           $data['code_type'] = 'Auto'; 
        }
        $data['nature'] = ChartOfAccount::module()->where('parent_id',0)->orderBy('id', 'asc')->get();
        return view('accounts::chart-of-accounts.modal.create-ledger',$data);
    }

    public function accountCodeGenerate($parentAccount, $newAccountType, $id)
    {
        $accountCode = '';
        $parentAccountCode = explode("--", $parentAccount->account_code);
        if ($id) {
            $account = ChartOfAccount::module()->where('parent_id', $parentAccount->id)->where('id', '!=', $id)->latest()->first();
            $uid = ($account) ? $account->uid : 0;
        } else {
            $account = ChartOfAccount::module()->where('parent_id', $parentAccount->id)->latest()->first();
            $uid = ($account) ? $account->uid : 0;
        }
        $uid++;

        if ($newAccountType == 'group') {
            $accountCode = $parentAccountCode[0] . "-" . $uid;
        } elseif ($newAccountType == 'ledger') {
            $accountCode = $parentAccountCode[0] . "--" . $uid;
        }

        return ['accountCode' => $accountCode, 'uid' => $uid];
    }
}
