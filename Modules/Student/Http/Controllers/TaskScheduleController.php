<?php

namespace Modules\Student\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Modules\Student\Entities\StudentActivityDirectoryCategory;
use Modules\Student\Entities\TaskSchedule;
use Modules\Student\Entities\TaskScheduleDate;
use Modules\Student\Http\Requests\TaskScheduleDateRequest;
use Modules\Student\Http\Requests\TaskScheduleRequest;

class TaskScheduleController extends Controller
{
    private $academicHelper;

    public function __construct(AcademicHelper $academicHelper)
    {
        $this->academicHelper = $academicHelper;
    }



    public function index($id = null)
    {
        $taskScheduleDates = TaskScheduleDate::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();

        $activityCategories = StudentActivityDirectoryCategory::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->get();

        $taskSchedules = TaskSchedule::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();

        $taskSchedule = null;
        if ($id) {
            $taskSchedule = TaskSchedule::findOrFail($id);
        }

        return view('student::pages.task-schedule.index', compact('taskScheduleDates', 'activityCategories', 'taskSchedules', 'taskSchedule'));
    }



    public function create()
    {
        return view('student::create');
    }



    public function store(TaskScheduleDateRequest $request)
    {
        $sameNameDate = TaskScheduleDate::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'name' => $request->name
        ])->get();

        if (sizeOf($sameNameDate) > 0) {
            Session::flash('errorMessage', 'Sorry! There is already a task shedule date in this name.');
            return redirect()->back();
        }

        DB::beginTransaction();
        try {
            $insertTaskScheduleDate = TaskScheduleDate::insert([
                'name' => $request->name,
                'start_date' => Carbon::parse($request->fromDate),
                'expected_date' => Carbon::parse($request->expectedDate),
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ]);

            if ($insertTaskScheduleDate) {
                DB::commit();
                Session::flash('message', 'Task Schedule Date created successfully.');
                return redirect()->back();
            } else {
                Session::flash('errorMessage', 'Error Creating task schedule date.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error Creating task schedule date.');
            return redirect()->back();
        }
    }



    public function show($id)
    {
        return view('student::show');
    }



    public function edit($id)
    {
        $taskScheduleDate = TaskScheduleDate::findOrFail($id);

        return view('student::pages.task-schedule.modal.edit-schedule-date', compact('taskScheduleDate'));
    }



    public function update(Request $request, $id)
    {
        $taskScheduleDate = TaskScheduleDate::findOrFail($id);
        $sameNameDate = TaskScheduleDate::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'name' => $request->name
        ])->first();

        if ($sameNameDate && $sameNameDate->id != $taskScheduleDate->id) {
            Session::flash('errorMessage', 'Sorry! There is already a task shedule date in this name.');
            return redirect()->back();
        }

        DB::beginTransaction();
        try {
            $updateTaskScheduleDate = $taskScheduleDate->update([
                'name' => $request->name,
                'start_date' => Carbon::parse($request->fromDate),
                'expected_date' => Carbon::parse($request->expectedDate),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ]);

            if ($updateTaskScheduleDate) {
                DB::commit();
                Session::flash('message', 'Task Schedule Date updated successfully.');
                return redirect()->back();
            } else {
                Session::flash('errorMessage', 'Error updating task schedule date.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error updating task schedule date.');
            return redirect()->back();
        }
    }



    public function destroy($id)
    {
        $taskScheduleDate = TaskScheduleDate::findOrFail($id);
        $taskSchedule = TaskSchedule::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'task_schedule_date_id' => $id
        ])->first();

        if ($taskSchedule) {
            Session::flash('errorMessage', 'Dependencies found can not delete.');
            return redirect()->back();
        } else {
            $taskScheduleDate->delete();
            Session::flash('message', 'Schedule date deleted successfully.');
            return redirect()->back();
        }
    }

    // Ajax Methods Starts
    public function getActivitiesFromActivityCategory(Request $request)
    {
        if ($request->categoryId) {
            $activityCategory = StudentActivityDirectoryCategory::findOrFail($request->categoryId);
            return $activityCategory->studentActivityDirectoryActivities;
        } else {
            return [];
        }
    }
    // Ajax Methods Ends


    public function createTaskSchedule(TaskScheduleRequest $request)
    {
        if ($request->differentThu && $request->eventType == 1) {
            $times = [
                'days' => $request->days,
                'startTime' => $request->startTime,
                'endTime' => $request->endTime,
                'thuStartTime' => $request->thuStartTime,
                'thuEndTime' => $request->thuEndTime,
            ];
        } else if ($request->eventType == 3) {
            $times = [
                'days' => $request->days,
                'weekNumber' => $request->weekNumber,
                'startTime' => $request->startTime,
                'endTime' => $request->endTime
            ];
        } else {
            $times = [
                'days' => $request->days,
                'startTime' => $request->startTime,
                'endTime' => $request->endTime
            ];
        }

        DB::beginTransaction();
        try {
            if ($request->taskScheduleId) {
                $taskSchedule = TaskSchedule::findOrFail($request->taskScheduleId);

                $updateTaskSchedule = $taskSchedule->update([
                    'task_schedule_date_id' => $request->taskScheduleDateId,
                    'student_activity_category_id' => $request->activityCategoryId,
                    'student_activity_id' => $request->activityId,
                    'event_type' => $request->eventType,
                    'different_thursday' => $request->differentThu,
                    'times' => json_encode($times),
                    'extra_note' => $request->extraNote,
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id()
                ]);

                if ($updateTaskSchedule) {
                    DB::commit();
                    Session::flash('message', 'Task Schedule updated successfully.');
                    return redirect()->back();
                } else {
                    Session::flash('errorMessage', 'Error updating task schedule.');
                    return redirect()->back();
                }
            } else {
                $insertTaskSchedule = TaskSchedule::insert([
                    'task_schedule_date_id' => $request->taskScheduleDateId,
                    'student_activity_category_id' => $request->activityCategoryId,
                    'student_activity_id' => $request->activityId,
                    'event_type' => $request->eventType,
                    'different_thursday' => $request->differentThu,
                    'times' => json_encode($times),
                    'extra_note' => $request->extraNote,
                    'created_at' => Carbon::now(),
                    'created_by' => Auth::id(),
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                ]);

                if ($insertTaskSchedule) {
                    DB::commit();
                    Session::flash('message', 'Task Schedule created successfully.');
                    return redirect()->back();
                } else {
                    Session::flash('errorMessage', 'Error creating task schedule.');
                    return redirect()->back();
                }
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error saving task schedule.');
            return redirect()->back();
        }
    }

    public function deleteTaskSchedule($id)
    {
        $taskSchedule = TaskSchedule::findOrFail($id);
        if ($taskSchedule) {
            $taskSchedule->delete();
            Session::flash('message', 'Task schedule deleted successfully.');
            return redirect()->back();
        } else {
            Session::flash('errorMessage', 'Error deleting task schedule.');
            return redirect()->back();
        }
    }


    // View Task Schedule Methods
    public function viewTaskSchedule()
    {
        $taskScheduleDates = TaskScheduleDate::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();

        return view('student::pages.task-schedule.view-task-schedule', compact('taskScheduleDates'));
    }

    public function searchTaskScheduleTable(Request $request)
    {
        $taskSchedules = TaskSchedule::with('activityCategory', 'activity')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'task_schedule_date_id' => $request->taskScheduleDateId
        ])->get();

        $dailyEvents = $taskSchedules->where('event_type', 1)->groupBy('student_activity_category_id')->all();
        $weeklyEvents = $taskSchedules->where('event_type', 2)->groupBy('student_activity_category_id')->all();
        $monthlyEvents = $taskSchedules->where('event_type', 3)->groupBy('student_activity_category_id')->all();

        return view('student::pages.task-schedule.task-schedule-table', compact('dailyEvents', 'weeklyEvents', 'monthlyEvents'))->render();
    }
}
