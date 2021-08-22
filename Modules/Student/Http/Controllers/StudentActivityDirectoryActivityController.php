<?php

namespace Modules\Student\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Contracts\Support\Renderable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Student\Entities\StudentActivityDirectoryActivity;
use Modules\Student\Entities\StudentActivityDirectoryCategory;
use App\Http\Requests;
use Illuminate\Support\Facades\Validator;
use Session;
use Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Academics\Entities\PhysicalRoom;
use Modules\Student\Entities\StudentsActivitySchedule;
use Modules\Student\Entities\TaskSchedule;
use Modules\Student\Entities\UserType;
use Modules\Student\Http\Requests\StudentActivityDirectoryActivityRequest;

class StudentActivityDirectoryActivityController extends Controller
{
    private $academicHelper;

    public function __construct(AcademicHelper $academicHelper)
    {
        $this->academicHelper = $academicHelper;
    }


    public function index()
    {
        $categories = StudentActivityDirectoryCategory::with('userTypes')->where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->get();
        $activities = StudentActivityDirectoryActivity::with('studentActivityDirectoryCategories')->where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->get();
        $rooms = PhysicalRoom::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->get();
        $userTypes = UserType::all();

        return view('student::pages.student-activity-directory.index', compact('categories', 'activities', 'rooms', 'userTypes'));
    }



    public function create()
    {
        return view('student::create');
    }



    public function store(StudentActivityDirectoryActivityRequest $request)
    {
        $sameNameActivity = StudentActivityDirectoryActivity::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
            'activity_name' => $request->activityName
        ])->get();

        if (sizeOf($sameNameActivity) > 0) {
            Session::flash('errorMessage', 'Sorry! There is already an activity in this name.');
            return redirect()->back();
        }

        DB::beginTransaction();
        try {
            $insertActivity = StudentActivityDirectoryActivity::insert([
                'student_activity_directory_category_id' => $request->cmbCategory,
                'room_id' => $request->room_id,
                'activity_name' => $request->activityName,
                'remarks' => $request->activityRemarks,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'campus' => session()->get('campus'),
                'institute' => session()->get('institute')
            ]);

            if ($insertActivity) {
                DB::commit();
                Session::flash('message', 'Activity created Successfully!.');
                return redirect()->back();
            } else {
                Session::flash('errorMessage', 'Error creating activity.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();

            Session::flash('errorMessage', 'Error creating activity.');
            return redirect()->back();
        }
    }



    public function show($id)
    {
        return view('student::show');
    }



    public function edit($id)
    {
        $activity = StudentActivityDirectoryActivity::findOrFail($id);
        $categories = StudentActivityDirectoryCategory::where([
            'campus' => session()->get('campus'),
            'institute' => session()->get('institute')
        ])->get();
        $rooms = PhysicalRoom::latest()->get();

        return view('student::pages.student-activity-directory.modal.edit', compact('activity', 'categories', 'rooms'));
    }



    public function update(Request $request, $id)
    {
        $activity = StudentActivityDirectoryActivity::findOrFail($id);

        $sameNameActivity = StudentActivityDirectoryActivity::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
            'activity_name' => $request->activityName
        ])->first();

        if ($sameNameActivity) {
            if ($sameNameActivity->id != $activity->id) {
                Session::flash('errorMessage', 'Sorry! There is already an activity in this name.');
                return redirect()->back();
            }
        }

        DB::beginTransaction();
        try {
            $updateActivity = $activity->update([
                'student_activity_directory_category_id' => $request->cmbCategory,
                'room_id' => $request->room_id,
                'activity_name' => $request->activityName,
                'remarks' => $request->activityRemarks,
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ]);

            if ($updateActivity) {
                DB::commit();
                Session::flash('message', 'Activity updated Successfully!.');
                return redirect()->back();
            } else {
                Session::flash('errorMessage', 'Error updating activity.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();

            Session::flash('errorMessage', 'Error updating activity.');
            return redirect()->back();
        }
    }



    public function destroy($id)
    {
        $activity = StudentActivityDirectoryActivity::findOrFail($id);

        $activitySchedule = StudentsActivitySchedule::where('activity_id', $id)->first();
        $taskSchedule = TaskSchedule::where('student_activity_id', $id)->first();

        if ($activitySchedule || $taskSchedule) {
            Session::flash('errorMessage', 'Can not delete activity, dependencies found.');
            return redirect()->back();
        } else {
            $activity->delete();
            Session::flash('message', 'Deleted Successfully!.');
            return redirect()->back();
        }
    }
}
