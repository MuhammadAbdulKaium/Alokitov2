<?php

namespace Modules\Fee\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Modules\Fee\Entities\FeeHeadFundSetting;
class FeeHeadFundSettingController extends Controller
{

    private  $feeHeadFundSetting;

    public function  __construct(FeeHeadFundSetting $feeHeadFundSetting)
    {
       $this->feeHeadFundSetting= $feeHeadFundSetting;
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
//        return json_encode($request->subheadlist);
        // create fee head
        $feeCreateObj= new $this->feeHeadFundSetting;
        $feeCreateObj->institution_id=institution_id();
        $feeCreateObj->campus_id=campus_id();
        $feeCreateObj->head_id=$request->head_id;
        $feeCreateObj->fundlist=json_encode($request->fundlist);
        $feeCreateObj->save();

        Session::flash('message', 'Fee Successfully Created');

        return redirect()->back();
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function headFundList(Request $request)
    {
        $feeProfile=$this->feeHeadFundSetting->where('head_id',$request->headid)->first();
        if(!empty($feeProfile)) {
            $feeFundList = json_decode($feeProfile->fundlist, true);
            return view('fee::modal.fundlist', compact('feeFundList'));
        } else {
            return '';
        }

    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('fee::edit');
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
