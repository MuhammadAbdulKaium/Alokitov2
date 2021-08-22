<?php

namespace Modules\Communication\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Communication\Entities\SmsLog;
use App\Http\Controllers\Helpers\AcademicHelper;
use Carbon\Carbon;

class SmsLogController extends Controller
{

    private  $smsLog;
    private  $academicHelper;


    public function __construct(SmsLog $smsLog, AcademicHelper $academicHelper)
    {
        $this->smsLog                     = $smsLog;
        $this->academicHelper             = $academicHelper;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {

        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();
        $searchSmsLog="";

        $smsLogsCount=$this->smsLog->where('institution_id',$instituteId)->where('campus_id',$campus_id)->orderBy('id','desc')->get()->count();
        $smsLogs=$this->smsLog->where('institution_id',$instituteId)->where('campus_id',$campus_id)->orderBy('id','desc')->paginate(20);
        return view('communication::pages.sms.sms_log',compact('smsLogs','searchSmsLog','smsLogsCount'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function smsLogSearch(Request $request)
    {

//         return $request->all();
        $instituteId = $this->academicHelper->getInstitute();
        $campus_id = $this->academicHelper->getCampus();


         $from_date =date('Y-m-d H:i:s', strtotime($request->input('start_date'))) ;
         $to_date = $request->input('end_date');
         $user_group = $request->input('user_group');


        // use carbon for date

        if (!empty($to_date)) {
            $to_date = date('Y-m-d', strtotime($to_date));
            $to_date = new Carbon($to_date);
            $to_date = $to_date->endOfDay();
        }


        if($user_group == "5" || empty($user_group)) {
            $smsLogsCount = $this->smsLog->where('institution_id', $instituteId)->where('campus_id', $campus_id)->whereBetween('created_at', [$from_date, $to_date])->orderBy('id', 'desc')->get()->count();
            $smsLogs = $this->smsLog->where('institution_id', $instituteId)->where('campus_id', $campus_id)->whereBetween('created_at', [$from_date, $to_date])->orderBy('id', 'desc')->paginate(20);
        }
        else {
            $smsLogsCount = $this->smsLog->where('institution_id', $instituteId)->where('campus_id', $campus_id)->where('user_group', $user_group)->whereBetween('created_at', [$from_date, $to_date])->orderBy('id', 'desc')->get()->count();
            $smsLogs = $this->smsLog->where('institution_id', $instituteId)->where('campus_id', $campus_id)->where('user_group', $user_group)->whereBetween('created_at', [$from_date, $to_date])->orderBy('id', 'desc')->paginate(20);
        }

        if ($smsLogs) {
            // all inputs
            $allInputs = [
                'search_start_date' => $from_date,
                'search_end_date' => $to_date,
                'user_group' => $user_group,
            ];
        }
            // return view
            $allInputs=(Object)$allInputs;

            $searchSmsLog=1;

        return view('communication::pages.sms.sms_log',compact('smsLogs','searchSmsLog','allInputs','smsLogsCount'));



    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
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
    public function edit()
    {
        return view('communication::edit');
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
