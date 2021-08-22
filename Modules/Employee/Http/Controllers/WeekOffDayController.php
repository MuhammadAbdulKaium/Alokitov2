<?php

namespace Modules\Employee\Http\Controllers;

use Redirect;
use Session;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Employee\Entities\WeekOffDay;
use Modules\Employee\Entities\NationalHoliday;
use Modules\Employee\Entities\NationalHolidayDetails;
use Modules\Employee\Http\Controllers\NationalHolidayController;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Employee\Entities\EmployeeDepartment;


class WeekOffDayController extends Controller
{
    private $db;
    private $weekOffDay;
    private $academicHelper;
    private $holidayDetails;
    private $nationalHoliday;
    private $nationalHolidayController;
    private $employeeDepartment;

    // constructor
    public function __construct(AcademicHelper $academicHelper, WeekOffDay $weekOffDay, NationalHoliday $nationalHoliday, EmployeeDepartment $employeeDepartment, NationalHolidayDetails $holidayDetails, NationalHolidayController $nationalHolidayController, DB $db)
    {
        $this->db = $db;
        $this->weekOffDay = $weekOffDay;
        $this->academicHelper = $academicHelper;
        $this->holidayDetails = $holidayDetails;
        $this->nationalHoliday = $nationalHoliday;
        $this->nationalHolidayController = $nationalHolidayController;
        $this->employeeDepartment = $employeeDepartment;
    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        // all week off days
        $allWeekOffDays = $this->weekOffDay->where([
            'academic_year'=>$this->academicHelper->getAcademicYear(),
            'campus_id'=>$this->academicHelper->getCampus(),
            'institute_id'=>$this->academicHelper->getInstitute(),
        ])->orderBy('dept_id', 'ASC')->orderBy('date', 'ASC')->get();
        // return view with variable
        return view('employee::pages.week-off-management.week-off', compact('allWeekOffDays'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        // find department list
        $departmentList = $this->employeeDepartment->where([
            'campus_id'=>$this->academicHelper->getCampus(), 'institute_id'=>$this->academicHelper->getInstitute(), 'dept_type'=>1
        ])->get();
        // return view with variable
        return view('employee::pages.week-off-management.modals.add-week-off', compact('departmentList'));
    }


    public function store(Request $request)
    {
        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'departments'   => 'required',
            'week_off_days' => 'required'
        ]);

        // validation checker
        if ($validator->passes()) {
            // request details
            $departments = $request->input('departments');
            $weekOffDays = $request->input('week_off_days');
            $campusId = $this->academicHelper->getCampus();
            $instituteId = $this->academicHelper->getInstitute();
            $academicYearId = $this->academicHelper->getAcademicYear();

            // Start transaction!
            DB::beginTransaction();
            // start creating week off day
            try {

                // dept loop counter counter
                $deptLoopCounter = 0;

                // department looping
                foreach ($departments as $deptId){

                    // month loop counter
                    $monthLoopCounter = 0;

                    // current year
                    $year = date('Y');
                    // current year looping
                    for($month=1; $month<=12; $month++) {

                        // week off day loop counter
                        $weekOffDayCounter = 0;
                        $weekOffDayLoopCounter = 0;

                        // get day array list
                        $dayDateArrayList = $this->getMonthDayArrayList($year, $month);
                        // week off day looping
                        foreach ($weekOffDays as $dayId=>$dayOffType){

                            // week off day count
                            $weekOffDayCounter += 1;

                            // checking $dayId
                            if($dayOffType==null || $dayId==7){ $weekOffDayLoopCounter += 1;  continue; }
                            // day's date list
                            $singleDayDateList = $dayDateArrayList[$dayId];

                            // date loop counter
                            $dateCounter = 0;
                            $dateLoopCounter = 0;

                            // date list looping
                            foreach ($singleDayDateList as $index=>$dateDetails){
                                // date counter
                                $dateCounter += 1;

                                // checking dayOffType
                                if ($dayOffType==2){ if($index%2==1){$dateLoopCounter += 1; continue;}}
                                if ($dayOffType==3){ if($index%2==0){$dateLoopCounter += 1; continue;}}
                                // find week-off day already exits or not
                                $oldWeekOffDayProfile = $this->checkWeekOffDay($dateDetails['date'], $deptId, $academicYearId, $campusId, $instituteId);
                                // checking week off day
                                if($oldWeekOffDayProfile==false) {
                                    // week off day profile
                                    $weekOffDayProfile = new $this->weekOffDay();
                                    // input day off details
                                    $weekOffDayProfile->date = $dateDetails['date'];
                                    $weekOffDayProfile->dept_id = $deptId;
                                    $weekOffDayProfile->academic_year = $academicYearId;
                                    $weekOffDayProfile->campus_id = $campusId;
                                    $weekOffDayProfile->institute_id = $instituteId;
                                    // save week off day
                                    $weekOffDayProfileSaved = $weekOffDayProfile->save();
                                    // checking
                                    if($weekOffDayProfileSaved) $dateLoopCounter += 1;
                                }else{
                                    $dateLoopCounter += 1;
                                }
                            }
                            // checking
                            if($dateCounter==$dateLoopCounter) $weekOffDayLoopCounter += 1;
                        }
                        // checking
                        if($weekOffDayCounter==$weekOffDayLoopCounter) $monthLoopCounter += 1;
                    }
                    // checking
                    if($monthLoopCounter==12){
                        $deptLoopCounter += 1;
                    }
                }

                // checking
                if($deptLoopCounter==count($departments)){
                    // If we reach here, then data is valid and working.
                    // Commit the queries!
                    DB::commit();
                    // session data
                    Session::flash('success', 'Week Off Day Created');
                    // receiving page action
                    return redirect()->back();
                }else{
                    // Rollback and then redirect back to form with errors
                    // Redirecting with error message
                    DB::rollback();
                    // session data
                    Session::flash('warning', 'Unable to Create Week Off Day');
                    // receiving page action
                    return redirect()->back();
                }

            } catch (ValidationException $e) {
                // Rollback and then redirect back to form with errors
                // Redirecting with error message
                DB::rollback();
                // session data
                Session::flash('warning', 'Fatal Error! Try catch exception');
                // receiving page action
                return redirect()->back();
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }
        }else{
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
        return view('employee::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        // find department list
        $departmentList = $this->employeeDepartment->where([
            'campus_id'=>$this->academicHelper->getCampus(), 'institute_id'=>$this->academicHelper->getInstitute(), 'dept_type'=>1
        ])->get();
        // return view with variable
        return view('employee::pages.week-off-management.modals.update-week-off', compact('departmentList'));
    }


    public function update(Request $request)
    {
        // validating all requested input data
        $validator = Validator::make($request->all(), ['date'   => 'required', 'schedule' => 'required']);
        // validation checker
        if ($validator->passes()) {
            // input details
            $date = date('Y-m-d', strtotime($request->input('date')));
            $schedule = $request->input('schedule');
            $departments = $request->input('departments');
            $campusId = $this->academicHelper->getCampus();
            $instituteId = $this->academicHelper->getInstitute();
            $academicYearId = $this->academicHelper->getAcademicYear();

            // Start transaction!
            DB::beginTransaction();
            // start creating week off day
            try {
                // checking day type
                if($schedule=='holiday'){
                    // holiday maker
                    $name = $request->input('name', 'holiday name');
                    $remarks =  $request->input('remarks', 'holiday remarks');
                    // make the day as holiday
                    $holidayMaker =  (object) $this->makeHoliday($name, $remarks, $date, $academicYearId, $campusId, $instituteId);
                    // checking
                    if($holidayMaker->status == 'success'){
                        // If we reach here, then data is valid and working.
                        // Commit the queries!
                        DB::commit();
                        // session data
                        Session::flash($holidayMaker->status, $holidayMaker->msg);
                    }else{
                        // If we reach here, then data is not valid and not working.
                        // rollback the queries!
                        DB::rollback();
                        // session data
                        Session::flash($holidayMaker->status, $holidayMaker->msg);
                    }
                    // redirect back
                    return redirect()->back();

                }elseif ($schedule=='week_off_day'){
                    // week off day maker
                    // department list loop counter
                    $deptCounter = 0;
                    // checking department list
                    if($departments AND !empty($departments) AND count($departments)>0){
                        // department list looping
                        foreach ($departments as $index=>$deptId){
                            // make the day as week off day
                            $weekOffDayMaker = (object)$this->makeWeekOffDay($campusId, $instituteId, $deptId, $date, $academicYearId);
                            // checking
                            if($weekOffDayMaker->status == 'success'){
                                // loop counter
                                $deptCounter += 1;
                            }else{
                                // rollback the queries!
                                DB::rollback();
                                // session data
                                Session::flash($weekOffDayMaker->status, $weekOffDayMaker->msg.' ('.$date.')');
                                // redirect back
                                return redirect()->back();
                            }
                        }
                    }else{
                        $deptCounter = count($departments);
                    }

                    // checking
                    if($deptCounter == count($departments)){
                        // Commit the queries!
                        DB::commit();
                        // session data
                        Session::flash('success', 'The day submitted as week off day');
                    }else{
                        //  rollback the queries!
                        DB::rollback();
                        // session data
                        Session::flash('warning', 'Unable to perform the action');
                    }
                    // redirect back
                    return redirect()->back();

                }else{
                    // department list loop counter
                    $deptCounter = 0;
                    // checking department list
                    if($departments AND !empty($departments) AND count($departments)>0){
                        // department list looping
                        foreach ($departments as $index=>$deptId){
                            // working day maker
                            $workingDayMaker = (object) $this->makeWorkingDay($deptId, $date, $academicYearId, $campusId, $instituteId);
                            // checking
                            if($workingDayMaker->status == 'success'){
                                // loop counter
                                $deptCounter += 1;
                            }else{
                                // rollback the queries!
                                DB::rollback();
                                // session data
                                Session::flash($workingDayMaker->status, $workingDayMaker->msg);
                                // redirect back
                                return redirect()->back();
                            }
                        }
                    }else{
                        $deptCounter = count($departments);
                    }

                    // checking
                    if($deptCounter == count($departments)){
                        // If we reach here, then data is valid and working. Commit the queries!
                        DB::commit();
                        // session data
                        Session::flash('success', 'The day converted as Working Day !!!');
                    }else{
                        // If we reach here, then data is not valid and not working. rollback the queries!
                        DB::rollback();
                        // session data
                        Session::flash('warning', 'Unable to perform the action');
                    }
                    // redirect back
                    return redirect()->back();
                }
            } catch (ValidationException $e) {
                // Rollback and then redirect back to form with errors  Redirecting with error message
                DB::rollback();
                // session data
                Session::flash('warning', 'Fatal Error! Try catch exception');
                // receiving page action
                return redirect()->back();
            } catch (\Exception $e) {
                // DB::rollback();
                throw $e;
            }
        }else{
            Session::flash('warning', 'Invalid Information');
            // receiving page action
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param  $weekOffId
     * @return Response
     */
    public function destroy($weekOffId)
    {
        // find holiday profile
        $weekOffDayProfile = $this->weekOffDay->find($weekOffId);
        // checking
        if($weekOffDayProfile){
            // now delete holiday profile
            if($weekOffDayProfile->delete()){
                Session::flash('success', 'Week-Off Day deleted');
                // receiving page action
                return redirect()->back();
            }else{
                Session::flash('warning', 'Unable to delete Week Off Day');
                // receiving page action
                return redirect()->back();
            }
        }else{
            abort(404);
        }
    }

    /**
     * @param $year
     * @param $month
     * @return array
     */
    public function getMonthDayArrayList($year, $month)
    {
        // find month date range
        $monthFirstDate = date('01', strtotime($year . '-' . $month . '-01'));
        $monthLastDate = date('t', strtotime($year . '-' . $month . '-01'));

        // day numeric array list
        $dayDateArrayList = array();

        // current month looping
        for ($day = $monthFirstDate; $day <= $monthLastDate; $day++) {
            $toDayDate = $year . "-" . $month . "-" . $day;
            // day details
            $dayId = date('w', strtotime($toDayDate));
            $dayName = date('l', strtotime($toDayDate));
            // input Date details
            $dayDateArrayList[$dayId][] = ['date' => $toDayDate, 'name' => $dayName];
        }
        // return
        return $dayDateArrayList;
    }

    /**
     * @param $date
     * @param $deptId
     * @param $academicYearId
     * @param $campusId
     * @param $instituteId
     * @return boolean
     */
    public function checkWeekOffDay($date, $deptId, $academicYearId, $campusId, $instituteId)
    {
        $weekOffDayProfile = $this->weekOffDay->where([
            'date' => $date,
            'dept_id' => $deptId,
            'academic_year' => $academicYearId,
            'campus_id' => $campusId,
            'institute_id' => $instituteId
        ])->first();
        // checking
        if($weekOffDayProfile){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @param $name
     * @param $remarks
     * @param $date
     * @param $academicYearId
     * @param $campusId
     * @param $instituteId
     * @return mixed|array
     */
    public function makeHoliday($name, $remarks, $date, $academicYearId, $campusId, $instituteId)
    {
        // check holiday
        if ($this->nationalHolidayController->checkHoliday($date, $date, $academicYearId, $campusId, $instituteId) == false) {
            // holiday profile creation
            $holidayProfile = $this->nationalHolidayController->createHoliday([
                'id' => 0,
                'name' => $name,
                'start_date' => $date,
                'end_date' => $date,
                'remarks' =>$remarks,
                'year' => $academicYearId,
                'campus' => $campusId,
                'institute' => $instituteId,
            ]);
            // checking
            if ($holidayProfile) {
                // create holiday details
                if ($this->nationalHolidayController->createHolidayDetails($holidayProfile->id, $date, $academicYearId, $campusId, $instituteId)) {
                    // remove the day from week off day
                    $workingDayMaker = (object) $this->makeWorkingDay(null, $date, $academicYearId, $campusId, $instituteId);
                    // checking
                    if($workingDayMaker->status=='success'){
                        // return msg
                        return ['status'=>'success', 'msg'=>'Week Off Updated as Holiday'];
                    }else{
                        // return msg
                        return [$workingDayMaker->status, 'msg'=>'Unable to remove the date form week off day list'];
                    }
                } else {
                    // return msg
                    return ['status'=>'warning', 'msg'=>'Unable to create holiday details'];
                }
            } else {
                // return msg
                return ['status'=>'warning', 'msg'=>'Unable to create holiday'];
            }
        }else{
            // return msg
            return ['status'=>'warning', 'msg'=>'Fatal Error! Conflict in holiday dates !!!'];
        }
    }

    /**
     * @param $campusId
     * @param $instituteId
     * @param $date
     * @param $deptId
     * @param $academicYearId
     * @return  mixed|boolean
     */
    public function makeWeekOffDay($campusId, $instituteId, $deptId, $date, $academicYearId)
    {
        // find week-off day already exits or not
        $oldWeekOffDayProfile = $this->checkWeekOffDay($date, $deptId, $academicYearId, $campusId, $instituteId);
        // checking week off day
        if ($oldWeekOffDayProfile == false) {
            // week off day profile
            $weekOffDayProfile = new $this->weekOffDay();
            // input day off details
            $weekOffDayProfile->date = $date;
            $weekOffDayProfile->dept_id = $deptId;
            $weekOffDayProfile->academic_year = $academicYearId;
            $weekOffDayProfile->campus_id = $campusId;
            $weekOffDayProfile->institute_id = $instituteId;
            // save week off day
            if($weekOffDayProfile->save()){
                // session data and return
                return ['status'=>'success', 'msg'=>'Day Updated as Week Off'];
            } else {
                // session data and return
                return ['status'=>'error', 'msg'=>'Unable to make the day as Week Off'];
            }
        }else{
            // session data and return
            return ['status'=>'success', 'msg'=>'already Week Off day'];
        }

    }

    /**
     * @param $deptId
     * @param $date
     * @param $academicYearId
     * @param $campusId
     * @param $instituteId
     * @return mixed|array
     */
    public function makeWorkingDay($deptId, $date, $academicYearId, $campusId, $instituteId)
    {
        // date conversion
        $myDate = date('Y-m-d', strtotime($date));
        // qry maker
        $qry = ['date' => $myDate, 'academic_year' => $academicYearId, 'campus_id' => $campusId, 'institute_id' => $instituteId];
        // department id checking
        if($deptId ==null AND !empty($deptId)) $qry['dept_id'] = $deptId;
        // find the week-off day with the current date
        $allWeekOffDays = $this->weekOffDay->where($qry)->get();

        // checking
        if ($allWeekOffDays->count()>0) {
            // weekOffDayLoopCounter
            $weekOffDayLoopCounter = 0;

            // weekOffDay Looping
            foreach ($allWeekOffDays as $weekOffDay) {
                // checking department list
                if ($weekOffDay->delete()) $weekOffDayLoopCounter += 1;
            }
            // checking
            if ($weekOffDayLoopCounter == $allWeekOffDays->count()) {
                return ['status'=>'success', 'msg'=>'Date is converted as Working day'];
            } else {
                return ['status'=>'warning', 'msg'=>'Fatal Error! Unable to make the Date as Working Day'];
            }
        } else {
            // session data
            return ['status'=>'success', 'msg'=>'Date is converted as Working day'];
        }
    }

    /**
     * @param $deptId
     * @param $academicYearId
     * @param $campusId
     * @param $instituteId
     * @return  mixed|array
     */
    public function weekOffDayList($deptId, $academicYearId, $campusId, $instituteId){
        // holiday array list
        $weekOffDayArrayList = [];
        // checking std dept
        if($deptId != null){
            // find all holidays with campus and institute
            $allWeekOffDayList = $this->weekOffDay->where([
                'dept_id' => $deptId,
                'academic_year' => $academicYearId,
                'campus_id' => $campusId,
                'institute_id' => $instituteId
            ])->orderBy('date', 'ASC')->get();
            // checking
            if($allWeekOffDayList->count()>0){
                // holiday list looping
                foreach ($allWeekOffDayList as $weekOffDay){
                    // array list insertion
                    $weekOffDayArrayList[$weekOffDay->date] = 'Week Off Day';
                }
                // return array list
                return ['status'=>'success', 'week_off_list'=>$weekOffDayArrayList];
            }else{
                return ['status'=>'failed', 'msg'=>'There is no WeekOff Day for this student class/section'];
            }
        }else{
            return ['status'=>'failed', 'msg'=>'There is no department for this student class/section'];
        }
    }

}
