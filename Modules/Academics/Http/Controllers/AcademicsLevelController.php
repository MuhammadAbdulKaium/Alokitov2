<?php

namespace Modules\Academics\Http\Controllers;

use Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Academics\Entities\AcademicsLevel;
use Modules\Academics\Entities\AcademicsYear;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Academics\Entities\Batch;
use Validator;


class AcademicsLevelController extends Controller
{

    private $academicsYear;
    private $academicsLevel;
    private $academicHelper;
    // constructor
    public function __construct(AcademicHelper $academicHelper, AcademicsYear $academicsYear, AcademicsLevel $academicsLevel)
    {
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

        $pageTitle = "Academic level Information";
        $insertOrEdit = 'insert'; //To identify insert
        $academicLevels = $this->getAll();
        $academicYearData = $this->academicHelper->getAllAcademicYears();
        //  return 'hello';

        //        return ($academicYearData);

        return view('academics::academicsLevel.index', compact('academicLevels', 'academicYearData', 'insertOrEdit', 'pageTitle'));
    }

    public function getAll()
    {
        return $academicLevels =  $this->academicsLevel->where([
            'institute_id' => $this->academicHelper->getInstitute(),
            'campus_id' => $this->academicHelper->getCampus(),
            //            'academics_year_id'=>$this->academicHelper->getAcademicYear()
        ])->get();
        // return $data =AcademicsLevel::findOrfail(1)->year();
        // return $data =AcademicsLevel::where('id', 1)->with('academicsYear')->first();
    }

    public function getLevel($id)
    {
        // level profile
        //        $yearName = AcademicsLevel::where('id', $id)->with('year')->get();

        // level details
        //        $levelName = $levelProfile->level_name;
        //        $levelCode = $levelProfile->level_code;
        //        $levelYearId = $levelProfile->year_id;

        // years details
        // $yearProfile = AcademicsYear::FindOrFail($levelYearId);

        //         = $levelProfile;
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function store(Request $request)
    {
        // validator checking required fields
        $validator = Validator::make($request->all(), [
            'level_name' => 'required|max:100',
            'level_type'        => 'required',
            'level_code'        => 'required',
            'is_active'         => 'required',
        ]);
        // checking validator pass or fail
        if ($validator->passes()) {
            // new academic level
            $data = new AcademicsLevel();
            // store requested profile name
            //$data->academics_year_id = $request->academics_year_id;
            $data->level_name = $request->level_name;
            $data->level_type = $request->level_type;
            $data->level_code = $request->level_code;
            $data->is_active = $request->is_active;
            $data->campus_id = $this->academicHelper->getCampus();
            $data->institute_id = $this->academicHelper->getInstitute();
            // save new profile
            if ($data->save()) {
                Session::flash('message', 'Success!Data has been saved successfully.');
            } else {
                Session::flash('message', 'Success!Data has not been saved successfully.');
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
        $pageTitle = 'Academic level Informations';
        //        $data = newAcademicLevel();
        //        $data = new AcademicsLevel();
        //        $data = $data->where('id', $id)->get();
        //        return $id;
        $academicLevel = new AcademicsLevel();
        $academicLevel = $academicLevel->where('id', $id)->get();
        ////        findOrFail('id',$id);
        //        echo '<pre>';
        //        foreach($academicLevel as $value)
        //        {
        //            print_r($value);
        //        }
        // return $academicLevel;



        //var_dump($academicLevel);
        $insertOrEdit = 'edit';
        return view('academics::academicsLevel.view', compact('insertOrEdit', 'academicLevel', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        //   return view('academics::edit');
        //die;

        $data = new AcademicsLevel();
        $editdata = $data->where('id', $id)->get();
        //$academicYears=AcademicsYear::all();

        $academicLevels = $this->getAll();

        $insertOrEdit = 'edit';

        return view('academics::academicsLevel.index', compact('insertOrEdit', 'editdata', 'academicLevels'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        // find academic level profile
        $academicLevel = AcademicsLevel::find($id);
        // save new profile
        if ($academicLevel->update($request->all())) {
            Session::flash('message', 'Success ! Academic Level Updated ');
        } else {
            Session::flash('message', 'Unable to Update Academic Level');
        }
        // return back
        return redirect('/academics/academic-level');
    }


    public function delete(Request $request, $id)
    {
        $batch = Batch::where('academics_level_id', $id)->get();
        if (sizeof($batch) > 0) {
            Session::flash('error', "Batch Found under this Level. Can't Delete");
        } else {
            // find academic level profile
            $academicLevel = AcademicsLevel::find($id);
            // delete and checking
            if ($academicLevel->delete()) {
                Session::flash('message', 'Success ! Academic Level Updated ');
            } else {
                Session::flash('message', 'Unable to Update Academic Level');
            }
        }
      

        // return back
        return redirect()->back();
    }

    ///////// Student Module Ajax Request  function /////////
    public function findMyLevel(Request $request)
    {
        // response array
        $data = array();
        // all level
        $levelList = $this->academicsLevel->where(['campus_id' => $request->campus, 'institute_id' => $request->institute])->orderBy('level_name', 'ASC')->get();
        //$levelList = $this->academicsLevel->where('academics_year_id', $request->id)->orderBy('level_name', 'ASC')->get();
        // looping for adding division into the batch name
        foreach ($levelList as $level) {
            $data[] = [
                'id' => $level->id,
                'level_name' => $level->level_name,
                'level_code' => $level->level_code,
                'is_active' => $level->is_active,
                'academics_year_id' => 'N/A',
                'institute_id' => $level->institute_id,
                'campus_id' => $level->campus_id,
            ];
        }
        //then sent this data to ajax success
        return $data;
    }


    ///////// Student Module Ajax Request  function /////////
    public function findLevel(Request $request)
    {
        $campus = $this->academicHelper->getCampus();
        $institute = $this->academicHelper->getInstitute();
        // response array
        $data = array();
        // all level
        $levelList = $this->academicsLevel->where(['campus_id' => $campus, 'institute_id' => $institute])->orderBy('level_name', 'ASC')->get();
        // looping for adding division into the batch name
        foreach ($levelList as $level) {
            $data[] = [
                'id' => $level->id,
                'level_name' => $level->level_name,
                'level_code' => $level->level_code,
                'is_active' => $level->is_active,
                'academics_year_id' => 'N/A',
                'institute_id' => $level->institute_id,
                'campus_id' => $level->campus_id,
            ];
        }
        //then sent this data to ajax success
        return $data;
    }
}
