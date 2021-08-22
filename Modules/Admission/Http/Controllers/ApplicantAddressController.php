<?php

namespace Modules\Admission\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admission\Entities\ApplicantAddress;
use Modules\Setting\Entities\Country;
use Redirect;
use Session;

class ApplicantAddressController extends Controller
{

    private $address;
    private $country;

    // constructor
    public function __construct(ApplicantAddress $address, Country $country)
    {
        $this->address = $address;
        $this->country   = $country;
    }

    // edit address
    public function edit($id)
    {
        // address profile
        $addressProfile = $this->address->find($id);
        // all country
        $allCountry = $this->country->orderBy('name', 'ASC')->get();
        // return view with  variable
        return view('admission::application.modals.address', compact('addressProfile', 'allCountry'));
    }

    // update address
    public function update(Request $request, $id)
    {
        // address profile
        $addressProfile = $this->address->find($id);
        // input details
        $addressProfile->address    = $request->input('address');
        $addressProfile->city_id    = $request->input('city');
        $addressProfile->state_id   = $request->input('state');
        $addressProfile->country_id = $request->input('country');
        $addressProfile->zip        = $request->input('zip');
        $addressProfile->house      = $request->input('house');
        $addressProfile->phone      = $request->input('phone');
        // save address profile
        $addressProfileSubmitted = $addressProfile->save();
        // checking address
        if ($addressProfileSubmitted) {
            Session::flash('success', 'Address Updated');
            // redirecting to applicant profile page
            return redirect()->back();
        } else {
            Session::flash('warning', 'Unable to update address');
            // redirecting to applicant profile page
            return redirect()->back();
        }
    }
}
