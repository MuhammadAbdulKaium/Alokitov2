<?php

namespace Modules\Admission\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admission\Entities\ApplicantInformation;
use Modules\Setting\Entities\Country;
use Modules\Setting\Entities\State;
use Modules\Setting\Entities\City;
use Redirect;
use Session;

class ApplicantInfoController extends Controller
{
    private $city;
    private $state;
    private $country;
    private $personalInfo;

    // constructor
    public function __construct(ApplicantInformation $personalInfo, Country $country, State $state, City $city)
    {
        $this->city      = $city;
        $this->state      = $state;
        $this->country      = $country;
        $this->personalInfo = $personalInfo;
    }

    // edit personal
    public function editPersonal($id)
    {
        // applicant profile
        $personalInfo = $this->personalInfo->find($id);
        // all country
        $allCountry = $this->country->orderBy('name', 'ASC')->get();
        // all state list
        $allState = $this->state->orderBy('name', 'ASC')->get();
        // return view with  variable
        return view('admission::application.modals.personal', compact('personalInfo', 'allCountry', 'allState'));
    }

    // Update the specified resource in storage.
    public function updatePersonal(Request $request, $id)
    {

        // applicant new profile
        $personalInoProfile = $this->personalInfo->find($id);
        // input details
        $personalInoProfile->std_name            = $request->input('std_name', null);
        $personalInoProfile->std_name_bn         = $request->input('std_name_bn', null);

        $personalInoProfile->father_name         = $request->input('father_name', null);
        $personalInoProfile->father_name_bn           = $request->input('father_name_bn', null);
        $personalInoProfile->father_occupation      = $request->input('father_occupation', null);
        $personalInoProfile->father_education    = $request->input('father_education', null);
        $personalInoProfile->father_phone    = $request->input('father_phone', null);

        $personalInoProfile->mother_name         = $request->input('mother_name', null);
        $personalInoProfile->mother_name_bn      = $request->input('mother_name_bn', null);
        $personalInoProfile->mother_occupation   = $request->input('mother_occupation', null);
        $personalInoProfile->mother_education    = $request->input('mother_education', null);
        $personalInoProfile->mother_phone    = $request->input('mother_phone', null);

        $personalInoProfile->birth_date          = date('Y-m-d', strtotime($request->input('birth_date')));
        $personalInoProfile->gender              = $request->input('gender', null);

        $personalInoProfile->add_per_address      = $request->input('add_per_address');
        $personalInoProfile->add_per_post        = $request->input('add_per_post', null);
        $personalInoProfile->add_per_city        = $request->input('add_per_city', null);
        $personalInoProfile->add_per_state       = $request->input('add_per_state', null);
        $personalInoProfile->add_per_phone       = $request->input('add_per_phone', null);

        $personalInoProfile->add_pre_address     = $request->input('add_pre_address', null);
        $personalInoProfile->add_pre_post        = $request->input('add_pre_post', null);
        $personalInoProfile->add_pre_city        = $request->input('add_pre_city', null);
        $personalInoProfile->add_pre_state       = $request->input('add_pre_state', null);
        $personalInoProfile->add_pre_phone       = $request->input('add_pre_phone', null);

        $personalInoProfile->gud_name            = $request->input('gud_name', null);
        $personalInoProfile->gud_phone           = $request->input('gud_phone', null);
        $personalInoProfile->gud_income          = $request->input('gud_income', null);
        $personalInoProfile->gud_income_bn       = $request->input('gud_income_bn', null);

        $personalInoProfile->jsc_gpa             = $request->input('jsc_gpa', null);
        $personalInoProfile->jsc_roll            = $request->input('jsc_roll', null);
        $personalInoProfile->jsc_year            = $request->input('jsc_year', null);

        $personalInoProfile->psc_gpa             = $request->input('psc_gpa', null);
        $personalInoProfile->psc_roll            = $request->input('psc_roll', null);
        $personalInoProfile->psc_year            = $request->input('psc_year', null);
        $personalInoProfile->psc_school          = $request->input('psc_school', null);
        $personalInoProfile->psc_tes_no          = $request->input('psc_tes_no', null);
        $personalInoProfile->psc_tes_date        = $request->input('psc_tes_date', null);

        $personalInoProfile->special_care        = $request->input('special_care');
        $personalInoProfile->tribal              = $request->input('tribal');
        $personalInoProfile->nationality            = $request->input('nationality');
        $personalInoProfile->religion          = $request->input('religion', null);
        $personalInoProfile->area_of_land          = $request->input('area_of_land', null);
        // save applicant profile
        $personalInfoSubmitted = $personalInoProfile->save();
        // checking
        if ($personalInoProfile) {
            Session::flash('success', 'Applicant Personal Information Updated');
            return redirect()->back();
        } else {
            Session::flash('warning', 'Unable to perform action');
            return redirect()->back();
        }
    }
}