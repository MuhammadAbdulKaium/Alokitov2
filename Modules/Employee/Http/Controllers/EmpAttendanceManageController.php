<?php

namespace Modules\Employee\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Employee\Entities\AttendanceDevice;
use Modules\Employee\Entities\AttendanceDeviceLog;
use Modules\Employee\Entities\EmployeeAttendance;
use Modules\Employee\Entities\EmployeeDepartment;
use Modules\Employee\Entities\EmployeeDesignation;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\Student\Entities\StudentInformation;
use Redirect;
use Session;
use Validator;
use App;
class EmpAttendanceManageController extends Controller
{
    protected $academicHelper;
    protected $attendanceDevice;
    protected $holidayController;
    protected $studentInformation;
    protected $employeeInformation;
    public function __construct(AcademicHelper $academicHelper, EmployeeInformation $employeeInformation, AttendanceDevice $attendanceDevice, StudentInformation $studentInformation, NationalHolidayController $holidayController)
    {
        $this->academicHelper = $academicHelper;
        $this->attendanceDevice = $attendanceDevice;
        $this->holidayController = $holidayController;
        $this->studentInformation = $studentInformation;
        $this->employeeInformation = $employeeInformation;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        if(isset($request->access_date)){
            $empAttAll= $this->getAllEmoployeeAttendance($request->access_date);
            $requestDate=$request->access_date;
        }else{
            $yesterDay = Carbon::now()->subDay(1);
            $empAttAll= $this->getAllEmoployeeAttendance($yesterDay);
            $requestDate=$yesterDay;
        }
        return view('employee::pages.attendance.attendance',compact('empAttAll','requestDate'));
    }


    ////////////////// Employee Custom Attendance List /////////////////////////////////////

    public function addCustomAttendance()
    {
        // attendance list
        $empAttendanceList = ['status'=>false, 'msg'=>'No Response From Server'];
        // return view with variable
        return view('employee::pages.attendance.custom-attendance', compact('empAttendanceList'));
    }



    public function getCustomAttendance(Request $request)
    {
        // user type
        $userType = $request->input('user_type');
        $accessId = $request->input('access_id', null);
        $campusId = $this->academicHelper->getCampus();
        $instituteId = $this->academicHelper->getInstitute();
        // start date & time
        $start_date = date('Y-m-d', strtotime($request->start_date));
        $start_time = date('H:i:s', strtotime($request->start_time));
        // end date & time
        $end_date = date('Y-m-d', strtotime($request->end_date));
        $end_time = date('H:i:s', strtotime($request->end_time));
        // json data
        $json = array();
        $json["operation"] = "fetch_log";
        $json["auth_code"] = "bh0qfj1gvnpddbcml0tej7z3vaaoosh";
        $json["start_date"] = $start_date;
        $json["start_time"] = $start_time;
        $json["end_date"] = $end_date;
        $json["end_time"] = $end_time;
        // checking access id
        if($accessId) $json["access_id"] = $accessId;

        Log::info("Attendance Device Job Started...");
        Log::info($json);
        //GuzzleHttp\Client
        $client = new Client();
        // guzzle client request
        $attendanceList = json_decode($client->request('POST', 'https://rumytechnologies.com/rams/json_api', ['json' => $json])->getBody()->getContents())->log;
        // attendance job log
        Log::info("Attendance Device Job Ended...");
        Log::info($attendanceList);
        // return view with variable
        return view('employee::pages.attendance.modals.custom-attendance', compact('attendanceList', 'userType', 'campusId', 'instituteId'));
    }

    // storeCustomAttendance
    public function storeCustomAttendance(Request $request)
    {
        // input request
        $attendanceList = $request->input('attendance_list', []);
        // checking attendance list
        if($attendanceList AND count($attendanceList)>0) {
            // find last attendance log
            $attendanceDeviceLog = AttendanceDeviceLog::where('status', 'success')->orderBy('created_at', 'desc')->first();
            // not found list
            $notFoundList = [];

            // attendance count
            $attendanceCount = 0;
            // access id counter
            $myLastAccessId = 0;

            // attendance date list looping
            foreach ($attendanceList as $attendance) {
                $attendance = (object)$attendance;
                // checking person type
                if ($attendance->person_type == 'employee') {
                    $userProfile = $this->employeeInformation->find($attendance->person_id);
                    // checking employee profile
                    if (empty($userProfile)) {
                        ($notFoundList[] = $attendance->person_id);
                        continue;
                    }
                    // employee institute and campus
                    $campus = $userProfile->campus_id;
                    $institute = $userProfile->institute_id;
                } else {
                    $userProfile = $this->studentInformation->find($attendance->person_id);
                    // checking student profile
                    if (empty($userProfile)) {
                        ($notFoundList[] = $attendance->person_id);
                        continue;
                    }
                    // student institute and campus
                    $campus = $userProfile->campus;
                    $institute = $userProfile->institute;
                }

                // new attendance device
                $attendanceDevice = new $this->attendanceDevice();
                // store attendance details
                $attendanceDevice->card = $attendance->card;
                $attendanceDevice->access_id = $attendance->access_id;
                $attendanceDevice->access_date = $attendance->access_date;
                $attendanceDevice->access_time = $attendance->access_time;
                $attendanceDevice->registration_id = $attendance->person_id;
                $attendanceDevice->person_type = $attendance->person_type;
                $attendanceDevice->campus_id = $campus;
                $attendanceDevice->institute_id = $institute;
                // save attendance
                if ($attendanceDevice->save()) {
                    $attendanceCount += 1;
                    $myLastAccessId = $attendance->access_id;
                }
            }


            // checking access id
            if ($myLastAccessId > 0){
                $attendanceDeviceLog->access_id = $myLastAccessId;
                $attendanceDeviceLog->update();
            }
            // checking
            if($attendanceCount==count($attendanceList)){
                // return
                return ['status'=>true, 'msg'=>'Attendance Uploaded'];
            }else{
                Log::info(count($notFoundList).' Person Not Found in Database');
                Log::info($notFoundList);
                // return
                return ['status'=>false, 'not_found_list'=>$notFoundList, 'msg'=>'Unable to Store '.count($notFoundList).' Attendance'];
            }
        }else{
            return ['status'=>false, 'msg'=>'Unable to Uploaded Attendance'];
        }
    }

////////////////// Employee Custom Attendance List /////////////////////////////////////


// get all employee attendance function
    public function  getAllEmoployeeAttendance($date){
        $institute_id=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

        return DB::table('employee_informations')
            ->leftJoin('employee_attendance', function ($join) use($date) {
                $join->on('employee_informations.id', '=', 'employee_attendance.emp_id')->where('employee_attendance.in_date' , date('Y:m:d', strtotime($date)));
            })
            ->leftJoin('employee_departments', 'employee_informations.department', '=', 'employee_departments.id')
            ->leftJoin('employee_designations', 'employee_informations.designation', '=', 'employee_designations.id')
            ->select('employee_informations.user_id as user_id','employee_informations.first_name as first_name','employee_informations.middle_name as middle_name','employee_informations.last_name as last_name','employee_departments.name as department','employee_designations.name as designation', 'employee_attendance.in_time as in_time', 'employee_attendance.out_time as out_time')
            ->where('employee_informations.institute_id',$institute_id)
            ->where('employee_informations.campus_id',$campus_id)
            ->where('employee_informations.status',1)
            ->orderBy('employee_informations.category', 'DESC')
            ->orderBy('employee_informations.sort_order', 'ASC')
            ->get();
    }


    // get all employee attendance for month
    public function  getAllEmployeeMonthlyAttendance($year, $month){
        // institute details
        $campus_id = $this->academicHelper->getCampus();
        $institute_id = $this->academicHelper->getInstitute();
        // employee monthly attendance list
        $empMonthlyAllAttendance =  DB::table('employee_attendance')->whereMonth('in_date' , $month)->whereYear('in_date', $year)
            ->where('company_id',$institute_id)->where('brunch_id',$campus_id)->get(['emp_id', 'in_date', 'in_time', 'out_time']);

        //array attendance list
        $empAttendanceArrayList = [];
        // employee monthly attendance list looping
        foreach ($empMonthlyAllAttendance as $attendance){
            $empAttendanceArrayList[$attendance->emp_id][$attendance->in_date]=['in_time'=>$attendance->in_time, 'out_time'=>$attendance->out_time];
        }
        // return employee monthly attendance list
        return $empAttendanceArrayList;
    }





    public function emp_list(Request $request){

        $department  = $request->input('department');
        $designation = $request->input('designation');

        $allSearchInputs = array();
        // check department
        if ($department) $allSearchInputs['department'] = $department;
        // check designation
        if ($designation!=='null') $allSearchInputs['designation'] = $designation;
//        return $allSearchInputs;
        // search result
        $allEmployee = EmployeeInformation::where($allSearchInputs)->where('institute_id', session()->get('institute'))
            ->where('campus_id', session()->get('campus'))
            ->get();
        return view('employee::pages.attendance.attendance_emp_list',compact('allEmployee'));
    }

    public function addAttendance(){
        $allDep = EmployeeDepartment::where(['institute_id'=>session()->get('institute'), 'dept_type'=>0])->orderBy('name', 'ASC')->get();
        return view('employee::pages.attendance.add-attendance',compact('allDep','allDes'));
    }

    public function updateAttendance($attendanceId){
        $emplAttendanceProfile=EmployeeAttendance::where('id',$attendanceId)->first();
        return view('employee::pages.attendance.update-attendance',compact('emplAttendanceProfile'));
    }


    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(){
        return view('employee::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {


        $attendance_id=$request->input('id');
        $validator = Validator::make($request->all(), [
            'startDate' => 'required',
            'startTime' => 'required',
            'endDate' => 'required',
            'endTime' => 'required',
        ]);
        if ($validator->passes()){


            $startDate = date('Y-m-d',strtotime($request->input('startDate')));
            $endDate = date('Y-m-d',strtotime($request->input('endDate')));
            $startTime = date('H:i:s',strtotime($request->input('startTime')));
            $endTime = date('H:i:s',strtotime($request->input('endTime')));

            if(!empty($attendance_id)) {

                $employAttProfile = EmployeeAttendance::where('id', $attendance_id)->first();
                $employAttProfile->in_time = $startTime;
                $employAttProfile->out_time = $endTime;
                $result = $employAttProfile->update();

            } else {

                foreach ($request->attenAloChkBox as $empId) {
                    $employAttendanceProfile = EmployeeAttendance::where('emp_id', $empId)->where('in_date', $startDate)->first();
                    if (!empty($employAttendanceProfile)) {
                        $employAttendanceProfile->emp_id = $empId;
                        $employAttendanceProfile->in_date = $startDate;
                        $employAttendanceProfile->in_time = $startTime;
                        $employAttendanceProfile->out_date = $endDate;
                        $employAttendanceProfile->out_time = $endTime;
                        $employAttendanceProfile->brunch_id = session()->get('campus');
                        $employAttendanceProfile->company_id = session()->get('institute');
                        $result = $employAttendanceProfile->save();

                    } else {
                        $empAtt = new EmployeeAttendance();
                        $empAtt->emp_id = $empId;
                        $empAtt->in_date = $startDate;
                        $empAtt->in_time = $startTime;
                        $empAtt->out_date = $endDate;
                        $empAtt->out_time = $endTime;
                        $empAtt->brunch_id = session()->get('campus');
                        $empAtt->company_id = session()->get('institute');
                        $result = $empAtt->save();
                    }
                }
            }
            // checking
            if ($result) {
                Session::flash('success', 'Done');
                // return redirect
                return redirect()->back();
            } else {
                Session::flash('warning', 'Uabale to perform the actions');
                // return redirect
                return redirect()->back();
            }
        } else {
            Session::flash('warning', 'Invalid Information. please try with correct Information');
            // receiving page action
            return redirect()->back()->withErrors($validator)->withInput();
        }

    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id)
    {
        $empAtt = EmployeeAttendance::where('id',$id)
            ->where('company_id', session()->get('institute'))
            ->where('brunch_id', session()->get('campus'))
            ->get();
        return view('employee::pages.attendance.attendance_emp',compact('empAtt'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function uploadAttForm(){
        return view('employee::pages.attendance.attendance_emp_upload');
    }

    public function fileUp(Request $request){
        $file = $request->file('file');
        if($file){
            // get file real path
            $filePath = $file->getRealPath();
            // receive data from the input file
            $data = Excel::load($filePath, function($reader) {})->get();
            if($data->count()>0){
                // return view with variable
                return view('employee::pages.attendance.upload_attendance',compact('data'));
            }else{
                return 0;
            }
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function fileUpSave(Request $request){
        $file = $request->file('myFile');
        if($file){
            // get file real path
            $filePath = $file->getRealPath();
            $fileExt = $file->getClientOriginalExtension();
            if($fileExt == 'xls' || $fileExt == 'xlsx'){
                // receive data from the input file
                $data = Excel::load($filePath, function($reader){})->get();
                if($data->count()>0){
                    foreach ($data as $d){
                        $emp_id = $d->employee_id;
                        $emp_name = $d->employee_name;
                        $in_date = date_format(date_create_from_format('d-m-Y', $d->employee_in_date), 'Y-m-d');
                        $in_time = date_format($d->employee_in_time, 'H:i:s');
                        $out_date = date_format(date_create_from_format('d-m-Y', $d->employee_out_date), 'Y-m-d');
                        $out_time= date_format($d->employee_out_time, 'H:i:s');

                        $empAtt = new EmployeeAttendance();
                        $empAtt->emp_id = $emp_id;
                        $empAtt->in_date = $in_date;
                        $empAtt->in_time = $in_time;
                        $empAtt->out_date = $out_date;
                        $empAtt->out_time = $out_time;
                        $empAtt->brunch_id = session()->get('campus');
                        $empAtt->company_id = session()->get('institute');
                        $empAtt->save();
                    }
                    // checking
                    if ($empAtt) {
                        Session::flash('success', ' Done');
                        // return redirect
                        return redirect()->back();
                    } else {
                        Session::flash('warning', 'Uabale to perform the actions');
                        // return redirect
                        return redirect()->back();
                    }
                }else{
                    Session::flash('warning', 'Something is wrong');
                    // return redirect
                    return redirect()->back();
                }
            }elseif ($fileExt=='txt'){
                Session::flash('warning', 'We are working on it');
                // return redirect
                return redirect()->back();
            }else{
                Session::flash('warning', 'Something is wrong');
                // return redirect
                return redirect()->back();
            }
        }
        Session::flash('warning', 'Something is wrong');
        // return redirect
        return redirect()->back();
    }

    public function uploadAttStore(Request $request){
        return $request->all();
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('employee::edit');
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

    public function view(){
        $now = Carbon::now();
        $campus_id = $this->academicHelper->getCampus();
        $institute_id = $this->academicHelper->getInstitute();
        $empAttAll = $this->getEmployeeInOut($institute_id, $campus_id);
        return view('employee::pages.attendance.attendance-view', compact('empAttAll'));
    }

    public function getEmployeeInOut($institute_id, $campus_id){
        $now = Carbon::now();
        $empAttAll = AttendanceDevice::select(['registration_id', 'access_date', 'person_type'])
            ->selectRaw('min(`access_time`) as access_time')
            ->selectRaw('max(`access_time`) as max_access_time')
            ->where('person_type', 'employee')
            ->where('access_date', $now->toDateString())
            ->where('institute_id', $institute_id)
            ->where('campus_id', $campus_id)
            ->orderByRaw('min(access_time)', 'asc')
            ->groupBy(['registration_id', 'person_type', 'access_date'])
            ->havingRaw('min(access_time) >= \'06.00.00\'')
            ->havingRaw('max(access_time) >= \'06.00.00\'')
            ->get();
        return $empAttAll;
    }


// download monthly report View

    public function  viewMonthlyReportFrom() {
        return view('employee::pages.attendance.attendance-monthly-report');
    }

    public function  monthlyAttendanceReport(Request $request) {

        $institute_id=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();
        $report_type = $request->input('report_type');
        $academicYearId = $this->academicHelper->getAcademicYear();
        $instituteInfo = $this->academicHelper->getInstituteProfile();
        $year = $request->input('year');
        $month = $request->input('month');

        // find all employee list
        $allEmployeeList = $this->employeeInformation->where(['institute_id'=>$institute_id, 'campus_id'=>$campus_id])
            ->orderBy('category', 'DESC')->orderBy('sort_order', 'ASC')->get(['id', 'first_name', 'middle_name', 'last_name']);
        // find all employee list with attendance
        $employeeAttendanceList = $this->getAllEmployeeMonthlyAttendance($request->year,$request->month);
        // academic holiday list
        $academicHolidayList = $this->holidayController->holidayList($academicYearId, $campus_id, $institute_id);

        // find name of the month
        $monthName = date("F", mktime(0, 0, 0, $month, 10));
        // share all variables with view
        view()->share(compact('allEmployeeList', 'employeeAttendanceList', 'instituteInfo','report_type','month','year','monthName', 'academicHolidayList'));

        // checking report type
        if($report_type=='pdf'){

            // generate PDf
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView('employee::pages.attendance.attendance-monthly-report-download')->setPaper('a4', 'landscape');
            $downloadFileName = "Employee-Monthly-Attendance-Report.pdf";
            // stream pdf
            return $pdf->stream();
            // download pdf
            //return $pdf->download($downloadFileName);

        }else{

            Excel::create('employee'.$monthName.'-attendance', function ($excel) {
                $excel->sheet('employee-attendance-report', function ($sheet) {
                    // Font family
                    $sheet->setFontFamily('Comic Sans MS');
                    // Set font with ->setStyle()
                    $sheet->setStyle(array('font' => array('name' => 'Calibri', 'size' => 12)));
                    // cell formatting
                    $sheet->setAutoSize(true);
                    // Set all margins
                    $sheet->setPageMargin(0.25);
                    // mergeCell
                    // $sheet->mergeCells(['C3:D1', 'E1:H1']);

                    $sheet->loadView('employee::pages.attendance.attendance-monthly-report-download-excel');
                });
            })->download('xls');

        }
    }




// downlaod employee attendance report

    public function  downloadAttendanceReport(Request $request)
    {

        $report_type=$request->input('report_type');
        $instituteProfile = $this->academicHelper->getInstituteProfile();
        $empAttAll = $this->getAllEmoployeeAttendance($request->date);
        $date=$request->date;
        // get institute profile
        // view('employee::pages.attendance.attendance-report-download', compact('empAttAll','instituteProfile'));

//
        if($report_type=="pdf") {

            view()->share(compact('empAttAll','instituteProfile','report_type','date'));

            //generate PDf
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView('employee::pages.attendance.attendance-report-download')->setPaper('a4', 'portrait');
            // return $pdf->stream();
            $downloadFileName = "Employee-attendance-report.pdf";
            return $pdf->download($downloadFileName);

        } else {
            view()->share(compact('empAttAll','instituteProfile','report_type','date'));
            //generate excel
            return Excel::create('Employee-attendance-report', function ($excel) {
                $excel->sheet('Employee-attendance-report', function ($sheet) {
                    // Font family
                    $sheet->setFontFamily('Comic Sans MS');
                    // Set font with ->setStyle()
                    $sheet->setStyle(array('font' => array('name' => 'Calibri', 'size' => 12)));
                    // cell formatting
                    $sheet->setAutoSize(true);
                    // Set all margins
                    $sheet->setPageMargin(0.25);
                    // mergeCell
                    // $sheet->mergeCells(['C3:D1', 'E1:H1']);

                    $sheet->loadView('employee::pages.attendance.attendance-report-download');
                });
            })->download('xls');

        }
    }

    public function empAttendanceDaily($instituteId, $campusId){
        $presentEmployeeCount = $this->getEmployeeInOut($instituteId, $campusId)->count();
        $allEmployeeCount = EmployeeInformation::where('institute_id',$instituteId)
            ->where('campus_id',$campusId)->get()->count();
        $data['present_percent'] = 0;
        $data['absent_percent'] = 0;
        $data['total_present'] = 0;
        $data['total_absent'] = 0;
        $data['total_employee'] = 0;
        if(isset($allEmployeeCount)){
            if($allEmployeeCount>0){

                $present = round($presentEmployeeCount/$allEmployeeCount*100);
                $data['status'] = 'success';
                $data['present_percent'] = $present;
                $data['absent_percent'] = 100 - $present;
                $data['total_present'] = $presentEmployeeCount;
                $data['total_absent'] = $allEmployeeCount - $presentEmployeeCount;
                $data['total_employee'] = $allEmployeeCount;
            }
        }

        return $data;
    }

}
