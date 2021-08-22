<?php

namespace Modules\Accounting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Accounting\Entities\AccCharts;
use Modules\Accounting\Entities\AccSubLedger;

class AccSubHeadController extends MyController
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $accSubLedgers = AccSubLedger::where('status',1)
            ->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->get();
        return view('accounting::pages.accSubHead.index',compact('accSubLedgers'));
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
        return view('accounting::pages.accSubHead.accountingSubHead',compact('accHeads'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $accSubLedger = new AccSubLedger();
        $accSubLedger->chart_code=$request->chart_code;
        $accSubLedger->chart_name=$request->chart_name;
        $accSubLedger->chart_parent=$request->chart_parent;
        $accSubLedger->brunch_id = session()->get('campus');
        $accSubLedger->company_id = session()->get('institute');
        $accSubLedger->notes=$request->notes;
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
        $datas = AccSubLedger::where('id', $request->id)->get();
        foreach ($datas as $data) {
            $parent = $data->chart_parent;
        }
        $accHeads = AccCharts::where('id',$parent)->get();
        return view('accounting::pages.accSubHead.edit',compact('datas','accHeads'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function  update(Request $request)
    {
        AccSubLedger::where('id',$request->id)
            ->update([
                'chart_code' => $request->chart_code,
                'chart_name' => $request->chart_name,
                'notes' => $request->notes
            ]);
        return redirect('accounting/accsubhead');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete(Request $request)
    {
        AccSubLedger::where('id',$request->id)
            ->update([
                'status' => 0
            ]);
        return redirect('accounting/accsubhead');
    }
}
