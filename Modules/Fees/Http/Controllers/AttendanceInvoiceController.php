<?php

namespace Modules\Fees\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Modules\Academics\Entities\AttendanceUpload;
use Modules\Student\Entities\StudentInformation;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Setting\Entities\AttendanceFine;
use Modules\Student\Entities\StudentEnrollment;
use Modules\Student\Entities\StudentAttendanceFine;
use Modules\Student\Entities\StudentProfileView;
use Modules\Fees\Entities\FeesInvoice;
use Modules\Setting\Entities\AutoSmsModule;
use Modules\Fees\Entities\PaymentMethod;
use Modules\Fees\Entities\InvoicePayment;
use Modules\Fees\Entities\InvoicePaymentSummary;
use Modules\Fees\Entities\InvoiceFine;
use Modules\Fees\Entities\Fees;
use Carbon\Carbon;
use Session;
use Modules\Fees\Entities\PaymentExtra;
use DB;
use App\Http\Controllers\SmsSender;

use Illuminate\Support\Facades\Input;
use Modules\Setting\Entities\Institute;
use Modules\Setting\Entities\SmsInstitutionGetway;
use Modules\Fees\Http\Controllers\InvoicePaymentController;

class AttendanceInvoiceController extends Controller
{

    private $academicHelper;
    private $attendanceUpload;
    private $studentInformation;
    private $studentProfileView;
    private $attendanceFine;
    private $studentAttendanceFine;
    private $feesInvoice;
    private $studentEnrollment;
    private $institute;
    private $autoSmsModule;
    private $paymentMethod;
    private $invoicePayment;
    private $invoicePaymentSummary;
    private $invoiceFine;
    private $fees;
    private $paymentExtra;
    private $smsSender;
    private $smsInstitutionGetway;
    private $invoicePaymentController;



    // constructor
    public function __construct(AttendanceFine $attendanceFine, InvoicePaymentController $invoicePaymentController, SmsInstitutionGetway $smsInstitutionGetway, SmsSender $smsSender, PaymentExtra $paymentExtra, Fees $fees, InvoiceFine $invoiceFine, InvoicePaymentSummary $invoicePaymentSummary, InvoicePayment $invoicePayment, PaymentMethod $paymentMethod, AutoSmsModule $autoSmsModule,Institute $institute, StudentEnrollment $studentEnrollment, FeesInvoice $feesInvoice, StudentAttendanceFine $studentAttendanceFine, AttendanceUpload $attendanceUpload, StudentInformation $studentInformation, AcademicHelper $academicHelper, StudentProfileView $studentProfileView)
    {
        $this->attendanceUpload  = $attendanceUpload;
        $this->studentInformation  = $studentInformation;
        $this->academicHelper  = $academicHelper;
        $this->studentProfileView  = $studentProfileView;
        $this->attendanceFine  = $attendanceFine;
        $this->studentAttendanceFine  = $studentAttendanceFine;
        $this->feesInvoice  = $feesInvoice;
        $this->studentEnrollment  = $studentEnrollment;
        $this->institute  = $institute;
        $this->autoSmsModule  = $autoSmsModule;
        $this->paymentMethod  = $paymentMethod;
        $this->invoicePayment  = $invoicePayment;
        $this->invoicePaymentSummary  = $invoicePaymentSummary;
        $this->invoiceFine  = $invoiceFine;
        $this->fees  = $fees;
        $this->paymentExtra  = $paymentExtra;
        $this->smsInstitutionGetway  = $smsInstitutionGetway;
        $this->smsSender  = $smsSender;
        $this->invoicePaymentController  = $invoicePaymentController;
    }



    public function fineStudentList(Request $request)
    {

        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

        $attendance_from_date = date('Y-m-d 00:00:00',strtotime($request->input('attendance_from_date')));
        $attendance_to_date =  date('Y-m-d 23:59:59',strtotime($request->input('attendance_to_date')));

        $studentList=$this->studentAttendanceFine->whereBetween('date', [$attendance_from_date, $attendance_to_date])->where('ins_id',$instituteId)->where('campus_id',$campus_id)->where('is_read',0)->get();

        $studentAttendanceFineArray=array();
        foreach ($studentList as $std){
            $studentAttendanceFineSum=$this->calculateStudentAttendaceFine($std->std_id,$attendance_from_date,$attendance_to_date);
            if($studentAttendanceFineSum>0) {
                // std enrollment profile
                $stdEnroll = $std->enroll();
                $studentInfo=$std->enroll()->student();
                $batch = $stdEnroll->batch();
                $section = $stdEnroll->section();
                // std att. fine details
                $studentAttendanceFineArray[$std->std_id] = [
                    'id'=> $std->id,
                    'std_id'=> $std->std_id,
                    'gr_no'=>$stdEnroll->gr_no,
                    'name'=>$studentInfo->first_name." ".$studentInfo->middle_name." ".$studentInfo->last_name,
                    'enroll'=>$batch->batch_name." (".$section->section_name.") ",
                    'total_attendance_fine'=>$studentAttendanceFineSum
                ];
            }
        }

         $studentAttendanceFineArray;

        return view('fees::pages.modal.attendance.std_attend_list_invoice',compact('studentAttendanceFineArray'));


    }

    public function  calculateStudentAttendaceFine($stdId,$fromDate,$toDate){

        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

        $studentTotalFine= $this->studentAttendanceFine->where('ins_id',$instituteId)->where('campus_id',$campus_id)->where('std_id',$stdId)->whereBetween('date', array($fromDate, $toDate))->where('is_read',0)->sum('fine_amount');
        return $studentTotalFine;
    }




    public function  attendanceAndInvoiceSearchView() {
        $searchInvoice=0;
        return view('fees::pages.attendance_invoice_search',compact('searchInvoice'));
    }


    public function  attendanceAndInvoiceSearchResult(Request $request){
//            return $request->all();


        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

        $academics_years = session()->get('academic_year');
        $payer_id = $request->input('std_id');
        $payer_name = $request->input('payer_name');
        //due date
        $from_date = $request->input('search_start_date');
        $to_date = $request->input('search_end_date');
        $invoice_status = $request->input('invoice_status');
        $invoice_type = $request->input('invoice_type');

        if (!empty($from_date)) {
            $from_date = date('Y-m-d H:i:s', strtotime($from_date));
        }

        if (!empty($to_date)) {
            $to_date = date('Y-m-d', strtotime($to_date));
            $to_date = new Carbon($to_date);

            $to_date = $to_date->endOfDay();
        }


        $searchStudent=array();
        $allSearchInputs=[];
        $allFeesInvoices=array();

        // check payer_id
        if ($payer_id) {

            $searchStudent['std_id'] = $payer_id;
        }

//        return $searchStudent;

        $std_enrollments = $this->studentEnrollment->where($searchStudent)->first();

        if(!empty($std_enrollments)) {
//            return "dd";

            // // check payment_status

            if ($invoice_status) {
                $allSearchInputs['invoice_status'] = $invoice_status;
            }
              if ($invoice_type) {
                $allSearchInputs['invoice_type'] = $invoice_type;
            }

            if($invoice_type==1) {
                if (!empty($from_date) && !empty($to_date)) {
                    $allFeesInvoices = $this->feesInvoice->where($allSearchInputs)->where('payer_id', $std_enrollments->std_id)->whereBetween('due_date', array($from_date, $to_date))->orderBy('created_at', 'DESC')->paginate(30);
                }
            } else {
                $allFeesInvoices = $this->feesInvoice->where($allSearchInputs)->where('payer_id', $std_enrollments->std_id)->orderBy('created_at', 'DESC')->paginate(30);
            }

        } else {
            $allFeesInvoices=[];
        }






        // all inputs
        $allInputs =[

            'payer_name'=>$payer_name,
            'payer_id' => $payer_id,
            'search_start_date' => $from_date,
            'search_end_date' => $to_date,
            'invoice_status'=>$invoice_status,
            'invoice_type'=>$invoice_type,

        ];

//        return $allFeesInvoices;
        // return view
        $allInputs=(Object)$allInputs;

//             $allFeesInvoices;

        $searchInvoice=1;
        return view('fees::pages.attendance_invoice_search', compact('searchInvoice','allFeesInvoices','allInputs'));
        // return redirect()->back()->with(compact('state'))->withInput();

    }





    public function fineGenerateInvoice(Request $request){

        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

        $attendanceFineInvoiceList= $request->input('attendanceFineInvoice');
        foreach($attendanceFineInvoiceList as $key=>$stdInvoice){
//            $std_id=$attendanceFineInvoiceList[$i]['std_id'];
            // check old valu database
//         $studentAttendanceInvoice=$this->feesInvoice->where('fees_id',NULL)->where('payer_id',$stdInvoice['std_id'])->whereMonth('created_at', '=', date('m'))->first();

//            if(empty($studentAttendanceInvoice)) {
                 //check fine amount
                if ($stdInvoice['total_amount'] > 0) {
                    $feesInvoice = new $this->feesInvoice;
                    $feesInvoice->institution_id=$instituteId;
                    $feesInvoice->campus_id=$campus_id;
                    $feesInvoice->payer_id = $stdInvoice['std_id'];
                    $feesInvoice->payer_type = 1;
                    $feesInvoice->invoice_type = "2";
                    $feesInvoice->invoice_amount = $stdInvoice['total_amount'];
                    $feesInvoice->payer_type = 1;
                    $feesInvoice->invoice_status = "2";
                    $feesInvoice->wf_status= "1";
                    $feesInvoice->save();

                    // update attendance fine status is_read

                // pass absent id
                $this->updateAttendanceIsRead($stdInvoice['std_id']);


//
//                }
            }


                $message="Attendance Fine Invoice Successfully Added";

            }

        return $message;

    }

    public function  invoicePaymentProcessModal($invoiceIdList,$totalAmount){

        // invoice ids string to array conver
        $invoiceIdsArray=explode(",",$invoiceIdList);
        $invoiceList=$this->feesInvoice->whereIn('id',$invoiceIdsArray)->get();
        $student_id="";

        $institution_id=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

       $multipleFeesAmount=0; $gettotalAmount=0; $i=1;  $attendanceFine=0; $getDueFine=0; $totalDiscount=0; $day_fine_amount=0; $totalWaiver=0; $invoiceArrayList=array();
            foreach($invoiceList as $invoice) {
                if ($invoice->invoice_type == "1") {
                    $fees = $invoice->fees();
                    $singleFeesAmount = 0;
                    foreach ($fees->feesItems() as $item) {
                        $singleFeesAmount += $item->rate * $item->qty;
                    }

                    if (!empty($var_dueFine)) {
                        $getDueFine = $var_dueFine->due_fine->amount;
                    } else {
                        if (!empty($invoice->findReduction())) {
                            $getDueFine = $invoice->findReduction()->due_fine;
                        } else {
                            $getDueFine = get_fees_day_amount($invoice->fees()->due_date);
                                        }
                    }

                    if ($discount = $invoice->fees()->discount()) {
                        $discountPercent = $discount->discount_percent;
                        $totalDiscount = (($singleFeesAmount * $discountPercent) / 100);
                        $gettotalAmount = $singleFeesAmount - $totalDiscount;
                    } else {
                        $gettotalAmount = $singleFeesAmount;
                    }

                    if ($invoice->waiver_type == "1") {
                        $totalWaiver = (($gettotalAmount * $invoice->waiver_fees) / 100);
                        $gettotalAmount = $gettotalAmount - $totalWaiver;
                    } elseif ($invoice->waiver_type == "2") {
                        $totalWaiver = $invoice->waiver_fees;
                        $gettotalAmount = $gettotalAmount - $totalWaiver;
                                          }

                     if($discount = $invoice->fees()->discount()) {
                        $totalDiscount=(($singleFeesAmount*$discountPercent)/100);}

                    if(!empty($invoice->waiver_fees)) {
                        $totalDiscount=$totalDiscount+$totalWaiver;
                        }

                    $student_id=$invoice->payer_id;
//
//             {{--end Waiver Calculate --}}
                    // due fine calculate
//                    $day_fine_amount=get_fees_day_amount($fees->due_date);

                    $invoiceArrayList[]= ['id'=>$invoice->id, 'invoice_amount'=>$gettotalAmount,'due_amount'=>$getDueFine];
                }

                if ($invoice->invoice_type == "2") {
                    $attendanceFine = $invoice->invoice_amount;
                    $invoiceArrayList[]= ['id'=>$invoice->id, 'invoice_amount'=>$attendanceFine,'due_amount'=>0];
                    $student_id=$invoice->payer_id;
                }

            }

        // check fees automatic moudle
        $feesModule=$this->autoSmsModule->where('status_code',"FEES")->where('ins_id',$institution_id)->where('campus_id',$campus_id)->where('status',1)->first();

            $paymentMethodList=$this->paymentMethod->where('institution_id',$institution_id)->where('campus_id',$campus_id)->get();
//            $invoiceInfo=$this->feesInvoice->find($invoice_id);
//            $paymentList=$this->getInvoicePaymentListByInvoice($invoice_id);
//            // get due date count
//            $fees=$invoiceInfo->fees();
//            // day fine amount call helper function
//            $day_fine_amount=get_fees_day_amount($fees->due_date);
//
            $stdExtraAmountProfile=$this->paymentExtra->where('student_id',$invoice->payer_id)->orderBy('created_at', 'desc')->first();
            if(!empty($stdExtraAmountProfile)>0){
                $stdExtraAmount=$stdExtraAmountProfile->extra_amount;
            } else {
                $stdExtraAmount=0;
            }
//return $stdExtraAmount;


            return view('fees::pages.modal.invoice_payment_process',compact('totalAmount','paymentMethodList','feesModule','invoiceIdsString','invoiceArrayList','student_id','stdExtraAmount'));

    }


    // attendace Invoice Process here

    public  function attendanceInvoiceProcess(Request $request){
        $invoiceList=$request->input('invoice_id');
        $institute=$this->academicHelper->getInstituteProfile();
        $invoiceId="";
        $invoiceArray=array();
        foreach ($invoiceList as $key=>$value){
            $invoiceArray[]=$key;
            $invoiceId=$key;
        }

        $std_id=$this->getPayerIdbyInvoice($invoiceId);
        $studentInfo=$this->getStudentInfoById($std_id);

        // get invoice
        $invoiceList=$this->feesInvoice->whereIn('id',$invoiceArray)->get();
        $invoiceStatusCheck=$this->feesInvoice->whereIn('id',$invoiceArray)->where('invoice_status',1)->get();

        return view('fees::pages.show_attendance_invoice',compact('invoiceList', 'invoiceArray','institute','studentInfo','invoiceStatusCheck'))->with('page', '');

            }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('fees::index');
    }

    public function  getStudentInfoById($studentId){
       return $this->studentInformation->where('id',$studentId)->first();
    }


    // attendance invoice Payment Store here

    public function  attendanceInvoiceProcessStore(Request $request) {

//        return $request->all();

//        DB::beginTransaction();
//        try {
        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();


        $due_amount=$request->input('due_amount');
        $payment_amount=$request->input('payment_amount');
        $invoicesList= $request->input('invoices');
        $student_id=$request->input('student_id');
        $totalExtraAmount = $request->input('total_extra_amount');
        $use_advance_amount = $request->input('use_advance_amount');
         //return $invoicesList;
//         foreach (json_decode($invoicesList) as $invoice) {
//                 echo 'Id'.$invoice->id.'</br>';
//                 echo 'Amunt'.$invoice->invoice_amount.'</br>';
//             }

             // invoice array
        $invoicePaymentArray=array();
            if($payment_amount>=$due_amount) {

                $invoicePayment = new $this->invoicePayment;
                $invoicePayment->payment_amount = $payment_amount;
                $invoicePayment->institution_id = $instituteId;
                $invoicePayment->campus_id = $campus_id;
                $invoicePayment->payment_method_id = $request->input('payment_method');
                $invoicePayment->transaction_id = $request->input('transaction_id');
                $invoicePayment->payment_date = date('Y-m-d', strtotime($request->input('payment_date')));
                $invoicePayment->paid_by = $request->input('paid_by');
                $invoicePayment->payment_status = "paid";
                $insert = $invoicePayment->save();
                $paymentParentId = $invoicePayment->id;

                if ($insert) {

                    foreach (json_decode($invoicesList) as $invoice) {
                        $feesId = $this->getFeesIdByInvoice($invoice->id);
                        $invoicePayment = new $this->invoicePayment;
                        $invoicePayment->institution_id = $instituteId;
                        $invoicePayment->campus_id = $campus_id;
                        $invoicePayment->fees_id = $feesId;
                        $invoicePayment->invoice_id = $invoice->id;
                        $invoicePayment->parent_id = $paymentParentId;
                        $invoicePayment->payment_amount = $invoice->invoice_amount;
                        $invoicePayment->payment_method_id = $request->input('payment_method');
                        $invoicePayment->transaction_id = $request->input('transaction_id');
                        $invoicePayment->payment_date = date('Y-m-d', strtotime($request->input('payment_date')));
                        $invoicePayment->paid_by = $request->input('paid_by');
                        $invoicePayment->payment_status = "paid";
                        $invoicePayment->save();

                        // Insert Due Amount Single Invoice Invoice Due Table
                        if (!empty($invoice->due_amount > 0)) {
                            $this->invoiceFineStore($invoicePayment->invoice_id, $invoicePayment->fees_id, $student_id, $invoice->due_amount, "DUE_FINE");
                        }

                        // update invoice status
                        // value== invoice id
                        $this->updateInoviceStatus($invoice->id);

                        // invoice Payment Summary Data Insert Code
                        $this->invoicePaymentSummeryStore($invoicePayment->id, $student_id);

                    }

                    $extraAmount = $payment_amount - $due_amount;
                    if (!empty($use_advance_amount)) {
                            // remaining extra amount here
                            $remainingExtraAmount = $totalExtraAmount - $due_amount;
                            $extra_taken=$due_amount;
                        }else {
                            $remainingExtraAmount = 0;
                            $extra_taken=0;
                        }

//
                    $this->invoicePaymentController->storeExtraAmount($student_id,$extraAmount,$extra_taken,$invoicePayment->invoice_id);


                    $automaticSms=$request->input('automatic_sms');
                    $payment_amount=$request->input('payment_amount');
                    // automatic fees sms
                    if ($automaticSms == "1") {

                        $smsGetawayProfile=$this->smsInstitutionGetway->where('institution_id',$instituteId)->where('status',1)->first();
                        if(!empty($smsGetawayProfile)) {
                            $this->smsSender->create_fees_job($student_id, $invoicePayment->invoice_id,$payment_amount);
                        }
                        else {

                            Session::flash('message','Sms doesn\'t send contact with admin');

                            return redirect()->back();
                        }
                    }


                }
            }

           return redirect()->back();
//                DB::commit();
//                // all good
//            } catch (\Exception $e) {
//                DB::rollback();
//                // something went wrong
//              return redirect()->back();
//            }


    }

    // get fees id by invoice id
    public function getFeesIdByInvoice($invoiceId){
        $invoiceProfile=$this->feesInvoice->where('id',$invoiceId)->first();
        return $invoiceProfile->fees_id;
    }



    // get Payer  id by invoice id
    public function getPayerIdbyInvoice($invoiceId){
        $invoiceProfile=$this->feesInvoice->where('id',$invoiceId)->first();
        return $invoiceProfile->payer_id;
    }


    // update invoice fucntion

    public function updateInoviceStatus($invoiceId)
    {
            $invoice = $this->feesInvoice->find($invoiceId);
            $invoice->invoice_status = "1";
            $invoice->save();
    }




    // store data in invoice payment summery table
    // invoice Payment  summary data store
    public function invoicePaymentSummeryStore($paymentId,$payerId)
    {
        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

        $paymentProfile = $this->invoicePayment->find($paymentId);

        if (!empty($paymentProfile->fees_id)) {
            // payment amount
            $paymentAmount = $paymentProfile->payment_amount;
            // fees type by fees amount
            $fees_type = $paymentProfile->fees()->fees_type()->fee_type_name;
            $fees_type_id = $paymentProfile->fees()->fees_type()->id;
            // variable declare
            $fineAmount = 0;
            $subtotal = 0;
            $totalWaiver = 0;

                foreach($paymentProfile->fees()->feesItems() as $amount) {
                    $subtotal += $amount->rate * $amount->qty;
                }


            if($paymentProfile->invoice()->waiver_type=="1") {
                $totalWaiver = (($subtotal * $paymentProfile->invoice()->waiver_fees) / 100);
            } elseif($paymentProfile->invoice()->waiver_type=="2") {
                $totalWaiver = $paymentProfile->invoice()->waiver_fees;
            }

            $dueFinePaid = $this->invoiceFine->where('payer_id', $payerId)->where('invoice_id', $paymentProfile->invoice_id)->where('status', 'DUE_FINE')->first();
//            print_r($dueFinePaid);

//            $dueFine = "";

            $amountAndDiscount = 0;
            // fine amount

            // fine amount
            if(!empty($dueFinePaid)) {
                $dueFine = $dueFinePaid->fine_amount;
            } else {
                $dueFine=0;
            }

            $amountAndDiscount = $this->fees_amount_and_discount($paymentProfile->fees_id);

            $total = $paymentAmount;

            // summery data set
            $summary = array(
                'total' => $total,
                'fees' => array(
                    'amount' => $subtotal,
                    'type' => $fees_type,
                    'type_id'=>$fees_type_id,
                ),
                'due_fine' => array(
                    'amount' => $dueFine,
                    'type' => "due_fine"
                ),
                'attendance_fine' => array(
                    'amount' => 0,
                    'type' => "attend_fine"
                ),
                'discount' => $amountAndDiscount['discount'],
                'waiver' =>$totalWaiver,
            );

            $invoicePaymentSummery = new $this->invoicePaymentSummary;
            $invoicePaymentSummery->institution_id=$instituteId;
            $invoicePaymentSummery->campus_id=$campus_id;
            $invoicePaymentSummery->invoice_id = $paymentProfile->invoice_id;
            $invoicePaymentSummery->payment_id = $paymentId;
            $invoicePaymentSummery->summary = json_encode($summary);
            $invoicePaymentSummery->total = $total;
            $invoicePaymentSummery->save();

        }

        elseif(($paymentProfile->fees_id==NULL) and !empty($paymentProfile->invoice_id)) {

            $attendanceFine=$this->feesInvoice->where('id',$paymentProfile->invoice_id)->first(['invoice_amount']);
            $total= $attendanceFine->invoice_amount;
            // summery data set
            $summary = array(
                'total' => $total,
                'fees' => array(
                    'amount' => $total,
                    'type' => "fine",
                    'type_id'=>0,
                ),
                'due_fine' => array(
                    'amount' => 0,
                    'type' => "due_fine"
                ),
                'attendance_fine' => array(
                    'amount' => 0,
                    'type' => "attend_fine"
                ),
                'discount' =>0,
                'waiver' =>0,
            );

            $invoicePaymentSummery = new $this->invoicePaymentSummary;
            $invoicePaymentSummery->institution_id=$instituteId;
            $invoicePaymentSummery->campus_id=$campus_id;
            $invoicePaymentSummery->invoice_id = $paymentProfile->invoice_id;
            $invoicePaymentSummery->payment_id = $paymentId;
            $invoicePaymentSummery->summary = json_encode($summary);
            $invoicePaymentSummery->total = $total;
            $invoicePaymentSummery->save();

        }





    }

//    public  function getInvoiceProifleById($id){
//        return $this->invoicePayment->where('parent_id',$id)->first();
////        return "dd";
//    }

    // fees amount and discount here

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

    public function  invoiceFineStore($invoiceId,$feesId,$payerId,$amount,$status){

        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

        $invoiceFine= new $this->invoiceFine;
        $invoiceFine->invoice_id=$invoiceId;
        $invoiceFine->institution_id=$instituteId;
        $invoiceFine->campus_id=$campus_id;
        $invoiceFine->payer_id=$payerId;
        $invoiceFine->fees_id=$feesId;
        $invoiceFine->fine_amount=$amount;
        $invoiceFine->status="$status";
        $invoiceFine->late_day=date('Y-m-d');
        $invoiceFine->save();
    }



    // update attendance  status
        public function  updateAttendanceIsRead($std_id) {
        $unReadAbsentList=$this->studentAttendanceFine->where('std_id',$std_id)->where('is_read',0)->update(array('is_read' => 1));
  }



    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('fees::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
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
}
