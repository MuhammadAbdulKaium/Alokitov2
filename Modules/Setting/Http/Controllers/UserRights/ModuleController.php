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

class ModuleController extends Controller
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
        $moduleProfile = null;
        $moduleList = $this->module->where(['parent_id'=>0, 'status'=>1])->orderBy('name', 'ASC')->get();
        return view('setting::manage-user-rights.modals.module', compact('moduleProfile', 'moduleList'));
    }

    // store and update
    public function store(Request $request)
    {
        // validator
        $validator = Validator::make($request->all(), [
            'module_id'     => 'required',
            'name'     => 'required|max:30',
            'icon'     => 'required|max:30',
            'route'     => 'required|max:150',
            'parent_id'     => 'required',
        ]);

        // checking
        if ($validator->passes()) {
            // role id
            $moduleId = $request->input('module_id', 0);
            // checking role id
            if($moduleId>0){
                // find module profile
                $moduleProfile = $this->module->find($moduleId);
                // update role profile
                $moduleProfileSubmitted = $moduleProfile->update($request->except(['module_id']));
            }else{
                // create new module profile
                $moduleProfile = new $this->module();
                // save module profile
                $moduleProfileSubmitted = $moduleProfile->create($request->except(['module_id']));
            }

            // checking
            if($moduleProfileSubmitted){
                // success msg
                Session::flash('success', 'Module Submitted Successfully');
                // redirecting
                return redirect()->back();
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

    // status
    public function status($id)
    {
        $moduleProfile = $this->module->find($id);
        // find role profile
        $moduleProfile->status = ($moduleProfile->status==0?'1':'0');
        //save profile
        $moduleProfileSubmitted = $moduleProfile->save();
        // checking $moduleProfileDeleted
        if($moduleProfileSubmitted){
            // success msg
            Session::flash('success', 'Module Status Changed');
            // redirecting
            return redirect()->back();
        }else{
            // warning msg
            Session::flash('warning', 'Unable to perform the action');
            // redirecting
            return redirect()->back();
        }
    }

    // edit
    public function edit($id)
    {
        $moduleProfile = $this->module->find($id);
        $moduleList = $this->module->where(['parent_id'=>0, 'status'=>1])->orderBy('name', 'ASC')->get();
        return view('setting::manage-user-rights.modals.module', compact('moduleProfile','moduleList'));
    }

    // find sub module list
    public function findSubModule(Request $request)
    {
        // module id
        $moduleId = $request->input('module_id');
        // sub module list
        $subModules = $this->module->where(['parent_id'=>$moduleId, 'status'=>1])->orderBy('name', 'ASC')->get();
        // response data
        $responseData = array();
        // looping
        foreach ($subModules as $module){
            $responseData[] = ['id'=>$module->id, 'name'=>$module->name];
        }
        // return response
        return $responseData;
    }

    // delete
    public function destroy($moduleId)
    {
        // find role profile
        $moduleProfile = $this->module->findOrFail($moduleId);
        // delete profile
        $moduleProfileDeleted = $moduleProfile->delete();
        // checking $moduleProfileDeleted
        if($moduleProfileDeleted){
            // success msg
            Session::flash('success', 'Module Deleted Successfully');
            // redirecting
            return redirect()->back();
        }else{
            // warning msg
            Session::flash('warning', 'Unable to perform the action');
            // redirecting
            return redirect()->back();
        }
    }
}
