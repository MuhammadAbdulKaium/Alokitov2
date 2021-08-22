<?php

namespace Modules\Academics\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Mockery\CountValidator\Exception;
use Modules\Academics\Entities\Section;
use Modules\Academics\Entities\Batch;
use Modules\Academics\Entities\AcademicsYear;
use Modules\Academics\Entities\AcademicsLevel;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Academics\Entities\Division;


use Illuminate\Support\Facades\DB;
use Validator;
use Session;

class SectionController extends Controller
{

    private $batch;
    private  $section;
    private $academicHelper;
    private $academicsYear;
    private $academicsLevel;

    // constructor
    public function __construct(Section $section, Batch $batch, AcademicsYear $academicsYear, AcademicsLevel $academicsLevel, AcademicHelper $academicHelper, Division $division)
    {
        $this->batch = $batch;
        $this->section = $section;
        $this->academicHelper = $academicHelper;
        $this->academicsYear = $academicsYear;
        $this->academicsLevel = $academicsLevel;
        $this->division = $division;
    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $pageTitle = "Section Information";
        $insertOrEdit = 'insert'; //To identify insert
        $sections=$this->getAll();

        return view('academics::section.index', compact('sections','insertOrEdit','pageTitle'));
    }

    public function getAll()
    {
        return $sectionList = $this->section->where([
            'campus'=>$this->academicHelper->getCampus(),
            'institute'=>$this->academicHelper->getInstitute(),
        ])->with('divisions')->get();

    }
    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function add_batch_view()
    {
        $campus = $this->academicHelper->getCampus();
        $institute = $this->academicHelper->getInstitute();

        // academic year list
        $academicLevels = $this->academicHelper->getAllAcademicLevel();
        $divisions = $this->division->where(['campus'=>$campus, 'institute'=>$institute])->get();

        return view('academics::section.add',compact('academicLevels', 'divisions'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $section=new Section();

        $validator = Validator::make($request->all(), [
//            'academics_year_id' => 'required|max:100',
            'batch_id' => 'required|max:100',
            'section_name'=>'required|max:100',
        ]);
        
        if($validator->passes()) {

//            $section->academics_year_id = $request->input('academics_year_id');
            $section->batch_id = $request->input('batch_id');
            $section->section_name = $request->input('section_name');
            $section->intake = $request->input('intake');
            $section->campus = $this->academicHelper->getCampus();
            $section->institute = $this->academicHelper->getInstitute();

            // checking
            if ($section->save()) {
                if (isset($request->section_divisions)) {
                    foreach ($request->section_divisions as $division) {
                        $section->divisions()->attach($division);
                    }
                }

                Session::flash('message', 'Success!Data has been saved successfully.');
            } else {
                Session::flash('message', 'Success!Data has not been saved successfully.');
            }
            return redirect()->back();
        }
        else
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id)
    {
        $section = new Section();
        $pageTitle='Batch Information View';
        $section = $section->where('id', $id)->get();
        $insertOrEdit = 'edit';
        return view('academics::section.view',compact('section','pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {


    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */

    public  function upadate_save(Request $request, $id)
    {
        $section = Section::find($id);
        $validator = Validator::make($request->all(), [
//            'academics_year_id' => 'required|max:100',
            'batch_id' => 'required|max:100',
            'section_name'=>'required|max:100',
        ]);

        if($validator->passes()) {

//            $section->academics_year_id = $request->input('academics_year_id');
            $section->batch_id = $request->input('batch_id');
            $section->section_name = $request->input('section_name');
            $section->intake = $request->input('intake');

            // division
            $division = $request->input('division', 'off');

            if($division=='on'){    
                $section->divisions()->sync($request->divisions);
            }else{    
                $section->divisions()->detach();
            }

            if ($section->save()) {
                Session::flash('message', 'Success!Data has been updated successfully.');
            } else {
                Session::flash('message', 'Success!Data has not been updated successfully.');
            }
            return redirect()->back();
        }
        else{
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }
    

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function delete($id)
    {
        $sectionDeleted = $this->section->find($id)->delete();
        // checking
        if ($sectionDeleted) {
            Session::flash('message', 'Success!Data has been deleted successfully.');
        } else {
            Session::flash('message', 'Failed!Data has not been deleted successfully.');
        }
        return redirect()->back();
    }


    public  function section_status_change($id)
    {
        $table = new Section();
        $current=$table->where('id', $id)->first();
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

        $pageTitle = "Section Information";
        $insertOrEdit = 'insert'; //To identify insert
        $sections=$this->getAll();
        return view('academics::section.index', compact('sections','insertOrEdit','pageTitle'));
    }
    public function show_edit($id)
    {
        // $section
        $section = $this->section->find($id);
        // academic year list
        $academicLevels = $this->academicHelper->getAllAcademicLevel();

        $campus = $this->academicHelper->getCampus();
        $institute = $this->academicHelper->getInstitute();

        $dvisionChange = true;
        if (sizeof($section->batch()->divisions) > 0) {
            $dvisionChange = false;
            $divisions = $section->batch()->divisions;
        }else{
            $divisions = $this->division->where(['campus'=>$campus, 'institute'=>$institute])->get();
        }

        // return view with variable
        return view('academics::section.edit',compact('section','academicLevels', 'divisions', 'dvisionChange'));
    }




    ///////// Student Module Ajax Request  function /////////
    public function findSection(Request $request)
    {
        // response array
        $data = array();
        // all level
        $sectionList = $this->section->where('batch_id', $request->id)->orderBy('section_name','ASC')->get();
        // looping for adding section
        foreach ($sectionList as $section) {
            $data[] = [
                'id' =>$section->id,
//                'academics_year_id' =>$section->academics_year_id,
                'batch_id' =>$section->batch_id,
                'section_name' =>$section->section_name,
                'intake' =>$section->intake,
                'status' =>$section->status,
                'campus' =>$section->campus,
                'institute' =>$section->institute,
            ];
        }
        //then sent this data to ajax success
        return $data;

    }

    public function findBatchSection(){

        // all academic level list
        $academicsLevel= $this->academicHelper->getAllAcademicLevel();
        // response data array
        $data = array();
        if($academicsLevel){
            foreach ($academicsLevel as $level){
                foreach ($level->batch() as $batch){
                    // division
                    $division = $batch->division();
                    // batch checking
                    foreach ($batch->section() as $section){
                        // checking division
                        if($division){
                            $batch_name =   $batch->batch_name.' - Section: '.$section->section_name.' ('.$division->name.')';
                        }else{
                            $batch_name =   $batch->batch_name.' - Section: '.$section->section_name;
                        }
                        // array input
                        $data[] = array(
                            'id'=>$level->id.$batch->id.$section->id,
                            'level_id'=>$level->id,
                            'batch_id'=>$batch->id,
                            'section_id'=>$section->id,
                            'name'=>$batch_name,
                        );
                    }
                }
            }

        }
        return json_encode($data);
    }

    public function findBatchDivision(Request $request)
    {
        $batch_division = Batch::findOrFail($request->id)->divisions;

        $campus = $this->academicHelper->getCampus();
        $institute = $this->academicHelper->getInstitute();
        $all_division = $this->division->where(['campus'=>$campus, 'institute'=>$institute])->get();

        return [$batch_division, $all_division];
    }

}
