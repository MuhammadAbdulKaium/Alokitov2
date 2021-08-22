<?php

namespace Modules\House\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Modules\Academics\Entities\AcademicsLevel;
use Modules\Academics\Entities\AcademicsYear;
use Modules\Academics\Entities\ExamName;
use Modules\Academics\Entities\Section;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\House\Entities\House;
use Modules\House\Entities\HouseHistory;
use Modules\House\Entities\Room;
use Modules\House\Entities\RoomStudent;
use Modules\House\Entities\WeightageConfig;
use Modules\House\Http\Requests\HouseRequest;
use Modules\House\Http\Requests\RoomRequest;
use Modules\Setting\Entities\CadetPerformanceCategory;
use Modules\Student\Entities\StudentProfileView;

class HouseController extends Controller
{
    private $academicHelper;

    public function __construct(AcademicHelper $academicHelper)
    {
        $this->academicHelper = $academicHelper;
    }

    public function index()
    {
        $houses = House::with('rooms')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();

        $rooms = Room::with('house')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();

        $employeeIds = House::with('rooms')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->pluck('employee_id');

        $employees = EmployeeInformation::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->whereNotIn('id', $employeeIds)->get();

        return view('house::index', compact('houses', 'rooms', 'employees'));
    }


    public function create()
    {
        return view('house::create');
    }


    public function store(HouseRequest $request)
    {
        $sameNameHouse = House::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'name' => $request->name
        ])->first();
        if ($sameNameHouse) {
            Session::flash('errorMessage', 'Error! There is already a house in this name.');
            return redirect()->back();
        }

        DB::beginTransaction();
        try {
            $newHouseId = House::insertGetId([
                'name' => $request->name,
                'no_of_floors' => $request->floors,
                'employee_id' => $request->employeeId,
                'house_master_history' => json_encode([[
                    'employeeId' => $request->employeeId,
                    'assignedDate' => Carbon::now()
                ]]),
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ]);

            // Generating House Master History Start for cadet_house_table
            $houseHistory = HouseHistory::where('employee_id', $request->employeeId)->first();

            if ($houseHistory) {
                $houseHistoryData = json_decode($houseHistory->house_master_history, true);
                $generatedData = [
                    'houseId' => $newHouseId,
                    'fromDate' => Carbon::now(),
                    'toDate' => null,
                ];
                array_push($houseHistoryData, $generatedData);

                $houseHistory->update([
                    'house_master_history' => json_encode($houseHistoryData),
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id()
                ]);
            }else {
                HouseHistory::insert([
                    'employee_id' => $request->employeeId,
                    'house_master_history' => json_encode([[
                        'houseId' => $newHouseId,
                        'fromDate' => Carbon::now(),
                        'toDate' => null,
                    ]]),
                    'created_at' => Carbon::now(),
                    'created_by' => Auth::id(),
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                ]);
            }
            // Generating House Master History End for cadet_house_table

            if ($newHouseId) {
                DB::commit();
                Session::flash('message', 'Success! New House Created Successfully.');
                return redirect()->back();
            } else {
                Session::flash('errorMessage', 'Error! Error creating house.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error! Error creating house.');
            return redirect()->back();
        }
    }


    public function show($id)
    {
        return view('house::show');
    }


    public function edit($id)
    {
        $house = House::findOrFail($id);

        $employeeIds = House::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->pluck('employee_id')->toArray();

        if (($key = array_search($house->employee_id, $employeeIds)) !== false) {
            unset($employeeIds[$key]);
        }

        $employees = EmployeeInformation::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->whereNotIn('id', $employeeIds)->get();

        $studentIds = RoomStudent::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'house_id' => $house->id
        ])->pluck('student_id');
        $students = StudentProfileView::whereIn('std_id', $studentIds)->get();

        return view('house::modal.edit_house', compact('house', 'employees', 'students'));
    }


    public function update(HouseRequest $request, $id)
    {
        $house = House::findOrFail($id);

        $sameNameHouse = House::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'name' => $request->name
        ])->first();

        $maxFloor = Room::where('house_id', $house->id)->max('floor_no');

        if ($maxFloor > $request->floors) {
            Session::flash('errorMessage', 'Error! Can not decrease floors, room assigned at top floors.');
            return redirect()->back();
        }

        if ($sameNameHouse) {
            if ($house->id != $sameNameHouse->id) {
                Session::flash('errorMessage', 'Error! There is already a house in this name.');
                return redirect()->back();
            }
        }

        // Generation House Master History Start
        // To own table
        $houseMasterHistory = $house->house_master_history;

        if ($request->employeeId != $house->employee_id) {
            $houseMasterHistoryArray = json_decode($houseMasterHistory);

            array_push($houseMasterHistoryArray, [
                'employeeId' => $request->employeeId,
                'assignedDate' => Carbon::now()
            ]);

            $houseMasterHistory = json_encode($houseMasterHistoryArray);
        }

        // To cadet_house_history table
        if ($request->employeeId != $house->employee_id) {
            $houseHistory = HouseHistory::where('employee_id', $request->employeeId)->first();
            if ($houseHistory) {
                $houseHistoryData = json_decode($houseHistory->house_master_history, true);
                $generatedData = [
                    'houseId' => $house->id,
                    'fromDate' => Carbon::now(),
                    'toDate' => null,
                ];
                array_push($houseHistoryData, $generatedData);

                $houseHistory->update([
                    'house_master_history' => json_encode($houseHistoryData),
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id()
                ]);
            }else {
                HouseHistory::insert([
                    'employee_id' => $request->employeeId,
                    'house_master_history' => json_encode([[
                        'houseId' => $house->id,
                        'fromDate' => Carbon::now(),
                        'toDate' => null,
                    ]]),
                    'created_at' => Carbon::now(),
                    'created_by' => Auth::id(),
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                ]);
            }

            // Previous employee toDate change
            $previousHouseHistory = HouseHistory::where('employee_id', $house->employee_id)->first();
            $previousHouseHistoryData = json_decode($previousHouseHistory->house_master_history, true);
            $lastData = end($previousHouseHistoryData);
            $lastData['toDate'] = Carbon::now();

            array_pop($previousHouseHistoryData);
            array_push($previousHouseHistoryData, $lastData);

            $previousHouseHistory->update([
                'house_master_history' => json_encode($previousHouseHistoryData),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ]);
        }      
        // Generation House Master History End

        
        // Generation House Prefect History Start
        $housePrefectHistory = $house->house_prefect_history;

        if ($request->studentId != $house->student_id) {
            // To own table
            if ($housePrefectHistory) {
                $housePrefectHistoryArray = json_decode($housePrefectHistory);
            } else {
                $housePrefectHistoryArray = [];
            }

            array_push($housePrefectHistoryArray, [
                'studentId' => $request->studentId,
                'assignedDate' => Carbon::now()
            ]);

            $housePrefectHistory = json_encode($housePrefectHistoryArray);

            // To cadet_house_history table
            $houseHistory = HouseHistory::where('student_id', $request->studentId)->first();
            $historyData = ['houseId' => $house->id, 'fromDate' => Carbon::now(), 'toDate' => null];

            if ($houseHistory) {
                if ($houseHistory->prefect_history) {
                    $previousHouseHistory = json_decode($houseHistory->prefect_history);
                    array_push($previousHouseHistory, $historyData);
                    $houseHistory->update([
                        'prefect_history' => json_encode($previousHouseHistory)
                    ]);

                    // previous prefect's to date set
                    $prevStudentHouseHistory = HouseHistory::where('student_id', $house->student_id)->first();
                    $previousHouseHistory = json_decode($prevStudentHouseHistory->prefect_history, true);
                    $lastData = end($previousHouseHistory);
                    $lastData['toDate'] = Carbon::now();

                    array_pop($previousHouseHistory);
                    array_push($previousHouseHistory, $lastData);

                    $prevStudentHouseHistory->update([
                        'prefect_history' => json_encode($previousHouseHistory)
                    ]);
                } else {
                    $houseHistory->update([
                        'prefect_history' => json_encode([$historyData])
                    ]);
                }
            }
        }
        // Generation House Prefect History End

        DB::beginTransaction();
        try {
            $updateHouse = $house->update([
                'name' => $request->name,
                'no_of_floors' => $request->floors,
                'employee_id' => $request->employeeId,
                'house_master_history' => $houseMasterHistory,
                'student_id' => $request->studentId,
                'house_prefect_history' => $housePrefectHistory,
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id(),
            ]);

            if ($updateHouse) {
                DB::commit();
                Session::flash('message', 'Success! House Updated Successfully.');
                return redirect()->back();
            } else {
                Session::flash('errorMessage', 'Error! Error updating house.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error! Error updating house.');
            return redirect()->back();
        }
    }


    public function destroy($id)
    {
        $house = House::findOrFail($id);

        if (sizeof($house->rooms) > 0) {
            Session::flash('errorMessage', 'Dependencies Found! This House has rooms assigned to it.');
            return redirect()->back();
        } else {
            $house->delete();
            Session::flash('message', 'Success! House Deleted Successfully.');
            return redirect()->back();
        }
    }


    public function createRoom(RoomRequest $request)
    {
        $sameNameRoom = Room::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'house_id' => $request->houseId,
            'name' => $request->name
        ])->first();
        if ($sameNameRoom) {
            Session::flash('errorMessage', 'Error! There is already a room in this house with the same name.');
            return redirect()->back();
        }

        DB::beginTransaction();
        try {
            $insertRoom = Room::insert([
                'house_id' => $request->houseId,
                'floor_no' => $request->floor,
                'name' => $request->name,
                'no_of_beds' => $request->beds,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ]);

            if ($insertRoom) {
                DB::commit();
                Session::flash('message', 'Success! New Room Created Successfully.');
                return redirect()->back();
            } else {
                Session::flash('errorMessage', 'Error creating new Room.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error creating new Room.');
            return redirect()->back();
        }
    }


    public function editRoom($id)
    {
        $room = Room::with('house')->findOrFail($id);
        $houses = House::with('rooms')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();

        return view('house::modal.edit_room', compact('room', 'houses'));
    }

    public function updateRoom(RoomRequest $request, $id)
    {
        $room = Room::findOrFail($id);

        $sameNameRoom = Room::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'house_id' => $request->houseId,
            'name' => $request->name
        ])->first();
        if ($sameNameRoom) {
            if ($room->id != $sameNameRoom->id) {
                Session::flash('errorMessage', 'Error! There is already a room in this house with the same name.');
                return redirect()->back();
            }
        }

        $maxBed = RoomStudent::where('room_id', $room->id)->max('bed_no');

        if ($maxBed > $request->beds) {
            Session::flash('errorMessage', 'Error! Can not decrease beds, student assigned at that bed.');
            return redirect()->back();
        }

        DB::beginTransaction();
        try {
            $updateRoom = $room->update([
                'house_id' => $request->houseId,
                'floor_no' => $request->floor,
                'name' => $request->name,
                'no_of_beds' => $request->beds,
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id(),
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ]);

            if ($updateRoom) {
                DB::commit();
                Session::flash('message', 'Success! Room Updated Successfully.');
                return redirect()->back();
            } else {
                Session::flash('errorMessage', 'Error updating Room.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error updating Room.');
            return redirect()->back();
        }
    }

    public function deleteRoom($id)
    {
        $roomStudent = RoomStudent::where('room_id', $id)->first();

        if ($roomStudent) {
            Session::flash('errorMessage', 'Assigned students found in this room, make the beds blank first.');
            return redirect()->back();
        } else {
            Room::findOrFail($id)->delete();
            Session::flash('message', 'Success! Room deleted Successfully.');
            return redirect()->back();
        }
    }

    public function assignBeds($id)
    {
        $room = Room::findOrFail($id);
        $academicLevels = $this->academicHelper->getAllAcademicLevel();
        $roomStudents = RoomStudent::with('batch', 'section', 'student')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'room_id' => $room->id,
        ])->get();

        return view('house::modal.assign_beds', compact('room', 'academicLevels', 'roomStudents'));
    }

    // Ajax Methods start
    public function findSectionsFromAcaemicLevel(Request $request)
    {
        if ($request->academicLevelId) {
            $batcheIds = AcademicsLevel::findOrFail($request->academicLevelId)->batch()->pluck('id');
            $sections = Section::with('singleBatch')->whereIn('batch_id', $batcheIds)->get();
            return $sections;
        } else {
            return [];
        }
    }

    public function findStudentsFromSection(Request $request)
    {
        $assignedStudentIds = RoomStudent::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->pluck('student_id');

        if ($request->sectionId) {
            return StudentProfileView::where('section', $request->sectionId)->whereNotIn('std_id', $assignedStudentIds)->get();
        } else {
            return [];
        }
    }

    public function assignStudentToBed(Request $request)
    {
        $previousRoomStudent = RoomStudent::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'house_id' => $request->houseId,
            'floor_no' => $request->floorNo,
            'room_id' => $request->roomId,
            'bed_no' => $request->bedNo,
        ])->first();
        $student = StudentProfileView::where('std_id', $request->studentId)->first();

        if ($previousRoomStudent) {
            $isPrefect = House::where('student_id', $previousRoomStudent->student_id)->first();
            if ($isPrefect) {
                return 2;
            }
        }        

        $houseHistoryData = [
            'fromDate' => Carbon::now(),
            'toDate' => null,
            'houseId' => $request->houseId,
            'floorNo' => $request->floorNo,
            'roomId' => $request->roomId,
            'bedNo' => $request->bedNo,
            'batchId' => $student->singleBatch->id,
            'sectionId' => $student->singleSection->id,
        ];
        $houseHistory = HouseHistory::where('student_id', $request->studentId)->first();

        DB::beginTransaction();
        try {
            $updateRoomStudent = null;
            $insertRoomStudent = null;

            // House history storing starts
            if ($houseHistory) {
                $houseHistoryArray = json_decode($houseHistory->house_history, true);
                array_push($houseHistoryArray, $houseHistoryData);

                $houseHistory->update([
                    'house_history' => json_encode($houseHistoryArray),
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id(),
                ]);
            } else {
                HouseHistory::insert([
                    'student_id' => $request->studentId,
                    'house_history' => json_encode([$houseHistoryData]),
                    'created_at' => Carbon::now(),
                    'created_by' => Auth::id(),
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                ]);
            }
            // House history storing ends

            if ($previousRoomStudent) {
                // House history previous student to_date changing starts
                $houseHistory2 = HouseHistory::where('student_id', $previousRoomStudent->student_id)->first();

                $houseHistory2Array = json_decode($houseHistory2->house_history, true);
                $lastData = end($houseHistory2Array);
                $lastData['toDate'] = Carbon::now();

                array_pop($houseHistory2Array);
                array_push($houseHistory2Array, $lastData);

                $houseHistory2->update([
                    'house_history' => json_encode($houseHistory2Array),
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id(),
                ]);
                // House history previous student to_date changing ends

                $updateRoomStudent = $previousRoomStudent->update([
                    'student_id' => $request->studentId,
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id()
                ]);
            } else {
                $insertRoomStudent = RoomStudent::insert([
                    'house_id' => $request->houseId,
                    'floor_no' => $request->floorNo,
                    'room_id' => $request->roomId,
                    'bed_no' => $request->bedNo,
                    'student_id' => $request->studentId,
                    'created_at' => Carbon::now(),
                    'created_by' => Auth::id(),
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                ]);
            }

            if ($updateRoomStudent || $insertRoomStudent) {
                DB::commit();
                $student = StudentProfileView::with('singleBatch', 'singleSection')->where('std_id', $request->studentId)->first();
                return ['student' => $student];
            } else {
                return null;
            }
        } catch (\Exception $e) {
            DB::rollback();
            return null;
        }
    }

    public function removeStudentFromBed(Request $request)
    {
        $roomStudent = RoomStudent::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'room_id' => $request->roomId,
            'bed_no' => $request->bedNo
        ])->first();

        $isPrefect = House::where('student_id', $roomStudent->student_id)->first();
        if ($isPrefect) {
            return 2;
        }

        if ($roomStudent) {
            // House history student to_date changing starts
            $houseHistory = HouseHistory::where('student_id', $roomStudent->student_id)->first();

            $houseHistoryArray = json_decode($houseHistory->house_history, true);
            $lastData = end($houseHistoryArray);
            $lastData['toDate'] = Carbon::now();

            array_pop($houseHistoryArray);
            array_push($houseHistoryArray, $lastData);

            $houseHistory->update([
                'house_history' => json_encode($houseHistoryArray),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id(),
            ]);
            // House history student to_date changing ends

            $roomStudent->delete();
            return 1;
        } else {
            return 0;
        }
    }
    // Ajax Methods end


    public function cadetsEvaluation()
    {
        $academicYears = $this->academicHelper->getAllAcademicYears();

        return view('house::evaluation.index', compact('academicYears'));
    }

    public function weightageConfig()
    {
        $academicYears = $this->academicHelper->getAllAcademicYears();

        return view('house::evaluation.modal.weightage-config', compact('academicYears'));
    }

    //Ajax Methods Start
    public function getSemesterFromYear(Request $request)
    {
        return AcademicsYear::findOrFail($request->academicYearId)->semesters();
    }

    public function getWeightageEventsFromType(Request $request)
    {
        $previousWeightage = WeightageConfig::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'academic_year_id' => $request->academicYearId,
            'semester_id' => $request->semesterId,
            'type' => $request->type,
        ])->get();

        if ($request->type == 1) {
            $exams = ExamName::where([
                'campus' => $this->academicHelper->getCampus(),
                'institute' => $this->academicHelper->getInstitute(),
            ])->get();

            return [$previousWeightage, $exams];
        } else if ($request->type == 2) {
            $performances = CadetPerformanceCategory::where("category_type_id", 9)->orderBy('flag', 'asc')->get();

            return [$previousWeightage, $performances];
        } else if ($request->type == 3) {
            $performances = CadetPerformanceCategory::where("category_type_id", 1)->orderBy('flag', 'asc')->get();

            return [$previousWeightage, $performances];
        } else {
            return [];
        }
    }

    public function deleteWeightage(Request $request)
    {
        $weightage = WeightageConfig::findOrFail($request->weightageId);
        if ($weightage) {
            $weightage->delete();
            return 1;
        } else {
            return 0;
        }
    }

    public function getEventsFromType(Request $request)
    {
        if ($request->type == 1) {
            $exams = ExamName::where([
                'campus' => $this->academicHelper->getCampus(),
                'institute' => $this->academicHelper->getInstitute(),
            ])->get();

            return $exams;
        } else if ($request->type == 2) {
            $performances = CadetPerformanceCategory::where("category_type_id", 9)->orderBy('flag', 'asc')->get();
            return $performances;
        } else if ($request->type == 3) {
            $performances = CadetPerformanceCategory::where("category_type_id", 1)->orderBy('flag', 'asc')->get();
            return $performances;
        } else {
            return [];
        }
    }

    public function searchEvaluationTable(Request $request)
    {
        $academicYearId = $request->academicYearId;
        $semesterId = $request->semesterId;
        $houses = House::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();
        $type = $request->type;
        $eventId = $request->eventId;

        $exams = ExamName::whereExists(function ($query) {
            $query->select(DB::raw(1))
                  ->from('cadet_exam_marks')
                  ->whereColumn('cadet_exam_marks.exam_id', 'cadet_exam_name.id');
        })->get();
        $examWeightages = WeightageConfig::with('exam')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'academic_year_id' => $academicYearId,
            'semester_id' => $semesterId,
            'type' => 1
        ])->get();
        $extraCurriculars = CadetPerformanceCategory::where('category_type_id', 9)
        ->whereExists(function ($query) {
            $query->select(DB::raw(1))
                  ->from('cadet_assesment')
                  ->whereColumn('cadet_assesment.cadet_performance_category_id', 'cadet_performance_category.id');
        })->get();
        $extraCurricularWeightages = WeightageConfig::with('performanceCategory')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'academic_year_id' => $academicYearId,
            'semester_id' => $semesterId,
            'type' => 2
        ])->get();
        $coCurriculars = CadetPerformanceCategory::where('category_type_id', 1)
        ->whereExists(function ($query) {
            $query->select(DB::raw(1))
                  ->from('cadet_assesment')
                  ->whereColumn('cadet_assesment.cadet_performance_category_id', 'cadet_performance_category.id');
        })->get();
        $coCurricularWeightages = WeightageConfig::with('performanceCategory')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'academic_year_id' => $academicYearId,
            'semester_id' => $semesterId,
            'type' => 3
        ])->get();

        if ($eventId) {
            if ($type == 1) {
                $examWeightages = WeightageConfig::with('exam')->where([
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                    'academic_year_id' => $academicYearId,
                    'semester_id' => $semesterId,
                    'type' => 1,
                    'exam_id' => $eventId
                ])->get();
                $exams = ExamName::where('id', $eventId)->get();
            } else if ($type == 2) {
                $extraCurricularWeightages = WeightageConfig::with('performanceCategory')->where([
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                    'academic_year_id' => $academicYearId,
                    'semester_id' => $semesterId,
                    'type' => 2,
                    'performance_cat_id' => $eventId
                ])->get();
                $extraCurriculars = CadetPerformanceCategory::where('id', $eventId)->get();
            } else if ($type == 3) {
                $coCurricularWeightages = WeightageConfig::with('performanceCategory')->where([
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                    'academic_year_id' => $academicYearId,
                    'semester_id' => $semesterId,
                    'type' => 3,
                    'performance_cat_id' => $eventId
                ])->get();
                $coCurriculars = CadetPerformanceCategory::where('id', $eventId)->get();
            }
        }

        return view('house::evaluation.evaluation-table', compact('academicYearId', 'semesterId', 'houses', 'type', 'exams', 'examWeightages', 'coCurriculars', 'coCurricularWeightages', 'extraCurriculars', 'extraCurricularWeightages'))->render();
    }
    //Ajax Methods End

    public function saveWeightage(Request $request)
    {
        $eventTypeId = '';
        if ($request->type == 1) {
            $eventTypeId = 'exam_id';
        } else if ($request->type == 2 || $request->type == 3) {
            $eventTypeId = 'performance_cat_id';
        }

        DB::beginTransaction();
        try {
            foreach ($request->events as $key => $event) {
                if ($event) {
                    $previousWeightage = WeightageConfig::where([
                        'campus_id' => $this->academicHelper->getCampus(),
                        'institute_id' => $this->academicHelper->getInstitute(),
                        'academic_year_id' => $request->academicYearId,
                        'semester_id' => $request->semesterId,
                        'type' => $request->type,
                        $eventTypeId => $event
                    ])->first();

                    if ($previousWeightage) {
                        $previousWeightage->update([
                            'mark' => $request->marks[$key],
                            'updated_at' => Carbon::now(),
                            'updated_by' => Auth::id(),
                        ]);
                    } else {
                        WeightageConfig::insert([
                            'academic_year_id' => $request->academicYearId,
                            'semester_id' => $request->semesterId,
                            'type' => $request->type,
                            $eventTypeId => $event,
                            'mark' => $request->marks[$key],
                            'created_at' => Carbon::now(),
                            'created_by' => Auth::id(),
                            'campus_id' => $this->academicHelper->getCampus(),
                            'institute_id' => $this->academicHelper->getInstitute(),
                        ]);
                    }
                }
            }

            DB::commit();
            Session::flash('message', 'Success! Weightage Configured Successfully.');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error Configuring weightage.');
            return redirect()->back();
        }
    }


    public function viewHouses()
    {
        $houses = House::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();

        return view('house::view-houses', compact('houses'));
    }

    public function searchHouse(Request $request)
    {
        $house = House::with('rooms')->findOrFail($request->houseId);
        $roomStudents = RoomStudent::with('student')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'house_id' => $house->id
        ])->get();
        $students = StudentProfileView::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ]);

        return view('house::house-table', compact('house', 'roomStudents', 'students'))->render();
    }
}
