<?php

namespace Modules\Setting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Setting\Entities\AttendanceFine;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Helpers\AcademicHelper;

class AttendanceFineController extends Controller
{


    private $academicHelper;

    // constructor
    public function __construct(AcademicHelper $academicHelper)
    {
        $this->academicHelper  = $academicHelper;
    }



    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {

        ///get institute Id and Campus Id
        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

        $attendanceFines=AttendanceFine::where('ins_id',$instituteId)->where('campus_id',$campus_id)->get();
        return view('setting::attendance_fine.fine_list',compact('attendanceFines'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('setting::attendance_fine.create');
    }


    public function edit($attendanceSettingId)
    {
        $attendanceSettingProfile=AttendanceFine::find($attendanceSettingId);
        return view('setting::attendance_fine.create',compact('attendanceSettingProfile'));
    }


    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
//        return $request->all();
        // fees setting id
        $attendance_setting_id=$request->input('attendance_setting_id');
        $sorting_order=$request->input('sorting_order');
        // session institute id
        $institutionId=session()->get('institute');
        // session campus id
        $campus_id=session()->get('campus');

        if(empty($attendance_setting_id)) {
            $attendanceProfile=AttendanceFine::where('sorting_order',$sorting_order)->where('ins_id',$institutionId)->where('campus_id',$campus_id)->first();
           if(empty($attendanceProfile)) {
               $attendanceFine = new AttendanceFine;
               $attendanceFine->ins_id = $institutionId;
               $attendanceFine->campus_id = $campus_id;
               $attendanceFine->amount = $request->input('amount');
               $attendanceFine->setting_type = $request->input('setting_type');
               $attendanceFine->form_entry_time = $request->input('form_entry_time');
               $attendanceFine->to_entry_time = $request->input('to_entry_time');
               $attendanceFine->sorting_order = $request->input('sorting_order');
               $insert = $attendanceFine->save();
               if ($insert) {
                   Session::flash('success', 'Attendance Fine Setting Successfully');
                   return redirect()->back();
               } else {
                   Session::flash('error', 'Attendance Fine Setting Error Found');
                   return redirect()->back();
               }
           }
            else {
                Session::flash('error', 'Attendance Sorting Order Already Use Try Again');
                return redirect()->back();
            }
        } else {

            $attendanceFine =AttendanceFine::find($attendance_setting_id);
            $attendanceFine->ins_id = $institutionId;
            $attendanceFine->campus_id = $campus_id;
            $attendanceFine->amount = $request->input('amount');
            $attendanceFine->setting_type = $request->input('setting_type');
            $attendanceFine->form_entry_time = $request->input('form_entry_time');
            $attendanceFine->to_entry_time = $request->input('to_entry_time');
            $attendanceFine->sorting_order = $request->input('sorting_order');
            $insert = $attendanceFine->save();
            if ($insert) {
                Session::flash('success', 'Fees Setting Update Successfully');
                return redirect()->back();
            } else {
                Session::flash('error', 'Fees Setting Error Found');
                return redirect()->back();
            }

        }

    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('setting::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */

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
    public function delete($id)
    {
       $attendanceProfile=  AttendanceFine::find($id);
       $attendanceProfile->delete();
       return "success";
    }
}
