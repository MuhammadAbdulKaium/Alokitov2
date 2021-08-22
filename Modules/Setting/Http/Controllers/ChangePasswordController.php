<?php

namespace Modules\Setting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\User;
use Session;
use App\Http\Controllers\SmsSender;
use App\Http\Controllers\Helpers\AcademicHelper;
use DB;


class ChangePasswordController extends Controller
{




    private $user;
    private $smsSender;
    private $academicHelper;

    public function __construct(User $user,SmsSender $smsSender, AcademicHelper $academicHelper)
    {
        $this->user = $user;
        $this->smsSender = $smsSender;
        $this->academicHelper = $academicHelper;
    }



    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('setting::change_password.index');
    }



    // find employee with search terms
    public function findUser(Request $request){
        // search term
        $searchTerm = $request->input('term');
        $institute= $this->academicHelper->getInstitute();
        $campus= $this->academicHelper->getCampus();
        // find user list
        $userList = DB::table('users as u');
        // checking user type
        if($request->user_type==1) {
            // find student user list
            $userList = $userList->join('student_informations as s', function($join) use ($campus, $institute) {
                $join->on('s.user_id', '=', 'u.id')->where('s.institute', '=', $institute) ->where('s.campus', '=', $campus);
            });
        } elseif($request->user_type==2) {
            // find employee user list
            $userList =$userList->join('employee_informations as e', function($join) use ($campus, $institute) {
                $join->on('e.user_id', '=', 'u.id')->where('e.institute_id', '=', $institute)->where('e.campus_id', '=', $campus);
            });
        }
        // find user list
        $userList = $userList->select("u.id as id", "u.name as name","u.username as username","u.email as email")
            ->where('u.username', 'like', "%" . $searchTerm . "%")
            ->orwhere('u.name', 'like', "%" . $searchTerm . "%")
            ->orwhere('u.email', 'like', "%" . $searchTerm . "%")
            ->get();

        // userList  array list
        $responseArray = array();
        // checking user list
        if($userList->count()>0){
            // re-arrange user list
            foreach ($userList as $user){
                $responseArray[] = ['id'=>$user->id, 'name'=>$user->username.'( '.$user->email.' )'];
            }
        }
        //  return
        return json_encode($responseArray);
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
        $user_id=$request->input('user_id');
        $password=$request->input('password');
        $confirm_password=$request->input('confirm_password');
        if(empty($user_id)) {
            Session::flash('warning', 'User not required');
        }elseif($password==$confirm_password) {

            $userProfile=$this->user->find($user_id);
            $userProfile->password=bcrypt($password);
            $userProfile->save();
            Session::flash('success', 'Password Successfully Changed');
            $this->smsSender->passwordChangeSmsJob($user_id,$password);
            return redirect()->back();

        } else {
            Session::flash('warning', 'Your password and confirmation password do not match.');
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
    public function edit()
    {
        return view('setting::edit');
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
    public function destroy()
    {
    }
}
