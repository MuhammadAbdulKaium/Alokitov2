<?php

namespace Modules\Fee\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Fee\Entities\Transaction;
use Modules\Fee\Entities\FeeInvoice;
use Modules\Fee\Entities\WaiverAssign;
use Modules\Fee\Entities\SubHeadFund;
use Modules\Fee\Entities\FeeFundCollection;
use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Support\Facades\DB;
class TransactionController extends Controller
{
    private  $transaction;
    private  $feeInvoice;
    private  $waiverAssign;
    private  $subHeadFund;
    private  $feeFundCollection;
    private  $academicHelper;
    private  $data;

    public function __construct(SubHeadFund $subHeadFund, Transaction $transaction,FeeInvoice $feeInvoice,WaiverAssign $waiverAssign, FeeFundCollection $feeFundCollection,AcademicHelper $academicHelper)
    {
     $this->transaction=$transaction;
     $this->feeInvoice=$feeInvoice;
     $this->waiverAssign=$waiverAssign;
     $this->subHeadFund=$subHeadFund;
     $this->feeFundCollection=$feeFundCollection;
     $this->academicHelper=$academicHelper;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function singleStudentPayment(Request $request)
    {

        // invoice profile
        $invoiceProfile=$this->feeInvoice->find($request->invoice_id);

        DB::beginTransaction();

        try {
            // insert data to transaction table
            $transactionObj=new $this->transaction;
            $transactionObj->institution_id=$invoiceProfile->institution_id;
            $transactionObj->campus_id=$invoiceProfile->campus_id;
            $transactionObj->invoice_id=$request->invoice_id;
            $transactionObj->head_id=$invoiceProfile->head_id;
            $transactionObj->academic_year=$invoiceProfile->academic_year;
            $transactionObj->std_id=$request->std_id;
            $transactionObj->amount=$request->paid_amount;
            $transactionObj->payment_date=date('Y-m-d');
            $transactionObj->payment_status=1;
            $transactionObj->payment_type=1; // 1 for fees
            $transactionObj->paid_by=$request->user()->id;
            $transactionObj->recipt_no=001;
            $transactionObj->save();

            if($request->due_amount==0)
            {
                $invoiceProfile->due_amount=0;
                $invoiceProfile->paid_amount=$invoiceProfile->paid_amount+$request->paid_amount;
                $invoiceProfile->status='paid';  // full paid
                $invoiceProfile->save();

//              sub head id amout
//              $this->fundAmountStoreBySubID($request->sub_id,$request->paid_amount);
            } 

            else 
            {
                $invoiceProfile->due_amount=$request->due_amount;
                $invoiceProfile->waiver_amount=$request->waiver_amount;
                $invoiceProfile->waiver_type=$request->waiver_type;
                $invoiceProfile->paid_amount=$invoiceProfile->paid_amount+$request->paid_amount;
                $invoiceProfile->status='partial';   // partial payment
                $invoiceProfile->save();
//              sub head id amout
//              $this->fundAmountStoreBySubID($request->sub_id,$request->paid_amount);
            }

            DB::commit();

            return [
                'transaction_id'=>$transactionObj->id,
                'invoice_id'=>$request->invoice_id,
                'status'=>'success',
                'due_amount'=>$invoiceProfile->due_amount,
                'paid_amount'=>$invoiceProfile->paid_amount,
            ];
        }

        catch (\Exception $e) 
        {
            DB::rollback();
            // something went wrong
            return $e;
        }


//      return $this->printInvoiceView($transactionObj->id);
//      return redirect('/fee/single-student/invoice/payment/'.$transactionObj->id);
    }



    public function  printStudentInvoice($transactionId){
        $this->data['instituteInfo']=$this->academicHelper->getInstituteProfile();
        $this->data['transactionProfile'] =$this->transaction->find($transactionId);

        return view('fee::pages.report.receipt-single-view',$this->data);
    }




    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function singleStudentMultipleInvoicePayment(Request $request)
    {
        $invoiceList = $request->invoice_ids;
        $invoiceIdArray = explode(',', $invoiceList);
        $invoiceValueArray = explode(',', $request->invoice_payable);
        $invoiceListArray = array_combine($invoiceIdArray, $invoiceValueArray);



        DB::beginTransaction();
        try {

            // student list for loop and insert data
            foreach ($invoiceListArray as $key => $value) {

                // invoice profile
                $invoiceProfile = $this->feeInvoice->find($key);

                // insert invoice
                $invoiceProfile->due_amount = 0;
                $waiverProfile = $invoiceProfile->isWaiver($invoiceProfile->student_id, $invoiceProfile->head_id, $invoiceProfile->amount);
                $invoiceProfile->waiver_type = $waiverProfile['waiver_type'];
                $invoiceProfile->paid_amount = $invoiceProfile->amount - $waiverProfile['waiver'];
                $invoiceProfile->status = 'paid';   // partial payment
                $result = $invoiceProfile->save();
                if ($result) {
                    $transactionObj = new $this->transaction;
                    $transactionObj->institution_id = institution_id();
                    $transactionObj->campus_id = campus_id();
                    $transactionObj->academic_year = academic_year();
                    $transactionObj->invoice_id = $invoiceProfile->id;
                    $transactionObj->head_id = $invoiceProfile->head_id;
                    $transactionObj->amount = $value;
                    $transactionObj->std_id = $invoiceProfile->student_id;
                    $transactionObj->payment_date = date('Y-m-d');
                    $transactionObj->payment_status = 1;
                    $transactionObj->payment_type = 1; // 1 for fees
                    $transactionObj->paid_by = '1';
                    $transactionObj->recipt_no = 001;
                    $transactionObj->save();

                }
            }
            DB::commit();

            return 'sucecess';
        }

        catch (\Exception $e) {
                DB::rollback();
                // something went wrong
                return $e;
            }

    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function multiplePaymentStore(Request $request)
    {
//        return $request->all();

        $invoiceList = $request->invoice_ids;
        $invoiceIdArray = explode(',', $invoiceList);
         $invoiceValueArray = explode(',', $request->invoice_payable);
         $invoiceListArray = array_combine($invoiceIdArray, $invoiceValueArray);
        DB::beginTransaction();
        try {

//        return $this->printMultipleReceipt([16,17,18]);
            $transactionListArray = array();
            // student list for loop and insert data
            foreach ($invoiceListArray as $key => $value) {

                // invoice profile
                $invoiceProfile = $this->feeInvoice->find($key);

                // insert invoice
                $invoiceProfile->due_amount = 0;

                $waiverProfile = $invoiceProfile->isWaiver($invoiceProfile->student_id, $invoiceProfile->head_id, $invoiceProfile->amount);
                $invoiceProfile->waiver_type = $waiverProfile['waiver_type'];
                $invoiceProfile->paid_amount = $invoiceProfile->amount - $waiverProfile['waiver'];
                $invoiceProfile->status = 'paid';   // partial payment
                $result = $invoiceProfile->save();
                if ($result) {

                    $transactionObj = new $this->transaction;
                    $transactionObj->institution_id = institution_id();
                    $transactionObj->campus_id = campus_id();
                    $transactionObj->head_id = $invoiceProfile->head_id;
                    $transactionObj->academic_year = academic_year();
                    $transactionObj->invoice_id = $invoiceProfile->id;
                    $transactionObj->amount = $value;
                    $transactionObj->std_id = $invoiceProfile->student_id;
                    $transactionObj->payment_date = date('Y-m-d');
                    $transactionObj->payment_status = 1;
                    $transactionObj->payment_type = 1;// 1 for fees
                    $transactionObj->paid_by = '1';
                    $transactionObj->recipt_no = 001;
                    $transactionObj->save();
                    $transactionListArray[] = $transactionObj->id;
                }

            }
            DB::commit();

//        return $this->printMultipleReceipt($transactionListArray);
            $arraytransactionSting = json_encode($transactionListArray);
            return redirect('/fee/multiple-student/multiple/payment/' . $arraytransactionSting);
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }

    }


    public function  printMultipleReceipt($transactionListArray){
        $instituteInfo=$this->academicHelper->getInstituteProfile();
        $transactionListArray=json_decode($transactionListArray);
         $transactionList=$this->transaction->whereIn('id',$transactionListArray)->get();

        return view('fee::pages.report.receipt-multiple-view',compact('transactionList','instituteInfo'));
    }



    /**
     * Show the specified resource.
     * @return Response
     */
    public function fundAmountStoreBySubID($subID,$amount)
    {
        $subFundList=$this->subHeadFund->where('sub_head_id',$subID)->get();
            foreach ($subFundList as $fund){
                    $feeFundCollectionObj= new $this->feeFundCollection;
                    $feeFundCollectionObj->institution_id=institution_id();
                    $feeFundCollectionObj->campus_id=campus_id();
                    $feeFundCollectionObj->academic_year=$this->academicHelper->getAcademicYear();
                    $feeFundCollectionObj->fund_id=$fund->fund_id;
                    $feeFundCollectionObj->amount=$this->getPercentOfNumber($fund->percentage,$amount);
                     $feeFundCollectionObj->payment_date=date('Y-m-d');
                     $feeFundCollectionObj->save();
                }
    }

    // get percentange to number
    public function getPercentOfNumber($number, $percent){
        return ($percent / 100) * $number;
    }

}
