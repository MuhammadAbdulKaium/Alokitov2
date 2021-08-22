<?php

namespace Modules\Fee\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Fee\Entities\FeeSubhead;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Modules\Fee\Entities\SubHeadFund;
class FeeSubheadController extends Controller
{
    private $feeSubhead;
    private $academicHelper;
    private $subHeadFund;
    private $data;
    public function  __construct(FeeSubhead $feeSubhead, AcademicHelper $academicHelper, SubHeadFund $subHeadFund)
    {
        $this->feeSubhead=$feeSubhead;
        $this->academicHelper=$academicHelper;
        $this->subHeadFund=$subHeadFund;

    }


    public function store(Request $request)
    {
//        return $request->all();
            // create fee head

        DB::beginTransaction();

        try {
            $feesubheadObj= new $this->feeSubhead;
            $feesubheadObj->institution_id=institution_id();
            $feesubheadObj->campus_id=campus_id();
            $feesubheadObj->head_id=$request->head_id;
            $feesubheadObj->class_id=$request->class_id;
            $feesubheadObj->name=$request->sub_head;
            $feesubheadObj->amount=$request->amount;
            $feesubheadObj->start_date=date('Y-m-d',strtotime($request->start_date));
            $feesubheadObj->due_date=date('Y-m-d',strtotime($request->due_date));
            $feesubheadObj->save();

            DB::commit();
            Session::flash('message', 'Fee Successfully Created');
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            Session::flash('message', 'Something Wrong Try Again');
        }



        return redirect()->back();

    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function feesubHeadEdit($feeheadid)
    {
        // get feehead profile
        $this->data['feesubheadProfile']=$this->feeSubhead->find($feeheadid);
        // fee head list
        $this->data['feesubHeads']=$this->feeSubhead->where('institution_id',institution_id())->where('campus_id',campus_id())->get();

        return view('fee::pages.setting.subhead',$this->data)->with('page', 'subhead');
    }

    // get sub head by head id
    public function  getSubHeadByHeadId(Request $request){
        // response array
        $data = array();
        // all batch
        $allSubhead = $this->feeSubhead->where('head_id',$request->id)->get();
        // looping for adding division into the batch name
        foreach ($allSubhead as $subHead) {
                $data[] = array('id' => $subHead->id, 'sub_head' => $subHead->name);
        }

        //then sent this data to ajax success
        return $data;

    }


    public function getSubheadClassAmount(Request $request)
    {
        $feeSubheadProfile = $this->feeSubhead->where('id', $request->id)->first();
        $amount = $feeSubheadProfile->amount;
        return $amount;

    }

    public function getSubHeadByClassHead(Request $request){
//        return $request->all();
        // response array
        $data = array();
        // all batch
        $allSubhead = $this->feeSubhead->where('class_id',$request->class_id)->where('head_id',$request->head_id)->get();
        // looping for adding division into the batch name
        foreach ($allSubhead as $subHead) {
            $data[] = array('id' => $subHead->id, 'sub_head' => $subHead->name);
        }

        //then sent this data to ajax success
        return $data;

    }



}
