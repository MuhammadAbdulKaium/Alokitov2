<?php

namespace Modules\Fees\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Support\Facades\Log;
use Modules\Fees\Entities\InvoicePaymentSummary;
use Modules\Academics\Entities\AcademicsYear;
use Modules\Academics\Entities\AcademicsLevel;
use Modules\Academics\Entities\Batch;
use Modules\Fees\Entities\PaymentMethod;
use Modules\Fees\Entities\InvoicePayment;
use Modules\Fees\Entities\ScheduleLog;
use Modules\Fees\Entities\FeesInvoice;
use Modules\Fees\Entities\Fees;
use Modules\Student\Entities\StudentInformation;
use Excel;
use App;
use DB;
use MPDF;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use Modules\Fees\Entities\StudentPyamentView;
use Modules\Fees\Entities\FeesItem;
use Modules\Fees\Entities\InvoiceFine;
use Modules\Fees\Entities\PaymentExtra;
use Modules\Fees\Entities\Items;
use Modules\Fees\Entities\PaymentAdvanceHistory;


class FeesReportController extends Controller
{

    private  $academicsYear;
    private  $academicsLevel;
    private  $paymentMethod;
    private  $invoicePayment;
    private  $scheduleLog;
    private  $studentInformation;
    private  $feesInvoice;
    private  $academicHelper;
    private  $fees;
    private  $feesItem;
    private  $invoiceFine;
    private  $paymentExtra;
    private  $items;
    private  $paymentAdvanceHistory;


    public function __construct(Fees $fees, PaymentAdvanceHistory $paymentAdvanceHistory, PaymentExtra $paymentExtra, Items $items, FeesItem $feesItem, InvoiceFine $invoiceFine, AcademicsYear $academicsYear,AcademicsLevel $academicsLevel, PaymentMethod $paymentMethod, InvoicePayment $invoicePayment, Batch $batch, ScheduleLog $scheduleLog, StudentInformation $studentInformation,FeesInvoice $feesInvoice,AcademicHelper $academicHelper)
    {
        $this->academicsYear             = $academicsYear;
        $this->academicsLevel             = $academicsLevel;
        $this->paymentMethod             = $paymentMethod;
        $this->invoicePayment             = $invoicePayment;
        $this->batch                        = $batch;
        $this->scheduleLog                 = $scheduleLog;
        $this->studentInformation           = $studentInformation;
        $this->feesInvoice                  = $feesInvoice;
        $this->academicHelper              = $academicHelper;
        $this->fees                     = $fees;
        $this->feesItem                 = $feesItem;
        $this->invoiceFine                 = $invoiceFine;
        $this->paymentExtra                 = $paymentExtra;
        $this->items                 = $items;
        $this->paymentAdvanceHistory                 = $paymentAdvanceHistory;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $academicsYears=$this->academicHelper->getAllAcademicYears();
        return view('fees::pages.report.fees_report',compact('academicsYears'));
    }


    public function getBatchListReport(Request $request){


        $academicYear=$request->input('academic_year');
        $academicsLevel=$request->input('academic_level');

        $batchList = $this->batch->where('academics_year_id',$academicYear)->where('academics_level_id',$academicsLevel)->paginate(10);

        $academicsYears=$this->academicHelper->getAllAcademicYears();
        $report_type="download";
        return view('fees::pages.modal.fees_report_modal',compact('academicsYears','batchList','academicYear','academicsLevel','report_type'));
    }





    // get date wise fees view

    public function dateWiseFees()
    {
        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

        //get all payment Method Name
        $paymentMethods=$this->paymentMethod->where('institution_id',$instituteId)->where('campus_id',$campus_id)->get();
        return view('fees::pages.report.date-wise-fees',compact('paymentMethods'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
    //     */
    public function searchDateWiseFees(Request $request)
    {
//        return $request->all();

        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

        //get all payment Method Name
        $paymentMethods=$this->paymentMethod->where('institution_id',$instituteId)->where('campus_id',$campus_id)->get();

//        //request
        $request_type=$request->input('request_type');
//        $fpt_search_start_date=date('Y-m-d', strtotime($request->input('fpt_search_start_date')));
//        $fpt_search_end_date=date('Y-m-d', strtotime($request->input('fpt_search_end_date')));
//        $fpt_list=$this->invoicePayment->where('payment_method_id',$paymentMethod)->whereBetween('payment_date',array($fpt_search_start_date, $fpt_search_end_date))->paginate(1);
//        $fpt_listWithoutPaginate=$this->invoicePayment->where('payment_method_id',$paymentMethod)->whereBetween('payment_date',array($fpt_search_start_date, $fpt_search_end_date))->get();
//
//

        $from_date = $request->input('fpt_search_start_date');
        $to_date = $request->input('fpt_search_end_date');
        $paymentMethod = $request->input('payment_method');
        if (!empty($from_date)) {
            $from_date = date('Y-m-d H:i:s', strtotime($from_date));
        }
        if (!empty($to_date)) {
            $to_date = date('Y-m-d', strtotime($to_date));
            $to_date = new Carbon($to_date);
            $to_date = $to_date->endOfDay();
        }


        $allSearchInputs = array();
        // check fees_id
        if ($paymentMethod) {
            $allSearchInputs['payment_method_id'] = $paymentMethod;
        }

//        return $allSearchInputs;
        if (!empty($from_date) && !empty($to_date)) {
            $fpt_list = $this->invoicePayment->where('institution_id',$instituteId)->where('campus_id',$campus_id)->where($allSearchInputs)->whereBetween('payment_date', [$from_date, $to_date])->paginate(10);
            $feesListWithoutPaginate = $this->invoicePayment->where('institution_id',$instituteId)->where('campus_id',$campus_id)->where($allSearchInputs)->whereBetween('payment_date', [$from_date, $to_date])->get();
        } else {

            $fpt_list = $this->invoicePayment->where('institution_id',$instituteId)->where('campus_id',$campus_id)->where($allSearchInputs)->paginate(10);
            $feesListWithoutPaginate = $this->invoicePayment->where('institution_id',$instituteId)->where('campus_id',$campus_id)->where($allSearchInputs)->get();
        }
        if ($fpt_list) {
            // all inputs
            $allInputs = [
                'payment_method_id' => $paymentMethod,
                'search_start_date' => $from_date,
                'search_end_date' => $to_date
            ];
            // return view
            $allInputs = (Object)$allInputs;

            return view('fees::pages.report.date-wise-fees', compact('feesListWithoutPaginate', 'fpt_list', 'paymentMethods', 'allInputs'));

        }
    }



    // daily fees search


//    public function getDailayPayment(Request $request,$date,$institute_id){
//
//         $studentList=$this->studentInformation->all();
//
//         foreach ($studentList=){
//
//         }

//        $response = $getPayment=$this->invoicePayment->whereBetween('payment_date',array($date, $date))->sum('payment_amount');





//        $date=date('Y-m-d',strtotime($date));
//        $request= 'date:'.$date.'cid:'.$company_id;
//        if(!empty($date)) {
//            $response = $getPayment=$this->invoicePayment->whereBetween('payment_date',array($date, $date))->sum('payment_amount');
//            $schedule_log=new $this->scheduleLog;
//            $schedule_log->ip=request()->ip();
//            $schedule_log->request=$request;
//            $schedule_log->response=$response;
//            $schedule_log->save();
//            return $response;
//        }


//
//    }




    // downlaod fees Payment Transaction List Excel File
    // share all variables with the view

    public function  getFeesPaymentTransactionPdfExcel(Request $request)
    {

        ///get institute Id and Campus Id
        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();


        //institute Informatio
        $instituteInfo=$this->academicHelper->getInstituteProfile();
        $from_date = $request->input('fpt_search_start_date');
        $to_date = $request->input('fpt_search_end_date');
        $paymentMethod = $request->input('payment_method');
        if (!empty($from_date)) {
            $from_date = date('Y-m-d H:i:s', strtotime($from_date));
        }
        if (!empty($to_date)) {
            $to_date = date('Y-m-d', strtotime($to_date));
            $to_date = new Carbon($to_date);
            $to_date = $to_date->endOfDay();
        }


        $allSearchInputs = array();
        // check fees_id
        if ($paymentMethod) {
            $allSearchInputs['payment_method_id'] = $paymentMethod;
        }

//        return $allSearchInputs;
        if (!empty($from_date) && !empty($to_date)) {

            $fpt_list = $this->invoicePayment->where('institution_id',$instituteId)->where('campus_id',$campus_id)->where($allSearchInputs)->whereBetween('payment_date', [$from_date, $to_date])->get();
        } else {

            $fpt_list = $this->invoicePayment->where('institution_id',$instituteId)->where('campus_id',$campus_id)->where($allSearchInputs)->get();
        }

        $report_type=$request->input('report_type');
        if($report_type=="pdf") {

            view()->share(compact('fpt_list', 'instituteInfo','report_type'));

            //generate PDf
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView('fees::pages.report.daily-fees-report')->setPaper('a4', 'portrait');
            // return $pdf->stream();
            $downloadFileName = "daily_fees_report.pdf";
            return $pdf->download($downloadFileName);

        } else {


            view()->share(compact('fpt_list','report_type'));
            //generate excel
            return Excel::create('Daily Fees Collection', function ($excel) {
                $excel->sheet('Daily Fees Collection', function ($sheet) {
                    // Font family
                    $sheet->setFontFamily('Comic Sans MS');
                    // Set font with ->setStyle()
                    $sheet->setStyle(array('font' => array('name' => 'Calibri', 'size' => 12)));
                    // cell formatting
                    $sheet->setAutoSize(true);
                    // Set all margins
                    $sheet->setPageMargin(0.25);
                    // mergeCell
                    // $sheet->mergeCells(['C3:D1', 'E1:H1']);

                    $sheet->loadView('fees::pages.report.daily-fees-report');
                });
            })->download('xls');
        }

    }




    // get daily fees report pdf and excel file

    public function  getFeesCollectionReportPdfExcel($report_type,$academicYear,$academicsLevel)
    {

        //get institute info

        $instituteInfo = $this->academicHelper->getInstituteProfile();
        $academicYearProfile = $this->academicsYear->select('year_name')->where('id', $academicYear)->first();
        $academicLevelProfile = $this->academicsLevel->select('level_name')->where('id', $academicsLevel)->first();

        $batchList = $this->batch->where('academics_year_id', $academicYear)->where('academics_level_id', $academicsLevel)->get();

        if($report_type=="pdf"){

            view()->share(compact('academicsYears', 'batchList', 'report_type', 'instituteInfo', 'academicYearProfile', 'academicLevelProfile'));

            //generate PDf
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView('fees::pages.modal.fees_report_modal')->setPaper('a4', 'portrait');
            // return $pdf->stream();
            $downloadFileName = "academicYear_" . $academicYear . "academic_Level" . $academicsLevel . '.pdf';
            return $pdf->download($downloadFileName);
        } else {

            view()->share(compact('batchList','report_type'));
            //generate excel
            return Excel::create('academicYear_level_fees_report', function ($excel) {
                $excel->sheet('academicYear_level_fees_report', function ($sheet) {
                    // Font family
                    $sheet->setFontFamily('Comic Sans MS');
                    // Set font with ->setStyle()
                    $sheet->setStyle(array('font' => array('name' => 'Calibri', 'size' => 12)));
                    // cell formatting
                    $sheet->setAutoSize(true);
                    // Set all margins
                    $sheet->setPageMargin(0.25);
                    // mergeCell
                    // $sheet->mergeCells(['C3:D1', 'E1:H1']);

                    $sheet->loadView('fees::pages.modal.fees_report_modal');
                });
            })->download('xls');
        }
    }


    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
//    public function getpdf(Request $request)
//    {
//        $pdf = App::make('dompdf.wrapper');
//        $pdf->loadView('fees::pages.report.pdf-test')->setPaper('a4', 'portrait');
//        return $pdf->download('test.pdf');
//        return $pdf->stream();
//    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function getLang(Request $request)
    {
        /* $pdf = App::make('dompdf.wrapper');
         $pdf->loadView('fees::pages.report.pdf-test-report-demo')->setPaper('a4', 'portrait');
         return $pdf->stream();*/

        $pdf = App::make('mpdf.wrapper');
        $pdf = $pdf->loadView('fees::pages.report.pdf-test-report-demo');
        $view = View::make('fees::pages.report.pdf-test-report-demo');

        $html = $view->render();
        $mpdf = new MPDF('UTF-8', 'A4',14,'SolaimanLipi');
        $mpdf->autoScriptToLang = true;// Mandatory
        $mpdf->autoLangToFont = true;//Mandatory
        $mpdf->WriteHTML($html);
        $mpdf->Output();
        //$mpdf->Output(dirname(__FILE__).'/new_created_file.pdf','F');

        //return $pdf->stream('document.pdf');
    }


    // get invoice report card by All Student

    public function  getInvoiceAllStudentReportCard(Request $request)
    {

        // get search request
        $reportTitle="Fees Summary Report";
        ///get institute Id and Campus Id
        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();


//        get institue info

        $instituteInfo=$this->academicHelper->getInstituteProfile();

        // get due date count


        $invoice_type=$request->input('invoice_type');
        $from_date=date('Y-m-d', strtotime($request->input('from_date')));
        $to_date=date('Y-m-d', strtotime($request->input('to_date')));
        $docType=$request->input('doc_type');
        if($invoice_type==3){

            $invoiceList=$this->feesInvoice->where('institution_id',$instituteId)->where('campus_id',$campus_id)->whereBetween('created_at',array($from_date, $to_date))->get();
        } else {
            $invoiceList=$this->feesInvoice->where('institution_id',$instituteId)->where('campus_id',$campus_id)->whereBetween('created_at',array($from_date, $to_date))->where('invoice_type',$invoice_type)->get();

        }

        // return $studentAttendanceList;
        // share all variables with the view
        view()->share(compact('invoiceList','instituteInfo','reportTitle'));

        if ($docType == 'pdf') {
            // generate pdf
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView('fees::pages.report.summery_invoice_report_card')->setPaper('a4', 'portrait');
            // return $pdf->stream();
            return $pdf->download('pdfview.pdf');
        } else {
            //generate excel
            Excel::create('New file', function ($excel) {
                $excel->sheet('New sheet', function ($sheet) {
                    $sheet->loadView('fees::pages.report.summery_invoice_report_card');
                });
            })->download('xls');
        }
    }



    // get invoice report card by Single Student
    public  function  getInvoiceSingleStudentReportCard(Request $request){

        ///get institute Id and Campus Id
        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

        $reportTitle="Student Fees Summary Report";

        $instituteInfo=$this->academicHelper->getInstituteProfile();
        $invoice_type=$request->input('invoice_type');
        $student_id=$request->input('std_id');
        $docType=$request->input('doc_type');
        // fees invoice list by student id
        if($invoice_type==3) {
            $invoiceList = $this->feesInvoice->where('institution_id', $instituteId)->where('campus_id', $campus_id)->where('payer_id', $student_id)->get();
        } else {
            $invoiceList = $this->feesInvoice->where('institution_id', $instituteId)->where('campus_id', $campus_id)->where('payer_id', $student_id)->where('invoice_type', $invoice_type)->get();

        }
        // return $studentAttendanceList;
        // share all variables with the view
        view()->share(compact('invoiceList','instituteInfo','reportTitle'));

        if ($docType == 'pdf') {
            // generate pdf
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView('fees::pages.report.summery_invoice_report_card')->setPaper('a4', 'portrait');
            // return $pdf->stream();
            return $pdf->download('pdfview.pdf');
        } else {
            //generate excel
            Excel::create('New file', function ($excel) {
                $excel->sheet('New sheet', function ($sheet) {
                    $sheet->loadView('fees::pages.report.summery_invoice_report_card');
                });
            })->download('xls');
        }

    }

    public function  feesDetailsReportPdfExcel(Request $request) {

        // get search request


        ///get institute Id and Campus Id
        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();
    $reportTitle="Fees Details Report";

//        get institue info

        $instituteInfo=$this->academicHelper->getInstituteProfile();

        // get due date count

        $fid=$request->input('fid');
        $fees_Id=$request->input('fees_id');
        $invoice_status=$request->input('invoice_status');
        $docType=$request->input('doc_type');

        if($invoice_status==5){
            if(!empty($fid)) {
                $invoiceList=$this->feesInvoice->where('institution_id',$instituteId)->where('campus_id',$campus_id)->where('fees_id',$fid)->get();
            }
            elseif(!empty($fees_Id)) {
                $invoiceList=$this->feesInvoice->where('institution_id',$instituteId)->where('campus_id',$campus_id)->where('fees_id',$fees_Id)->get();
            }

        } else {

            if(!empty($fid)) {
                $invoiceList=$this->feesInvoice->where('institution_id',$instituteId)->where('campus_id',$campus_id)->where('fees_id',$fid)->where('invoice_status',$invoice_status)->get();
            }
            elseif(!empty($fees_Id)) {
                $invoiceList=$this->feesInvoice->where('institution_id',$instituteId)->where('campus_id',$campus_id)->where('fees_id',$fees_Id)->where('invoice_status',$invoice_status)->get();
            }
        }



        // return $studentAttendanceList;
        // share all variables with the view
        view()->share(compact('invoiceList','instituteInfo','reportTitle'));

        if ($docType == 'pdf') {
            // generate pdf
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView('fees::pages.report.summery_invoice_report_card')->setPaper('a4', 'portrait');
            // return $pdf->stream();
            return $pdf->download('pdfview.pdf');
        } else {
            //generate excel
            Excel::create('New file', function ($excel) {
                $excel->sheet('New sheet', function ($sheet) {
                    $sheet->loadView('fees::pages.report.summery_invoice_report_card');
                });
            })->download('xls');
        }
    }




    //due date fees report view

    public  function  dueDateFeesReportView(Request $request){
        $searchInvoice=0;
        return view('fees::pages.due-date-fees-report',compact('invoiceList','searchInvoice'));


    }


    //due date fees report
    public  function  dueDateFeesReport(Request $request)
    {

        ///get institute Id and Campus Id
        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();


        $from_date = date('Y-m-d', strtotime($request->input('search_start_date')));
        $to_date = date('Y-m-d', strtotime($request->input('search_end_date')));
        $invoice_status =$request->input('invoice_status') ;

        $feesListbyDueDate = $this->fees
            ->where('institution_id',$instituteId)->where('campus_id',$campus_id)
            ->select('id')
            ->whereBetween('created_at', [$from_date, $to_date])
            ->orWhereBetween('due_date', [$from_date, $to_date])
            ->get();

        if ($feesListbyDueDate) {
            $feesIds = array();
            foreach ($feesListbyDueDate as $fees) {
                $feesIds[] = $fees->id;
            }

        }


        $invoiceList=$this->feesInvoice->where('institution_id',$instituteId)->where('campus_id',$campus_id)->whereIn('fees_id',$feesIds)->where('invoice_status',$invoice_status)->get();

//        return $allFeesInvoices;

        if ($invoiceList) {
            // all inputs
            $allInputs =[
                'search_start_date' => $from_date,
                'search_end_date' => $to_date,
                'invoice_status'=>$invoice_status
            ];
            // return view
            $allInputs=(Object)$allInputs;
            $searchInvoice=1;
            return view('fees::pages.due-date-fees-report', compact('invoiceList','allInputs','searchInvoice'));
            // return redirect()->back()->with(compact('state'))->withInput();

        }

    }

    // due date frees invoice report

    public function  dueDateFeesReportPdfExcel(Request $request)
    {

        ///get institute Id and Campus Id
        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();


        // institute information
        $instituteInfo=$this->academicHelper->getInstituteProfile();

        $from_date = date('Y-m-d', strtotime($request->input('search_start_date')));
        $to_date = date('Y-m-d', strtotime($request->input('search_end_date')));
        $invoice_status =$request->input('invoice_status') ;

        $feesListbyDueDate = $this->fees
            ->where('institution_id',$instituteId)->where('campus_id',$campus_id)
            ->select('id')
            ->whereBetween('created_at', [$from_date, $to_date])
            ->orWhereBetween('due_date', [$from_date, $to_date])
            ->get();

        if ($feesListbyDueDate) {
            $feesIds = array();
            foreach ($feesListbyDueDate as $fees) {
                $feesIds[] = $fees->id;
            }

        }


        $invoiceList=$this->feesInvoice->whereIn('fees_id',$feesIds)->where('invoice_status',$invoice_status)->where('institution_id',$instituteId)->where('campus_id',$campus_id)->get();


        $report_type=$request->input('report_type');
        if($report_type=="pdf") {

            view()->share(compact('invoiceList', 'instituteInfo','report_type'));

            //generate PDf
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView('fees::pages.report.due-date-fees-pdf-excel-report')->setPaper('a4', 'portrait');
            // return $pdf->stream();
            $downloadFileName = "due_date_fees_invoice_report.pdf";
            return $pdf->download($downloadFileName);

        } else {


            view()->share(compact('invoiceList','report_type'));
            //generate excel
            return Excel::create('due_date_fees_invoice_report', function ($excel) {
                $excel->sheet('due_date_fees_invoice_report', function ($sheet) {
                    // Font family
                    $sheet->setFontFamily('Comic Sans MS');
                    // Set font with ->setStyle()
                    $sheet->setStyle(array('font' => array('name' => 'Calibri', 'size' => 12)));
                    // cell formatting
                    $sheet->setAutoSize(true);
                    // Set all margins
                    $sheet->setPageMargin(0.25);
                    // mergeCell
                    // $sheet->mergeCells(['C3:D1', 'E1:H1']);

                    $sheet->loadView('fees::pages.report.due-date-fees-pdf-excel-report');
                });
            })->download('xls');
        }

    }


    public function dailyFeesCollectionApi($start_date,$end_date){

        ///get institute Id and Campus Id
        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

         $end_date = new Carbon($end_date);
         $start_date= date('Y-m-d H:i:s', strtotime($start_date));
         $end_date = $end_date->endOfDay();

        return InvoicePaymentSummary::whereraw("created_at >= '$start_date'")
            ->whereraw("created_at <= '$end_date'")
            ->whereraw('status is null')
            ->where('institution_id',$instituteId)
            ->where('campus_id',$campus_id)
            ->get(['summary']);
    }



    // daily Fees Colleciton Api

//    public function dailyFeesCollectionApi($fees_name,$start_date,$end_date){
//
//
//        ///get institute Id and Campus Id
//        $instituteId=$this->academicHelper->getInstitute();
//        $campus_id=$this->academicHelper->getCampus();
//
//
//        $feesProfile=$this->fees->where('institution_id',$instituteId)->where('campus_id',$campus_id)->get();
//        if($feesProfile){
//            // date format change
//            $start_date=date('Y-m-d',strtotime($start_date));
//            $end_date=date('Y-m-d',strtotime($end_date));
//
//            // create carbon object
//            $end_date = new Carbon($end_date);
//            $end_date = $end_date->endOfDay();
//
//            $amount= $this->invoicePayment->where('institution_id',$instituteId)->where('campus_id',$campus_id)->where('fees_id',$feesProfile->id)->whereBetween('created_at', [$start_date, $end_date])->sum('payment_amount');
//            $extra_amount= $this->invoicePayment->where('institution_id',$instituteId)->where('campus_id',$campus_id)->where('fees_id',$feesProfile->id)->whereBetween('created_at', [$start_date, $end_date])->sum('extra_payment_amount');
//
//            return $dailyFeesArray=["fees"=>$fees_name,"amount"=>"$amount","extra_amount"=>"$extra_amount"];
//
//        } else {
//            return "0";
//        }
//
//
//    }

    /**
     * Fees Monty Report
     */
    public function  feesMonthlyReport(Request $request){
//        return $request->all();

        // institute information
        $instituteInfo=$this->academicHelper->getInstituteProfile();

        $download_type=$request->input('download');

        StudentPyamentView::all();
        $academicYear=$request->input('academic_year');
        $academicLevel=$request->input('academic_level');
        $batch=$request->input('batch');
        $section=$request->input('section');
        $monthlyReportsView=[];


        $from_date=date("Y-m-d H:i:s",strtotime($request->input('from_date')));
        $to_date=$request->input('to_date');
        $to_date = new Carbon($to_date);
        $to_date = $to_date->endOfDay();

        $first_month= date('m',strtotime($from_date));
        $year= date('Y',strtotime($from_date));
        $last_month=date('m',strtotime($to_date));
//
        if($download_type=="pdf"){

//            $monthlyReports = DB::select(DB::raw("SELECT e.std_id as std_id, e.academic_year as academic_year, e.academic_level as academic_level, e.batch as batch, e.section as section, sum(virtualtable.amount) as payable_amount, sum(IFNULL(virtualtable2.payed_amount, 0)) as payed_amount FROM (SELECT * FROM student_enrollments st WHERE st.batch ='$batch') As e JOIN (Select * from fees_invoices WHERE fees_invoices.created_at >= '2017-10-25 00:00:00') As i ON i.payer_id = e.id JOIN (Select SUM(rate*qty) as amount, fees_id from fees_item group by fees_id ) AS virtualtable ON virtualtable.fees_id = i.fees_id LEFT OUTER JOIN (Select SUM(payment_amount) as payed_amount,invoice_id from invoice_payment GROUP BY invoice_id) AS virtualtable2 ON i.id = virtualtable2.invoice_id GROUP BY e.id
//                      "));

            for ($month = $first_month; $month <= $last_month; $month++) {
                $form_date= date('Y-m-01 00:00:00',strtotime($year.'-'.$month.'-01'));
                 $to_date= date('Y-m-t 23:59:59',strtotime($year.'-'.$month.'-20'));
                $monthlyReports[$month] =$this->monthlyReportBatchSection($form_date,$to_date,$academicLevel,$batch,$section);
            }

            $report_type="pdf";
            view()->share(compact('report_type','academicYear','instituteInfo','monthlyReports'));
            //generate PDf
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView('fees::pages.modal.monthly_fees_report_modal')->setPaper('a4', 'portrait');
            // return $pdf->stream();
            $downloadFileName = "monthly_fees_report_modal.pdf";
            return $pdf->download($downloadFileName);
        } else {
            $report_type="view";
            for ($month = $first_month; $month <= $last_month; $month++) {
                $form_date= date('Y-m-01 00:00:00',strtotime($year.'-'.$month.'-01'));
                $to_date= date('Y-m-t 23:59:59',strtotime($year.'-'.$month.'-20'));
                $monthlyReportsView[$month] =$this->monthlyReportBatchSection($form_date,$to_date,$academicLevel,$batch,$section);
            }

            return view('fees::pages.modal.monthly_fees_report_modal', compact('monthlyReportsView', 'report_type','section', 'academicYear','academicLevel','from_date','to_date','batch'));

        }

    }


    // get batch section fees monthly reports

    public function monthlyReportBatchSection($form_date,$to_date, $level, $batch,$section){
        if(empty($level)) { $selectQuery="student_enrollments e";}

        elseif(empty($batch)) { $selectQuery ="(SELECT * FROM student_enrollments st WHERE  st.academic_level =$level) As e "; }

        elseif(empty($section)) { $selectQuery ="(SELECT * FROM student_enrollments st WHERE  st.academic_level =$level AND st.batch =$batch) As e "; }
        else { $selectQuery ="(SELECT * FROM student_enrollments st WHERE  st.academic_level =$level AND st.batch =$batch AND st.section =$section) As e "; }

                     $query="SELECT
                            e.std_id as std_id, e.academic_year as academic_year, e.academic_level as academic_level, e.batch as batch, e.section as section, sum(virtualtable.amount) as payable_amount,
                            sum(IFNULL(virtualtable2.payed_amount, 0)) as payed_amount
                            FROM
                              $selectQuery
                            JOIN
                            (Select * from  fees_invoices WHERE fees_invoices.created_at >= '$form_date' AND fees_invoices.created_at <= '$to_date')
                            As i
                            ON
                            i.payer_id = e.id
                            
                            JOIN
                            (Select SUM(rate*qty) as amount, fees_id from fees_item
                            group by fees_id )
                            AS virtualtable
                            ON virtualtable.fees_id = i.fees_id
                            LEFT OUTER JOIN
                            (Select SUM(payment_amount) as payed_amount,invoice_id from invoice_payment GROUP BY invoice_id)
                            AS virtualtable2
                            ON i.id = virtualtable2.invoice_id
                            GROUP BY e.id
                      ";
                 return  DB::select(DB::raw($query));
    }



    //============ Fees Items collection here ============

    public function getFeesItemsCollectionApi($start_date,$end_date)
    {


        ///get institute Id and Campus Id
        $instituteId = $this->academicHelper->getInstitute();
        $campus_id = $this->academicHelper->getCampus();

        $end_date = new Carbon($end_date);
        $start_date = date('Y-m-d H:i:s', strtotime($start_date));
        $end_date = $end_date->endOfDay();


        $invoicePaymentSummaryIds = InvoicePaymentSummary::whereraw("created_at >= '$start_date'")
            ->whereraw("created_at <= '$end_date'")
            ->whereraw('status is null')
            ->where('institution_id', $instituteId)
            ->where('campus_id', $campus_id)
            ->get(['invoice_id', 'summary']);

        $summeryArray = [];
        if (!empty($invoicePaymentSummaryIds)) {
            $itemsArrayList=[];
            foreach ($invoicePaymentSummaryIds as $invoiceSummery) {

                $summeryArray[$invoiceSummery->invoice_id]['invoice_id'] = $invoiceSummery->invoice_id;
                $summeryArray[$invoiceSummery->invoice_id]['summery'] = $invoiceSummery->summary;

                // get item list and set in array

                $invoiceProfile = $this->feesInvoice->whereNotNull('fees_id')->where('id', $invoiceSummery->invoice_id)->first();
                if (!empty($invoiceProfile)) {
                    $feesItemsList = $this->feesItem->where('fees_id', $invoiceProfile->fees_id)->get();
                    //only item id list
                    $itemIDList = $feesItemsList->pluck('id');
                    array_unique($itemIDList->toArray());

                    $itemList = $this->feesItem
                        ->select('id', 'item_id', DB::raw('SUM(rate*qty) as totalAmount'))
                        ->whereIn('id', $itemIDList)
                        ->groupBy('id')->get();
//                $itemList['item'][]=$itemList;

                    $itemsArrayList[] = $itemList;
                }

            }
            // item amount counter
            $itemAmountCounter = array();
//            $itemAmountCounterNew = array();
            //all item list looping
            foreach ($itemsArrayList as $item) {
                // single item list looping
                foreach ($item as $singleItem) {
                    // checking
                    if (array_key_exists($singleItem->item_id, $itemAmountCounter)) {
                        $itemAmountCounter[$singleItem->item_id] += $singleItem->totalAmount;
                    } else {
                        $itemAmountCounter[$singleItem->item_id] = $singleItem->totalAmount;
                    }
                }
            }


            // items amount and chart id
           $feesItemIdArray=[];

            foreach ($itemAmountCounter as $key=>$value) {
                $feesItemIdArray[]=$key;
                $feesItemIdArrayValue[$key]=$value;
            }
//        return $feesItemIdArray;

            // get chart item by item id

            $itemsList=$this->items->whereIn('id',$feesItemIdArray)->get();
            $itemChartIdAmount=[];
            foreach($itemsList as $item){
                $itemChartIdAmount[$item->acc_chart_id]=$feesItemIdArrayValue[$item->id];
        }

            $invoiceIds = $invoicePaymentSummaryIds->pluck('invoice_id')->toArray();
//        print_r($invoiceIds);
//        exit();
            $invoiceIds= array_values(array_unique($invoiceIds));

            // get total due fine amount
            $totalDueDateFine = $this->invoiceFine->whereIn('invoice_id', $invoiceIds)->sum('fine_amount');
            // get total fees amount
            $totalFees = $this->invoicePayment->whereIn('invoice_id', $invoiceIds)->whereNotNull('fees_id')->sum('payment_amount');
            // get total attendance fine
            $totalAttendanceFine = $this->invoicePayment->whereIn('invoice_id', $invoiceIds)->whereNull('fees_id')->sum('payment_amount');


            // get total fees amount
            $invoiceFeesIdsList = $this->invoicePayment->whereIn('invoice_id', $invoiceIds)->whereNotNull('fees_id')->get();
            $feesIds = $invoiceFeesIdsList->pluck('fees_id');

            $uniqueFeesIds = array_unique($feesIds->toArray());
            // variable declare
            $totalDiscount = 0;
            foreach ($invoiceFeesIdsList as $invoice) {

                $totalDiscount += $this->getFeesDiscount($invoice->invoice_id);
            }

//        $feesItemList= $this->feesItem->whereIn('id', $uniqueFeesIds)->get();
//        foreach ($feesItemList as $item) {
//            $itemList[] = $this->getItemAmountByfeesIdandItemId($item->item_id);
//        }
//
//
//        return $itemList;
            // calculate advance Payment amount
            $advancePaymentAmount = $this->paymentAdvanceHistory->whereIn('invoice_id', $invoiceIds)->where('status',1)->where('is_read',0)->sum('amount');
            $advanceTakenAmount = $this->paymentAdvanceHistory->whereIn('invoice_id', $invoiceIds)->where('status',0)->where('is_read',0)->sum('amount');


            return $dateRangeSummary = array(
                'all_invoice' => $invoiceIds,
                'total_fees_paid' => $totalFees + $totalAttendanceFine + $totalDueDateFine,
                'total_due_fine' => $totalDueDateFine,
                'total_attendance_fine' => $totalAttendanceFine,
                'totalDiscount' => $totalDiscount,
                'advance_payment_amount' => $advancePaymentAmount,
                'payment_taken_amount' => $advanceTakenAmount,
                'fees_items' => $itemChartIdAmount,
            );

        } else {
            return "0";
        }
    }


    public function  getFeesDiscount($invoice_id)
    {
        $invoice =$this->feesInvoice->where('id',$invoice_id)->first();
        $fees=$invoice->fees();
        $subtotal=0; $totalAmount=0; $totalDiscount=0;
          foreach($fees->feesItems() as $amount) {
                $subtotal += $amount->rate*$amount->qty;
         }

        if ($discount = $fees->discount()) {
            $discountPercent = $discount->discount_percent;
            $totalDiscount = (($subtotal * $discountPercent) / 100);
            $totalAmount = $subtotal - $totalDiscount;
        }
        else {
            $totalDiscount=0;
            $totalAmount = $subtotal;
        }

        if ($invoice->waiver_type == "1") {
            Log::info('total_Amount='.$totalAmount);
            $totalWaiver = (($totalAmount * $invoice->waiver_fees) / 100);
        } elseif ($invoice->waiver_type == "2") {
            $totalWaiver = $invoice->waiver_fees;
        }

        if(!empty($invoice->waiver_fees)) {
            Log::info('Not empalty Log='.$totalWaiver);
                $totalDiscount = $totalDiscount + $totalWaiver;
          }


        return $totalDiscount;

    }


    // item total amount by fees_id and Items Id

    public function  getItemAmountByfeesIdandItemId($item_id) {

        $itemList=$this->feesItem->where('item_id', $item_id)->get();
        $itemRateQtyCalculate=0;
        foreach ($itemList as $item) {
            $itemRateQtyCalculate+=$item->rate*$item->qty;
        }
        return $itemRateQtyCalculate;

    }



//    public function getFeesItemsCollectionApi($start_date,$end_date){
//
//
//        ///get institute Id and Campus Id
//        $instituteId=$this->academicHelper->getInstitute();
//        $campus_id=$this->academicHelper->getCampus();
//
//        $end_date = new Carbon($end_date);
//        $start_date= date('Y-m-d H:i:s', strtotime($start_date));
//        $end_date = $end_date->endOfDay();
//
//        $invoicePaymentSummary= InvoicePaymentSummary::whereraw("created_at >= '$start_date'")
//            ->whereraw("created_at <= '$end_date'")
//            ->whereraw('status is null')
//            ->where('institution_id',$instituteId)
//            ->where('campus_id',$campus_id)
//            ->get(['invoice_id','summary']);
//
//        $summeryArray=[];
//
//        foreach ($invoicePaymentSummary as $invoiceSummery) {
//
//            $summeryArray[$invoiceSummery->invoice_id]['invoice_id']=$invoiceSummery->invoice_id;
//            $summeryArray[$invoiceSummery->invoice_id]['summery']=$invoiceSummery->summary;
//
//            // get item list and set in array
//
//            $invoiceProfile=$this->feesInvoice->whereNotNull('fees_id')->where('id', $invoiceSummery->invoice_id)->first();
//            if(!empty($invoiceProfile)) {
//                $feesItemsList = $this->feesItem->where('fees_id', $invoiceProfile->fees_id)->get();
//                //only item id list
//                $itemIDList = $feesItemsList->pluck('id');
//                array_unique($itemIDList->toArray());
//
//                $itemList = $this->feesItem
//                    ->select('id','item_id', DB::raw('SUM(rate*qty) as totalAmount'))
//                    ->whereIn('id', $itemIDList)
//                    ->groupBy('id')->get();
//
//                $summeryArray[$invoiceSummery->invoice_id]['items'] = $itemList;
//            }
//
//        }
//
//        return $summeryArray;
//
//
//
//
//        // save invoice id
//        $invoiceList= $invoicePaymentSummary->pluck('invoice_id');
//        // get fees list here
//        $feesList=$this->feesInvoice->whereNotNull('fees_id')->whereIn('id', $invoiceList)->distinct('fees_id');
//        $feesList= $feesList->pluck('fees_id');
//        $feesItemsList=$this->feesItem->whereIn('fees_id', $feesList)->get();
//        //only item id list
//        $itemIDList=$feesItemsList->pluck('item_id');
//        array_unique($itemIDList->toArray());
//
//        return $this->feesItem
//            ->select('item_id', DB::raw('SUM(rate*qty) as totalAmount'))
//            ->whereIn('item_id',$itemIDList)
//            ->groupBy('item_id')->get();
//
//
//    }


    public function dashboard()
    {

        // /get institute Id and Campus Id

        $instituteId = $this->academicHelper->getInstitute();
        $campus_id = $this->academicHelper->getCampus();

        // fees select by fees type  tuition fees
         $tuitionFeesList=$this->fees->where('institution_id', $instituteId)->where('campus_id', $campus_id)->where('fee_type',2)->get();
            // fees id list serialize
         $tutionFeesIds=$tuitionFeesList->pluck('id');
         $allInvoiceList = $this->feesInvoice->where('institution_id', $instituteId)->where('campus_id', $campus_id)->whereIn('fees_id',$tutionFeesIds)->get();

         // total attendance fine amount
          $attendanceInvoiceList= $this->feesInvoice->where('institution_id', $instituteId)->where('campus_id', $campus_id)->where('invoice_type', 2)->get();
           $totalAttendanceFineGenerate= $attendanceInvoiceList->sum('invoice_amount');

        // paid attendance fine amount
            $attendancePaidInvoiceList= $this->feesInvoice->where('institution_id', $instituteId)->where('campus_id', $campus_id)->where('invoice_type', 2)->where('invoice_status',1)->get();
            $totalAttendanceFineCollected=  $this->getPaidAttendnaceFine($attendancePaidInvoiceList);
        // paid invoice list
         $paidInvoiceList = $this->feesInvoice->where('institution_id', $instituteId)->where('campus_id', $campus_id)->whereIn('fees_id',$tutionFeesIds)->where('invoice_status',1)->get();
         $totalFeesGenerate=$this->getTotalFeesGenerateand($allInvoiceList);
         $totalFeesCollected=$this->getTotalFeesCollected($paidInvoiceList);

         // monthly Tuition fees generate
        // monthly tuition fees generated
        $month=date("n");
        $monthlyTuitionFees=$this->getMonthlyTuitionFeesGenerate($month);

        // due fine collected

         $totalDueFineGenerated=$this->getTotalDueFineAmount($allInvoiceList);
         $totalDueFineCollected=$this->invoiceFine->where('institution_id', $instituteId)->where('campus_id', $campus_id)->sum('fine_amount');


        return view('academic_modules.dashboard-fees', compact('totalFeesGenerate','totalFeesCollected','totalAttendanceFineGenerate','totalAttendanceFineCollected','monthlyTuitionFees','totalDueFineCollected','totalDueFineGenerated'));

    }




    // total fees collected and fees generate code

    public function  getTotalFeesGenerateand($allInvoiceList){
        $i = 1;
        $getAttendFine = 0;
        $getDueFine = 0;
        $totalPaidCalculate = 0;
        $subTotalSum = 0;
        $totalFeesAmountSum = 0;
        $totalSumAmount = 0;
        $totalDueAmountSum = 0;
        $totalDiscountAmountSum = 0;
        $totalPaidAmountSum = 0;

        foreach($allInvoiceList as $invoice)
        {

            // due fine amount

            if ($invoice->due_fine_amount())
            {
                $due_fine_amount = $invoice->due_fine_amount()->fine_amount;
            }
            else
            {
                $due_fine_amount = 0;
            }

            // check fees id

            if (!empty($invoice->fees()))
            {
                $fees = $invoice->fees();

                // sub total calculate

                $subtotal = 0;
                $totalAmount = 0;
                $totalDiscount = 0;
                foreach($fees->feesItems() as $amount)
                {
                    $subtotal+= $amount->rate * $amount->qty;
                }

                // due fine amount paid or unpaid calculate

                $dueFinePaid = $invoice->invoice_payment_summary();
                $var_dueFine = 0;
                if ($dueFinePaid)
                {
                    $var_dueFine = json_decode($dueFinePaid->summary);
                }

                if ($invoice->invoice_status == "1") {
                    if (!empty($var_dueFine)) {
                        $getDueFine = $var_dueFine->due_fine->amount;
                    }
                }
                else
                    {
                        if (!empty($invoice->findReduction()))
                        {
                            $getDueFine = $invoice->findReduction()->due_fine;
                        }
                        else
                        {
                            $getDueFine = get_fees_day_amount($invoice->fees()->due_date);
                        }
                    }

                // discount calculate

                if ($discount = $invoice->fees()->discount())
                {
                    $discountPercent = $discount->discount_percent;
                    $totalDiscount = (($subtotal * $discountPercent) / 100);
                    $totalAmount = $subtotal - $totalDiscount;
                }
                else
                {
                    $totalAmount = $subtotal;
                }

                // waiver and discount her

                if ($invoice->waiver_type == "1") {
                    $totalWaiver = (($totalAmount * $invoice->waiver_fees) / 100);
                    $totalAmount = $totalAmount - $totalWaiver;
                }
                elseif ($invoice->waiver_type == "2")
                {
                    $totalWaiver = $invoice->waiver_fees;
                    $totalAmount = $totalAmount - $totalWaiver;
                }

                if ($discount = $invoice->fees()->discount())
                {
                    $totalDiscount = (($subtotal * $discountPercent) / 100);
                }

                if (!empty($invoice->waiver_fees))
                {
                    $totalDiscount = $totalDiscount + $totalWaiver;
                }

                if ($invoice->invoice_status=="1") {
                    $totalPaidCalculate = $invoice->totalPayment() + $getDueFine;
                } else {
                    $totalPaidCalculate=0;
                }

            }
//            else {
//                $totalDiscount=0; $getDueFine=0;
//
//                $subtotal=$invoice->invoice_amount;
//
//                if ($invoice->invoice_status=="1") {
//                    $totalPaidCalculate = $invoice->invoice_amount;
//                }
//                else {
//                    $totalPaidCalculate=0;
//                }
//            }



            $subTotalSum+=$subtotal;
            $totalDiscountAmountSum+=$totalDiscount;
            $totalDueAmountSum+=$getDueFine;
            $totalSumAmount+=$subtotal-$totalDiscount;
            $totalPaidAmountSum+=$totalPaidCalculate;

        }

        return $totalSumAmount;
//        return $resultArray=array(
////            'subtotal'=> $subTotalSum,
////            'totalDiscountAmountSum'=> $totalDiscountAmountSum,
//            'totalDueFine'=> $totalDueAmountSum,
//            'totalTutionFeesGenerate'=> $totalSumAmount,
////            'totalCollected'=> $totalPaidAmountSum,
//        );
    }


    // total tuition fees collected
    public function  getTotalFeesCollected($paidInvoiceList){
        //paid invoice id list
        $paidInvoiceIds=$paidInvoiceList->pluck('id');
        return  $this->invoicePayment->whereIn('invoice_id',$paidInvoiceIds)->sum('payment_amount');
    }

    // total paid attendance fine
    public function getPaidAttendnaceFine($attendancePaidInvoiceList){
            $paidInvoiceIds=$attendancePaidInvoiceList->pluck('id');
        return  $this->invoicePayment->whereIn('invoice_id',$paidInvoiceIds)->sum('payment_amount');
    }


    public function  getMonthlyTuitionFeesGenerate($month){

        // /get institute Id and Campus Id
        $instituteId = $this->academicHelper->getInstitute();
        $campus_id = $this->academicHelper->getCampus();

         $monthlyTuitionFeesList=$this->fees->where('institution_id', $instituteId)->where('campus_id', $campus_id)->where('fee_type',2)->where('month',$month)->get();
        // fees id list serialize
        $monthlyTuitionFeesIds=$monthlyTuitionFeesList->pluck('id');
        $monthlyInvoiceList = $this->feesInvoice->where('institution_id', $instituteId)->where('campus_id', $campus_id)->whereIn('fees_id',$monthlyTuitionFeesIds)->get();


        // monthly tuition fees paid
        $monthlyInvoiceIds=$monthlyInvoiceList->pluck('id');
        $start_date= date("Y-".$month."-01"); // hard-coded '01' for first day
        $end_date=date("Y-".$month."-t"); // hard-coded '01' for first day

        $monthlyTuitionFeesCollected=$this->invoicePayment->whereIn('invoice_id',$monthlyInvoiceIds)->whereBetween('payment_date',[$start_date, $end_date])->sum('payment_amount');

        return array(
            'monthly_tutionfees_generated'=>$this->getTotalFeesGenerateand($monthlyInvoiceList),
            'monthly_tutionfees_collected'=>$monthlyTuitionFeesCollected,
        );

    }



    // get total due fine amount

    public function  getTotalDueFineAmount($allInvoiceList){
        $i = 1;
        $getAttendFine = 0;
        $getDueFine = 0;
        $totalPaidCalculate = 0;
        $subTotalSum = 0;
        $totalFeesAmountSum = 0;
        $totalSumAmount = 0;
        $totalDueAmountSum = 0;
        $totalDiscountAmountSum = 0;
        $totalPaidAmountSum = 0;

        foreach($allInvoiceList as $invoice)
        {

            // due fine amount

            if ($invoice->due_fine_amount())
            {
                $due_fine_amount = $invoice->due_fine_amount()->fine_amount;
            }
            else
            {
                $due_fine_amount = 0;
            }

            // check fees id

            if (!empty($invoice->fees()))
            {
                $fees = $invoice->fees();

                // sub total calculate

                $subtotal = 0;
                $totalAmount = 0;
                $totalDiscount = 0;
                foreach($fees->feesItems() as $amount)
                {
                    $subtotal+= $amount->rate * $amount->qty;
                }

                // due fine amount paid or unpaid calculate

                $dueFinePaid = $invoice->invoice_payment_summary();
                $var_dueFine = 0;
                if ($dueFinePaid)
                {
                    $var_dueFine = json_decode($dueFinePaid->summary);
                }

                if ($invoice->invoice_status == "1") {
                    if (!empty($var_dueFine)) {
                        $getDueFine = $var_dueFine->due_fine->amount;
                    }
                 }
                elseif ($invoice->invoice_status == "2") {
                        if (!empty($invoice->findReduction()))
                        {
                            $getDueFine = $invoice->findReduction()->due_fine;
                        }
                        else
                        {
                            $getDueFine = get_fees_day_amount($invoice->fees()->due_date);
                        }
                    }

                // discount calculate

                if ($discount = $invoice->fees()->discount())
                {
                    $discountPercent = $discount->discount_percent;
                    $totalDiscount = (($subtotal * $discountPercent) / 100);
                    $totalAmount = $subtotal - $totalDiscount;
                }
                else
                {
                    $totalAmount = $subtotal;
                }

                // waiver and discount her

                if ($invoice->waiver_type == "1") {
                    $totalWaiver = (($totalAmount * $invoice->waiver_fees) / 100);
                    $totalAmount = $totalAmount - $totalWaiver;
                }
                elseif ($invoice->waiver_type == "2")
                {
                    $totalWaiver = $invoice->waiver_fees;
                    $totalAmount = $totalAmount - $totalWaiver;
                }

                if ($discount = $invoice->fees()->discount())
                {
                    $totalDiscount = (($subtotal * $discountPercent) / 100);
                }

                if (!empty($invoice->waiver_fees))
                {
                    $totalDiscount = $totalDiscount + $totalWaiver;
                }

                if ($invoice->invoice_status=="1") {
                    $totalPaidCalculate = $invoice->totalPayment() + $getDueFine;
                } else {
                    $totalPaidCalculate=0;
                }

            }
//            else {
//                $totalDiscount=0; $getDueFine=0;
//
//                $subtotal=$invoice->invoice_amount;
//
//                if ($invoice->invoice_status=="1") {
//                    $totalPaidCalculate = $invoice->invoice_amount;
//                }
//                else {
//                    $totalPaidCalculate=0;
//                }
//            }

//            echo $getDueFine."</br>";


            $subTotalSum+=$subtotal;
            $totalDiscountAmountSum+=$totalDiscount;
            $totalDueAmountSum+=$getDueFine;
            $totalSumAmount+=$subtotal-$totalDiscount;
            $totalPaidAmountSum+=$totalPaidCalculate;

        }

        return $totalDueAmountSum;
//        return $resultArray=array(
//            'subtotal'=> $subTotalSum,
//            'totalDiscountAmountSum'=> $totalDiscountAmountSum,
//            'totalDueFine'=> $totalDueAmountSum,
//            'totalTutionFeesGenerate'=> $totalSumAmount,
//            'totalCollected'=> $totalPaidAmountSum,
//        );
    }




}
