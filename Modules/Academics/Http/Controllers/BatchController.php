<?php

namespace Modules\Academics\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Mockery\CountValidator\Exception;
use Modules\Academics\Entities\AcademicsLevel;
use Modules\Academics\Entities\AcademicsYear;
//use Illuminate\Support\Facades\Session;
use Modules\Academics\Entities\Batch;
use Modules\Academics\Entities\Division;
use Modules\Academics\Entities\Section;
use App\Http\Controllers\Helpers\AcademicHelper;
use Session;
use Validator;

class BatchController extends Controller
{

    private $batch;
    private $section;
    private $division;
    private $academicsYear;
    private $academicsLevel;
    private $academicHelper;
    // constructor
    public function __construct(AcademicHelper $academicHelper, AcademicsYear $academicsYear, AcademicsLevel $academicsLevel, Batch $batch, Section $section, Division $division)
    {
        $this->batch = $batch;
        $this->section = $section;
        $this->division = $division;
        $this->academicsYear  = $academicsYear;
        $this->academicsLevel  = $academicsLevel;
        $this->academicHelper  = $academicHelper;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $pageTitle    = "Batch Information";
        $insertOrEdit = 'insert'; //To identify insert
        $batches      = $this->getAll();

        return view('academics::batch.index', compact('batches', 'insertOrEdit', 'pageTitle'));
    }
    public function add_batch_view()
    {

        $campus = $this->academicHelper->getCampus();
        $institute = $this->academicHelper->getInstitute();
        $year = $this->academicHelper->getAcademicYear();
        // division list
        $divisions = $this->division->where(['campus'=>$campus, 'institute'=>$institute])->get();
        // academic details
        $academicLevels = $this->academicHelper->getAllAcademicLevel();
        // $academicYear = $this->academicHelper->getAllAcademicYears();

        // return view with variables
        return view('academics::batch.add', compact('academicLevels', 'divisions'));
    }


    public function getAll()
    {
        return $batches = $this->batch->where([
            'campus'=> $this->academicHelper->getCampus(),
            'institute'=>$this->academicHelper->getInstitute()
        ])->with('divisions')->get();
    }

    /**
     * Show the form for creating a new resource
     * @return Response
     */
    public function create()
    {
        return view('academics::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $batch = new Batch();
        // store requested profile name

//        $batch = Batch::find($id);

        // store requested profile name
        $validator = Validator::make($request->all(), [
            'academics_level_id' => 'required|max:100',
            'batch_name'         => 'required',
            // 'start_date'         => 'required',
            // 'end_date'           => 'required',
        ]);

        if ($validator->passes()) {

            // $batch->academics_year_id  = $request->input('academics_year_id');
            $batch->academics_level_id = $request->input('academics_level_id');
            //  $batch->section_name = $request->input('section_name');
            $batch->batch_alias = $request->input('batch_alias');
            // $batch->start_date  = date('Y-m-d', strtotime($request->input('start_date')));
            // $batch->end_date    = date('Y-m-d', strtotime($request->input('end_date')));
            $batch->batch_name  = $request->input('batch_name');
            // $batch->division_id = $request->input('division_id');
            $batch->campus            =  $this->academicHelper->getCampus();
            $batch->institute            = $this->academicHelper->getInstitute();

            if ($batch->save()) {
                if (isset($request->divisions)) {
                    foreach ($request->divisions as $division_id) {
                        $batch->divisions()->attach($division_id);
                    }
                }

                $section                    = new Section();
                $section->batch_id          = $batch->id;
                $section->section_name      = $request->input('section_name');
                //$section->academics_year_id = $request->input('academics_year_id');
                $section->intake            = $request->input('intake');
                $section->campus            =  $this->academicHelper->getCampus();
                $section->institute            = $this->academicHelper->getInstitute();
                $section->save();

                if (isset($request->section_divisions)) {
                    foreach ($request->section_divisions as $division_id) {
                        $section->divisions()->attach($division_id);
                    }
                }

                Session::flash('message', 'Success!Data has been saved successfully.');
            } else {
                Session::flash('message', 'Failed!Data has not been saved successfully.');

            }
            return redirect()->back();
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }

    }
    public function show($id)
    {
        $batch     = new Batch();
        $pageTitle = 'Batch Information View';
        $batch     = $batch->where('id', $id)->get();
//        return $batch;
        $insertOrEdit = 'edit';
        return view('academics::batch.view', compact('insertOrEdit', 'batch', 'pageTitle'));
    }
    public function show_edit($id)
    {
        $batch     = new Batch();
        $pageTitle = 'Batch Information Edit';
        $batch     = $batch->where('id', $id)->get();
        $campus = $this->academicHelper->getCampus();
        $institute = $this->academicHelper->getInstitute();
        // academic level list
        $academicLevel = $this->academicsLevel->where(['campus_id'=> $campus, 'institute_id'=> $institute, 'is_active'=>1])->get();
        // division
        $divisions = $this->division->where(['campus'=>$campus, 'institute'=>$institute])->get();
        $insertEdit = 'edit';
        return view('academics::batch.edit', compact( 'academicLevel', 'batch', 'pageTitle', 'divisions'));

    }
    public function update(Request $request, $id)
    {

        // division
        $division = $request->input('division', 'off');
        // division id
        $divisionId = $request->input('division_id');

        $batch = Batch::find($id);
        // store requested profile name
        // $batch->academics_year_id  = $request->input('academics_year_id');
        $batch->academics_level_id = $request->input('academics_level_id');
        $batch->batch_alias        = $request->input('batch_alias');
        // $batch->start_date         = date('Y-m-d', strtotime($request->input('start_date')));
        // $batch->end_date           = date('Y-m-d', strtotime($request->input('end_date')));
        $batch->batch_name         = $request->input('batch_name');
        if($division=='on'){
            $batch->division_id = $divisionId ;

            $batch->divisions()->sync($request->divisions);

                if (isset($request->divisions)) {
                    foreach ($batch->section() as $section) {
                    $section->divisions()->sync($request->divisions);
                }
            }
        }else{
            $batch->division_id = NULL;

            $batch->divisions()->detach();
        }

        $saved = $batch->save();
        if ($saved) {
            Session::flash('message', 'Success!Data has been updated successfully.');
            // return
            return redirect()->back();
        } else {
            Session::flash('message', 'Failed!Data has not been updated successfully.');
            // return
            return redirect()->back();
        }
    }
    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function delete($id)
    {
        $batchDeleted = $this->batch->find($id)->delete();
        // checking
        if ($batchDeleted) {
            Session::flash('message', 'Batch deleted successfully.');
        } else {
            Session::flash('message', 'Unable to Delete batch');
        }
        return redirect()->back();

    }

    public function batch_status_change($id)
    {
        $table = new Batch();
        $current=$table->where('id', $id)->first();
//        return $current;
        $currentStatus=$current->status;
        if ($currentStatus==0){
            $status= $table->where('id', $id)->update(['status' => 1]);
        }
        else{
            $status= $table->where('id', $id)->update(['status' => 0]);
        }
        try
        {
            if ($status) {

                Session::flash('message', 'Success!Status has been changed successfully.');
            } else {

                Session::flash('message', 'Failed!Status has not been changed successfully.');
            }

        } catch (Exception $e) {

        }
        $pageTitle    = "Batch Information";
        $insertOrEdit = 'insert'; //To identify insert
        $batches      = $this->getAll();
        return view('academics::batch.index', compact('batches', 'insertOrEdit', 'pageTitle'));
    }

    ///////// Student Module Ajax Request  function /////////
    public function findBatch(Request $request)
    {

        $academicLevelId = $request->input('id');
        $campusId = $request->input('campus', $this->academicHelper->getCampus());
        $instituteId = $request->input('institute', $this->academicHelper->getInstitute());

        // response array
        $data = array();
        // all batch
        $allBatch = $this->batch->where(['academics_level_id'=>$academicLevelId, 'campus'=> $campusId, 'institute'=>$instituteId])->orderBy('batch_name','ASC')->get();
        // looping for adding division into the batch name
        foreach ($allBatch as $batch) {
            if (sizeof($batch->divisions) != 0) {
                $divisions = '';
                foreach ($batch->divisions as $key => $division) {
                    if ($key != sizeof($batch->divisions)-1){
                        $divisions .= $division->name.", ";
                    }else{
                        $divisions .= $division->name;
                    }
                }
                $data[] = array('id' => $batch->id, 'batch_name' => $batch->batch_name . " - " . $divisions, 'is_division' => 1);
            } else {
                $data[] = array('id' => $batch->id, 'batch_name' => $batch->batch_name, 'is_division' => 0);
            }
        }

        //then sent this data to ajax success
        return $data;
    }

}
