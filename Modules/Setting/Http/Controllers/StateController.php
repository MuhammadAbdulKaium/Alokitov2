<?php

namespace Modules\Setting\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Setting\Entities\Country;
use Modules\Setting\Entities\State;
use Redirect;
use Session;
use Validator;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $pageTitle    = "State Information";
        $insertOrEdit = 'insert'; //To identify insert
        $states       = State::all();
        $countries    = Country::all();
        return view('setting::state.index', compact('states', 'countries', 'insertOrEdit', 'pageTitle'));
    }
    public function add_batch_view()
    {

        $country = new Country();
        $country = $country->get();

        $academicYear = new AcademicsYear();
        $academicYear = $academicYear->get();

        return view('setting::batch.add', compact('academicLevel', 'academicYear', 'country'));
    }
    public function getAll()
    {
        return $states = State::all();
    }

    /**
     * Show the form for creating a new resource
     * @return Response
     */
    public function create()
    {
        return view('setting::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $state = new State();
        // store requested profile name

//        $batch = Batch::find($id);

        // store requested profile name
        $validator = Validator::make($request->all(), [

            'name'       => 'required|max:100|unique:setting_state',
            'country_id' => 'required',

        ]);

        if ($validator->passes()) {
            $state->country_id = $request->input('country_id');
            $state->name       = $request->input('name');

            try {
                if ($state->save()) {
                    Session::flash('message', 'Success!Data has been saved successfully.');
                } else {
                    Session::flash('message', 'Failed!Data has not been updated successfully.');

                }
            } catch (Exception $e) {

            }
            $pageTitle    = "State/Province Information";
            $insertOrEdit = 'insert'; //To identify insert
            $states       = State::all();

            $countries = Country::all();
            return view('setting::state.index', compact('states', 'countries', 'insertOrEdit', 'pageTitle'));
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }

    }
    public function show($id)
    {
        $batch        = new Batch();
        $pageTitle    = 'Batch Information View';
        $batch        = $batch->where('id', $id)->get();
        $insertOrEdit = 'edit';
        return view('academics::batch.view', compact('insertOrEdit', 'editdata', 'batch', 'pageTitle'));
    }
    public function edit($id)
    {

        $countries = Country::all();

        $states = State::all();

        $state     = new State();
        $pageTitle = 'State Information View';
        $editdata  = $state->where('id', $id)->get();

        $insertOrEdit = 'edit';
        return view('setting::state.index', compact('insertOrEdit', 'editdata', 'countries', 'states', 'pageTitle'));

    }
    public function update(Request $request, $id)
    {
        $state = State::find($id);

        $validator = Validator::make($request->all(), [

            'name'       => 'required|max:100|unique:setting_state',
            'country_id' => 'required',

        ]);
        // store requested profile name
        if ($validator->passes()) {
            $state->country_id = $request->input('country_id');
            $state->name       = $request->input('name');

            try
            {
                $saved = $state->update();
                if ($saved) {
                    Session::flash('message', 'Success!Data has been updated successfully.');
                } else {
                    Session::flash('message', 'Failed!Data has not been updated successfully.');
                }
            } catch (\Exception $e) {

                return $e->getMessage();
            }
            $pageTitle    = "State Information";
            $insertOrEdit = 'insert'; //To identify insert
            $states       = State::all();
            $countries    = Country::all();

            return view('setting::state.index', compact('insertOrEdit', 'editdata', 'countries', 'states', 'pageTitle'));
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }
    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function delete($id)
    {
        $state = new State();

        try
        {
            if ($state->where('id', $id)->update(['deleted_at' => Carbon::now()])) {
                Session::flash('message', 'Success!Data has been deleted successfully.');
            } else {
                Session::flash('message', 'Failed!Data has not been deleted successfully.');
            }

        } catch (Exception $e) {

        }
        $pageTitle    = "State Information";
        $insertOrEdit = 'insert'; //To identify insert
        $states       = State::all();
        $countries    = Country::all();
        return view('setting::state.index', compact('insertOrEdit', 'editdata', 'countries', 'states', 'pageTitle'));
    }

    public function batch_status_change($id)
    {
        $table = new Batch();
        try
        {
            if ($table->where('id', $id)->update(['deleted_at' => Carbon::now(), 'is_active' => 0])) {
                Session::flash('message', 'Success!Status has been changed successfully.');
            } else {
                Session::flash('message', 'Failed!Status has not been changed successfully.');
            }

        } catch (Exception $e) {

        }
        $pageTitle    = "Batch Information";
        $insertOrEdit = 'insert'; //To identify insert
        $batches      = $this->getAll();
        return view('setting::batch.index', compact('batches', 'insertOrEdit', 'pageTitle'));
    }

    
    ///////// Student Module Ajax Request  function /////////
    public function findBatch(Request $request)
    {
        //$request->id here is the id of our chosen option id
        $data = Batch::where('academic_level', $request->id)->get();
        //then sent this data to ajax success
        return response()->json($data);
    }

    // find state list using country id for student module address
    public function findStateList(Request $request)
    {
        //$request->id here is the id of our chosen option id
        $data = State::where('country_id', $request->id)->get();
        //then sent this data to ajax success
        return response()->json($data);
    }
}
