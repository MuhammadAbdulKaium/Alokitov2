<?php

namespace Modules\Student\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Carbon\Carbon;
use Modules\Setting\Entities\CadetAssessmentActivity;
use Modules\Setting\Entities\CadetPerformanceActivity;
use Modules\Setting\Entities\CadetPerformanceActivityPoint;
use Modules\Setting\Entities\CadetPerformanceCategory;
use Modules\Setting\Entities\CadetPerformanceType;
use Modules\Student\Entities\CadetAssesment;
use Modules\Student\Entities\CadetChartView;
use Redirect;
use Session;
use Validator;

class CadetGraphController extends Controller
{
    public function AjaxBarChart($type, $categoryid, $suid)
    {
        $data = [];
        // dd($suid);
        $bar = CadetChartView::where('student_id', $suid)
            ->where('performance_category_id', $categoryid)
            ->orderBy('group_name', 'ASC')
            ->get();

//        $year = CadetChartView::where('student_id', $suid)
//            ->where('performance_category_id', $categoryid)
//            ->orWhere('date', 'like', '%' . 2014 . '%')
//            ->count();
//
//        echo $year;


        foreach ($bar as $item)
        {
            $chart = array(
                'group_name' => $item->group_name,
                'name' => date('Y', strtotime($item->date)),
                'value' => $item->value
            );
            array_push($data, $chart);
        }

        return json_encode($data);
    }

    public function PostLandingGraph(Request $request)
    {
//        return \response()->json($request);
        $data = [];
        $activity = [];

        $firstDay = "";
        $LastDay = "";
        $monthName = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

        $first = CadetChartView::where('student_id', $request->student_id)
            ->orderBy('date', 'ASC')
            ->first();

        $last = CadetChartView::where('student_id', $request->student_id)
            ->orderBy('date', 'DESC')
            ->first();
        if($request->month_name != null)
        {
            $firstDay =$request->month_name.'-01-01';
            $LastDay = $request->month_name.'-12-31';
        }

        if($request->category_id != null && $request->duration == "yearly" && $request->type == "details" && $request->month_name == null &&  $request->activity_id == null)
        {
            $category = CadetPerformanceCategory::where('category_type_id', $request->category_id )->orderBy('category_name', 'ASC')->get();

            $totalYear = date('Y', strtotime($last->date)) - date('Y', strtotime($first->date));
            $year = date('Y', strtotime($first->date));

            for ($i = 0; $i <= $totalYear; $i++) {
                $sdate = strval($year).'-01-01';
                $edate =strval($year).'-12-31';


                foreach ($category as $cat)
                {
                    $activitySum = CadetChartView::where('student_id', $request->student_id)
                        ->whereBetween('date', [$sdate, $edate])
                        ->where('performance_category_id', $cat->id)
                        ->orderBy('group_name', 'ASC')
                        ->get();

                    if($activitySum->sum('value') > 0)
                    {
                        $avg = ($activitySum->sum('value') / $activitySum->count());
                        $chart = array(
                            'group_name' => $cat->category_name,
                            'name' => strval($year),
                            'value' => round($avg)
                        );
                        array_push($data, $chart);
                    }
                }
                $year++;
            }

            return \response()->json($this->ArrayShort($data, 'group_name', SORT_ASC, 'name', SORT_DESC));
        }
        elseif ($request->category_id != null && $request->duration == "yearly" && $request->type == "details" && $request->month_name == null &&  $request->activity_id != null)
        {
            $bar = CadetChartView::where('student_id', $request->student_id)
                ->whereIn('performance_category_id', $request->activity_id)
                ->orderBy('group_name', 'ASC')
                ->get();

            foreach ($bar as $item)
            {
                if($item->group_name != null)
                {
                    $chart = array(
                        'group_name' => $item->group_name,
                        'name' => date('Y', strtotime($item->date)),
                        'value' => $item->value
                    );
                    array_push($data, $chart);
                }
            }
            return \response()->json($this->ArrayShort($data, 'group_name', SORT_ASC, 'name', SORT_DESC));
        }
        elseif ($request->category_id != null && $request->duration == "monthly" && $request->type == "details" && $request->month_name != null && $request->activity_id == null)
        {
            $bar = CadetChartView::where('student_id', $request->student_id)
                ->whereBetween('date', [$firstDay, $LastDay])
                ->where('type', $request->category_id)
                ->orderBy('date', 'ASC')
                ->get();
//            return \response()->json($bar);

            foreach ($bar as $item)
            {
                if($item->group_name != null)
                {
                    if($bar->sum('value') > 0)
                    {
//                        $avg = ($bar->sum('value') / $bar->count());
                        $chart = array(
                            'group_name' => $item->group_name,
                            'name' => $item->name,
                            'value' => $item->value
                        );
                        array_push($data, $chart);
                    }
                }
            }
            return \response()->json($this->ArrayShort($data, 'group_name', SORT_DESC , 'name', SORT_DESC));
        }
        elseif ($request->category_id != null && $request->duration == "monthly" && $request->type == "details" && $request->month_name != null && $request->activity_id != null)
        {
//            return \response()->json($request);
            $category = CadetPerformanceCategory::whereIn('id', $request->activity_id)->get();
            foreach ($category as $cate)
            {
                $bar = CadetChartView::where('student_id', $request->student_id)
                    ->where('performance_category_id', $cate->id)
                    ->whereBetween('date', [$firstDay, $LastDay])
                    ->orderBy('group_name', 'ASC')
                    ->get();

                foreach ($bar as $item)
                {
                    if($item->group_name != null)
                    {
                        if($bar->sum('value') > 0)
                        {
                            $avg = ($bar->sum('value') / $bar->count());
                            $chart = array(
                                'group_name' => $item->category_name,
                                'name' => $item->name,
                                'value' => round($avg)
                            );
                            array_push($data, $chart);
                        }
                    }
                }
            }

            return \response()->json($this->ArrayShort($data, 'group_name', SORT_ASC, 'name', SORT_DESC));
        }
        elseif ($request->category_id != null && $request->duration == "yearly" && $request->type == "summary" && $request->month_name == null && $request->activity_id == null)
        {
            $category = CadetPerformanceType::where('id', $request->category_id )->first();

            $totalYear = date('Y', strtotime($last->date)) - date('Y', strtotime($first->date));
            $year = date('Y', strtotime($first->date));

            for ($i = 0; $i <= $totalYear; $i++) {
                $sdate = strval($year).'-01-01';
                $edate =strval($year).'-12-31';

                    $activitySum = CadetChartView::where('student_id', $request->student_id)
                        ->whereBetween('date', [$sdate, $edate])
                        ->where('performance_category_id', $request->category_id )
                        ->orderBy('group_name', 'ASC')
                        ->get();

                    if($activitySum->sum('value') > 0)
                    {
                        $avg = ($activitySum->sum('value') / $activitySum->count());
                        $chart = array(
                            'group_name' => $category->performance_type,
                            'name' => strval($year),
                            'value' => round($avg)
                        );
                        array_push($data, $chart);
                    }
                $year++;
            }

            return \response()->json($this->ArrayShort($data, 'group_name', SORT_ASC, 'name', SORT_DESC));
        }
        elseif ($request->category_id != null && $request->duration == "monthly" && $request->type == "summary" && $request->month_name != null && $request->activity_id == null)
        {
//            return \response()->json("hi");

                $category = CadetPerformanceCategory::where('category_type_id', $request->category_id)->get();

            foreach ($category as $item)
            {
                    foreach ($monthName as $m)
                    {
                    $bar = CadetChartView::where('student_id', $request->student_id)
                        ->whereBetween('date', [$firstDay, $LastDay])
                        ->where('performance_category_id', $item->id)
                        ->whereNotNull('group_name')
                        ->where('name', $m)
                        ->orderBy('date', 'ASC')
                        ->get();

//                        return \response()->json($bar);

//                    if($item->group_name != null)
//                    {
                        if($bar->sum('value') > 0)
                        {
                        $avg = ($bar->sum('value') / $bar->count());
                            $chart = array(
                                'group_name' => $item->category_name,
                                'name' => $m,
                                'value' => number_format($avg, 2)
                            );
                            array_push($data, $chart);
                        }
//                    }
                }
            }

            return \response()->json($this->ArrayShort($data, 'group_name', SORT_DESC , 'name', SORT_DESC));
        }
        elseif ($request->category_id != null && $request->duration == "yearly" && $request->type == "summary" && $request->month_name == null &&  $request->activity_id != null)
        {
            $totalYear = date('Y', strtotime($last->date)) - date('Y', strtotime($first->date));
            $year = date('Y', strtotime($first->date));

            for ($i = 0; $i <= $totalYear; $i++) {
                $sdate = strval($year).'-01-01';
                $edate =strval($year).'-12-31';

                $activitySum = CadetChartView::where('student_id', $request->student_id)
                    ->whereBetween('date', [$sdate, $edate])
                    ->whereIn('performance_category_id', $request->activity_id)
                    ->orderBy('group_name', 'ASC')
                    ->get();

                if($activitySum->sum('value') > 0)
                {
                    $avg = ($activitySum->sum('value') / $activitySum->count());
                    $chart = array(
                        'group_name' => strval($year),
                        'name' => "",
                        'value' => round($avg)
                    );
                    array_push($data, $chart);
                }
                $year++;
            }

            return \response()->json($this->ArrayShort($data, 'group_name', SORT_ASC, 'name', SORT_DESC));
        }
        elseif ($request->category_id != null && $request->duration == "monthly" && $request->type == "summary" && $request->month_name != null &&  $request->activity_id != null)
        {
//            return \response()->json($request);

            $category = CadetPerformanceCategory::whereIn('id', $request->activity_id)->get();
            foreach ($category as $cate)
            {
                foreach ($monthName as $m)
                {
                    $activitySum = CadetChartView::where('student_id', $request->student_id)
                        ->whereBetween('date', [$firstDay, $LastDay])
                        ->where('performance_category_id', $cate->id)
                        ->where('name', $m)
                        ->get();

                    if($activitySum->sum('value') > 0)
                    {
                        $avg = ($activitySum->sum('value') / $activitySum->count());
                        $chart = array(
                            'group_name' => $cate->category_name,
                            'name' => $m,
                            'value' => round($avg)
                        );
                        array_push($data, $chart);
                    }
                }


            }

            return \response()->json($this->ArrayShort($data, 'group_name', SORT_ASC, 'name', SORT_DESC));
        }



        return \response()->json("No Data Found");
    }

    public function PostActivityGraph(Request $request)
    {
//        return \response()->json($request);
        $data = [];
        $activity = [];
        $monthName = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

        $firstDay = "";
        $LastDay = "";

        $first = CadetChartView::where('student_id', $request->student_id)
            ->orderBy('date', 'ASC')
            ->first();

        $last = CadetChartView::where('student_id', $request->student_id)
            ->orderBy('date', 'DESC')
            ->first();

        if($request->month_name != null)
        {
            $firstDay = $request->month_name.'-01-01';
            $LastDay = $request->month_name.'-12-31';
        }

        if ($request->category != null && $request->duration == "yearly" && $request->month_name == null && $request->type == "details"  && $request->activity_id == null)
        {
            $activity = CadetPerformanceActivity::where('cadet_category_id', $request->category)->get();

            $totalYear = date('Y', strtotime($last->date)) - date('Y', strtotime($first->date));
            $year = date('Y', strtotime($first->date));

            for ($i = 0; $i <= $totalYear; $i++) {
                $sdate = strval($year).'-01-01';
                $edate =strval($year).'-12-31';


                foreach ($activity as $cat)
                {
                    $activitySum = CadetChartView::where('student_id', $request->student_id)
                        ->whereBetween('date', [$sdate, $edate])
                        ->where('cadet_performance_activity_id', $cat->id)
                        ->orderBy('date', 'DESC')
                        ->get();

                    if($activitySum->sum('value') > 0)
                    {
                        $avg = ($activitySum->sum('value') / $activitySum->count());
                        $chart = array(
                            'group_name' => $cat->activity_name,
                            'name' => strval($year),
                            'value' => number_format($avg, 2)
                        );
                        array_push($data, $chart);
                    }
                }
                $year++;
            }
//            array_multisort($data,SORT_ASC ,SORT_NUMERIC);
            return \response()->json($this->ArrayShort($data, 'name', SORT_DESC, 'group_name', SORT_DESC));
        }
        elseif ($request->category != null && $request->duration == "yearly" && $request->month_name == null && $request->type == "summary" && $request->activity_id == null)
        {
//            return \response()->json($request);
            $totalYear = date('Y', strtotime($last->date)) - date('Y', strtotime($first->date));
            $year = date('Y', strtotime($first->date));

            for ($i = 0; $i <= $totalYear; $i++) {
                $sdate = strval($year).'-01-01';
                $edate =strval($year).'-12-31';

                    $activitySum = CadetChartView::where('student_id', $request->student_id)
                        ->whereBetween('date', [$sdate, $edate])
                        ->where('performance_category_id', $request->category)
                        ->where('type', $request->fector_item)
                        ->orderBy('date', 'DESC')
                        ->get();

                    if($activitySum->sum('value') > 0)
                    {
                        $avg = ($activitySum->sum('value') / $activitySum->count());
                        $chart = array(
                            'group_name' => strval($year),
                            'name' => "",
                            'value' => number_format($avg, 2)
                        );
                        array_push($data, $chart);
                }
                $year++;
            }
            return \response()->json($this->ArrayShort($data, 'group_name', SORT_DESC, 'name', SORT_ASC));
        }
        elseif ($request->category != null && $request->duration == "yearly" && $request->month_name == null && $request->type == "details" && $request->activity_id != null)
        {

            $activity = CadetPerformanceActivity::where('cadet_category_id', $request->category)->whereIn('id', $request->activity_id)->get();

            $totalYear = date('Y', strtotime($last->date)) - date('Y', strtotime($first->date));
            $year = date('Y', strtotime($first->date));

            for ($i = 0; $i <= $totalYear; $i++) {
                $sdate = strval($year).'-01-01';
                $edate =strval($year).'-12-31';


                foreach ($activity as $cat)
                {
                    $activitySum = CadetChartView::where('student_id', $request->student_id)
                        ->whereBetween('date', [$sdate, $edate])
                        ->where('cadet_performance_activity_id', $cat->id)
                        ->orderBy('date', 'DESC')
                        ->get();

                    if($activitySum->sum('value') > 0)
                    {
                        $avg = ($activitySum->sum('value') / $activitySum->count());
                        $chart = array(
                            'group_name' => $cat->activity_name,
                            'name' => strval($year),
                            'value' => number_format($avg, 2)
                        );
                        array_push($data, $chart);
                    }
                }
                $year++;
            }
            return \response()->json($this->ArrayShort($data, 'group_name', SORT_ASC, 'name', SORT_DESC));
        }
        elseif ($request->category != null && $request->duration == "monthly" && $request->month_name != null && $request->type == "details" && $request->activity_id == null)
        {
//            return \response()->json($request);
            $activitySum = CadetChartView::where('student_id', $request->student_id)
                ->whereBetween('date', [$firstDay, $LastDay])
                ->where('performance_category_id', $request->category)
                ->where('type', $request->fector_item)
                ->orderBy('date', 'DESC')
                ->get();
            foreach ($activitySum as $cat)
            {
                $chart = array(
                    'group_name' => $cat->group_name,
                    'name' => $cat->name,
                    'value' => $cat->value
                );
                array_push($data, $chart);
            }
            return \response()->json($this->ArrayShort($data, 'group_name', SORT_DESC, 'name', SORT_DESC));
        }
        elseif ($request->category != null && $request->duration == "monthly" && $request->month_name != null && $request->type == "summary" && $request->activity_id == null)
        {
//            return \response()->json('hi');
            foreach ($monthName as $m)
            {
                $activitySum = CadetChartView::where('student_id', $request->student_id)
                    ->whereBetween('date', [$firstDay, $LastDay])
                    ->where('performance_category_id', $request->category)
                    ->where('type', $request->fector_item)
                    ->where('name', $m)
                    ->orderBy('date', 'DESC')
                    ->get();

                if($activitySum->sum('value') > 0)
                {
                    $avg = ($activitySum->sum('value') / $activitySum->count());
                    $chart = array(
                        'group_name' => $m,
                        'name' => "",
                        'value' => round($avg)
                    );
                    array_push($data, $chart);
                }
            }
            return \response()->json($this->ArrayShort($data, 'group_name', SORT_ASC ));
        }
        elseif ($request->category != null && $request->duration == "monthly" && $request->month_name != null && $request->type == "details" && $request->activity_id != null)
        {
            $activity = CadetPerformanceActivity::where('cadet_category_id', $request->category)->whereIn('id', $request->activity_id)->get();

                foreach ($activity as $cat)
                {
                    $activitySum = CadetChartView::where('student_id', $request->student_id)
                        ->whereBetween('date', [$firstDay, $LastDay])
                        ->where('cadet_performance_activity_id', $cat->id)
                        ->orderBy('date', 'DESC')
                        ->get();


                    if($activitySum->sum('value') > 0)
                    {
                        $avg = ($activitySum->sum('value') / $activitySum->count());
                        $chart = array(
                            'group_name' => $cat->activity_name,
                            'name' => $cat->name,
                            'value' => round($avg)
                        );
                        array_push($data, $chart);
                    }
                }
            return \response()->json($this->ArrayShort($data, 'group_name', SORT_ASC, 'name', SORT_DESC));
        }

        return \response()->json("Activity No Data Found");
    }

    public function PostAcadimicGraph(Request $request)
    {
        $data = [];
        $firstDay = "";
        $LastDay = "";

        $first = CadetAssesment::where('student_id', $request->student_id)
            ->orderBy('date', 'ASC')
            ->first();

        $last = CadetAssesment::where('student_id', $request->student_id)
            ->orderBy('date', 'DESC')
            ->first();

        if($request->month_name != null)
        {
            $firstDay = $request->month_name.'-01-01';
            $LastDay = $request->month_name.'-12-31';
        }

        if($request->duration == "yearly" && $request->type == "details" && $request->month_name == null && $request->category != null && $request->activity_id == null)
        {
            $bar = CadetAssesment::where('student_id', $request->student_id)
                ->where('type', $request->category)
//                ->orderBy('date', 'DESC')
                ->get();

            foreach ($bar as $item)
            {
                $chart = array(
                    'group_name' => $item->remarks,
                    'name' => date('Y', strtotime($item->date)),
                    'value' => $item->total_point
                );
                array_push($data, $chart);
            }

            return \response()->json($this->ArrayShort($data, 'group_name', SORT_ASC , 'name', SORT_DESC));
        }
        elseif($request->duration == "yearly" && $request->type == "summary" && $request->month_name == null && $request->category != null && $request->activity_id == null)
        {
            $totalYear = date('Y', strtotime($last->date)) - date('Y', strtotime($first->date));
            $year = date('Y', strtotime($first->date));

            for ($i = 0; $i <= $totalYear; $i++) {
                $sdate = strval($year).'-01-01';
                $edate =strval($year).'-12-31';


                $bar = CadetChartView::where('student_id', $request->student_id)
                    ->where('type', $request->category)
                    ->whereBetween('date', [$sdate, $edate])
                    ->get();

                if($bar->sum('total_point') > 0)
                {
                    $avg = ($bar->sum('total_point') / $bar->count());
                    $chart = array(
                        'group_name' => strval($year),
                        'name' => '',
                        'value' => number_format($avg, 2)
                    );
                    array_push($data, $chart);
                }

                $year++;
            }

            return \response()->json($this->ArrayShort($data, 'group_name', SORT_DESC , 'name', SORT_DESC));
        }
        elseif($request->duration == "yearly" && $request->type == "details" && $request->month_name == null && $request->category != null && $request->activity_id != null)
        {
//            return \response()->json("hi");
            $totalYear = date('Y', strtotime($last->date)) - date('Y', strtotime($first->date));
            $year = date('Y', strtotime($first->date));
            $activity = CadetPerformanceActivity::whereIn('id', $request->activity_id)->get();

            for ($i = 0; $i <= $totalYear; $i++) {
                $sdate = strval($year).'-01-01';
                $edate =strval($year).'-12-31';

                foreach ($activity as $act)
                {
                    $bar = CadetAssesment::where('student_id', $request->student_id)
                        ->join('cadet_assesment_activity', 'cadet_assesment_activity.assessment_id', '=', 'cadet_assesment.id')
                        ->where('type', $request->category)
                        ->where('activity_id', $act->id)
                        ->whereBetween('date', [$sdate, $edate])
                        ->get();

                    foreach ($bar as $var)
                    {
                        $chart = array(
                            'group_name' => $var->remarks,
                            'name' => strval($year),
                            'value' => $var->activity_point
                        );
                        array_push($data, $chart);
                    }

//                    if($bar->sum('total_point') > 0)
//                    {
//                        $avg = ($bar->sum('total_point') / $bar->count());
//
//                    }
                }



                $year++;
            }

            return \response()->json($this->ArrayShort($data, 'group_name', SORT_DESC , 'name', SORT_DESC));
        }
        elseif($request->duration == "yearly" && $request->type == "summary" && $request->month_name == null && $request->category != null && $request->activity_id != null)
        {
//            return \response()->json("hi");
            $totalYear = date('Y', strtotime($last->date)) - date('Y', strtotime($first->date));
            $year = date('Y', strtotime($first->date));
            $activity = CadetPerformanceActivity::whereIn('id', $request->activity_id)->get();

            for ($i = 0; $i <= $totalYear; $i++) {
                $sdate = strval($year).'-01-01';
                $edate =strval($year).'-12-31';

                foreach ($activity as $act)
                {
                    $bar = CadetAssesment::where('student_id', $request->student_id)
                        ->join('cadet_assesment_activity', 'cadet_assesment_activity.assessment_id', '=', 'cadet_assesment.id')
                        ->where('type', $request->category)
                        ->where('activity_id', $act->id)
                        ->whereBetween('date', [$sdate, $edate])
                        ->get();

                    if($bar->sum('total_point') > 0)
                    {
                        $avg = ($bar->sum('total_point') / $bar->count());
                        $chart = array(
                            'group_name' => $act->activity_name,
                            'name' => strval($year),
                            'value' => number_format($avg, 2)
                        );
                        array_push($data, $chart);

                    }
                }



                $year++;
            }

            return \response()->json($this->ArrayShort($data, 'group_name', SORT_ASC , 'name', SORT_DESC));
        }
        elseif($request->duration == "monthly" && $request->type == "details" && $request->month_name != null && $request->category != null && $request->activity_id == null)
        {
            $assessment = CadetAssesment::where('student_id', $request->student_id)
                ->where('type', $request->category)
                ->whereBetween('date', [$firstDay, $LastDay])
                ->orderBy('date', 'DESC')
                ->get();

            foreach ($assessment as $item)
            {
                $subject = CadetAssessmentActivity::where('assessment_id', $item->id)->get();
                foreach ($subject as $sub)
                {
                    $chart = array(
                        'group_name' => $sub->subjectName()->activity_name,
                        'name' => $item->remarks,
                        'value' => $sub->activity_point
                    );
                    array_push($data, $chart);
                }

            }

            return \response()->json($this->ArrayShort($data, 'group_name', SORT_DESC , 'name', SORT_DESC));
        }
        elseif($request->duration == "monthly" && $request->type == "summary" && $request->month_name != null && $request->category != null && $request->activity_id == null)
        {
            $assessment = CadetAssesment::where('student_id', $request->student_id)
                ->where('type', $request->category)
                ->whereBetween('date', [$firstDay, $LastDay])
                ->orderBy('date', 'DESC')
                ->get();

            foreach ($assessment as $item)
            {
//                $subject = CadetAssessmentActivity::where('assessment_id', $item->id)->get();
//                foreach ($subject as $sub)
//                {
                    $chart = array(
                        'group_name' => $item->remarks,
                        'name' => '',
                        'value' => $item->total_point
                    );
                    array_push($data, $chart);
//                }
            }

            return \response()->json($this->ArrayShort($data, 'group_name', SORT_DESC , 'name', SORT_DESC));
        }

        return \response()->json($request);
    }

    public function PostDiameterGraph(Request $request)
    {
        $data =[];
        $depressions=[
            array(
                'start' => 0,
                'end' => 29,
                'state' => 'Normal'
            ),
            array(
                'start' => 30,
                'end' => 100,
                'state' => 'Minimal'
            ),
            array(
                'start' => 101,
                'end' => 114,
                'state' => 'Mild'
            ),
            array(
                'start' => 115,
                'end' => 123,
                'state' => 'Moderate'
            ),
            array(
                'start' => 124,
                'end' => 150,
                'state' => 'Severe'
            ),
        ];

        $anxietys=[
            array(
                'start' => 0,
                'end' => 54,
                'state' => 'Mild'
            ),
            array(
                'start' => 55,
                'end' => 66,
                'state' => 'Moderate'
            ),
            array(
                'start' => 67,
                'end' => 77,
                'state' => 'Severe'
            ),
            array(
                'start' => 78,
                'end' => 135,
                'state' => 'Profound'
            ),
        ];
        $socialAnxietys=[
            array(
                'start' => 0,
                'end' => 20,
                'state' => 'Slightly'
            ),
            array(
                'start' => 21,
                'end' => 40,
                'state' => 'Moderately'
            ),
            array(
                'start' => 41,
                'end' => 60,
                'state' => 'Very Much'
            ),
            array(
                'start' => 61,
                'end' => 80,
                'state' => 'Extremely'
            ),
        ];
        $hopelessness=[
            array(
                'start' => 0,
                'end' => 3,
                'state' => 'None or minimal'
            ),
            array(
                'start' => 4,
                'end' => 8,
                'state' => 'Mild'
            ),
            array(
                'start' => 9,
                'end' => 14,
                'state' => 'Moderate'
            ),
            array(
                'start' => 15,
                'end' => 100,
                'state' => 'Severe'
            ),
        ];
        $OCS=[
            array(
                'start' => 0,
                'end' => 23,
                'state' => 'Mild'
            ),
            array(
                'start' => 24,
                'end' => 40,
                'state' => 'Moderate '
            ),
            array(
                'start' => 41,
                'end' => 49,
                'state' => 'Severe '
            ),
            array(
                'start' => 50,
                'end' => 800,
                'state' => 'Profound '
            ),
        ];

        $student_id=$request->student_id;
        $category_id=$request->category_id;
        $type=$request->type;
        $psycology=CadetAssesment::where('student_id',$student_id)
                                    ->where('performance_category_id',$category_id)
                                    ->where('type',$type)
                                    ->orderBy('id', 'DESC')
                                    ->get();

        foreach ($psycology as $item)
        {
            if($item->performance_category()->category_name == "Depression Scale")
            {
                foreach ($depressions as $key => $depression)
                {

                    if($depression['start'] <= $item->total_point && $depression['end'] >= $item->total_point)
                    {
                        $chart = array(
                            'name' => $item->remarks,
                            'value' => $item->total_point,
                            'state' => $depression['state']
                        );
                        array_push($data, $chart);
                    }
                }
            }
            elseif ($item->performance_category()->category_name == "Anxiety Scale")
            {
                foreach ($anxietys as $key => $depression)
                {

                    if($depression['start'] <= $item->total_point && $depression['end'] >= $item->total_point)
                    {
                        $chart = array(
                            'name' => $item->remarks,
                            'value' => $item->total_point,
                            'state' => $depression['state']
                        );
                        array_push($data, $chart);
                    }
                }
            }
            elseif ($item->performance_category()->category_name == "Social Interaction Anxiety Scale")
            {
                foreach ($socialAnxietys as $key => $depression)
                {

                    if($depression['start'] <= $item->total_point && $depression['end'] >= $item->total_point)
                    {
                        $chart = array(
                            'name' => $item->remarks,
                            'value' => $item->total_point,
                            'state' => $depression['state']
                        );
                        array_push($data, $chart);
                    }
                }
            }
            elseif ($item->performance_category()->category_name == "Obsessive Compulsive Scale")
            {
                foreach ($OCS as $key => $depression)
                {

                    if($depression['start'] <= $item->total_point && $depression['end'] >= $item->total_point)
                    {
                        $chart = array(
                            'name' => $item->remarks,
                            'value' => $item->total_point,
                            'state' => $depression['state']
                        );
                        array_push($data, $chart);
                    }
                }
            }
            elseif ($item->performance_category()->category_name == "Hopelessness")
            {
                foreach ($hopelessness as $key => $depression)
                {

                    if($depression['start'] <= $item->total_point && $depression['end'] >= $item->total_point)
                    {
                        $chart = array(
                            'name' => $item->remarks,
                            'value' => $item->total_point,
                            'state' => $depression['state']
                        );
                        array_push($data, $chart);
                    }
                }
            }
        }

        return \response()->json($data);
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
