<?php

namespace Modules\Employee\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Employee\Entities\EmployeeStatus;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class EmployeeStatusController extends Controller
{
    private $academicHelper;

    public function __construct(AcademicHelper $academicHelper)
    {
        $this->academicHelper = $academicHelper;
    }


    public function index()
    {
        $statuses = EmployeeStatus::latest()->get();

        return view('employee::pages.teacher-staff.index', compact('statuses'));
    }



    public function create()
    {
        return view('employee::pages.teacher-staff.modal.add');
    }



    public function store(Request $request)
    {
        $sameNameStatus = EmployeeStatus::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'status' => $request->name
        ])->get();

        if (sizeOf($sameNameStatus) > 0) {
            Session::flash('errorMessage', 'Sorry! There is already a status in this name.');
            return redirect()->back();
        }


        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'category' => 'required'
        ]);

        if ($validator->passes()) {
            DB::beginTransaction();
            try {
                $insertEmployeeStatus = EmployeeStatus::insert([
                    'status' => $request->name,
                    'category' => $request->category,
                    'created_at' => Carbon::now(),
                    'created_by' => Auth::id(),
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                ]);

                if ($insertEmployeeStatus) {
                    DB::commit();
                    Session::flash('message', 'Success! Employee status has been created successfully.');
                    return redirect()->back();
                } else {
                    Session::flash('errorMessage', 'Error creating employee status.');
                    return redirect()->back();
                }
            } catch (\Exception $e) {
                DB::rollback();

                Session::flash('errorMessage', 'Error creating employee status.');
                return redirect()->back();
            }
        } else {
            Session::flash('errorMessage', 'Please insert valid data.');
            return redirect()->back();
        }
    }



    public function show($id)
    {
        return view('employee::show');
    }



    public function edit($id)
    {
        $status = EmployeeStatus::findOrfail($id);

        return view('employee::pages.teacher-staff.modal.edit', compact('status'));
    }



    public function update(Request $request, $id)
    {
        $status = EmployeeStatus::findOrfail($id);

        $sameNameStatus = EmployeeStatus::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'status' => $request->name
        ])->first();

        if ($sameNameStatus) {
            if ($sameNameStatus->id != $status->id) {
                Session::flash('errorMessage', 'Sorry! There is already a status in this name.');
                return redirect()->back();
            }
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'category' => 'required'
        ]);

        if ($validator->passes()) {
            DB::beginTransaction();
            try {
                $updateStatus = $status->update([
                    'status' => $request->name,
                    'category' => $request->category,
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id(),
                ]);

                if ($updateStatus) {
                    DB::commit();
                    Session::flash('message', 'Success! Employee status has been updated successfully.');
                    return redirect()->back();
                } else {
                    Session::flash('errorMessage', 'Error updating employee status.');
                    return redirect()->back();
                }
            } catch (\Exception $e) {
                DB::rollback();

                Session::flash('errorMessage', 'Error updating employee status.');
                return redirect()->back();
            }
        } else {
            Session::flash('message', 'Sorry! Please insert valid data.');
            return redirect()->back();
        }
    }



    public function destroy($id)
    {
        $employeeStatus = EmployeeStatus::findOrFail($id);

        if ($employeeStatus) {
            $employeeStatus->delete();
            Session::flash('message', 'Success! Employee status has been deleted successfully.');
            return redirect()->back();
        } else {
            Session::flash('message', 'Sorry! Error updating Employee status.');
            return redirect()->back();
        }
    }
}
