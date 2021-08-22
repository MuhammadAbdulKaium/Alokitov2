<?php

namespace Modules\Fees\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Fees\Entities\InvoiceFine;
use Carbon\Carbon;
use App;
use App\Http\Controllers\Helpers\AcademicHelper;


class InvoiceFineController extends Controller
{

    private  $invoiceFine;
    private  $academicHelper;

    public function __construct(InvoiceFine $invoiceFine, AcademicHelper $academicHelper)
    {
        $this->invoiceFine           = $invoiceFine;
        $this->academicHelper           = $academicHelper;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('fees::index');
    }


    public function  getDateWiseFineReport(Request $request){

        $reportTitle='Student Fine Reports';

        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

            //institute Information
            $instituteInfo=$this->academicHelper->getInstituteProfile();

            $from_date = date('Y-m-d H:i:s', strtotime($request->input('from_date')));
            $to_date = date('Y-m-d', strtotime($request->input('to_date')));
            $to_date = new Carbon($to_date);
            $to_date = $to_date->endOfDay();

            $invoiceFines=$this->invoiceFine->where('institution_id',$instituteId)->where('campus_id',$campus_id)->whereBetween('created_at', array($from_date, $to_date))->get();

            view()->share(compact('invoiceFines', 'instituteInfo','reportTitle'));

            //generate PDf
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView('fees::pages.report.fine-fees-report')->setPaper('a4', 'portrait');
            // return $pdf->stream();
            $downloadFileName = "fine_fees_report.pdf";
            return $pdf->download($downloadFileName);
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
