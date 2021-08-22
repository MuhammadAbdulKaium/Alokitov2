<?php

namespace Modules\Accounting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Accounting\Entities\AccCharts;
use Modules\Accounting\Entities\AccClosingBalance;
use Modules\Accounting\Entities\AccOpbalance;
use Validator;
class AccHeadController extends MyController
{
    public function home(){
        return view('accounting::pages.accHead.home');
//        return view('accounting::pages.accHead.tallyView');
    }
    public function dashboard(){
        $accHeads = AccCharts::where('chart_parent', null)
            ->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->get();
        $assetData = array();
        $i=0;
        $accHeadsData[0]['name'] = $accHeads[0]->chart_name;
        $accHeadsData[0]['totalAmt'] = $accHeads[0]->sumDrCalc($accHeads[0]->id);
        if(count($accHeads[0]->childs)){
            foreach ($accHeads[0]->childs as $data){
                $assetData[$i]['name'] = $data->chart_name;
                $assetData[$i]['totalAmt'] = $data->sumDrCalc($data->id);
                $i++;
            }
        }
        $liabilityData = array();
        $i=0;
        $accHeadsData[1]['name'] = $accHeads[1]->chart_name;
        $accHeadsData[1]['totalAmt'] = $accHeads[1]->sumDrCalc($accHeads[1]->id);
        if(count($accHeads[1]->childs)){
            foreach ($accHeads[1]->childs as $data){
                $liabilityData[$i]['name'] = $data->chart_name;
                $liabilityData[$i]['totalAmt'] = $data->sumDrCalc($data->id);
                $i++;
            }
        }
        $incData = array();
        $i=0;
        $accHeadsData[2]['name'] = $accHeads[2]->chart_name;
        $accHeadsData[2]['totalAmt'] = $accHeads[2]->sumDrCalc($accHeads[2]->id);
        if(count($accHeads[2]->childs)){
            foreach ($accHeads[2]->childs as $data){
                $incData[$i]['name'] = $data->chart_name;
                $incData[$i]['totalAmt'] = $data->sumDrCalc($data->id);
                $i++;
            }
        }
        $expData = array();
        $i=0;
        $accHeadsData[3]['name'] = $accHeads[3]->chart_name;
        $accHeadsData[3]['totalAmt'] = $accHeads[3]->sumDrCalc($accHeads[3]->id);
        if(count($accHeads[3]->childs)){
            foreach ($accHeads[3]->childs as $data){
                $expData[$i]['name'] = $data->chart_name;
                $expData[$i]['totalAmt'] = $data->sumDrCalc($data->id);
                $i++;
            }
        }
        return view('accounting::pages.accHead.dashboard',compact('accHeadsData','assetData','liabilityData','incData','expData'));
    }
    public function index()
    {
//        return $accHeads = AccCharts::where();
        $accHeads = AccCharts::where('chart_parent', '=', null)
            ->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->get();
        $allAccHeads = AccCharts::pluck('chart_name','id');

        return view('accounting::pages.accHead.index',compact('accHeads','allAccHeads'));
    }

    public function create()
    {
        $accHeads = AccCharts::where('chart_parent',null)
            ->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->get();

        return view('accounting::pages.accHead.accountingHead',compact('accHeads','allAccHeads'));
    }

    public function store(Request $request)
    {
        //dd($request->all());
        $validator = Validator::make($request->all(), [
            'chart_code'       => 'required',
            'chart_name'       => 'required',
            'chart_type'       => 'required'
        ]);
        if($validator->passes()){
            $accHead = new AccCharts();
            $accHead->chart_code=$request->chart_code;
            $accHead->chart_name=$request->chart_name;
            $accHead->chart_type=$request->chart_type;
            $accHead->chart_parent=$request->chart_parent;
            if($request->cash_acc == 'on'){
                $accHead->cash_acc=1;
            }
            if($request->reconciliation == 'on'){
                $accHead->reconciliation=1;
            }
            $accHead->notes=$request->notes;
            $accHead->brunch_id = session()->get('campus');
            $accHead->company_id = session()->get('institute');

            $accHead->status=1;
            $accHead->save();
            $chart_id = $accHead->id;
            if(!empty($request->opbalance)){
                if($request->opbalancetype == 'C'){
                    $opbalance = $request->opbalance * (-1);
                }else{
                    $opbalance = $request->opbalance;
                }
            }else{
                $opbalance=0;
            }
            $AccClosingBalance = new AccClosingBalance();
            $AccClosingBalance->acc_f_year_id = $this->getAcc_f_year_id();
            $AccClosingBalance->acc_charts_id = $chart_id;
            $AccClosingBalance->balance = $opbalance;
            $AccClosingBalance->brunch_id = session()->get('campus');
            $AccClosingBalance->company_id = session()->get('institute');
            $AccClosingBalance->status = 1;
            $AccClosingBalance->save();
        }
        return redirect()->back();
    }
    public function edit(Request $request)
    {
        $datas = AccCharts::where('id', $request->id)->get();
        foreach ($datas as $data) {
            $parent = $data->chart_parent;
        }
        $accHeads = AccCharts::where('id',$parent)->get();
        return view('accounting::pages.accHead.edit',compact('datas','accHeads'));
    }
    public function  update(Request $request){
        AccCharts::where('id',$request->id)
            ->update([
                'chart_code' => $request->chart_code,
                'chart_name' => $request->chart_name,
                'chart_type' => $request->chart_type
            ]);
        return redirect('accounting/acchead');
    }
    public function delete(Request $request){
        AccCharts::where('id',$request->id)
            ->update([
                'status' => 0
            ]);
        return redirect('accounting/acchead');
    }

    public function accfeeslist(){
        $feesId=  AccCharts::where('chart_code','fees')
            ->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->get(['id']);
        $feesId=$feesId[0]->id;
        return AccCharts::where('chart_parent',$feesId)->get(['id','chart_name']);
    }
}