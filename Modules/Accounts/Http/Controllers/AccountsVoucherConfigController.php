<?php

namespace Modules\Accounts\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Modules\Accounts\Entities\AccountsVoucherConfigModel;
use Modules\Accounts\Entities\AccountsTransactionModel;
use Modules\Setting\Entities\Institute;
use Modules\Setting\Entities\Campus;
use Illuminate\Validation\Rule;
use App\Helpers\AccountsHelper;

class AccountsVoucherConfigController extends Controller
{
    use AccountsHelper;
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $data['vouchers'] = AccountsVoucherConfigModel::module()->join('setting_institute', 'setting_institute.id', 'accounts_voucher_config.institute_id')
            ->join('setting_campus', 'setting_campus.id', 'accounts_voucher_config.campus_id')
            ->select('accounts_voucher_config.*','setting_institute.institute_name','setting_campus.name as campus_name')
            ->orderBy('accounts_voucher_config.id', 'desc')
            ->get();
        return view('accounts::voucher-config.voucher-config', $data);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $instititue_list = Institute::where('id', self::getInstituteId())->orderBy('institute_name', 'asc')->get();
        return view('accounts::voucher-config.modal.add-voucher-config', compact('instititue_list'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $campus_id = $request->campus_id;
        $campus_info = Campus::find($campus_id);
        $institute_id = $campus_info->institute_id;
        $rules = [
            'campus_id' => 'required',
            'voucher_name' => 'required',
            'type_of_voucher' => 'required',
            'numbering' => 'required',
            'status' => 'required'
        ];
        if($request->numbering=='auto'){
            $rules['numeric_part'] = 'required';
            $rules['starting_number'] = 'required|numeric';
            $rules['prefix'] = 'required';
        }
        $validated = $request->validate($rules);
        $voucher = new AccountsVoucherConfigModel();
        $voucher->type_of_voucher = $request->type_of_voucher;
        $voucher->numbering = $request->numbering;
        $voucher->voucher_name = $request->voucher_name;

        if($request->numbering=='auto'){
            $voucher->numeric_part = $request->numeric_part;
            $voucher->suffix = $request->suffix;
            $voucher->starting_number = $request->starting_number;
            $voucher->prefix = $request->prefix;
        }else{
            $voucher->numeric_part = null;
            $voucher->suffix = null;
            $voucher->starting_number = null;
            $voucher->prefix = null; 
        }
        $voucher->campus_id = $request->campus_id;
        $voucher->institute_id = $institute_id;
        $voucher->status = $request->status;
        $voucher->created_by = Auth::user()->id;
        if($request->status==1){
            AccountsVoucherConfigModel::where('institute_id',$institute_id)->where('campus_id',$request->campus_id)->where('type_of_voucher', $request->type_of_voucher)->update(['status'=>'0']);
        }
        $voucherStore=$voucher->save();
        if ($voucherStore) {
            Session::flash('message', 'New Voucher created successfully.');
            return redirect()->back();
        } else {
            Session::flash('errorMessage', 'Error creating Voucher.');
            return redirect()->back();
        }
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
    public function edit($id)
    {
        $voucher=AccountsVoucherConfigModel::where('id','=',$id)->first();
        $instititue_list = Institute::where('id', self::getInstituteId())->orderBy('institute_name', 'asc')->get();
        return view('accounts::voucher-config.modal.edit-voucher-config', compact('voucher','instititue_list'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $campus_id = $request->campus_id;
        $campus_info = Campus::find($campus_id);
        $institute_id = $campus_info->institute_id;

        $rules = [
            'campus_id' => 'required',
            'voucher_name' => 'required',
            'type_of_voucher' => 'required',
            'status' => 'required',
            'numbering' => 'required',
        ];
        if($request->numbering=='auto'){
            $rules['numeric_part'] = 'required';
            $rules['starting_number'] = 'required|numeric';
            $rules['prefix'] = 'required';
        }
        $validated = $request->validate($rules);

        $voucher=AccountsVoucherConfigModel::where('id','=',$id)->first();
        $checkUpdate = true;
        if($voucher->campus_id != $request->campus_id || $voucher->type_of_voucher != $request->type_of_voucher || $voucher->numbering != $request->numbering || $voucher->voucher_name != $request->voucher_name || (($voucher->numbering == $request->numbering && $voucher->numbering=='auto') &&  $voucher->numeric_part != $request->numeric_part || $voucher->suffix != $request->suffix || $voucher->starting_number != $request->starting_number || $voucher->prefix != $request->prefix)){
            $checkUpdate = self::checkVoucherTransaction($voucher);
        }
        if($checkUpdate){
            $voucher->type_of_voucher = $request->type_of_voucher;
            $voucher->numbering = $request->numbering;
            $voucher->voucher_name = $request->voucher_name;
            if($request->numbering=='auto'){
                $voucher->numeric_part = $request->numeric_part;
                $voucher->suffix = $request->suffix;
                $voucher->starting_number = $request->starting_number;
                $voucher->prefix = $request->prefix;
            }else{
                $voucher->numeric_part = null;
                $voucher->suffix = null;
                $voucher->starting_number = null;
                $voucher->prefix = null;
            }
            $voucher->campus_id = $request->campus_id;
            $voucher->institute_id = $institute_id;
            $voucher->status = $request->status;
            $voucher->updated_by = Auth::user()->id;
            $voucherUpdate = $voucher->save();
            if($request->status==1){
                AccountsVoucherConfigModel::where('institute_id',$institute_id)->where('campus_id',$request->campus_id)->where('type_of_voucher', $request->type_of_voucher)->where('id', '!=', $id)->update(['status'=>'0']);
            }
            if ($voucherUpdate) {
                Session::flash('message', 'Voucher Update successfully.');
                return redirect()->back();
            } else {
                Session::flash('errorMessage', 'Error Update Voucher.');
                return redirect()->back();
            }
        }else{
            Session::flash('errorMessage', 'Sorry voucher configuration has data dependancy.');
            return redirect()->back(); 
        }
        
    }

    public static  function checkVoucherTransaction($voucher){
        $type_of_voucher = $voucher->type_of_voucher;
        $campus_id = $voucher->campus_id;
        $institute_id = $voucher->institute_id;
        $id = $voucher->id;
        $checkUpdate = true;
        
        $voucherInfo = AccountsTransactionModel::where('campus_id',$campus_id)->where('institute_id',$institute_id)->where('voucher_config_id', $id)->first();
        if(!empty($voucherInfo)){
            $checkUpdate = false;
        }
        return $checkUpdate; 
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
