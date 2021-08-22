<?php

namespace Modules\Accounting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Accounting\Entities\AccCharts;
use Modules\Accounting\Entities\AccVoucherEntry;
use Modules\Accounting\Entities\AccVoucherType;

class AccVoucherEntryController extends MyController
{
    public function index()
    {
        /*return $accVoucherEntrys = DB::select('select tran_date,tran_serial, SUM(tran_amt_dr) tran_amt_dr, SUM(tran_amt_cr) tran_amt_cr, tran_details, status
                                              from acc_tran 
                                              group by tran_serial,tran_date,tran_details, status');*/

        $accVoucherEntrys =  AccVoucherEntry::select(DB::raw('tran_date,tran_serial, SUM(tran_amt_dr) tran_amt_dr, SUM(tran_amt_cr) tran_amt_cr, tran_details, status'))
            ->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->groupBy('tran_serial','tran_date','tran_details', 'status','created_at')
            ->orderBy('created_at','desc')->orderBy('status')->orderBy('tran_date')
            ->get();

        return view('accounting::pages.accVoucherEntry.index',compact('accVoucherEntrys'));
    }

    public function create()
    {
        $accHeads = AccCharts::where('chart_type','L')
            ->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->get();
        $accVoucherTypes = AccVoucherType::where('status','1')
            ->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->get();
         $f_date['start'] = $this->getStartDate();
        $f_date['end'] = $this->getEndDate();
        return view('accounting::pages.accVoucherEntry.accountingVoucherEntry',compact('accHeads','accVoucherTypes','f_date'));
    }

    /**
     * making of next serial number by calculating previous voucher no
     * every month it will be reshuffled
     * @param $vType
     * @return mixed
     */
    public function voucherNextSerial(Request $request){
        $vType = explode(' ----- ',$request->vType);
        $voucherType = AccVoucherType::where('voucher_code',$vType[0])
                                    ->where('voucher_name',$vType[1])
                                    ->where('company_id', session()->get('institute'))
                                    ->where('brunch_id', session()->get('campus'))
                                    ->get();
        $vti = $voucherType[0]->id;
        //$dci = $voucherType[0]->acc_charts_id;
        $voucherType = str_pad($vti,2,0,STR_PAD_LEFT);
        $lastVoucherSerial = AccVoucherEntry::where('acc_voucher_type_id',$vti)
            ->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->max('tran_serial');
        $nextVoucherSerial = !empty($lastVoucherSerial)? substr($lastVoucherSerial, -4) + 1 : '1';
        $nextVoucherSerial = str_pad($nextVoucherSerial, 4, "0", STR_PAD_LEFT);
        /*$data['voucherNextSerial'] = date('ym').$voucherType.$nextVoucherSerial;
        $data['voucherDefaultLedger'] = $dci;*/
        return date('ym').$voucherType.$nextVoucherSerial;
    }
    public function voucherNextSerialFees($vType){
        $vType = explode(' ----- ',$vType);
        $voucherType = AccVoucherType::where('voucher_code',$vType[0])
                                    ->where('voucher_name',$vType[1])
                                    ->where('company_id', session()->get('institute'))
                                    ->where('brunch_id', session()->get('campus'))
                                    ->get();
        $vti = $voucherType[0]->id;
        //$dci = $voucherType[0]->acc_charts_id;
        $voucherType = str_pad($vti,2,0,STR_PAD_LEFT);
        $lastVoucherSerial = AccVoucherEntry::where('acc_voucher_type_id',$vti)
            ->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->max('tran_serial');
        $nextVoucherSerial = !empty($lastVoucherSerial)? substr($lastVoucherSerial, -4) + 1 : '1';
        $nextVoucherSerial = str_pad($nextVoucherSerial, 4, "0", STR_PAD_LEFT);
        return date('ym').$voucherType.$nextVoucherSerial;
    }


    public function headToId($name){
        $head = explode(' ----- ', $name);

        $head = AccCharts::where('chart_code',$head[0])
            ->where('chart_name',$head[1])
            ->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->get(['id']);
        return $head[0]->id;
    }


    public function voucherToId($name){
        $voucher = explode(' ----- ', $name);

        $voucher = AccVoucherType::where('chart_code',$voucher[0])
            ->where('chart_name',$voucher[1])
            ->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->get(['id']);
        return $voucher[0]->id;
    }


    public function store(Request $request){
        //return $request->all();
        //voucher type id
        $vType = explode(' ----- ',$request->voucher_type);
        $voucherType = AccVoucherType::where('voucher_code',$vType[0])
            ->where('voucher_name',$vType[1])
            ->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->get(['id']);
        $vti = $voucherType[0]->id;

        $voucherNo = $request->voucherNo;
        $voucherData = date_format(date_create_from_format('d-m-Y', $request->voucherData),'Y-m-d');
        $notes = $request->notes;

        for($i=0;$i<$request->snCount;$i++){
            $headId = $this->headToId($request->chart_name[$i]);
            $accVoucherEntry = new AccVoucherEntry();
            $accVoucherEntry->tran_seq = $i;
            $accVoucherEntry->tran_serial = $voucherNo;
            $accVoucherEntry->acc_voucher_type_id = $vti;
            $accVoucherEntry->tran_date = $voucherData;
            $accVoucherEntry->acc_charts_id = $headId;
            $dr = (empty($request->dr[$i]))? '0': $request->dr[$i];
            $cr = (empty($request->cr[$i]))? '0': $request->cr[$i];
            $accVoucherEntry->tran_amt_dr = $dr;
            $accVoucherEntry->tran_amt_cr = $cr;
            $accVoucherEntry->tran_details  = $notes;
            $accVoucherEntry->brunch_id = session()->get('campus');
            $accVoucherEntry->company_id = session()->get('institute');
            $accVoucherEntry->status = 2;
            $accVoucherEntry->save();
        }
        return 1;
    }

    public function approve(Request $request){
        $accVoucherEntrys = AccVoucherEntry::where('tran_serial',$request->id)
            ->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->get();
        $accVoucherSummery =  AccVoucherEntry::where('tran_serial',$request->id)->select(DB::raw('SUM(tran_amt_dr) total_dr, SUM(tran_amt_cr) total_cr'))
            ->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->groupBy('tran_serial')->get()->first();
        return view('accounting::pages.accVoucherEntry.approve',compact('accVoucherEntrys','accVoucherSummery'));
    }
    public function approveStore(Request $request){
        AccVoucherEntry::where('tran_serial', $request->voucherNo)
            ->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->update(['status' => 1]);
        return redirect('accounting/accvoucherentry');
    }
}
