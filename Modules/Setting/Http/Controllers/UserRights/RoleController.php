<?php

namespace Modules\Setting\Http\Controllers\UserRights;

use Session;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Models\Role;

class RoleController extends Controller
{

    private  $role;
    public function __construct(Role $role)
    {
        $this->role = $role;
    }

    // create
    public function create()
    {
        $roleProfile = null;
        return view('setting::manage-user-rights.modals.role', compact('roleProfile'));
    }

    // store and update
    public function store(Request $request)
    {
        // validator
        $validator = Validator::make($request->all(), [
            'role_id'     => 'required|max:30',
            'name'     => 'required|max:30',
            // 'name'     => 'required|max:30|unique:roles',
            'display_name' => 'required|max:30',
            'description' => 'required|max:100',
        ]);

        // checking
        if ($validator->passes()) {
            // role id
            $roleId = $request->input('role_id', 0);
            // checking role id
            if($roleId>0){
                // find role profile
                $roleProfile = $this->role->find($roleId);
            }else{
                // create new role profile
                $roleProfile = new $this->role();
            }
            // input details
            $roleProfile->name = $request->input('name');
            $roleProfile->display_name = $request->input('display_name');
            $roleProfile->description = $request->input('description');
            // save role profile
            $roleProfileSubmitted = $roleProfile->save();
            // checking
            if($roleProfileSubmitted){
                // success msg
                Session::flash('success', 'Role Submitted Successfully');
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

    // edit
    public function edit($id)
    {
        $roleProfile = $this->role->find($id);
        return view('setting::manage-user-rights.modals.role', compact('roleProfile'));
    }

    // delete
    public function destroy($roleId)
    {
        // find role profile
        $roleProfile = $this->role->findOrFail($roleId);
        // delete profile
        $roleProfileDeleted = $roleProfile->delete();
        // checking roleProfileDeleted
        if($roleProfileDeleted){
            // success msg
            Session::flash('success', 'Role Deleted Successfully');
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
