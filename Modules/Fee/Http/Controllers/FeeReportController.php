<?php

namespace Modules\Fee\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Fee\Entities\FeeHead;
use Modules\Fee\Entities\FeeInvoice;
use Modules\Fee\Entities\Transaction;
use Modules\Student\Entities\StudentProfileView;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Academics\Entities\Batch;
use Modules\Academics\Entities\Section;
use App;
class FeeReportController extends Controller
{

    private  $feeInvoice;
    private  $transaciton;
    private  $studentProfileView;
    private  $academicHelper;

    public  function __construct(FeeInvoice $feeInvoice, AcademicHelper $academicHelper, Transaction $transaction, StudentProfileView $studentProfileView)
    {
        $this->feeInvoice=$feeInvoice;
        $this->transaciton=$transaction;
        $this->studentProfileView=$studentProfileView;
        $this->academicHelper=$academicHelper;

    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function feeHeadYearlyReport()
    {


//        $feeHeadList=FeeHead::all();
//
//        return $orders = DB::table('fee_transaction')
//           ->select(DB::raw('sum(fee_transaction.amount) as sums, extract(month from fee_transaction.payment_date) as month, head_id'))
//            ->groupBy('month')
//            ->groupBy('head_id')
////            ->pluck('sums', 'month');
//            ->get();

//        return date("M", strtotime("$iM/12/10"));
//        return  DB::table('fee_transaction')->where('head_id', 1)->whereMonth('created_at', date("M", strtotime("$iM'./12/10"))->sum('amount');
        return $this->feeHeadMonthly(1);




//        return array_replace(array_fill_keys(range(1, 12), 0), $orders);



        return view('fee::pages.report.test',compact('feeHeadList'));
    }





    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function studentFeeDetails(Request $request)
    {

         $feeInvoiceDetails= $this->feeInvoice
            ->where('year',$request->year_id)
            ->where('class_id',$request->class_id)
            ->where('section_id',$request->section)
            ->where('head_id',$request->fee_head)
            ->where('sub_head_id',$request->sub_head)
             ->get();
        return view('fee::modal.feedetails',compact('feeInvoiceDetails'));
    }

    // downlaod fee details

    public function studentFeeDetailsDownload(Request $request){

        // get class ans ection name
        $class=Batch::getBatchNameById($request->class_id);
        $section=Section::getSectionNameById($request->section);

        $instituteInfo=$this->academicHelper->getInstituteProfile();

        $feeInvoiceDetails= $this->feeInvoice
            ->where('year',$request->year_id)
            ->where('class_id',$request->class_id)
            ->where('section_id',$request->section)
            ->where('head_id',$request->fee_head)
            ->where('sub_head_id',$request->sub_head)
            ->get();
        view()->share(compact('feeInvoiceDetails','instituteInfo','class','section'));
        //generate PDf
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('fee::modal.download.feedetails')->setPaper('a4', 'portrait');
        return $pdf->stream();
    }





    public function getClassSectionTransaction(Request $request)
    {
//        return $request->all();
// get all student ids by class section academic year
       $studentIds= $this->studentProfileView
            ->where('academic_year',$request->year_id)
            ->where('batch',$request->class_id)
            ->where('section',$request->section)
            ->pluck('std_id')
            ->toArray();

        $feeTransactionList= $this->transaciton
            ->whereIn('std_id',$studentIds)
            ->where('std_id',$studentIds)
            ->whereBetween('payment_date', [$request->start_date, $request->end_date])
            ->get();
        return view('fee::modal.feetransaction',compact('feeTransactionList'));
    }



}
