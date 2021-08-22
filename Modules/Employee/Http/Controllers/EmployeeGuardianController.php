<?php

namespace Modules\Employee\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Employee\Entities\EmployeeGuardian;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\Student\Entities\StudentGuardian;
use Modules\Student\Entities\StudentInformation;
use Modules\Student\Entities\StudentParent;
use Redirect;
use Session;
use Validator;

class EmployeeGuardianController extends Controller
{

    // Display a listing of the resource.
    public function show($id)
    {
        // find employee profile
        $employeeInfo = EmployeeInformation::findOrFail($id);
        // return view
        return view('employee::pages.profile.guardian', compact('employeeInfo'))->with('page', 'guardian');
    }

    // Show the form for creating a new resource.
    public function create($id)
    {
        return view('employee::pages.modals.guardian-create')->with('emp_id', $id);
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'first_name'     => 'required',
            'last_name'      => 'required|max:100',
            'email'          => 'required|email|max:100|unique:users',
            'guardian_photo'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:200',
            'guardian_signature'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:200',
            'nid_file'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:200',
            'birth_file'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:200',
            'tin_file'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:200',
            'passport_file'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:200',
            'driving_lic_file'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:200',
        ]);

        // storing requesting input data
        if ($validator->passes()) {
            // Start transaction!
            DB::beginTransaction();
//
            try {
                // std id
                $empId = $request->input('emp_id');
                $employeeUser = EmployeeInformation::findOrFail($empId)->user();

                // Guardian Institute
                $institute_info = [];
                foreach ($request->institute_name as $key => $institute) {
                    $thisInstitute = [];
                    $thisInstitute['institute_name'] = $institute;
                    $thisInstitute['certificate_name'] = $request->certificate_name[$key];
                    $thisInstitute['passing_year'] = $request->passing_year[$key];
                    if (isset($request->certificate_file[$key])) {
                        $photoFileName = $institute . '-' . time() . '.' . $request->certificate_file[$key]->extension();
                        $request->certificate_file[$key]->move(public_path('family-attachment'), $photoFileName);
                        $thisInstitute['certificate_file'] = $photoFileName;
                    } else {
                        $thisInstitute['certificate_file'] = null;
                    }

                    array_push($institute_info, $thisInstitute);
                }

                // guardian profile
                $photoFileName = null;
                $signatureFileName = null;
                $nidFileName = null;
                $birthFileName = null;
                $tinFileName = null;
                $passportFileName = null;
                $drivingLicFileName = null;
                // Guardian Photo
                if ($request->hasFile('guardian_photo')) {
                    $photoFileName = 'photo-' . time() . '.' . $request->guardian_photo->extension();
                    $request->guardian_photo->move(public_path('family'), $photoFileName);
                }
                // Guardian Signature
                if ($request->hasFile('guardian_signature')) {
                    $signatureFileName = 'signature-' . time() . '.' . $request->guardian_signature->extension();
                    $request->guardian_signature->move(public_path('family'), $signatureFileName);
                }
                // NID File
                if ($request->hasFile('nid_file')) {
                    $nidFileName = 'nid-' . time() . '.' . $request->nid_file->extension();
                    $request->nid_file->move(public_path('family-attachment'), $nidFileName);
                }
                // Birth File
                if ($request->hasFile('birth_file')) {
                    $birthFileName = 'birth-' . time() . '.' . $request->birth_file->extension();
                    $request->birth_file->move(public_path('family-attachment'), $birthFileName);
                }
                // Tin File
                if ($request->hasFile('tin_file')) {
                    $tinFileName = 'tin-' . time() . '.' . $request->tin_file->extension();
                    $request->tin_file->move(public_path('family-attachment'), $tinFileName);
                }
                // Passport File
                if ($request->hasFile('passport_file')) {
                    $passportFileName = 'passport-' . time() . '.' . $request->passport_file->extension();
                    $request->passport_file->move(public_path('family-attachment'), $passportFileName);
                }
                // Driving Lic File
                if ($request->hasFile('driving_lic_file')) {
                    $drivingLicFileName = 'drivingLic-' . time() . '.' . $request->driving_lic_file->extension();
                    $request->driving_lic_file->move(public_path('family-attachment'), $drivingLicFileName);
                }

                $guardianProfile = StudentGuardian::create($request->except('date_of_birth', 'guardian_photo', 'guardian_signature', 'nid_file', 'birth_file', 'tin_file', 'passport_file', 'driving_lic_file', 'institute_info') + [
                        'date_of_birth' => Carbon::parse($request->date_of_birth),
                        'guardian_photo' => $photoFileName,
                        'guardian_signature' => $signatureFileName,
                        'nid_file' => $nidFileName,
                        'birth_file' => $birthFileName,
                        'tin_file' => $tinFileName,
                        'passport_file' => $passportFileName,
                        'driving_lic_file' => $drivingLicFileName,
                        'institute_info' => json_encode($institute_info)
                    ]);

                if ($guardianProfile){
                    $studentParentProfile = StudentParent::create([
                        'gud_id'=>$guardianProfile->id,
                        'emp_id'=>$empId,
                    ]);
                }

                DB::commit();
                // success message
                Session::flash('success', 'Employee Family Added Successfully');
                return back();
            } catch (ValidationException $e) {
                // Rollback and then redirect
                // back to form with errors
                DB::rollback();
                return redirect()->back()->withErrors($e->getErrors())->withInput();
            }
        } else {
            // warning message
            Session::flash('warning', "invalid Information. Please try with correct Information");
            // return to the previous link
            return redirect()->back();
        }
    }

    // Show the form for editing the specified resource.
    public function edit($id)
    {
        // guardain profile
        $guardian = StudentGuardian::findOrFail($id);
        // return view
        return view('employee::pages.modals.guardian-update', compact('guardian'));
    }

    // Update the specified resource in storage.
    public function update(Request $request, $id)
    {
        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'first_name'     => 'required',
            'last_name'      => 'required|max:100',
            'email'          => 'required|email|max:100|unique:users',
            'guardian_photo'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:200',
            'guardian_signature'          => 'image|mimes:jpeg,png,jpg,gif,svg|max:200',
            'nid_file'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:200',
            'birth_file'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:200',
            'tin_file'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:200',
            'passport_file'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:200',
            'driving_lic_file'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:200',
        ]);

        // guardian profile
        $guardianProfile = StudentGuardian::findOrFail($id);
        // checking
        if ($guardianProfile) {
            // Start transaction!
            DB::beginTransaction();

            if($guardianProfile->email != $request->input('email')){
                // validating all requested input data
                $validator = Validator::make(['email'=>$request->input('email')], [
                    'email' => 'required|email|max:100|unique:users',
                ]);
                // storing requesting input data
                if ($validator->passes()) {
                    // update guardian email
                    $guardianProfileUpdated = $guardianProfile->update([
                        'email' => $request->email
                    ]);
                }else{
                    Session::flash('warning', 'email already exits');
                    return redirect()->back();
                }
            }

            // Guardian Institute
            $oldInstituteInfos = json_decode($guardianProfile->institute_info,1);
            foreach($oldInstituteInfos as $oldInstituteInfo){
                if ($oldInstituteInfo['certificate_file']){
                    $file_path = public_path().'/family-attachment/'.$oldInstituteInfo['certificate_file'];
                    unlink($file_path);
                }
            }
            $institute_info = [];
            foreach ($request->institute_name as $key => $institute){
                $thisInstitute = [];
                $thisInstitute['institute_name'] = $institute;
                $thisInstitute['certificate_name'] = $request->certificate_name[$key];
                $thisInstitute['passing_year'] = $request->passing_year[$key];
                if (isset($request->certificate_file[$key])){
                    $photoFileName = $institute.'-'.time().'.'.$request->certificate_file[$key]->extension();
                    $request->certificate_file[$key]->move(public_path('family-attachment'), $photoFileName);
                    $thisInstitute['certificate_file'] = $photoFileName;
                }else{
                    $thisInstitute['certificate_file'] = null;
                }

                array_push($institute_info, $thisInstitute);
            }

            // guardian profile
            $photoFileName = $guardianProfile->guardian_photo;
            $signatureFileName = $guardianProfile->guardian_signature;
            $nidFileName = $guardianProfile->nid_file;
            $birthFileName = $guardianProfile->birth_file;
            $tinFileName = $guardianProfile->tin_file;
            $passportFileName = $guardianProfile->passport_file;
            $drivingLicFileName = $guardianProfile->driving_lic_file;
            // Guardian Photo
            if ($request->hasFile('guardian_photo')) {
                if ($guardianProfile->guardian_photo) {
                    $file_path = public_path().'/family/'.$guardianProfile->guardian_photo;
                    unlink($file_path);
                }
                $photoFileName = 'photo-'.time().'.'.$request->guardian_photo->extension();
                $request->guardian_photo->move(public_path('family'), $photoFileName);
            }
            // Guardian Signature
            if ($request->hasFile('guardian_signature')) {
                if ($guardianProfile->guardian_signature) {
                    $file_path = public_path().'/family/'.$guardianProfile->guardian_signature;
                    unlink($file_path);
                }
                $signatureFileName = 'signature-'.time().'.'.$request->guardian_signature->extension();
                $request->guardian_signature->move(public_path('family'), $signatureFileName);
            }
            // NID File
            if ($request->hasFile('nid_file')) {
                if ($guardianProfile->nid_file) {
                    $file_path = public_path().'/family-attachment/'.$guardianProfile->nid_file;
                    unlink($file_path);
                }
                $nidFileName = 'nid-'.time().'.'.$request->nid_file->extension();
                $request->nid_file->move(public_path('family-attachment'), $nidFileName);
            }
            // Birth File
            if ($request->hasFile('birth_file')) {
                if ($guardianProfile->birth_file) {
                    $file_path = public_path().'/family-attachment/'.$guardianProfile->birth_file;
                    unlink($file_path);
                }
                $birthFileName = 'birth-'.time().'.'.$request->birth_file->extension();
                $request->birth_file->move(public_path('family-attachment'), $birthFileName);
            }
            // Tin File
            if ($request->hasFile('tin_file')) {
                if ($guardianProfile->tin_file) {
                    $file_path = public_path().'/family-attachment/'.$guardianProfile->tin_file;
                    unlink($file_path);
                }
                $tinFileName = 'tin-'.time().'.'.$request->tin_file->extension();
                $request->tin_file->move(public_path('family-attachment'), $tinFileName);
            }
            // Passport File
            if ($request->hasFile('passport_file')) {
                if ($guardianProfile->passport_file) {
                    $file_path = public_path().'/family-attachment/'.$guardianProfile->passport_file;
                    unlink($file_path);
                }
                $passportFileName = 'passport-'.time().'.'.$request->passport_file->extension();
                $request->passport_file->move(public_path('family-attachment'), $passportFileName);
            }
            // Driving Lic File
            if ($request->hasFile('driving_lic_file')) {
                if ($guardianProfile->driving_lic_file) {
                    $file_path = public_path().'/family-attachment/'.$guardianProfile->driving_lic_file;
                    unlink($file_path);
                }
                $drivingLicFileName = 'drivingLic-'.time().'.'.$request->driving_lic_file->extension();
                $request->driving_lic_file->move(public_path('family-attachment'), $drivingLicFileName);
            }

            $guardianProfileUpdated = $guardianProfile->update($request->except('email', 'date_of_birth', 'guardian_photo', 'guardian_signature', 'nid_file', 'birth_file', 'tin_file', 'passport_file', 'driving_lic_file') + [
                    'date_of_birth' => Carbon::parse($request->date_of_birth),
                    'guardian_photo' => $photoFileName,
                    'guardian_signature' => $signatureFileName,
                    'nid_file' => $nidFileName,
                    'birth_file' => $birthFileName,
                    'tin_file' => $tinFileName,
                    'passport_file' => $passportFileName,
                    'driving_lic_file' => $drivingLicFileName,
                    'institute_info' => json_encode($institute_info)
                ]);

            // If we reach here, then data is valid and working.  Commit the queries!
            DB::commit();
            // success message
            Session::flash('success', 'Employee family member Updated');
            // return back
            return redirect()->back();
        } else {
            // success message
            Session::flash('warning', 'Unable to update family member');
            // return back
            return redirect()->back();
        }
    }

    // Remove the specified resource from storage.
    public function destroy($id)
    {
        // guardian profile
        $guardianProfile = StudentGuardian::findOrFail($id);
        // checking
        if ($guardianProfile->is_emergency == 1) {
            $studentProfile = $guardianProfile->student();
            //variable
            $count = 1;
            // looping
            foreach ($studentProfile->allGuardian() as $guardian) {
                if ($guardian->id == $id) {
                    continue;
                } else {
                    $guardian->is_emergency = 1;
                    // save update
                    $guardianUpdated = $guardian->save();

                    if ($count == 1) {
                        break;
                    }
                }
            }
        }

        $deleteStudentParent = StudentParent::where('gud_id', $guardianProfile->id)->first()->delete();

        if ($deleteStudentParent) {
            // delete guardian profile
            // Guardian Photo
            if ($guardianProfile->guardian_photo) {
                $file_path = public_path().'/family/'.$guardianProfile->guardian_photo;
                unlink($file_path);
            }
            // Guardian Signature
            if ($guardianProfile->guardian_signature) {
                $file_path = public_path().'/family/'.$guardianProfile->guardian_signature;
                unlink($file_path);
            }
            // NID File
            if ($guardianProfile->nid_file) {
                $file_path = public_path().'/family-attachment/'.$guardianProfile->nid_file;
                unlink($file_path);
            }
            // Birth File
            if ($guardianProfile->birth_file) {
                $file_path = public_path().'/family-attachment/'.$guardianProfile->birth_file;
                unlink($file_path);
            }
            // Tin File
            if ($guardianProfile->tin_file) {
                $file_path = public_path().'/family-attachment/'.$guardianProfile->tin_file;
                unlink($file_path);
            }
            // Passport File
            if ($guardianProfile->passport_file) {
                $file_path = public_path().'/family-attachment/'.$guardianProfile->passport_file;
                unlink($file_path);
            }
            // Driving Lic File
            if ($guardianProfile->driving_lic_file) {
                $file_path = public_path().'/family-attachment/'.$guardianProfile->driving_lic_file;
                unlink($file_path);
            }
            $guardianProfileDeleted = $guardianProfile->delete();
        } else {
            $guardianProfileDeleted = null;
        }

        // checking
        if ($guardianProfileDeleted) {
            // success message
            Session::flash('success', 'Employee Family member deleted Successfully');
            // return back
            return redirect()->back();
        } else {
            // warning message
            Session::flash('warning', 'Unable to delete family member');
            // return back
            return redirect()->back();
        }
    }

    // set emergency contact
    public function status($id)
    {
        if ($employeeGuardian = EmployeeGuardian::findOrFail($id)) {
            // student profile
            $employeeProfile = $employeeGuardian->employee();
            // looping
            foreach ($employeeProfile->allGuardian() as $guardian) {
                //checking
                if ($guardian->id == $id) {
                    // update emergency
                    $guardian->is_emergency = 1;
                    // save update
                    $guardianUpdated = $guardian->save();
                } else {
                    // update emergency
                    $guardian->is_emergency = 0;
                    // save update
                    $guardianUpdated = $guardian->save();
                }
            }

            // success msg
            Session::flash('success', 'Emergency Contact Changed');
            // return back
            return redirect()->back();
        } else {
            // warning msg
            Session::flash('warning', 'Unable to set emergency contact');
            // return back
            return redirect()->back();
        }
    }
}
