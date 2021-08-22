<?php

namespace Modules\Setting\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Setting\Entities\City;
use Modules\Setting\Entities\Country;
use Modules\Setting\Entities\State;
use Redirect;
use Session;
use Validator;

class CityController extends Controller
{

    public function index()
    {
        $pageTitle    = "City Information";
        $insertOrEdit = 'insert'; //To identify insertt
        $cities       = $this->getAll();
        $states       = State::all();
        $countries    = Country::all();
        return view('setting::city.index', compact('cities', 'states', 'countries', 'insertOrEdit', 'pageTitle'));
    }
    public function getAll()
    {

        return City::all();
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'       => 'required|max:100|unique:setting_city',
            'country_id' => 'required',
            'state_id'   => 'required',
        ]);

        if ($validator->passes()) {

            $city = new City();
            // store requested profile name
            $city->name       = $request->name;
            $city->country_id = $request->country_id;
            $city->state_id   = $request->state_id;

            // save new profile
            try
            {
                $saved = $city->save();
                if ($saved) {
                    Session::flash('message', 'Success!Data has been saved successfully.');
                } else {
                    Session::flash('message', 'Success!Data has not been saved successfully.');
                }
            } catch (\Exception $e) {

                return $e->getMessage();

            }
            $insertOrEdit = 'insert'; //To identify insert

            $pageTitle = 'City Name';

            $cities    = $this->getAll();
            $states    = State::all();
            $countries = Country::all();
            return view('setting::city.index', compact('cities', 'states', 'countries', 'insertOrEdit', 'pageTitle'));
        } else {
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
        $pageTitle = 'City Informations';
        $data         = new City();
        $editdata     = $data->where('id', $id)->get();
        $insertOrEdit = 'edit';
        return view('setting::city.view', compact('insertOrEdit', 'editdata', 'data', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        //   return view('academics::edit');
        //die;
        $data     = new City();
        $editdata = $data->where('id', $id)->get();

        $insertOrEdit = 'edit';
        $cities       = $this->getAll();
        $states       = State::all();
        $countries    = Country::all();

        return view('setting::city.index', compact('countries', 'cities', 'states', 'insertOrEdit', 'editdata', 'data'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'       => 'required|max:100|unique:setting_city',
            'country_id' => 'required',
            'state_id'   => 'required',
        ]);
        if ($validator->passes()) {
            $city             = City::find($id);
            $city->name       = $request->name;
            $city->country_id = $request->country_id;
            $city->state_id   = $request->state_id;
            try {
                $saved = $city->update();
                if ($saved) {
                    Session::flash('message', 'Success!Data has been updated successfully.');
                } else {
                    Session::flash('message', 'Failed!Data has not been updated successfully.');
                }
            } catch (\Exception $e) {

                return $e->getMessage();

            }
            $insertOrEdit = 'insert';

            $cities    = $this->getAll();
            $states    = State::all();
            $countries = Country::all();
            return view('setting::city.index', compact('cities', 'states', 'countries', 'insertOrEdit', 'pageTitle'));
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }
    public function delete(Request $request, $id)
    {
        $table        = new City();
        $data         = $this->getAll();
        $insertOrEdit = 'insert';
        try
        {
            $saved = $table->where('id', $id)->update(['deleted_at' => Carbon::now()]);
            if ($saved) {
                Session::flash('message', 'Success!Data has been deleted successfully.');
            } else {
                Session::flash('message', 'Failed!Data has not been deleted successfully.');
            }
        } catch (\Exception $e) {

            return $e->getMessage();

        }
        $cities    = $this->getAll();
        $states    = State::all();
        $countries = Country::all();
        return view('setting::city.index', compact('cities', 'states', 'countries', 'insertOrEdit', 'pageTitle'));
    }

    ///////// Student Module Ajax Request  function /////////

    // find state list using state id for student module address
    public function findCityList(Request $request)
    {
        //$request->id here is the id of our chosen option id
        $data = City::where('state_id', $request->id)->get();
        //then sent this data to ajax success
        return response()->json($data);
    }

}
