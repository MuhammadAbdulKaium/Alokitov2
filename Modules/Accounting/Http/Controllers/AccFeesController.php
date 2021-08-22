<?php

namespace Modules\Accounting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Accounting\Entities\AccCharts;
use Modules\Accounting\Entities\AccFeesCollection;
use Modules\Accounting\Entities\AccVoucherEntry;
use Modules\Accounting\Entities\AccVoucherType;
use Modules\Fees\Entities\InvoicePaymentSummary;
use Redirect;
use Session;
use Validator;
use Modules\Fees\Http\Controllers\FeesReportController;
use Modules\Fees\Entities\Items;
use Modules\Fees\Entities\PaymentAdvanceHistory;
class AccFeesController extends Controller
{
    private $drHeadCode = "cash"; // cash ledger code
    private $feesCode = "Fees Collected"; // cash ledger code
    private $vType = "Rv ----- Receive Voucher"; // cash ledger code

    private  $feesReport;
    private  $items;
    private  $paymentAdvanceHistory;

    public function  __construct(FeesReportController $feesReport, Items $items, PaymentAdvanceHistory $paymentAdvanceHistory)
    {
        $this->feesReport=$feesReport;
        $this->items=$items;
        $this->paymentAdvanceHistory=$paymentAdvanceHistory;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $acc = AccCharts::where([['chart_code','fees']])
            ->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->get();
        foreach ($acc as $data){
            $fees = $data->childs()->get();
        }
        return view('accounting::pages.accFees.accFees',compact('fees'));
    }
//    ========= Items List and View

    public function feesItems()
    {

        return view('accounting::pages.accFees.accFeesitem');
    }


    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('accounting::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return $this|array|\Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function store(Request $request)
    {
        if(empty($request->total)){
            Session::flash('warning', 'Total Fees amount is 0.');
            // receiving page action
            return redirect()->back();
        }
        $validator = Validator::make($request->all(),[
            'start_date' => 'required',
            'end_date' => 'required',
            'total' => 'required'
        ]);
        $start_date = date_format(date_create_from_format('m/d/Y', $request->start_date),'Y-m-d');
        $end_date = date('Y-m-d 23:59:59', strtotime($request->end_date));

        if ($validator){
            // Start transaction
            DB::beginTransaction();
            try{
                $accFees = new AccFeesCollection();
                $accFees->from_date = $start_date;
                $accFees->to_date = $end_date;
                $accFees->fees_code = '';
                $accFees->amount = $request->total;
                $accFees->brunch_id = session()->get('campus');
                $accFees->company_id = session()->get('institute');
                $accFees->save();
                //$accFeesId = DB::getPdo()->lastInsertId();
            } catch (ValidationException $e){
                DB::rollback();
                return redirect()->back()->withErrors($e->getErrors())->withInput();
            } catch (\Exception $e){
                DB::rollback();
                throw $e;
            }
            try{
                //voucher number
                $accVouEnt = new AccVoucherEntryController;
                $voucherNo = $accVouEnt->voucherNextSerialFees($this->vType);

                //voucher type segregation
                $vt = explode(' ----- ',$this->vType);

                //voucher type id
                $voucherType = AccVoucherType::where('voucher_code',$vt[0])
                    ->where('voucher_name',$vt[1])
                    ->where('company_id', session()->get('institute'))
                    ->where('brunch_id', session()->get('campus'))
                    ->get(['id']);
                $vti = $voucherType[0]->id;

                //voucherDate
                $voucherDate = date('Y-m-d');

                //Head id
                 $accChart = AccCharts::where('chart_code',$this->feesCode)->where('company_id', session()->get('institute'))
                    ->where('brunch_id', session()->get('campus'))
                    ->first(['id']);
                Log::info('accChart_id'.$accChart);
                return $accChart;
                $headId1 = $accChart['id'];
                $accChart = AccCharts::where('chart_code',$this->drHeadCode)->where('company_id', session()->get('institute'))
                    ->where('brunch_id', session()->get('campus'))
                    ->first(['id']);
                $headId2 = $accChart['id'];

                //dr cr set
                $dr1 = $cr2 = 0;
                $dr2 = $cr1 = $request->total;

                //note
                $notes = "Fees Collection Receive Voucher.";

                //cr entry
                $accVoucherEntry_1 = new AccVoucherEntry();
                $accVoucherEntry_1->tran_seq = 1;
                $accVoucherEntry_1->tran_serial = $voucherNo;
                $accVoucherEntry_1->acc_voucher_type_id = $vti;
                $accVoucherEntry_1->tran_date = $voucherDate;
                $accVoucherEntry_1->acc_charts_id = $headId1;
                $accVoucherEntry_1->tran_amt_dr = $dr1;
                $accVoucherEntry_1->tran_amt_cr = $cr1;
                $accVoucherEntry_1->tran_details  = $notes;
                $accVoucherEntry_1->brunch_id = session()->get('campus');
                $accVoucherEntry_1->company_id = session()->get('institute');
                $accVoucherEntry_1->status = 1;
                $accVoucherEntry_1->save();

                //dr entry
                $accVoucherEntry_2 = new AccVoucherEntry();
                $accVoucherEntry_2->tran_seq = 2;
                $accVoucherEntry_2->tran_serial = $voucherNo;
                $accVoucherEntry_2->acc_voucher_type_id = $vti;
                $accVoucherEntry_2->tran_date = $voucherDate;
                $accVoucherEntry_2->acc_charts_id = $headId2;
                $accVoucherEntry_2->tran_amt_dr = $dr2;
                $accVoucherEntry_2->tran_amt_cr = $cr2;
                $accVoucherEntry_2->tran_details  = $notes;
                $accVoucherEntry_2->brunch_id = session()->get('campus');
                $accVoucherEntry_2->company_id = session()->get('institute');
                $accVoucherEntry_2->status = 1;
                $accVoucherEntry_2->save();

            } catch (ValidationException $e){
                DB::rollback();
                return redirect()->back()->withErrors($e->getErrors())->withInput();
            } catch (\Exception $e){
                DB::rollback();
                throw $e;
            }
            // checking
            if ($accVoucherEntry_1 && $accVoucherEntry_2){
                //End transaction
                $idList = InvoicePaymentSummary::whereraw("created_at >= '$start_date'")
                    ->whereraw("created_at <= '$end_date'")
                    ->whereraw('status is null')
                    ->where('institution_id', session()->get('institute'))
                    ->where('campus_id', session()->get('campus'))
                    ->get(['id']);
                Log::info("aasasas ". session()->get('institute') . " campus ".session()->get('campus') );

                foreach ($idList as $data){
                    Log::info("ex log");
                    InvoicePaymentSummary::where('id',$data->id)->update(['status' => '1']);;
                }
                DB::commit();
                Session::flash('success', 'Fees Collection Done');
                // return redirect
                return redirect()->back();
            } else {
                Session::flash('warning', 'Uabale to perform the actions');
                // return redirect
                return redirect()->back();
            }
        } else {
            Session::flash('warning', 'Invalid Information. please try with correct Information');
            // receiving page action
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }




//    New Fees Collection For Romesh Shil


    public function storeFeesCollection(Request $request){

//        return $request->all();

        if(empty($request->total)){
            Session::flash('warning', 'Total Fees amount is 0.');
            // receiving page action
            return redirect()->back();
        }
        $validator = Validator::make($request->all(),[
            'start_date' => 'required',
            'end_date' => 'required',
            'total' => 'required'
        ]);

        $start_date = date_format(date_create_from_format('m/d/Y', $request->start_date),'Y-m-d');
        $end_date = date('Y-m-d 23:59:59', strtotime($request->end_date));


        $feesCollectionProfile=$this->feesReport->getFeesItemsCollectionApi($start_date, $end_date);
        // fees collecction all invoice id
         $allInvoiceIds=$feesCollectionProfile['all_invoice'];
         // fees items and amount
        $feesProfileItem= $feesCollectionProfile['fees_items'];
        $attendanceFine= $feesCollectionProfile['total_attendance_fine'];
        $dueFine= $feesCollectionProfile['total_due_fine'];
        $advancePayment= $feesCollectionProfile['advance_payment_amount'];
        $advancePaymentTaken= $feesCollectionProfile['payment_taken_amount'];
        $totalDiscount= $feesCollectionProfile['totalDiscount'];

        if ($validator) {
            // Start transaction
            DB::beginTransaction();
//            try {
//                $accFees = new AccFeesCollection();
////                $accFees->from_date = $start_date;
////                $accFees->to_date = $end_date;
////                $accFees->fees_code = '';
////                $accFees->amount = $request->total;
////                $accFees->brunch_id = session()->get('campus');
////                $accFees->company_id = session()->get('institute');
////                $accFees->save();
//                //$accFeesId = DB::getPdo()->lastInsertId();
//            } catch (ValidationException $e) {
//                DB::rollback();
//                return redirect()->back()->withErrors($e->getErrors())->withInput();
//            } catch (\Exception $e) {
//                DB::rollback();
//                throw $e;
//            }
            try {

                //Head fees id
                $accChartFeesProfile = AccCharts::where('chart_code', 'Fees')->where('company_id', session()->get('institute'))
                    ->where('brunch_id', session()->get('campus'))
                    ->first();

                // fees chart all heads
                $accChartList = AccCharts::where('chart_parent', $accChartFeesProfile->id)->where('company_id', session()->get('institute'))
                    ->where('brunch_id', session()->get('campus'))
                    ->get();

                Log::info('accChart_id' . $accChartList);


                foreach ($accChartList as $key => $chart) {
                    if ($chart->chart_code == 'ATTENDANCE') {
                        if($attendanceFine>0){
                            //note
                            $notes = "Attendance Fees Collection Receive Voucher.";
                            $this->accVoucherEntry($chart->id, $attendanceFine,$notes);
                        }
                    } elseif($chart->chart_code == 'DUE_FINE') {
                        if($dueFine>0){
                            //note
                            $notes = "Due Fine Fees Collection Receive Voucher.";
                            $this->accVoucherEntry($chart->id, $dueFine,$notes);
                        }
                    }
                    else {
                        if(array_key_exists($chart->id,$feesProfileItem)) {
                            //note
                            $notes = $chart->chart_code." Fees Collection Receive Voucher";
                            $this->accVoucherEntry($chart->id, $feesProfileItem[$chart->id],$notes);
                        }
                    }
                }

                // advance payment system here
                //advance head
                $accChartAdvancePaymentProfile = AccCharts::where('chart_code', 'ADVANCE_PAYMENT')->where('company_id', session()->get('institute'))
                    ->where('brunch_id', session()->get('campus'))
                    ->first();
                if($advancePayment>0) {
                    //note
                    $notes = "Advance Fees Collection Receive Voucher";
                    $this->accVoucherEntry($accChartAdvancePaymentProfile->id,$advancePayment,$notes);
                }

                if($advancePaymentTaken>0) {
                    //note
                    $notes = "Advance Taken Receive Voucher";
                    $this->accVoucherDebitCreditEntry($accChartAdvancePaymentProfile->id,$advancePaymentTaken,$notes);
                }

                // discount head
                //advance head
                $accChartDiscountProfile = AccCharts::where('chart_code', 'DISCOUNT')->where('company_id', session()->get('institute'))
                    ->where('brunch_id', session()->get('campus'))
                    ->first();

                if($totalDiscount>0) {
                    //note
                    $notes = "Discount Receive Voucher";
                    $this->accVoucherDebitCreditEntry($accChartDiscountProfile->id,$totalDiscount,$notes);
                }



//            exit();


            } catch (ValidationException $e) {
                DB::rollback();
                return redirect()->back()->withErrors($e->getErrors())->withInput();
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }
            // checking
//            if ($accVoucherEntry_1 && $accVoucherEntry_2){
            //End transaction
            $idList = InvoicePaymentSummary::whereraw("created_at >= '$start_date'")
                ->whereraw("created_at <= '$end_date'")
                ->whereraw('status is null')
                ->where('institution_id', session()->get('institute'))
                ->where('campus_id', session()->get('campus'))
                ->get(['id']);
            Log::info("aasasas " . session()->get('institute') . " campus " . session()->get('campus'));

            foreach ($idList as $data) {
                Log::info("ex log");
                InvoicePaymentSummary::where('id', $data->id)->update(['status' => '1']);
            }

            $this->paymentAdvanceHistory->whereIn('invoice_id',$allInvoiceIds)->update(['is_read' => '1']);

            DB::commit();
            Session::flash('success', 'Fees Collection Done');
            // return redirect
            return redirect()->back();
//            } else {
//                Session::flash('warning', 'Uabale to perform the actions');
//                // return redirect
//                return redirect()->back();
//            }
        } else {
            Session::flash('warning', 'Invalid Information. please try with correct Information');
            // receiving page action
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }



// End Fees Collection


    public function accVoucherEntry($headId1,$totalAmount, $notes){

        //voucher number
        $accVouEnt = new AccVoucherEntryController;
        $voucherNo = $accVouEnt->voucherNextSerialFees($this->vType);

        //voucher type segregation
        $vt = explode(' ----- ',$this->vType);

        //voucher type id
        $voucherType = AccVoucherType::where('voucher_code',$vt[0])
            ->where('voucher_name',$vt[1])
            ->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->get(['id']);
        $vti = $voucherType[0]->id;

        //voucherDate
        $voucherDate = date('Y-m-d');


        $accChart = AccCharts::where('chart_code',$this->drHeadCode)->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->first(['id']);
        $headId2 = $accChart['id'];

        //dr cr set
        $dr1 = $cr2 = 0;
        $dr2 = $cr1 = $totalAmount;

        //cr entry
        $accVoucherEntry_1 = new AccVoucherEntry();
        $accVoucherEntry_1->tran_seq = 1;
        $accVoucherEntry_1->tran_serial = $voucherNo;
        $accVoucherEntry_1->acc_voucher_type_id = $vti;
        $accVoucherEntry_1->tran_date = $voucherDate;
        $accVoucherEntry_1->acc_charts_id = $headId1;
        $accVoucherEntry_1->tran_amt_dr = $dr1;
        $accVoucherEntry_1->tran_amt_cr = $cr1;
        $accVoucherEntry_1->tran_details  = $notes;
        $accVoucherEntry_1->brunch_id = session()->get('campus');
        $accVoucherEntry_1->company_id = session()->get('institute');
        $accVoucherEntry_1->status = 1;
        $accVoucherEntry_1->save();

        //dr entry
        $accVoucherEntry_2 = new AccVoucherEntry();
        $accVoucherEntry_2->tran_seq = 2;
        $accVoucherEntry_2->tran_serial = $voucherNo;
        $accVoucherEntry_2->acc_voucher_type_id = $vti;
        $accVoucherEntry_2->tran_date = $voucherDate;
        $accVoucherEntry_2->acc_charts_id = $headId2;
        $accVoucherEntry_2->tran_amt_dr = $dr2;
        $accVoucherEntry_2->tran_amt_cr = $cr2;
        $accVoucherEntry_2->tran_details  = $notes;
        $accVoucherEntry_2->brunch_id = session()->get('campus');
        $accVoucherEntry_2->company_id = session()->get('institute');
        $accVoucherEntry_2->status = 1;
        $accVoucherEntry_2->save();
    }


    // advance taken debit and credit calcualtion  function updat e

    public function accVoucherDebitCreditEntry($headId1,$totalAmount, $notes){

        //voucher number
        $accVouEnt = new AccVoucherEntryController;
        $voucherNo = $accVouEnt->voucherNextSerialFees($this->vType);

        //voucher type segregation
        $vt = explode(' ----- ',$this->vType);

        //voucher type id
        $voucherType = AccVoucherType::where('voucher_code',$vt[0])
            ->where('voucher_name',$vt[1])
            ->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->get(['id']);
        $vti = $voucherType[0]->id;

        //voucherDate
        $voucherDate = date('Y-m-d');


        $accChart = AccCharts::where('chart_code',$this->drHeadCode)->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->first(['id']);
        $headId2 = $accChart['id'];

        //dr cr set
        $dr1 = $cr2 = 0;
        $dr2 = $cr1 = $totalAmount;

        //cr entry
        $accVoucherEntry_1 = new AccVoucherEntry();
        $accVoucherEntry_1->tran_seq = 1;
        $accVoucherEntry_1->tran_serial = $voucherNo;
        $accVoucherEntry_1->acc_voucher_type_id = $vti;
        $accVoucherEntry_1->tran_date = $voucherDate;
        $accVoucherEntry_1->acc_charts_id = $headId1;
        $accVoucherEntry_1->tran_amt_dr =$cr1;
        $accVoucherEntry_1->tran_amt_cr =$dr1 ;
        $accVoucherEntry_1->tran_details  = $notes;
        $accVoucherEntry_1->brunch_id = session()->get('campus');
        $accVoucherEntry_1->company_id = session()->get('institute');
        $accVoucherEntry_1->status = 1;
        $accVoucherEntry_1->save();

        //dr entry
        $accVoucherEntry_2 = new AccVoucherEntry();
        $accVoucherEntry_2->tran_seq = 2;
        $accVoucherEntry_2->tran_serial = $voucherNo;
        $accVoucherEntry_2->acc_voucher_type_id = $vti;
        $accVoucherEntry_2->tran_date = $voucherDate;
        $accVoucherEntry_2->acc_charts_id = $headId2;
        $accVoucherEntry_2->tran_amt_dr =$cr2;
        $accVoucherEntry_2->tran_amt_cr =$dr2 ;
        $accVoucherEntry_2->tran_details  = $notes;
        $accVoucherEntry_2->brunch_id = session()->get('campus');
        $accVoucherEntry_2->company_id = session()->get('institute');
        $accVoucherEntry_2->status = 1;
        $accVoucherEntry_2->save();
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
}
