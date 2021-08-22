<?php

namespace Modules\Setting\Http\Controllers\UserRights;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Session;
use Validator;
use App\Models\Role;
use App\Models\Permission;
use Modules\Setting\Entities\Menu;
use Modules\Setting\Entities\Module;
use Modules\Setting\Entities\InstituteModule;
use App\Models\UserPermission;

class PermissionController extends Controller
{

    private  $role;
    private  $permission;
    private  $menu;
    private  $module;
    private  $userPermission;
    private  $instituteModule;
    // constructor
    public function __construct(Role $role, Permission $permission, Menu $menu, Module $module, InstituteModule $instituteModule, UserPermission $userPermission)
    {
        $this->role = $role;
        $this->permission = $permission;
        $this->menu = $menu;
        $this->module = $module;
        $this->userPermission = $userPermission;
        $this->instituteModule = $instituteModule;
    }

    // create
    public function create()
    {
        $permissionProfile = null;
        $moduleList = $this->module->where(['parent_id'=>0,'status'=>1])->orderBy('name', 'ASC')->get();
        return view('setting::manage-user-rights.modals.permission', compact('permissionProfile', 'moduleList'));
    }

    // store and update
    public function store(Request $request)
    {
        // validator
        $validator = Validator::make($request->all(), [
            'permission_id'     => 'required|max:30',
            'name'     => 'required|max:100',
//             'name'     => 'required|max:100|unique:permissions',
            'module_id' => 'required|max:30',
            'sub_module_id' => 'required|max:30',
            'display_name' => 'required|max:100',
            'description' => 'required|max:100',
            'is_user' => 'required',
        ]);

        // checking
        if ($validator->passes()) {
            // permission id
            $permissionId = $request->input('permission_id', 0);
            // checking role id
            if($permissionId>0){
                // find permission Profile
                $permissionProfile = $this->permission->find($permissionId);
            }else{
                // create new permission profile
                $permissionProfile = new $this->permission();
            }
            // input details
            $permissionProfile->name = $request->input('name');
            $permissionProfile->module_id = $request->input('module_id');
            $permissionProfile->sub_module_id = $request->input('sub_module_id');
            $permissionProfile->display_name = $request->input('display_name');
            $permissionProfile->description = $request->input('description');
            $permissionProfile->is_user = $request->input('is_user');
            // save role profile
            $permissionProfileSubmitted = $permissionProfile->save();
            // checking
            if($permissionProfileSubmitted){
                // permission list
                $allPermission = $this->permission->where([
                    'module_id'=>$request->input('module_id'),
                    'sub_module_id'=>$request->input('sub_module_id')
                ])->paginate(10);
                // return
                return view('setting::manage-user-rights.modals.permission-list', compact('allPermission'));
            }else{
                // warning msg
                return ['status'=>'failed', 'msg'=>'Unable to perform the action'];
            }
        }else{
            return ['status'=>'failed', 'msg'=>'invalid information'];
        }
    }

    // edit
    public function edit($id)
    {

        // permission profile
        $permissionProfile = $this->permission->find($id);
        // module list
        $moduleList = $this->module->where(['parent_id'=>0,'status'=>1])->orderBy('name', 'ASC')->get();
        // return view with all variables
        return view('setting::manage-user-rights.modals.permission', compact('permissionProfile', 'moduleList'));
    }


    public function findPermission(Request $request)
    {
        //get search term
        $searchTerm = $request->input('term');
        $moduleId = $request->input('module_id');
        $subModuleId = $request->input('sub_module_id');
        $requestType = $request->input('request_type');
        // checking
        if($requestType=='auto_complete'){
            // permission list
            $permissionList = $this->permission->where(['module_id'=>$moduleId, 'sub_module_id'=>$subModuleId])
                ->where('name', 'like', "%" . $searchTerm . "%")
                ->orwhere('display_name', 'like', "%" . $searchTerm . "%")
                ->orwhere('description', 'like', "%" . $searchTerm . "%")
                ->get();
            // response data array
            $responseData = array();
            // looping
            foreach ($permissionList as $permission){
                $responseData[] = ['id'=>$permission->id,'name'=>$permission->name, 'display_name'=>$permission->display_name];
            }
            // return variable
            return json_encode($responseData);
        }else{
            // permission list
            $allPermission = $this->permission->where(['module_id'=>$moduleId, 'sub_module_id'=>$subModuleId])
            ->paginate(10);
            // return
            return view('setting::manage-user-rights.modals.permission-list', compact('allPermission'));
        }
    }

    // status
    public function status($permissionId)
    {
        // find permission profile
        $permissionProfile = $this->permission->find($permissionId);
        // find role profile
        $permissionProfile->status = ($permissionProfile->status==0?'1':'0');
        //save profile
        $permissionProfileSubmitted = $permissionProfile->save();
        // checking $permissionProfileSubmitted
        if($permissionProfileSubmitted){
            return ['response_type'=>'status', 'status'=>'success'];
        }else{
            return ['response_type'=>'status', 'status'=>'failed'];
        }
    }

    // delete
    public function destroy($permissionId)
    {
        // find permission profile
        $permissionProfile = $this->permission->findOrFail($permissionId);
        // delete profile
        $permissionProfileDeleted = $permissionProfile->delete();
        // checking permission Profile deleted
        if($permissionProfileDeleted){
            return ['response_type'=>'delete', 'status'=>'success'];
        }else{
            return ['response_type'=>'delete', 'status'=>'success'];
        }
    }

    //////////////////////////////////////////  User Permission //////////////////////////////////////////////////

    // get user permission
    public function getUserPermission(Request $request)
    {
        // user permission array list
        $userPermissionArrayList = array();
        // request details
        $userId = $request->input('user_id');
        // find all permission list
        $allPermission = $this->permission->where(['is_user'=>1, 'status'=>1])->get();
        // find user permission list
        if($userPermissionList = $this->userPermission->where(['user_id'=>$userId])->get()){
            // user list looping
            foreach ($userPermissionList as $userPermission){
                $dateTextColor = Carbon::parse(Carbon::now())->lt($userPermission->valid_up_to)?'text-green':'text-red';
                // store into permission array list
                $userPermissionArrayList[$userPermission->permission_id] = ['date_time'=>$userPermission->valid_up_to, 'date_text_color'=>$dateTextColor];
            }
        }
        // return view with variable
        return view('setting::manage-user-rights.modals.user-permission-list', compact('allPermission', 'userPermissionArrayList'));
    }

    // get user permission
    public function storeUserPermission(Request $request)
    {
        // request details
        $userId = $request->input('user_id');
        $validUpTo = $request->input('valid_up_to');
        $permissionId = $request->input('permission_id');
        // institute details

        // checking
        if($userPermission = $this->userPermission->where(['user_id'=>$userId, 'permission_id'=>$permissionId])->withTrashed()->first()){
            // restore $userPermission
            if ($userPermission->trashed()) $userPermission->restore();
        }else{
            $userPermission = new $this->userPermission();
        }
        // store permission details
        $userPermission->user_id = $userId;
        $userPermission->valid_up_to = $validUpTo;
        $userPermission->permission_id = $permissionId;
        // save and checking
        if($userPermission->save()){
            // date checking
            $dateColor = Carbon::parse(Carbon::now())->lt($validUpTo)?'text-green':'text-red';
            // return success msg
            return ['status'=>true, 'date_text_color'=>$dateColor, 'msg'=>'User Permission Submitted'];
        }else{
            // return success msg
            return ['status'=>false, 'msg'=>'User Permission Submitted'];
        }
    }

    //////////////////////////////////////////  User Permission //////////////////////////////////////////////////
}
