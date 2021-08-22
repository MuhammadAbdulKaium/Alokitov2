<?php

namespace Modules\RoleManagement\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\RoleManagement\Entities\Menu;
use Modules\RoleManagement\Entities\Role;
use Modules\RoleManagement\Entities\User;

class PrivillageController extends Controller
{

    public function index()
    {
        $roles=Role::all();
        $users=User::all();
        $parents=Menu::where('parent_id','=','0' )->where('grand_parent_id','=','0')->where('menu_type','=','3')->get();
        $childs=Menu::where('parent_id','>','0')->where('grand_parent_id','=','0' )->where('menu_type','=','3')->get();
        $grands=Menu::where('parent_id','>','0')->where('grand_parent_id','>','0' )->where('menu_type','=','3')->get();
        return view('RoleManagement::privillage.index',compact('parents','childs','grands','roles','users'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('rolemanagement::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('rolemanagement::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('rolemanagement::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
