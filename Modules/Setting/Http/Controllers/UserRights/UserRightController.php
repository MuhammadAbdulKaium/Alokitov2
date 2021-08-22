<?php

namespace Modules\Setting\Http\Controllers\UserRights;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Models\Role;
use App\Models\Permission;
use Modules\Setting\Entities\Menu;
use Modules\Setting\Entities\Module;
use Modules\Setting\Entities\Institute;
use Modules\Setting\Entities\InstituteModule;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Employee\Entities\EmployeeInformation;

class UserRightController extends Controller
{
    private  $role;
    private  $permission;
    private  $menu;
    private  $module;
    private  $institute;
    private  $academicHelper;
    private  $instituteModule;
    private  $employeeInformation;
    // constructor
    public function __construct(Role $role, Permission $permission, Menu $menu, Module $module, Institute $institute, InstituteModule $instituteModule, AcademicHelper $academicHelper, EmployeeInformation $employeeInformation)
    {
        $this->role = $role;
        $this->permission = $permission;
        $this->menu = $menu;
        $this->module = $module;
        $this->institute = $institute;
        $this->academicHelper = $academicHelper;
        $this->instituteModule = $instituteModule;
        $this->employeeInformation = $employeeInformation;
    }
    // index function
    public function index($tabId)
    {
        $allRole = $this->role->orderBy('name', 'ASC')->get();
        $allPermission = $this->permission->all();
        $allMenu = $this->menu->orderBy('name', 'ASC')->get();
        $allModule = $this->module->where(['parent_id'=>0, 'status'=>1])->orderBy('name', 'ASC')->get();
        $allInstitute = $this->institute->orderBy('institute_name', 'ASC')->get();

        switch ($tabId) {

            case 'role':
                return view('setting::manage-user-rights.role', compact('allRole'))->with('page', 'role');
                break;

            case 'permission':
                return view('setting::manage-user-rights.permission', compact('allModule'))->with('page', 'permission');
                break;

            case 'module':
                $allModule = $this->module->where(['parent_id'=>0])->orderBy('name', 'ASC')->get();
                return view('setting::manage-user-rights.module', compact('allModule'))->with('page', 'module');
                break;

            case 'menu':
                return view('setting::manage-user-rights.menu', compact('allModule'))->with('page', 'menu');
                break;

            case 'setting':
                return view('setting::manage-user-rights.setting', compact('allInstitute','allModule', 'allRole', 'allPermission'))->with('page', 'setting');
                break;

            case 'user-permission':
                // institute details
                $campus = $this->academicHelper->getCampus();
                $institute = $this->academicHelper->getInstitute();
                // find institute employee list
                $employeeList = $this->employeeInformation->where(['institute_id'=>$institute,'campus_id'=>$campus])->get();
                // return view with variable
                return view('setting::manage-user-rights.user-permission', compact('employeeList'))->with('page', 'user-permission');
                break;

            default:
                # code...
                break;
        }
    }


////////////////////////////////////////////// Role Permissions //////////////////////////////////////////////

    // assign institute module
    public function manageRolePermission(Request $request)
    {
        // roleId
        $roleId = $request->input('role_id');
        $moduleId = $request->input('module_id');
        $subModuleId = $request->input('sub_module_id');
        $requestType = $request->input('request_type', 'show');
        // role profile
        $roleProfile =  $this->role->find($roleId);
        // all permissions
        $allPermission = $this->permission->where(['module_id'=>$moduleId,'sub_module_id'=>$subModuleId])->orderBy('name', 'ASC')->paginate(10);
        // checking
        if($requestType=='assign'){
            $responseData = array();
            // permission id
            $permissionId = $request->input('permission_id');
            // permission profile
            $permissionProfile = $this->permission->find($permissionId);
            // checking permission role
            if($checkRole = $permissionProfile->checkRole($roleProfile->id)){
                // detach permission form role
                $roleProfile->rolePermissions()->detach($permissionId);
                // response status
                $responseData['status'] = 'detached';
            }else{
                // attach permission with role
                $roleProfile->attachPermission($permissionId);
                // response status
                $responseData['status'] = 'attached';
            }
            // return response data array
            return $responseData;
        }
        // return view with all variables
        return view('setting::manage-user-rights.modals.role-permission', compact('allPermission', 'roleProfile'));
    }

////////////////////////////////////////////// Module Menus //////////////////////////////////////////////
    // get institute module
    public function manageInstituteModule(Request $request)
    {
        // instituteId
        $instituteId = $request->input('institute_id');
        $requestType = $request->input('request_type', 'show');
        // institute profile
        $instituteProfile =  $this->institute->find($instituteId);
        // all modules
        $allModule = $this->module->where(['parent_id'=>0,'status'=>1])->orderBy('name', 'ASC')->get();
        // checking
        if($requestType=='assign'){
            // detach all modules form institute
            $instituteProfile->instituteModules()->detach();
            // looping
            foreach ($allModule as $module){
                // module id
                $moduleId = $module->id;
                // checking module id
                if($request->$moduleId){
                    $instituteProfile->instituteModules()->attach($moduleId);
                }
            }
        }
        // return view with all variables
        return view('setting::manage-user-rights.modals.institute-module', compact('allModule', 'instituteProfile'));
    }
}
