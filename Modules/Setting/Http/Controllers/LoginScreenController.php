<?php

namespace Modules\Setting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Validator;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Setting\Entities\LoginScreen;
use Modules\Setting\Entities\Institute;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Helpers\AcademicHelper;

class LoginScreenController extends Controller
{


    private  $loginScreen;
    private  $institute;
    private  $academicHelper;


    public function __construct(LoginScreen $loginScreen, AcademicHelper $academicHelper,Institute $institute)
    {
        $this->loginScreen             = $loginScreen;
        $this->institute                   = $institute;
        $this->academicHelper              = $academicHelper;
    }



    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();
        //get all institutes
        $institutes=$this->institute->all();
        //get all loginScreens
          $loginScreens=$this->loginScreen->get();
        return view('setting::login_screen.index',compact('institutes','loginScreens'));

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

        $instituteId=$request->input('institute_name');
        $loignSecreenId=$request->input('loginScreenProfile_id');
        if(empty($loignSecreenId)) {
            $loginScreenProfile = $this->loginScreen->where('institution_id', $instituteId)->first();

            if (empty($loginScreenProfile)) {
                $loginImage = $request->file('login_image');
                $fileExtension = $loginImage->getClientOriginalExtension();
                $filename = str_random(30) . "." . $fileExtension;
                $destinationPath = 'assets/login_screen/';
                $uploaded = $loginImage->move($destinationPath, $filename);

                if ($uploaded) {
                    $loginScreen = new $this->loginScreen;
                    // input teacher deatails
                    $loginScreen->institution_id = $request->input('institute_name');
                    $loginScreen->domain_name = $request->input('domain_name');
                    // image file name
                    $loginScreen->login_image = $filename;

                    $loginScreen = $loginScreen->save();

                    Session::flash('message', 'Login Screen Successfully Created');
                }


            } else {
                Session::flash('error', 'Login Screen Already Exist');
            }

        } else {

            $loginImage = $request->file('login_image');
            if(!empty($loginImage)) {
                $loginImage = $request->file('login_image');
                $fileExtension = $loginImage->getClientOriginalExtension();
                $filename = str_random(30) . "." . $fileExtension;
                $destinationPath = 'assets/login_screen/';
                $uploaded = $loginImage->move($destinationPath, $filename);
                $loginScreen = $this->loginScreen->find($loignSecreenId);
                $loginScreen->login_image = $filename;
                $loginScreen->update();
            }
            $loginScreen = $this->loginScreen->find($loignSecreenId);
            $loginScreen->institution_id = $request->input('institute_name');
            $loginScreen->domain_name = $request->input('domain_name');
            $loginScreen = $loginScreen->update();
            Session::flash('message', 'Login Screen Successfully Created');

        }
        return redirect()->back();

    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('setting::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($loginScreenId)
    {
        $institutes=$this->institute->all();
        //get all institute Property
        $loginScreens=$this->loginScreen->all();
        //single property
        $loginScreenProfile=$this->loginScreen->find($loginScreenId);
        return view('setting::login_screen.index',compact('institutes','loginScreens','loginScreenProfile'));

    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function delete($loginScreenId)
    {
        $loginScreenProfile=$this->loginScreen->find($loginScreenId);
        $loginScreenProfile->delete();
    }
}
