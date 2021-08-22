<?php

namespace Modules\Setting\Http\Controllers;

use App\Address;
use App\Models\Role;
use App\UserInfo;
use File;
use Modules\Academics\Entities\AcademicsYear;
use Redirect;
use Session;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Setting\Entities\Institute;
use Modules\Setting\Entities\Campus;
use Modules\Setting\Entities\InstituteAddress;
use Carbon\Carbon;
use App\User;
use Illuminate\Support\Facades\DB;


class InstituteController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $institutes=Institute::all();

        return view('setting::institute.index',compact('institutes'));
    }
    public function getAll()
    {

    }


    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('setting::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        $data=$this->getAll();
        return view('setting::institute.update',compact('data'));
    }



    public function add_institute()
    {
        return view('setting::institute.add');
    }
    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('setting::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public  function save_add_institute(Request $request)
    {
        // return $request->all();

        $institute =new Institute();
        // store requested profile name
        $institute->institute_name = $request->input('institute_name');
        $institute->bn_institute_name = $request->input('bn_institute_name');

        $institute->institute_alias = $request->input('institute_alias');
        $institute->address1 = $request->input('address1');

        $institute->address2 = $request->input('address2');
        $institute->bn_address = $request->input('bn_address');
        $institute->phone = $request->input('phone');

        $institute->email = $request->input('email');


        $institute->website = $request->input('website');
        $photoFile       = $request->file('logo');

        $contentName     = $photoFile->getClientOriginalName();

        $destinationPath = '/assets/users/images/';
        $photoFile->move(public_path().$destinationPath, $contentName);

        try
        {
            $institute->logo = $contentName;
            $saved = $institute->save();
            if($saved)
            {
                Session::flash('message', 'Success!Data has been saved successfully.');
            }
            else
            {
                Session::flash('message', 'Failed!Data has not been saved successfully.');
            }
        }
        catch (\Exception $e)
        {
            return $e->getMessage();
        }
        $institutes=Institute::all();
        return view('setting::institute.index',compact('institutes'));

    }
    public  function edit_institute_view($id)
    {
        $data = new Institute();
        $data = $data->where('id', $id)->get();
        return view('setting::institute.update',compact('data'));
    }

    public function institute_show($id)
    {

        $institutes = new Institute();
        $institute = $institutes->where('id', $id)->get();

        $campuses=new Campus();

        $campusesOfThis=$campuses->where('institute_id',$id)->get();

//        return $campusesOfThis;

        return view('setting::institute.view',compact('institute','campusesOfThis'));
    }

    public function store_update(Request $request,$id)
    {

        try
        {
            $institute = Institute::find($id);
            // store requested profile name
            $institute->institute_name = $request->input('institute_name');
            $institute->bn_institute_name = $request->input('bn_institute_name');
            $institute->institute_alias = $request->input('institute_alias');
            $institute->address1 = $request->input('address1');
            $institute->address2 = $request->input('address2');
            $institute->bn_address = $request->input('bn_address');
            $institute->phone = $request->input('phone');
            $institute->email = $request->input('email');
            $institute->eiin_code = $request->input('eiin_code');
            $institute->center_code = $request->input('center_code');
            $institute->institute_code = $request->input('institute_code');
            $institute->upazila_code = $request->input('upazila_code');
            $institute->zilla_code = $request->input('zilla_code');
            $institute->website = $request->input('website');
            $photoFile       = $request->file('logo');
            // checking
            if($photoFile){
                $fileExtension   = $photoFile->getClientOriginalExtension();
                $contentName     = $photoFile->getClientOriginalName();
                $contentFileName = $photoFile->getClientOriginalName();
                $destinationPath = 'assets/users/images/';
                $uploaded = $photoFile->move($destinationPath, $contentName);
                // input logo
                $institute->logo = $contentName;
            }
            // save institute
            $saved = $institute->save();

        }
        catch (\Exception $e)
        {
            return $e->getMessage();
        }


        // checking
        if($saved)
        {
            Session::flash('message', 'Success!Data has been updated successfully.');
            // return redirect
            return redirect()->back();
        }
        else
        {
            Session::flash('message', 'Failed!Data has not been updated successfully.');
            // return redirect
            return redirect()->back();
        }

    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function delete($id)
    {
        $institute = Institute::find($id)->delete();

//
//
//        try
//        {$saved = $institute->where('id', $id)->update(['deleted_at' => Carbon::now()]);
//
//
//            if($saved)
//            {
//                Session::flash('message', 'Success!Data has been deleted successfully.');
//            }
//            else
//            {
//                Session::flash('message', 'Failed!Data has not been deleted successfully.');
//            }
//        }
//        catch (\Exception $e)
//        {
//            return $e->getMessage();
//        }
//        $institutes=Institute::all();
        return redirect()->back();
    }

    // store institute
    public function storeInstitute(Request $request)
    {

        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'name'  => 'required|max:100',
            'phone' => 'required|max:100',
            'city' => 'required|max:100',
            'state' => 'required|max:100',
            'country' => 'required|max:100',
            'email' => 'required|email|max:100|unique:users',
        ]);

        // storing requesting input data
        if ($validator->passes()) {
            // Start transaction!
            DB::beginTransaction();

            // student user creation
            try {

                $instituteProfile = new Institute();
                // store requested profile name
                $instituteProfile->institute_name = $request->input('name');
                $instituteProfile->bn_institute_name = $request->input('bn_institute_name');
                $instituteProfile->institute_alias = $request->input('alias');
                $instituteProfile->address1 = $request->input('address');
                $instituteProfile->address2 = $request->input('address');
                $instituteProfile->bn_address = $request->input('bn_address');
                $instituteProfile->phone = $request->input('phone');
                $instituteProfile->email = $request->input('email');
                $instituteProfile->eiin_code = $request->input('eiin_code');
                $instituteProfile->center_code = $request->input('center_code');
                $instituteProfile->institute_code = $request->input('institute_code');
                $instituteProfile->upazila_code = $request->input('upazila_code');
                $instituteProfile->zilla_code = $request->input('zilla_code');
                // checking
                if($instituteProfile->save()){
                    //  address profile
                    $campusAddressProfile = new Address();
                    // input details
                    $campusAddressProfile->address = $request->input('address');
                    $campusAddressProfile->phone = $request->input('phone');
                    $campusAddressProfile->city_id = $request->input('city');
                    $campusAddressProfile->state_id = $request->input('state');
                    $campusAddressProfile->country_id = $request->input('country');
                    // save and checking
                    if($campusAddressProfile->save()){
                        // campus profile
                        $campusProfile = new Campus();
                        // input details
                        $campusProfile->address_id   = $campusAddressProfile->id;
                        $campusProfile->institute_id = $instituteProfile->id;
                        $campusProfile->name         = 'Main Campus';
                        $campusProfile->campus_code  = 01;
                        // checking
                        if($campusProfile->save()){
                            // now create institute address
                            $instituteAddress = (object)$this->manageInstituteAddress([
                                'id'=>0, // create institute address
                                'address'=>$request->input('address'),
                                'campus'=>$campusProfile->id,
                                'institute'=>$instituteProfile->id,
                                'city'=>$request->input('city'),
                                'state'=>$request->input('state'),
                                'country'=>$request->input('country')
                            ]);
                            // checking $instituteAddress
                            if($instituteAddress->status=='success'){

                                // create academic year
                                $academicYearProfile = new AcademicsYear();
                                // input academic year details
                                $academicYearProfile->year_name = date('Y').' (default) ';
                                $academicYearProfile->start_date = date('Y-m-d', strtotime('01-01-'.date('Y')));
                                $academicYearProfile->end_date = date('Y-m-d', strtotime('31-12-'.date('Y')));
                                $academicYearProfile->status = 1;
                                $academicYearProfile->institute_id = $instituteProfile->id;
                                $academicYearProfile->campus_id = $campusProfile->id;
                                // checking
                                if($academicYearProfile->save()){
                                    // now create user profile
                                    $adminProfile = new User();
                                    // input user details
                                    $adminProfile->name = $request->input('admin-name');
                                    $adminProfile->email = $request->input('admin-email');
                                    $adminProfile->password = bcrypt(123456);
                                    // checking user profile
                                    if($adminProfile->save()){
                                        $userInfoProfile = new UserInfo();
                                        // add user details
                                        $userInfoProfile->user_id = $adminProfile->id;
                                        $userInfoProfile->institute_id = $instituteProfile->id;
                                        $userInfoProfile->campus_id = $campusProfile->id;
                                        // save user Info profile
                                        // checking
                                        if($userInfoProfile->save()){
                                            // adminRoleProfile
                                            $adminRoleProfile = Role::where('name', 'admin')->first();
                                            // assigning student role to this user
                                            $adminProfile->attachRole($adminRoleProfile);

                                            // If we reach here, then data is valid and working. Commit the queries!
                                            DB::commit();
                                            // return back
                                            return redirect()->back();
                                        }else{
                                            Session::flash('warning', 'Unable to add user info');
                                            // receiving page action
                                            return redirect()->back();
                                        }
                                    }else{
                                        Session::flash('warning', 'Unable to add user profile');
                                        // receiving page action
                                        return redirect()->back();
                                    }
                                }else{
                                    Session::flash('warning', 'Unable to add academic year');
                                    // receiving page action
                                    return redirect()->back();
                                }
                            }else{
                                Session::flash('warning', $instituteAddress->msg);
                                // receiving page action
                                return redirect()->back();
                            }
                        }else{
                            Session::flash('warning', 'Unable to add campus');
                            // receiving page action
                            return redirect()->back();
                        }
                    }else{
                        Session::flash('warning', 'Unable to add address');
                        // receiving page action
                        return redirect()->back();
                    }
                }else{
                    Session::flash('warning', 'Unable to add institute');
                    // receiving page action
                    return redirect()->back();
                }
            } catch (ValidationException $e) {
                // Rollback and then redirect back to form with errors
                DB::rollback();
                return redirect()->back();
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }
        } else {
            Session::flash('warning', 'Invalid Information');
            // receiving page action
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }


    ////////////////////////  ajax request //////////////////
    public function findInstituteBySearchTerm(Request $request)
    {
        //get search term and request details
        $searchTerm = $request->input('term');
        // institute list
        $instituteList = Institute::where('institute_name', 'like', "%".$searchTerm."%")->orwhere('institute_alias', 'like', "%" .$searchTerm."%")->get();
        // checking
        if ($instituteList) {
            // institute array list for response
            $instituteArrayList = array();
            // institute list
            foreach ($instituteList as $institute) {
                // store into data set
                $instituteArrayList[] = [
                    'id'=>$institute->id,
                    'name'=>$institute->institute_name." (".$institute->institute_alias.") "
                ];
            }
            // return institute array list
            return json_encode($instituteArrayList);
        }
    }


    public function manageInstituteAddress($address)
    {
        $address = (object)$address;
        // checking address id
        if($address->id>0){
            $addressProfile = InstituteAddress::find($address->id);
        }else{
            $addressProfile = new InstituteAddress();
        }
        // input address details
        if($address->address) $addressProfile->address = $address->address;
        if($address->campus) $addressProfile->campus_id = $address->campus;
        if($address->institute) $addressProfile->institute_id = $address->institute;
        if($address->city) $addressProfile->city_id = $address->city;
        if($address->state) $addressProfile->state_id = $address->state;
        if($address->country) $addressProfile->country_id = $address->country;
        // checking
        if($addressProfile->save()){
            return ['status'=>'success', 'id'=>$addressProfile->id, 'msg'=>'Institute Address submitted'];
        }else{
            return ['status'=>'failed', 'msg'=>'Unable to submit Institute Address'];
        }
    }
}
