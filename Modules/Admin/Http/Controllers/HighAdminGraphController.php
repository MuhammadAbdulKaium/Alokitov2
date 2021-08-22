<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Carbon\Carbon;
use Modules\Academics\Entities\AcademicsYear;
use Modules\Setting\Entities\CadetAssessmentActivity;
use Modules\Setting\Entities\CadetPerformanceActivity;
use Modules\Setting\Entities\CadetPerformanceCategory;
use Modules\Setting\Entities\CadetPerformanceType;
use Modules\Setting\Entities\Institute;
use Modules\Student\Entities\CadetAssesment;
use Modules\Student\Entities\CadetChartView;
use Redirect;
use Session;
use Validator;

class HighAdminGraphController extends Controller
{
    public function AcadimicChart(Request $request)
    {
        $data = [];
        $institute = Institute::all();
        // categoryType = 1 (performance)
        if($request->year != null  && $request->cadetClass == null && $request->categoryType == 1 && $request->month == null && $request->categoryID == null && $request->categoryActivity == null && $request->viewtType == "details")
        {
            $sdate = strval($request->year).'-01-01';
            $edate =strval($request->year).'-12-31';

            foreach ($institute as $item)
            {
                $inst = [];
                $activity = CadetPerformanceCategory::where('category_type_id', $request->categoryType)->get();
                foreach ($activity as $act)
                {
                    $assessment = CadetChartView::where('type', $request->categoryType)
                        ->where('institute_id', $item->id)
                        ->whereBetween('date', [$sdate, $edate])
                        ->where('performance_category_id', $act->id)
                        ->get();

                    if($assessment->sum('value') > 0)
                    {
                        $avg = ($assessment->sum('value') / $assessment->count());
                        $chart = array(
                            'group_name' => $act->category_name,
                            'name' => $item->institute_alias ,
                            'value' =>  number_format($avg,2)
                        );
                        array_push($inst, $chart);
                    }
                }
                $per = array(
                    'graph_type' => "bar chart",
                    'institute' => $item->institute_alias,
                    'data' => $inst
                );
                array_push($data, $per);
            }

            return \response()->json($data);
        }
        elseif($request->year != null  && $request->cadetClass == null && $request->categoryType == 1 && $request->month == null && $request->categoryID == null && $request->categoryActivity == null && $request->viewtType == "summary")
        {
//            return \response()->json("hi");
            $sdate = strval($request->year).'-01-01';
            $edate =strval($request->year).'-12-31';

            foreach ($institute as $item)
            {
                $inst = [];
                $type = CadetPerformanceType::find($request->categoryType);
                    $assessment = CadetChartView::where('type', $request->categoryType)
                        ->where('institute_id', $item->id)
                        ->whereBetween('date', [$sdate, $edate])
                        ->get();

                    if($assessment->sum('value') > 0)
                    {
                        $avg = ($assessment->sum('value') / $assessment->count());
                        $chart = array(
                            'group_name' => $type->performance_type,
                            'name' => $item->institute_alias ,
                            'value' =>  number_format($avg,2)
                        );
                        array_push($inst, $chart);
                    }
                $per = array(
                    'graph_type' => "bar chart",
                    'institute' => $item->institute_alias,
                    'data' => $inst
                );
                array_push($data, $per);
            }

            return \response()->json($data);
        }
        elseif($request->year != null  && $request->cadetClass == null && $request->categoryType == 1 && $request->month != null && $request->categoryID == null && $request->categoryActivity == null && $request->viewtType == "summary")
        {
//            return \response()->json("hi");
            $sdate = strval($request->year).'-01-01';
            $edate =strval($request->year).'-12-31';

            foreach ($institute as $item)
            {
                $inst = [];
                $type = CadetPerformanceType::find($request->categoryType);
                $assessment = CadetChartView::where('type', $request->categoryType)
                    ->where('institute_id', $item->id)
                    ->whereBetween('date', [$sdate, $edate])
                    ->where('name', $request->month)
                    ->get();

                if($assessment->sum('value') > 0)
                {
                    $avg = ($assessment->sum('value') / $assessment->count());
                    $chart = array(
                        'group_name' => $type->performance_type,
                        'name' => $item->institute_alias ,
                        'value' =>  number_format($avg,2)
                    );
                    array_push($inst, $chart);
                }
                $per = array(
                    'graph_type' => "bar chart",
                    'institute' => $item->institute_alias,
                    'data' => $inst
                );
                array_push($data, $per);
            }

            return \response()->json($data);
        }
        elseif($request->year != null && $request->cadetClass == null && $request->categoryType == 1 && $request->month == null && $request->categoryID != null && $request->categoryActivity == null && $request->viewtType == "details")
        {
            $sdate = strval($request->year).'-01-01';
            $edate =strval($request->year).'-12-31';

            foreach ($institute as $item)
            {
                $inst = [];
                $category = CadetPerformanceActivity::where('cadet_category_id', $request->categoryID)
                    ->get();

                foreach ($category as $cat)
                {
                    $assessment = CadetChartView::where('type', $request->categoryType)
                        ->where('cadet_performance_activity_id', $cat->id)
                        ->where('institute_id', $item->id)
                        ->whereBetween('date', [$sdate, $edate])
                        ->get();

                    if($assessment->sum('value') > 0)
                    {
                        $avg = ($assessment->sum('value') / $assessment->count());
                        $chart = array(
                            'group_name' => $cat->activity_name,
                            'name' => $item->institute_alias ,
                            'value' =>  number_format($avg,2)
                        );
                        array_push($inst, $chart);
                    }
                }
                $per = array(
                    'graph_type' => "bar chart",
                    'institute' => $item->institute_alias,
                    'data' => $inst
                );
                array_push($data, $per);
            }

            return \response()->json($data);

        }
        elseif($request->year != null && $request->cadetClass == null && $request->categoryType == 1 && $request->month != null && $request->categoryID != null && $request->categoryActivity == null && $request->viewtType == "details")
        {
            $sdate = strval($request->year).'-01-01';
            $edate =strval($request->year).'-12-31';

            foreach ($institute as $item)
            {
                $inst = [];
                $category = CadetPerformanceActivity::where('cadet_category_id', $request->categoryID)
                    ->get();

                foreach ($category as $cat)
                {
                    $assessment = CadetChartView::where('type', $request->categoryType)
                        ->where('cadet_performance_activity_id', $cat->id)
                        ->where('institute_id', $item->id)
                        ->where('name', $request->month)
                        ->whereBetween('date', [$sdate, $edate])
                        ->get();

                    if($assessment->sum('value') > 0)
                    {
                        $avg = ($assessment->sum('value') / $assessment->count());
                        $chart = array(
                            'group_name' => $cat->activity_name,
                            'name' => $item->institute_alias ,
                            'value' =>  number_format($avg,2)
                        );
                        array_push($inst, $chart);
                    }
                }
                $per = array(
                    'graph_type' => "bar chart",
                    'institute' => $item->institute_alias,
                    'data' => $inst
                );
                array_push($data, $per);
            }

            return \response()->json($data);

        }
        elseif($request->year != null && $request->cadetClass == null && $request->categoryType == 1 && $request->month == null && $request->categoryID != null && $request->categoryActivity == null && $request->viewtType == "summary")
        {
//            return \response()->json("hi");
            $sdate = strval($request->year).'-01-01';
            $edate =strval($request->year).'-12-31';

            foreach ($institute as $item)
            {
                $inst = [];
                $category = CadetPerformanceCategory::where('id', $request->categoryID)->first();

                $assessment = CadetChartView::where('type', $request->categoryType)
                    ->where('institute_id', $item->id)
                    ->whereBetween('date', [$sdate, $edate])
                    ->get();
//                return \response()->json($assessment);

                if($assessment->sum('value') > 0)
                {
                    $avg = ($assessment->sum('value') / $assessment->count());
                    $chart = array(
                        'group_name' => $category->category_name,
                        'name' => $item->institute_alias ,
                        'value' =>  number_format($avg,2)
                    );
                    array_push($inst, $chart);
                }

                $per = array(
                    'graph_type' => "bar chart",
                    'institute' => $item->institute_alias,
                    'data' => $inst
                );
                array_push($data, $per);
            }

            return \response()->json($data);

        }
        elseif($request->year != null && $request->cadetClass == null && $request->categoryType == 1 && $request->month != null && $request->categoryID != null && $request->categoryActivity == null && $request->viewtType == "summary")
        {
//            return \response()->json($data);
            $sdate = strval($request->year).'-01-01';
            $edate =strval($request->year).'-12-31';

            foreach ($institute as $item)
            {
                $inst = [];
                $category = CadetPerformanceCategory::where('id', $request->categoryID)->first();


                $assessment = CadetChartView::where('type', $request->categoryType)
                    ->where('institute_id', $item->id)
                    ->where('name', $request->month)
                    ->whereBetween('date', [$sdate, $edate])
                    ->get();

                if($assessment->sum('value') > 0)
                {
                    $avg = ($assessment->sum('value') / $assessment->count());
                    $chart = array(
                        'group_name' => $category->category_name,
                        'name' => $item->institute_alias ,
                        'value' =>  number_format($avg,2)
                    );
                    array_push($inst, $chart);
                }
                $per = array(
                    'graph_type' => "bar chart",
                    'institute' => $item->institute_alias,
                    'data' => $inst
                );
                array_push($data, $per);
            }

            return \response()->json($data);

        }
        elseif($request->year != null && $request->cadetClass == null && $request->categoryType == 1 && $request->month == null && $request->categoryID != null && $request->categoryActivity != null && $request->viewtType == "details")
        {
            $sdate = strval($request->year).'-01-01';
            $edate =strval($request->year).'-12-31';

            foreach ($institute as $item)
            {
                $inst = [];
                $category = CadetPerformanceActivity::where('id', $request->categoryActivity)
                    ->get();

                foreach ($category as $cat)
                {
                    $assessment = CadetChartView::where('type', $request->categoryType)
                        ->where('cadet_performance_activity_id', $cat->id)
                        ->where('institute_id', $item->id)
                        ->whereBetween('date', [$sdate, $edate])
                        ->get();

                    foreach ($assessment as $ass)
                    {
                        $chart = array(
                            'group_name' => $ass->name,
                            'name' => $item->institute_alias ,
                            'value' =>  $ass->value
                        );
                        array_push($inst, $chart);
                    }


                }
                $per = array(
                    'graph_type' => "bar chart",
                    'institute' => $item->institute_alias,
                    'data' => $inst
                );
                array_push($data, $per);
            }

            return \response()->json($data);

        }
        elseif($request->year != null && $request->cadetClass == null && $request->categoryType == 1 && $request->month != null && $request->categoryID != null && $request->categoryActivity != null && $request->viewtType != null)
        {
            $sdate = strval($request->year).'-01-01';
            $edate =strval($request->year).'-12-31';

            foreach ($institute as $item)
            {
                $inst = [];
                $category = CadetPerformanceActivity::where('id', $request->categoryActivity)->get();

                foreach ($category as $cat)
                {
                    $assessment = CadetChartView::where('type', $request->categoryType)
                        ->where('cadet_performance_activity_id', $cat->id)
                        ->where('name', $request->month)
                        ->where('institute_id', $item->id)
                        ->whereBetween('date', [$sdate, $edate])
                        ->get();

                    foreach ($assessment as $ass)
                    {
                        $chart = array(
                            'group_name' => $ass->remarks,
                            'name' => $item->institute_alias ,
                            'value' =>  $ass->value
                        );
                        array_push($inst, $chart);
                    }
                }
                $per = array(
                    'graph_type' => "bar chart",
                    'institute' => $item->institute_alias,
                    'data' => $inst
                );
                array_push($data, $per);
            }

            return \response()->json($data);

        }
        // categoryType = 6 (Academic)
        elseif ($request->year != null && $request->cadetClass == null && $request->categoryType == 6 && $request->month == null && $request->categoryID == null && $request->categoryActivity == null && $request->viewtType == "details")
        {
            $sdate = strval($request->year).'-01-01';
            $edate =strval($request->year).'-12-31';

            foreach ($institute as $item)
            {
                $inst = [];

                $assesment = CadetAssesment::where('institute_id', $item->id)
                    ->where('type',19)
                    ->whereBetween('date', [$sdate, $edate])
                    ->get();
                
                foreach ($assesment as $act)
                {
//                    return \response()->json($assessment);
                    $chart = array(
                        'group_name' => $act->remarks,
                        'name' => $item->institute_alias ,
                        'value' =>  number_format($act->total_point,2)
                    );
                    array_push($inst, $chart);
//                    if($assessment->sum('total_point') > 0)
//                    {
//                        $avg = ($assessment->sum('total_point') / $assessment->count());
//                        $chart = array(
//                            'group_name' => $act->remarks,
//                            'name' => $item->institute_alias ,
//                            'value' =>  number_format($avg,2)
//                        );
//                        array_push($inst, $chart);
//                    }
                }
                $per = array(
                    'graph_type' => "bar chart",
                    'institute' => $item->institute_alias,
                    'data' => $this->ArrayShort($inst, 'group_name', SORT_ASC, 'name', SORT_ASC)
                );
                array_push($data, $per);
            }

            return \response()->json($data);
        }
        elseif ($request->year != null && $request->cadetClass == null && $request->categoryType == 6 && $request->month == null && $request->categoryID != null && $request->categoryActivity != null && $request->viewtType == "details")
        {
            $sdate = strval($request->year).'-01-01';
            $edate =strval($request->year).'-12-31';

            foreach ($institute as $item)
            {
                $inst = [];

                $assesment = CadetAssesment::where('institute_id', $item->id)
                    ->where('type',19)
                    ->join('cadet_assesment_activity', 'cadet_assesment_activity.assessment_id', '=', 'cadet_assesment.id')
                    ->whereBetween('date', [$sdate, $edate])
                    ->where('activity_id', $request->categoryActivity)
                    ->get();

//                return \response()->json($assesment);

                foreach ($assesment as $act)
                {
                    $assessment = CadetChartView::where('type', 19)
                        ->where('institute_id', $item->id)
                        ->whereBetween('date', [$sdate, $edate])
                        ->get();
//                    return \response()->json($assessment);
                    $chart = array(
                        'group_name' => $act->remarks,
                        'name' =>  $item->institute_alias,
                        'value' =>  number_format($act->total_point,2)
                    );
                    array_push($inst, $chart);
//                    if($assessment->sum('total_point') > 0)
//                    {
//                        $avg = ($assessment->sum('total_point') / $assessment->count());
//                        $chart = array(
//                            'group_name' => $act->remarks,
//                            'name' => $item->institute_alias ,
//                            'value' =>  number_format($avg,2)
//                        );
//                        array_push($inst, $chart);
//                    }
                }
                $per = array(
                    'graph_type' => "bar chart",
                    'institute' => $item->institute_alias,
                    'data' => $inst
                );
                array_push($data, $per);
            }

            return \response()->json($data);
        }
        elseif ($request->year != null && $request->cadetClass == null && $request->categoryType == 6 && $request->month == null && $request->categoryID != null && $request->categoryActivity != null && $request->viewtType == "summary")
        {
            $sdate = strval($request->year).'-01-01';
            $edate =strval($request->year).'-12-31';
            $activity = CadetPerformanceActivity::find($request->categoryActivity);

            foreach ($institute as $item)
            {
                $inst = [];

                $assesment = CadetAssesment::where('institute_id', $item->id)
                    ->where('type',19)
                    ->join('cadet_assesment_activity', 'cadet_assesment_activity.assessment_id', '=', 'cadet_assesment.id')
                    ->whereBetween('date', [$sdate, $edate])
                    ->where('activity_id', $request->categoryActivity)
                    ->get();

//                return \response()->json($assesment);

                if($assesment->sum('total_point') > 0)
                {
                    $avg = ($assesment->sum('total_point') / $assesment->count());
                    $chart = array(
                        'group_name' => $item->institute_alias,
                        'name' => $activity->activity_name,
                        'value' =>  number_format($avg,2)
                    );
                    array_push($inst, $chart);
                }

                $per = array(
                    'graph_type' => "bar chart",
                    'institute' => $item->institute_alias,
                    'data' => $inst
                );
                array_push($data, $per);
            }

            return \response()->json($data);
        }

        return \response()->json($request);
    }

    public function AdminDashboardChart(Request $request)
    {
        $data = [];
//        return \response()->json($request);
        $monthName = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

        if($request->AcademicLevel != null && $request->month_name == null  && $request->Class != null && $request->Section != null && $request->cattype != null && $request->CategoryID == null && $request->categoryActivity == null  && $request->duration == "yearly" && $request->type == "details")
        {
                $activity = CadetPerformanceCategory::where('category_type_id', $request->cattype)->get();
                foreach ($activity as $act)
                {
                    $assessment = CadetChartView::where('performance_category_id', $act->id)
                        ->where('institute_id', $request->instituteId)
                        ->where('campus_id', $request->campusId)
                        ->where('academics_year_id', $request->AcademicYear)
                        ->where('academics_level_id', $request->AcademicLevel)
                        ->where('section_id', $request->Section)
                        ->where('batch_id', $request->Class)
                        ->where('type', $request->cattype)
                        ->get();

                    if($assessment->sum('value') > 0)
                    {
                        $avg = ($assessment->sum('value') / $assessment->count());
                        $chart = array(
                            'group_name' => $act->category_name,
                            'name' => "" ,
                            'value' =>  number_format($avg,2)
                        );
                        array_push($data, $chart);
                    }
                }
            return \response()->json($data);
        }
        elseif($request->AcademicLevel != null && $request->month_name == null  && $request->Class != null && $request->Section != null && $request->cattype != null && $request->CategoryID != null && $request->categoryActivity == null && $request->duration == "yearly" && $request->type == "details")
        {
            $activity = CadetPerformanceActivity::where('cadet_category_id', $request->cattype)->get();
            foreach ($activity as $act)
            {
                $assessment = CadetChartView::where('cadet_performance_activity_id', $act->id)
                    ->where('institute_id', $request->instituteId)
                    ->where('campus_id', $request->campusId)
                    ->where('academics_year_id', $request->AcademicYear)
                    ->where('academics_level_id', $request->AcademicLevel)
                    ->where('section_id', $request->Section)
                    ->where('batch_id', $request->Class)
                    ->where('type', $request->cattype)
                    ->get();

                if($assessment->sum('value') > 0)
                {
                    $avg = ($assessment->sum('value') / $assessment->count());
                    $chart = array(
                        'group_name' => $act->activity_name,
                        'name' => '' ,
                        'value' =>  number_format($avg,2)
                    );
                    array_push($data, $chart);
                }
            }
            return \response()->json($data);
        }
        elseif($request->AcademicLevel != null && $request->month_name == null  && $request->Class != null && $request->Section != null && $request->cattype != null && $request->CategoryID != null && $request->categoryActivity != null && $request->duration == "yearly" && $request->type == "details")
        {
            $activity = CadetPerformanceActivity::find($request->categoryActivity);

                $assessment = CadetChartView::where('cadet_performance_activity_id', $request->categoryActivity)
                    ->where('institute_id', $request->instituteId)
                    ->where('campus_id', $request->campusId)
                    ->where('academics_year_id', $request->AcademicYear)
                    ->where('academics_level_id', $request->AcademicLevel)
                    ->where('section_id', $request->Section)
                    ->where('batch_id', $request->Class)
                    ->where('type', $request->cattype)
                    ->get();

                if($assessment->sum('value') > 0)
                {
                    $avg = ($assessment->sum('value') / $assessment->count());
                    $chart = array(
                        'group_name' => $activity->activity_name,
                        'name' => '' ,
                        'value' =>  number_format($avg,2)
                    );
                    array_push($data, $chart);
                }
            return \response()->json($data);
        }
        elseif($request->AcademicLevel != null && $request->month_name == null  && $request->Class != null && $request->Section != null && $request->cattype != null && $request->CategoryID == null && $request->categoryActivity == null && $request->duration == "yearly" && $request->type == "summary")
        {
            $activity = CadetPerformanceType::find($request->cattype);
//            foreach ($activity as $act)
//            {
                $assessment = CadetChartView::where('institute_id', $request->instituteId)
                    ->where('campus_id', $request->campusId)
                    ->where('academics_year_id', $request->AcademicYear)
                    ->where('academics_level_id', $request->AcademicLevel)
                    ->where('section_id', $request->Section)
                    ->where('batch_id', $request->Class)
                    ->where('type', $request->cattype)
                    ->get();

                if($assessment->sum('value') > 0)
                {
                    $avg = ($assessment->sum('value') / $assessment->count());
                    $chart = array(
                        'group_name' => $activity->performance_type,
                        'name' => "" ,
                        'value' =>  number_format($avg,2)
                    );
                    array_push($data, $chart);
                }
//            }
            return \response()->json($data);
        }
        elseif($request->AcademicLevel != null && $request->month_name == null  && $request->Class != null && $request->Section != null && $request->cattype != null && $request->CategoryID != null && $request->categoryActivity == null && $request->duration == "yearly" && $request->type == "summary")
        {
            $activity = CadetPerformanceCategory::find($request->CategoryID);
            $assessment = CadetChartView::where('institute_id', $request->instituteId)
                ->where('campus_id', $request->campusId)
                ->where('academics_year_id', $request->AcademicYear)
                ->where('academics_level_id', $request->AcademicLevel)
                ->where('section_id', $request->Section)
                ->where('batch_id', $request->Class)
                ->where('type', $request->cattype)
                ->where('performance_category_id', $request->CategoryID)
                ->get();

            if($assessment->sum('value') > 0)
            {
                $avg = ($assessment->sum('value') / $assessment->count());
                $chart = array(
                    'group_name' => $activity->category_name,
                    'name' => "" ,
                    'value' =>  number_format($avg,2)
                );
                array_push($data, $chart);
            }
            return \response()->json($data);
        }
        elseif($request->AcademicLevel != null && $request->month_name == null  && $request->Class != null && $request->Section != null && $request->cattype != null && $request->CategoryID != null && $request->categoryActivity != null && $request->duration == "yearly" && $request->type == "summary")
        {
            $activity = CadetPerformanceActivity::find($request->categoryActivity);
            $assessment = CadetChartView::where('institute_id', $request->instituteId)
                ->where('campus_id', $request->campusId)
                ->where('academics_year_id', $request->AcademicYear)
                ->where('academics_level_id', $request->AcademicLevel)
                ->where('section_id', $request->Section)
                ->where('batch_id', $request->Class)
                ->where('type', $request->cattype)
                ->where('performance_category_id', $request->CategoryID)
                ->where('cadet_performance_activity_id', $request->categoryActivity)
                ->get();

            if($assessment->sum('value') > 0)
            {
                $avg = ($assessment->sum('value') / $assessment->count());
                $chart = array(
                    'group_name' => $activity->activity_name,
                    'name' => "" ,
                    'value' =>  number_format($avg,2)
                );
                array_push($data, $chart);
            }
            return \response()->json($data);
        }
        elseif($request->AcademicLevel != null && $request->month_name == null  && $request->Class != null && $request->Section != null && $request->cattype != null && $request->CategoryID == null && $request->categoryActivity == null  && $request->duration == "monthly" && $request->type == "details")
        {

            $activity = CadetPerformanceCategory::where('category_type_id', $request->cattype)->get();
            foreach ($activity as $act)
            {
                foreach ($monthName as $m)
                {
                    $assessment = CadetChartView::where('performance_category_id', $act->id)
                        ->where('institute_id', $request->instituteId)
                        ->where('campus_id', $request->campusId)
                        ->where('academics_year_id', $request->AcademicYear)
                        ->where('academics_level_id', $request->AcademicLevel)
                        ->where('section_id', $request->Section)
                        ->where('batch_id', $request->Class)
                        ->where('type', $request->cattype)
                        ->where('name', $m)
                        ->get();

                    if($assessment->sum('value') > 0)
                    {
                        $avg = ($assessment->sum('value') / $assessment->count());
                        $chart = array(
                            'group_name' => $act->category_name,
                            'name' => $m,
                            'value' =>  number_format($avg,2)
                        );
                        array_push($data, $chart);
                    }
                }

            }
            return \response()->json($data);
        }
        elseif($request->AcademicLevel != null && $request->month_name == null  && $request->Class != null && $request->Section != null && $request->cattype != null && $request->CategoryID != null && $request->categoryActivity == null  && $request->duration == "monthly" && $request->type == "details")
        {

            $activity = CadetPerformanceActivity::where('cadet_category_id', $request->cattype)->get();
            foreach ($activity as $act)
            {
                foreach ($monthName as $m)
                {
                    $assessment = CadetChartView::where('cadet_performance_activity_id', $act->id)
                        ->where('institute_id', $request->instituteId)
                        ->where('campus_id', $request->campusId)
                        ->where('academics_year_id', $request->AcademicYear)
                        ->where('academics_level_id', $request->AcademicLevel)
                        ->where('section_id', $request->Section)
                        ->where('batch_id', $request->Class)
                        ->where('type', $request->cattype)
                        ->where('name', $m)
                        ->get();

                    if($assessment->sum('value') > 0)
                    {
                        $avg = ($assessment->sum('value') / $assessment->count());
                        $chart = array(
                            'group_name' => $act->activity_name,
                            'name' => $m,
                            'value' =>  number_format($avg,2)
                        );
                        array_push($data, $chart);
                    }
                }

            }
            return \response()->json($data);
        }
        elseif($request->AcademicLevel != null && $request->month_name == null  && $request->Class != null && $request->Section != null && $request->cattype != null && $request->CategoryID != null && $request->categoryActivity != null && $request->duration == "monthly" && $request->type == "details")
        {
            $activity = CadetPerformanceActivity::find($request->categoryActivity);

            foreach ($monthName as $m)
            {
                $assessment = CadetChartView::where('cadet_performance_activity_id', $request->categoryActivity)
                    ->where('institute_id', $request->instituteId)
                    ->where('campus_id', $request->campusId)
                    ->where('academics_year_id', $request->AcademicYear)
                    ->where('academics_level_id', $request->AcademicLevel)
                    ->where('section_id', $request->Section)
                    ->where('batch_id', $request->Class)
                    ->where('type', $request->cattype)
                    ->where('name', $m)
                    ->get();

                if($assessment->sum('value') > 0)
                {
                    $avg = ($assessment->sum('value') / $assessment->count());
                    $chart = array(
                        'group_name' => $activity->activity_name,
                        'name' => $m,
                        'value' =>  number_format($avg,2)
                    );
                    array_push($data, $chart);
                }
            }

            return \response()->json($data);
        }
        elseif($request->AcademicLevel != null && $request->month_name == null  && $request->Class != null && $request->Section != null && $request->cattype != null && $request->CategoryID == null && $request->categoryActivity == null && $request->duration == "monthly" && $request->type == "summary")
        {
            $activity = CadetPerformanceType::find($request->cattype);
            foreach ($monthName as $m)
            {
                $assessment = CadetChartView::where('institute_id', $request->instituteId)
                    ->where('campus_id', $request->campusId)
                    ->where('academics_year_id', $request->AcademicYear)
                    ->where('academics_level_id', $request->AcademicLevel)
                    ->where('section_id', $request->Section)
                    ->where('batch_id', $request->Class)
                    ->where('type', $request->cattype)
                    ->where('name', $m)
                    ->get();

                if($assessment->sum('value') > 0)
                {
                    $avg = ($assessment->sum('value') / $assessment->count());
                    $chart = array(
                        'group_name' => $activity->performance_type,
                        'name' => $m ,
                        'value' =>  number_format($avg,2)
                    );
                    array_push($data, $chart);
                }
            }
            return \response()->json($data);
        }
        elseif($request->AcademicLevel != null && $request->month_name == null  && $request->Class != null && $request->Section != null && $request->cattype != null && $request->CategoryID != null && $request->categoryActivity == null && $request->duration == "monthly" && $request->type == "summary")
        {
            $activity = CadetPerformanceCategory::find($request->CategoryID);
            foreach ($monthName as $m) {
                $assessment = CadetChartView::where('institute_id', $request->instituteId)
                    ->where('campus_id', $request->campusId)
                    ->where('academics_year_id', $request->AcademicYear)
                    ->where('academics_level_id', $request->AcademicLevel)
                    ->where('section_id', $request->Section)
                    ->where('batch_id', $request->Class)
                    ->where('type', $request->cattype)
                    ->where('performance_category_id', $request->CategoryID)
                    ->where('name', $m)
                    ->get();

                if ($assessment->sum('value') > 0) {
                    $avg = ($assessment->sum('value') / $assessment->count());
                    $chart = array(
                        'group_name' => $activity->category_name,
                        'name' => $m,
                        'value' => number_format($avg, 2)
                    );
                    array_push($data, $chart);
                }
            }
            return \response()->json($data);
        }
        elseif($request->AcademicLevel != null && $request->month_name == null  && $request->Class != null && $request->Section != null && $request->cattype != null && $request->CategoryID != null && $request->categoryActivity != null && $request->duration == "monthly" && $request->type == "summary")
        {
            $activity = CadetPerformanceActivity::find($request->categoryActivity);
            foreach ($monthName as $m)
            {
                $assessment = CadetChartView::where('institute_id', $request->instituteId)
                    ->where('campus_id', $request->campusId)
                    ->where('academics_year_id', $request->AcademicYear)
                    ->where('academics_level_id', $request->AcademicLevel)
                    ->where('section_id', $request->Section)
                    ->where('batch_id', $request->Class)
                    ->where('type', $request->cattype)
                    ->where('performance_category_id', $request->CategoryID)
                    ->where('cadet_performance_activity_id', $request->categoryActivity)
                    ->where('name', $m)
                    ->get();

                if($assessment->sum('value') > 0)
                {
                    $avg = ($assessment->sum('value') / $assessment->count());
                    $chart = array(
                        'group_name' => $activity->activity_name,
                        'name' => $m,
                        'value' =>  number_format($avg,2)
                    );
                    array_push($data, $chart);
                }
            }

            return \response()->json($data);
        }

        return \response()->json($request);
    }


    private function ArrayShort()
    {
        $args = func_get_args();
        $data = array_shift($args);
        foreach ($args as $n => $field) {
            if (is_string($field)) {
                $tmp = array();
                foreach ($data as $key => $row)
                    $tmp[$key] = $row[$field];
                $args[$n] = $tmp;
            }
        }
        $args[] = &$data;
        call_user_func_array('array_multisort', $args);
        return array_pop($args);
    }

}
