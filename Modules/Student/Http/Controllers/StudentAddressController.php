<?php

namespace Modules\Student\Http\Controllers;

use App\Address;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Setting\Entities\Country;
use Modules\Student\Entities\StudentInformation;
use Redirect;
use Session;
use Validator;

class StudentAddressController extends Controller
{
    // student profile address main page
    public function getStudentAddresses($id)
    {
        $personalInfo = StudentInformation::findOrFail($id);
        return view('student::pages.student-profile.student-address', compact('personalInfo'))->with('page', 'addresses');
    }
    // student address edit
    public function editStudentAddress($id)
    {
        // student personal profile
        $personalInfo = StudentInformation::findOrFail($id);
        // addresses
        $presentAddress   = $personalInfo->user()->singleAddress("STUDENT_PRESENT_ADDRESS");
        $permanentAddress = $personalInfo->user()->singleAddress("STUDENT_PERMANENT_ADDRESS");
        // country list
        $allCountry = Country::all();

        // return view with all variables
        return view('student::pages.student-profile.modals.address-update', compact('personalInfo', 'presentAddress', 'permanentAddress', 'allCountry'));
    }

    /////////////////////// online application address creation starts here  ////////////////////////////

    public function storeOnlineStudentAddress($userId, $applicantPersonalInfo)
    {
        // response data
        $addressCount = 0;
        // student address creation loop
        for ($i=0; $i<2; $i++) {

            // address
            $presentAddress = new Address();
            // store address details
            $presentAddress->user_id    = $userId;
            $presentAddress->type       = ($i==0?"STUDENT_PRESENT_ADDRESS":"STUDENT_PERMANENT_ADDRESS");
            $presentAddress->address    = ($i==0?$applicantPersonalInfo->add_pre_address:$applicantPersonalInfo->add_per_address);
            $presentAddress->street     = "Not available";
            $presentAddress->city_id    = ($i==0?$applicantPersonalInfo->add_pre_city:$applicantPersonalInfo->add_per_city);
            $presentAddress->state_id   = ($i==0?$applicantPersonalInfo->add_pre_state:$applicantPersonalInfo->add_per_state);
            $presentAddress->house      = "Not available";
            $presentAddress->phone      = ($i==0?$applicantPersonalInfo->add_pre_phone:$applicantPersonalInfo->add_per_phone);
            $presentAddress->zip        = 0000;
            $presentAddress->country_id = 1;
            $presentAddress->bn_village = '';
            $presentAddress->bn_postoffice = ($i==0?$applicantPersonalInfo->add_pre_post:$applicantPersonalInfo->add_per_post);
            $presentAddress->bn_upzilla = '';
            $presentAddress->bn_zilla = '';
            // save present address
            if($presentAddress->save()){
                $addressCount += 1;
            }
        }
        // checking address creation
        if($addressCount==2){
            return true;
        }else{
            return false;
        }
    }

/////////////////////// online application address creation ends here  ////////////////////////////

    // student address store
    public function storeStuentAddress(Request $request, $id)
    {
        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'present_address'   => 'required',
            'present_country'   => 'required',
            'present_state'     => 'required',
            'present_city'      => 'required',
            'present_house'     => 'required',
            'present_phone'     => 'required',
            'present_zipcode'   => 'required',
            'permanent_address' => 'required',
            'permanent_country' => 'required',
            'permanent_state'   => 'required',
            'permanent_city'    => 'required',
            'permanent_house'   => 'required',
            'permanent_phone'   => 'required',
            'permanent_zipcode' => 'required',
        ]);

        // storing requesting input data
        if ($validator->passes()) {
            // present address
            $presentAddress = new Address();
            // store present address
            $presentAddress->user_id    = $id;
            $presentAddress->type       = "STUDENT_PRESENT_ADDRESS";
            $presentAddress->address    = $request->input('present_address');
            $presentAddress->street     = "Not available";
            $presentAddress->city_id    = $request->input('present_city');
            $presentAddress->state_id   = $request->input('present_state');
            $presentAddress->house      = $request->input('present_house');
            $presentAddress->phone      = $request->input('present_phone');
            $presentAddress->zip        = $request->input('present_zipcode');
            $presentAddress->country_id = $request->input('present_country');
            $presentAddress->bn_village = $request->input('bn_village');
            $presentAddress->bn_postoffice = $request->input('bn_postoffice');
            $presentAddress->bn_upzilla = $request->input('bn_upzilla');
            $presentAddress->bn_zilla = $request->input('bn_zilla');
            // save present address
            $presentAdded = $presentAddress->save();

            // checking
            if ($presentAdded) {
                // permanent adderess
                $permanentAddress = new Address();
                // stroe permanent address
                $permanentAddress->user_id    = $id;
                $permanentAddress->type       = "STUDENT_PERMANENT_ADDRESS";
                $permanentAddress->address    = $request->input('permanent_address');
                $permanentAddress->street     = "Not available";
                $permanentAddress->city_id    = $request->input('permanent_city');
                $permanentAddress->state_id   = $request->input('permanent_state');
                $permanentAddress->house      = $request->input('permanent_house');
                $permanentAddress->phone      = $request->input('permanent_phone');
                $permanentAddress->zip        = $request->input('permanent_zipcode');
                $permanentAddress->country_id = $request->input('permanent_country');
                $presentAddress->bn_village = $request->input('bn_village');
                $presentAddress->bn_postoffice = $request->input('bn_postoffice');
                $presentAddress->bn_upzilla = $request->input('bn_upzilla');
                $presentAddress->bn_zilla = $request->input('bn_zilla');
                // save permanent address
                $permanentAdded = $permanentAddress->save();

                if ($permanentAdded) {
                    // session alert message
                    Session::flash('success', 'Student Address Added Successfully');
                    // redirect back
                    return redirect()->back();
                }
            }
        } else {
            Session::flash('warning', 'invalid Information. please try with correct Information');
            // receiving page action
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    // student profile address update
    public function updateStudentAddress(Request $request, $id)
    {
        // return $request->all();

        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'present_address'   => 'required',
            'present_country'   => 'required',
            'present_state'     => 'required',
            'present_city'      => 'required',
            'present_house'     => 'required',
            'present_phone'     => 'required',
            'present_zipcode'   => 'required',
            'permanent_address' => 'required',
            'permanent_country' => 'required',
            'permanent_state'   => 'required',
            'permanent_city'    => 'required',
            'permanent_house'   => 'required',
            'permanent_phone'   => 'required',
            'permanent_zipcode' => 'required',
        ]);

        // storing requesting input data
        if ($validator->passes()) {
            // student profile
            $personalInfo = StudentInformation::findOrFail($id);
            // present address
            $presentAddress = $personalInfo->user()->singleAddress("STUDENT_PRESENT_ADDRESS");
            if ($presentAddress) {
                $presentAddress->address    = $request->input('present_address');
                $presentAddress->street     = "Not available";
                $presentAddress->city_id    = $request->input('present_city');
                $presentAddress->state_id   = $request->input('present_state');
                $presentAddress->house      = $request->input('present_house');
                $presentAddress->phone      = $request->input('present_phone');
                $presentAddress->zip        = $request->input('present_zipcode');
                $presentAddress->country_id = $request->input('present_country');

                $presentAddress->bn_village = $request->input('bn_village');
                $presentAddress->bn_postoffice = $request->input('bn_postoffice');
                $presentAddress->bn_upzilla = $request->input('bn_upzilla');
                $presentAddress->bn_zilla = $request->input('bn_zilla');
                // save present address
                $presentAddressUpdated = $presentAddress->save();
                // checking
                if ($presentAddressUpdated) {
                    // Permanent address
                    $permanentAddress = $personalInfo->user()->singleAddress("STUDENT_PERMANENT_ADDRESS");
                    if ($permanentAddress) {
                        $permanentAddress->address    = $request->input('permanent_address');
                        $permanentAddress->street     = "Not available";
                        $permanentAddress->city_id    = $request->input('permanent_city');
                        $permanentAddress->state_id   = $request->input('permanent_state');
                        $permanentAddress->house      = $request->input('permanent_house');
                        $permanentAddress->phone      = $request->input('permanent_phone');
                        $permanentAddress->zip        = $request->input('permanent_zipcode');
                        $permanentAddress->country_id = $request->input('permanent_country');
                        $presentAddress->bn_village = $request->input('bn_village');
                        $presentAddress->bn_postoffice = $request->input('bn_postoffice');
                        $presentAddress->bn_upzilla = $request->input('bn_upzilla');
                        $presentAddress->bn_zilla = $request->input('bn_zilla');
                        // save permanent address
                        $permanentAddressUpdated = $permanentAddress->save();
                        // checking
                        if ($permanentAddressUpdated) {
                            Session::flash('success', 'Student Address Updated');
                            // receiving page action
                            return redirect()->back();
                        }
                    } else {
                        Session::flash('warning', 'unable to update present address');
                        // receiving page action
                        return redirect()->back()->withErrors($validator)->withInput();
                    }
                }
            } else {
                Session::flash('warning', 'unable to update present address');
                // receiving page action
                return redirect()->back()->withErrors($validator)->withInput();
            }
        } else {
            Session::flash('warning', 'invalid Information. please try with correct Information');
            // receiving page action
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }
}
