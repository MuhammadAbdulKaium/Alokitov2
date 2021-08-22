<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use URL;
use Modules\Setting\Entities\LoginScreen;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void|mixed
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }


    public  function  showLoginForm( Request $request) {

        $getCurrentDomain=$request->root();
        $loginScreenProfile=LoginScreen::where('domain_name',$getCurrentDomain)->first();

        return view('auth.login', compact('loginScreenProfile'));

    }



    // user login
    public function login(Request $request)
    {
        // validation
        $this->validate($request, [
            'login'    => 'required',
            'password' => 'required',
        ]);

        // select login type
        $login_type = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL )?'email':'username';
        // merge request
        $request->merge([$login_type => $request->input('login')]);

        // checking user credentials
        if (Auth::attempt($request->only($login_type, 'password'))) {
            // return redirect()->intended($this->redirectPath());
            return redirect()->to($request->root());
        }

        // redirecting
        return redirect()->back()
            ->withInput()
            ->withErrors([
                'login' => 'These credentials do not match our records.',
            ]);
    }

}
