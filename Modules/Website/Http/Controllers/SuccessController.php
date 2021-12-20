<?php

namespace Modules\Website\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Modules\Website\Entities\Success;

class SuccessController extends Controller
{
    //check if the result has ssc,jsc,psc,hsc
    public function  checkResult($success){
        $data['psc']=false;
        $data['jsc']=false;
        $data['ssc']=false;
        $data['hsc']=false;

        foreach ($success as $result)
        {
            ($result->psc_passing_rate!=null && $data['psc']==false) ? ($data['psc']=true) : ($data['psc']=false)  ;
            ($result->jsc_passing_rate!=null  && $data['jsc']==false) ? ($data['jsc']=true) : ($data['jsc']=false)  ;
            ($result->ssc_passing_rate!=null && $data['ssc']==false) ? ($data['ssc']=true) : ($data['ssc']=false)  ;
            ($result->hsc_passing_rate!=null && $data['hsc']==false) ? ($data['hsc']=true) : ($data['hsc']=false)  ;
        }
        return $data;

    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $campus = session()->get('campus');
        $institute = session()->get('institute');


        $user = Auth::user();
        $successes=Success::where('campus_id', '=', $campus)
            ->where('institute_id', '=', $institute)->get();
        $data=$this->checkResult($successes);


        return view('website::success.index',compact('successes','user','data'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {

        $user = Auth::user();
        return view('website::success.create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $campus = session()->get('campus');
        $institute = session()->get('institute');

        if ($request->psc_passing_rate == null && $request->jsc_passing_rate == null && $request->ssc_passing_rate == null && $request->hsc_passing_year == null) {
            Session::flash('warning', 'Please input  one result');
            return redirect()->back();
        } else {

            $success = new Success();
            $success->campus_id = $campus;
            $success->institute_id = $institute;
            $success->passing_year = $request->passing_year;
            $success->total_examine = $request->total_examine;
            $success->psc_passing_rate = $request->psc_passing_rate;
            $success->jsc_passing_rate = $request->jsc_passing_rate;
            $success->ssc_passing_rate = $request->ssc_passing_rate;
            $success->hsc_passing_rate = $request->hsc_passing_rate;
            $success->save();


            Session::flash('success', 'Successfully added ');
            return redirect()->back()->with("Successfully Inserted");

            }

    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('website::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $user = Auth::user();
        $success=Success::findOrFail($id);

        return view('website::success.create',compact('success','user'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $campus = session()->get('campus');
        $institute = session()->get('institute');

        if ($request->psc_passing_rate == null && $request->jsc_passing_rate == null && $request->ssc_passing_rate == null && $request->hsc_passing_year == null) {
            Session::flash('warning', 'Please input  one result');
            return redirect()->back();
        } else {

            $success = Success::findOrFail($id);
            $success->campus_id = $campus;
            $success->institute_id = $institute;
            $success->passing_year = $request->passing_year;
            $success->total_examine = $request->total_examine;
            $success->psc_passing_rate = $request->psc_passing_rate;
            $success->jsc_passing_rate = $request->jsc_passing_rate;
            $success->ssc_passing_rate = $request->ssc_passing_rate;
            $success->hsc_passing_rate = $request->hsc_passing_rate;
            $success->save();


            Session::flash('success', 'Successfully Updated ');
            return redirect()->back()->with("Successfully Inserted");

        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $success = Success::findOrFail($id);
        $success->delete();
        Session::flash('danger', 'Deleted Successfully ');
        return redirect()->back();
    }
}
