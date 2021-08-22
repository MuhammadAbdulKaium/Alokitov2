<?php

namespace Modules\Accounting\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Excel;
use Modules\Accounting\Entities\AccCharts;
use Modules\Accounting\Entities\AccVoucherType;
use Modules\Accounting\Entities\AccVoucherEntry;
use Illuminate\Support\Facades\DB;

class AccReportController extends MyController
{
    private $data=array();

    public function index()
    {
        return view('accounting::pages.accReport.index');
    }

    public function dailyBook(){
        $accVoucherTypes = AccVoucherType::where('status',1)
            ->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->get();

        $start = $f_date['start'] = $this->getStartDate();
        $end = $f_date['end'] = $this->getEndDate();

        $accVoucherEntrys =  AccVoucherEntry::select(DB::raw('tran_date,tran_serial, SUM(tran_amt_dr) tran_amt_dr, SUM(tran_amt_cr) tran_amt_cr, tran_details, status, acc_voucher_type_id'))
            ->where('status','1')
            ->whereRaw("tran_date >= '$start' and tran_date <= '$end'")
            ->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->groupBy('tran_serial','tran_date','tran_details', 'status','created_at','acc_voucher_type_id')
            ->orderBy('created_at','desc')->orderBy('status')->orderBy('tran_date')
            ->get();
        return view('accounting::pages.accReport.dailyBook',compact('accVoucherEntrys','f_date', 'accVoucherTypes'));
    }

    public function dailyBookAjax(Request $request)
    {
        $fDate = empty($request->fromDate)? '' : date_format(date_create_from_format('m/d/Y', $request->fromDate),'Y-m-d');
        $tDate = empty($request->toDate)? '' : date_format(date_create_from_format('m/d/Y', $request->toDate),'Y-m-d');

        $start = $this->getStartDate();
        $end =  $this->getEndDate();

        $accVoucherEntrys = AccVoucherEntry::select(DB::raw('tran_date,tran_serial, SUM(tran_amt_dr) tran_amt_dr, SUM(tran_amt_cr) tran_amt_cr, tran_details, status, acc_voucher_type_id'))
                            ->where('status','1')
                            ->where('company_id', session()->get('institute'))
                            ->where('brunch_id', session()->get('campus'))
                            ->whereRaw("tran_date >= '$start' and tran_date <= '$end'")
                            ->groupBy('tran_serial','tran_date','tran_details', 'status','created_at','acc_voucher_type_id')
                            ->orderBy('created_at','desc')->orderBy('status')->orderBy('tran_date');

        if(!empty($request->voucherType)){
            $vType = explode(' ----- ',$request->voucherType);
            $voucherType = AccVoucherType::where('voucher_code',$vType[0])
                ->where('company_id', session()->get('institute'))
                ->where('brunch_id', session()->get('campus'))
                ->where('voucher_name',$vType[1])
                ->get();
            $vId = $voucherType[0]->id;

            if(!empty($vId)){
                $accVoucherEntrys = $accVoucherEntrys->where('acc_voucher_type_id',$vId);
            }
        }

        if(!empty($fDate)){
            if(empty($tDate)){$tDate = $fDate;}
            $accVoucherEntrys =  $accVoucherEntrys
                ->whereRaw("tran_date >= '$start' and tran_date <= '$end'")
                ->where('tran_date', '>=', $fDate)
                ->where('tran_date', '<=', $tDate);

        }

        $accVoucherEntrys = $accVoucherEntrys->get();
        return view('accounting::pages.accReport.dailyBookAjax',compact('accVoucherEntrys'));
    }

    public function ledgerBook()
    {   $f_date['start'] = $this->getStartDate();
        $f_date['end'] = $this->getEndDate();

        $accHeads = AccCharts::where('chart_type','L')
            ->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->get();

//        $accVoucherEntrys =  AccVoucherEntry::where('status','1')
//            ->where('company_id', session()->get('institute'))
//            ->where('brunch_id', session()->get('campus'))
//            ->orderBy('created_at','asc')->orderBy('status')->orderBy('tran_date')
//            ->get();

        return view('accounting::pages.accReport.ledgerBook',compact('accHeads','f_date', 'accVoucherEntrys'));
    }

    public function ledgerBookAjax(Request $request){
        $fDate = empty($request->fromDate)? '' : date_format(date_create_from_format('m/d/Y', $request->fromDate),'Y-m-d');
        $tDate = empty($request->toDate)? '' : date_format(date_create_from_format('m/d/Y', $request->toDate),'Y-m-d');

        $start = $this->getStartDate();
        $end = $this->getEndDate();

        $accVoucherEntrys =  AccVoucherEntry::where('status','1')
            ->whereRaw("tran_date >= '$start' and tran_date <= '$end'")
            ->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->orderBy('created_at','asc')->orderBy('status')->orderBy('tran_date');

        if(!empty($request->ledgersList)){
            $lData = explode(' ----- ',$request->ledgersList);
            if(!empty($lData[0]) && !empty($lData[1])){
                $lDatas = AccCharts::where('chart_code',$lData[0])
                    ->where('chart_name',$lData[1])
                    ->where('company_id', session()->get('institute'))
                    ->where('brunch_id', session()->get('campus'))
                    ->get();
                if(count($lDatas) != 0 ){
                    $lId = $lDatas[0]->id;
                    $accVoucherEntrys = $accVoucherEntrys->where('acc_charts_id',$lId);
                }else{
                    return 'This Ledger is not in database';
                }
            }else{
                return 'This Ledger is not in database';
            }
        }

        if(!empty($fDate)){
            if(empty($tDate)){$tDate = $fDate;}
            $accVoucherEntrys =  $accVoucherEntrys
                ->where('tran_date', '>=', $fDate)
                ->where('tran_date', '<=', $tDate);
        }

        $accVoucherEntrys = $accVoucherEntrys->get();
        return view('accounting::pages.accReport.ledgerBookAjax',compact('accVoucherEntrys'));
    }

    public function receivePayment(Request $request)
    {
        $f_date['start'] = $this->getStartDate();
        $f_date['end'] = $this->getEndDate();

        $receiveTrns = $this->receiveTransaction();
        $paymentTrns = $this->paymentTransaction();
//        return $this->periodClosingBalance();
        return view('accounting::pages.accReport.receivePayment',compact('receiveTrns','paymentTrns','f_date'));
    }

    /**
     * @param string $from
     * @param string $to
     * @return mixed
     */
    public function receiveTransaction($from='', $to=''){
        $from = !empty($from)? $from : date($this->getStartDate());
        $to = !empty($to)? $to : date($this->getEndDate());
        // receive transaction
        //receive voucherType id is 11 and its hard coded
        $receive = $this->voucherType('11')->get(['id']);
        $receiveTrns = AccVoucherEntry::where('tran_amt_cr','!=','')
            ->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->wherein('acc_voucher_type_id',$receive)
            ->whereBetween('tran_date', array($from, $to))
            ->where('status','1')
            ->get(['acc_charts_id','tran_amt_cr as amount']);
        return $receiveTrns;
    }

    /**
     * @param string $from
     * @param string $to
     * @return mixed
     */
    public function paymentTransaction($from='', $to=''){

        $from = !empty($from)? $from : date($this->getStartDate());
        $to = !empty($to)? $to : date($this->getEndDate());

        // payment transaction
        //payment voucherType id is 7 and its hard coded
        $payment = $this->voucherType('7')->get(['id']);
        $paymentTrns = AccVoucherEntry::where('tran_amt_dr','!=','')
            ->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->wherein('acc_voucher_type_id',$payment)
            ->whereBetween('tran_date', array($from, $to))
            ->where('status','1')
            ->get(['acc_charts_id','tran_amt_dr as amount']);
        return $paymentTrns;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function trialBalance()
    {
        $f_date['start'] = $this->getStartDate();
        $f_date['end'] = $this->getEndDate();

         $accHeads = AccCharts::where('chart_parent', '=', null)
            ->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->get();

        return view('accounting::pages.accReport.trialBalance',compact('accHeads','f_date'));
    }


    public function trialBalanceTest(){

        $f_date['start'] = $this->getStartDate();
        $f_date['end'] = $this->getEndDate();

        $accHeads = AccCharts::where('chart_parent', '=', null)
            ->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->get();

        return view('accounting::pages.accReport.trialBalancetest',compact('accHeads','f_date'));
    }



    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function balanceSheet()
    {
        $f_date['start'] = $this->getStartDate();
        $f_date['end'] = $this->getEndDate();

        $accHeads = AccCharts::where('chart_parent', '=', null)
//            ->where('id', '>=', 1)->where('id', '<=', 2)
            ->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->get();

        // income head id
         $incomeHeadId=$this->getHeadId('INCOME');
         $expenseHeadId=$this->getHeadId('EXPENSE');


        return view('accounting::pages.accReport.balanceSheet',compact('accHeads','f_date','incomeHeadId', 'expenseHeadId'));
    }
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function profitLoss()
    {
        $f_date['start'] = $this->getStartDate();
        $f_date['end'] = $this->getEndDate();

        $incomeHeadId=$this->getHeadId('INCOME');
        $expenseHeadId=$this->getHeadId('EXPENSE');

          $accHeads = AccCharts::where('chart_parent', '=', null)
            ->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->whereIn('id',  [$incomeHeadId,$expenseHeadId])->get();
        return view('accounting::pages.accReport.profitLoss',compact('accHeads','f_date','incomeHeadId','expenseHeadId'));
    }


    public function profitLossTest(){
        $f_date['start'] = $this->getStartDate();
        $f_date['end'] = $this->getEndDate();

        $incomeHeadId=$this->getHeadId('INCOME');
        $expenseHeadId=$this->getHeadId('EXPENSE');

        // account income head
        $accIncomeHeads = AccCharts::where('chart_parent', '=', null)
            ->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->where('id',  $incomeHeadId)->get();

         $accExpenseHeads = AccCharts::where('chart_parent', '=', null)
            ->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->where('id',  $expenseHeadId)->get();


        return view('accounting::pages.accReport.profitLoss-test',compact('accIncomeHeads','accExpenseHeads','f_date','incomeHeadId','expenseHeadId'));

    }

    /**
     * @param $headId
     */
    function sumCalc1($headId){
        //echo '<br>'.$headId;
        $totalDr = 0;
        $totalCr = 0;
        $mainHead = AccCharts::where('id',$headId)
            ->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->get();
        if(count($mainHead[0]->childs) > 0){
            foreach ($mainHead[0]->childs as $child){
                //echo '<br>'.$child->id;
                 $this->sumCalc($child->id);
            }
        }else{
            //$dr = AccVoucherEntry::select(DB::raw(' SUM(tran_amt_dr) tran_amt_dr ')) ->where('acc_charts_id',$headId) ->get();
            $cr = AccVoucherEntry::select(DB::raw(' SUM(tran_amt_cr) tran_amt_cr ')) ->where('acc_charts_id',$headId) ->get();

            //echo $totalDr = $totalDr + $dr[0]->tran_amt_dr;
            echo $totalCr = $totalCr + $cr[0]->tran_amt_cr;
        }

        echo '<pre>';
        //print_r($totalDr);
        echo '</pre>';
    }

    /**
     * @param $id
     * @return mixed
     */
    public function voucherType($id){
        return AccVoucherType::where('voucher_type_id',$id);
    }

    /**
     * @param string $from
     * @param string $to
     * @return int
     */
    public function periodClosingBalance($from='', $to=''){
        $from = !empty($from)? $from : date($this->getStartDate());
        $to = !empty($to)? $to : date($this->getEndDate());
        $receiveTrns = $this->receiveTransaction($from, $to);
        $paymentTrns = $this->paymentTransaction($from, $to);
        $payment = 0; $receive = 0;
        foreach($paymentTrns as $paymentTrn){
            $payment = $payment + $paymentTrn->amount;
        }
        foreach($receiveTrns as $receiveTrn){
            $receive = $receive + $receiveTrn->amount;
        }
        return $receive - $payment;
    }
    /*public function  chartLedgre($headId) {
        $mainHead = AccCharts::where('id',$headId)->get();
        if(count($mainHead[0]->childs) > 0){
            foreach ($mainHead[0]->childs as $child){
                if($child->chart_type == 'L'){
                    $this->data[] = $child->id;
                }else{
                    $this->chartLedgre($child->id);
                }
            }
        }
        return $this->data;
    }

    public function sumCalc($headId){
        $this->data = array();
        $result = 0;
        foreach ($this->chartLedgre($headId) as $ledger){
            $cr = AccVoucherEntry::select(DB::raw(' SUM(tran_amt_dr) tran_amt_dr ')) ->where('acc_charts_id',$ledger) ->get();
            $cr->toArray();
            $result = $result + $cr[0]->tran_amt_dr;
        }
        return $result;
    }*/
    public function dailybookexcel(Request $request){
        $gap = '_____';
        if($request->fromDate == $gap ) {$request->fromDate = '';}
        if($request->toDate == $gap ) {$request->toDate = '';}
        if($request->voucherType == $gap ) {$request->voucherType = '';}

        $fDate = empty($request->fromDate)? '' : date_format(date_create_from_format('m-d-Y', $request->fromDate),'Y-m-d');
        $tDate = empty($request->toDate)? '' : date_format(date_create_from_format('m-d-Y', $request->toDate),'Y-m-d');
        $accVoucherEntrys = AccVoucherEntry::select(DB::raw('tran_date,tran_serial, SUM(tran_amt_dr) tran_amt_dr, SUM(tran_amt_cr) tran_amt_cr, tran_details, status, acc_voucher_type_id'))
            ->where('status','1')
            ->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->groupBy('tran_serial','tran_date','tran_details', 'status','created_at','acc_voucher_type_id')
            ->orderBy('created_at','desc')->orderBy('status')->orderBy('tran_date');

        if(!empty($request->voucherType)){
            $vType = explode(' ----- ',$request->voucherType);
            $voucherType = AccVoucherType::where('voucher_code',$vType[0])
                ->where('company_id', session()->get('institute'))
                ->where('brunch_id', session()->get('campus'))
                ->where('voucher_name',$vType[1])
                ->get();
            $vId = $voucherType[0]->id;

            if(!empty($vId)){
                $accVoucherEntrys = $accVoucherEntrys->where('acc_voucher_type_id',$vId);
            }
        }

        if(!empty($fDate)){
            if(empty($tDate)){$tDate = $fDate;}
            $accVoucherEntrys =  $accVoucherEntrys
                ->where('tran_date', '>=', $fDate)
                ->where('tran_date', '<=', $tDate);
        }

        $accVoucherEntrys = $accVoucherEntrys->get();

        // share all variables with the view
       view()->share(compact('accVoucherEntrys'));

       $fileName = 'Daily Book- '.date('YmdHis');
       //generate excel
        Excel::create($fileName, function ($excel) {
           $excel->sheet('Daily Book', function ($sheet) {
               // Font family
               $sheet->setFontFamily('Comic Sans MS');
               // Set font with ->setStyle()
               $sheet->setStyle(array('font' => array('name'=>'Calibri','size' => 12)));
               // cell formatting
               $sheet->setAutoSize(true);
               // Set all margins
               $sheet->setPageMargin(0.25);
               // mergeCell
               // $sheet->mergeCells(['C3:D1', 'E1:H1']);
               $sheet->loadView('accounting::pages.accReport.excel.dailyBookExcel');
           });
       })->download('xls');
    }

    public function ledgerBookexcel(Request $request){
        $gap = '_____';
        if($request->fromDate == $gap ) {$request->fromDate = '';}
        if($request->toDate == $gap ) {$request->toDate = '';}
        if($request->ledgersList == $gap ) {$request->ledgersList = '';}

        $fDate = empty($request->fromDate)? '' : date_format(date_create_from_format('m-d-Y', $request->fromDate),'Y-m-d');
        $tDate = empty($request->toDate)? '' : date_format(date_create_from_format('m-d-Y', $request->toDate),'Y-m-d');
        $accVoucherEntrys =  AccVoucherEntry::where('status','1')
            ->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->orderBy('created_at','asc')->orderBy('status')->orderBy('tran_date');

        if(!empty($request->ledgersList)){
            $lData = explode(' ----- ',$request->ledgersList);
            if(!empty($lData[0]) && !empty($lData[1])){
                $lDatas = AccCharts::where('chart_code',$lData[0])
                    ->where('company_id', session()->get('institute'))
                    ->where('brunch_id', session()->get('campus'))
                    ->where('chart_name',$lData[1])
                    ->get();
                if(count($lDatas) != 0 ){
                    $lId = $lDatas[0]->id;
                    $accVoucherEntrys = $accVoucherEntrys->where('acc_charts_id',$lId);
                }else{
                    return 'This Ledger is not in database';
                }
            }else{
                return 'This Ledger is not in database';
            }
        }

        if(!empty($fDate)){
            if(empty($tDate)){$tDate = $fDate;}
            $accVoucherEntrys =  $accVoucherEntrys
                ->where('tran_date', '>=', $fDate)
                ->where('tran_date', '<=', $tDate);
        }

        $accVoucherEntrys = $accVoucherEntrys->get();

        // share all variables with the view
        view()->share(compact('accVoucherEntrys'));

        $fileName = 'Ledger Book- '.date('YmdHis');
        //generate excel
        Excel::create($fileName, function ($excel) {
            $excel->sheet('Ledger Book' , function ($sheet) {
                // Font family
                $sheet->setFontFamily('Comic Sans MS');
                // Set font with ->setStyle()
                $sheet->setStyle(array('font' => array('name'=>'Calibri','size' => 12)));
                // cell formatting
                $sheet->setAutoSize(true);
                // Set all margins
                $sheet->setPageMargin(0.25);
                // mergeCell
                // $sheet->mergeCells(['C3:D1', 'E1:H1']);
                $sheet->loadView('accounting::pages.accReport.excel.ledgerBookExcel');
            });
        })->download('xls');

    }

    public function receivePaymentexcel(){
        $f_date['start'] = $this->getStartDate();
        $f_date['end'] = $this->getEndDate();

        $receiveTrns = $this->receiveTransaction();
        $paymentTrns = $this->paymentTransaction();
        view()->share(compact('receiveTrns','paymentTrns','f_date'));

        $fileName = 'Receive Payment- '.date('YmdHis');
        //generate excel
        Excel::create($fileName, function ($excel) {
            $excel->sheet('Receive Payment' , function ($sheet) {
                // Font family
                $sheet->setFontFamily('Comic Sans MS');
                // Set font with ->setStyle()
                $sheet->setStyle(array('font' => array('name'=>'Calibri','size' => 12)));
                // cell formatting
                $sheet->setAutoSize(true);
                // Set all margins
                $sheet->setPageMargin(0.25);
                // mergeCell
                // $sheet->mergeCells(['C3:D1', 'E1:H1']);
                $sheet->loadView('accounting::pages.accReport.excel.receivePaymentExcel');
            });
        })->download('xls');
    }

    public function trialBalanceexcel(){
        $f_date['start'] = $this->getStartDate();
        $f_date['end'] = $this->getEndDate();

        $accHeads = AccCharts::where('chart_parent', '=', null)
            ->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->get();
        view()->share(compact('accHeads','f_date'));

        $fileName = 'Trial Balance- '.date('YmdHis');
        //generate excel
        Excel::create($fileName, function ($excel) {
            $excel->sheet('Trial Balance' , function ($sheet) {
                // Font family
                $sheet->setFontFamily('Comic Sans MS');
                // Set font with ->setStyle()
                $sheet->setStyle(array('font' => array('name'=>'Calibri','size' => 12)));
                // cell formatting
                $sheet->setAutoSize(true);
                // Set all margins
                $sheet->setPageMargin(0.25);
                // mergeCell
                // $sheet->mergeCells(['C3:D1', 'E1:H1']);
                $sheet->loadView('accounting::pages.accReport.excel.trialBalanceExcel');
            });
        })->download('xls');
    }

    public function balanceSheetexcel(){
        $f_date['start'] = $this->getStartDate();
        $f_date['end'] = $this->getEndDate();

        $accHeads = AccCharts::where('chart_parent', '=', null)
            ->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->where('id', '>=', 1)->where('id', '<=', 2)->get();

        view()->share(compact('accHeads','f_date'));

        $fileName = 'Balance Sheet- '.date('YmdHis');
        //generate excel
        Excel::create($fileName, function ($excel) {
            $excel->sheet('BalanceS heet' , function ($sheet) {
                // Font family
                $sheet->setFontFamily('Comic Sans MS');
                // Set font with ->setStyle()
                $sheet->setStyle(array('font' => array('name'=>'Calibri','size' => 12)));
                // cell formatting
                $sheet->setAutoSize(true);
                // Set all margins
                $sheet->setPageMargin(0.25);
                // mergeCell
                // $sheet->mergeCells(['C3:D1', 'E1:H1']);
                $sheet->loadView('accounting::pages.accReport.excel.balanceSheetExcel');
            });
        })->download('xls');
    }

    public function profitLossexcel(){
        $f_date['start'] = $this->getStartDate();
        $f_date['end'] = $this->getEndDate();

        $accHeads = AccCharts::where('chart_parent', '=', null)
            ->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->where('id', '>=', 3)->where('id', '<=', 4)->get();

        view()->share(compact('accHeads','f_date'));

        $fileName = 'Profit Loss- '.date('YmdHis');
        //generate excel
        Excel::create($fileName, function ($excel) {
            $excel->sheet('Profit Loss' , function ($sheet) {
                // Font family
                $sheet->setFontFamily('Comic Sans MS');
                // Set font with ->setStyle()
                $sheet->setStyle(array('font' => array('name'=>'Calibri','size' => 12)));
                // cell formatting
                $sheet->setAutoSize(true);
                // Set all margins
                $sheet->setPageMargin(0.25);
                // mergeCell
                // $sheet->mergeCells(['C3:D1', 'E1:H1']);
                $sheet->loadView('accounting::pages.accReport.excel.profitLossExcel');
            });
        })->download('xls');
    }


    public function  getHeadId($headName){
         $accHeads = AccCharts::where('chart_code', '=', $headName)
//            ->where('id', '>=', 1)->where('id', '<=', 2)
            ->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->first();
        return $accHeads->id;
    }



}
?>