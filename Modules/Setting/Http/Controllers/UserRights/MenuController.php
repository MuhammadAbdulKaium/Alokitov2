<?php

namespace Modules\Setting\Http\Controllers\UserRights;

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

class MenuController extends Controller
{

    private  $role;
    private  $permission;
    private  $menu;
    private  $module;
    private  $instituteModule;
    // constructor
    public function __construct(Role $role, Permission $permission, Menu $menu, Module $module, InstituteModule $instituteModule)
    {
        $this->role = $role;
        $this->permission = $permission;
        $this->menu = $menu;
        $this->module = $module;
        $this->instituteModule = $instituteModule;
    }

    // create
    public function create()
    {
        $menuProfile = null;
        $allModule = $this->module->where(['parent_id'=>0,'status'=>1])->orderBy('name', 'ASC')->get();
        return view('setting::manage-user-rights.modals.menu', compact('menuProfile', 'allModule'));
    }

    //
    public function store(Request $request)
    {

        // validator
        $validator = Validator::make($request->all(), [
            'menu_id'     => 'required',
            'name'     => 'required|max:30',
            'module_id'     => 'required',
            'sub_module_id' => 'required',
            'permission_id' => 'required',
            'route' => 'required|max:100',
            'icon' => 'required|max:100',
        ]);

        // checking
        if ($validator->passes()) {
            // menu id
            $menuId = $request->input('menu_id', 0);
            $moduleId =$request->input('module_id');
            $subModuleId = $request->input('sub_module_id');
            // permission id
            $permissionId = $request->input('permission_id');
            // checking role id
            if($menuId>0){
                // find menu profile
                $menuProfile = $this->menu->find($menuId);
                // detach permission
                $menuProfile->menuPermissions()->detach();

            }else{
                // create new role profile
                $menuProfile = new $this->menu();
            }
            // input details
            $menuProfile->name = $request->input('name');
            $menuProfile->module_id = $moduleId;
            $menuProfile->sub_module_id = $subModuleId;
            $menuProfile->route = $request->input('route');
            $menuProfile->icon = $request->input('icon');
            // save menu profile
            $menuProfileSubmitted = $menuProfile->save();
            // checking
            if($menuProfileSubmitted){
                //permission action
                $menuProfile->menuPermissions()->attach($permissionId);
                // success msg
                $allMenu = $this->menu->where(['module_id'=>$moduleId, 'sub_module_id'=>$subModuleId])->paginate(10);
                // return
                return view('setting::manage-user-rights.modals.menu-list', compact('allMenu'));
            }else{
                // warning msg
                Session::flash('warning', 'Unable to perform the action');
                // redirecting
                return redirect()->back();
            }
        }else{
            // warning msg
            Session::flash('warning', 'Invalid Information');
            // redirecting
            return redirect()->back();
        }

    }

    //
    public function edit($menuId)
    {
        $menuProfile = $this->menu->find($menuId);
        $allModule = $this->module->where(['parent_id'=>0,'status'=>1])->orderBy('name', 'ASC')->get();
        return view('setting::manage-user-rights.modals.menu', compact('menuProfile', 'allModule'));
    }


    // status
    public function status($menuId)
    {
        // find menu profile
        $menuProfile = $this->menu->find($menuId);
        // find role profile
        $menuProfile->status = ($menuProfile->status==0?'1':'0');
        //save profile
        $menuProfileSubmitted = $menuProfile->save();
        // checking $menuProfileSubmitted
        if($menuProfileSubmitted){
            return ['response_type'=>'status', 'status'=>'success'];
        }else{
            return ['response_type'=>'status', 'status'=>'failed'];
        }
    }

    //find menu
    public function findMenu(Request $request)
    {
        // module id
        $moduleId = $request->input('module_id');
        // sub module id
        $subModuleId = $request->input('sub_module_id');
        // menu list
        $allMenu = $this->menu->where(['module_id'=>$moduleId, 'sub_module_id'=>$subModuleId])->paginate(10);
        // return
        return view('setting::manage-user-rights.modals.menu-list', compact('allMenu'));
    }

    // delete
    public function destroy($menuId)
    {
        // find role profile
        $menuProfile = $this->menu->find($menuId);
        // detach permission
        $menuProfile->menuPermissions()->detach();
        // delete profile
        $menuProfileDeleted = $menuProfile->delete();
        // checking $menuProfileDeleted
        if($menuProfileDeleted){
              return ['response_type'=>'delete', 'status'=>'success'];
        }else{
            return ['response_type'=>'delete', 'status'=>'failed'];
        }
    }
}
