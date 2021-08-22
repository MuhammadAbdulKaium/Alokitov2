<?php

namespace Modules\Setting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Setting\Entities\Institute;
use Modules\Setting\Entities\SmsInstitutionGetway;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Helpers\AcademicHelper;

class SmsInstitutionGetwayController extends Controller
{

    private  $institute;
    private $smsInstitutionGetway;
    private $academicHelper;

    public function __construct(Institute $institute, AcademicHelper $academicHelper,SmsInstitutionGetway $smsInstitutionGetway)
    {
        $this->institute             = $institute;
        $this->smsInstitutionGetway  = $smsInstitutionGetway;
        $this->academicHelper          = $academicHelper;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {

         $institute=$this->academicHelper->getInstituteProfile();
        return view('setting::sms.index',compact('institute'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function smsGetwayStore(Request $request)
    {
        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

        $api_paths=$request->input('api_path');
        $count=count($api_paths);
        $remark=$request->input('remark');
        $activated_upto=date('Y-m-d', strtotime($request->input('activated_upto')));
            for ($i = 0; $i < $count; $i++) {

                $smsInstitutionGetway = new $this->smsInstitutionGetway();
                $smsInstitutionGetway->institution_id =$instituteId;
                $smsInstitutionGetway->campus_id = $campus_id;
                $smsInstitutionGetway->api_path = $api_paths[$i];
                $smsInstitutionGetway->api_path = $api_paths[$i];
                $smsInstitutionGetway->sender_id = $request->input('sender_id');
                $smsInstitutionGetway->activated_upto = $activated_upto;;
                $smsInstitutionGetway->status = $request->input('status');
                $smsInstitutionGetway->save();

            }

        if ($count>0) {
            // success msg
            Session::flash('success', 'Sms Institution Getway Successfully Added');
            return redirect()->back();
        }else{
            // warning msg
            Session::flash('warning', 'Please Check Something are Wrong');
            return redirect()->back();

        }

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

        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();
         $smsGetways =$this->smsInstitutionGetway->where('institution_id',$instituteId)->where('campus_id',$campus_id)->get();
        return view('setting::sms.view',compact('smsGetways'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function smsGetwayEdit(Request $request, $smsgetwayId )
    {
        $institute=$this->academicHelper->getInstituteProfile();
         $smsGetwayProfile=$this->smsInstitutionGetway->find($smsgetwayId);
        return view('setting::sms.index',compact('institute','smsGetwayProfile'))->with('page', 'getway');;
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function smsGetwayUpdate(Request $request)
    {

        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();


        $getway_id=$request->input('getway_id');
        $smsInstitutionGetway = $this->smsInstitutionGetway->find($getway_id);
        $smsInstitutionGetway->institution_id =$instituteId;
        $smsInstitutionGetway->campus_id = $campus_id;
        $smsInstitutionGetway->api_path = $request->input('api_path');
        $smsInstitutionGetway->sender_id = $request->input('sender_id');
        $smsInstitutionGetway->remark = $request->input('remark');
        $smsInstitutionGetway->activated_upto = date('Y-m-d', strtotime($request->input('activated_upto')));
        $smsInstitutionGetway->status = $request->input('status');
        $smsInstitutionGetway->save();

        if ($smsInstitutionGetway) {
            // success msg
        Session::flash('update_msg', 'Sms Institution Getaway Successfully Updated');
        return redirect()->back();
        }else{
            // warning msg
            Session::flash('warning', 'Please Check Something are Wrong');
            return redirect()->back();

        }


    }


    // getway delete function
    public  function  deleteSmsGetWay($getway_id){
        $smsInstitutionGetway=$this->smsInstitutionGetway->find($getway_id);
        $smsInstitutionGetway->delete();
        return redirect()->back();
    }


    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}
