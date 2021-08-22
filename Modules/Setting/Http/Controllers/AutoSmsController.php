<?php

namespace Modules\Setting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Setting\Entities\Institute;
use Modules\Communication\Entities\SmsTemplate;
use Modules\Setting\Entities\AutoSmsModule;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Setting\Entities\AutoSmsSetting;
class AutoSmsController extends Controller
{

    private  $institute;
    private  $autoSmsModule;
    private  $smsTemplate;
    private  $academicHelper;
    private  $autoSmsSetting;

    public function __construct(Institute $institute,AutoSmsSetting $autoSmsSetting, SmsTemplate $smsTemplate,AutoSmsModule $autoSmsModule, AcademicHelper $academicHelper)
    {
        $this->institute             = $institute;
        $this->smsTemplate           = $smsTemplate;
        $this->autoSmsModule           = $autoSmsModule;
        $this->academicHelper           = $academicHelper;
        $this->autoSmsSetting           = $autoSmsSetting;
    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('setting::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('setting::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {

        $instituteId= $this->academicHelper->getInstitute();
        $campusId= $this->academicHelper->getCampus();

        $autoSmsModule= new $this->autoSmsModule();
        $autoSmsModule->status_code=$request->input('status_code');
        $autoSmsModule->description=$request->input('description');
        $autoSmsModule->status=$request->input('status');
        $autoSmsModule->ins_id= $instituteId;
        $autoSmsModule->campus_id= $campusId;
        $result=$autoSmsModule->save();
            if($request){

                $autoSmsSetting=new $this->autoSmsSetting;
                $autoSmsSetting->user_type='["4","2"]';
                $autoSmsSetting->ins_id=$instituteId;
                $autoSmsSetting->campus_id=$campusId;
                $autoSmsSetting->auto_sms_module_id=$autoSmsModule->id;
                $autoSmsSetting->save();

                Session::flash('success', 'Auto Sms Modules  Successfully Added');
                return redirect()->back();
            }else{
                // warning msg
                Session::flash('warning', 'Please Check Something are Wrong');
                return redirect()->back();

            }
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('setting::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function updateModal($id)
    {
        $instituteId= $this->academicHelper->getInstitute();
        $campusId= $this->academicHelper->getCampus();
        $autoSmsModuleProfile=$this->autoSmsModule->find($id);
        return view('setting::modals.auto_sms_modules',compact('autoSmsModuleProfile'));
    }



    public function updateSmsModules(Request $request){

        $sms_modules_id= $request->input('sms_modules_id');
        $status_code= $request->input('status_code');
        $message= $request->input('message');
        $autoSmsModuleProfile=$this->autoSmsModule->find($sms_modules_id);
        $autoSmsModuleProfile->status_code=$status_code;
        $result=$autoSmsModuleProfile->save();
        return redirect()->back();

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
