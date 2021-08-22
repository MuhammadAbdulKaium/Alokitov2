<?php

namespace Modules\Employee\Http\Controllers;

use App\Address;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\Setting\Entities\Country;
use Redirect;
use Session;
use Validator;

class EmployeeAddressController extends Controller
{
    public function showEmployeeAddress($id)
    {
        // employe profile
        $employeeInfo = EmployeeInformation::findOrfail($id);
        // return view
        return View('employee::pages.profile.address', compact('employeeInfo'))->with('page', 'address');
    }

    // edit employee address
    public function editEmployeeAddress($id)
    {
        // employee personal profile
        $employeeInfo = EmployeeInformation::findOrFail($id);
        // addresses
        $presentAddress   = $employeeInfo->user()->singleAddress("EMPLOYEE_PRESENT_ADDRESS");
        $permanentAddress = $employeeInfo->user()->singleAddress("EMPLOYEE_PERMANENT_ADDRESS");
        // all Country
        $allCountry = Country::all();
        // return view
        return view('employee::pages.modals.address-update', compact('employeeInfo', 'presentAddress', 'permanentAddress', 'allCountry'));
    }

    // store employee address
    public function storeEmployeeAddress(Request $request)
    {
        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'emp_id'            => 'required',
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
            $presentAddress->user_id = $request->input('emp_id');
            $presentAddress->type    = "EMPLOYEE_PRESENT_ADDRESS";
            $presentAddress->address = $request->input('present_address');
            $presentAddress->street  = "Not available";
            $presentAddress->city_id    = $request->input('present_city');
            $presentAddress->state_id   = $request->input('present_state');
            $presentAddress->house   = $request->input('present_house');
            $presentAddress->phone   = $request->input('present_phone');
            $presentAddress->zip     = $request->input('present_zipcode');
            $presentAddress->country_id = $request->input('present_country');
            // save present address
            $presentAdded = $presentAddress->save();

            // checking
            if ($presentAdded) {
                // permanent adderess
                $permanentAddress = new Address();
                // stroe permanent address
                $permanentAddress->user_id = $request->input('emp_id');
                $permanentAddress->type    = "EMPLOYEE_PERMANENT_ADDRESS";
                $permanentAddress->address = $request->input('permanent_address');
                $permanentAddress->street  = "Not available";
                $permanentAddress->city_id    = $request->input('permanent_city');
                $permanentAddress->state_id   = $request->input('permanent_state');
                $permanentAddress->house   = $request->input('permanent_house');
                $permanentAddress->phone   = $request->input('permanent_phone');
                $permanentAddress->zip     = $request->input('permanent_zipcode');
                $permanentAddress->country_id = $request->input('permanent_country');
                // save permanent address
                $permanentAdded = $permanentAddress->save();

                if ($permanentAdded) {
                    // session alert message
                    Session::flash('success', 'Employee Address Added Successfully');
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

    // update employee address
    public function updateEmployeeAddress(Request $request, $id)
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
            // student profile
            $employeeInfo = EmployeeInformation::findOrFail($id);
            // present address
            $presentAddress = $employeeInfo->user()->singleAddress("EMPLOYEE_PRESENT_ADDRESS");
            if ($presentAddress) {
                $presentAddress->address = $request->input('present_address');
                $presentAddress->street  = "Not available";
                $presentAddress->city_id    = $request->input('present_city');
                $presentAddress->state_id   = $request->input('present_state');
                $presentAddress->house   = $request->input('present_house');
                $presentAddress->phone   = $request->input('present_phone');
                $presentAddress->zip     = $request->input('present_zipcode');
                $presentAddress->country_id = $request->input('present_country');
                // save present address
                $presentAddressUpdated = $presentAddress->save();
                // checking
                if ($presentAddressUpdated) {
                    // Permanent address
                    $permanentAddress = $employeeInfo->user()->singleAddress("EMPLOYEE_PERMANENT_ADDRESS");
                    if ($permanentAddress) {
                        $permanentAddress->address = $request->input('permanent_address');
                        $permanentAddress->street  = "Not available";
                        $permanentAddress->city_id    = $request->input('permanent_city');
                        $permanentAddress->state_id   = $request->input('permanent_state');
                        $permanentAddress->house   = $request->input('permanent_house');
                        $permanentAddress->phone   = $request->input('permanent_phone');
                        $permanentAddress->zip     = $request->input('permanent_zipcode');
                        $permanentAddress->country_id = $request->input('permanent_country');
                        // save permanent address
                        $permanentAddressUpdated = $permanentAddress->save();
                        // checking
                        if ($permanentAddressUpdated) {
                            Session::flash('success', 'Employee Address Updated');
                            // receiving page action
                            return redirect()->back();
                        }
                    } else {
                        Session::flash('warning', 'unable to update present address');
                        // receiving page action
                        return redirect()->back();
                    }
                }
            } else {
                Session::flash('warning', 'unable to perform the action');
                // receiving page action
                return redirect()->back();
            }
        } else {
            Session::flash('warning', 'invalid Information. please try with correct Information');
            // receiving page action
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }
}
