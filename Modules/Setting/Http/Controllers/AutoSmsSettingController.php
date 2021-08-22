<?php

namespace Modules\Setting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Setting\Entities\Institute;
use Modules\Setting\Entities\AutoSmsSetting;
use Modules\Setting\Entities\AutoSmsModule;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Helpers\AcademicHelper;
class AutoSmsSettingController extends Controller
{

    private  $institute;
    private  $autoSmsModule;
    private  $autoSmsSetting;
    private  $academicHelper;

    public function __construct(Institute $institute,AutoSmsSetting $autoSmsSetting,AutoSmsModule $autoSmsModule, AcademicHelper $academicHelper)
    {
        $this->institute             = $institute;
        $this->autoSmsModule           = $autoSmsModule;
        $this->autoSmsSetting           = $autoSmsSetting;
        $this->academicHelper           = $academicHelper;
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

//        return $request->all();
        //cehck sms setting count
        $sms_setting= $request->input('sms_setting');

        if(!empty($sms_setting)){

            $auto_sms_module_ids=$request->input("auto_sms_module");
            $userTypeArray=[];
            for ($i=0;$i<count($auto_sms_module_ids);$i++){
                $userTypeArray[$i]=$request->input("user_type-".$auto_sms_module_ids[$i]);
            }


            foreach ($auto_sms_module_ids as $key=>$value) {
                $userString=json_encode($userTypeArray[$key]);
                $autoSmsSetting=$this->autoSmsSetting->where('auto_sms_module_id',$auto_sms_module_ids[$key])->first();
                $autoSmsSetting->user_type=$userString;
                $autoSmsSetting->ins_id=$instituteId;
                $autoSmsSetting->campus_id=$campusId;
                $autoSmsSetting->save();
            }

            Session::flash('success', 'Auto Sms Setting  Successfully');
            return redirect()->back();

        } else {

            $auto_sms_module_ids=$request->input("auto_sms_module");
            $userTypeArray=[];
            for ($i=0;$i<count($auto_sms_module_ids);$i++){
                $userTypeArray[]=$request->input("user_type-".$auto_sms_module_ids[$i]);
            }


            for ($i=0;$i<count($auto_sms_module_ids);$i++) {
                $userString=json_encode($userTypeArray[$i]);
                $autoSmsSetting=new $this->autoSmsSetting;
                $autoSmsSetting->user_type=$userString;
                $autoSmsSetting->auto_sms_module_id=$auto_sms_module_ids[$i];
                $autoSmsSetting->ins_id=$instituteId;
                $autoSmsSetting->campus_id=$campusId;
                $autoSmsSetting->save();

            }

            Session::flash('success', 'Auto Sms Setting  Successfully');
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
    public function edit()
    {
        return view('setting::edit');
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
