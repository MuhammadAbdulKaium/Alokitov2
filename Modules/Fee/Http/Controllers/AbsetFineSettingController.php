<?php

namespace Modules\Fee\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Fee\Entities\AbsetFineSetting;
use Illuminate\Support\Facades\Session;
class AbsetFineSettingController extends Controller
{
    private  $absetFineSetting;
    /**
     * Display a listing of the resource
     * @return Response
     */

    public  function __construct(AbsetFineSetting $absetFineSetting)
    {
        $this->absetFineSetting=$absetFineSetting;
    }

    public function store(Request $request)
    {
        $absent_fine_id=$request->absent_fine_id;
        if(!empty($absent_fine_id)){

            // create fee head
            $absentFineProfile= $this->absetFineSetting->find($absent_fine_id);
            $absentFineProfile->class=$request->class_id;
            $absentFineProfile->period=$request->period;
            $absentFineProfile->amount=$request->amount;
            $absentFineProfile->save();
            Session::flash('message', 'Absent Fine Successfully Updated');
        }else {
            // create fee head
            $absentFineObje= new $this->absetFineSetting;
            $absentFineObje->institution_id=institution_id();
            $absentFineObje->campus_id=campus_id();
            $absentFineObje->class=$request->class_id;
            $absentFineObje->period=$request->period;
            $absentFineObje->amount=$request->amount;
            $absentFineObje->save();
            Session::flash('message', 'Absent Fine Successfully Created');
        }

        return redirect()->back();
    }


}
