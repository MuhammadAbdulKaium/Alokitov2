<?php

namespace Modules\Fee\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Fee\Entities\FeeWaiverType;
use Illuminate\Support\Facades\Session;

class FeeWaiverTypeController extends Controller
{
    private $feewaiverType;
    private $academicHelper;
    private $data;
    public function  __construct(FeeWaiverType $feewaiverType, AcademicHelper $academicHelper)
    {
        $this->feewaiverType=$feewaiverType;
        $this->academicHelper=$academicHelper;

    }


    public function store(Request $request)
    {
        $feewaiverType_id=$request->feewaiverType_id;
        if(!empty($feewaiverType_id)){

            // create fee head
            $feewaiverTypeProfile= $this->feewaiverType->find($feewaiverType_id);
            $feewaiverTypeProfile->name=$request->name;
            $feewaiverTypeProfile->save();
            Session::flash('message', 'Fee Waiver Type Successfully Updated');
        }else {
            // create fee head
            $feewaiverTypeObj= new $this->feewaiverType;
            $feewaiverTypeObj->institution_id=institution_id();
            $feewaiverTypeObj->campus_id=campus_id();
            $feewaiverTypeObj->name=$request->name;
            $feewaiverTypeObj->save();
            Session::flash('message', 'Fee Waiver Type Successfully Created');
        }

        return redirect()->back();

    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function feewaiverTypeEdit($waiverid)
    {
        // get feehead profile
        $this->data['feewaiverTypeProfile']=$this->feewaiverType->find($waiverid);
        // fee head list
        $this->data['feewaiverTypes']=$this->feewaiverType->where('institution_id',institution_id())->where('campus_id',campus_id())->get();

        return view('fee::pages.setting.waiver',$this->data)->with('page', 'waiver');
    }
}
