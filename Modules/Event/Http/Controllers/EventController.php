<?php

namespace Modules\Event\Http\Controllers;

use carbon\carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Controllers\Helpers\AcademicHelper;
use CadetEventsTable;
use Illuminate\Support\Carbon as SupportCarbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Modules\Academics\Entities\PhysicalRoom;
use Modules\Academics\Entities\Section;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\Event\Entities\Event;
use Modules\Setting\Entities\CadetPerformanceActivity;
use Modules\Setting\Entities\CadetPerformanceCategory;
use Modules\Setting\Entities\CadetPerformanceType;
use Modules\Student\Entities\StudentProfileView;
use Validator;
use Modules\Event\Http\Requests\EventRequest;
use Illuminate\Support\Facades\Auth;
use Modules\Academics\Entities\AcademicsYear;
use Modules\Academics\Entities\Batch;
use Modules\Event\Entities\EventMark;
use Modules\Event\Entities\EventTeam;
use Modules\House\Entities\House;
use Modules\House\Entities\RoomStudent;
use Modules\Student\Entities\CadetAssesment;

class EventController extends Controller
{
    private $academicHelper;

    public function __construct(AcademicHelper $academicHelper)
    {
        $this->academicHelper  = $academicHelper;
    }


    public function index()
    {
        $employees = EmployeeInformation::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();
        $events = Event::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->get();

        return view('event::index', compact('employees', 'events'));
    }



    public function create()
    {
        $employees = EmployeeInformation::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();
        $categories = CadetPerformanceType::all();

        return view('event::modal.event', compact('categories', 'employees'));
    }



    public function store(EventRequest $request)
    {
        $sameNameEvent = Event::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
            'event_name' => $request->event_name
        ])->get();

        if (sizeOf($sameNameEvent) > 0) {
            Session::flash('errorMessage', 'Sorry! There is already a event in this name.');
            return redirect()->back();
        }

        $data = $request->employees;
        $judge = json_encode($data);

        DB::beginTransaction();
        try {
            $event = Event::create([
                'event_name' => $request->event_name,
                'category_id' => $request->category,
                'sub_category_id' => $request->sub_category,
                'activity_id' => $request->activity,
                'status' => $request->status,
                'remarks' => $request->remarks,
                'employee_id' => $judge,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'campus' => $this->academicHelper->getCampus(),
                'institute' => $this->academicHelper->getInstitute(),
            ]);
            if ($event) {
                DB::commit();
                Session::flash('message', 'New Event created successfully.');
                return redirect()->back();
            } else {
                Session::flash('errorMessage', 'Error creating Event.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error creating Event.');
            return redirect()->back();
        }
    }



    public function show($id)
    {
        return view('event::show');
    }



    public function edit($id)
    {
        $event = Event::findOrFail($id);
        $employees = EmployeeInformation::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();
        $categories = CadetPerformanceType::all();
        $sub_categories = CadetPerformanceCategory::where('category_type_id', '=', $event->category_id)->get();
        $activities = CadetPerformanceActivity::where('cadet_category_id', '=', $event->sub_category_id)->get();

        return view('event::modal.edit', compact('event', 'employees', 'categories', 'sub_categories', 'activities'));
    }



    public function update(EventRequest $request, $id)
    {
        $event = Event::findOrFail($id);
        $sameNameEvent = Event::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
            'event_name' => $request->event_name
        ])->first();

        if ($sameNameEvent) {
            if ($sameNameEvent->id != $event->id) {
                Session::flash('errorMessage', 'Sorry! There is already a event in this name.');
                return redirect()->back();
            }
        }

        $data = $request->employees;
        $judge = json_encode($data);
        DB::beginTransaction();
        try {
            $eventUpdate = $event->update([
                'event_name' => $request->event_name,
                'category_id' => $request->category,
                'sub_category_id' => $request->sub_category,
                'activity_id' => $request->activity,
                'status' => $request->status,
                'remarks' => $request->remarks,
                'employee_id' => $judge,
                'updated_at' => carbon::now(),
                'updated_by' => Auth::id(),
                'campus' => $this->academicHelper->getCampus(),
                'institute' => $this->academicHelper->getInstitute(),
            ]);
            if ($eventUpdate) {
                DB::commit();
                Session::flash('message', 'Success! Event updated successfully.');
                return redirect()->back();
            } else {
                Session::flash('errorMessage', 'Sorry! Error updating Event.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Sorry! Error updating Event.');
            return redirect()->back();
        }
    }



    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        $eventMark = EventMark::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'event_id' => $event->id,
        ])->first();

        if ($eventMark) {
            Session::flash('errorMessage', 'Event date assigned to this event, can not delete.');
            return redirect()->back();
        } else {
            $event->deleted_by = Auth::id();
            $event->deleted_at = Carbon::now();
            $event->save();
            $event->delete();

            Session::flash('message', 'Deleted Successfully.');
            return redirect()->back();
        }
    }

    //Assign date for event
    public function assignDate($id)
    {
        $event = Event::findOrFail($id);
        $houses = House::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();
        $batches = Batch::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->get();

        $previousDates = EventMark::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'event_id' => $id,
        ])->get();
        $previousDatesGrouped = $previousDates->groupBy('date_time')->all();

        return view('event::modal.assignDate', compact('event', 'houses', 'batches', 'previousDatesGrouped'));
    }

    public function searchStudents(Request $request)
    {
        if ($request->sections) {
            return StudentProfileView::where([
                'campus' => $this->academicHelper->getCampus(),
                'institute' => $this->academicHelper->getInstitute(),
            ])->whereIn('section', $request->sections)->get();
        } else {
            return [];
        }
    }

    public function saveEventDate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'eventId' => 'required',
            'dateTime' => 'required',
            'venue' => 'required',
            'teamIds' => 'required'
        ]);

        if ($validator->passes()) {
            $event = Event::findOrFail($request->eventId);
            $eventMark = EventMark::where([
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
                'event_id' => $request->eventId,
                'date_time' => $request->dateTime
            ])->whereNotNull('mark')->first();

            if ($eventMark) {
                Session::flash('errorMessage', 'Marks assigned for students performed in this date, can not update this event.');
                return redirect()->back();
            } else {
                if ($request->previousDateTime) {
                    EventMark::where([
                        'campus_id' => $this->academicHelper->getCampus(),
                        'institute_id' => $this->academicHelper->getInstitute(),
                        'event_id' => $request->eventId,
                        'date_time' => $request->previousDateTime,
                    ])->delete();
                }
                $previousEventMark = EventMark::where([
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                    'event_id' => $request->eventId,
                    'date_time' => $request->dateTime,
                ])->first();
                if ($previousEventMark) {
                    Session::flash('errorMessage', 'Cannot set two event at the same moment.');
                    return redirect()->back();
                }

                DB::beginTransaction();
                try {
                    foreach ($request->teamIds as $teamId) {
                        EventMark::insert([
                            'event_id' => $request->eventId,
                            'performance_type_id' => $event->category->id,
                            'performance_category_id' => $event->sub_category->id,
                            'date_time' => $request->dateTime,
                            'venue' => $request->venue,
                            'team_id' => $teamId,
                            'created_at' => Carbon::now(),
                            'created_by' => Auth::id(),
                            'campus_id' => $this->academicHelper->getCampus(),
                            'institute_id' => $this->academicHelper->getInstitute(),
                        ]);
                    }

                    DB::commit();
                    Session::flash('message', 'Event Date Saved Successfully.');
                    return redirect()->back();
                } catch (\Exception $e) {
                    DB::rollback();
                    Session::flash('errorMessage', 'Error Saving Event Date.');
                    return redirect()->back();
                }
            }
        } else {
            Session::flash('errorMessage', 'Please fill up all the fields.');
            return redirect()->back();
        }
    }

    public function editEventTeam($id)
    {
        $event = Event::with('teams')->findOrFail($id);

        $houses = House::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();
        $batches = Batch::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->get();

        return view('event::modal.event-teams', compact('event', 'houses', 'batches'));
    }

    public function updateEventTeam(Request $request)
    {
        $team = EventTeam::findOrFail($request->teamId);

        DB::beginTransaction();
        try {
            $updateTeam = $team->update([
                'name' => $request->teamName,
                'house_id' => $request->houseId,
                'batch_id' => $request->batchId,
                'section_id' => $request->sectionId,
                'students' => json_encode($request->studentIds),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ]);

            if ($updateTeam) {
                DB::commit();
                Session::flash('message', 'Team updated successfully.');
                return redirect()->back();
            } else {
                Session::flash('errorMessage', 'Error updating team.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error updating team.');
            return redirect()->back();
        }
    }


    // Events Ajax Methods start
    public function getAjaxTypeCategory($id)
    {
        $data = [];
        array_push($data, '<option value="">-- Select --</option>');

        if ($id == 6) {
            $sub_category = CadetPerformanceCategory::where('id', 19)->get();
        } else {
            $sub_category = CadetPerformanceCategory::where('category_type_id', $id)->get();
        }
        if ($sub_category->count() > 0) {

            foreach ($sub_category as $item) {
                array_push($data, '<option value="' . $item->id . '" data-point="' . $item->id . '">' . $item->category_name . '</option>');
            }
        }

        return json_encode($data);
    }


    //Get activity from sub-cat
    public function getAjaxCategoryActivity($id)
    {
        $data = [];

        $activity = CadetPerformanceActivity::where('cadet_category_id', $id)->get();

        if ($activity->count() > 0) {
            array_push($data, '<option value="">-- Select --</option>');
            foreach ($activity as $item) {
                array_push($data, '<option value="' . $item->id . '" data-point="' . $item->id . '">' . $item->activity_name . '</option>');
            }
        }
        return json_encode($data);
    }

    public function getStudentsFromHouse(Request $request)
    {
        $studentIds = RoomStudent::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'house_id' => $request->houseId
        ])->pluck('student_id');

        return StudentProfileView::whereIn('std_id', $studentIds)->get();
    }

    public function getSectionsStudentsFromBatch(Request $request)
    {
        $sections = Section::where('batch_id', $request->batchId)->get();
        $students = StudentProfileView::where('batch', $request->batchId)->get();

        return [$sections, $students];
    }

    public function getStudentsFromSection(Request $request)
    {
        return StudentProfileView::where('section', $request->sectionId)->get();
    }


    public function addTeam(Request $request)
    {
        DB::beginTransaction();
        try {
            $teamId = EventTeam::insertGetId([
                'event_id' => $request->eventId,
                'name' => $request->teamName,
                'house_id' => $request->houseId,
                'batch_id' => $request->batchId,
                'section_id' => $request->sectionId,
                'students' => json_encode($request->studentIds),
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ]);

            if ($teamId) {
                DB::commit();
                return $teamId;
            } else {
                return 0;
            }
        } catch (\Exception $e) {
            DB::rollback();
            return 0;
        }
    }

    public function removeEventDate(Request $request)
    {
        $eventMark = EventMark::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'event_id' => $request->eventId,
            'date_time' => $request->dateTime
        ])->whereNotNull('mark')->first();

        if ($eventMark) {
            return 2;
        } else {
            EventMark::where([
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
                'event_id' => $request->eventId,
                'date_time' => $request->dateTime
            ])->delete();
        }

        return 1;
    }

    public function deleteEventTeam(Request $request)
    {
        $team = EventTeam::findOrFail($request->teamId);

        $eventMark = EventMark::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'team_id' => $team->id
        ])->first();

        if ($eventMark) {
            return 2;
        } else {
            $team->delete();
            return 1;
        }
    }
    // Events Ajax Methods end




    // Event Marks Methods
    public function eventMarks()
    {
        $employeeId = EmployeeInformation::where('user_id', Auth::id())->value('id');
        if (!$employeeId) {
            $employeeId = 0;
        }

        $events = Event::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
            'status' => 1,
            ['employee_id', 'like', '%' . $employeeId . '%']
        ])->get();

        return view('event::event-marks', compact('events'));
    }

    public function saveStudentsEventMarks(Request $request)
    {
        $event = Event::findOrFail($request->eventId);
        $team = EventTeam::findOrFail($request->teamId);
        $studentIds = EventTeam::where('id', $request->teamId)->value('students');

        $previousMarks = EventMark::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'event_id' => $request->eventId,
            'date_time' => $request->dateTime,
            'team_id' => $team->id,
            'created_by' => Auth::id()
        ])->whereNotNull('mark')->get();

        DB::beginTransaction();
        try {
            if (sizeof($previousMarks) > 0) {
                foreach ($previousMarks as $previousMark) {
                    $previousMark->update([
                        'mark' => $request->marks[$previousMark->student_id],
                        'remarks' => $request->remarks[$previousMark->student_id],
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth::id(),
                    ]);

                    // Insert into cadet assessment starts   
                    // Data Generating start
                    $academicYear = AcademicsYear::where([
                        'campus_id' => $this->academicHelper->getCampus(),
                        'institute_id' => $this->academicHelper->getInstitute(),
                    ])->whereDate('start_date', '<=', $request->dateTime)
                        ->whereDate('end_date', '>=', $request->dateTime)
                        ->first();
                    $student = StudentProfileView::where('std_id', $previousMark->student_id)->first();
                    $batch = $student->batch();
                    $section = $student->section();
                    $acadmeicLevel = $batch->academicsLevel();
                    $activity = CadetPerformanceActivity::findOrFail($event->activity->id);
                    $activityPoints = $activity->activityPoint;
                    $point = EventMark::where([
                        'campus_id' => $this->academicHelper->getCampus(),
                        'institute_id' => $this->academicHelper->getInstitute(),
                        'event_id' => $request->eventId,
                        'date_time' => $request->dateTime,
                        'student_id' => $previousMark->student_id,
                    ])->avg('mark');
                    $activityPointId = $activityPoints->firstWhere('point', round($point))->id;

                    $previousAssessment = CadetAssesment::where([
                        'student_id' => $previousMark->student_id,
                        'campus_id' => $this->academicHelper->getCampus(),
                        'institute_id' => $this->academicHelper->getInstitute(),
                        'academics_year_id' => $academicYear->id,
                        'academics_level_id' => $acadmeicLevel->id,
                        'date' => $request->dateTime,
                        'cadet_performance_category_id' => $event->sub_category->id,
                        'cadet_performance_activity_id' => $event->activity->id,
                        'type' => $event->category->id,
                    ])->first();
                    // Data Generating end           
                    $previousAssessment->update([
                        'cadet_performance_activity_point_id' => $activityPointId,
                        'remarks' => $request->remarks[$previousMark->student_id],
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth::id(),
                    ]);
                    // Insert into cadet assessment ends
                }
            } else {
                foreach (json_decode($studentIds) as $studentId) {
                    EventMark::insert([
                        'event_id' => $request->eventId,
                        'performance_type_id' => $event->category->id,
                        'performance_category_id' => $event->sub_category->id,
                        'date_time' => $request->dateTime,
                        'team_id' => $team->id,
                        'house_id' => $team->house_id,
                        'batch_id' => $team->batch_id,
                        'section_id' => $team->section_id,
                        'student_id' => $studentId,
                        'mark' => $request->marks[$studentId],
                        'remarks' => $request->remarks[$studentId],
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'campus_id' => $this->academicHelper->getCampus(),
                        'institute_id' => $this->academicHelper->getInstitute(),
                    ]);

                    // Insert into cadet assessment starts   
                    // Data Generating start
                    $academicYear = AcademicsYear::where([
                        'campus_id' => $this->academicHelper->getCampus(),
                        'institute_id' => $this->academicHelper->getInstitute(),
                    ])->whereDate('start_date', '<=', $request->dateTime)
                        ->whereDate('end_date', '>=', $request->dateTime)
                        ->first();
                    $student = StudentProfileView::where('std_id', $studentId)->first();
                    $batch = $student->batch();
                    $section = $student->section();
                    $acadmeicLevel = $batch->academicsLevel();
                    $activity = CadetPerformanceActivity::findOrFail($event->activity->id);
                    $activityPoints = $activity->activityPoint;
                    $point = EventMark::where([
                        'campus_id' => $this->academicHelper->getCampus(),
                        'institute_id' => $this->academicHelper->getInstitute(),
                        'event_id' => $request->eventId,
                        'date_time' => $request->dateTime,
                        'student_id' => $studentId,
                    ])->avg('mark');
                    $activityPointId = $activityPoints->firstWhere('point', round($point))->id;
                    // Data Generating end           
                    CadetAssesment::insert([
                        'student_id' => $studentId,
                        'campus_id' => $this->academicHelper->getCampus(),
                        'institute_id' => $this->academicHelper->getInstitute(),
                        'academics_year_id' => $academicYear->id,
                        'academics_level_id' => $acadmeicLevel->id,
                        'section_id' => $section->id,
                        'batch_id' => $batch->id,
                        'date' => $request->dateTime,
                        'cadet_performance_category_id' => $event->sub_category->id,
                        'cadet_performance_activity_id' => $event->activity->id,
                        'cadet_performance_activity_point_id' => $activityPointId,
                        'performance_category_id' => $event->sub_category->id,
                        'type' => $event->category->id,
                        'remarks' => $request->remarks[$studentId],
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                    ]);
                    // Insert into cadet assessment ends
                }
            }
            DB::commit();
            Session::flash('message', 'Event Marks Saved Successfully.');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error saving event marks.');
            return redirect()->back();
        }
    }

    // Event Marks Ajax Methods starts
    public function dateTimeFromEvent(Request $request)
    {
        $dateTime = EventMark::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'event_id' => $request->eventId,
        ])->whereNull('mark')->get();

        return array_keys($dateTime->groupBy('date_time')->all());
    }

    function teamFromDateTime(Request $request)
    {
        $teamIds = EventMark::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'event_id' => $request->eventId,
            'date_time' => $request->dateTime,
        ])->whereNull('mark')->pluck('team_id');

        return EventTeam::whereIn('id', $teamIds)->get();
    }

    public function studentSearchForMarks(Request $request)
    {
        $event = Event::findOrFail($request->eventId);
        $dateTime = $request->dateTime;
        $team = EventTeam::findOrFail($request->teamId);
        $activityPoints = $event->activity->activityPoint;

        $studentIds = EventTeam::where('id', $request->teamId)->value('students');
        $students = StudentProfileView::whereIn('std_id', json_decode($studentIds))->get();

        $previousMarks = EventMark::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'event_id' => $event->id,
            'date_time' => $dateTime,
            'team_id' => $team->id,
            'created_by' => Auth::id()
        ])->whereNotNull('mark')->get();

        return view('event::event-marks-std-list', compact('event', 'dateTime', 'team', 'students', 'activityPoints', 'previousMarks'))->render();
    }
    // Event Marks Ajax Methods ends

}
