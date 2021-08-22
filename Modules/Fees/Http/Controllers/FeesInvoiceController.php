<?php

namespace Modules\Fees\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Modules\Fees\Entities\FeesInvoice;
use Modules\Fees\Entities\Fees;
use Modules\Academics\Entities\Batch;
use Modules\Academics\Entities\Section;
use Modules\Student\Entities\StudentEnrollment;
use Modules\Student\Entities\StudentInformation;
use Modules\Academics\Entities\AcademicsLevel;
use Modules\Setting\Entities\Institute;
use Modules\Fees\Entities\InvoicePayment;
use Modules\Fees\Entities\PaymentExtra;
use Modules\Fees\Entities\FeesBatchSection;
use Modules\Student\Entities\StudentWaiver;
use Modules\Setting\Entities\FessSetting;
use Modules\Fees\Entities\InvoiceFine;
use Modules\Fees\Entities\FeesItem;
use Modules\Student\Entities\StudentAttendanceFine;
use Modules\Academics\Entities\AcademicsYear;
use App\Http\Controllers\Helpers\AcademicHelper;
use App\Http\Controllers\SmsSender;
use Modules\Setting\Entities\SmsInstitutionGetway;
use Modules\Setting\Entities\AutoSmsModule;
use Modules\Student\Entities\StudentProfileView;

use MPDF;
use Illuminate\Support\Facades\View;

use App;
use Excel;
use Carbon\Carbon;
use Session;

use Illuminate\Support\Facades\Input;

class FeesInvoiceController extends Controller
{
    private  $academicsLevel;
    private  $feesInvoice;
    private  $fees;
    private  $institute;
    private  $invoicePayment;
    private  $batch;
    private  $section;
    private  $paymentExtra;
    private  $feesBatchSection;
    private  $studentWaiver;
    private  $feeSetting;
    private  $invoiceFine;
    private  $feesItems;
    private  $studentAttendanceFine;
    private  $academicsYear;
    private  $academicHelper;
    private  $studentInformation;
    private  $smsSender;
    private  $smsInstitutionGetway;
    private  $autoSmsModule;
    private  $studentProfileView;

    public function __construct(AutoSmsModule $autoSmsModule, StudentProfileView $studentProfileView, Fees $fees,SmsInstitutionGetway $smsInstitutionGetway, SmsSender $smsSender, AcademicHelper $academicHelper, AcademicsYear $academicsYear, StudentInformation $studentInformation, StudentAttendanceFine $studentAttendanceFine,FeesItem $feesItems, InvoiceFine $invoiceFine, FessSetting $feeSetting, FeesBatchSection $feesBatchSection, StudentWaiver $studentWaiver,  PaymentExtra $paymentExtra,StudentEnrollment $studentEnrollment, AcademicsLevel $academicsLevel, FeesInvoice $feesInvoice,Institute $institute,InvoicePayment $invoicePayment, Batch $batch,Section $section)
    {
        $this->academicsLevel       = $academicsLevel;
        $this->studentEnrollment    = $studentEnrollment;
        $this->feesInvoice          = $feesInvoice;
        $this->fees                  = $fees;
        $this->institute             = $institute;
        $this->invoicePayment        = $invoicePayment;
        $this->studentInformation        = $studentInformation;
        $this->batch                  = $batch;
        $this->section                = $section;
        $this->paymentExtra           = $paymentExtra;
        $this->feesBatchSection       = $feesBatchSection;
        $this->studentWaiver        = $studentWaiver;
        $this->feeSetting              = $feeSetting;
        $this->invoiceFine            = $invoiceFine;
        $this->feesItems           = $feesItems;
        $this->studentAttendanceFine  = $studentAttendanceFine;
        $this->academicsYear  = $academicsYear;
        $this->academicHelper                = $academicHelper;
        $this->smsSender                = $smsSender;
        $this->smsInstitutionGetway                = $smsInstitutionGetway;
        $this->autoSmsModule                = $autoSmsModule;
        $this->studentProfileView                = $studentProfileView;
    }


    // get invoice list by fees id and Name searching
    //
    public function getInvoiceListByFeesIdName(Request $request)
    {

        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();
         $fees_id_single = $request->input('fees_id_single');
        $fees_id = $request->input('fees_id');
        $fees_name = $request->input('fees_name');
        if(!empty($fees_id_single)) {
            $fees_id_single=$fees_id_single;
        } elseif($fees_id) {
            $fees_id_single=$fees_id;
        }  else {
            $fees_id_single=null;
        }
//        return $request->all();


        $feesId=0;


         $feesProfile=$this->fees->orWhere('id',$fees_id_single)->first();
         if(!empty($feesProfile)) {

             $allFeesInvoices = $this->feesInvoice->where('institution_id',$instituteId)->where('campus_id',$campus_id)->where('fees_id', $feesProfile->id)->orderBy('id','desc')->paginate(10);
         } else {
             $allFeesInvoices=[];
         }




        // all inputs
            $allInputs = [
                'fees_id' => $fees_id,
                'fees_id_single' => $fees_id_single,
                'fees_name' => $fees_name
            ];
            // return view
            $allInputs = (Object)$allInputs;

            $searchInvoice = 1;
            return view('fees::pages.feesmanage',compact('searchInvoice','allInputs','allFeesInvoices','feesId','feesProfile'))->with('page', 'feesmanage');


    }


    //single payer add modal
    public function  getAddPayerModal($fees_id) {

        // get fees sms modules

        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();
        $feesModule=$this->autoSmsModule->where('ins_id',$instituteId)->where('campus_id',$campus_id)->where('status_code',"FEES")->where('status',1)->first();
        return view('fees::pages.modal.add_payer_fees',compact('fees_id','feesModule'));
    }


    // get class section payer add modal here
    public function  getAddPayerClassSectionModal($fees_id) {

        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();
        $feesModule=$this->autoSmsModule->where('ins_id',$instituteId)->where('campus_id',$campus_id)->where('status_code',"FEES")->where('status',1)->first();

         $academicLevels=$this->academicsLevel->where('institute_id',$instituteId)->where('campus_id',$campus_id)->orderBy('id','desc')->get();

        return view('fees::pages.modal.add_payer_class_section',compact('fees_id','academicLevels','feesModule'));
    }



    public function  addPayerClassSectionFeesInvoice(Request $request) {
//        return "ddd";
//        return $request->all();
//        $academic_year=$request->input('academic_year');
        $academic_level=$request->input('academic_level');
        $batch=$request->input('batch');
        $section=$request->input('section');
        $fees_id=$request->input('fees_id');

        $stdList = $this->studentProfileView->where(['academic_level'=>$academic_level,'batch'=>$batch, 'section'=>$section])->get();
        $stdListArray=array();
        foreach ($stdList as $key=>$std) {
            $studentProfile=$std->student();
            $stdListArray[$key][]=strval($std->std_id);
            $stdListArray[$key][]=$studentProfile->first_name.' '.$studentProfile->middle_name.' '.$studentProfile->last_name;
        }

        $stdlistDataSet=array(
            'data'=>$stdListArray
        );
        return $stdlistDataSet;

//        return  view('fees::pages.modal.class_section_student_search',compact('stdList','fees_id'));
//        $html =  view('fees::pages.modal.class_section_student_search',compact('stdList'))->render();
//
//        if($stdList->count()>0) {
//            return ['status'=>'success', 'html'=>$html];
//        } else {
//            return ['status'=>'error', 'msg'=>''];
//        }


    }


    // fees class section invoice store function
    public function  addPayerClassSectionFeesInvoiceStore(Request $request){


         $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();
        $auto_sms=$request->input('auto_sms');

////        class section student list here
        $fees_id= $request->input('fees_id');
         $stdList= $request->input('studentId');

         // fees profile
        $feesProfile=$this->fees->find($fees_id);

         $studentListArray=explode(",",$stdList);
        $studentInvoicelist=array();
        foreach ($studentListArray as $std){

                $studentFeesInvoiceProfile=$this->feesInvoice->where('fees_id',$fees_id)->where('payer_id',$std)->first();
                if(empty($studentFeesInvoiceProfile)) {
                    $feesInvoice = new $this->feesInvoice;
                    $feesInvoice->institution_id=$instituteId;
                    $feesInvoice->campus_id=$campus_id;
                    $feesInvoice->fees_id =$fees_id;
                    $feesInvoice->payer_id = $std;
                    $feesInvoice->payer_type = 1;
                    $feesInvoice->invoice_type = 1;
                    $feesInvoice->invoice_status = "2";
                    $feesInvoice->wf_status= "1";
                    $feesInvoice->due_date=$feesProfile->due_date;
                    $result=$feesInvoice->save();
                    if($result) {
                        $studentInvoicelist[]=$std;
                    }
                }
            }


        $smsGetawayProfile=$this->smsInstitutionGetway->where('institution_id',$instituteId)->where('status',1)->first();
        if(!empty($smsGetawayProfile) && !empty($studentInvoicelist) && ($auto_sms=="1")) {
            $this->smsSender->create_fees_multisms_generate_job($fees_id,$studentInvoicelist);
        }
            return "success";

//            return redirect()->back();





//        $stdIds=array();
//        foreach ($stdList as $key=>$std){
//            $stdIds[]=$key;
//        }
//        return $stdIds;
//        return $studentFeesInvoiceProfile=$this->feesInvoice->where('fees_id',$fees_id)->whereNotIn('payer_id',$stdIds)->get();
//
    }



    /**
     * Display a listing of the resource.
     * @return Response
     */

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
// store Student Fees Invoice   Payer Add code

    public function  addPayerFeesInvoice(Request $request) {


        $auto_sms=$request->input('auto_sms');
        // varibale decalarire
        $return=0;

        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

        $student_id=$request->input('std_id');
        $fees_id= $request->input('fees_id');
        // fees profile
        $feesProfile=$this->fees->find($fees_id);
        $studentFeesInvoiceProfile=$this->feesInvoice->where('fees_id',$fees_id)->where('payer_id',$student_id)->first();
// check student information profile
        if(empty($studentFeesInvoiceProfile)) {
            $feesPayers = new $this->feesInvoice;
            $feesPayers->institution_id=$instituteId;
            $feesPayers->campus_id=$campus_id;
            $feesPayers->fees_id =$fees_id;
            $feesPayers->payer_id = $student_id;
            $feesPayers->payer_type = 1;
            $feesPayers->invoice_type = 1;
            $feesPayers->invoice_status = "2";
            $feesPayers->wf_status= "1";
            $feesPayers->due_date= $feesProfile->due_date;
            $feesPayers->save();
            $feesPayers->id;

            // invoice profile
            $invoice=$this->feesInvoice->find($feesPayers->id);

            if($invoice->due_fine_amount()) {
                $due_fine_amount=$invoice->due_fine_amount()->fine_amount;
            }
            else {
                $due_fine_amount=0;
            }

            $fees=$invoice->fees();
            $std=$invoice->payer();

            $subtotal=0; $totalAmount=0; $totalDiscount=0;
            $getDueFine=0;
            foreach($fees->feesItems() as $amount) {
                $subtotal += $amount->rate*$amount->qty;
                                    }
                                    if($discount = $invoice->fees()->discount()) {
                                        $discountPercent=$discount->discount_percent;
                                        $totalDiscount=(($subtotal*$discountPercent)/100);
                                        $totalAmount=$subtotal-$totalDiscount;
                                        }

                                    else {
                                        $totalAmount=$subtotal;
                                    }

                                if($invoice->waiver_type=="1") {
                                    $totalWaiver=(($subtotal*$invoice->waiver_fees)/100);
                                    $totalAmount=$totalAmount-$totalWaiver;
                                    }
                                elseif($invoice->waiver_type=="2") {
                                    $totalWaiver=$invoice->waiver_fees;
                                    $totalAmount=$subtotal-$totalWaiver;
                                   }

                                        $dueFinePaid=$invoice->invoice_payment_summary();
                                        if($dueFinePaid){
                                            $var_a = json_decode($dueFinePaid->summary);
                                        }
                                    if(!empty($var_a)) {

                $getDueFine=$var_a->due_fine->amount;
            }
            else {

                $getDueFine=get_fees_day_amount($invoice->fees()->due_date);
            }


                                    if($discount = $invoice->fees()->discount()) {
                                        $totalDiscount=(($subtotal*$discountPercent)/100);
                                    }

                                    if(!empty($invoice->waiver_fees)) {
                                        $totalDiscount=$totalDiscount+$totalWaiver;
                                    }

                                    $amount=$totalAmount+$getDueFine;

               // waiver
            $waiverInfo=0;
            if(!empty($invoice->payer()->student_waiver()) && ($invoice->payer()->student_waiver()->end_date>date('Y-m-d')) && ($invoice->wf_status=='1')) {
               $waiverInfo='<a  class="label label-primary"  id="'.$invoice->id.'"    onclick="waiver_avaliable(this.id)"   class="btn btn-success btn-xs wf_status" > Available</a >';
           }
            elseif(!empty($invoice->payer()->student_waiver()) && ($invoice->wf_status=='2')) {
                $waiverInfo='<span class="label  label-default " > Applied</span >';
            }
            // partial allowed
//            $partial_allow=0;
            if ($fees->partial_allowed==1)
                $partial_allow='<span class="label label-success">Yes<span>';
            else
                $partial_allow='<span class="label label-warning">no<span>';

            $deleteButton=0;
            if($invoice->invoice_status==2) {
                $deleteButton='<a  id="'.$invoice->id.'" class="btn btn-danger btn-xs delete_class_recent" onclick="delete_recent_invoice(this.id)" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>';
            }

            $return.='<tr id="'.$invoice->id.'">';
            $return.='<td>'.$invoice->id.'</td>';
            $return.='<td>'.$fees->fee_name.'</td>';
            $return.='<td><a href="/student/profile/personal/'.$invoice->payer_id.'">'.$std->first_name.' '.$std->middle_name.' '.$std->last_name.'</a></td>';
            $return.='<td>'.$subtotal.'</td>';
            $return.='<td>'.$totalDiscount.'</td>';
            $return.='<td>'.$getDueFine.'</td>';
            $return.='<td>'.$amount.'</td>';
            $return.='<td>0</td>';
            $return.='<td><span id="unPainInvoiceStatus'.$invoice->id.'"  class="label label-danger">Un-Paid</span></td>';
            $return.='<td>'.$waiverInfo.'</td>';
            $return.='<td>'.$deleteButton.'</td>';
            $return.='</tr>';


             $smsGetawayProfile=$this->smsInstitutionGetway->where('institution_id',$instituteId)->where('status',1)->first();
            if(!empty($smsGetawayProfile) && ($auto_sms==1)) {
                $this->smsSender->create_fees_generate_job($student_id, $invoice->id,$amount);
            }


            return $return;

//                    return $invoiceArrayList[]= [
//                        'id'=>$invoice->id,
//                        'fees_name'=>$fees->fee_name,
//                        'payer_name'=> $std->first_name.' '.$std->middle_name.' '.$std->last_name,
//                        'amount'=>$totalAmount+$getAttendFine+$getDueFine,
//                        'fees_amount'=>$subtotal,
//                        'discount'=>$totalDiscount,
//                        'due_fine'=>$getDueFine,
//                        'attendance_fine'=>$getAttendFine,
//                        'paid_amount'=>$invoice->totalPayment()+$due_fine_amount+$attendance_fine_amount,
//                        'status'=>$invoice->invoice_status,
//                        'waiver'=>'dd',
//                        'action'=>'button',
//                    ];

            // End array

        } else {
            return  'error';
        }
    }

    public function  getStudentFeesAmountById($fee_id,$student_id) {
        return $this->feesInvoice->getStudentFeesAmountById($fee_id, $student_id);
    }





    // store Student Fees  Payer Invoice
    public function storeStudentPayer(Request $request)
    {
//    return $request->all();
        $auto_sms= $request->input('auto_sms');
        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

        $student_id=$request->input('std_id');
         $fees_id= $request->input('fees_id');
         $feesProfile=$this->fees->find($fees_id);
         $studentFeesInvoiceProfile=$this->feesInvoice->where('fees_id',$fees_id)->where('payer_id',$student_id)->first();
// check student information profile
        if(empty($studentFeesInvoiceProfile)) {
            $feesPayers = new $this->feesInvoice;
            $feesPayers->institution_id=$instituteId;
            $feesPayers->campus_id=$campus_id;
            $feesPayers->fees_id =$fees_id;
            $feesPayers->payer_id = $student_id;
            $feesPayers->payer_type = 1;
            $feesPayers->invoice_type = 1;
            $feesPayers->invoice_status = "2";
            $feesPayers->wf_status= "1";
            $feesPayers->due_date= $feesProfile->due_date;
            $result=$feesPayers->save();
            if($result) {
                $smsGetawayProfile=$this->smsInstitutionGetway->where('institution_id',$instituteId)->where('status',1)->first();
                if(!empty($smsGetawayProfile) && ($auto_sms==1) ) {
                    $amount=FeesInvoice::getStudentFeesAmountById($fees_id,$student_id);
                    $this->smsSender->create_fees_generate_job($student_id, $feesPayers->id,$amount);
                }
            }
            return 'success';
        } else {
            return  'error';
        }
    }

    public function getBatchSection(Request $request){

         $academicsLevel=$this->academicHelper->getAllAcademicLevel();
        // response data array
        $data = array();
        if($academicsLevel){

            foreach ($academicsLevel as $level){
                foreach ($level->batch() as $batch){
                    foreach ($batch->section() as $section){
                        if ($batch->get_division()) {
                            $divsionName=$batch->batch_name . " (" . $batch->get_division()->name.")";

                        } else {
                            $divsionName =$batch->batch_name;
                        }
                        $data[] = array(
                            'id'=>$level->id.$batch->id.$section->id,
                            'level_id'=>$level->id,
                            'batch_id'=>$batch->id,
                            'section_id'=>$section->id,
                            'name'=>$divsionName.'- Section: '.$section->section_name,
                            'std_count'=>$this->getClsssSectionStudentCount($batch->id,$section->id),
                        );
                    }
                }
            }
            return json_encode($data);
        }
    }


    // get student by fees and payers

    public  function getStudentbyFees(Request $request, $fees_id){
        $invoicePayers=$this->feesInvoice->where('fees_id',$fees_id)->get();
        return view('fees::pages.modal.payer_list',compact('invoicePayers'));

    }


    public function storeStudentPrayer(Request $request)
    {
        $auto_sms=$request->input('auto_sms');

        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

        $classSection = $request->input('fees_payer_type_radio');

        $csCount = $request->input('cs_count');
        $fees_id = $request->input('fees_id');
        $batchList =$request->input('batch');
        $sectionList =$request->input('section');


        // fees profile
        $feesProfile=$this->fees->find($fees_id);




        //      return  $batchList=[];

        $studentIdListArray=array();

        for($i=0;$i<$csCount;$i++){
            $batch = $batchList[$i];
            $section = $sectionList[$i];
            $stdList = $this->studentEnrollment->where(['batch'=>$batch, 'section'=>$section])->where('enroll_status','IN_PROGRESS')->get(['std_id']);

            if($stdList->count()>0) {
                foreach ($stdList as $std) {
                    $studentFeesInvoiceProfile=$this->feesInvoice->where('fees_id',$fees_id)->where('payer_id',$std->std_id)->first();
                // check student information profile
                    if(empty($studentFeesInvoiceProfile)) {
                        $feesPayers = new $this->feesInvoice();
                        $feesPayers->institution_id=$instituteId;
                        $feesPayers->campus_id=$campus_id;
                        $feesPayers->fees_id = $fees_id;
                        $feesPayers->payer_id = $std->std_id;
                        $feesPayers->payer_type = 1;
                        $feesPayers->invoice_type = 1;
                        $feesPayers->invoice_status = "2";
                        $feesPayers->wf_status = "1";
                        $feesPayers->due_date = $feesProfile->due_date;
                        $result=$feesPayers->save();
                        if($result){
                            $studentIdListArray[]=$std->std_id;
                        }
                    }
                }

                if($classSection=="class"){

                    $feesBatchSectionProfile=$this->feesBatchSection->where('fees_id',$fees_id)->where('batch_id',$batchList[$i])->where('section_id',$sectionList[$i])->first();

                    if(empty($feesBatchSectionProfile)) {
                        // fees batch section profile check
                        $feesBatchSection = new $this->feesBatchSection;
                        $feesBatchSection->institution_id=$instituteId;
                        $feesBatchSection->campus_id=$campus_id;
                        $feesBatchSection->fees_id = $fees_id;
                        $feesBatchSection->batch_id = $batchList[$i];
                        $feesBatchSection->section_id = $sectionList[$i];
                        $feesBatchSection->save();
                    }

                }
            }
        }


        $smsGetawayProfile=$this->smsInstitutionGetway->where('institution_id',$instituteId)->where('status',1)->first();
        if(!empty($smsGetawayProfile) && !empty($studentIdListArray) && ($auto_sms==1)) {
            $this->smsSender->create_fees_multisms_generate_job($fees_id,$studentIdListArray);
        }


        return "success";
    }

    public  function  updateInvoiceStatus(Request $request, $invoiceId){
//        return $request->all();
        $invoice=$this->feesInvoice->find($invoiceId);
        $invoice->invoice_status="4";
        $invoice->save();
        return "success";
    }

    // fees Invoice Delete
    public function deleteInvoice(Request $request, $invoiceId){
        $invoice=$this->feesInvoice->find($invoiceId);
        $invoice->delete();
    }



    /// add Invoice by Prayer
    public  function  addInvoice(Request $request, $id){
        // fees profile
        $fee=$this->fees->find($id);
        // fees item list
       $feeItems=$this->feesItems->where('fees_id',$id)->get();
        // fees batch seciton list
        $feesBatchSections=$this->feesBatchSection->where('fees_id',$id)->get();
        return view('fees::pages.add_invoice',compact('fee','feesBatchSections','feeItems'))->with('page', '');

    }

    //get invoice by Id
    public  function getInvoiceById(Request $request, $invoiceId,$backUrl) {
//        return $backUrl;
        $currentUrl=str_replace('+','/',$backUrl);
        $currentUrl=str_replace('>>','?',$currentUrl);
        $currentUrl=str_replace('-','%',$currentUrl);

        $invoice=$this->feesInvoice->find($invoiceId);
        $institute=$this->academicHelper->getInstituteProfile();
        // get extra payment by invoice payer id

        $payment_extra=$this->paymentExtra->select("*")->where("student_id" ,$invoice->payer()->id)->orderBy('created_at', 'desc')->first();
        $paymentList=$this->getInvoicePaymentListByInvoice($invoiceId);

        if($invoice->fees_id!=null) {
        // get due date count
        $fees=$invoice->fees();

        $day_fine_amount=get_fees_day_amount($fees->due_date);

        // if any fine payment then check and show invoice page
         $invoiceFine=$this->invoiceFine->where('payer_id',$invoice->payer_id)->where('invoice_id',$invoiceId)->get();

             return view('fees::pages.show_invoice', compact('invoice', 'institute', 'paymentList', 'payment_extra', 'day_fine_amount', 'invoiceFine', 'currentUrl'))->with('page', '');
         } else {
             return view('fees::pages.show_only_attendance_invoice', compact('invoice', 'institute', 'currentUrl'))->with('page', '');
         }
    }


    public  function  getInvoicePaymentListByInvoice($invoiceId){
        return $this->invoicePayment->where('invoice_id',$invoiceId)->get();
    }

    public  function getFeesInvoiceReport(Request $request, $invoiceId){


         $invoice=$this->feesInvoice->find($invoiceId);
        //find institute Info
        $institute=$this->getInstituteProfile();
        $paymentList=$this->getInvoicePaymentListByInvoice($invoiceId);


        // get due date count
        $fees=$invoice->fees();
        $day_fine_amount=get_fees_day_amount($fees->due_date);


//         generate pdf
        $pdf = App::make('dompdf.wrapper');;
        $pdf->loadView('fees::pages.report.invoice_report',compact('invoice','institute','paymentList','day_fine_amount'))->setPaper('a4', 'portrait');
        return $pdf->download('fees_invoice_report.pdf');
//         return view('fees::pages.report.invoice_report',compact('invoice','institute'));
    }





    public function  getStudentInfoById($studentId){
        return $this->studentInformation->where('id',$studentId)->first();
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


    // get all invoice download like attendance invoice and fees invoice

    public  function getFeesInvoiceAllReport($invoiceList){

        $invoiceList= explode(",",$invoiceList);
        $institute=$this->academicHelper->getInstituteProfile();

//        return $invoiceList;
        $std_id=$this->getPayerIdbyInvoice($invoiceList[0]);
        $studentInfo=$this->getStudentInfoById($std_id);
//        return $invoiceList;
//        // get invoice
        $invoiceStatusCheck=$this->feesInvoice->whereIn('id',$invoiceList)->where('invoice_status',1)->get();
        $invoiceList=$this->feesInvoice->whereIn('id',$invoiceList)->get();


        $data = [
            'invoiceList' => $invoiceList,
            'institute' => $institute,
            'invoiceStatusCheck' => $invoiceStatusCheck,
            'studentInfo' => $studentInfo,
        ];


        $pdf = App::make('mpdf.wrapper');
        $pdf->loadView('fees::pages.report.invoice_attenfees_report',$data);
        $view = View::make('fees::pages.report.invoice_attenfees_report',$data);
        $html = $view->render();
        $mpdf = new MPDF('utf-8', 'A4-L', 12,'SolaimanLipi','0','0','0','0');
        $mpdf->autoScriptToLang = true;// Mandatory
        $mpdf->autoLangToFont = true;//Mandatory
        $mpdf->WriteHTML($html);
        $mpdf->Output();
//
//        $pdf = App::make('mpdf.wrapper');
//        $pdf->loadView('fees::pages.report.application-form',$data);
//        $view = View::make('fees::pages.report.application-form',$data);
//        $html = $view->render();
//        $mpdf = new MPDF('utf-8', 'A3', 12,'SolaimanLipi');
//        $mpdf->autoScriptToLang = true;// Mandatory
//        $mpdf->autoLangToFont = true;//Mandatory
//        $mpdf->WriteHTML($html);
//        $mpdf->Output();
//
//        $pdf = App::make('dompdf.wrapper');
//        $pdf->loadView('fees::pages.report.application-form')->setPaper('a4', 'portrait');
//        // return $pdf->stream();
//        return $pdf->download('pdfview.pdf');




//        return $pdf->stream();
        return view('fees::pages.report.invoice_attenfees_report',$data);


    }





    public  function getDemoFeesInvoiceReport(Request $request, $invoiceId){
        
         $invoice=$this->feesInvoice->find($invoiceId);
        //find institute Info
         $institute=$this->academicHelper->getInstituteProfile();
         $paymentList=$this->getInvoicePaymentListByInvoice($invoiceId);
//         //generate pdf
//        $pdf = App::make('dompdf.wrapper');;
//        $pdf->loadView('fees::pages.report.demo_invoice_report',compact('invoice','institute','paymentList'))->setPaper('a4', 'portrait');
//         return $pdf->download('fees_invoice_report.pdf');
        if($invoice->fees_id!=null) {

            // get due date count
            $fees = $invoice->fees();

            $day_fine_amount = get_fees_day_amount($fees->due_date);
        } else {
            $day_fine_amount=0;
        }

        $data = [
            'invoice' => $invoice,
            'institute' => $institute,
            'paymentList' => $paymentList,
            'day_fine_amount' => $day_fine_amount,
        ];

//        return view('fees::pages.report.demo_invoice_english_report', $data);

        $pdf = App::make('mpdf.wrapper');
        if($invoice->fees_id!=null) {
            $pdf->loadView('fees::pages.report.demo_invoice_english_report', $data);
            $view = View::make('fees::pages.report.demo_invoice_english_report', $data);
        } else {
            $pdf->loadView('fees::pages.report.demo_attend_invoice_english_report', $data);
            $view = View::make('fees::pages.report.demo_attend_invoice_english_report', $data);
        }
        $html = $view->render();
        $mpdf = new MPDF('utf-8', 'A4-L', 12,'SolaimanLipi','0','0','0','0');
        $mpdf->autoScriptToLang = true;// Mandatory
        $mpdf->autoLangToFont = true;//Mandatory
        $mpdf->WriteHTML($html);
        $mpdf->Output();
//
//        $pdf = App::make('mpdf.wrapper');
//        $pdf->loadView('fees::pages.report.application-form',$data);
//        $view = View::make('fees::pages.report.application-form',$data);
//        $html = $view->render();
//        $mpdf = new MPDF('utf-8', 'A3', 12,'SolaimanLipi');
//        $mpdf->autoScriptToLang = true;// Mandatory
//        $mpdf->autoLangToFont = true;//Mandatory
//        $mpdf->WriteHTML($html);
//        $mpdf->Output();
//
//        $pdf = App::make('dompdf.wrapper');
//        $pdf->loadView('fees::pages.report.application-form')->setPaper('a4', 'portrait');
//        // return $pdf->stream();
//        return $pdf->download('pdfview.pdf');




//        return $pdf->stream();
       return view('fees::pages.report.demo_invoice_english_report',$data);
    }




    // get class-section-subject student list
    public function getClsssSectionStudentCount($class, $section)
    {
        // class section students
        return $this->studentEnrollment->where(['batch'=>$class, 'section'=>$section])->where('enroll_status','IN_PROGRESS')->get(['id'])->count();

    }


    // invoice search

    public function invoiceSearch(Request $request)
    {

//        return $request->all();

        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

        $academics_years = session()->get('academic_year');
        $batch = $request->input('batch');
        $section = $request->input('section');
        $invoiceId = $request->input('invoice_id');

        $fees_id = $request->input('fees_id');
        $fees_name = $request->input('fees_name');

        $payer_id = $request->input('std_id');
        $payer_name = $request->input('payer_name');
        $invoice_type = $request->input('invoice_type');

        //due date
        $from_date = $request->input('search_start_date');
        $to_date = $request->input('search_end_date');

        //created date
        $created_from_date = $request->input('created_start_date');
        $created_to_date = $request->input('created_end_date');

        // waiver

        $waiver = $request->input('waiver_status');

        if (!empty($created_from_date)) {
            $created_from_date = date('Y-m-d H:i:s', strtotime($created_from_date));
        }

        if (!empty($created_to_date)) {
            $created_to_date = date('Y-m-d', strtotime($created_to_date));
            $created_to_date = new Carbon($created_to_date);

            $created_to_date = $created_to_date->endOfDay();
        }


        $invoice_status = $request->input('invoice_status');
        $paymentStatus = $request->input('payment_type');

        if (!empty($from_date)) {
            $from_date = date('Y-m-d H:i:s', strtotime($from_date));
        }

        if (!empty($to_date)) {
            $to_date = date('Y-m-d', strtotime($to_date));
            $to_date = new Carbon($to_date);

            $to_date = $to_date->endOfDay();
        }

//        return $request->all();


//        return $to_date->endOfDay();;

        $allSearchInputs = array();

        if (!empty($batch)) {
             $std_enrollments = $this->studentEnrollment->where('academic_year', $academics_years)->Where('batch', $batch)->orWhere('section', $section)->get();
//            return $std_enrollments = $this->studentEnrollment->where(['academic_year' => $academics_years, 'batch' => $batch, 'section' => $section])->get();
            $data    = array();
            $i = 1;
            if ($std_enrollments) {
                $studentIdlist = array();
                foreach ($std_enrollments as $enrollment) {
                    $studentinfo = $enrollment->student();
                    $studentIdlist[] = $studentinfo->id;
                }

            }
        }

        // check fees_id
        if ($fees_id) {
            $allSearchInputs['fees_id'] = $fees_id;
        }
        // check payer_id
        if ($payer_id) {

            $allSearchInputs['payer_id'] = $payer_id;
        }
        // // check payment_status

        if ($invoice_status!=NULL) {
            $allSearchInputs['invoice_status'] = $invoice_status;
        }

        if ($paymentStatus) {
            $allSearchInputs['invoice_status'] = $paymentStatus;

        }
        if ($invoice_type) {
            $allSearchInputs['invoice_type'] = $invoice_type;
        }


        if ($invoiceId) {
            $allSearchInputs['id'] = $invoiceId;
        }

        $fees=$this->fees->where('institution_id',$instituteId)->where('campus_id',$campus_id)->whereBetween('due_date', [$from_date, $to_date])->get();
        $feesIdArray=array();
        foreach ($fees as $fee){
            $feesIdArray[]=$fee->id;
        }

//       return $allFeesInvoices = $this->feesInvoice->where($allSearchInputs)->where('institution_id',$instituteId)->where('campus_id',$campus_id)->whereIn('payer_id', $studentIdlist)->orWhere('invoice_type', $invoice_type)->orderBy('created_at', 'DESC')->paginate(10);


        if (!empty($from_date) && !empty($to_date) && !empty($batch)) {

            $allFeesInvoices = $this->feesInvoice->where($allSearchInputs)->where('institution_id',$instituteId)->where('campus_id',$campus_id)->whereIn('payer_id', $studentIdlist)->whereIn('fees_id', $feesIdArray)->orderBy('created_at', 'DESC')->paginate(10);
        }

        elseif(!empty($created_from_date) && !empty($created_to_date)){
            $allFeesInvoices = $this->feesInvoice->where($allSearchInputs)->where('institution_id',$instituteId)->where('campus_id',$campus_id)->whereBetween('created_at', [$created_from_date, $created_to_date])->orderBy('created_at', 'DESC')->paginate(10);

        }
        // search for waiver list
        elseif(!empty($waiver)){
            if(!empty($studentIdlist)) {
                $allFeesInvoices= $this->searchStudentWaiver($waiver,$academics_years, $studentIdlist);
            } else {
            $allFeesInvoices= $this->searchStudentWaiver($waiver,$academics_years);
            }
        }
        elseif(!empty($invoiceId)){
            $allFeesInvoices= $this->feesInvoice->where('institution_id',$instituteId)->where('campus_id',$campus_id)->where('id', $invoiceId)->paginate(10);
        }
        else {

            $allFeesInvoices = $this->feesInvoice->where($allSearchInputs)->where('institution_id',$instituteId)->where('campus_id',$campus_id)->paginate(10);
        }



//         else {
//            if(empty($payer_id)  && !empty($batch)  && !empty($section)) {
//            } else {
//                $allFeesInvoices = $this->feesInvoice->where($allSearchInputs)->paginate(10);
//
//
//            }
//
//        }
//         search reslt
//        return $allFeesInvoices;

        if ($allFeesInvoices) {
            // all inputs
            $allInputs =[
                'invoice_id'=>$invoiceId,
                'fees_id' => $fees_id,
                'fees_name'=>$fees_name,
                'payer_name'=>$payer_name,
                'payer_id' => $payer_id,
                'batch' => $batch,
                'section' => $section,
                'invoice_type' => $invoice_type,
                'search_start_date' => $from_date,
                'search_end_date' => $to_date,
                'created_start_date' => $created_from_date,
                'created_end_date' => $created_to_date,
                'invoice_status'=>$invoice_status,
                'payment_status'=>$paymentStatus,
                'waiver_status'=>$waiver

            ];
            // return view
            $allInputs=(Object)$allInputs;

            $searchInvoice=1;
            $academicYear=session()->get('academic_year');
            $batchs=$this->batch->where('academics_year_id',$academicYear)->get();
            $sections=$this->section->where('academics_year_id',$academicYear)->get();
            return view('fees::pages.invoice', compact('searchInvoice','allFeesInvoices','allInputs','batchs','sections'))->with('page', 'invoice');
            // return redirect()->back()->with(compact('state'))->withInput();

        }
    }



    // advance Search Code

//    public  function invoiceAdvanceSearch(Request $request){
//
//
//        $academics_years=session()->get('academic_year');
//        $batch=$request->input('batch');
//        $section=$request->input('section');
//        $std_enrollments=$this->studentEnrollment->where(['academic_year'=>$academics_years,'batch'=>$batch,'section'=>$section])->get();
//        $data = array();
//        $i=1;
//        if($std_enrollments){
//            $studentIdlist=array();
//            foreach ($std_enrollments as $enrollment){
//                $studentinfo=$enrollment->student();
//                $studentIdlist[]=$studentinfo->id;
//            }
//            return $studentIdlist;
//        }
//
//
//        // advance search here
//        $from_date= $request->input('advance_search_start_date');
//        $to_date= $request->input('advance_search_end_date');
//        if(!empty($from_date)) {
//            $from_date=date('Y-m-d H:i:s', strtotime($from_date));
//        }
//
//        if(!empty($to_date)) {
//            $to_date=date('Y-m-d', strtotime($to_date));
//        }
//
//        $to_date = new Carbon($to_date);
//
//        $to_date = $to_date->endOfDay();
//
//
//        if(!empty($from_date) && !empty($to_date)) {
//
//            $allFeesInvoices = $this->feesInvoice->whereIn('payer_id', $studentIdlist)->whereBetween('created_at', [$from_date, $to_date])->get();
//
//
//        } else {
//
//            $allFeesInvoices = $this->feesInvoice->whereIn('payer_id', $studentIdlist)->get();
//
//        }
////        return $allFeesInvoices->count();
//
//        if ($allFeesInvoices) {
//            // all inputs
////            $allInputs =['fees_id' => $fees_id, 'payer_id' => $payer_id, 'search_start_date' => $from_date, 'search_end_date' => $to_date,'payment_status'=>$paymentStatus];
//            // return view
////            $allInputs=(Object)$allInputs;
//            $searchInvoice=1;
//            $academicYear=session()->get('academic_year');
//            $batchs=$this->batch->where('academics_year_id',$academicYear)->get();
//            $sections=$this->section->where('academics_year_id',$academicYear)->get();
//            return view('fees::pages.invoice', compact('searchInvoice','allFeesInvoices','batchs','sections'))->with('page', 'invoice');
//            // return redirect()->back()->with(compact('state'))->withInput();
//
//        }
//
//
//    }


    // student add waiver modal here

    public function addStudentWaiverModal($invoiceId){
        $invoiceProfile=$this->feesInvoice->find($invoiceId);
        // today date
        $todayDate= date('Y-m-d');
        $studentdWaiverProfile=$this->studentWaiver->where('std_id',$invoiceProfile->payer_id)->where('end_date','>=',$todayDate)->first();

        return view('fees::pages.modal.waiver_apply',compact('studentdWaiverProfile','invoiceId'));

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

        if($totalAmount==0) {

        }





        Session::flash('message','Waiver Added Successfully');

        return redirect()->back();




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


    // waiver search for student

    public  function  searchStudentWaiver($waiver,$academics_years,$batch=null,$section=null){

        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

        $studentIdlist="";
        $std_enrollments = $this->studentEnrollment->where(['academic_year' => $academics_years])->get();
        if ($std_enrollments) {
            $studentIdlist = array();
            foreach ($std_enrollments as $enrollment) {
                $studentinfo = $enrollment->student();
                $studentIdlist[] = $studentinfo->id;
            }
//                    $studentIdlist;
        }

//             $studentIdlist;
        $waiverList=$this->studentWaiver->select('std_id')->where('end_date','>=',date('Y-m-d'))->whereIn('std_id',$studentIdlist)->get();

        if ($waiverList) {
            $studentIdlist = array();
            foreach ($waiverList as $waiverstd) {
                $studentIdlist[] = $waiverstd->std_id;
            }
            //                    $studentIdlist;
        }

        // avaliable waiver
        if($waiver=="1") {
             return $allFeesInvoices = $this->feesInvoice->where('institution_id',$instituteId)->where('campus_id',$campus_id)->whereIn('payer_id', $studentIdlist)->where('wf_status', 1)->paginate(10);
        }
        // applied Waiver
        elseif ($waiver=="2"){
            return  $allFeesInvoices = $this->feesInvoice->where('institution_id',$instituteId)->where('campus_id',$campus_id)->whereIn('payer_id', $studentIdlist)->where('wf_status', 2)->paginate(10);
        }
        elseif ($waiver=="3"){
             return $allFeesInvoices = $this->feesInvoice->where('institution_id',$instituteId)->where('campus_id',$campus_id)->whereNotIn('payer_id', $studentIdlist)->paginate(10);
        }

    }


//    /// get fees day fine amount
//    public function  get_fees_day_amount($due_date){
//        $date = \Carbon\Carbon::parse($due_date);
//        $now = \Carbon\Carbon::now();
//        $diff = $date->diffInDays($now)-1;
//        $day_fine_amount=0;
//        // day_fine_count here
//        if($due_date<date('Y-m-d')){
//            $feeSetting=$this->feeSetting->where('id',1)->first();
//            $day_fine_amount=$feeSetting->value*$diff;
//        }
//        return $day_fine_amount;
//
//    }


// get child all invoice by student id only for parent dashboard

    public  function  singleStudentAllInvoice($studentId){
        // invoice List show for parent
           $invoceList=$this->feesInvoice->where('payer_id',$studentId)->orderBy('id','desc')->get();
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

    public  function  singleMonthInvoice($studentId,$feesidArray){
        // invoice List show for parent
        $invoceList=$this->feesInvoice->whereIn('fees_id',$feesidArray)->where('payer_id',$studentId)->orderBy('id','desc')->get();
        if($invoceList->count()>0) {
            foreach ($invoceList as $invoice) {
                $year =$invoice->fees()->year;
                $month =$invoice->fees()->month;
                $yearMonth = date('Y-m', strtotime($year.'-'.$month));
                $invoicesByYearMonth[$year][$month][] = $invoice;
            }
        } else {
            $invoicesByYearMonth=0;
        }


        return  $invoicesByYearMonth;
    }


}
