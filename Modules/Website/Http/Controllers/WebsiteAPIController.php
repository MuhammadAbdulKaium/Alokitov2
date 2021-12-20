<?php

namespace Modules\Website\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Website\Entities\Success;
use Modules\Website\Entities\WebsiteCommittee;
use Modules\Website\Entities\WebsiteExtra;
use Modules\Website\Entities\WebsiteFormDuration;
use Modules\Website\Entities\WebsiteImage;
use Modules\Website\Entities\WebsiteInformation;
use Modules\Website\Entities\WebsitePublicExamResult;
use Modules\Website\Entities\WebsiteRule;

class WebsiteAPIController extends Controller
{
    private $academicHelper;
    private $websiteController;

    // constructor
    public function __construct(AcademicHelper $academicHelper, WebsiteController $websiteController)
    {
        $this->academicHelper = $academicHelper;
        $this->websiteController = $websiteController;
    }

    public function informationAPI(Request $request)
    {
        $campusId = $request->input('campus_id');
        $instituteId = $request->input('institute_id');

        // checking campus with institute
        if ($this->academicHelper->findCampusWithInstId($campusId, $instituteId)) {

            $information = WebsiteInformation::where('campus_id', '=', $campusId)
                ->where('institute_id', '=', $instituteId)->first();

            $informationArray = null;

            if ($information)
            {
                if ($information['school_logo']) {
                    $photo = url('/images/' . $information->school_logo);
                } else {
                    $photo = url('/assets/users/images/user-default.png');
                }
                $informationArray = [
                    'school_name' => $information->school_name,
                    'school_phone' => $information->school_phone,
                    'school_email' => $information->school_email,
                    'school_address' => $information->school_address,
                    'school_fb' => $information->school_fb,
                    'school_contact' => $information->school_contact,
                    'school_history' => $information->school_history,
                    'school_mission' => $information->school_mission,
                    'school_structure' => $information->school_structure,
                    'school_logo' => $photo
                ];
                return ['status' => 'success', 'msg' => 'Website Information', 'data' => $informationArray];
            }
            else {
                return ['status' => 'failed', 'msg' => 'No records found'];
            }
        }
        else {
            return ['status' => 'failed', 'msg' => 'Invalid Campus or Institute ID'];
        }
        }
        public function successAPI(Request $request){
            $campusId = $request->input('campus_id');
            $instituteId = $request->input('institute_id');

            // checking campus with institute
            if ($this->academicHelper->findCampusWithInstId($campusId, $instituteId)) {

                $successes = Success::where('campus_id', '=', $campusId)
                    ->where('institute_id', '=', $instituteId)->get();

                $successArray = null;

                if (count($successes) > 0)
                {
                    foreach ($successes as $success)
                    {


                        $successArray[$success->id] =
                            [
                                'passing_year' => $success->passing_year,
                                'total_examine' => $success->total_examine,
                                'psc_passing_rate' => $success->psc_passing_rate,
                                'jsc_passing_rate' => $success->jsc_passing_rate,
                                'ssc_passing_rate' => $success->ssc_passing_rate,

                                'hsc_passing_rate' => $success->hsc_passing_rate,

                            ];
                    }
                    return ['status' => 'success', 'msg' => 'Website Committee ddd', 'data' => $successArray];
                }
                else {
                    return ['status' => 'failed', 'msg' => 'No records found'];
                }
            }
            else {
                return ['status' => 'failed', 'msg' => 'Invalid Campus or Institute ID'];
            }
        }

    public function committeeAPI(Request $request)
    {
        $campusId = $request->input('campus_id');
        $instituteId = $request->input('institute_id');

        // checking campus with institute
        if ($this->academicHelper->findCampusWithInstId($campusId, $instituteId)) {

            $committees = WebsiteCommittee::where('campus_id', '=', $campusId)
                ->where('institute_id', '=', $instituteId)->get();

            $committeeArray = null;

            if (count($committees) > 0)
            {
                foreach ($committees as $committee)
                {
                    if ($committee['image']) {
                        $photo = $committee->image;
                    }

                    $committeeArray[$committee->id] =
                    [
                        'name' => $committee->name,
                        'phone' => $committee->phone,
                        'email' => $committee->email,
                        'designation' => $committee->designation,
                        'speech' => $committee->speech,
                        'image' => $photo
                    ];
                }
                return ['status' => 'success', 'msg' => 'Website Committee', 'data' => $committeeArray];
            }
            else {
                return ['status' => 'failed', 'msg' => 'No records found'];
            }
        }
        else {
            return ['status' => 'failed', 'msg' => 'Invalid Campus or Institute ID'];
        }

    }


    public function rulesAPI(Request $request)
    {
        $campusId = $request->input('campus_id');
        $instituteId = $request->input('institute_id');

        // checking campus with institute
        if ($this->academicHelper->findCampusWithInstId($campusId, $instituteId)) {

            $rules = WebsiteRule::where('campus_id', '=', $campusId)
                ->where('institute_id', '=', $instituteId)->first();

            $rulesArray = null;

            if ($rules)
            {
                $rulesArray = [
                    'teacher_rule' => $rules->teacher_rule,
                    'student_rule' => $rules->student_rule,
                    'parent_rule' => $rules->parent_rule
                ];
                return ['status' => 'success', 'msg' => 'Website Information', 'data' => $rulesArray];
            }
            else {
                return ['status' => 'failed', 'msg' => 'No records found'];
            }
        }
        else {
            return ['status' => 'failed', 'msg' => 'Invalid Campus or Institute ID'];
        }
    }


    public function extrasAPI(Request $request)
    {
        $campusId = $request->input('campus_id');
        $instituteId = $request->input('institute_id');
        $type = $request->input('type');

        // checking campus with institute
        if ($this->academicHelper->findCampusWithInstId($campusId, $instituteId)) {

            $extras = WebsiteExtra::where('campus_id', '=', $campusId)
                ->where('institute_id', '=', $instituteId)
                ->where('type', '=', $type)->get();

            $extrasArray = null;

            if (count($extras) > 0)
            {
                foreach ($extras as $extra)
                {
                    if ($extra['file']) {
                        $file = url('/images/' . $extra->file);
                    }
                    else {
                        $file = url('/assets/users/images/user-default.png');
                    }

                    if ($extra['file2']) {
                        $file2 = url('/images/' . $extra->file2);
                    }
                    else {
                        $file2 = url('/assets/users/images/user-default.png');
                    }

                    $extrasArray[$extra->id] =
                        [
                            'type' => $extra->type,
                            'curricular_type' => $extra->curricular_type,
                            'name' => $extra->name,
                            'description' => $extra->description,
                            'file' => $file,
                            'file2' => $file2
                        ];
                }

                return ['status' => 'success', 'msg' => 'Website Committee', 'data' => $extrasArray];
            }
            else {
                return ['status' => 'failed', 'msg' => 'No records found'];
            }
        }
        else {
            return ['status' => 'failed', 'msg' => 'Invalid Campus or Institute ID'];
        }

    }


    public function publicExamAPI(Request $request)
    {
        $campusId = $request->input('campus_id');
        $instituteId = $request->input('institute_id');

        // checking campus with institute
        if ($this->academicHelper->findCampusWithInstId($campusId, $instituteId)) {

            $results = WebsitePublicExamResult::where('campus_id', '=', $campusId)
                ->where('institute_id', '=', $instituteId)->get();

            $rulesArray = null;

            if (count($results) > 0)
            {
                foreach ($results as $result)
                {
                    $rulesArray[$result->id] = [
                        'year' => $result->year,
                        'name' => $result->name,
                        'examinee' => $result->examinee,
                        'a_plus' => $result->a_plus,
                        'pass_percentage' => $result->pass_percentage
                    ];
                }

                return ['status' => 'success', 'msg' => 'Website success and achievement ', 'data' => $rulesArray];
            }
            else {
                return ['status' => 'failed', 'msg' => 'No records found'];
            }
        }
        else {
            return ['status' => 'failed', 'msg' => 'Invalid Campus or Institute ID'];
        }
    }


    public function imageAPI(Request $request)
    {
        $campusId = $request->input('campus_id');
        $instituteId = $request->input('institute_id');

        // checking campus with institute
        if ($this->academicHelper->findCampusWithInstId($campusId, $instituteId)) {

            $albums = WebsiteImage::where('campus_id', '=', $campusId)
                ->where('institute_id', '=', $instituteId)->get();

            $albumsArray = null;

            if (count($albums) > 0)
            {
                foreach ($albums as $album)
                {
                    $images = explode('|', $album->images);
                    $file = array();

                    foreach ($images as $image)
                    {
                        $file[] =  $image;
                    }

                    $albumsArray[$album->id] = [
                        'type' => $album->type,
                        'name' => $album->name,
                        'images' => $file
                    ];
                }
                return ['status' => 'success', 'msg' => 'Website Information', 'data' => $albumsArray];
            }
            else {
                return ['status' => 'failed', 'msg' => 'No records found'];
            }
        }
        else {
            return ['status' => 'failed', 'msg' => 'Invalid Campus or Institute ID'];
        }
    }


    public function formDurationAPI(Request $request)
    {
        $campusId = $request->input('campus_id');
        $instituteId = $request->input('institute_id');

        // checking campus with institute
        if ($this->academicHelper->findCampusWithInstId($campusId, $instituteId)) {

            $duration = WebsiteFormDuration::where('campus_id', '=', $campusId)
                ->where('institute_id', '=', $instituteId)->first();

            $durationArray = null;

            if ($duration)
            {
                $durationArray = [
                    'starting_date' => $duration->starting_date,
                    'ending_date' => $duration->ending_date,
                    'is_active' => $duration->is_active
                ];
                return ['status' => 'success', 'msg' => 'Website Information', 'data' => $durationArray];
            }
            else {
                return ['status' => 'failed', 'msg' => 'No records found'];
            }
        }
        else {
            return ['status' => 'failed', 'msg' => 'Invalid Campus or Institute ID'];
        }
    }
}

