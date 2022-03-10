<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use JWTAuth ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Academics\Entities\ReportCardSetting;
use Modules\Admission\Entities\ApplicantManageView;
use Modules\Admission\Entities\ApplicantUser;
use Modules\Admission\Entities\ApplicantUser as User;

class AuthController extends Controller
{
    public function __construct(){
       $this->guard();
    }


    public function login(Request $request){

        if($request->email && $request->campus && $request->institute){
            $checkMail=ApplicantUser::where([
                'campus_id'=>$request->campus,
                'email'=>$request->email,
                'institute_id'=>$request->institute

            ])->first();
            if(!$checkMail){
                return response()->json(['error' => 'Invalid Campus'], 401);
            }
        }
    $validator = Validator::make($request->all(), [
    'email' => 'required',
    'password' => 'required',
    ]);

    if ($validator->fails()) {
    return response()->json($validator->errors(), 422);
    }

    if (! $token = auth('api')->attempt($validator->validated())) {
    return response()->json(['error' => 'Unauthorized'], 401);
    }
    return $this->createNewToken($token);
    }

    public function register(Request $request){
    $validator = Validator::make($request->all(), [
    'username' => 'required|string|between:2,100',
    'email' => 'required|string|email|max:100|unique:users',
    'password' => 'required|confirmed|',
    ]);

    if($validator->fails()){
    return response()->json(['error'=>$validator->errors()->toJson()], 400);
    }

   /* $user = User::create(array_merge(
    $validator->validated(),
    ['password' => bcrypt($request->password)]
    ));*/
        $user=new User();
        $user->username=$request->username;
        $user->email=$request->email;
        $user->password= bcrypt($request->password);
        $user->save();


    if (! $token = JWTAuth::attempt($request->only('email','password'))) {
    return response()->json(['error' => 'Unauthorized'], 401);
    }
    return $this->createNewToken($token);
    }

    protected function createNewToken($token){
    return response()->json([
    'access_token' => $token,
    'token_type' => 'bearer',
    'user' => auth('api')->user()
    ]);
    }


    public function downloadAdmit(Request $request){
        $user=\auth('api')->user();
        if($user){
            $uID=$user->id;
            $userInfo=ApplicantUser::where('id',$uID)->with('applicantManageView')->first();
            $applicantProfile = ApplicantManageView::where(['applicant_id'=>$uID])->first();
            $reportCardSetting = ReportCardSetting::where(['institute'=>$applicantProfile->institute_id,
                'campus'=>$applicantProfile->campus_id])->first();
            //institute profile
            $instituteInfo = $applicantProfile->institute();
            // share all variables with the view
            view()->share(compact('instituteInfo', 'applicantProfile', 'reportCardSetting'));
            // generate pdf
            $pdf = App::make('dompdf.wrapper');
            // load view
            $pdf->loadView('admission::application.reports.report-admit-card')->setPaper('a4', 'portrait');
            return $pdf->download('admit_card_no_'.$applicantProfile->application_no.'.pdf');

           // return ['status'=>true,'admit'=>]

        }
    }


    public function getUser(Request $request)
    {
        $user=\auth('api')->user();
        if($user){
            $uID=$user->id;
            $userInfo=ApplicantUser::where('id',$uID)->with('applicantManageView')->first();

        }
        return ['status'=>true ,'data'=>$user,'user'=>$userInfo];
    return response()->json(auth('api')->user());
    }

    public function refresh() {
    return $this->createNewToken(auth()->refresh());
    }
    protected function guard(){
        return Auth::guard('api');
    }
}
