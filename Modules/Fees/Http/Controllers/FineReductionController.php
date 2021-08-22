<?php

namespace Modules\Fees\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Fees\Entities\FineReduction;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Fees\Entities\FeesInvoice;



class FineReductionController extends Controller
{

    private  $fineReduction;
    private  $academicHelper;
    private  $feesInvoice;

    public function __construct(FineReduction $fineReduction, AcademicHelper $academicHelper,FeesInvoice $feesInvoice)
    {
        $this->fineReduction            = $fineReduction;
        $this->academicHelper           = $academicHelper;
        $this->feesInvoice           = $feesInvoice;
    }



    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('fees::pages.fine_reduction');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    // search invoice for reduction
    public function searchInvoice(Request $request)
    {

        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();
        $invoice_id=$request->input('invoice_id');
        $invoice=$this->feesInvoice->where('institution_id',$instituteId)->where('campus_id',$campus_id)->where('id',$invoice_id)->first();
        return view('fees::pages.fine_reduction',compact('invoice','invoice_id'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function fineReductionStore(Request $request)
    {
            $instituteId=$this->academicHelper->getInstitute();
            $campus_id=$this->academicHelper->getCampus();
            $invoiceId=$request->input('invoice_id');
            $fineReductionProifle=$this->fineReduction->where('invoice_id',$invoiceId)->first();
        if(!empty($fineReductionProifle)) {
                $fineReductionProifle->institution_id =$instituteId;
                $fineReductionProifle->campus_id =$campus_id;
                $fineReductionProifle->invoice_id = $request->input('invoice_id');
                $fineReductionProifle->due_fine = $request->input('reduction_due_fine');
                $fineReductionProifle->attendance_fine = $request->input('reduction_attendance_fine');
                $fineReductionProifle->save();
                return "update";
        } else {
                $fineReduction = $this->fineReduction;
                $fineReduction->institution_id =$instituteId;
                $fineReduction->campus_id =$campus_id;
                $fineReduction->invoice_id = $request->input('invoice_id');
                $fineReduction->due_fine = $request->input('reduction_due_fine');
                $fineReduction->attendance_fine = $request->input('reduction_attendance_fine');
                $fineReduction->save();
            return "create";
        }


    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function showFineReductionModal($invoiceId,$dueFine,$attendanceFine)
    {
        $fineReductionProfile=$this->fineReduction->where('invoice_id',$invoiceId)->first();
        return view('fees::pages.modal.fine_reduction',compact('invoiceId','dueFine','attendanceFine','fineReductionProfile'));
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
