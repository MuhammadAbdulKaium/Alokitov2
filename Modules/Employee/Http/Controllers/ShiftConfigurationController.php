<?php

namespace Modules\Employee\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Employee\Entities\ShiftConfiguration;
use Modules\Employee\Http\Requests\ShiftConfigurationRequest;
use Illuminate\Support\Facades\Session;


class ShiftConfigurationController extends Controller
{
    private $academicHelper;

    public function __construct(AcademicHelper $academicHelper)
    {
        $this->academicHelper = $academicHelper;
    }

    
    
    public function index($id = '')
    {
        $shiftConfiguration = '';
        if ($id) {
            $shiftConfiguration = ShiftConfiguration::findOrFail($id);
        }

        $shiftConfigurations = ShiftConfiguration::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();

        return view('employee::pages.shift-configuration.index', compact('shiftConfigurations', 'shiftConfiguration'));
    }

    
    
    public function create()
    {
        return view('employee::create');
    }

    
    
    public function store(ShiftConfigurationRequest $request)
    {
        $sameNameShift = ShiftConfiguration::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'name' => $request->name
        ])->get();

        if (sizeOf($sameNameShift) > 0) {
            Session::flash('errorMessage', 'Sorry! There is already a shift in this name.');
            return redirect()->back();
        }

        DB::beginTransaction();
        try {        
            $insertShift = ShiftConfiguration::insert([
                'name' => $request->name,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'next_day_exit' => $request->nextDayCheck,
                'minutes_before' => $request->lastMinute,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ]);
        
            if ($insertShift) {
                DB::commit();
                Session::flash('message', 'Success! Shift created successfully.');
                return redirect()->back();
            }else{
                Session::flash('errorMessage', 'Error creating new Shift.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error creating new Shift.');
            return redirect()->back();
        }
    }
    

    public function show($id)
    {
        return view('employee::show');
    }

    
    
    public function edit($id)
    {
        return view('employee::edit');
    }

    
    
    public function update(ShiftConfigurationRequest $request, $id)
    {
        $shiftConfiguration = ShiftConfiguration::findOrFail($id);

        $sameNameShift = ShiftConfiguration::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'name' => $request->name
        ])->first();

        if ($sameNameShift) {
            if ($sameNameShift->id != $shiftConfiguration->id) {
                Session::flash('errorMessage', 'Sorry! There is already a Shift in this name.');
                return redirect()->back();
            }
        }

        DB::beginTransaction();
        try {
        
            $updateShift = $shiftConfiguration->update([
                'name' => $request->name,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'next_day_exit' => $request->nextDayCheck,
                'minutes_before' => $request->lastMinute,
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ]);
        
            if ($updateShift) {
                DB::commit();
                Session::flash('message', 'Success! Shift updated successfully.');
                return redirect()->back();
            }else{
                Session::flash('errorMessage', 'Error updating Shift.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error updating Shift.');
            return redirect()->back();
        }
    }

    
    
    public function destroy($id)
    {
        $shiftConfiguration = ShiftConfiguration::findOrFail($id);

        if ($shiftConfiguration) {
            $shiftConfiguration->delete();

            Session::flash('message', 'Success! Shift deleted successfully.');
            return redirect()->back();
        }else{
            Session::flash('message', 'Error updating Shift.');
            return redirect()->back();
        }        
    }
}
