<?php

namespace Modules\Student\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\HealthCare\Entities\HealthPrescription;
use Modules\House\Entities\House;
use Modules\House\Entities\HouseHistory;
use Modules\House\Entities\Room;
use Modules\Student\Entities\StudentInformation;

class StudentHistoryController extends Controller
{
    private $academicHelper;

    // constructor
    public function __construct(AcademicHelper $academicHelper)
    {
        $this->academicHelper = $academicHelper;
    }


    public function index($id)
    {
        $personalInfo = StudentInformation::findOrFail($id);
        $page = 'history';

        return view('student::pages.student-profile.student-history', compact('personalInfo', 'page'));
    }

    public function houseHistory($id)
    {
        $personalInfo = StudentInformation::findOrFail($id);
        $page = 'history';

        $houseHistoriesJson = HouseHistory::where('student_id', $personalInfo->id)->value('house_history');
        if ($houseHistoriesJson) {
            $houseHistories = array_reverse(json_decode($houseHistoriesJson, true));
        } else {
            $houseHistories = [];
        }

        $houses = House::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();

        $rooms = Room::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();

        return view('student::pages.student-profile.student-house-history', compact('personalInfo', 'page', 'houseHistories', 'houses', 'rooms'));
    }

    public function medicalHistory($id)
    {
        $personalInfo = StudentInformation::findOrFail($id);
        $page = 'history';

        $medicalHistories = HealthPrescription::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'patient_type' => 1,
            'patient_id' => $id
        ])->orderByDesc('id')->get();

        return view('student::pages.student-profile.student-medical-history', compact('personalInfo', 'page', 'medicalHistories'));
    }
}
