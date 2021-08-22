<?php

namespace Modules\Payroll\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Modules\Payroll\Entities\SalaryHead;

class SalaryHeadController extends Controller
{
//    public function __construct()
//    {
//        if(isset(session()->get('institute')))
//        {
//            Session::flash('warning', 'Please join an institute');
//            // return back
//            return redirect()->back();
//        }
//    }
    private $type = array(
        0=> "Addition",
        1=> "Deduction"
    );

    private $calculation = array(
        0=> "Basic",
        1=> "Gross"
    );

    private $placement = array(
        1=> "Gross",
        2=> "Other",
        3=> "Extra",
        4=> "N/A"
    );

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $headList = SalaryHead::get();
        $data = [
            $this->type,
            $this->calculation,
            $this->placement
        ];
        return view('payroll::pages.salaryHead.index', compact('headList', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $data = [
            $this->type,
            $this->calculation,
            $this->placement
        ];

        return view('payroll::pages.salaryHead.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'head_name'   => 'required|max:100',
            'type'        => 'required',
            'calculation' => 'required',
            'placement'   => 'required',
        ]);

//        dd($request->all());
        if($validator->passes())
        {
            DB::beginTransaction();
            try
            {
                $head = new SalaryHead();
                $head->instute_id = session()->get('institute');
                $head->custom_name = $request->custom_name;
                $head->head_name = $request->head_name;
                $head->type = $request->type;
                $head->calculation = $request->calculation;
                $head->placement = $request->placement;
                $head->save();
            }
            catch (\Exception $e)
            {
                DB::rollback();
                Session::flash('error', $e->getMessage());
                return redirect()->back();
            }
            DB::commit();

            Session::flash('success', 'Salary Head created');
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
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $head = SalaryHead::find($id);
        $data = [
            $this->type,
            $this->calculation,
            $this->placement
        ];
        return view('payroll::pages.salaryHead.edit', compact('head', 'data'));
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
            'head_name'   => 'required|max:100',
            'type'        => 'required',
            'calculation' => 'required',
            'placement'   => 'required',
        ]);
        $head = SalaryHead::find($request->id);
//        dd($head);

        if($validator->passes())
        {
            DB::beginTransaction();
            try
            {
                $head->instute_id = $request->instute_id;
                $head->custom_name = $request->custom_name;
                $head->head_name = $request->head_name;
                $head->type = $request->type;
                $head->calculation = $request->calculation;
                $head->placement = $request->placement;
                $head->save();
            }
            catch (\Exception $e)
            {
                DB::rollback();
                Session::flash('error', $e->getMessage());
                return redirect()->back();
            }
            DB::commit();
            Session::flash('success', 'Salary Head created');
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
        $category = SalaryHead::findOrFail($request->id);
        $category->delete();
        Session::flash('success', 'Salary Head delete');
        // return back
        return redirect()->back();
    }
}
