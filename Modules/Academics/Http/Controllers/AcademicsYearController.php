<?php

namespace Modules\Academics\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Validator;
use Carbon\Carbon;
use Modules\Academics\Entities\AcademicsYear;
use Session;
use App\Http\Controllers\Helpers\AcademicHelper;

class AcademicsYearController extends Controller
{

    private $academicsYear;
    private $academicHelper;
    // constructor
    public function __construct(AcademicHelper $academicHelper, AcademicsYear $academicsYear)
    {
        $this->academicsYear  = $academicsYear;
        $this->academicHelper  = $academicHelper;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $pageTitle = "Academic Year Information";
        $insertOrEdit = 'insert'; //To identify insert
        $data = $this->getAll();
        return view('academics::academicsyear.index', compact('data', 'pageTitle', 'insertOrEdit'));
    }

    public function getAll()
    {
        return $this->academicsYear->where(['institute_id' => $this->academicHelper->getInstitute(), 'campus_id' => $this->academicHelper->getCampus()])->get();
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function store(Request $request)
    {
        // validator
        $validator = Validator::make($request->all(), ['year_name' => 'required|max:100']);
        // validator checking
        if ($validator->passes()) {
            // institute details
            $campus = $this->academicHelper->getCampus();
            $institute = $this->academicHelper->getInstitute();
            // find active academic list
            $activeAcademicYearList = $this->getAcademicYearStatus($campus, $institute)->count();
            // new academic profile
            $yearProfile = new AcademicsYear();
            // store academic year details
            $yearProfile->year_name = $request->input('year_name');
            $yearProfile->start_date = date('Y-m-d', strtotime($request->input('start_date')));
            $yearProfile->end_date = date('Y-m-d', strtotime($request->input('end_date')));
            $yearProfile->status = $activeAcademicYearList > 0 ? 0 : $request->input('status', 0);
            $yearProfile->campus_id = $campus;
            $yearProfile->institute_id = $institute;
            //save and checking
            if ($yearProfile->save()) {
                // success msg
                Session::flash('message', 'Success! Data has been saved successfully.');
            } else {
                // failed msg
                Session::flash('message', 'Failed! Data has not been saved successfully.');
            }
            // return back
            return redirect()->back();
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }


    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id)
    {
        $pageTitle = 'Academic Year Informations';
        $academicYear = new AcademicsYear();
        $academicYear = $academicYear->where('id', $id)->get();
        $insertOrEdit = 'edit';
        return view('academics::academicsyear.view', compact('insertOrEdit', 'academicYear', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {

        $data = new AcademicsYear();
        $editdata = $data->where('id', $id)->get();
        $data = $this->getAll();
        $insertOrEdit = 'edit';

        return view('academics::academicsyear.index', compact('insertOrEdit', 'editdata', 'data'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), ['year_name' => 'required']);
        // checking validator
        if ($validator->passes()) {
            // request details
            $status = $request->input('status');
            // institute details
            $campus = $this->academicHelper->getCampus();
            $institute = $this->academicHelper->getInstitute();
            // find active academic list
            $activeAcademicYearList = $this->getAcademicYearStatus($campus, $institute);
            // request details
            $yearProfile = $this->academicsYear->find($id);
            // store requested profile name
            $yearProfile->year_name = $request->input('year_name');
            $yearProfile->start_date = date('Y-m-d', strtotime($request->input('start_date')));
            $yearProfile->end_date = date('Y-m-d', strtotime($request->input('end_date')));
            // checking status
            if ($status == 1 and $activeAcademicYearList->count() > 0) {
                // $activeAcademicYearList looping
                foreach ($activeAcademicYearList as $year) {
                    // checking
                    if ($year->id == $id) continue;
                    $year->status = 0;
                    $year->save();
                }
            }
            // update status
            $yearProfile->status = $status;
            //save and checking
            if ($yearProfile->save()) {
                // success msg
                Session::flash('message', 'Success! Data has been saved successfully.');
            } else {
                // failed msg
                Session::flash('message', 'Failed! Data has not been saved successfully.');
            }
            // return back
            return redirect('/academics/academic-year');
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }


    public function delete($id)
    {
        // find year profile
        $yearProfile = $this->academicsYear->find($id);
        // checking year profile
        if ($yearProfile->status == 0) {

            // delete and checking
            if ($yearProfile->delete()) {
                // success msg
                Session::flash('message', 'Success!Data has been deleted successfully.');
            } else {
                // failed msg
                Session::flash('message', 'Failed!Data has not been deleted successfully.');
            }
        } else {
            // failed msg
            Session::flash('message', 'Unable to Delete Active Academic Year');
        }
        // return back
        return redirect()->back();
    }

    // find institute academic year status
    public function getAcademicYearStatus($campus, $institute)
    {
        // get all active year list
        return $this->academicsYear->where(['campus_id' => $campus, 'institute_id' => $institute, 'status' => 1])->get();
    }
}
