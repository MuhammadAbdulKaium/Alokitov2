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

class FeesStructureController extends Controller
{
    private $academicHelper;
    private $academicsLevel;


    public function __construct(AcademicHelper $academicHelper,AcademicsLevel $academicsLevel)
    {
        $this->academicHelper = $academicHelper;
        $this->academicsLevel = $academicsLevel;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('cadetfees::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('cadetfees::feesStructure.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $feesStructure = new FeesStructure();
        $feesStructure->structure_name = $request->structure_name;
        $feesStructure->campus_id = $this->academicHelper->getCampus();
        $feesStructure->institute_id = $this->academicHelper->getInstitute();
        $feesStructure->created_by = Auth::user()->id;
        $feesStructureStore=$feesStructure->save();
        if ($feesStructureStore) {
            Session::flash('message', 'Success!Data has been saved successfully.');
            return redirect()->back();
        } else {
            Session::flash('message', 'Success!Data has not been saved successfully.');
            return redirect()->back();

        }
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
        $feesStructure = FeesStructure::where('id',$id)->first();
        return view('cadetfees::feesStructure.edit',compact('feesStructure'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $feesStructure=FeesStructure::findOrFail($id);
        $feesStructureUpdate = $feesStructure->update([
            'structure_name' => $request->structure_name
        ]);
        if ($feesStructureUpdate) {
            Session::flash('message', 'Success!Data has been Updated successfully.');
            return redirect()->back();
        } else {
            Session::flash('message', 'Success!Data has not been Updated.');
            return redirect()->back();

        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
