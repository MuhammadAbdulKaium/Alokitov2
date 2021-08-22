<?php

namespace Modules\Employee\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\Employee\Entities\EmployeeOtHour;
use Redirect;
use Session;
use Validator;
class EmpOtManageController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $empOtHour = EmployeeOtHour::where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->get();
        return view('employee::pages.otEntry.ot_enty',compact('empOtHour'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $company_id = session()->get('institute');
        $brunch_id = session()->get('campus');
        $empInfo = DB::select("SELECT 
                                employee_id, concat( title,' ',first_name,' ',middle_name,' ',last_name,' ',alias) employee_name
                            FROM
                                pay_salary_structure a,
                                pay_salary_structure_detail b,
                                pay_emp_salary_assign c,
                                pay_salary_component d,
                                employee_informations e
                            WHERE
                                a.id = b.structure_id
                                    AND a.id = c.salary_structure_id
                                    AND d.id = b.component_id
                                    AND d.amount_type = 'OT'
                                    and e.id=c.employee_id
                                    and a.company_id = $company_id
                                    and a.brunch_id = $brunch_id
                            group by employee_id
                            order by employee_id");
        return view('employee::pages.otEntry.ot_enty_add',compact('empInfo'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'empList' => 'required',
            'approveDate' => 'required',
            'otHour' => 'required',
            'effectiveMonth' => 'required',
            'effectiveYear' => 'required',
        ]);
        if ($validator->passes()){
            $approveDate = $request->approveDate != '' ? date('Y-m-d',strtotime($request->input('approveDate'))) : '';

            $empOtHour = new EmployeeOtHour();
            $empOtHour->employee_id = $request->empList;
            $empOtHour->ot_hours = $request->otHour;
            $empOtHour->approve_date = $approveDate;
            $empOtHour->effective_month = $request->effectiveMonth;
            $empOtHour->effective_year = $request->effectiveYear;
            $empOtHour->brunch_id = session()->get('campus');
            $empOtHour->company_id = session()->get('institute');
            $empOtHour->save();
            // checking
            if ($empOtHour){
                Session::flash('success', 'Ot Hour added');
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
        //EmployeeOtHour
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('employee::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('employee::edit');
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
    public function destroy()
    {
    }
}
