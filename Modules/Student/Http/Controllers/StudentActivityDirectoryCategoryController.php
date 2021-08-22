<?php

namespace Modules\Student\Http\Controllers;

use AuthenticatesUsers;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Student\Entities\StudentActivityDirectoryCategory;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Validator;
use Session;
use Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Student\Entities\UserType;
use Modules\Student\Http\Requests\StudentActivityDirectoryCategoryRequest;

class StudentActivityDirectoryCategoryController extends Controller
{
    private $academicHelper;

    public function __construct(AcademicHelper $academicHelper)
    {
        $this->academicHelper = $academicHelper;
    }


    public function index()
    {
    }



    public function create()
    {
        return view('student::create');
    }



    public function store(StudentActivityDirectoryCategoryRequest $request)
    {
        $sameNameCategory = StudentActivityDirectoryCategory::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
            'category_name' => $request->categoryName
        ])->get();

        if (sizeOf($sameNameCategory) > 0) {
            Session::flash('errorMessage', 'Sorry! There is already a activity category in this name.');
            return redirect()->back();
        }

        DB::beginTransaction();
        try {
            $activityCategoryId = StudentActivityDirectoryCategory::insertGetId([
                'category_name' => $request->categoryName,
                'remarks' => $request->remarks,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'campus' => $this->academicHelper->getCampus(),
                'institute' => $this->academicHelper->getInstitute(),
            ]);

            $activityCategory = StudentActivityDirectoryCategory::findOrFail($activityCategoryId);
            $activityCategory->userTypes()->attach($request->cadetHrFm);

            if ($activityCategoryId) {
                DB::commit();
                Session::flash('message', 'Activity category created successfully!.');
                return redirect()->back();
            } else {
                Session::flash('errorMessage', 'Error creating activity category.');
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
        $activityCategory = StudentActivityDirectoryCategory::with('userTypes')->findOrFail($id);
        $userTypes = UserType::all();

        return view('student::pages.student-activity-directory.modal.edit-category', compact('activityCategory', 'userTypes'));
    }



    public function update(StudentActivityDirectoryCategoryRequest $request, $id)
    {
        $activityCategory = StudentActivityDirectoryCategory::findOrFail($id);

        $sameNameCategory = StudentActivityDirectoryCategory::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
            'category_name' => $request->categoryName
        ])->first();

        if ($sameNameCategory) {
            if ($sameNameCategory->id != $activityCategory->id) {
                Session::flash('errorMessage', 'Sorry! There is already a physical room category in this name.');
                return redirect()->back();
            }
        }

        DB::beginTransaction();
        try {
            $activityCategory->update([
                'category_name' => $request->categoryName,
                'remarks' => $request->remarks,
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ]);

            if ($activityCategory->userTypes()->sync($request->cadetHrFm)) {
                DB::commit();
                Session::flash('message', 'Activity category updated successfully!.');
                return redirect()->back();
            } else {
                Session::flash('errorMessage', 'Error updating activity category.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();

            Session::flash('errorMessage', 'Error updating activity category.');
            return redirect()->back();
        }
    }



    public function destroy($id)
    {
        $category = StudentActivityDirectoryCategory::findOrfail($id);

        if (sizeof($category->studentActivityDirectoryActivities) > 0) {
            Session::flash('errorMessage', 'Dependencies Found!.');
            return redirect()->back();
        } else {
            $category->delete();

            Session::flash('message', 'Deleted Successfully!.');
            return redirect()->back();
        }
    }
    public function getAjaxActivityByCategory($id)
    {
        $activities = StudentActivityDirectoryActivity::where([
            'campus' => session()->get('campus'),
            'institute' => session()->get('institute'),
            'student_activity_directory_category_id' => $id,
        ])->get();
        $data = [];
        if ($activities->count() > 0) {
            array_push($data, '<option value="">-- Select --</option>');
            foreach ($activities as $item) {
                array_push($data, '<option value="' . $item->id . '" data-point="' . $item->id . '">' . $item->activity_name . ' Room ' . $item->room_id . '</option>');
            }
        }

        return json_encode($data);
    }
}
