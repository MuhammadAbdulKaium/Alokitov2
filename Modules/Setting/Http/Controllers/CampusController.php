<?php

namespace Modules\Setting\Http\Controllers;

//use Faker\Provider\Address;
use App\Address;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Setting\Entities\Campus;
use Modules\Setting\Entities\City;
use Modules\Setting\Entities\Country;
use Modules\Setting\Entities\Institute;
use Modules\Setting\Entities\InstituteAddress;
use Modules\Setting\Entities\State;
use Session;
use Validator;
use App\UserInfo;
use App\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Modules\Academics\Entities\AcademicsYear;

class CampusController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $pageTitle    = "Country Information";
        $insertOrEdit = 'insert'; //To identify insert
        $data         = $this->getAll();
        return view('setting::country.index', compact('data', 'insertOrEdit', 'pageTitle'));
    }
    public function getAll()
    {
        $data = new Country();
        return $data->where('is_deleted', '0')->orderBy('id', 'desc')->get();
    }
    public function add_campus($institute_id)
    {
        $cities    = City::all();
        $states    = State::all();
        $countries = Country::all();
        return view('setting::campus.add_campus', compact('cities', 'states', 'countries', 'institute_id'));
    }


    public function save_add_campus(Request $request, $institute_id)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|max:100',
            'campus_code' => 'required|max:100|unique:setting_campus',
            'address'     => 'required',
            'house'       => 'required',
            'street'      => 'required',
            'city_id'     => 'required',
            'state_id'    => 'required',
            'zip'         => 'required',
            'country_id'  => 'required',
            'phone'       => 'required',
            'campus-admin-name' => 'required',
            'email' => 'required|email|max:100|unique:users',
        ]);

        if ($validator->passes()) {
            // Start transaction!
            DB::beginTransaction();
            // try catch
            try{
                // admin details
                $adminName = $request->input('campus-admin-name');
                $adminEmail = $request->input('email');

                // create address
                $address             = new Address();
                $address->user_id    = 1;
                $address->address    = $request->input('address');
                $address->house      = $request->input('house');
                $address->street     = $request->input('street');
                $address->city_id    = $request->input('city_id');
                $address->state_id   = $request->input('state_id');
                $address->zip        = $request->input('zip');
                $address->country_id = $request->input('country_id');
                $address->phone      = $request->input('phone');
                // checking
                if($address->save()){
                    $campus              = new Campus();
                    $campus->address_id  = $address->id;
                    $campus->name        = $request->name;
                    $campus->campus_code = $request->campus_code;
                    $campus->institute_id = $institute_id;
                    // checking
                    if($campus->save()){
                        // now create institute address
                        $instituteAddress = (object)$this->manageInstituteAddress([
                            'id'=>0, // create institute address
                            'address'=>$request->input('address'),
                            'campus'=>$campus->id,
                            'institute'=>$institute_id,
                            'city'=>$request->input('city_id'),
                            'state'=>$request->input('state_id'),
                            'country'=>$request->input('country_id')
                        ]);
                        // checking $instituteAddress
                        if($instituteAddress->status=='success'){
                            // create academic year
                            $academicYearProfile = $this->createAcademicYear($institute_id, $campus);
                            // checking
                            if($academicYearProfile){
                                // create admin profile
                                $adminProfile = $this->crateAdmin($adminName, $adminEmail);
                                // checking user profile
                                if($adminProfile){
                                    // create user info
                                    $userInfoProfile = $this->createUserInfo($institute_id, $adminProfile, $campus);
                                }else{
                                    Session::flash('warning', 'Unable to add user profile');
                                    // receiving page action
                                    return redirect()->back();
                                }
                            }else{
                                Session::flash('warning', 'Unable to add academic Year');
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
            } catch (ValidationException $e) {
                // Rollback and then redirect back to form with errors
                DB::rollback();
                return redirect()->back();
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }
            // If we reach here, then data is valid and working.
            // Commit the queries!
            DB::commit();
            // success msg
            Session::flash('success', 'Campus created');
            // return back
            return redirect()->back();
        } else {
            Session::flash('warning', 'Invalid Information');
            // receiving page action
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    public function campus_edit_save(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'name'        => 'required',
            'campus_code' => 'required',
            'address'     => 'required',
            'house'       => 'required',
            'street'      => 'required',
            'city_id'     => 'required',
            'state_id'    => 'required',
            'zip'         => 'required',
            'country_id'  => 'required',
            'phone'       => 'required',

        ]);

        if ($validator->passes()) {

            $address             = Address::find($request->address_id);
            $address->user_id    = 1;
            $address->address    = $request->input('address');
            $address->house      = $request->input('house');
            $address->street     = $request->input('street');
            $address->city_id    = $request->input('city_id');
            $address->state_id   = $request->input('state_id');
            $address->zip        = $request->input('zip');
            $address->country_id = $request->input('country_id');
            $address->phone      = $request->input('phone');

//            return $address;

//            $campus=Campus();
            //            $campus->address_id=$address->id;
            //            $campus->name=$request->name;
            //            $campus->campus_code=$request->campus_code;

            // save new profile
            try
            {
                $institute_id = $request->institute_id;
                $address->save();

                $campus = Campus::find($id);

                $campus->name        = $request->name;
                $campus->campus_code = $request->campus_code;

                // return $campus;

                $saved = $campus->save();
                if ($saved) {
                    // now get inst address profile
                    if($instAddress = $campus->instAddress()){
                        // now create institute address
                        $this->manageInstituteAddress([
                            'id'=>$instAddress->id, // create institute address
                            'address'=>$request->input('address'),
                            'city'=>$request->input('city_id'),
                            'state'=>$request->input('state_id'),
                            'country'=>$request->input('country_id')
                        ]);
                    }


                    Session::flash('message', 'Success!Data has been updated successfully.');
                } else {
                    Session::flash('message', 'Success!Data has not been updated successfully.');
                }
            } catch (\Exception $e) {

                return $e->getMessage();

            }

            $institutes = new Institute();
            $institute  = $institutes->where('id', $institute_id)->get();

            $campuses = new Campus();

            $campusesOfThis = $campuses->where('institute_id', $institute_id)->get();

//        return $campusesOfThis;

            return view('setting::institute.view', compact('institute', 'campusesOfThis', 'institute_id'));

        } else {
            // Session::flash('warning', 'unable to crate student profile');
            // receiving page action
            return redirect()->back()->withErrors($validator)->withInput();
        }

    }
    public function campus_show($id)
    {
        $pageTitle = 'Campus Information';

        $cities    = City::all();
        $states    = State::all();
        $countries = Country::all();
        $campus    = new Campus();
        $campus    = $campus->where('id', $id)->get();
        return view('setting::campus.view', compact('cities', 'states', 'countries', 'campus', 'pageTitle'));

    }
    public function edit_campus_view($id)
    {
        $pageTitle = 'Campus Information';

        $cities    = City::all();
        $states    = State::all();
        $countries = Country::all();
        $campus    = new Campus();
        $campus    = $campus->where('id', $id)->get();
        return view('setting::campus.update', compact('cities', 'states', 'countries', 'campus', 'pageTitle'));

    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id)
    {
        $pageTitle = 'Country Informations';
//        $data = newCountry();

        $data         = new Country();
        $data         = $data->where('id', $id)->get();
        $insertOrEdit = 'edit';
        return view('setting::country.view', compact('insertOrEdit', 'editdata', 'data', 'pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        //   return view('academics::edit');
        //die;
        $data         = new Country();
        $editdata     = $data->where('id', $id)->get();
        $data         = $this->getAll();
        $insertOrEdit = 'edit';

        return view('setting::country.index', compact('insertOrEdit', 'editdata', 'data'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100|unique:setting_country',
        ]);
        if ($validator->passes()) {
            $Country = Country::find($id);
            try {
                $saved = $Country->update($request->all());
                if ($saved) {
                    Session::flash('message', 'Success!Data has been updated successfully.');
                } else {
                    Session::flash('message', 'Failed!Data has not been updated successfully.');
                }
            } catch (\Exception $e) {

                return $e->getMessage();

            }
            $data         = $this->getAll();
            $insertOrEdit = 'insert';

            return view('setting::country.index', compact('insertOrEdit', 'editdata', 'data'));
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }
    public function delete($id)
    {
        $campus = new Campus();

        try
        {
            $saved = $campus->where('id', $id)->update(['deleted_at' => Carbon::now()]);
            if ($saved) {
                Session::flash('message', 'Success!Data has been deleted successfully.');
            } else {
                Session::flash('message', 'Failed!Data has not been deleted successfully.');
            }
        } catch (\Exception $e) {

            return $e->getMessage();

        }
        $campus     = Campus::find($id);
        $institutes = new Institute();
        $institute  = $institutes->where('id', $campus->institute_id)->get();

        $campuses = new Campus();

        $campusesOfThis = $campuses->where('institute_id', $campus->institute_id)->get();
        $institute_id   = $campus->institute_id;

//        return $campusesOfThis;

        return view('setting::institute.view', compact('institute', 'campusesOfThis', 'institute_id'));
    }


    // get campus list by institute id

    public function getCampusListByInstitueId(Request $request)
    {
        $campus = Campus::where('institute_id', $request->id)->get();
        //then sent this data to ajax success
        return response()->json($campus);
    }

    public function storeCampusBySuperAdmin(Request $request)
    {
//        return $request->all();
        $validator = Validator::make($request->all(), [
            'name'        => 'required|max:100',
            'campus_code' => 'required|max:100|unique:setting_campus',
            'admin-name' => 'required',
            'city' => 'required|max:100',
            'state' => 'required|max:100',
            'country' => 'required|max:100',
            'address' => 'required|max:500',
            'email' => 'required|email|max:100|unique:users',
        ]);

        if ($validator->passes()) {
            // Start transaction!
            DB::beginTransaction();
            // try catch
            try{
                // admin details
                $institute = $request->input('institute');
                $campusName = $request->input('name');
                $campusCode = $request->input('campus_code');
                $adminName = $request->input('admin-name');
                $adminEmail = $request->input('email');
                $city = $request->input('city');
                $state = $request->input('state');
                $country = $request->input('country');
                $address = $request->input('address');

                // create campus address
                $campusAddressProfile = new Address();
                // input details
                $campusAddressProfile->address = $address;
                $campusAddressProfile->city_id = $city;
                $campusAddressProfile->state_id = $state;
                $campusAddressProfile->country_id = $country;
                // save and checking
                if($campusAddressProfile->save()){
                    // create campus profile
                    $campusProfile  = new Campus();
                    $campusProfile->address_id  = $campusAddressProfile->id;
                    $campusProfile->name        = $campusName;
                    $campusProfile->campus_code = $campusCode;
                    $campusProfile->institute_id = $institute;
                    // checking
                    if($campusProfile->save()){
                        // now create institute address
                        $instituteAddress = (object)$this->manageInstituteAddress([
                            'id'=>0, // create institute address
                            'address'=>$address,
                            'campus'=>$campusProfile->id,
                            'institute'=>$institute,
                            'city'=>$city,
                            'state'=>$state,
                            'country'=>$country
                        ]);
                        // checking
                        if($instituteAddress->status=='success'){
                            // create academic year
                            $academicYearProfile = $this->createAcademicYear($institute, $campusProfile);
                            // checking
                            if($academicYearProfile){
                                // create admin profile
                                $adminProfile = $this->crateAdmin($adminName, $adminEmail);
                                // checking user profile
                                if($adminProfile){
                                    // create user info
                                    $userInfoProfile = $this->createUserInfo($institute, $adminProfile, $campusProfile);

                                    // If we reach here, then data is valid and working.
                                    // Commit the queries!
                                    DB::commit();
                                    // success msg
                                    Session::flash('success', 'Campus created');
                                    // return back
                                    return redirect()->back();
                                }else{
                                    Session::flash('warning', 'Unable to add user profile');
                                    // receiving page action
                                    return redirect()->back();
                                }
                            }else{
                                Session::flash('warning', 'Unable to add academic Year');
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


    /**
     * @param $adminName
     * @param $adminEmail
     * @return User
     */
    public function crateAdmin($adminName, $adminEmail)
    {
        // now create user profile
        $adminProfile = new User();
        // input user details
        $adminProfile->name = $adminName;
        $adminProfile->email = $adminEmail;
        $adminProfile->password = bcrypt(123456);
        // save admin profile
        $adminProfileSaved = $adminProfile->save();
        // checking
        if($adminProfileSaved){
            return $adminProfile;
        }else{
            return null;
        }
    }

    /**
     * @param $institute_id
     * @param $adminProfile
     * @param $campus
     * @return UserInfo
     */
    public function createUserInfo($institute_id, $adminProfile, $campus)
    {
        $userInfoProfile = new UserInfo();
        // add user details
        $userInfoProfile->user_id = $adminProfile->id;
        $userInfoProfile->institute_id = $institute_id;
        $userInfoProfile->campus_id = $campus->id;
        // save user info
        $userInfoProfileSaved = $userInfoProfile->save();
        // checking
        if($userInfoProfileSaved){
            // adminRoleProfile
            $adminRoleProfile = Role::where('name', 'admin')->first();
            // assigning student role to this user
            $adminProfile->attachRole($adminRoleProfile);
            return $userInfoProfile;
        }else{
            return null;
        }
    }

    public function createAcademicYear($institute_id, $campus)
    {
        // create academic year
        $academicYearProfile = new AcademicsYear();
        // input academic year details
        $academicYearProfile->year_name = date('Y');
        $academicYearProfile->start_date = date('Y-m-d', strtotime('01-01-' . date('Y')));
        $academicYearProfile->end_date = date('Y-m-d', strtotime('31-12-' . date('Y')));
        $academicYearProfile->status = 1;
        $academicYearProfile->institute_id = $institute_id;
        $academicYearProfile->campus_id = $campus->id;
        // save academic year profile
        $academicYearProfileSaved = $academicYearProfile->save();
        // checking
        if($academicYearProfileSaved){
            return $academicYearProfile;
        }else{
            return null;
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
