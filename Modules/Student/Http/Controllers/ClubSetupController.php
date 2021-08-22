<?php

namespace Modules\Student\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Academics\Entities\PhysicalRoom;
use Modules\Academics\Entities\PhysicalRoomAllocation;
use Modules\Academics\Entities\PhysicalRoomCategory;
use Modules\Academics\Entities\PhysicalRoomCatType;
use Modules\Academics\Entities\PhysicalRoomStudent;
use Modules\Academics\Entities\Section;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\Student\Entities\StudentActivityDirectoryActivity;
use Modules\Student\Entities\StudentActivityDirectoryCategory;
use Modules\Student\Entities\StudentInformation;
use Modules\Student\Entities\StudentProfileView;
use Modules\Student\Entities\StudentsActivitySchedule;
use Modules\Student\Entities\StudentsActivityScheduleComment;

class ClubSetupController extends Controller
{
    private $academicHelper;

    public function __construct(AcademicHelper $academicHelper)
    {
        $this->academicHelper = $academicHelper;
    }


    public function index($id = "")
    {
        $roleId = Auth::user()->role()->id;
        $roomCategoriesId = PhysicalRoomCategory::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
            'cat_type' => 1
        ])->pluck('id');

        if ($roleId == 6) {
            $rooms = PhysicalRoom::whereIn('category_id', $roomCategoriesId)->get();
        } else if ($roleId == 3) {
            $rooms = StudentInformation::where('user_id', Auth::id())->first()->physcialRoomsClub($roomCategoriesId);
        } else {
            $rooms = EmployeeInformation::where('user_id', Auth::id())->first()->physcialRoomsClub($roomCategoriesId);
        }

        if (sizeof($rooms) > 0) {
            if ($id) {
                $room = PhysicalRoom::with('activities')->findOrFail($id);
            } else {
                $room = $rooms[0];
            }
        } else {
            return abort(404);
        }


        if (!$rooms->where('id', $room->id)->first()) {
            return abort(404);
        }

        $fullAccess = true;
        $commentOnly = false;

        if ($roleId == 3) {
            foreach ($room->prefectStudents() as $student) {
                if ($student->user_id == Auth::id()) {
                    $fullAccess = true;
                    $commentOnly = false;
                } else {
                    $fullAccess = false;
                    $commentOnly = true;
                }
            }
        }

        $sectionsId = Section::with('singleBatch')->where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->pluck('id');

        $userId = Auth::id();
        $allocation = $room->latestAllocation();
        $sections = Section::with('singleBatch')->where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->get();
        $students = StudentProfileView::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->whereIn('section', $sectionsId)->get();
        $activityCategories = StudentActivityDirectoryCategory::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->get();
        $employees = EmployeeInformation::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute()
        ])->get();

        return view('student::pages.club-setup.index', compact('userId', 'rooms', 'room', 'allocation', 'sections', 'students', 'activityCategories', 'employees', 'fullAccess', 'commentOnly'));
    }



    public function create()
    {
        return view('student::create');
    }



    public function store(Request $request)
    {
        //
    }



    public function show($id)
    {
        return view('student::show');
    }



    public function edit($id)
    {
        return view('student::edit');
    }



    public function update(Request $request, $id)
    {
        //
    }



    public function destroy($id)
    {
        //
    }


    public function saveActivitySchedule(Request $request, $id)
    {
        if (isset($request->dates)) {
            DB::beginTransaction();
            try {
                foreach ($request->dates as $key => $date) {
                    $date = Carbon::parse($date . $request->times[$key]);
                    $currentDateString = Carbon::today()->toDateString();
                    $currentDate = Carbon::parse($currentDateString);

                    if ($date < $currentDate) {
                        return abort(404);
                    }

                    $schedule = StudentsActivitySchedule::where([
                        'campus_id' => $this->academicHelper->getCampus(),
                        'institute_id' => $this->academicHelper->getInstitute(),
                        'room_id' => $id
                    ])->whereDate('schedule', $date->format('Y-m-d'))->first();

                    if ($schedule) {
                        // Update in table
                        $schedule->update([
                            'room_id' => $id,
                            'activity_id' => ($request->activities) ? $request->activities[$key] : null,
                            'schedule' => $date,
                            'details' => ($request->details) ? $request->details[$key] : null,
                            'updated_at' => Carbon::now(),
                            'updated_by' => Auth::id()
                        ]);

                        $sheduleId = $schedule->id;
                    } else {
                        // Insert into table
                        $sheduleId = StudentsActivitySchedule::insertGetId([
                            'room_id' => $id,
                            'activity_id' => ($request->activities) ? $request->activities[$key] : null,
                            'schedule' => $date,
                            'details' => ($request->details) ? $request->details[$key] : null,
                            'created_at' => Carbon::now(),
                            'created_by' => Auth::id(),
                            'campus_id' => $this->academicHelper->getCampus(),
                            'institute_id' => $this->academicHelper->getInstitute(),
                        ]);
                    }


                    $comment = StudentsActivitySchedule::findOrFail($sheduleId)->comments->where('created_by', Auth::id())->first();

                    if ($comment) {
                        $comment->update([
                            'comment' => $request->comments[$key],
                            'updated_at' => Carbon::now(),
                            'updated_by' => Auth::id(),
                        ]);
                    } else {
                        StudentsActivityScheduleComment::insert([
                            'activity_schedule_id' => $sheduleId,
                            'comment' => $request->comments[$key],
                            'created_at' => Carbon::now(),
                            'created_by' => Auth::id(),
                            'campus_id' => $this->academicHelper->getCampus(),
                            'institute_id' => $this->academicHelper->getInstitute(),
                        ]);
                    }
                }

                DB::commit();
                Session::flash('message', 'Success! Activity Schedule saved successfully.');
                return redirect()->back();
            } catch (\Exception $e) {
                DB::rollback();
                Session::flash('errorMessage', 'Sorry! Error saving Activity Schedule.');
                return redirect()->back();
            }
        }
    }

    public function searchActivitySchedule(Request $request)
    {
        $startDateTime = new Carbon($request->startDate);
        $endDateTime = new Carbon($request->endDate);

        $result = StudentsActivitySchedule::with('comments')->where('room_id', $request->room_id)->whereDate('schedule', '>=', $startDateTime)->whereDate('schedule', '<=', $endDateTime)->get();

        return $result;
    }

    public function addClubActivity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'required',
            'name' => 'required',
            'remarks' => 'required',
        ]);

        if ($validator->passes()) {
            DB::beginTransaction();
            try {
                $taskDone = StudentActivityDirectoryActivity::insert([
                    'student_activity_directory_category_id' => $request->category,
                    'room_id' => $request->room,
                    'activity_name' => $request->name,
                    'remarks' => $request->remarks,
                    'created_at' => Carbon::now(),
                    'created_by' => Auth::id(),
                    'campus' => session()->get('campus'),
                    'institute' => session()->get('institute')
                ]);

                if ($taskDone) {
                    DB::commit();
                    Session::flash('message', 'Club Activity added successfully.');
                    return redirect()->back();
                } else {
                    Session::flash('errorMessage', 'Error Creating Club Activity.');
                    return redirect()->back();
                }
            } catch (\Exception $e) {
                DB::rollback();
                Session::flash('errorMessage', 'Error Creating Club Activity.');
                return redirect()->back();
            }
        } else {
            Session::flash('errorMessage', 'Sorry! Please Input Valid Data.');
            return redirect()->back();
        }
    }

    public function editClubEmployee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employees' => 'required',
        ]);

        if ($validator->passes()) {
            $room = PhysicalRoom::findOrfail($request->room);
            DB::beginTransaction();
            try {
                if ($room->employees()->sync($request->employees)) {
                    DB::commit();
                    Session::flash('message', 'Success! HR/FM updated successfully.');
                    return redirect()->back();
                } else {
                    Session::flash('errorMessage', 'Sorry! Error updating HR/FM.');
                    return redirect()->back();
                }
            } catch (\Exception $e) {
                DB::rollback();
                Session::flash('errorMessage', 'Sorry! Error updating HR/FM.');
                return redirect()->back();
            }
        } else {
            Session::flash('errorMessage', 'Sorry! Please Input Valid Data.');
            return redirect()->back();
        }
    }

    public function clubActivityHistory($id)
    {
        $activitySchedules = StudentsActivitySchedule::with('activity')->where([
            ['campus_id', $this->academicHelper->getCampus()],
            ['institute_id', $this->academicHelper->getInstitute()],
            ['room_id', $id],
            ['activity_id', '!=', null]
        ])->latest()->get();

        return view('student::pages.club-setup.modal.history', compact('activitySchedules'));
    }

    public function updateClubStudents(Request $request)
    {
        $room = PhysicalRoom::findOrFail($request->roomId);
        if (isset($request->cadets)) {
            if (($room->rows * $room->cols * $room->cadets_per_seat) < sizeof($request->cadets)) {
                Session::flash('message', 'Sorry! Room capacity exceeded.');
                return redirect()->back();
            }
        }

        DB::beginTransaction();
        try {
            if ($request->allocationId) {
                PhysicalRoomStudent::where('allocation_id', $request->allocationId)->delete();
                $allocationId = $request->allocationId;
            } else {
                $allocationId = PhysicalRoomAllocation::insertGetId([
                    'physical_room_id' => $room->id,
                    'created_at' => Carbon::now(),
                    'created_by' => Auth::id(),
                    'campus_id' => session()->get('campus'),
                    'institute_id' => session()->get('institute')
                ]);
            }

            $sections = Section::whereIn('id', $request->sections)->get();
            foreach ($sections as $section) {
                PhysicalRoomStudent::insert([
                    'allocation_id' => $allocationId,
                    'physical_room_id' => $room->id,
                    'section_id' => $section->id,
                    'created_at' => Carbon::now(),
                    'created_by' => Auth::id(),
                    'campus_id' => session()->get('campus'),
                    'institute_id' => session()->get('institute'),
                ]);
            }

            if (isset($request->cadets)) {
                $cadets = StudentProfileView::whereIn('std_id', $request->cadets)->get();
                foreach ($cadets as $cadet) {
                    $prefect = null;
                    if ($request->prefects) {
                        if (in_array($cadet->std_id, $request->prefects)) {
                            $prefect = 1;
                        }
                    }

                    PhysicalRoomStudent::insert([
                        'allocation_id' => $allocationId,
                        'physical_room_id' => $room->id,
                        'section_id' => $cadet->section,
                        'student_information_id' => $cadet->std_id,
                        'prefect' => $prefect,
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'campus_id' => session()->get('campus'),
                        'institute_id' => session()->get('institute'),
                    ]);
                }
            }

            DB::commit();
            Session::flash('message', 'Success! Cadets updated successfully.');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error updating cadets.');
            return redirect()->back();
        }
    }
}
