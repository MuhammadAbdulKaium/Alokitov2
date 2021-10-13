<?php

namespace Modules\CadetFees\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Modules\Academics\Entities\AcademicsLevel;
use Modules\CadetFees\Entities\FeesHead;
use Modules\CadetFees\Entities\FeesStructure;
use Modules\CadetFees\Entities\FeesStructureDetails;
use Modules\CadetFees\Entities\FeesStructureDetailsHistory;


class FeesStructureDetailsController extends Controller
{
    private $academicHelper;
    private $academicsLevel;


    public function __construct(AcademicHelper $academicHelper,AcademicsLevel $academicsLevel)
    {
        $this->academicHelper = $academicHelper;
        $this->academicsLevel = $academicsLevel;
    }
    public function index()
    {
        return view('cadetfees::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create($id)
    {
        $feesHeads = FeesHead::where([
            'institute_id' => $this->academicHelper->getInstitute(),
            'campus_id' => $this->academicHelper->getCampus()
        ])->get();
        $structureName= FeesStructure::findOrFail($id);
        $structureDetails = FeesStructureDetails::where('structure_id',$id)->get()->keyBy('head_id');
        return view('cadetfees::feesStructureDetails.create',compact('feesHeads','structureName','structureDetails'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $sum = 0;
        $feesStructure=FeesStructure::findOrFail($request->structureID);
        $feesStructureDetails=FeesStructureDetails::where('structure_id',$request->structureID)->get();

        if(count($feesStructureDetails)>0)
        {
            for($i=0;$i<sizeof($request['checkbox']);$i++)
            {
                $sum+=$request['amount'][$i];
                $feesStructureDetailsCheck=FeesStructureDetails::where('head_id',$request['checkbox'][$i])->first();
                if($feesStructureDetailsCheck){
                    $feesStructureDetailsCheck->update([
                        'head_amount'=>$request['amount'][$i]
                    ]);
                    for($i=0;$i<sizeof($request['checkbox']);$i++) {
                        $structureDetailsHistory = new FeesStructureDetailsHistory();
                        $structureDetailsHistory->structure_id = $request->structureID;
                        $structureDetailsHistory->head_id = $request['checkbox'][$i];
                        $structureDetailsHistory->head_amount = $request['amount'][$i];
                        $structureDetailsHistory->created_by = Auth::user()->id;
                        $structureDetailsHistorySave = $structureDetailsHistory->save();
                    }
                }
                else{
                    $structureDetails = new FeesStructureDetails();
                    $structureDetails->structure_id =$request->structureID;
                    $structureDetails->head_id =$request['checkbox'][$i];
                    $structureDetails->head_amount =$request['amount'][$i];
                    $structureDetails->	created_by =Auth::user()->id;
                    $structureDetailsSave=$structureDetails->save();

                    for($i=0;$i<sizeof($request['checkbox']);$i++) {
                        $structureDetailsHistory = new FeesStructureDetailsHistory();
                        $structureDetailsHistory->structure_id = $request->structureID;
                        $structureDetailsHistory->head_id = $request['checkbox'][$i];
                        $structureDetailsHistory->head_amount = $request['amount'][$i];
                        $structureDetailsHistory->created_by = Auth::user()->id;
                        $structureDetailsHistorySave = $structureDetailsHistory->save();
                    }
                }
            }
            $feesStructureUpdate = $feesStructure->update([
                'total_fees' => $sum
            ]);

        }
        else{
            for($i=0;$i<sizeof($request['checkbox']);$i++)
            {
                $sum+=$request['amount'][$i];
                $structureDetails = new FeesStructureDetails();
                $structureDetails->structure_id =$request->structureID;
                $structureDetails->head_id =$request['checkbox'][$i];
                $structureDetails->head_amount =$request['amount'][$i];
                $structureDetails->	created_by =Auth::user()->id;
                $structureDetailsSave=$structureDetails->save();
            }
            for($i=0;$i<sizeof($request['checkbox']);$i++)
            {
                $structureDetailsHistory = new FeesStructureDetailsHistory();
                $structureDetailsHistory->structure_id =$request->structureID;
                $structureDetailsHistory->head_id =$request['checkbox'][$i];
                $structureDetailsHistory->head_amount =$request['amount'][$i];
                $structureDetailsHistory->created_by =Auth::user()->id;
                $structureDetailsHistorySave=$structureDetailsHistory->save();
            }
        }
        $feesStructureUpdate = $feesStructure->update([
            'total_fees' => $sum
        ]);

    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('cadetfees::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('cadetfees::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
//        $feesStructureAssign
    }
}
