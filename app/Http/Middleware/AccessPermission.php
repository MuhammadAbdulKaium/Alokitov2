<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;
use Redirect;
use Session;

class AccessPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // input request id
        $id = $request->route('id'); // For example, the current URL is: /posts/1/edit
        // current user id
        $user = Auth::user();
        // checking
        if($user->hasRole(['super-admin'])) {
            // return
            return $next($request);

        }elseif($user->hasRole('admin')){
            // return
            return $next($request);
        }elseif($user->hasRole('teacher')){
            // return
            return $next($request);

        } elseif ($user->hasRole('parent')){
            Session::put('warning','Attempt to View Unauthorized Content');
            // save session msg
            Session::save();
            // return
            return redirect()->to('/'); // Nope! Get out from here.

        } elseif ($user->hasRole('student')){
            Session::put('warning','Attempt to View Unauthorized Content');
            // save session msg
            Session::save();
            // return
            return redirect()->to('/'); // Nope! Get out from here.
        }
        elseif ($user->hasRole('guest')){
            // return
            return $next($request);
        }
        elseif($user->hasRole('accountant')){
            // return
            return $next($request);

        }
        else{
            // set warning msg
            Session::put('warning','Attempt to View Unauthorized Content');
            // save session msg
            Session::save();
            // return
            return redirect()->to('/'); // Nope! Get out from here.
        }
    }
}
