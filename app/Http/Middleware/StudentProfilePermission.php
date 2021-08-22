<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;
use Redirect;
use Session;
use App\Http\Controllers\Helpers\AcademicHelper;

class StudentProfilePermission
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
        // user information
        $userInfo = $user->userInfo()->first();

        // checking
        if($user->hasRole(['super-admin'])) {
            // return
            return $next($request);

        }elseif($user->hasRole('admin') || $user->hasRole('teacher')){
            // checking user institute and campus information
            if($user->findStudent($id, $userInfo->campus_id, $userInfo->institute_id)){
                // return
                return $next($request);
            }else{
                // set warning msg
                //Session::put('warning','Attempt to View Unauthorized Content');
                // save session msg
                //Session::save();
                // return
                return redirect()->to('/'); // Nope! Get out from here.
            }
        } elseif ($user->hasRole('parent')){
            // std information
            $parentInfo = $user->parent();
            // std list
            $stdList = $parentInfo->students();
            // parent assigned std list
            $myStudents = array();
            // looping for creating parent std list
            foreach ($stdList as $student){
                $myStudents[] = $student->std_id;
            }
            // checking
            if (in_array($id, $myStudents)) {
                return $next($request);
            }else{
                // set warning msg
                Session::put('warning','Attempt to View Unauthorized Content');
                // save session msg
                Session::save();
                // return
                return redirect()->to('/'); // Nope! Get out from here.
            }

        } elseif ($user->hasRole('student')){
            // std information
            $stdInfo = $user->student();
            // checking
            if($id == $stdInfo->id){
                return $next($request);
            }else{
                // set warning msg
                Session::put('warning','Attempt to View Unauthorized Content');
                // save session msg
                Session::save();
                // return
                return redirect()->to('/'); // Nope! Get out from here.
            }
        }else{
            // set warning msg
            Session::put('warning','Attempt to View Unauthorized Content');
            // save session msg
            Session::save();
            // return
            return redirect()->to('/'); // Nope! Get out from here.
        }
    }
}
