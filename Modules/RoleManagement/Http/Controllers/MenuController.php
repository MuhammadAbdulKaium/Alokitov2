<?php

namespace Modules\RoleManagement\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\RoleManagement\Entities\Menu;
use Session;
use Validator;

class MenuController extends Controller
{

    public function index()
    {
        $parents=Menu::where('parent_id','=','0' )->where('grand_parent_id','=','0')->where('menu_type','=','3')->get();
        $childs=Menu::where('parent_id','>','0')->where('grand_parent_id','=','0' )->where('menu_type','=','3')->get();
        $grands=Menu::where('parent_id','>','0')->where('grand_parent_id','>','0' )->where('menu_type','=','3')->get();
        return view('RoleManagement::menus.index',compact('parents','childs','grands'));
    }

    public function create()
    {
        $parent=Menu::where('parent_id','=','0' )->where('grand_parent_id','=','0')->where('menu_type','=','3')->get();
        $child=Menu::where('parent_id','>','0')->where('grand_parent_id','=','0' )->where('menu_type','=','3')->get();
        return view('RoleManagement::menus.create',compact('parent','child'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'menu_name' => 'required|unique:setting_menus|max:255',
            'icon' => 'required',
            'route' => 'required',
            'status' => 'required',
            'menu_type' => 'required',
        ]);

          if(!$request->parent_id){
              $parent_id=0;
          }
          else{
              $parent_id=$request->parent_id;
          }

         if(!$request->grand_parent_id)
             {
                $grand_parent_id=0;
             }
         else
             {
                $grand_parent_id=$request->grand_parent_id;
            }

            $menu= New Menu();
            $menu->menu_name=$request->menu_name;
            $menu->icon=$request->icon;
            $menu->route=$request->route;
            $menu->status=$request->status;
            $menu->comment=$request->comment;
            $menu->menu_type=$request->menu_type;
            $menu->parent_id=$parent_id ;
            $menu->grand_parent_id=$grand_parent_id;
            $menu_save=$menu->save();
        if($menu_save)
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
//        $menu_type = array(
//            '1' => "Module",
//            '2' => "Sub Module",
//            '3' => "Page Menu",
//            '4' => "Link Menu",
//        );

        $parents=Menu::where('parent_id','=','0' )->where('grand_parent_id','=','0')->where('menu_type','=','3')->get();
        $childs=Menu::where('parent_id','>','0')->where('grand_parent_id','=','0' )->where('menu_type','=','3')->get();
        $grands=Menu::where('parent_id','>','0')->where('grand_parent_id','>','0' )->where('menu_type','=','3')->get();
        $menus=Menu::findOrFail($id);
        return view('RoleManagement::menus.edit',compact('menus','parents','childs','grands'));

    }

    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }

    public function updateMenu(Request $request)
    {

        $menu=Menu::findOrFail($request->id);

        $menu->menu_name = $request->menu_name;
        $menu->icon = $request->icon;
        $menu->route = $request->route;
        $menu->parent_id = $request->parent_id;
        $menu->grand_parent_id = $request->grand_parent_id;
        $menu->comment= $request->comment;
        $menu->status = $request->status;
        $menu->update();

    }
}
