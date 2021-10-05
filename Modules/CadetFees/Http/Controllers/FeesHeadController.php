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

class FeesHeadController extends Controller
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
        $feesHeads = FeesHead::where([
            'institute_id' => $this->academicHelper->getInstitute(),
            'campus_id' => $this->academicHelper->getCampus()
        ])->get();

        $feesStructures = FeesStructure::where([
            'institute_id' => $this->academicHelper->getInstitute(),
            'campus_id' => $this->academicHelper->getCampus()
        ])->get();
        return view('cadetfees::feesHead.index',compact('feesHeads','feesStructures'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('cadetfees::feesHead.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
//        $validated = $request->validate([
//            'fees_head' => 'required|unique:fees_head',
//        ]);
        $feesHead = new FeesHead();
        $feesHead->fees_head = $request->head_name;
        $feesHead->fees_gl_id = 52;
        $feesHead->campus_id = $this->academicHelper->getCampus();
        $feesHead->institute_id = $this->academicHelper->getInstitute();
        $feesHead->created_by = Auth::user()->id;
        $feesHeadStore=$feesHead->save();
        if ($feesHeadStore) {
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
        $feesHead = FeesHead::where('id',$id)->first();
        return view('cadetfees::feesHead.edit',compact('feesHead'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $feesHead=FeesHead::findOrFail($id);
        $feesHeadUpdate = $feesHead->update([
            'fees_head' => $request->head_name
        ]);
        if ($feesHeadUpdate) {
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
