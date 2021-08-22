<?php

namespace Modules\Employee\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Employee\Entities\Shift;
use Redirect;
use Session;
use Validator;

class EmpShiftManagementController extends Controller
{
    private $companyId = 1;

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $allShift = Shift::orderBy('shiftName', 'ASC')->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->get();
        return view('employee::pages.shift.shift', compact('allShift'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('employee::pages.shift.shiftCreateModal');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'shiftName' => 'required',
            'shiftStartTime' => 'required',
            'shiftEndTime' => 'required',
            'lastOutTime' => 'required',
        ]);

        if ($validator->passes()) {
            $shift = new Shift();
            $shift->shiftName = $request->shiftName;
            $shift->firstHoliday = $request->firstHoliday;
            $shift->secondHoliday = $request->secondHoliday;
            $shift->shiftStartTime = $request->shiftStartTime;
            $shift->shiftEndTime = $request->shiftEndTime;
            $shift->lateInTime = $request->lateInTime;
            $shift->absentInTime = $request->absentInTime;
            $shift->lunchStartTime = $request->lunchStartTime;
            $shift->lunchEndTime = $request->lunchEndTime;
            $shift->overTimeStart = $request->overTimeStart;
            $shift->overTimeEnd = $request->overTimeEnd;
            $shift->extraOverTimeStart = $request->extraOverTimeStart;
            $shift->extraOverTimeEnd = $request->extraOverTimeEnd;
            $shift->earlyOutTime = $request->earlyOutTime;
            $shift->lastOutTime = $request->lastOutTime;
            $shift->lateDayAllow = $request->lateDayAllow;
            $shift->outTimeGrace = $request->outTimeGrace;
            $shift->brunch_id = session()->get('campus');
            $shift->company_id = session()->get('institute');
            $shift->save();

            // checking
            if ($shift) {
                Session::flash('success', 'Shift added');
                // return redirect
                return redirect()->back();
            } else {
                Session::flash('warning', 'Unable to perform the actions');
                // return redirect
                return redirect()->back();
            }
        } else {
            Session::flash('warning', 'Invalid Information. please try with correct Information');
            // receiving page action
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id)
    {
        $shift = Shift::FindOrFail($id);
        return view('employee::pages.shift.shift-view', compact('shift'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $shift = Shift::FindOrFail($id);
        return view('employee::pages.shift.shiftEdit-view', compact('shift'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'shiftName' => 'required',
            'shiftStartTime' => 'required',
            'shiftEndTime' => 'required',
            'lastOutTime' => 'required',
        ]);

        if ($validator->passes()) {
            $shift = Shift::where('id', $request->id)
                ->update([
                    "shiftName" => $request->shiftName,
                    "firstHoliday" => $request->firstHoliday,
                    "secondHoliday" => $request->secondHoliday,
                    "shiftStartTime" => $request->shiftStartTime,
                    "shiftEndTime" => $request->shiftEndTime,
                    "lateInTime" => $request->lateInTime,
                    "absentInTime" => $request->absentInTime,
                    "lunchStartTime" => $request->lunchStartTime,
                    "lunchEndTime" => $request->lunchEndTime,
                    "overTimeStart" => $request->overTimeStart,
                    "overTimeEnd" => $request->overTimeEnd,
                    "extraOverTimeStart" => $request->extraOverTimeStart,
                    "extraOverTimeEnd" => $request->extraOverTimeEnd,
                    "earlyOutTime" => $request->earlyOutTime,
                    "lastOutTime" => $request->lastOutTime,
                    "lateDayAllow" => $request->lateDayAllow,
                    "outTimeGrace" => $request->outTimeGrace,
                ]);
            // checking
            if ($shift) {
                Session::flash('success', 'Shift Updated');
                // return redirect
                return redirect()->back();
            } else {
                Session::flash('warning', 'Uabale to perform the actions');
                // return redirect
                return redirect()->back();
            }
        } else {
            Session::flash('warning', 'invalid Information. please try with correct Information');
            // receiving page action
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        $shift = Shift::FindOrFail($id);
        // checking
        if ($shift) {
            $shiftDeleted = $shift->delete();
            // checking
            if ($shiftDeleted) {
                Session::flash('success', 'Shift Deleted');
                // return redirect
                return redirect()->back();
            } else {
                Session::flash('warning', 'Uabale to delete Shift');
                // return redirect
                return redirect()->back();
            }
        } else {
            Session::flash('warning', 'Uabale to perform the actions');
            // return redirect
            return redirect()->back();
        }
    }
}
