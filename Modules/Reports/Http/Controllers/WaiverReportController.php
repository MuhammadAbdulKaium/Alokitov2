<?php

namespace Modules\Reports\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Academics\Entities\Batch;
use Modules\Student\Entities\StudentWaiver;
use Modules\Student\Entities\StudentEnrollment;
use DB;
use App;
use App\Http\Controllers\Helpers\AcademicHelper;


class WaiverReportController extends Controller
{

    private  $batch;
    private  $waiver;
    private  $studentEnrollment;
    private  $academicHelper;

    public function __construct( Batch $batch, StudentWaiver $waiver,StudentEnrollment $studentEnrollment,AcademicHelper $academicHelper)
    {
        $this->batch                    = $batch;
        $this->waiver                   = $waiver;
        $this->studentEnrollment        = $studentEnrollment;
        $this->academicHelper        = $academicHelper;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function feesWaiverReportModal()
    {
        return view('reports::pages.modals.fees-waiver-report-modal');
    }


    /*
     * Single or Bactch section Student Waiver Report Modals
     */
    public function waiverReportModal()
    {
        //get academic years
        $academicYear=session()->get('academic_year');
        // get batch list
        $batchs=$this->batch->where('academics_year_id',$academicYear)->get();
        return view('reports::pages.modals.waiver-report-modal',compact('batchs'));
    }


    public  function batchSectionWaiverReport(Request $request){

        $reportTitle='Waiver Report ';
        // get institution information
        $instituteInfo=session()->get('institute');
        $instituteProfile=$this->academicHelper->getInstituteProfile();

        $batch_id=$request->input('batch');
        $section_id=$request->input('section');
        $waiverStatus=$request->input('waiver_status');
        $download_type=$request->input('doc_types');
        $from_date=date('Y-m-d',strtotime($request->input('from_date')));
         $to_date=date('Y-m-d',strtotime($request->input('to_date')));


        //$batchSectionList = DB::table('student_waiver')->where('start_date','>=',$from_date)->where('end_date','<=',$to_date)->where('status',$waiverStatus)->get();
        // get batch and section for student waiver
        $batchSectionList = DB::table('student_waiver')->where('start_date','>=',$from_date)->where('end_date','<=',$to_date)->where('status',$waiverStatus)
            ->join('student_enrollments', 'student_waiver.std_id', '=', 'student_enrollments.std_id')
               ->where(function($query) use ($batch_id,$section_id,$waiverStatus){
                   if(!empty($batch_id))
                       $query->orWhere('student_enrollments.batch', '=', $batch_id);
                   if(!empty($section_id))
                       $query->where('student_enrollments.section','=', $section_id);
                   if(!empty($waiverStatus))
                       $query->where('student_waiver.status', '=', $waiverStatus);
               })
            ->distinct('student_enrollments.batch')
            ->distinct('student_enrollments.section')
            ->get(['batch','section']);

        // student waiver batch and section
        $studentWaivers=$this->waiver->where('start_date','>=',$from_date)->where('end_date','<=',$to_date)
            ->join('student_enrollments', 'student_waiver.std_id', '=', 'student_enrollments.std_id')
            ->where(function($query) use ($batch_id,$section_id,$waiverStatus){
                if(!empty($batch_id))
                    $query->orWhere('student_enrollments.batch', '=', $batch_id);
                if(!empty($section_id))
                    $query->where('student_enrollments.section','=', $section_id);
                if(!empty($waiverStatus))
                    $query->where('student_waiver.status', '=', $waiverStatus);
            })
            ->orderBy('student_enrollments.batch')
            ->get();


        view()->share(compact('instituteProfile','studentWaivers','batchSectionList','reportTitle'));

        //generate PDf
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('reports::pages.report.waiver_report')->setPaper('a4', 'portrait');
        // return $pdf->stream();
        $downloadFileName = "waiver_reports.pdf";
        return $pdf->download($downloadFileName);


    }


    public  function singleStudentWaiverReport(Request $request){

        $reportTitle='Student Waiver Report';
        // get institution information
        $instituteInfo=session()->get('institute');
        $instituteProfile=$this->academicHelper->getInstituteProfile();

        $std_id=$request->input('std_id');
        $studentWaiverProifle=$this->waiver->where('std_id',$std_id)->first();

        view()->share(compact('instituteProfile','studentWaiverProifle','reportTitle'));

        //generate PDf
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('reports::pages.report.single_student_waiver_report')->setPaper('a4', 'portrait');
        // return $pdf->stream();
        $downloadFileName = "waiver_reports.pdf";
        return $pdf->download($downloadFileName);
    }


    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('reports::create');
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
        return view('reports::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('reports::edit');
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
