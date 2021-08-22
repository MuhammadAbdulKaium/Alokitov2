<?php

namespace Modules\Accounting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Accounting\Entities\AccCharts;
use Modules\Accounting\Entities\AccVoucherType;

class AccVoucherTypeController extends MyController
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $accVoucherTypes = AccVoucherType::where('status',1)
            ->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->get();
        return view('accounting::pages.accVoucherType.index',compact('accVoucherTypes'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $accHeads = AccCharts::where('chart_parent',null)
            ->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->get();
        return view('accounting::pages.accVoucherType.accVoucherType',compact('accHeads'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $accSubLedger = new AccVoucherType();
        $accSubLedger->voucher_code=$request->voucher_code;
        $accSubLedger->voucher_name=$request->voucher_name;
        $accSubLedger->voucher_type_id=$request->voucher_type;
        $accSubLedger->acc_charts_id=$request->voucher_default;
        $accSubLedger->notes=$request->notes;
        $accSubLedger->brunch_id = session()->get('campus');
        $accSubLedger->company_id = session()->get('institute');
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
        $datas = AccVoucherType::where('id', $request->id)->get();
        foreach ($datas as $data) {
            $defaultLedger = $data->acc_charts_id;
        }
        $accHeads = AccCharts::where('id',$defaultLedger)->get();
        return view('accounting::pages.accVoucherType.edit',compact('datas','accHeads'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function  update(Request $request)
    {
        AccVoucherType::where('id',$request->id)
            ->update([
                'voucher_code' => $request->voucher_code,
                'voucher_name' => $request->voucher_name,
                'voucher_type_id' => $request->voucher_type,
                'notes' => $request->notes
            ]);
        return redirect('accounting/accvouchertype');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete(Request $request)
    {
        AccVoucherType::where('id',$request->id)
            ->update([
                'status' => 0
            ]);
        return redirect('accounting/accvouchertype');
    }
}
