<?php

namespace Modules\Fee\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Modules\Fee\Entities\SubHeadFine;

class SubHeadFineController extends Controller
{
    private $subHeadFine;
    private $data;
    public function  __construct(SubHeadFine $subHeadFine)
    {
        $this->subHeadFine=$subHeadFine;

    }


    public function subheadFineStore(Request $request)
    {
//        return $this->subHeadFine->all();
        $subHeadFineProfile=$this->subHeadFine->where('institution_id',institution_id())->where('campus_id',campus_id())->where('class_id',$request->class_id)->where('head_id',$request->fee_head)->where('class_id',$request->sub_head_id)->get();
        if(!empty($subHeadFineProfile)){
            // create sub head fine
            $subheadFineObj= new $this->subHeadFine;
            $subheadFineObj->institution_id=institution_id();
            $subheadFineObj->campus_id=campus_id();
            $subheadFineObj->head_id=$request->fee_head;
            $subheadFineObj->class_id=$request->class_id;
            $subheadFineObj->sub_head_id=$request->sub_head;
            $subheadFineObj->amount_percentage=$request->amount_percentage;
            $subheadFineObj->fine_amount=$request->fine_amount;
            $subheadFineObj->fine_type=$request->fine_type;
            $subheadFineObj->monthy_daily=$request->monthy_daily;
            $subheadFineObj->maximumfine=$request->maximum_fine;
            $subheadFineObj->save();

            Session::flash('message', 'Fee fine successfully created');
        } else {
            Session::flash('message', 'Class wise fee fine already exist');
        }

        return redirect()->back();

    }

}
