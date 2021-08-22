<?php

namespace Modules\RoleManagement\Http\Controllers;

use App\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\RoleManagement\Entities\Role;
use Session;
use Validator;

class RoleController extends Controller
{
    public function index()
    {
        return view('RoleManagement::index');
    }

    public function create()
    {
        return view('RoleManagement::roles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:roles',
            'display_name' => 'required',
            'description' => 'required',
            'status' => 'required',
        ]);

        $role = New Role();
        $role->name=$request->name;
        $role->display_name=$request->display_name;
        $role->description=$request->description;
        $role->status=$request->status;
        $roleSave=$role->save();
        if($roleSave)
            {
                Session::flash('message', 'Success!Data has been saved successfully.');
            }
        else
            {

                Session::flash('message', 'Failed!Data has not been saved successfully.');

            }
        return redirect()->back();

    }

    public function delete($id)
    {
        $role=Role::findOrFail($id);
        $delete=$role->delete();

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
