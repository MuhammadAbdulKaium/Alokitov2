<?php

namespace Modules\Fees\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Fees\Entities\FeeType;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Fees\Entities\Fees;



class FeeTypeController extends Controller
{

    private  $feeType;
    private  $fees;
    private  $academicHelper;

    public function __construct(FeeType $feeType, Fees $fees, AcademicHelper $academicHelper)
    {
        $this->feeType             = $feeType;
        $this->fees             = $fees;
        $this->academicHelper      = $academicHelper;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('fees::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function store(Request $request)
    {
        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();


        $feetypeId=$request->input('feetype_id');
        if(empty($feetypeId)) {
            $fee_type = new $this->feeType;
            $fee_type->institution_id=$instituteId;
            $fee_type->campus_id=$campus_id;
            $fee_type->fee_type_name = $request->input('fee_type_name');
            $fee_type->status = "0";
            $fee_type->save();
            // session Message
            Session::flash('insert', 'Fee Type Create Successfully');
            return redirect()->back();
        } else {

            $fee_type = $this->feeType->find($feetypeId);
            $fee_type->institution_id=$instituteId;
            $fee_type->campus_id=$campus_id;
            $fee_type->fee_type_name = $request->input('fee_type_name');
            $fee_type->status = "0";
            $fee_type->update();
            // session Message
            Session::flash('update', 'Fee Type Update Successfully');
            return redirect()->back();
        }


    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('fees::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($feetypeId)
    {
        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

        $feetypeProfile=$this->feeType->find($feetypeId);
        $feetypes=$this->feeType->where('institution_id',$instituteId)->where('campus_id',$campus_id)->paginate(10);
        return view('fees::pages.feetype',compact('feetypes','feetypeProfile'))->with('page', 'feetype');

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
    public function delete($feetypeId)
    {
        $feesProfile= $this->fees->where('fee_type', $feetypeId)->first();
        if(!empty($feesProfile)) {
            return 'warning';
        } else {
            $feeTypeProfile=$this->feeType->find($feetypeId);
            $feeTypeProfile->delete();
            return 'success';
        }
    }
}
