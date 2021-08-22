<?php

namespace Modules\Setting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Setting\Entities\Country;


use File;
use Illuminate\Support\Facades\DB;

use Redirect;
use Session;
use Validator;
use Carbon\Carbon;



class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    
    public function index()
    {
        $pageTitle = "Country Information";
        $insertOrEdit = 'insert'; //To identify insert
        $data=$this->getAll();
        return view('setting::country.index', compact('data','insertOrEdit','pageTitle'));
    }
    public function getAll()
    {
        $data = new Country();
        return $data->orderBy('id', 'desc')->get();
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100|unique:setting_country',
        ]);

        if($validator->passes()){

            $data = new Country();
            // store requested profile name
            $data->name = $request->name;

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
                    Session::flash('message', 'Success!Data has not been saved successfully.');
                }
            }
            catch (\Exception $e)
            {

                return $e->getMessage();


            }
            $insertOrEdit = 'insert'; //To identify insert
            $data=$this->getAll();
            $pageTitle='Country Name';
            return view('setting::country.index', compact('data','insertOrEdit','pageTitle'));
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
        $pageTitle = 'Country Informations';
//        $data = newCountry();

        $data = new Country();
        $data = $data->where('id', $id)->get();
        $insertOrEdit = 'edit';
        return view('setting::country.view',compact('insertOrEdit','editdata','data','pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        //   return view('academics::edit');
        //die;
        $data = new Country();
        $editdata = $data->where('id', $id)->get();
        $data=$this->getAll();
        $insertOrEdit = 'edit';

        return view('setting::country.index',compact('insertOrEdit','editdata','data'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100|unique:setting_country',
        ]);
        if($validator->passes()) {
            $Country = Country::find($id);
            try {
                $saved = $Country->update($request->all());
                if ($saved) {
                    Session::flash('message', 'Success!Data has been updated successfully.');
                } else {
                    Session::flash('message', 'Failed!Data has not been updated successfully.');
                }
            } catch (\Exception $e) {

                return $e->getMessage();

            }
            $data = $this->getAll();
            $insertOrEdit = 'insert';

            return view('setting::country.index', compact('insertOrEdit', 'editdata', 'data'));
        }
        else
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }
    public function delete(Request $request,$id)
    {
        $table = new Country();
        $data=$this->getAll();
        $insertOrEdit='insert';
        try
        {
            $saved = $table->where('id', $id)->update(['deleted_at' => Carbon::now()]);
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
        return view('setting::country.index',compact('insertOrEdit','editdata','data'));
    }
}
