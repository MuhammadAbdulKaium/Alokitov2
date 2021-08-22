<?php

namespace Modules\Academics\Http\Controllers;

use Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Academics\Entities\AdmissionYear;
use Session;

class AdmissionYearController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $pageTitle = "Admission Year Information";
        $insertOrEdit = 'insert'; //To identify insert
        $data=$this->getAll();
        return view('academics::academicsadmissionyear.index', compact('data','pageTitle','insertOrEdit') );


    }

    public function getAll()
    {
        $data = new AdmissionYear();
        return $data->where('is_deleted','0')->orderByRaw('LENGTH(admission_year) asc')->orderBy('admission_year', 'asc')->get();
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function store(Request $request)
    {

        // validating all requested input data
        $validator = Validator::make($request->all(), [
                'year_name' => 'required|max:100',
        ]);

        if($validator->passes()){

            $insertOrEdit='insert';

            $data = new AdmissionYear();
            // store requested profile name
            $data->year_name = $request->input('year_name');

            $data->status = $request->input('status');
            // save new profile


            try
            {
                $saved = $data->save();
                if($saved)
                {
                    Session::flash('message', 'Success!Data has been saved successfully.');
                }
                else
                {
                    Session::flash('message', 'Failed!Data has not been saved successfully.');
                }
            }
            catch (\Exception $e)
            {

                return $e->getMessage();
            }
            $data=$this->getAll();
            return view('academics::academicsadmissionyear.index',compact('insertOrEdit','editdata','data'));
        }else{
            // Session::flash('warning', 'unable to crate student profile');
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
        $pageTitle = 'Admission Year Informations';

//        $data = new academicsadmissionyear();

        $data = new AdmissionYear();
        $editdata = $data->where('id', $id)->get();


        $insertOrEdit = 'edit';

        return view('academics::academicsadmissionyear.view',compact('insertOrEdit','$editdata','pageTitle'));

    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $data = new AdmissionYear();
        $editdata = $data->where('id', $id)->get();
        $data=$this->getAll();
        $insertOrEdit = 'edit';
        return view('academics::academicsadmissionyear.index',compact('insertOrEdit','editdata','data'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $subject = AdmissionYear::find($id);
        // store requested profile name
        $subject->year_name = $request->input('year_name');

        $subject->status = $request->input('status');

        try
        {
            $saved = $subject->update($request->all());
            if($saved)
            {
                Session::flash('message', 'Success!Data has been updated successfully.');
            }
            else
            {
                Session::flash('message', 'Failed!Data has not been updated successfully.');
            }
        }
        catch (\Exception $e)
        {

            return $e->getMessage();


        }
        $data=$this->getAll();
        $insertOrEdit='insert';

        return view('academics::academicsadmissionyear.index',compact('insertOrEdit','editdata','data'));
    }


    public function delete($id)
    {


        $table = new AdmissionYear();
        $data=$this->getAll();
        $insertOrEdit='insert';
        try
        {
            $saved = $table->where('id', $id)->update(['is_deleted' => 1]);
            if($saved)
            {
                Session::flash('message', 'Success!Data has been deleted successfully.');
            }
            else
            {
                Session::flash('message', 'Failed!Data has not been deleted successfully.');
            }
        }
        catch (\Exception $e)
        {

            return $e->getMessage();

        }
        return view('academics::academicsadmissionyear.index',compact('insertOrEdit','editdata','data'));
    }
}
