<?php

namespace Modules\Payroll\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Modules\Payroll\Entities\SalaryGrade;
use Modules\Payroll\Entities\SalaryHead;

class SalaryGradeController extends Controller
{
    private $based = array(
        0=> "Gross",
        1=> "Basic"
    );
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $gradeList = SalaryGrade::get();
        $data = $this->based;
        return view('payroll::pages.salaryGrade.index', compact('gradeList', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data = $this->based;
        $gradeList = SalaryGrade::get();
        return view('payroll::pages.salaryGrade.create', compact('data', 'gradeList'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
//
        $validator = Validator::make($request->all(), [
            'grade_name'  => 'required|max:100',
            'institute_id'  => 'required',
            'based'  => 'required'
        ]);

        if($validator->passes())
        {
            DB::beginTransaction();
            try
            {
                $grade = new SalaryGrade();
                $grade->institute_id = session()->get('institute');
                $grade->grade_name = $request->grade_name;
                $grade->parent_grade_id = $request->parent_grade_id;
                $grade->amount = $request->amount;
                $grade->percentage = $request->percentage;
                $grade->based = $request->based;
                $grade->save();
            }
            catch (\Exception $e)
            {
                DB::rollback();
                Session::flash('error', $e->getMessage());
                return redirect()->back();
            }
            DB::commit();

            Session::flash('success', 'Salary Grade created');
            // return back
            return redirect()->back();
        }
        else
        {
            Session::flash('warning', 'Invalid Information');
            // return back
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
        return view('payroll::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $grade = SalaryGrade::find($id);
        $gradeList = SalaryGrade::get();
        $data = $this->based;
        return view('payroll::pages.salaryGrade.edit', compact('grade','gradeList', 'data'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'grade_name'  => 'required|max:100',
            'institute_id'  => 'required',
            'based'  => 'required'
        ]);

        $grade = SalaryGrade::find($request->id);

        if($validator->passes())
        {
            DB::beginTransaction();
            try
            {
                $grade->institute_id = session()->get('institute');
                $grade->grade_name = $request->grade_name;
                $grade->parent_grade_id = $request->parent_grade_id;
                $grade->amount = $request->amount;
                $grade->percentage = $request->percentage;
                $grade->based = $request->based;
                $grade->save();
            }
            catch (\Exception $e)
            {
                DB::rollback();
                Session::flash('error', $e->getMessage());
                return redirect()->back();
            }
            DB::commit();
            Session::flash('success', 'Salary Grade Updated');
            // return back
            return redirect()->back();
        }
        else
        {
            Session::flash('warning', 'Invalid Information');
            // return back
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Request $request)
    {
        $category = SalaryGrade::find($request->id);
        $category->delete();
        Session::flash('success', 'Salary Grade delete');
        // return back
        return redirect()->back();
    }
}
