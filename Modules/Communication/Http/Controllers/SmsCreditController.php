<?php

namespace Modules\Communication\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Modules\Setting\Entities\Institute;
use Modules\Communication\Entities\SmsCredit;
use Modules\Communication\Entities\SmsLog;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Setting\Entities\InstitutionSmsPrice;
use PDF;


class SmsCreditController extends Controller
{


    private  $institute;
    private  $smsCredit;
    private  $smsLog;
    private  $academicHelper;
    private  $institutionSmsPrice;


    public function __construct(Institute $institute, InstitutionSmsPrice $institutionSmsPrice,  SmsCredit $smsCredit,SmsLog $smsLog,AcademicHelper $academicHelper)
    {
        $this->institute             = $institute;
        $this->smsCredit             = $smsCredit;
        $this->smsLog                = $smsLog;
        $this->academicHelper        = $academicHelper;
        $this->institutionSmsPrice        = $institutionSmsPrice;
    }




    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {


        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

         $smsCredits=$this->smsCredit->where('institution_id',$instituteId)->where('campus_id',$campus_id)->where('status','1')->orderBy('id','desc')->paginate(10);
        // sms _creadit COunt
        $smsCreditCount=$this->smsCredit->where('institution_id',$instituteId)->where('campus_id',$campus_id)->where('status','1')->sum('sms_amount');
        // sms _sms Log Count
        $smsLogCount= $this->smsLog->where('institution_id',$instituteId)->where('campus_id',$campus_id)->count();

        //sms_creadit - sms_log
          $totalSmsCreadit=$smsCreditCount-$smsLogCount;

          // institute sms price here
        $smsPrice=$this->institutionSmsPrice->where('institution_id',$instituteId)->first();
       if(!empty($smsPrice)) $smsPrice=$smsPrice->sms_price;

        $instituteProfile=$this->institute->select('institute_name')->where('id',$instituteId)->first();
        return view('communication::pages.sms.sms_credit',compact('instituteProfile','instituteId','campus_id','smsCredits','totalSmsCreadit','smsPrice'));
    }


// pending sms  list
    public function pendingSms()
        {
            $instituteId=$this->academicHelper->getInstitute();
            $campus_id=$this->academicHelper->getCampus();

            $smsCredits=$this->smsCredit->where('institution_id',$instituteId)->where('campus_id',$campus_id)->where('status','0')->orderBy('id','desc')->paginate(10);
            $instituteId=session()->get('institute');
            $instituteProfile=$this->institute->select('institute_name')->where('id',$instituteId)->first();
            return view('communication::pages.sms.pending_sms',compact('instituteProfile','instituteId','smsCredits'));
        }



    //pending sms approve method

    public function pendingSmsApprove($pending_sms_id)
    {
//        return $request->all();
        $smsCredit= $this->smsCredit->find($pending_sms_id);
        $smsCredit->status=1;
        $smsCredit->accepted_date=date('Y-m-d');
        $smsCredit->save();

    }


    // SMS credit Invoice Payment Paid Status  Update

    public function updateSmsCreditPaymentStatus($id){
        $smsCredit= $this->smsCredit->find($id);
        $smsCredit->payment_status=1;
        $smsCredit->save();
        Session::flash('success','Successfully Paid');
        return redirect()->back();
    }




    //pending sms delete

    public  function pendingSmsCancel($pending_sms_id){
        $smsCredit= $this->smsCredit->find($pending_sms_id);
        $smsCredit->status=2;
        $smsCredit->save();
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
//        return $request->all();

        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

        $smsCreditProfile=$this->smsCredit->where('institution_id',$instituteId)->where('campus_id',$campus_id)->where('month',$request->input('month'))->where('year',$request->input('year'))->get();
        $payable=0;
        if(!empty($smsCreditProfile) && $smsCreditProfile->count()>0){
            $payable=1;
        } else {
            $payable=0;
        }
        $smsCredit= new $this->smsCredit;
        $smsCredit->institution_id=$instituteId;
        $smsCredit->campus_id=$campus_id;
        $smsCredit->sms_amount=$request->input('sms_amount');
        $smsCredit->year=$request->input('year');
        $smsCredit->month=$request->input('month');
        $smsCredit->payable=$payable;
        $smsCredit->status=0;
        $smsCredit->comment=$request->input('comment');
        $smsCredit->submission_date=date('Y-m-d');
        $smsCredit->submitted_by="Session User";
        $smsCredit->sms_type=0;
        $smsCredit->save();

        return redirect()->back();

    }



    /**
     * Show the specified resource.
     * @return Response
     */
    public function show(Request $request,$sms_credit_id)
    {
        $smsCreditProfile=$this->smsCredit->find($sms_credit_id);
        return view('communication::pages.modal.sms.sms_credit_update',compact('smsCreditProfile'));
    }


    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

        $sms_credit_id=$request->input('sms_credit_id');
        $smsCredit=$this->smsCredit->find($sms_credit_id);
        $smsCredit->institution_id=$instituteId;
        $smsCredit->campus_id=$campus_id;
        $smsCredit->sms_amount=$request->input('sms_amount');
        $smsCredit->status=$request->input('status');
        $smsCredit->comment=$request->input('comment');
        $smsCredit->sms_type=0;
        $smsCredit->save();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }


    public function  downloadSmsCreditInvoice($id){
//        return view('communication::pages.reports.sms_credit_invoice');
       $smsCreditProfile=$this->smsCredit->find($id);
        $smsPrice=$this->getSmsPrice();
        $instituteProfile=$this->academicHelper->getInstituteProfile();
        PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        $pdf = PDF::loadView('communication::pages.reports.sms_credit_invoice', compact('instituteProfile','smsCreditProfile','smsPrice'));
        return $pdf->stream('pdfview.pdf');
    }


    public function  getSmsPrice(){
        // institute sms price here
        $instituteId=$this->academicHelper->getInstitute();
        $smsPrice=$this->institutionSmsPrice->where('institution_id',$instituteId)->first();
        if(!empty($smsPrice)) {
            return $smsPrice=$smsPrice->sms_price;
        } else {
            return 0;
        }

    }


}
