<?php

namespace Modules\Fee\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Fee\Entities\FeeFund;
use Illuminate\Support\Facades\Session;
class FeeFundController extends Controller
{
    private $feefund;
    private $academicHelper;
    private $data;
    public function  __construct(FeeFund $feefund, AcademicHelper $academicHelper)
    {
        $this->feefund=$feefund;
        $this->academicHelper=$academicHelper;

    }


    public function store(Request $request)
    {
        $feefund_id=$request->feefund_id;
        if(!empty($feefund_id)){

            // create fee head
            $feefundProfile= $this->feefund->find($feefund_id);
            $feefundProfile->name=$request->name;
            $feefundProfile->save();
            Session::flash('message', 'Fee Head Successfully Updated');
        }else {
            // create fee head
            $feefundObj= new $this->feefund;
            $feefundObj->institution_id=institution_id();
            $feefundObj->campus_id=campus_id();
            $feefundObj->name=$request->name;
            $feefundObj->save();
            Session::flash('message', 'Fee Head Successfully Created');
        }

        return redirect()->back();

    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function feefundEdit($feefundid)
    {
        // get feefund profile
        $this->data['feefundProfile']=$this->feefund->find($feefundid);
        // fee head list
        $this->data['feefunds']=$this->feefund->where('institution_id',institution_id())->where('campus_id',campus_id())->get();

        return view('fee::pages.setting.fund',$this->data)->with('page', 'fund');
    }

}
