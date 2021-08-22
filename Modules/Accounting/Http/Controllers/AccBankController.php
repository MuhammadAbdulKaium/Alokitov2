<?php

namespace Modules\Accounting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Accounting\Entities\AccBank;
use Modules\Accounting\Entities\AccCharts;

class AccBankController extends MyController
{   //private $companyId = 1;
    //private $branchId = 1;
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $accBanks = AccBank::where('status',1)->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->get();
        return view('accounting::pages.accBank.index',compact('accBanks'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $accHeads = AccCharts::where('chart_parent',null)->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->get();
        return view('accounting::pages.accBank.accBank',compact('accHeads'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {   $accSubLedger = new AccBank();
        $accSubLedger->bank_code=$request->bank_code;
        $accSubLedger->bank_name=$request->bank_name;
        $accSubLedger->bank_acc_no=$request->bank_acc_no;
        $accSubLedger->bank_acc_name=$request->bank_acc_name;
        $accSubLedger->chart_parent=$request->bank_parent;
        $accSubLedger->notes=$request->notes;
        $accSubLedger->company_id=session()->get('institute');
        $accSubLedger->brunch_id=session()->get('campus');
        $accSubLedger->status=1;
        $accSubLedger->save();
        return redirect()->back();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request)
    {
        $datas = AccBank::where('id', $request->id)->get();
        foreach ($datas as $data) {
            $parent = $data->chart_parent;
        }
        $accHeads = AccCharts::where('id',$parent)->get();
        return view('accounting::pages.accBank.edit',compact('datas','accHeads'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function  update(Request $request)
    {
        AccBank::where('id',$request->id)
            ->update([
                'bank_code' => $request->bank_code,
                'bank_name' => $request->bank_name,
                'bank_acc_no' => $request->bank_acc_no,
                'bank_acc_name' => $request->bank_acc_name,
                'notes' => $request->notes
            ]);
        return redirect('accounting/accbank');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete(Request $request)
    {
        AccBank::where('id',$request->id)
            ->update([
                'status' => 0
            ]);
        return redirect('accounting/accbank');
    }
}
