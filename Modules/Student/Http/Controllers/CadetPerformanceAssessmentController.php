<?php

namespace Modules\Student\Http\Controllers;

use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Setting\Entities\CadetAssessmentActivity;
use Modules\Setting\Entities\CadetPerformanceActivity;
use Modules\Setting\Entities\CadetPerformanceCategory;
use Modules\Student\Entities\CadetAssesment;
use Modules\Student\Entities\StudentInformation;
use Redirect;
use Session;
use Validator;

class CadetPerformanceAssessmentController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index($type, $suid)
    {
        $personalInfo = CadetAssesment::where([
            'type'=> $type,
            'student_id' => $suid
            ])->orderBy('created_at', 'desc')->get();
        return view('student::pages.student-profile.student-address', compact('personalInfo'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('student::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
//         dd($request);
//        return $this->fectorAdd($request->fector_point,"");;

        $validator = Validator::make($request->all(), [
            'remarks'    => 'required|max:300',
            'date'       => 'required',
        ]);

        $mandetory = CadetPerformanceCategory::where('id', $request->cadet_performance_category_id)->where('is_mandatory', 1)->get();

        $id=$request->std_id;

        $personalInfo = StudentInformation::findOrFail($id);
        $enrollment = $personalInfo->enroll();

        $assessment = new CadetAssesment();
        $assessment->campus_id = $personalInfo->campus;
        $assessment->institute_id = $personalInfo->institute;
        $assessment->student_id = $personalInfo->id;
        $assessment->section_id = $enrollment->section;
        $assessment->batch_id = $enrollment->batch;
        $assessment->academics_year_id = $enrollment->academic_year;
        $assessment->academics_level_id = $enrollment->academic_level;
        $assessment->date = $request->date;
        $assessment->remarks = $request->remarks;
        $assessment->type = $request->type;

        // if($request->type == 1 || $request->type == 2 || $request->type == 4){
            $assessment->cadet_performance_category_id = $request->cadet_performance_category_id;
            $assessment->cadet_performance_activity_id = $request->cadet_performance_activity_id;
            $assessment->cadet_performance_activity_point_id = $request->cadet_performance_activity_point_id;
            $assessment->performance_category_id = $request->performance_category_id;
            $assessment->total_point = $request->total_point;
        // }

        if ($validator->passes()) {
            try
            {
//                dd($mandetory->count());
                $saved = $assessment->save();

                if($mandetory->count() == 1)
                {
//                    dd($request->fector_point);
                    $insert_id = $assessment->id;
                    $fector = $this->fectorAdd($request->fector_point, $insert_id);
                }

                if ($saved) {
                    Session::flash('message', 'Success!Data has been saved successfully.');
                    return redirect()->back()->with('message', 'Records saved correctly!!!');
                } else {
                    Session::flash('message', 'Success!Data has not been saved successfully.');
                }
            } catch (\Exception $e) {

                return $e->getMessage();

            }
        }
    }

    public function show($id, $item)
    {
        $personalInfo = CadetAssesment::where([
            'id'=> $item,
            'student_id' => $id
        ])->first();
        return view('student::pages.student-profile.modals.common-info-edit', compact('personalInfo'));
    }

    public function edit($id)
    {
        return view('student::edit');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'remarks'    => 'required|max:300',
        ]);

        $personalInfo = StudentInformation::findOrFail($id);
        $enrollment = $personalInfo->enroll();

        $assessment = CadetAssesment::find($request->item_id);
        $assessment->campus_id = $personalInfo->campus;
        $assessment->institute_id = $personalInfo->institute;
        $assessment->student_id = $personalInfo->id;
        $assessment->section_id = $enrollment->section;
        $assessment->batch_id = $enrollment->batch;
        $assessment->academics_year_id = $enrollment->academic_year;
        $assessment->date = $request->date;
        $assessment->remarks = $request->remarks;
        $assessment->type = $request->type;
        $saved = $assessment->update();
        if ($saved) {
            Session::flash('message', 'Success!Data has been updated successfully.');
            return redirect()->back()->with('message', 'Records saved correctly!!!');

//            return redirect()->route('personalInfo',$id)->with('message', 'Records saved correctly!!!');
        } else {
            Session::flash('message', 'Success!Data has not been updated successfully.');
        }

    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $table        = new CadetAssesment();
        $data         = $this->getAll();
        $insertOrEdit = 'delete';
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
    }

    public function delete ($id){
//        dd($id);
        $table        = new CadetAssesment();
        $insertOrEdit = 'delete';
        try
        {
            $saved = $table->where('id', $id)->update(['deleted_at' => Carbon::now()]);
            if ($saved) {
                return redirect()->back()->with('message', 'Success!Data has been deleted successfully.');
            } else {
                Session::flash('message', 'Failed!Data has not been deleted successfully.');
            }
        } catch (\Exception $e) {

            return $e->getMessage();

        }
    }

    private function fectorAdd($factor_assessment, $insert_id)
    {
        //dd(count($factor_assessment));
        try
        {
            foreach($factor_assessment as $key => $val)
            {
                if($val != null)
                {
                    $tbl = new CadetAssessmentActivity();                
                    $tbl->assessment_id = $insert_id;
                    $tbl->activity_id = $key;
                    $tbl->activity_point = $val;
                    $tbl->save();
                }                
            }
        }
        catch(Exception $ex)
        {
            throw new Exception($ex);
        }
        
        return true;
    }
}
