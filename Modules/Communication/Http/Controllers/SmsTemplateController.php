<?php

namespace Modules\Communication\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Communication\Entities\SmsTemplate;
use App\Http\Controllers\Helpers\AcademicHelper;

class SmsTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */

    private $smsTemplate;
    private $academicHelper;


    public function  __construct(SmsTemplate $smsTemplate, AcademicHelper $academicHelper)
    {
        $this->smsTemplate= $smsTemplate;
        $this->academicHelper= $academicHelper;

    }


    public function createSmsTemplate()
    {
        return view('communication::pages.sms-template.create');

    }


    public function smsTemplateList()
    {

        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

        $smsTemplateList= $this->smsTemplate->where('institution_id', $instituteId)->where('campus_id', $campus_id)->orderByDesc('id')->paginate(20);
        return view('communication::pages.sms-template.index',compact('smsTemplateList'));

    }




    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function storeSmsTemplate(Request $request)
    {

        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();
        $sms_template_id=$request->input('sms_template_id');

        if(empty($sms_template_id)) {
            $smsTemplateObj= new $this->smsTemplate;
            $smsTemplateObj->institution_id=$instituteId;
            $smsTemplateObj->campus_id=$campus_id;
            $smsTemplateObj->template_name=$request->input('template_name');
            $smsTemplateObj->sms_type=$request->input('sms_type');
            $smsTemplateObj->message=$request->input('message');
            $result=$smsTemplateObj->save();
            if($result){
                return "insert";
            }
        } else {

            $smsTemplateProfile= $this->smsTemplate->find($sms_template_id);
            $smsTemplateProfile->institution_id=$instituteId;
            $smsTemplateProfile->campus_id=$campus_id;
            $smsTemplateProfile->template_name=$request->input('template_name');
            $smsTemplateProfile->sms_type=$request->input('sms_type');
            $smsTemplateProfile->message=$request->input('message');
            $result=$smsTemplateProfile->save();
            if($result){
                return "update";
            }

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
        return view('communication::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function smsTemplateEdit($sms_template_id)
    {
        $smsTemplateProfile= $this->smsTemplate->find($sms_template_id);
        if(!empty($smsTemplateProfile)) {
            return view('communication::pages.sms-template.create', compact('smsTemplateProfile'));
        }

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
    public function smsTemplateDelete($sms_template_id)
    {
         $smsTemplateProfile= $this->smsTemplate->find($sms_template_id);
        if($smsTemplateProfile) {
            $smsTemplateProfile->delete();
            return 'success';
        } else {
            return "error";
        }

    }
}
