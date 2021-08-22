<?php

namespace Modules\Fee\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Fee\Entities\AbsetFineSetting;
use Modules\Academics\Entities\AttendanceUploadAbsent;
use Illuminate\Support\Facades\DB;
use Modules\Fee\Entities\FeeInvoice;
use Modules\Student\Entities\StudentProfileView;
use Modules\Fee\Entities\Transaction;
use App;
class MoneyReceiptController extends Controller
{
    private $academicHelper;
    private $data;
    private $absentFineSetting;
    private $attendanceUploadAbsent;
    private $feeInvoice;
    private $studentProfileView;
    private $transaction;
    public function  __construct( AcademicHelper $academicHelper, Transaction $transaction, StudentProfileView $studentProfileView, FeeInvoice $feeInvoice, AttendanceUploadAbsent $attendanceUploadAbsent, AbsetFineSetting $absentFineSetting)
    {
        $this->academicHelper=$academicHelper;
        $this->absentFineSetting=$absentFineSetting;
        $this->attendanceUploadAbsent=$attendanceUploadAbsent;
        $this->feeInvoice=$feeInvoice;
        $this->studentProfileView=$studentProfileView;
        $this->transaction=$transaction;

    }

   public function lateFineReceipt(Request $request){
        // get all late fine receipt
       $this->data['latefineReceiptArray']=$this->lateFineReceiptToArray($request);
       return view('fee::modal.money-receipt.late-fine.moneyreceipt',$this->data);
   }

   // late fine receipt download

        public function lateFineReceiptDownload(Request $request){
            $this->data['instituteInfo']=$this->academicHelper->getInstituteProfile();
            $this->data['start_date']=$request->start_date;
            $this->data['end_date']=$request->end_date;
            $this->data['latefineReceiptArray']=$this->lateFineReceiptToArray($request);

            view()->share($this->data);
            //generate PDf
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView('fee::modal.money-receipt.late-fine.download.pdf-late-fine-receipt')->setPaper('a4', 'portrait');
            return $pdf->stream();

        }


        public function lateFineReceiptToArray(Request $request){

            $studentList= $this->studentProfileView
                ->where('batch',$request->class_id)
                ->where('section',$request->section)
                ->where('academic_year',$request->year_id)
                ->pluck('std_id')->toArray();
            // get late fine transcation
            $receiptLatefineList=$this->transaction->whereIn('std_id',$studentList)
                ->where('institution_id',institution_id())
                ->where('campus_id',campus_id())
                ->where('academic_year',academic_year())
                ->where('payment_type',3) // 3 for late fine
                ->where('academic_year',$request->year_id)
                ->whereBetween('payment_date', [$request->start_date, $request->end_date])
                ->get();
            $receiptArrayList=array();
            $totalPaidAmount=0; $class='';$section='';
            foreach ($receiptLatefineList as $receipt){
                $studentProfile=$receipt->studentProfile();
                $receiptArrayList[$receipt->id]['std_id']=$studentProfile->username;
                $receiptArrayList[$receipt->id]['std_name']=$studentProfile->first_name.' '.$studentProfile->middle_name.' '.$studentProfile->last_name;
                $receiptArrayList[$receipt->id]['std_roll']=$studentProfile->gr_no;
                $receiptArrayList[$receipt->id]['std_class']=$studentProfile->batch()->batch_name;
                $receiptArrayList[$receipt->id]['std_section']=$studentProfile->section()->section_name;
                $receiptArrayList[$receipt->id]['std_paid_amount']=$receipt->amount;
                $receiptArrayList[$receipt->id]['std_date']=$receipt->payment_date;
                $receiptArrayList[$receipt->id]['transaction_id']=$receipt->id;
                // calcualte total paid amount
                $totalPaidAmount+=$receipt->amount;
                $class=$studentProfile->batch()->batch_name;;
                $section=$studentProfile->section()->section_name;;
            }
            return array('moneyreceipt'=>$receiptArrayList,'totalPaidamount'=>$totalPaidAmount,'class'=>$class,'section'=>$section);

        }





    public function absentFineReceipt(Request $request){
        $this->data['absentfineReceiptArray']=$this->absentFineReceiptToArray($request);
        return view('fee::modal.money-receipt.absent-fine.moneyreceipt',$this->data);
    }

    public function absentFineReceiptDownload(Request $request){
        $this->data['instituteInfo']=$this->academicHelper->getInstituteProfile();
        $this->data['start_date']=$request->start_date;
        $this->data['end_date']=$request->end_date;
        $this->data['absentfineReceiptArray']=$this->absentFineReceiptToArray($request);

        view()->share($this->data);
        //generate PDf
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('fee::modal.money-receipt.absent-fine.download.pdf-absent-fine-receipt')->setPaper('a4', 'portrait');
        return $pdf->stream();
    }


    // absent fine receipt array list

    public function  absentFineReceiptToArray(Request $request){

        $studentList= $this->studentProfileView
            ->where('batch',$request->class_id)
            ->where('section',$request->section)
            ->where('academic_year',$request->year_id)
            ->pluck('std_id')->toArray();
        // get late fine transcation
        $receiptAbsentfineList=$this->transaction
            ->where('institution_id',institution_id())
            ->where('campus_id',campus_id())
            ->where('academic_year',academic_year())
             ->whereIn('std_id',$studentList)
            ->where('payment_type',2) // 2 for absent fine
            ->where('academic_year',$request->year_id)
            ->whereBetween('payment_date', [$request->start_date, $request->end_date])
            ->get();
        $receiptArrayList=array();
        $totalPaidAmount=0; $class='';$section='';
        foreach ($receiptAbsentfineList as $receipt){
            $studentProfile=$receipt->studentProfile();
            $receiptArrayList[$receipt->id]['std_id']=$studentProfile->username;
            $receiptArrayList[$receipt->id]['std_name']=$studentProfile->first_name.' '.$studentProfile->middle_name.' '.$studentProfile->last_name;
            $receiptArrayList[$receipt->id]['std_roll']=$studentProfile->gr_no;
            $receiptArrayList[$receipt->id]['std_class']=$studentProfile->batch()->batch_name;
            $receiptArrayList[$receipt->id]['std_section']=$studentProfile->section()->section_name;
            $receiptArrayList[$receipt->id]['std_paid_amount']=$receipt->amount;
            $receiptArrayList[$receipt->id]['std_date']=$receipt->payment_date;
            $receiptArrayList[$receipt->id]['transaction_id']=$receipt->id;
            // calcualte total paid amount
            $totalPaidAmount+=$receipt->amount;
            $class=$studentProfile->batch()->batch_name;;
            $section=$studentProfile->section()->section_name;;
        }
        return array('moneyreceipt'=>$receiptArrayList,'totalPaidamount'=>$totalPaidAmount,'class'=>$class,'section'=>$section);

    }




    public function feeReceipt(Request $request){
//        return $request->all();

        $feeReceiptList=$this->transaction
            ->where('institution_id',institution_id())
            ->where('campus_id',campus_id())
            ->where('academic_year',academic_year())
            ->where('payment_type',1) // 1 for fee
            ->where('academic_year',$request->year_id)
            ->whereBetween('payment_date', [$request->start_date, $request->end_date])
            ->get();
        $this->data['feeReceiptArray']=$this->feeReceiptToArray($feeReceiptList);
        return view('fee::modal.money-receipt.fee.moneyreceipt',$this->data);
    }

    public function feeReceiptDownload(Request $request){
        $this->data['instituteInfo']=$this->academicHelper->getInstituteProfile();
        $this->data['start_date']=$request->start_date;
        $this->data['end_date']=$request->end_date;
       $feeReceiptList=$this->transaction
           ->where('institution_id',institution_id())
           ->where('campus_id',campus_id())
           ->where('academic_year',academic_year())
            ->where('payment_type',1) // 1 for fee
            ->where('academic_year',$request->year_id)
            ->whereBetween('payment_date', [$request->start_date, $request->end_date])
            ->get();

         $this->data['feeReceiptArray']=$this->feeReceiptToArray($feeReceiptList);

        view()->share($this->data);
        //generate PDf
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('fee::modal.money-receipt.fee.download.pdf-moneyreceipt')->setPaper('a4', 'portrait');
         return $pdf->stream();
    }


    public function feeReceiptToArray($feeReceiptList){
        $receiptArrayList=array();
        $totalPaidAmount=0;
        foreach ($feeReceiptList as $receipt){
            $studentProfile=$receipt->studentProfile();
            $receiptArrayList[$receipt->id]['std_id']=$studentProfile->username;
            $receiptArrayList[$receipt->id]['std_name']=$studentProfile->first_name.' '.$studentProfile->middle_name.' '.$studentProfile->last_name;
            $receiptArrayList[$receipt->id]['std_roll']=$studentProfile->gr_no;
            $receiptArrayList[$receipt->id]['std_class']=$studentProfile->batch()->batch_name;
            $receiptArrayList[$receipt->id]['std_section']=$studentProfile->section()->section_name;
            $receiptArrayList[$receipt->id]['std_fee_head']=$receipt->invoiceProfile()->feehead()->name;
            $receiptArrayList[$receipt->id]['std_sub_head']=$receipt->invoiceProfile()->subhead()->name;
            $receiptArrayList[$receipt->id]['std_paid_amount']=$receipt->amount;
            $receiptArrayList[$receipt->id]['std_date']=$receipt->payment_date;
            $receiptArrayList[$receipt->id]['transaction_id']=$receipt->id;
            // calcualte total paid amount
            $totalPaidAmount+=$receipt->amount;
        }
        return array('moneyreceipt'=>$receiptArrayList,'totalPaidamount'=>$totalPaidAmount);


    }


    public function  studentMoneyReceipt($transactionId){
        $this->data['instituteInfo']=$this->academicHelper->getInstituteProfile();
        $this->data['transactionProfile'] =$this->transaction->find($transactionId);

        return view('fee::modal.money-receipt.student.single-student',$this->data);

    }



}
