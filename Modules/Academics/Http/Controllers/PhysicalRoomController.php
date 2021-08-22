<?php

namespace Modules\Academics\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Academics\Entities\PhysicalRoom;
use Modules\Academics\Entities\PhysicalRoomAllocation;
use Modules\Academics\Entities\PhysicalRoomCategory;
use Modules\Academics\Entities\PhysicalRoomStudent;
use Modules\Academics\Entities\Section;
use Modules\Academics\Http\Requests\PhysicalRoomAllocationRequest;
use Modules\Academics\Http\Requests\PhysicalRoomRequest;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\Student\Entities\CadetAssesment;
use Modules\Academics\Entities\PhysicalRoomCatType;
use Modules\Student\Entities\PhysicalRoomCatType as EntitiesPhysicalRoomCatType;
use Modules\Student\Entities\StudentInformation;
use Modules\Student\Entities\StudentProfileView;

class PhysicalRoomController extends Controller
{
    private $academicHelper;

    public function __construct(AcademicHelper $academicHelper)
    {
        $this->academicHelper = $academicHelper;
    }

    public function index()
    {
        $roomCategories = PhysicalRoomCategory::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->get();
        $employees = EmployeeInformation::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();
        $rooms = PhysicalRoom::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->get();


        return view('academics::physical-rooms.index', compact('roomCategories', 'employees', 'rooms'));
    }



    public function create()
    {
        return view('academics::create');
    }



    public function store(PhysicalRoomRequest $request)
    {
        $sameNameRoom = PhysicalRoom::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
            'name' => $request->name
        ])->get();

        if (sizeOf($sameNameRoom) > 0) {
            Session::flash('errorMessage', 'Sorry! There is already a physical room in this name.');
            return redirect()->back();
        }

        DB::beginTransaction();
        try {
            $roomId = PhysicalRoom::insertGetId([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'rows' => $request->rows,
                'cols' => $request->cols,
                'cadets_per_seat' => $request->cadets_per_seat,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'campus' => $this->academicHelper->getCampus(),
                'institute' => $this->academicHelper->getInstitute(),
            ]);

            if ($roomId) {
                $room = PhysicalRoom::findOrFail($roomId);
                if ($room->employees()->sync($request->employees)) {
                    DB::commit();
                    Session::flash('message', 'New physical room created successfully.');
                    return redirect()->back();
                } else {
                    Session::flash('errorMessage', 'Error assigning employees to the room.');
                    return redirect()->back();
                }
            } else {
                Session::flash('errorMessage', 'Error creating physical room.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error creating physical room.');
            return redirect()->back();
        }
    }



    public function show($id)
    {
        return view('academics::show');
    }



    public function edit($id)
    {
        $roomCategories = PhysicalRoomCategory::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->get();
        $employees = EmployeeInformation::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();
        $room = PhysicalRoom::with('category', 'employee')->findOrFail($id);

        return view('academics::physical-rooms.modal.edit', compact('roomCategories', 'employees', 'room'));
    }



    public function update(PhysicalRoomRequest $request, $id)
    {
        $room = PhysicalRoom::findOrFail($id);

        $sameNameRoom = PhysicalRoom::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
            'name' => $request->name
        ])->first();

        if ($sameNameRoom) {
            if ($sameNameRoom->id != $room->id) {
                Session::flash('errorMessage', 'Sorry! There is already a physical room in this name.');
                return redirect()->back();
            }
        }

        DB::beginTransaction();
        try {
            $roomUpdate = $room->update([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'rows' => $request->rows,
                'cols' => $request->cols,
                'cadets_per_seat' => $request->cadets_per_seat,
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id(),
            ]);

            if ($roomUpdate) {
                if ($room->employees()->sync($request->employees)) {
                    DB::commit();
                    Session::flash('message', 'Success! Physical room updated successfully.');
                    return redirect()->back();
                } else {
                    Session::flash('errorMessage', 'Sorry! Error assigning employees to Physical room.');
                    return redirect()->back();
                }
            } else {
                Session::flash('errorMessage', 'Sorry! Error updating Physical room.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Sorry! Error updating Physical room.');
            return redirect()->back();
        }
    }


    public function destroy($id)
    {
        $room = PhysicalRoom::findOrFail($id);
        if (sizeof($room->allocations) > 0) {
            Session::flash('message', 'Sorry! Dependencies found.');
            return redirect()->back();
        } else {
            $room->deleted_by = Auth::id();
            $room->save();
            $room->delete();

            Session::flash('message', 'Deleted Successfully.');
            return redirect()->back();
        }
    }

    public function allocateView($id)
    {
        $room = PhysicalRoom::findOrFail($id);
        $sections = Section::with('singleBatch')->where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->get();
        $allocations = $room->allocations;

        return view('academics::physical-rooms.modal.allocate', compact('room', 'sections', 'allocations'));
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

    public function searchPrefects(Request $request)
    {
        if ($request->students) {
            return StudentProfileView::where([
                'campus' => $this->academicHelper->getCampus(),
                'institute' => $this->academicHelper->getInstitute(),
            ])->whereIn('std_id', $request->students)->get();
        }else{
            return [];
        }
    }

    public function allocateStudents(PhysicalRoomAllocationRequest $request, $id)
    {
        $room = PhysicalRoom::findOrFail($id);

        if (isset($request->cadets)) {
            if (($room->rows * $room->cols * $room->cadets_per_seat) < sizeof($request->cadets)) {
                Session::flash('errorMessage', 'Sorry! Room capacity exceeded.');
                return redirect()->back();
            }
        }

        DB::beginTransaction();
        try {
            $allocationId = PhysicalRoomAllocation::insertGetId([
                'physical_room_id' => $room->id,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ]);

            if ($allocationId) {
                $sections = Section::whereIn('id', $request->sections)->get();
                foreach ($sections as $section) {
                    $allocateSection = PhysicalRoomStudent::insert([
                        'allocation_id' => $allocationId,
                        'physical_room_id' => $room->id,
                        'section_id' => $section->id,
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'campus_id' => $this->academicHelper->getCampus(),
                        'institute_id' => $this->academicHelper->getInstitute(),
                    ]);
                }

                if (isset($request->cadets)) {
                    $cadets = StudentProfileView::whereIn('std_id', $request->cadets)->get();
                    foreach ($cadets as $cadet) {
                        $allocateStudent = PhysicalRoomStudent::insert([
                            'allocation_id' => $allocationId,
                            'physical_room_id' => $room->id,
                            'section_id' => $cadet->section,
                            'student_information_id' => $cadet->std_id,
                            'created_at' => Carbon::now(),
                            'created_by' => Auth::id(),
                            'campus_id' => $this->academicHelper->getCampus(),
                            'institute_id' => $this->academicHelper->getInstitute(),
                        ]);
                    }
                }

                if ($allocateSection && $allocateStudent) {
                    DB::commit();
                    Session::flash('message', 'Success! Room allocated successfully.');
                    return redirect()->back();
                }
            } else {
                Session::flash('errorMessage', 'Error Allocating Room.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error Allocating Room.');
            return redirect()->back();
        }
    }


    public function allocateEditView($id)
    {
        $room = PhysicalRoom::findOrFail($id);
        $allocation = $room->latestAllocation();
        $sections = Section::with('singleBatch')->where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->get();
        $sectionsId = Section::with('singleBatch')->where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->pluck('id');
        $students = StudentProfileView::whereIn('section', $sectionsId)->get();

        return view('academics::physical-rooms.modal.edit-allocation', compact('room', 'sections', 'allocation', 'students'));
    }
}
