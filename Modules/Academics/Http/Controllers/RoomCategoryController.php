<?php

namespace Modules\Academics\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Academics\Entities\RoomCategory;
use Illuminate\Support\Facades\DB;
use Redirect;
use Session;
use Validator;

class RoomCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $roomCategoryList = RoomCategory::all();
        return view('academics::timetable.roomcategory',compact('roomCategoryList'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('academics::create');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function storeRoomCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'roomcategoryname'      => 'required'
        ]);

        if ($validator->passes()) {

            // Start transaction!
            DB::beginTransaction();
            try{
                $roomCategory = null;
                $message = "";
                if(!empty($request->input('roomcategory-id'))){
                    $id = $request->input('roomcategory-id');
                    $roomCategory = RoomCategory::findorfail($id);
                    if($roomCategory)
                        $message = "Room Category is now updated!";
                }else{
                    $roomCategory = new RoomCategory();
                    $message = "Room Category is now created!";

                }
                $roomCategory->categoryname    = $request->input('roomcategoryname');
                $roomCategory = $roomCategory->save();

                DB::commit();
                // checking and redirecting
                if ($roomCategory) {
                    Session::flash('success', $message);
                    return redirect('academics/roomcategory/');
                } else {
                    Session::flash('warning', 'Unable to create room category');
                    // receiving page action
                    return redirect()->back()->withErrors($validator)->withInput();
                }
            }catch (\Exception $e){
                DB::rollback();
                return redirect()->back()->withErrors($e->getErrors())->withInput();
            }

        }
        else{
            Session::flash('warning', 'Invalid Information');
            // receiving page action
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('academics::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('academics::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function deleteRoomCategory(Request $request, $id)
    {
        $roomCategory = RoomCategory::findorfail($id);
        if($roomCategory){
            $roomCategory->delete();
            Session::flash('success', 'Room category deleted successfully');
            return redirect()->back();
        }
        else{
            Session::flash('warning', 'Invalid Information');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}
