<?php

namespace Modules\Fees\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Academics\Entities\Batch;
use Modules\Fees\Entities\FeesBatchSection;
use Modules\Student\Entities\StudentEnrollment;
use Modules\Fees\Entities\FeesInvoice;
use Modules\Fees\Entities\Fees;
use App;
use DB;
use App\Http\Controllers\Helpers\AcademicHelper;

class FeesBatchSectionController extends Controller
{

    private  $batch;
    private  $feesBatchSection;
    private  $studentEnrollment;
    private  $feesInvoice;
    private  $academicHelper;

    public function __construct(Batch $batch, AcademicHelper $academicHelper, Fees $fees,FeesInvoice $feesInvoice, FeesBatchSection $feesBatchSection, StudentEnrollment $studentEnrollment)
    {
        $this->batch                        = $batch;
        $this->feesBatchSection             = $feesBatchSection;
        $this->studentEnrollment             = $studentEnrollment;
        $this->feesInvoice                   = $feesInvoice;
        $this->fees                         = $fees;
        $this->academicHelper              = $academicHelper;
    }


    /**
     * Display a listing of the resource.
     * @return Response
     */

    // get batch name by fees Id
    public function getBatchByFees(Request $request)
    {


        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

        // response array
        $data = array();
        // all fees batch
         $feesBatchSection = $this->feesBatchSection->where('fees_id', $request->id)->where('institution_id',$instituteId)->where('campus_id',$campus_id)->distinct('batch_id')->get(['batch_id']);
        // looping for adding division into the batch name
        foreach ($feesBatchSection as $batchSection) {
            if ($batchSection->batch()->division()) {
                $data[] = array('id' => $batchSection->batch_id, 'batch_name' => $batchSection->batch()->batch_name.'-'.$batchSection->batch()->division()->name);
            } else {
                $data[] = array('id' => $batchSection->batch_id, 'batch_name' => $batchSection->batch()->batch_name);


            }
        }

        //then sent this data to ajax success
        return $data;
    }




    // get batch name by fees Id
    public function getSectionByFees(Request $request)
    {

        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

        // response array
        $data = array();
        // all fees section
        $feesBatchSection = $this->feesBatchSection->where('fees_id', $request->fees_id)->where('batch_id',$request->batch_id)->where('institution_id',$instituteId)->where('campus_id',$campus_id)->get();
        // looping  section name
        foreach ($feesBatchSection as $batchSection) {

            $data[] = array('id' => $batchSection->section_id, 'section_name' => $batchSection->section()->section_name);

        }

        //then sent this data to ajax success
        return $data;
    }


    public  function getBatchSectionWaiver(Request $request){

        $reportTitle='Class Section Waiver Report';
        $instituteInfo=session()->get('institute');
        $instituteProfile=$this->academicHelper->getInstituteProfile();

        $fees_id=$request->input('fees_id');
        $batch_id=$request->input('batch');
        $section_id=$request->input('section');
    // all search input array declare
        $allSearchInputs=array();

        if ($fees_id) {
            $allSearchInputs['fees_invoices.fees_id'] = $fees_id;
        }

        if ($batch_id) {
            $allSearchInputs['student_enrollments.batch'] = $batch_id;
        }

        if ($section_id) {
            $allSearchInputs['student_enrollments.section'] = $section_id;
        }

        $feesInfo=$this->fees->find($fees_id);

          $batchSectionList = DB::table('fees_invoices')
           ->where('fees_invoices.fees_id' ,'=', $fees_id)
           ->join('student_enrollments', 'fees_invoices.payer_id', '=', 'student_enrollments.std_id')
                ->where(function($query) use ($batch_id,$section_id){
                    if(!empty($batch_id))
                    $query->orWhere('student_enrollments.batch', '=', $batch_id);
                    if(!empty($section_id))
                        $query->where('student_enrollments.section','=', $section_id);
                })
            ->distinct('student_enrollments.batch')
            ->distinct('student_enrollments.section')
//                                ->whereNotNull('fees_invoices.waiver_fees')
            ->get(['batch','section']);


         $feesWaiverInvoiceList= $this->feesInvoice->where('fees_invoices.fees_id',$fees_id)
                                ->join('student_enrollments', 'fees_invoices.payer_id', '=', 'student_enrollments.std_id')
                                ->where(function($query) use ($batch_id,$section_id){
                                    if(!empty($batch_id))
                                        $query->orWhere('student_enrollments.batch', '=', $batch_id);
                                    if(!empty($section_id))
                                        $query->where('student_enrollments.section','=', $section_id);
                                })
                                 ->orderBy('student_enrollments.batch')
                                    ->get();


//         $users = DB::table('fees_invoices')
//           ->where('fees_invoices.fees_id' ,'=', $fees_id)
//           ->join('student_enrollments', 'fees_invoices.payer_id', '=', 'student_enrollments.std_id')
//           ->where(function($query) use ($batch_id,$section_id){
//               $query->orWhere('student_enrollments.batch', '=', $batch_id);
//               $query->orWhere('student_enrollments.section','=', $section_id);
//           })
//           ->get();


//         return view('reports::pages.report.waiver_report',compact('instituteInfo','feesWaiverInvoiceList','batchSectionList','feesInfo'));

        view()->share(compact('instituteProfile','feesWaiverInvoiceList','batchSectionList','feesInfo','reportTitle'));

        //generate PDf
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('reports::pages.report.fees_waiver_report')->setPaper('a4', 'portrait');
        // return $pdf->stream();
        $downloadFileName = "waiver_reports.pdf";
        return $pdf->download($downloadFileName);



        // ifthekhar viya code
//        $demo_fees_invoice = $this->feesInvoice->first();
//        return $feesInvoiceList= $demo_fees_invoice->students()
//                                ->join('fees_invoices',function ($join) {
//                                        $join->on('fees_invoices.payer_id', '=', 'student_enrollments.std_id')
//                                            ->where('fees_invoices.id', '=', 4)
//                                        ->where('fees_invoices.id', '=', 4)->where('student_enrollments.batch', '=', 4);


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
