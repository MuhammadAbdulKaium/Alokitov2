<?php

namespace Modules\Setting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Setting\Entities\FessSetting;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Helpers\AcademicHelper;

class FeesSettingController extends Controller
{


    private $academicHelper;

    // constructor
    public function __construct(AcademicHelper $academicHelper)
    {
        $this->academicHelper  = $academicHelper;
    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        ///get institute Id and Campus Id
        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

        $feesSettings=FessSetting::where('ins_id',$instituteId)->where('campus_id',$campus_id)->get();
        return view('setting::fees.fees-setting-list',compact('feesSettings'));
    }



    public function create()
    {
        return view('setting::fees.index');
    }


    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {

        // fees setting id
        $fees_setting_id=$request->input('fees_setting_id');

        ///get institute Id and Campus Id
        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();


        if(empty($fees_setting_id)) {
            $feesSetting = new FessSetting;
            $feesSetting->ins_id = $instituteId;
            $feesSetting->campus_id = $campus_id;
            $feesSetting->attribute = $request->input('attribute');
            $feesSetting->setting_type = $request->input('setting_type');
            $feesSetting->value = $request->input('value');
            $insert = $feesSetting->save();
            if ($insert) {
                Session::flash('success', 'Fees Setting Successfully');
                return redirect()->back();
            } else {
                Session::flash('error', 'Fees Setting Error Found');
                return redirect()->back();
            }
        } else {

            $feesSetting =FessSetting::find($fees_setting_id);
            $feesSetting->ins_id = $instituteId;
            $feesSetting->campus_id = $campus_id;
            $feesSetting->attribute = $request->input('attribute');
            $feesSetting->setting_type = $request->input('setting_type');
            $feesSetting->value = $request->input('value');
            $insert = $feesSetting->save();
            if ($insert) {
                Session::flash('success', 'Fees Setting Update Successfully');
                return redirect()->back();
            } else {
                Session::flash('error', 'Fees Setting Error Found');
                return redirect()->back();
            }

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
    public function edit($feesSettingId)
    {
        $feesSettingProfile=FessSetting::find($feesSettingId);
        return view('setting::fees.index',compact('feesSettingProfile'));
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
    public function delete($feesSettingId)
    {
        $feesSetting=FessSetting::find($feesSettingId)->delete();

    }
}
