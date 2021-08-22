<?php

namespace Modules\Academics\Http\Controllers;

use Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Academics\Entities\AcademicsAdmissionYear;
use App\Http\Controllers\Helpers\AcademicHelper;
use Session;

class AcademicsAdmissionYearController extends Controller
{

    private $admissionYear;
    private $academicHelper;
    // constructor
    public function __construct(AcademicHelper $academicHelper, AcademicsAdmissionYear $admissionYear)
    {
        $this->admissionYear  = $admissionYear;
        $this->academicHelper  = $academicHelper;
    }

    public function index()
    {
        $pageTitle = "Admission Year Information";
        $insertOrEdit = 'insert'; //To identify insert
        $admissionYears=$this->getAll();
        return view('academics::academicsadmissionyear.index', compact('admissionYears','pageTitle','insertOrEdit') );


    }

    public function getAll()
    {
        return  $admissionYears = $this->admissionYear->where([
            'institute_id'=>$this->academicHelper->getInstitute(), 'campus_id'=>$this->academicHelper->getCampus()
        ])->get();
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

            $data = new AcademicsAdmissionYear();
            // store requested profile name
            $data->year_name = $request->input('year_name');
            $data->status = $request->input('status');
            $data->campus_id = $this->academicHelper->getCampus();
            $data->institute_id = $this->academicHelper->getInstitute();
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

             $admissionYears=$this->getAll();
            return view('academics::academicsadmissionyear.index',compact('insertOrEdit','admissionYears'));
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

        $admissionAcademicsYear = new AcademicsAdmissionYear();


        $academicsYearView = $admissionAcademicsYear->where('id', $id)->get();


        $insertOrEdit = 'edit';

        return view('academics::academicsadmissionyear.view',compact('insertOrEdit','academicsYearView','pageTitle'));

    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $data = new AcademicsAdmissionYear();
        $academicsYearEdit = $data->where('id', $id)->get();
        $admissionYears=$this->getAll();
        $insertOrEdit = 'edit';
        return view('academics::academicsadmissionyear.index',compact('insertOrEdit','editdata','admissionYears','academicsYearEdit'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $subject = AcademicsAdmissionYear::find($id);
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
        $admissionYears=$this->getAll();
        $insertOrEdit='insert';

        return view('academics::academicsadmissionyear.index',compact('insertOrEdit','editdata','admissionYears'));
    }


    public function delete($id)
    {


        $table = new AcademicsAdmissionYear();
        $admissionYears=$this->getAll();
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
        return view('academics::academicsadmissionyear.index',compact('insertOrEdit','editdata','admissionYears'));
    }
}
