<?php

namespace Modules\Fees\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Fees\Entities\InvoicePayment;
use Modules\Fees\Entities\PaymentGatewayRequest;
use Modules\Fees\Entities\PaymentGatewayResponse;
use Modules\Fees\Entities\PaymentMethod;
use Modules\Fees\Entities\Fees;
use Modules\Fees\Entities\FeesInvoice;
use Modules\Fees\Entities\PaymentExtra;
use Modules\Setting\Entities\AutoSmsModule;
use App\Http\Controllers\SmsSender;
use Modules\Setting\Entities\FessSetting;
use Modules\Fees\Entities\InvoiceFine;
use Modules\Fees\Entities\InvoicePaymentSummary;
use Modules\Fees\Http\Controllers\AttendanceFineController;
use DB;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Setting\Entities\SmsInstitutionGetway;
use Modules\Student\Entities\StudentWaiver;
use Modules\Student\Entities\StudentInformation;
use Modules\Fees\Entities\PaymentAdvanceHistory;

use Carbon\Carbon;
use Session;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;


class InvoicePaymentController extends Controller
{
    private  $fees;
    private  $invoicePayment;
    private  $paymentMethod;
    private  $feesInvoice;
    private  $paymentExtra;
    private  $autoSmsModule;
    private  $smsSender;
    private  $feesSetting;
    private  $invoiceFine;
    private  $invoicePaymentSummary;
    private  $attendanceFine;
    private  $academicHelper;
    private  $smsInstitutionGetway;
    private  $studentWaiver;
    private  $studentInformation;
    private  $paymentGatewayRequest;
    private  $paymentGatewayResponse;
    private  $paymentAdvanceHistory;

    public function __construct(Fees $fees, PaymentAdvanceHistory $paymentAdvanceHistory, PaymentGatewayResponse $paymentGatewayResponse, PaymentGatewayRequest $paymentGatewayRequest, SmsInstitutionGetway $smsInstitutionGetway, StudentInformation $studentInformation, StudentWaiver $studentWaiver, AcademicHelper $academicHelper, AttendanceFineController $attendanceFine, InvoicePaymentSummary $invoicePaymentSummary, InvoiceFine $invoiceFine, FessSetting $feesSetting,PaymentExtra $paymentExtra, SmsSender $smsSender,InvoicePayment $invoicePayment,PaymentMethod $paymentMethod, FeesInvoice $feesInvoice,AutoSmsModule $autoSmsModule)
    {
        $this->invoicePayment       = $invoicePayment;
        $this->paymentMethod        = $paymentMethod;
        $this->fees                 = $fees;
        $this->feesInvoice          = $feesInvoice;
        $this->paymentExtra          = $paymentExtra;
        $this->autoSmsModule         = $autoSmsModule;
        $this->smsSender            = $smsSender;
        $this->feesSetting           = $feesSetting;
        $this->invoiceFine           = $invoiceFine;
        $this->invoicePaymentSummary = $invoicePaymentSummary;
        $this->attendanceFine       = $attendanceFine;
        $this->academicHelper       = $academicHelper;
        $this->smsInstitutionGetway = $smsInstitutionGetway;
        $this->studentWaiver = $studentWaiver;
        $this->studentInformation = $studentInformation;
        $this->paymentGatewayRequest = $paymentGatewayRequest;
        $this->paymentGatewayResponse = $paymentGatewayResponse;
        $this->paymentAdvanceHistory = $paymentAdvanceHistory;

    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function invoicePaymentModal(Request $request, $invoice_id)
    {
        // check fees automatic moudle


        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

        $institudeIdCode=str_pad($instituteId,4,"000",STR_PAD_LEFT);

        // generate unique key
        $transactionId=strtoupper($institudeIdCode.substr(md5(microtime()),rand(0,26),8));


        $feesModule=$this->autoSmsModule->where('ins_id',$instituteId)->where('campus_id',$campus_id)->where('status_code',"FEES")->where('status',1)->first();

        $paymentMethodList=$this->paymentMethod->where('institution_id',$instituteId)->where('campus_id',$campus_id)->get();
        $invoiceInfo=$this->feesInvoice->find($invoice_id);
        $paymentList=$this->getInvoicePaymentListByInvoice($invoice_id);
        // get due date count
        $fees=$invoiceInfo->fees();


        $stdExtraAmountProfile=$this->paymentExtra->where('student_id',$invoiceInfo->payer_id)->orderBy('created_at', 'desc')->first();
        if(!empty($stdExtraAmountProfile)>0){
            $stdExtraAmount=$stdExtraAmountProfile->extra_amount;
        } else {
            $stdExtraAmount=0;
        }

        return view('fees::pages.modal.invoice_payment',compact('paymentMethodList','invoiceInfo','paymentList','feesModule','stdExtraAmount','transactionId'));
    }


    public function invoicePaymentUpdateModal(Request $request, $paymentId)
    {


        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

        $paymentMethodList=$this->paymentMethod->where('institution_id',$instituteId)->where('campus_id',$campus_id)->get();
        $paymentInfo=$this->invoicePayment->find($paymentId);
        $invoiceInfo=$this->feesInvoice->find($paymentInfo->invoice_id);
        $paymentList=$this->getInvoicePaymentListByInvoice($paymentInfo->invoice_id);
        return view('fees::pages.modal.invoice_payment',compact('paymentMethodList','paymentInfo','invoiceInfo','paymentList'));
    }

    public function invoicePaymentViewModal(Request $request, $paymentId)
    {
        $paymentProfile=$this->invoicePayment->find($paymentId);
        return view('fees::pages.modal.invoice_payment_view',compact('paymentProfile'));
    }

    public  function  invoicePaymentUpdate(Request $request){

        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();


        $totalAmount=$request->input('total_amount');
        $payment_amount=$request->input('payment_amount');
        $paymentId=$request->input('payment_id');
        $invoicePayment=$this->invoicePayment->find($paymentId);
        $invoicePayment->institution_id=$instituteId;
        $invoicePayment->campus_id=$campus_id;
        $invoicePayment->fees_id=$request->input('fees_id');
        $invoicePayment->invoice_id=$request->input('invoice_id');
        $invoicePayment->payment_amount=$request->input('payment_amount');
        if($totalAmount>$payment_amount) {
            $invoicePayment->extra_payment_amount=0;
            $extraPaymentProfile= $this->paymentExtra->where('student_id',1)->first();
            $extraPaymentProfile->extra_amount=0;
            $extraPaymentProfile->institution_id=$instituteId;
            $extraPaymentProfile->campus_id=$campus_id;
            $extraPaymentProfile->save();
        } else {
            $extra_amount=$payment_amount-$totalAmount;
            $invoicePayment->extra_payment_amount=$extra_amount;
            $extraPaymentProfile= $this->paymentExtra->where('student_id',1)->first();
            $extraPaymentProfile->extra_amount=$extra_amount;
            $extraPaymentProfile->institution_id=$instituteId;
            $extraPaymentProfile->campus_id=$campus_id;
            $extraPaymentProfile->save();
        }
        $invoicePayment->payment_method_id=$request->input('payment_method');
        $invoicePayment->transaction_id=$request->input('transaction_id');
        $invoicePayment->payment_date=date('Y-m-d', strtotime($request->input('payment_date')));
        $invoicePayment->paid_by=$request->input('paid_by');
        $invoicePayment->payment_status="paid";
        $invoicePayment->save();
        $invoiceId=$request->input('invoice_id');
        $totalAmount=$request->input('total_amount');

        // Update Invoice Status
        $this->updateInoviceStatus($invoiceId, $totalAmount);
    }




    //get multiple Payment By Invoice Id
    public  function  getInvoicePaymentListByInvoice($invoiceId){
        return $this->invoicePayment->where('invoice_id',$invoiceId)->get();
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

//       return  $id.time().uniqid(mt_rand(),true);
//        return str_random(10);
//        return $s = substr(str_shuffle(str_repeat("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ", 10)), 0, 10);

        // payment metho get
        $paymentMethodProfile=$this->paymentMethod->where('id', $request->payment_method)->first();
        $paymentMethodProfile->method_name;

        if($paymentMethodProfile->method_name=="Pay Online") {

            $this->feespaymentGatewayRequest($request);
        }






//        // call guzzle http auto request
//        $client = new Client(); //GuzzleHttp\Client
//        $result = $client->request('POST', 'http://localhost:8080/sendsms', ['json' => $smsDataSet]);

//
        // student extra amount
        $totalExtraAmount = $request->input('total_extra_amount');
        // payer id
        $payer_id = $request->input('payer_id');
        $dueAmount = $request->input('due_amount');
        $fineAmount = $request->input('fine_amount');
        $total_amount = $request->input('total_amount');
        $paymentAmount = $request->input('payment_amount');
        $invoice_id = $request->input('invoice_id');
        $attendanceFine = $request->input('attendance_fine');
        $use_advance_amount = $request->input('use_advance_amount');
//        if (!empty($fineAmount)) {
//            $dueAmount = $dueAmount - $fineAmount;
//        }


        if (!empty($use_advance_amount)) {
            // remaining extra amount here
            if($totalExtraAmount>$dueAmount) {
//                return "dd";
                if (!empty($fineAmount)) {
                    $remainingExtraAmount = $totalExtraAmount - ($dueAmount + $fineAmount);
                    $extra_taken= $paymentAmount;
                } else {
                    $remainingExtraAmount = $totalExtraAmount - $dueAmount;
                    $extra_taken=$paymentAmount;
                }
                //return $remainingExtraAmount." ".$extra_taken;

            }elseif($totalExtraAmount+$paymentAmount==$dueAmount) {
                $remainingExtraAmount =0;
                $extra_taken=$dueAmount;
            }

            else {
                $remainingExtraAmount = $totalExtraAmount - $dueAmount;
                $extra_taken=$dueAmount;

            }


            return $this->invoicePaymentForStudent($request, $extra_taken,$remainingExtraAmount);



        } else {
            if($paymentAmount>$total_amount) {

                $extraAmount = $paymentAmount-$total_amount;
            } else {
                $extraAmount=0;
            }

            return $this->invoicePaymentForStudent($request,$extra_taken=0,$extraAmount);
        }


    }

    public function  test(){
        return "My name is Khna";
    }

//    public function  changeInvoiceStatus(Request $request){
//
//        return
//
//    }

    public function  storeStudentAttendanceFine(Request $request)
    {
//        return $request->all();
        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();
        $invoicePayment = new $this->invoicePayment();
        $invoicePayment->institution_id=$instituteId;
        $invoicePayment->campus_id=$campus_id;
        $invoicePayment->invoice_id = $request->input('invoice_id');
        $invoicePayment->payment_amount =$request->input('total_amount');
        $invoicePayment->transaction_id = $request->input('transaction_id');
        $invoicePayment->payment_method_id = $request->input('payment_method');
        $invoicePayment->payment_date = date('Y-m-d', strtotime($request->input('payment_date')));
        $invoicePayment->paid_by = $request->input('paid_by');
        $invoicePayment->payment_status = "paid";
        $invoicePayment->save();

        $invoice = $this->feesInvoice->find($request->invoice_id);
        $invoice->invoice_status = 1;
        $invoice->save();


        $this->invoicePaymentSummeryStore($invoicePayment->id,$request->input('payer_id'));

        // Update Invoice Status
        $this->updateInoviceStatus($request->input('invoice_id'), $request->input('total_amount'),$request->input('total_amount'));

        $automaticSms=$request->input('automatic_sms');
        $student_id=$request->input('payer_id');
        $invoicePaidId=$request->input('invoice_id');
        $paymentAmount=$request->input('total_amount');

        if ($automaticSms == "1") {

            $smsGetawayProfile=$this->smsInstitutionGetway->where('institution_id',$instituteId)->where('status',1)->first();
            if(!empty($smsGetawayProfile)) {
                $this->smsSender->create_fees_job($student_id, $invoicePaidId,$paymentAmount);
            }
            else {

                Session::flash('message','Sms doesn\'t send contact with admin');

                return redirect()->back();
            }
        }

        Session::flash('message','Fees amount successfully paid');
        return redirect()->back();

    }


// student invoice payment store function

    public  function invoicePaymentForStudent(Request $request,$extra_taken, $extraAmount){

//        return $request->all();


        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

//    echo "sdfsdf";
//    try {
        //find invoice due amount
        $dueAmount = $request->input('due_amount'); //1400
        $student_id = $request->input('payer_id');
        $fineAmount = $request->input('fine_amount'); //400

//        exit();
        $invoice_id=$request->input('invoice_id');

//        if ($dueAmount == "0" || $dueAmount==$fineAmount) {
//            $extraAmount = $paymentAmount - $dueAmount;
//            $this->storeExtraAmount($student_id, $extraAmount,$invoice_id);
//        }

//        if ($paymentAmount >= $dueAmount) {
//            echo "rdddd";
//            exit();
            $paymentAmount = $dueAmount;
            if(!empty($request->fees_id)) {

                if (!empty($fineAmount)) {
                    $this->invoiceFineStore($request, $fineAmount, "DUE_FINE");
                }


                if (!empty($fineAmount)) {
                    $paymentAmount = $dueAmount - ($fineAmount);
                }
            }


            $invoicePayment = new $this->invoicePayment();
            $invoicePayment->institution_id=$instituteId;
            $invoicePayment->campus_id=$campus_id;
            $invoicePayment->fees_id = $request->input('fees_id');
            $invoicePayment->invoice_id = $request->input('invoice_id');
            $invoicePayment->payment_amount = $paymentAmount;
            $invoicePayment->extra_payment_amount = $extraAmount;
            $invoicePayment->extra_taken = $extra_taken;
            $invoicePayment->payment_method_id = $request->input('payment_method');
            $invoicePayment->transaction_id = $request->input('transaction_id');
            $invoicePayment->payment_date = date('Y-m-d', strtotime($request->input('payment_date')));
            $invoicePayment->paid_by = $request->input('paid_by');
            $invoicePayment->payment_status = "paid";
            $invoicePayment->save();
            $invoiceId = $request->input('invoice_id');
            $totalAmount = $request->input('total_amount');


            $this->invoicePaymentSummeryStore($invoicePayment->id,$student_id);

            // Update Invoice Status
            $this->updateInoviceStatus($invoiceId, $totalAmount,$fineAmount);

//            if($extraAmount>0) {
                // extra Amount add
                $this->storeExtraAmount($student_id, $extraAmount,$extra_taken,$invoice_id);
//            }

//        }

//    }
//    catch (\Exception $exception){
//
//    } finally{
        $automaticSms=$request->input('automatic_sms');
        $student_id=$request->input('payer_id');
        $invoicePaidId=$request->input('invoice_id');
        $paymentAmount=$request->input('payment_amount');

        if ($automaticSms == "1") {

            $smsGetawayProfile=$this->smsInstitutionGetway->where('institution_id',$instituteId)->where('status',1)->first();
            if(!empty($smsGetawayProfile)) {
                $this->smsSender->create_fees_job($student_id, $invoicePaidId,$paymentAmount);
            }
            else {

                Session::flash('message','Sms doesn\'t send contact with admin');

                return redirect()->back();
            }
        }

        Session::flash('message','Fees amount successfully paid');
        return redirect()->back();
    }



// search  payment transaction

    public  function searchPaymentTransaction( Request $request)

    {
        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

        $fees_id=$request->input('fees_id');
        $fees_name=$request->input('fees_name');
        $payer_name=$request->input('payer_name');
        $payer_id=$request->input('std_id');

        $invoiceIdList=$this->feesInvoice->where('institution_id',$instituteId)->where('campus_id',$campus_id)->select('id','fees_id')->where('payer_id',$payer_id)->get();
        $invoiceIds=array();
        $feesIds=array();
        foreach ($invoiceIdList as $invoice) {
            $invoiceIds[] =$invoice->id;
            $feesIds[] =$invoice->fees_id;
        };

        $from_date= $request->input('search_start_date');
        $to_date= $request->input('search_end_date');
        $payment_date= $request->input('search_payment_date');
        $invoiceId=$request->input('search_invoice_id');
        $transactionId=$request->input('search_transaction_id');
        if(!empty($from_date)) {
            $from_date=date('Y-m-d H:i:s', strtotime($from_date));
        }
        if(!empty($to_date)) {
            $to_date=date('Y-m-d', strtotime($to_date));
            $to_date = new Carbon($to_date);
            $to_date = $to_date->endOfDay();
        }

        $allSearchInputs=array();
        // check fees_id
        if ($fees_id) {
            $allSearchInputs['fees_id'] = $fees_id;
        }
        // // check payment_status
        if ($invoiceId) {
            $allSearchInputs['invoice_id'] = $invoiceId;
        }

        if ($transactionId) {
            $allSearchInputs['transaction_id'] = $transactionId;
        }


        if ($payment_date) {
            $payment_date=date('Y-m-d H:i:s', strtotime($payment_date));
            $allSearchInputs['payment_date'] = $payment_date;
        }


        if(!empty($from_date) && !empty($to_date)) {
            if($invoiceIds) {

                $allPaymentTransaction = $this->invoicePayment->where($allSearchInputs)->whereIn('invoice_id', $invoiceIds)->where('institution_id',$instituteId)->where('campus_id',$campus_id)->whereBetween('created_at', [$from_date, $to_date])->paginate(10);
            } else {
                $allPaymentTransaction = $this->invoicePayment->where($allSearchInputs)->where('institution_id',$instituteId)->where('campus_id',$campus_id)->whereBetween('created_at', [$from_date, $to_date])->paginate(10);

            }

        } else {
            if($invoiceIds) {

                $allPaymentTransaction = $this->invoicePayment->where($allSearchInputs)->whereIn('invoice_id', $invoiceIds)->whereIn('fees_id', $feesIds)->where('institution_id',$instituteId)->where('campus_id',$campus_id)->paginate(10);
            } else {
                $allPaymentTransaction = $this->invoicePayment->where($allSearchInputs)->where('institution_id',$instituteId)->where('campus_id',$campus_id)->paginate(10);

            }

        }


        if ($allPaymentTransaction) {
            // all inputs
            $allInputs =[
                'fees_id' => $fees_id,
                'fees_name'=>$fees_name,
                'payer_id'=>$payer_id,
                'payer_name'=>$payer_name,
                'transaction_id'=>$transactionId,
                'invoice_id' => $invoiceId,
                'search_start_date' => $from_date,
                'search_end_date' => $to_date,
                'search_payment_date'=>$payment_date
            ];
            // return view
            $allInputs=(Object)$allInputs;
            $searchPaymentTransaction=1;
            return view('fees::pages.paymenttransaction', compact('searchPaymentTransaction','allPaymentTransaction','allInputs'))->with('page', 'paymenttransaction');
            // return redirect()->back()->with(compact('state'))->withInput();

        }

    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('fees::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('fees::edit');
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

    /**
     * @param $invoiceId
     * @param $totalAmount
     */
    public function updateInoviceStatus($invoiceId, $totalAmount,$fineAmount)
    {
        $paymentAmount = InvoicePayment::where('invoice_id', $invoiceId)->get()->sum('payment_amount');
        if ($totalAmount <= $paymentAmount) {
            $invoice = $this->feesInvoice->find($invoiceId);
            $invoice->invoice_status = "1";
            $invoice->save();
        } else {
//            $invoice_status="3";
//            if($fineAmount>0){
//                $invoice_status="1"; // invoice status paid
//            }
            $invoice_status="1";
            $invoice = $this->feesInvoice->find($invoiceId);
            $invoice->invoice_status = $invoice_status;
            $invoice->save();
        }
    }


    // store or update  Extra payment
    public  function storeExtraAmount($student_id,$extraAmount,$extra_taken,$invoice_id){

        if($extra_taken==0){

            if($extraAmount!=0) {
                // store payment advance table
                $instituteId=$this->academicHelper->getInstitute();
                $campus_id=$this->academicHelper->getCampus();

                $paymentAdvanceHistory = new $this->paymentAdvanceHistory;
                $paymentAdvanceHistory->student_id = $student_id;
                $paymentAdvanceHistory->institution_id=$instituteId;
                $paymentAdvanceHistory->campus_id=$campus_id;
                $paymentAdvanceHistory->amount = $extraAmount;
                $paymentAdvanceHistory->status = 1;
                $paymentAdvanceHistory->invoice_id = $invoice_id;
                $paymentAdvanceHistory->save();

                $paymentExtraProfile=$this->paymentExtra->where('student_id',$student_id)->first();
                if(!empty($paymentExtraProfile)) {

                    $paymentExtraProfile->extra_amount=$paymentExtraProfile->extra_amount+$extraAmount;
                    $paymentExtraProfile->save();
                } else {
                    $paymentExtra = new $this->paymentExtra;
                    $paymentExtra->student_id = $student_id;
                    $paymentExtra->institution_id=$instituteId;
                    $paymentExtra->campus_id=$campus_id;
                    $paymentExtra->extra_amount = $extraAmount;
                    //        $paymentExtra->invoice_id = $invoice_id;
                    $paymentExtra->save();
                }
            }


        } else {

            if($extraAmount!=0) {
                // store payment advance table
                $instituteId=$this->academicHelper->getInstitute();
                $campus_id=$this->academicHelper->getCampus();

                $paymentAdvanceHistory = new $this->paymentAdvanceHistory;
                $paymentAdvanceHistory->student_id = $student_id;
                $paymentAdvanceHistory->institution_id=$instituteId;
                $paymentAdvanceHistory->campus_id=$campus_id;
                $paymentAdvanceHistory->amount = $extraAmount;
                $paymentAdvanceHistory->status = 0;
                $paymentAdvanceHistory->invoice_id = $invoice_id;
                $paymentAdvanceHistory->save();

                $paymentExtraProfile=$this->paymentExtra->where('student_id',$student_id)->first();
                $paymentExtraProfile->extra_amount=$extraAmount;
                $paymentExtraProfile->save();

            }


        }



    }


    // invoice Payment  summary data store
    public function invoicePaymentSummeryStore($paymentId,$payerId){

        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

        $paymentProfile=$this->invoicePayment->find($paymentId);
        // payment amount
        $paymentAmount=$paymentProfile->payment_amount;
        if($paymentProfile->fees_id!=null) {
            // fees type by fees amount
            $fees_type = $paymentProfile->fees()->fees_type()->fee_type_name;
            // variable declare
            $fineAmount = 0;

            $dueFinePaid = $this->invoiceFine->where('payer_id', $payerId)->where('invoice_id', $paymentProfile->invoice_id)->where('status', 'DUE_FINE')->where('institution_id', $instituteId)->where('campus_id', $campus_id)->first();
            $dueFine = "";
            $attendanceFine = "";
            // fine amount
            if (!empty($dueFinePaid)) {
                $dueFine = $dueFinePaid->fine_amount;
            } else {
                $dueFine = 0;
            }


            $amountAndDiscount = $this->fees_amount_and_discount($paymentProfile->fees_id);

            $total = $paymentAmount;
            // attendance fine

            // summery data set
            $summary = array(
                'total' => $total,
                'fees' => array(
                    'amount' => $amountAndDiscount['payment_amount'],
                    'type' => $fees_type,
                ),
                'due_fine' => array(
                    'amount' => $dueFine,
                    'type' => "due_fine"
                ),
                'attendance_fine' => array(
                    'amount' => $attendanceFine,
                    'type' => "attend_fine"
                ),
                'discount' => $amountAndDiscount['discount'],
            );
        } else {
            $total = $paymentAmount;
            // summery data set
            $summary = array(
                'total' => $total,
                'fees' => array(
                    'amount' => $total,
                    'type' => 'attend_fine',
                ),
                'attendance_fine' => array(
                    'amount' => $total,
                    'type' => "attend_fine"
                ),
                'discount' => 0,
            );

        }

        $invoicePaymentSummery= new $this->invoicePaymentSummary;
        $invoicePaymentSummery->institution_id=$instituteId;
        $invoicePaymentSummery->campus_id=$campus_id;
        $invoicePaymentSummery->invoice_id=$paymentProfile->invoice_id;
        $invoicePaymentSummery->payment_id=$paymentId;
        $invoicePaymentSummery->summary=json_encode($summary);
        $invoicePaymentSummery->total=$total;
        $invoicePaymentSummery->save();

    }


    public function  fees_amount_and_discount($fees_id)
    {

        $feesProfile =$this->fees->find($fees_id);
        $subtotal = 0;
        $totalAmount = 0;
        $totalDiscount = 0;
        foreach ($feesProfile->feesItems() as $amount) {
            $subtotal += $amount->rate * $amount->qty;
        }

        if ($discount = $feesProfile->discount()) {
            $discountPercent = $discount->discount_percent;
            $totalDiscount = (($subtotal * $discountPercent) / 100);
            $totalAmount = $subtotal - $totalDiscount;
        }
        else {
            $totalAmount=$subtotal;
        }

        return $amountAndDiscount=array('payment_amount'=>$totalAmount,'discount'=>$totalDiscount);

    }


    // fine fine store all fine attendance fine and other fine here

    public function  invoiceFineStore(Request $request,$amount,$status){

        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

        $invoiceFine= new $this->invoiceFine;
        $invoiceFine->institution_id=$instituteId;
        $invoiceFine->campus_id=$campus_id;
        $invoiceFine->invoice_id=$request->input('invoice_id');
        $invoiceFine->payer_id=$request->input('payer_id');;
        $invoiceFine->fees_id=$request->input('fees_id');
        $invoiceFine->fine_amount=$amount;
        $invoiceFine->status="$status";
        $invoiceFine->late_day=date('Y-m-d');
        $invoiceFine->save();
    }



    // get attendance Fine by Student student id

//    public function  getAttendanceFine($fees)

    // attendance Fine IsRead  Confirm Message Here

    public function  attendanceFineIsReadAccept($student_id){
//        $studentAttendanceFineList=$this->attendanceFine->where('std_id', $student_id)->get();
        return DB::table('student_attendance_fine')->where('std_id', '=', $student_id)->update(array('is_read' => 1));

    }




/// student add waiver
    public function addStudentWaiver($invoiceId)
    {
        $invoiceProfile=$this->feesInvoice->find($invoiceId);

        if($invoiceProfile->invoice_status=="2") {
            $todayDate = date('Y-m-d');
            $studentdWaiverProfile = $this->studentWaiver->where('std_id', $invoiceProfile->payer_id)->where('end_date', '>=', $todayDate)->first();
            if ($studentdWaiverProfile->type == 1) {
                // waiver type ==1 meaning percent
                // waiver type==2 meaning amount
                $invoiceProfile->waiver_fees = $studentdWaiverProfile->value;
                $invoiceProfile->waiver_type = 1;
                $invoiceProfile->wf_status = 2;
                $invoiceProfile->save();

            } elseif ($studentdWaiverProfile->type == 2) {
                // waiver type ==1 meaning percent
                // waiver type==2 meaning amount
                $invoiceProfile->waiver_fees = $studentdWaiverProfile->value;
                $invoiceProfile->waiver_type = 2;
                $invoiceProfile->wf_status = 2;
                $invoiceProfile->save();

            }
        }

        $invoiceProfile=$this->feesInvoice->find($invoiceId);

        $subtotal=0; $totalAmount=0; $totalDiscount=0; $getDueFine=0;
        foreach($invoiceProfile->fees()->feesItems() as $amount) {
            $subtotal += $amount->rate*$amount->qty;
        }
        if($discount = $invoiceProfile->fees()->discount()) {
            $discountPercent=$discount->discount_percent;
            $totalDiscount=(($subtotal*$discountPercent)/100);
            $totalAmount=$subtotal-$totalDiscount;
        } else {
            $totalAmount=$subtotal;
        }
        // waiver
        if($invoiceProfile->waiver_type=="1")
        { $totalWaiver=(($totalAmount*$invoiceProfile->waiver_fees)/100);
            $totalAmount=$totalAmount-$totalWaiver;
        }
        elseif($invoiceProfile->waiver_type=="2") {
            $totalWaiver=$invoiceProfile->waiver_fees;
            $totalAmount=$totalAmount-$totalWaiver;
        }


        if($discount = $invoiceProfile->fees()->discount()) {
            $totalDiscount=(($subtotal*$discountPercent)/100);
        }



        if(!empty($invoiceProfile->waiver_fees)) {
            $totalDiscount=$totalDiscount+$totalWaiver;
        }



        $dueFinePaid=$invoiceProfile->invoice_payment_summary();
        $var_dueFine=0;
        if($dueFinePaid){
            $var_dueFine = json_decode($dueFinePaid->summary);
        }


        if($invoiceProfile->invoice_status=="1") {
            if(!empty($var_dueFine)) {
                $getDueFine=$var_dueFine->due_fine->amount;
            }
        }
        else {
            if(!empty($invoiceProfile->findReduction())) {
                $getDueFine=$invoiceProfile->findReduction()->due_fine;
            }
            else {
                $getDueFine=get_fees_day_amount($invoiceProfile->fees()->due_date);
            }
        }

        if($totalAmount+$getDueFine==0) {
            $instituteId=$this->academicHelper->getInstitute();
            $campus_id=$this->academicHelper->getCampus();

            $invoicePayment = new $this->invoicePayment();
            $invoicePayment->institution_id=$instituteId;
            $invoicePayment->campus_id=$campus_id;
            $invoicePayment->fees_id = $invoiceProfile->fees_id;
            $invoicePayment->invoice_id = $invoiceProfile->id;
            $invoicePayment->payment_amount = 0;
            $invoicePayment->extra_payment_amount = 0;
            $invoicePayment->payment_method_id =1;
            $invoicePayment->payment_date = date('Y-m-d');
            $invoicePayment->payment_status = "paid";
            $invoicePayment->save();
            $invoiceId =$invoiceProfile->id;
            $totalAmount = 0;

            $this->invoicePaymentSummeryStore($invoicePayment->id,$invoiceProfile->payer_id);

            // Update Invoice Status
            $invoice = $this->feesInvoice->find($invoiceId);
            $invoice->invoice_status = "1";
            $invoice->save();


        }


        Session::flash('message','Waiver Added Successfully');

        return redirect()->back();

    }



    public function rand_string( $length ) {
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $size = strlen( $chars );
        $str="";
        for( $i = 0; $i < $length; $i++) { $str .= $chars[ rand( 0, $size - 1 ) ]; }
        return $str;
    }

    public function redirect_to_merchant($url) {
        ?>
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head><script type="text/javascript">
                function closethisasap() { document.forms["redirectpost"].submit(); }
            </script></head>
        <body onLoad="closethisasap();">
        <form name="redirectpost" method="post" action="<?php echo $url; ?>"></form>
        </body>
        </html>
        <?php
        exit;
    }


    // invoice payment getaway reew

    public function feespaymentGatewayRequest (Request $request) {


        // student profile
        $studentProfile=$this->studentInformation->where('id', $request->payer_id)->first();

        $cur_random_value=$this->rand_string(10);
        $url = 'http://epwsandbox.com/payment/request.php';
        $fields = array(
            'store_id' => 'alokitosoftware',
            'amount' => $request->total_amount,
            'payment_type' => '',
            'currency' => 'BDT',
            'tran_id' => $cur_random_value,
            'cus_name' => $studentProfile->first_name.' '.$studentProfile->middle_name.' '.$studentProfile->last_name,
            'cus_email' => $studentProfile->email,
            'cus_add1' => 'House B-158, Road 22',
            'cus_add2' => 'Mohakhali DOHS',
            'cus_city' => 'Dhaka',
            'cus_state' => 'Dhaka',
            'cus_postcode' => '1206',
            'cus_country' => 'Bangladesh',
            'cus_phone' => '018837888373',
            'cus_fax' => 'Not¬Applicable',
            'ship_name' => 'Mr. XYZ',
            'ship_add1' => 'House B-121, Road 21',
            'ship_add2' => 'Mohakhali',
            'ship_city' => 'Dhaka',
            'ship_state' => 'Dhaka',
            'ship_postcode' => '1212',
            'ship_country' => 'Bangladesh',
            'desc' => 'Fees',
            'success_url' => 'http://www.abc.com/success.php',
            'fail_url' => 'http://www.abc.com/fail.php',
            'cancel_url' => 'http://www.abc.com/cancel.php',
            'opt_a' => 'Optional Value A',
            'opt_b' => 'Optional Value B',
            'opt_c' => 'Optional Value C',
            'opt_d' => 'Optional Value D',
            'signature_key' => 'a881724ec12e3fc477655d4e6b63c30d');
        $fields_string="";
        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        $fields_string = rtrim($fields_string, '&');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $url_forward = str_replace('"', '', stripslashes(curl_exec($ch)));
        curl_close($ch);

        // get institution
        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

        //get track code
        preg_match_all('#track=([^\s]+)#', $url_forward, $matches);
        $trackCode=implode(' ', $matches[1]);

        // store payment request
        $paymentGatewayRequestProfile= new PaymentGatewayRequest;
        $paymentGatewayRequestProfile->date=date('Y-m-d');
        $paymentGatewayRequestProfile->tran_id=$cur_random_value;
        $paymentGatewayRequestProfile->track_code=$trackCode;
        $paymentGatewayRequestProfile->request_data=json_encode($fields);
        $paymentGatewayRequestProfile->institution_id=$instituteId;
        $paymentGatewayRequestProfile->campus_id=$campus_id;
        $paymentGatewayRequestProfile->save();
        // curl redirect url



        // store payment response data
        $paymentGatewayResponsetProfile= new PaymentGatewayResponse;
        $paymentGatewayResponsetProfile->date=date('Y-m-d');
        $paymentGatewayResponsetProfile->request_id=$paymentGatewayRequestProfile->id;
        $paymentGatewayResponsetProfile->tran_id=$cur_random_value;
        $paymentGatewayResponsetProfile->track_code=$trackCode;
        $paymentGatewayResponsetProfile->response_data=$this->paymentResponseData();
        $paymentGatewayResponsetProfile->institution_id=$instituteId;
        $paymentGatewayResponsetProfile->campus_id=$campus_id;
        $paymentGatewayResponsetProfile->save();

        $this->redirect_to_merchant($url_forward);

    }






    public function paymentResponseData() {
        return '{"epw_txnid":"EPW1444230652117529",
                "mer_txnid":"EA4V9QSSW3",
                "risk_title":"Safe",
                "risk_level":"0",
                "merchant_id":"epw",
                "store_id":"epw",
                "amount":"10.00",
                "pay_status":"Successful",
                "status_code":"2",
                "status_title":"Successful Transaction",
                "cardnumber":"IBBL­mCash",
                "payment_processor":"IBBL",
                "bank_trxid":"0d3ea8f1da7e3cf366f426f35acd90a4a56b1bbd0c1657ef72a3716975af59de",
                "payment_type":"IBBL­mCash",
                "error_code":"Not­Available",
                "error_title":"Not­Available",
                "bin_country":"BANGLADESH",
                "bin_issuer":"Islami Bank Limited",
                "bin_cardtype":"Mobile Banking",
                "bin_cardcategory":"DEBIT",
                "date":"2015­10­07 21:10:52",
                "date_processed":"2015­10­08 1:25:31",
                "amount_currency":"10.00",
                "rec_amount":"9.82",
                "processing_ratio":"1.75",
                "processing_charge":"0.18",
                "ip":"117.20.41.3",
                "currency":"BDT",
                "currency_merchant":"BDT",
                "convertion_rate":"Not­Available",
                "opt_a":"Not­Available",
                "opt_b":"Not­Available",
                "opt_c":"Not­Available",
                "opt_d":"Not­Available",
                "verify_status":"PENDING",
                "call_type":"Post­Method",
                "email_send":"1",
                "doc_recived":"NO",
                "checkout_status":"Not­Paid­Yet"
                }';
    }





}
