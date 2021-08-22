<?php

namespace Modules\Communication\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Communication\Entities\Event;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Employee\Entities\NationalHolidayDetails;
use Modules\Employee\Entities\WeekOffDay;

class EventController extends Controller
{

    private  $event;
    private  $weekOffDay;
    private  $nationalHolidayDetails;
    private  $academicHelper;

    public function __construct(Event $event, AcademicHelper $academicHelper, NationalHolidayDetails $nationalHolidayDetails, WeekOffDay $weekOffDay)
    {
        $this->event = $event;
        $this->weekOffDay = $weekOffDay;
        $this->nationalHolidayDetails = $nationalHolidayDetails;
        $this->academicHelper = $academicHelper;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        // event list
        $eventList = $this->event->where([
            'campus'=>$this->academicHelper->getCampus(),
            'institute'=>$this->academicHelper->getInstitute(),
        ])->orderBy('created_at', 'DESC')->paginate(10);
        // return view with variable
        return view('communication::pages.event.event', compact('eventList'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        // event profile
        $eventProfile = null;
        // return view with variable
        return view('communication::pages.event.modals.event', compact('eventProfile'));
    }

    /**
     * Store a newly created or Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        // receive event id
        $eventId = $request->input('event_id');
        // checking event id
        if($eventId>0){
            // create event new instance
            $eventProfile = $this->event->find($eventId);
        }else{
            // create event new instance
            $eventProfile = new $this->event();
        }
        // input details
        $eventProfile->user_type = $request->input('event_user_type');
        $eventProfile->title = $request->input('event_title');
        $eventProfile->detail = $request->input('event_detail');
        $eventProfile->start_date_time = $request->input('event_start_date_time');
        $eventProfile->end_date_time = $request->input('event_end_date_time');
        $eventProfile->campus = $this->academicHelper->getCampus();
        $eventProfile->institute = $this->academicHelper->getCampus();
        // save event profile
        $eventProfileSaved = $eventProfile->save();
        // checking
        if($eventProfileSaved){
            Session::flash('success', 'Event '.($eventId>0?'Updated':'Created'). ' Successfully');
            return redirect()->back();
        }else{
            Session::flash('warning', 'Unable to perform the action');
            return redirect()->back();
        }
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('communication::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        // event profile
        $eventProfile = $this->event->find($id);
        // return view with variable
        return view('communication::pages.event.modals.event', compact('eventProfile'));
    }

    /**
     * change status the specified resource.
     * @return Response|mixed
     */
    public function status(Request $request)
    {
        // event id
        $eventId = $request->input('event_id');
        // event profile
        $eventProfile = $this->event->find($eventId);
        // change status profile
        $eventProfile->status = $eventProfile->status==0?1:0;
        //save
        $eventProfileSaved = $eventProfile->save();
        // checking
        if($eventProfileSaved){
            return array('status'=>'success', 'event_status'=>$eventProfile->status, 'msg'=>'Event Status Changed successfully');
        }else{
            return array('status'=>'failed', 'msg'=>'Unable to perform the action');
        }
    }
    /**
     * Remove the specified resource from storage.
     * @return Response|mixed
     */
    public function destroy(Request $request)
    {
        // event id
        $eventId = $request->input('event_id');
        // event profile
        $eventProfile = $this->event->find($eventId);
        // delete profile
        $eventProfileDeleted = $eventProfile->delete();
        if($eventProfileDeleted){
            return array('status'=>'success', 'msg'=>'Event deleted successfully');
        }else{
            return array('status'=>'failed', 'msg'=>'Unable to perform the action');
        }
    }

    // get current single month event, holiday list and week off day list
    public function getMonthSchedule($year, $month)
    {
        // response data
        $response = array();
        $campus = $this->academicHelper->getCampus();
        $institute = $this->academicHelper->getInstitute();
        $academicYear = $this->academicHelper->getAcademicYear();
        $monthDayList = $this->getMonthDateList($month, $year);
        // checking
        if($year AND $month AND $campus AND $institute){
            // month day looping
            foreach ($monthDayList as $date=>$dateProfile){
                // find date event / holiday / weekOffDay list
                $holidayDetails = $this->nationalHolidayDetails->where(['date'=>date('Y-m-d', strtotime($date)), 'campus_id'=>$campus, 'institute_id'=>$institute])->first();
                $weekOffDayList = $this->weekOffDay->where(['date'=>date('Y-m-d', strtotime($date)), 'campus_id'=>$campus, 'institute_id'=>$institute])->get();
                $eventList = $this->event->whereBetween('start_date_time', [date('Y-m-d 00:00:00', strtotime($date)), date('Y-m-d 23:59:59', strtotime($date))])
                    ->where(['campus'=>$campus, 'institute'=>$institute, 'status'=>1 ])->get();
                // find event list and checking
                $response[$date]['event'] = $eventList->count()>0?true:false;
                $response[$date]['holiday'] = $holidayDetails?true:false;
                $response[$date]['week_off_day'] = $weekOffDayList->count()>0?true:false;
            }
        }else{
            $response['Invalid Information'];
        }
        // return
        return $response;
    }

    ////////////////// dashboard ajax request section starts //////////////////

    public function getDashboardEvent(Request $request)
    {
        // input details
        $selectedDate = $request->input('selected_date');
        $campusId = $this->academicHelper->getCampus();
        $instituteId = $this->academicHelper->getInstitute();
        $academicYearId = $this->academicHelper->getAcademicYear();

        // date conversion for event list
        $fromDayDateTime = date('Y-m-d 00:00:00', strtotime($selectedDate));
        $toDayDateTime = date('Y-m-d 23:59:59', strtotime($selectedDate));

        // find event list with selected date
        $eventList = $this->event->whereBetween('start_date_time', [$fromDayDateTime, $toDayDateTime])->where([
            'campus'=>$campusId,
            'institute'=>$instituteId,
            'status'=>1
        ])->get();

        // find academic week off day list
        $weekOffDayList = $this->weekOffDay->where([
            'date'=>date('Y-m-d', strtotime($selectedDate)),
            'campus_id'=>$campusId,
            'institute_id'=>$instituteId,
        ])->get();

        // find academic holiday list
        $nationalHolidayDetailsProfile = $this->nationalHolidayDetails->where([
            'date'=>date('Y-m-d', strtotime($selectedDate)),
            'campus_id'=>$campusId,
            'institute_id'=>$instituteId,
            'academic_year'=>$academicYearId,
        ])->first();

        // return view with variable
        return view('communication::pages.event.modals.dashboard-event-list', compact('eventList', 'weekOffDayList', 'nationalHolidayDetailsProfile', 'selectedDate'));
    }



    // get month date list
    public function getMonthDateList($month, $year)
    {
        // day numeric array list
        $responseData = array();
        // checking
        if($month AND $year){
            // find month date range
            $monthFirstDate = date('1', strtotime($year . '-' . $month . '-01'));
            $monthLastDate = date('t', strtotime($year . '-' . $month . '-01'));
            // current month looping
            for ($day = $monthFirstDate; $day <= $monthLastDate; $day++) {
                // current loop date
                $toDayDate = date('Y-m-d', strtotime($year . "-" . $month . "-" . $day));
                // day details
                $dayId = date('w', strtotime($toDayDate));
                $dayName = date('l', strtotime($toDayDate));
                // input Date details
                $responseData[$toDayDate] = ['id'=>$dayId, 'name' => $dayName];
            }
        }
        // return
        return $responseData;
    }



    //////////////////////////////////////////  dashboard ajax request section ends ////////////////////////////////////////////////////////



}
