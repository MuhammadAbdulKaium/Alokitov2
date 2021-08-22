<?php

namespace Modules\Accounting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Accounting\Entities\AccCharts;
use Modules\Accounting\Entities\AccClosingBalance;
use Modules\Accounting\Entities\AccFYear;

class AccFYearController extends MyController
{   private $companyId = 1;
    private $branchId = 1;

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $accFYears = AccFYear::where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->get();
        return view('accounting::pages.accFYear.index',compact('accFYears'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('accounting::pages.accFYear.accFYear');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {

        //opening balance from previous
        $accFYears = AccFYear::where('status','1')
            ->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->get();
        if(count($accFYears)!=1){
            //year opening
            $start_date = date_format(date_create_from_format('m/d/Y', $request->start_date),'Y-m-d');
            $end_date = date_format(date_create_from_format('m/d/Y', $request->end_date),'Y-m-d');
            $accFYear = new AccFYear();
            $accFYear->start_date = $start_date;
            $accFYear->end_date = $end_date;
            $accFYear->brunch_id = session()->get('campus');
            $accFYear->company_id = session()->get('institute');
            $accFYear->status = 1;
            $accFYear->save();
            $new_f_year_id = $accFYear->id;

            $accHeads = AccCharts::where('status', 1)
                ->where('company_id', session()->get('institute'))
                ->where('brunch_id', session()->get('campus'))
                ->get();
            $accFYear = AccFYear::where('status', 2)
                ->where('company_id', session()->get('institute'))
                ->where('brunch_id', session()->get('campus'))
                ->first()->get();
            $accFYear[0]->id;
            foreach ($accHeads as $accHead){
                $data['chart_id'][]=$accHead->id;
                $data['chart_name'][]=$accHead->chart_name;
                $data['balance'][]=$accHead->sumCalc($accHead->id);
            }

            for ($i=0; $i<count($accHeads); $i++){
                /*echo '<br>'.$company_id;
                echo '---'.$data['chart_id'][$i];
                echo '---'.$data['chart_name'][$i];
                echo '---'.$data['balance'][$i];*/
                $AccClosingBalance = new AccClosingBalance();
                $AccClosingBalance->acc_f_year_id = $new_f_year_id;
                $AccClosingBalance->acc_charts_id = $data['chart_id'][$i];
                $AccClosingBalance->balance = $data['balance'][$i];
                $AccClosingBalance->brunch_id = session()->get('campus');
                $AccClosingBalance->company_id = session()->get('institute');
                $AccClosingBalance->status = 1;
                $AccClosingBalance->save();
            }
        echo 'Successfully Added';
        }else{
            echo 'Please Close the Opened Financial Year first.';
        }
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('accounting::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('accounting::edit');
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
    public function destroy()
    {
    }

    public function yearClosing(){
        $accHeads = AccCharts::where('chart_parent', '=', null)->get();
        return view('accounting::pages.accFYear.accFYearClosing',compact('accHeads'));
    }

    public function closeYear(){
        AccFYear::where('status',1)
        ->update([
            'status' => 2
        ]);
        return redirect('/accounting/accfyear');
    }
}
