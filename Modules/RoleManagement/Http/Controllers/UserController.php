<?php

namespace Modules\RoleManagement\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\RoleManagement\Entities\Role;
use Modules\RoleManagement\Entities\User;
use Illuminate\Support\Facades\Hash;
use Session;
use Validator;

class UserController extends Controller
{
    public function index()
    {
        $roles=Role::all();
        $users=User::paginate(10);
        return view('RoleManagement::users.index',compact('roles','users'));
    }

    public function create()
    {
        $roles=Role::all();
        return view('RoleManagement::users.create',compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'role_id' => 'required',
            'status' => 'required',
            'username' => 'required',
            'password' => 'required',
        ]);

        $user=New User();
        $user->name=$request->name;
        $user->email=$request->email;
        $user->role_id=$request->role_id;
        $user->username=$request->username;
        $user->password=Hash::make($request->password);
        $user->status=$request->status;
        $userSave=$user->save();

        if($userSave)
        {
            Session::flash('message', 'Success!Data has been saved successfully.');
        }
        else
        {
            Session::flash('message', 'Failed!Data has not been saved successfully.');

        }

        return redirect()->back();
    }

    public function show($id)
    {
        return view('rolemanagement::show');
    }

    public function edit($id)
    {
        return view('rolemanagement::edit');
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
    public function delete($id)
    {
        $user=User::findOrFail($id);
        $delete=$user->delete();

        if($delete)
        {
            Session::flash('message', 'Success!Delete data has been saved successfully.');
        }
        else
        {

            Session::flash('message', 'Failed!Data has not been Delete successfully.');

        }
        return redirect()->back();
    }
}
