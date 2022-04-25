<?php 
  	namespace App\Helpers;
  	use DB;
	use App\User;
	use App\UserInfo;
	use App\RoleUser;
	use Auth;
	trait UserAccessHelper {
   		public static function linkAccess($request, $config=array()){
   			$user  = Auth::user(); 
        	$user_id = $user->id;
        	$accessData = []; $internalAccessData=[];
        	$accessKey  = $request->route()->getAction('access');
        	$route_name = $request->route()->getName();
        	if(!empty($accessKey)){
        		$route_path = $accessKey; 
        	}else{
        		$route_path = $request->path();
        	}
        	if(!empty($config)){
        		if(isset($config['manualRoute'])){
        			$route_path = $config['manualRoute'];
        		}
        	}
        	$route_info = DB::table('cadet_menu_routes')
					->where(function($query)use($route_path, $route_name){
						if(gettype($route_path)=='array'){
							$query->where(function($qq)use($route_path){
								$qq->whereIn('route_link', $route_path);
							})->orWhere(function($qq)use($route_path){
								$route_path_slash = [];
								foreach($route_path as $v){
									$route_path_slash[] = '/'.$v; 
								}
								$qq->whereIn('route_link', $route_path_slash);
							});
						}else{
							$query->where('route_link', $route_path)->orWhere('route_link','/'.$route_path);
						}
						if(!empty($route_name)){
							$query->orWhere('route_link', $route_name);
						}
						
					})->first();
        	if(!empty($route_info)){
        		$userAccessData = DB::table('cadet_user_permissions')
		            ->select('cadet_menu_routes.route_link as access_name','label','uid','parent_uid')
		            ->join('cadet_menu_routes', 'cadet_user_permissions.menu_route_id', 'cadet_menu_routes.id')
		            ->where('link_type', 'internal')
		            ->where('user_id', $user_id)->get();
		        if(count($userAccessData)>0){
		        	$internalAccessData = $userAccessData;
		        }else{
		        	$role_info = RoleUser::where('user_id',$user_id)->first();
		            if(!empty($role_info)){
		                $role_id = $role_info->role_id;
		                $roleAccessData = DB::table('cadet_role_permissions')
		                    ->select('cadet_menu_routes.route_link as access_name','label','uid','parent_uid')
		                    ->join('cadet_menu_routes', 'cadet_role_permissions.menu_route_id', 'cadet_menu_routes.id')
		                    ->where('link_type', 'internal')
		                    ->where('role_id', $role_id)->get();
		                if(count($roleAccessData)>0){
		                	$internalAccessData = $roleAccessData;
		                }
		            }
		        }

		        if(count($internalAccessData)>0){
		        	$internalAccessDataGroupBy = collect($internalAccessData)->groupBy('parent_uid')->all();
		        	
		        	$accessData = self::getInternalAccessData($internalAccessDataGroupBy, $route_info->uid);
		        }
        	}
        	return $accessData;
   		}

   		public static function getInternalAccessData($internalAccessDataGroupBy,$parent_uid, $accessData = []){
   			if(array_key_exists($parent_uid, $internalAccessDataGroupBy)){
   				foreach($internalAccessDataGroupBy[$parent_uid] as $internal_access) {
	                $accessData[] = $internal_access->uid;
	                $accessData[] = $internal_access->access_name;
	                $accessData = self::getInternalAccessData($internalAccessDataGroupBy,$internal_access->uid,$accessData);
	            }
   			}
   			return $accessData;
   		}

   		public function formateAccessArray($array){
   			$newArray = [];
   			foreach($array as $v){
   				if($v){
   					$newArray[$v] = true;
   				}
   			}
   			return $newArray; 
   		}

   		public static function vueLinkAccess($request){
   			$user  = Auth::user(); 
        	$user_id = $user->id;
        	$accessData = []; $internalAccessData=[];
        	$accessKey  = $request->route()->getAction('access');
        	$route_name = $request->route()->getName();
        	if(!empty($accessKey)){
        		$route_path = $accessKey; 
        	}else{
        		$route_path = $request->path();
        	}
        	$route_info = DB::table('cadet_menu_routes')
					->where(function($query)use($route_path, $route_name){
						if(gettype($route_path)=='array'){
							$query->where(function($qq)use($route_path){
								$qq->whereIn('route_link', $route_path);
							})->orWhere(function($qq)use($route_path){
								$route_path_slash = [];
								foreach($route_path as $v){
									$route_path_slash[] = '/'.$v; 
								}
								$qq->whereIn('route_link', $route_path_slash);
							});
						}else{
							$query->where('route_link', $route_path)->orWhere('route_link','/'.$route_path);
						}
						if(!empty($route_name)){
							$query->orWhere('route_link', $route_name);
						}
						
					})->first();
        	if(!empty($route_info)){
        		$userAccessData = DB::table('cadet_user_permissions')
		            ->select('cadet_menu_routes.route_link as access_name','label','uid','parent_uid')
		            ->join('cadet_menu_routes', 'cadet_user_permissions.menu_route_id', 'cadet_menu_routes.id')
		            ->where('link_type', 'internal')
		            ->where('user_id', $user_id)->get();
		        if(count($userAccessData)>0){
		        	$internalAccessData = $userAccessData;
		        }else{
		        	$role_info = RoleUser::where('user_id',$user_id)->first();
		            if(!empty($role_info)){
		                $role_id = $role_info->role_id;
		                $roleAccessData = DB::table('cadet_role_permissions')
		                    ->select('cadet_menu_routes.route_link as access_name','label','uid','parent_uid')
		                    ->join('cadet_menu_routes', 'cadet_role_permissions.menu_route_id', 'cadet_menu_routes.id')
		                    ->where('link_type', 'internal')
		                    ->where('role_id', $role_id)->get();
		                if(count($roleAccessData)>0){
		                	$internalAccessData = $roleAccessData;
		                }
		            }
		        }

		        if(count($internalAccessData)>0){
		        	$internalAccessDataGroupBy = collect($internalAccessData)->groupBy('parent_uid')->all();
		        	
		        	$accessData = self::getVueInternalAccessData($internalAccessDataGroupBy, $route_info->uid);
		        }
        	}
        	return $accessData;
   		}


   		public static function getVueInternalAccessData($internalAccessDataGroupBy,$parent_uid, $accessData = []){
   			if(array_key_exists($parent_uid, $internalAccessDataGroupBy)){
   				foreach($internalAccessDataGroupBy[$parent_uid] as $internal_access) {
	                $accessData[$internal_access->uid] = true;
	                $accessData[$internal_access->access_name] = true;
	                $accessData = self::getVueInternalAccessData($internalAccessDataGroupBy,$internal_access->uid,$accessData);
	            }
   			}
   			return $accessData;
   		}


   		
	}