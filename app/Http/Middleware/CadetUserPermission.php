<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\RoleUser;
use DB;
use Session;

use Closure;

class CadetUserPermission
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
        /*$user  = Auth::user(); 
        $user_id = $user->id;
        $accessData = [];
        $userAccessData = DB::table('cadet_user_permissions')
            ->select('cadet_menu_routes.route_link as access_name','label')
            ->join('cadet_menu_routes', 'cadet_user_permissions.menu_route_id', 'cadet_menu_routes.id')
            ->where('user_id', $user_id)->get();
        if(count($userAccessData)>0){
            $accessData = collect($userAccessData)->pluck('access_name')->all();
        }else{
            $role_info = RoleUser::where('user_id',Auth::user()->id)->first();
            if(!empty($role_info)){
                $role_id = $role_info->role_id;
                $roleAccessData = DB::table('cadet_role_permissions')
                    ->select('cadet_menu_routes.route_link as access_name','label')
                    ->join('cadet_menu_routes', 'cadet_role_permissions.menu_route_id', 'cadet_menu_routes.id')
                    ->where('role_id', $role_id)->get();
                $accessData =  (count($roleAccessData)>0)?collect($roleAccessData)->pluck('access_name')->all():[];
            }
        }
        $accessKey  = $request->route()->getAction('access');*/
        /*$hasAccess = false;
        if(!empty($accessKey)){
            if(gettype($accessKey)=='array'){
                foreach ($accessKey as $v) {
                    if(in_array($v, $accessData)){
                        $hasAccess = true;
                        break;
                    }
                }
            }else{   // allow permission for route 
               $hasAccess = true; 
            }
        }else{
            $route_path = $request->path();
            $route_name = $request->route()->getName();
            if(in_array($route_path, $accessData) || (!empty($route_name) && in_array($route_path, $accessData))){
                $hasAccess = true;
            }
        }
        if($hasAccess){
            return $next($request);
        }else{
            if($request->wantsJson()) {  // for ajax request 
                return redirect()->route("access-deny");
            }else{
                Session::put('warning','Attempt to View Unauthorized Content');
                // save session msg
                Session::save();
                // return
                return redirect()->to('/'); // Nope! Get out from here.
            }
        }*/
        
        return $next($request);
    }

    
}
