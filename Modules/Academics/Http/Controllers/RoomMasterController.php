<?php

namespace Modules\Academics\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Academics\Entities\RoomCategory;
use Modules\Academics\Entities\RoomMaster;
use Illuminate\Support\Facades\DB;

use Redirect;
use Session;
use Validator;

class RoomMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $roomCategoryList = RoomCategory::all();
        $roomList = RoomMaster::all();
        return view('academics::timetable.roommaster', compact("roomCategoryList"))->with(compact('roomList'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function createOrUpdateRoom(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'category_id'      => 'required'

         ]);
        if ($validator->passes()) {

            // Start transaction!
            DB::beginTransaction();
            $roommaster = null;
            $message = "";
            try{
                if(!empty($request->input("room_id"))){
                    $roommaster = RoomMaster::findorfail($request->input("room_id"));
                    $message = "Room updated successfully";
                }else{
                    $roommaster = new RoomMaster();
                    $message = "Room created successfully";
                }
                $roommaster->name = $request->input("name");
                $roommaster->category_id = $request->input("category_id");
                $roommaster->seat_capacity = $request->input("seat_capacity");
                $roommaster->location = $request->input("location");

                $roommaster = $roommaster->save();
                DB::commit();
                // checking and redirecting
                if ($roommaster) {
                    Session::flash('success',$message );
                    return redirect('academics/roommaster/');
                } else {
                    Session::flash('warning', 'Unable to create room');
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
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function getRoomMaster(Request $request, $id)
    {
        $roomCategoryList = RoomCategory::all();
        $room = RoomMaster::findorfail($id);
        if($room){
            return view('academics::timetable.roommaster', compact("roomCategoryList"))->with(compact('roomList'));
        }


    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function deleteRoomMaster(Request $request, $id)
    {
        $room = RoomMaster::findorfail($id);
        if($room){
            $room->delete();
            Session::flash('success', 'Room deleted successfully');
            return redirect()->back();
        }
        else{
            Session::flash('warning', 'Invalid Information');
            return redirect()->back();
        }
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
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}
