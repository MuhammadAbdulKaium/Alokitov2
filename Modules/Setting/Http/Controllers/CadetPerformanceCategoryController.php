<?php

namespace Modules\Setting\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Setting\Entities\CadetPerformanceActivity;
use Modules\Setting\Entities\CadetPerformanceActivityPoint;
use Modules\Setting\Entities\CadetPerformanceCategory;
use Modules\Setting\Entities\CadetPerformanceType;
use Modules\Student\Entities\CadetAssesment;
use Modules\Student\Entities\CadetAssessmentActivity;
use Modules\Student\Entities\StudentInformation;
use Redirect;
use Session;
use Validator;

class CadetPerformanceCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $pageTitle    = "Performance Category";
        $insertOrEditCategory = 'insert'; //To identify insertt
        $insertOrEditActivity = 'insert';
        $insertOrEditType = 'insert';
        $performancetype = CadetPerformanceType::all();
        $performanceCategory = CadetPerformanceCategory::all();
        $PerformanceActivity = CadetPerformanceActivity::all();
        $PerformanceActivityPoint = CadetPerformanceActivityPoint::all();
        return view('setting::performance-category.index', compact('performanceCategory', 'PerformanceActivity', 'PerformanceActivityPoint', 'insertOrEditCategory', 'insertOrEditType', 'insertOrEditActivity', 'performancetype',  'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('setting::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_name'       => 'required|max:250|unique:cadet_performance_category',
        ]);

        if ($validator->passes()) {

            $performance = new CadetPerformanceCategory();
            // store requested profile name
            $performance->category_name       = $request->category_name;
            $performance->category_type_id       = $request->category_type_id; //for performance
            $performance->is_mandatory       = $request->is_mandatory;

            // save new profile
            try
            {
                $saved = $performance->save();
                if ($saved) {
                    Session::flash('message', 'Success!Data has been saved successfully.');
                } else {
                    Session::flash('message', 'Success!Data has not been saved successfully.');
                }
            } catch (\Exception $e) {

                return $e->getMessage();

            }
            
            $pageTitle    = "Performance Category";
            $insertOrEditCategory = 'insert'; //To identify insertt
            $insertOrEditActivity = 'insert';
            $insertOrEditType = 'insert';
            $performancetype = CadetPerformanceType::all();
            $performanceCategory = CadetPerformanceCategory::all();
            $PerformanceActivity = CadetPerformanceActivity::all();
            $PerformanceActivityPoint = CadetPerformanceActivityPoint::all();
            return view('setting::performance-category.index', compact('performanceCategory', 'PerformanceActivity', 'PerformanceActivityPoint', 'insertOrEditCategory', 'insertOrEditType', 'insertOrEditActivity', 'performancetype',  'pageTitle'));
        } else {
            // Session::flash('warning', 'unable to crate student profile');
            // receiving page action
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    public function activityStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'activity_name'       => 'required|unique:cadet_performance_activity',
            'cadet_category_id'       => 'required',
        ]);
        
        if ($validator->passes()) {

            $Activity = new CadetPerformanceActivity();
            // store requested profile name
            $Activity->activity_name             = $request->activity_name;
            $Activity->cadet_category_id      = $request->cadet_category_id;
            // save new profile
            try
            {
               
                $saved = $Activity->save();
                if ($saved) {
                    Session::flash('message', 'Success!Data has been saved successfully.');
                } else {
                    Session::flash('message', 'Success!Data has not been saved successfully.');
                }
            } catch (\Exception $e) {
                return $e->getMessage();
            }

            $pageTitle    = "Performance Category";
            $insertOrEditCategory = 'insert'; //To identify insertt
            $insertOrEditActivity = 'insert';
            $insertOrEditType = 'insert';
            $performancetype = CadetPerformanceType::all();
            $performanceCategory = CadetPerformanceCategory::all();
            $PerformanceActivity = CadetPerformanceActivity::all();
            $PerformanceActivityPoint = CadetPerformanceActivityPoint::all();
            return view('setting::performance-category.index', compact('performanceCategory', 'PerformanceActivity', 'PerformanceActivityPoint', 'insertOrEditCategory', 'insertOrEditType', 'insertOrEditActivity', 'performancetype',  'pageTitle'));
        } else {
            // Session::flash('warning', 'unable to crate student profile');
            // receiving page action
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    public function TypeStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'performance_type'       => 'required|max:250|unique:cadet_performance_type',
        ]);

        if ($validator->passes()) {

            $performance = new CadetPerformanceType();
            // store requested profile name
            $performance->performance_type       = $request->performance_type;

            // save new profile
            try
            {
                $saved = $performance->save();
                if ($saved) {
                    Session::flash('message', 'Success!Data has been saved successfully.');
                } else {
                    Session::flash('message', 'Success!Data has not been saved successfully.');
                }
            } catch (\Exception $e) {

                return $e->getMessage();

            }
            
            $pageTitle    = "Performance Category";
            $insertOrEditCategory = 'insert'; //To identify insertt
            $insertOrEditActivity = 'insert';
            $insertOrEditType = 'insert';
            $performancetype = CadetPerformanceType::all();
            $performanceCategory = CadetPerformanceCategory::all();
            $PerformanceActivity = CadetPerformanceActivity::all();
            $PerformanceActivityPoint = CadetPerformanceActivityPoint::all();
            return view('setting::performance-category.index', compact('performanceCategory', 'PerformanceActivity', 'PerformanceActivityPoint', 'insertOrEditCategory', 'insertOrEditType', 'insertOrEditActivity', 'performancetype',  'pageTitle'));
        } else {
            // Session::flash('warning', 'unable to crate student profile');
            // receiving page action
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function TypeEdit($id)
    {
        $insertOrEditCategory = 'insert'; //To identify insert
        $insertOrEditActivity = 'insert'; //To identify insert
        $insertOrEditType = 'edit';
        $pageTitle    = "Performance Category";
        $performancetype = CadetPerformanceType::all();
        $performanceCategory = CadetPerformanceCategory::all();
        $type = CadetPerformanceType::where('id', $id)->get();
        $PerformanceActivity = CadetPerformanceActivity::all();
        $PerformanceActivityPoint = CadetPerformanceActivityPoint::all();
        return view('setting::performance-category.index', compact('performanceCategory', 'PerformanceActivity', 'PerformanceActivityPoint', 'type', 'insertOrEditType', 'insertOrEditCategory', 'performancetype', 'insertOrEditActivity', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function CategoryEdit($id)
    {
        $insertOrEditCategory = 'edit'; //To identify insert
        $insertOrEditActivity = 'insert'; //To identify insert
        $insertOrEditType = 'insert';
        $pageTitle    = "Performance Category";
        $performancetype = CadetPerformanceType::all();
        $performanceCategory = CadetPerformanceCategory::all();
        $category = CadetPerformanceCategory::where('id', $id)->get();
        $PerformanceActivity = CadetPerformanceActivity::all();
        $PerformanceActivityPoint = CadetPerformanceActivityPoint::all();
        return view('setting::performance-category.index', compact('performanceCategory', 'PerformanceActivity', 'PerformanceActivityPoint', 'category', 'insertOrEditType', 'insertOrEditCategory', 'performancetype', 'insertOrEditActivity', 'pageTitle'));
    }

        /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function ActivityEdit($id)
    {
        $insertOrEditCategory = 'insert'; //To identify insert
        $insertOrEditActivity = 'edit'; //To identify insert
        $insertOrEditType = 'insert';
        $pageTitle    = "Performance Category";
        $performancetype = CadetPerformanceType::all();
        $performanceCategory = CadetPerformanceCategory::all();
        $activity = CadetPerformanceActivity::where('id', $id)->get();
        $PerformanceActivity = CadetPerformanceActivity::all();
        $PerformanceActivityPoint = CadetPerformanceActivityPoint::all();
        return view('setting::performance-category.index', compact('performanceCategory', 'PerformanceActivity', 'PerformanceActivityPoint', 'activity', 'insertOrEditType', 'insertOrEditCategory', 'performancetype', 'insertOrEditActivity', 'pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function TypeUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'performance_type'       => 'required|max:250|unique:cadet_performance_type',
        ]);

        if ($validator->passes()) {

            $performance = CadetPerformanceType::find($id);
            // store requested profile name
            $performance->performance_type       = $request->performance_type;

            // save new profile
            try
            {
                $saved = $performance->update();
                if ($saved) {
                    Session::flash('message', 'Success!Data has been saved successfully.');
                } else {
                    Session::flash('message', 'Success!Data has not been saved successfully.');
                }
            } catch (\Exception $e) {

                return $e->getMessage();

            }

        
            $pageTitle    = "Performance Category";
            $insertOrEditCategory = 'insert'; //To identify insertt
            $insertOrEditActivity = 'insert';
            $insertOrEditType = 'insert';
            $performancetype = CadetPerformanceType::all();
            $performanceCategory = CadetPerformanceCategory::all();
            $PerformanceActivity = CadetPerformanceActivity::all();
            $PerformanceActivityPoint = CadetPerformanceActivityPoint::all();
            return view('setting::performance-category.index', compact('performanceCategory', 'PerformanceActivity', 'PerformanceActivityPoint', 'insertOrEditCategory', 'insertOrEditType', 'insertOrEditActivity', 'performancetype',  'pageTitle'));
        } else {
            // Session::flash('warning', 'unable to crate student profile');
            // receiving page action
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function CategoryUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'category_name'       => 'required|max:250',
        ]);

        if ($validator->passes()) {

            $performance = CadetPerformanceCategory::find($id);
            // store requested profile name
            $performance->category_name       = $request->category_name;
            $performance->category_type_id       = $request->category_type_id;
            $performance->is_mandatory       = $request->is_mandatory;

            // save new profile
            try
            {
                $saved = $performance->update();
                if ($saved) {
                    Session::flash('message', 'Success!Data has been saved successfully.');
                } else {
                    Session::flash('message', 'Success!Data has not been saved successfully.');
                }
            } catch (\Exception $e) {

                return $e->getMessage();

            }

            $pageTitle    = "Performance Category";
            $insertOrEditCategory = 'insert'; //To identify insertt
            $insertOrEditActivity = 'insert';
            $insertOrEditType = 'insert';
            $performancetype = CadetPerformanceType::all();
            $performanceCategory = CadetPerformanceCategory::all();
            $PerformanceActivity = CadetPerformanceActivity::all();
            $PerformanceActivityPoint = CadetPerformanceActivityPoint::all();
            return view('setting::performance-category.index', compact('performanceCategory', 'PerformanceActivity', 'PerformanceActivityPoint', 'insertOrEditCategory', 'insertOrEditType', 'insertOrEditActivity', 'performancetype',  'pageTitle'));
        } else {
            // Session::flash('warning', 'unable to crate student profile');
            // receiving page action
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function ActivityUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'activity_name'       => 'required',
            'cadet_category_id'       => 'required',
        ]);

        if ($validator->passes()) {

            $Activity = CadetPerformanceActivity::find($id);
            // store requested profile name
            $Activity->activity_name             = $request->activity_name;
            $Activity->cadet_category_id      = $request->cadet_category_id;            

            // save new profile
            try
            {
                $saved = $Activity->update();
                if ($saved) {
                    Session::flash('message', 'Success!Data has been saved successfully.');
                } else {
                    Session::flash('message', 'Success!Data has not been saved successfully.');
                }
            } catch (\Exception $e) {

                return $e->getMessage();

            }

            $pageTitle    = "Performance Category";
            $insertOrEditCategory = 'insert'; //To identify insertt
            $insertOrEditActivity = 'insert';
            $insertOrEditType = 'insert';
            $performancetype = CadetPerformanceType::all();
            $performanceCategory = CadetPerformanceCategory::all();
            $PerformanceActivity = CadetPerformanceActivity::all();
            $PerformanceActivityPoint = CadetPerformanceActivityPoint::all();
            // return view('setting::performance-category.index', compact('performanceCategory', 'PerformanceActivity', 'PerformanceActivityPoint', 'insertOrEditCategory', 'insertOrEditType', 'insertOrEditActivity', 'performancetype',  'pageTitle'));
            return redirect('/setting/performance/category');
        } else {
            // Session::flash('warning', 'unable to crate student profile');
            // receiving page action
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    public function TypeDelete(Request $request, $id)
    {
        try
        {            
            $data = CadetPerformanceCategory::where('category_type_id', $id)->get();
            if($data->count() > 0){
                Session::flash('message', 'Failed! Child record found.');
            } else {
                $saved = CadetPerformanceType::where('id', $id)->forceDelete();
                if ($saved) {
                    Session::flash('message', 'Success!CadetPerformanceTypea has been deleted successfully.');
                } else {
                    Session::flash('message', 'Failed!Data has not been deleted successfully.');
                }
            }
            
        } catch (\Exception $e) {

            return $e->getMessage();

        }

        $insertOrEditCategory = 'insert';
        $insertOrEditActivity = 'insert'; //To identify insert
        $insertOrEditType = 'insert'; //To identify insert
        $pageTitle    = "Performance Category";
        $performancetype = CadetPerformanceType::all();
        $performanceCategory = CadetPerformanceCategory::all();
        $PerformanceActivity = CadetPerformanceActivity::all();
        $PerformanceActivityPoint = CadetPerformanceActivityPoint::all();
        // return view('setting::performance-category.index', compact('performanceCategory', 'PerformanceActivity', 'PerformanceActivityPoint', 'insertOrEditCategory','insertOrEditType', 'performancetype', 'insertOrEditActivity', 'pageTitle'));
        return redirect('/setting/performance/category');
    }

    public function CategoryDelete(Request $request, $id)
    {
        $insertOrEditCategory = 'insert';
        $insertOrEditActivity = 'insert'; //To identify insert
        try
        {            
            $data = CadetPerformanceActivity::where('cadet_category_id', $id)->get();
            if($data->count() > 0){
                Session::flash('message', 'Failed! Child record found.');
            } else {
                $saved = CadetPerformanceCategory::where('id', $id)->forceDelete();
                if ($saved) {
                    Session::flash('message', 'Success!Data has been deleted successfully.');
                } else {
                    Session::flash('message', 'Failed!Data has not been deleted successfully.');
                }
            }
            
        } catch (\Exception $e) {

            return $e->getMessage();

        }
        
        $insertOrEditCategory = 'insert';
        $insertOrEditActivity = 'insert'; //To identify insert
        $insertOrEditType = 'insert'; //To identify insert
        $pageTitle    = "Performance Category";
        $performancetype = CadetPerformanceType::all();
        $performanceCategory = CadetPerformanceCategory::all();
        $PerformanceActivity = CadetPerformanceActivity::all();
        $PerformanceActivityPoint = CadetPerformanceActivityPoint::all();
        // return view('setting::performance-category.index', compact('performanceCategory', 'PerformanceActivity', 'PerformanceActivityPoint', 'insertOrEditCategory', 'insertOrEditType', 'performancetype', 'insertOrEditActivity', 'pageTitle'));
        return redirect('/setting/performance/category');
    }

    public function ActivityDelete(Request $request, $id)
    {
        
        try
        {            
            $data = CadetPerformanceActivityPoint::where('cadet_performance_activity', $id)->get();
            if($data->count() > 0){
                Session::flash('message', 'Failed! Child record found.');
            } else {
                $saved = CadetPerformanceActivity::where('id', $id)->forceDelete();
                if ($saved) {
                    Session::flash('message', 'Success!Data has been deleted successfully.');
                } else {
                    Session::flash('message', 'Failed!Data has not been deleted successfully.');
                }
            }
            
        } catch (\Exception $e) {

            return $e->getMessage();

        }

        $insertOrEditCategory = 'insert';
        $insertOrEditActivity = 'insert'; //To identify insert
        $insertOrEditType = 'insert'; //To identify insert
        $pageTitle    = "Performance Category";
        $performancetype = CadetPerformanceType::all();
        $performanceCategory = CadetPerformanceCategory::all();
        $PerformanceActivity = CadetPerformanceActivity::all();
        $PerformanceActivityPoint = CadetPerformanceActivityPoint::all();
        // return view('setting::performance-category.index', compact('performanceCategory', 'PerformanceActivity', 'PerformanceActivityPoint', 'insertOrEditCategory', 'insertOrEditType', 'performancetype', 'insertOrEditActivity', 'pageTitle'));
        return redirect('/setting/performance/category');
    }

    public function ActivityPoint($id)
    {
        $addOrEdit = "insert";
        $point = CadetPerformanceActivityPoint::where("cadet_performance_activity", $id)->get();
        return view('setting::modals.performance_point_modal')->with('activity_id', $id)->with("pointList", $point)->with("addOrEdit", $addOrEdit);
    }

    public function ActivityPointAdd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'value'       => 'required|max:250',
            'point'       => 'required',
            'cadet_performance_activity' => 'required'
        ]);

        if ($validator->passes()) {

            $performance = new CadetPerformanceActivityPoint();
            // store requested profile name
            $performance->cadet_performance_activity       = $request->cadet_performance_activity;
            $performance->value       = $request->value;
            $performance->point       = $request->point;

            // save new profile
            try
            {
                $saved = $performance->save();
                if ($saved) {
                    Session::flash('message', 'Success!Data has been saved successfully.');
                } else {
                    Session::flash('message', 'Success!Data has not been saved successfully.');
                }
            } catch (\Exception $e) {

                return $e->getMessage();

            }
            return redirect('/setting/performance/category');            
        } else {
            // Session::flash('warning', 'unable to crate student profile');
            // receiving page action
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    public function ActivityPointEdit($id){
        $addOrEdit = "edit";
        $pointedit = CadetPerformanceActivityPoint::find($id);
        $point = CadetPerformanceActivityPoint::where("cadet_performance_activity", $id)->get();
        return view('setting::modals.performance_point_edit_modal')->with('activity_id', $id)->with("pointList", $point)->with("pointedit", $pointedit)->with("addOrEdit", $addOrEdit);
    }

    public function ActivityPointDelete($id)
    {
        // $act = CadetPerformanceActivity::where("cadet_category_id", $id)->get();
        // dd($act);

        // $data = array("Excellent"=>5, "Very Good"=>4, "Good"=>4, "Average"=>2, "Below Average"=>1);

        // $data_bool = array("True (সত্য)"=>1, "False (মিথ্যা)"=>0);
        
        // $data_grad = array("A+"=>5, "A"=>4, "A-"=>3, "B"=>2, "C"=>1, "F"=>0);
        
        // $data_Depression = array("Absolutely Not Applicable (একেবারেই প্রযোজ্য নয়)"=>0, "Not Applicable (প্রযোজ্য নয়)"=>1, "In Between (মাঝামাঝি)"=>2, "Slightly Applicable (কিছুটা প্রযোজ্য)"=>3, "Absolutely Applicable (পুরোপুরি প্রযোজ্য)"=>4);
        
        // $data_Depression1 = array("Absolutely Not (একেবারেই হয় না)"=>0, "Not Applicable (প্রযোজ্য নয়)"=>1, "In Between (মাঝামাঝি)"=>2, "Slightly Applicable (কিছুটা প্রযোজ্য)"=>3, "Absolutely Applicable (পুরোপুরি প্রযোজ্য)"=>4);

        // foreach($act as $item)
        // {
        //     foreach($data_bool as $x => $val)
        //     {
        //         $point = new CadetPerformanceActivityPoint();
        //         $point->cadet_performance_activity = $item->id;
        //         $point->value = $x;
        //         $point->point = $val;

        //         //dd($point);
        //         $point->save();
        //     }
        // }
        // echo "done";
        
            


        $point = CadetPerformanceActivityPoint::find($id);
        $data = CadetAssesment::where('cadet_performance_activity_point_id', $point->id)->get();
        if($data != null){
            $del = CadetPerformanceActivityPoint::where('id', $id)->forceDelete();
            
            if ($del) {
                Session::flash('message', 'Success!Data has been deleted successfully.');
            } else {
                Session::flash('message', 'Failed!Data has not been deleted successfully.');
            }
        }else{
            Session::flash('message', 'Failed! Child record found.');
        }
        return redirect('/setting/performance/category');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
    public function SubjectPointStore(Request $request)
    {
        $length = 0;
        foreach ($request->subject_point as $sub){
            if($sub != null)
            {
                $length ++;
            }
        }
        $total=$request->totalValue;
        $gpa=floatval($total)/$length;
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
        $assessment->remarks = $request->exam_name;
        $assessment->cadet_performance_category_id = 19;
        $assessment->type = 19;
        $assessment->total_point = floatval($gpa);
        try
        {
            $assessment->save();
            $insert_id = $assessment->id;
            $fector = $this->fectorAdd($request->subject_point, $insert_id);

            if ($fector) {
                Session::flash('message', 'Success!Data has been saved successfully.');
                return redirect()->back()->with('message', 'Records saved correctly!!!');
            } else {
                Session::flash('message', 'Success!Data has not been saved successfully.');
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
            foreach($factor_assessment as $key => $val) {
                if ($val != null) {
                    $tbl = new \Modules\Setting\Entities\CadetAssessmentActivity();
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
