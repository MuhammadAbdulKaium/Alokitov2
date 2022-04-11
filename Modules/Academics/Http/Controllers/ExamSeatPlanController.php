<?php

namespace Modules\Academics\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Carbon\Carbon;
use DateTime;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Modules\Academics\Entities\Batch;
use Modules\Academics\Entities\ExamCategory;
use Modules\Academics\Entities\ExamMarkParameter;
use Modules\Academics\Entities\ExamName;
use Modules\Academics\Entities\ExamSchedule;
use Modules\Academics\Entities\ExamSeatPlan;
use Modules\Academics\Entities\PhysicalRoom;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\Student\Entities\StudentProfileView;
use PDF;
use App;
use App\Helpers\UserAccessHelper;
use Modules\Academics\Entities\AcademicsYear;
use Modules\Academics\Entities\Semester;
use Modules\Setting\Entities\Institute;

class ExamSeatPlanController extends Controller
{
    private $academicHelper;
    use UserAccessHelper;

    public function __construct(AcademicHelper $academicHelper)
    {
        $this->academicHelper = $academicHelper;
    }


    public function index(Request $request)
    {
        $pageAccessData = self::linkAccess($request);

        $examCategories = ExamCategory::all();
        $academicYears = $this->academicHelper->getAllAcademicYears();
        $semesters = Semester::all();

        return view('academics::exam.exam-seatPlan', compact('pageAccessData', 'examCategories', 'academicYears', 'semesters'));
    }


    public function create()
    {
        return view('academics::create');
    }


    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        return view('academics::show');
    }


    public function edit($id)
    {
        return view('academics::edit');
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }


    public function examFromExamCategory(Request $request)
    {
        if ($request->examCategoryId) {
            return ExamCategory::findOrFail($request->examCategoryId)->examNames;
        } else {
            return [];
        }
    }

    public function searchExamSeat(Request $request)
    {
        $examName = ExamName::findOrFail($request->examId);

        $physicalRooms = PhysicalRoom::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->get();

        $batches = Batch::whereIn('id', $examName->classes)->get();

        $students = StudentProfileView::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->get();

        $examSchedules = ExamSchedule::with('subject')->where([
            'exam_id' => $examName->id
        ])->get();

        $criterias = ExamMarkParameter::all()->keyBy('id');

        $date = Carbon::parse($request->date)->format('Y-m-d');
        $fromTime = date('H:i:s', strtotime($request->fromTime));
        $toTime = date('H:i:s', strtotime($request->toTime));

        // Previous Seat Plan
        $previousSeatPlan = ExamSeatPlan::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'exam_id' => $examName->id,
            'date' => $date,
            'from_time' => $fromTime,
            'to_time' => $toTime,
        ])->first();

        if (!$previousSeatPlan) {
            $checkTime = ExamSeatPlan::where([
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
                'date' => $date,
            ])->whereBetween('from_time', [$fromTime, $toTime])
                ->orWhereBetween('to_time', [$fromTime, $toTime])->where([
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                    'date' => $date,
                ])->first();

            if ($checkTime) {
                return 1;
            }
        } else {
            $html = view('academics::exam.snippets.exam-seat-config', compact('physicalRooms', 'batches', 'students', 'examSchedules', 'examName', 'date', 'fromTime', 'toTime', 'previousSeatPlan', 'criterias'))->render();
            return [2, $html, $previousSeatPlan->seat_plan, $previousSeatPlan->employee_ids];
        }

        $html = view('academics::exam.snippets.exam-seat-config', compact('physicalRooms', 'batches', 'students', 'examSchedules', 'examName', 'date', 'fromTime', 'toTime', 'previousSeatPlan', 'criterias'))->render();
        return [3, $html];
    }

    public function scheduleWiseCriteriaFromSubject(Request $request)
    {
        $examSchedule = ExamSchedule::where([
            'exam_id' => $request->examId,
            'batch_id' => $request->batchId,
            'subject_id' => $request->subjectId,
        ])->first();

        $schedules = json_decode($examSchedule->schedules, 1);
        $criteriaIds = [];

        foreach ($schedules as $key => $schedule) {
            // if ($schedule['date'] == $request->date && $schedule['startTime'] == $request->fromTime && $schedule['endTime'] == $request->toTime) {
            array_push($criteriaIds, $key);
            // }
        }

        if (sizeof($criteriaIds) > 0) {
            return ExamMarkParameter::whereIn('id', $criteriaIds)->get();
        } else {
            return [];
        }
    }

    public function getStudentsFromSections(Request $request)
    {
        if ($request->sectionIds) {
            return StudentProfileView::where([
                'campus' => $this->academicHelper->getCampus(),
                'institute' => $this->academicHelper->getInstitute(),
            ])->whereIn('section', $request->sectionIds)->get()->groupBy('batch')->all();
        } else {
            return [];
        }
    }

    public function getSeatPlanView(Request $request)
    {
        $seatPlans = json_decode($request->seatPlan, 1);
        $physicalRooms = PhysicalRoom::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->get();
        $batches = Batch::get();
        $students = StudentProfileView::with('singleUser', 'singleBatch', 'singleSection')->where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->get();
        $totalStudents = $request->totalStudents;
        $employees = EmployeeInformation::with('singleUser', 'singleDepartment', 'singleDesignation')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get()->keyBy('id');
        $selectedEmployeeIds = $request->employeeIds;

        $canCheckInvigilatorHistory = $request->canCheckInvigilatorHistory;
        $canSave = $request->canSave;
        $canPrint = $request->canPrint;

        return view('academics::exam.snippets.exam-seat-plan-view', compact('seatPlans', 'physicalRooms', 'batches', 'totalStudents', 'students', 'employees', 'selectedEmployeeIds', 'canCheckInvigilatorHistory', 'canSave', 'canPrint'))->render();
    }

    public function saveSeatPlan(Request $request)
    {
        $examName = ExamName::findOrFail($request->examId);
        $semester = Semester::findOrFail($request->termId);
        $academicYear = AcademicsYear::findOrFail($request->yearId);
        $date = Carbon::parse($request->date);
        $fromTime = date('H:i', strtotime($request->fromTime));
        $toTime = date('H:i', strtotime($request->toTime));

        $previousSeatPlan = ExamSeatPlan::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'exam_id' => $examName->id,
            'date' => $date,
            'from_time' => $fromTime,
            'to_time' => $toTime,
        ])->first();

        // DB::beginTransaction();
        // try {
        if ($previousSeatPlan) {
            $previousSeatPlan->update([
                'employee_ids' => $request->employeeIds,
                'physical_room_ids' => $request->roomIds,
                'batch_ids' => $request->batchIds,
                'section_ids' => $request->sectionIds,
                'batch_with_subjects' => $request->batchesWithSubject,
                'seat_plan' => $request->seatPlan,
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id(),
            ]);
        } else {
            ExamSeatPlan::insert([
                'academic_year_id' => $academicYear->id,
                'semester_id' => $semester->id,
                'exam_id' => $examName->id,
                'employee_ids' => $request->employeeIds,
                'date' => $date->format('Y-m-d'),
                'from_time' => $fromTime,
                'to_time' => $toTime,
                'physical_room_ids' => $request->roomIds,
                'batch_ids' => $request->batchIds,
                'section_ids' => $request->sectionIds,
                'batch_with_subjects' => $request->batchesWithSubject,
                'seat_plan' => $request->seatPlan,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ]);
        }

        //     DB::commit();
        //     return "Success";
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return $e;
        // }
    }

    public function printSeatPlan(Request $request)
    {
        $institute = Institute::findOrFail($this->academicHelper->getInstitute());

        $seatPlans = json_decode($request->seatPlan, 1);
        $physicalRooms = PhysicalRoom::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->get();
        $batches = Batch::get();
        $students = StudentProfileView::with('singleUser', 'singleBatch', 'singleSection')->where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->get();
        $totalStudents = $request->totalStudents;
        $employees = EmployeeInformation::with('singleUser', 'singleDepartment', 'singleDesignation')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get()->keyBy('id');
        $selectedEmployeeIds = json_decode($request->employeeIds, 1);
        $exam = ExamName::with('ExamCategory')->findOrFail($request->examId);
        $date = $request->date;
        $fromTime = $request->fromTime;
        $toTime = $request->toTime;

        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('academics::exam.exam-seat-plan-pdf', compact('exam', 'date', 'fromTime', 'toTime', 'institute', 'seatPlans', 'physicalRooms', 'batches', 'totalStudents', 'students', 'employees', 'selectedEmployeeIds'))->setPaper('a4', 'portrait');
        return $pdf->stream();
    }

    public function invigilatorHistory($yearId, $termId, $examId, $ids)
    {
        if ($ids == "null") {
            return "No Invigilators Selected";
        }
        $examName = ExamName::findOrFail($examId);
        $semester = Semester::findOrFail($termId);
        $academicYear = AcademicsYear::findOrFail($yearId);
        $employeeIds = json_decode($ids, 1);

        $employees = EmployeeInformation::whereIn('id', $employeeIds)->get()->keyBy('id');
        $rooms = PhysicalRoom::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->get()->keyBy('id');

        $semesters = Semester::where([
            'academic_year_id' => $academicYear->id
        ])->get()->keyBy('id');

        $exams = ExamName::all()->keyBy('id');

        $examSeatPlans = ExamSeatPlan::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'academic_year_id' => $academicYear->id,
        ])->latest()->get()->groupBy('exam_id');

        return view('academics::exam.modal.invigilator-history', compact('academicYear', 'semesters', 'exams', 'examSeatPlans', 'employees', 'rooms', 'employeeIds'));
    }
}
