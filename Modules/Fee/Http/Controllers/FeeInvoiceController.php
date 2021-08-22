<?php

namespace Modules\Fee\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Fee\Entities\FeeInvoice;
use Modules\Student\Entities\StudentProfileView;
class FeeInvoiceController extends Controller
{
    private  $feeAssign;
    private  $feeInvoice;
    private  $studentProfileView;
    private  $feeSubhead;
    private $data;

    public function  __construct(FeeInvoice $feeInvoice, StudentProfileView $studentProfileView)
    {
        $this->feeInvoice=$feeInvoice;
        $this->studentProfileView=$studentProfileView;
    }


    public function getSingleStudentInvoice(Request $request)
    {

//      return $request->all();
        $this->data['studentProfile']=$this->studentProfileView->where('std_id',$request->std_id)->first();
        $this->data['studentInvoiceList']=$this->feeInvoice->where('student_id',$request->std_id)->where('year',$request->year_id)->get();

        return view('fee::modal.singlestudent',$this->data);
    }



    // single student payment modal

    public function SingleStudentPaymentModal($invoiceId){
        $this->data['invoiceProfile']=$this->feeInvoice->where('id',$invoiceId)->first();
        return view('fee::modal.single-student-payment',$this->data);
    }


    public function multipleStudentInvoiceSearch(Request $request){
//        return $request->all();
         $this->data['feeInvoiceList']=$this->feeInvoice
             ->where('class_id',$request->class_id)
             ->where('section_id',$request->section)
             ->where('head_id',$request->fee_head)
             ->where('sub_head_id',$request->sub_head)
             ->get();
        return view('fee::modal.student-invoicelist',$this->data);

    }

    public function deleteInvoice($invoiceId) {

        $feesProfile=$this->feeInvoice
            ->where('id',$invoiceId)
            ->where('due_amount',0)
            ->first();
        if($feesProfile->delete()){
            return 'success';
        } else {
            return 'error';
        }
    }

    public  function  singleStudentAllInvoice($studentId){
        // invoice List show for parent
           $invoceList=$this->feeInvoice->where('student_id',$studentId)->orderBy('id','desc')->get();
           if($invoceList->count()>0) {
               foreach ($invoceList as $invoice) {
                   $yearMonth = date('Y-m', strtotime($invoice->created_at));
                   $invoicesByYearMonth[$yearMonth][] = $invoice;
               }
           } else {
               $invoicesByYearMonth=0;
           }
        return  $invoicesByYearMonth;
    }
}
