<?php

namespace Modules\Employee\Http\Controllers;

use Redirect;
use Session;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;
use Modules\Employee\Entities\NationalHoliday;
use Modules\Employee\Entities\NationalHolidayDetails;
use App\Http\Controllers\Helpers\AcademicHelper;

class NationalHolidayController extends Controller
{

    private $academicHelper;
    private $holidayDetails;
    private $nationalHoliday;

    // constructor
    public function __construct(AcademicHelper $academicHelper, NationalHoliday $nationalHoliday, NationalHolidayDetails $holidayDetails)
    {
        $this->academicHelper = $academicHelper;
        $this->holidayDetails = $holidayDetails;
        $this->nationalHoliday = $nationalHoliday;
    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        // all national Holidays
        $allNationalHolidays = $this->nationalHoliday->where([
            'academic_year'=>$this->academicHelper->getAcademicYear(),
            'campus_id'=>$this->academicHelper->getCampus(),
            'institute_id'=>$this->academicHelper->getInstitute(),
        ])->orderBy('start_date', 'ASC')->get();
        // return view with variable
        return view('employee::pages.holiday-management.national-holidays', compact('allNationalHolidays'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        // holiday profile
        $holidayProfile = null;
        // return view with variable
        return view('employee::pages.holiday-management.modals.holiday', compact('holidayProfile'));
    }

    public function store(Request $request)
    {
        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'holiday_id' => 'required',
            'name'       => 'required',
            'start_date' => 'required',
            'end_date'   => 'required',
            'remarks'    => 'required|max:200'
        ]);

        // validation checker
        if ($validator->passes()) {
            // request details
            $holidayId = $request->input('holiday_id');
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $academicYearId = $this->academicHelper->getAcademicYear();
            $campusId = $this->academicHelper->getCampus();
            $instituteId = $this->academicHelper->getInstitute();

            // date comparison
            if(date('Y', strtotime($startDate)) != date('Y', strtotime($endDate))  ||  (strtotime($startDate)>strtotime($endDate))){
                Session::flash('warning', 'Fatal Error! Invalid Date Formation');
                // receiving page action
                return redirect()->back();
            }
            // checking holiday exists or not
            if($this->checkHoliday($startDate, $endDate, $academicYearId, $campusId, $instituteId)==false){
                // Start transaction!
                DB::beginTransaction();
                // employee user creation
                try {
                    // holiday profile creation
                    $holidayProfile = $this->createHoliday([
                        'id'=>$holidayId,
                        'name'=>$request->input('name'),
                        'start_date'=>date('Y-m-d', strtotime($startDate)),
                        'end_date'=>date('Y-m-d', strtotime($endDate)),
                        'remarks'=>$request->input('remarks'),
                        'year'=>$academicYearId,
                        'campus'=>$campusId,
                        'institute'=>$instituteId,
                    ]);

                    // checking
                    if($holidayProfile){
                        // holiday id checking
                        if($holidayId>0){
                            $oldHolidayDetailsDeleted = $this->deleteHolidayDetails($holidayId);
                        }
                        // create holiday details
                        $holidayDetailsCreated = $this->makeHolidayDetails($startDate, $endDate, $holidayProfile->id, $academicYearId, $campusId, $instituteId);
                        // checking
                        if($holidayDetailsCreated){
                            // If we reach here, then data is valid and working.
                            // Commit the queries!
                            DB::commit();
                            // session
                            Session::flash('success', 'National Holiday Submitted');
                            // receiving page action
                            return redirect()->back();
                        }else{
                            // Rollback and then redirect  back to form with errors
                            DB::rollback();
                            // session
                            Session::flash('warning', 'Unable to submit national holiday details');
                            // receiving page action
                            return redirect()->back();
                        }
                    }else{
                        // Rollback and then redirect  back to form with errors
                        DB::rollback();
                        // session
                        Session::flash('warning', 'Unable to submit national holiday');
                        // receiving page action
                        return redirect()->back();
                    }
                } catch (ValidationException $e) {
                    // Rollback and then redirect  back to form with errors
                    DB::rollback();
                    return redirect()->back();
                } catch (\Exception $e) {
                    DB::rollback();
                    throw $e;
                }
            }else{
                Session::flash('warning', 'Fatal Error! Conflict in holiday dates!');
                // receiving page action
                return redirect()->back();
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
    public function edit($holidayId)
    {
        // find holiday profile
        $holidayProfile = $this->nationalHoliday->find($holidayId);
        // checking
        if($holidayProfile){
            // return view with variable
            return view('employee::pages.holiday-management.modals.holiday', compact('holidayProfile'));
        }else{
            abort(404);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($holidayId)
    {
        // find holiday profile
        $holidayProfile = $this->nationalHoliday->find($holidayId);
        // checking
        if($holidayProfile){
            // now delete holiday profile
            if($holidayProfile->delete()){
                if($this->deleteHolidayDetails($holidayId)){
                    Session::flash('success', 'National Holiday deleted');
                    // receiving page action
                    return redirect()->back();
                }else{
                    Session::flash('warning', 'Unable to delete Holiday Details');
                    // receiving page action
                    return redirect()->back();
                };
            }else{
                Session::flash('warning', 'Unable to delete national holiday');
                // receiving page action
                return redirect()->back();
            }
        }else{
            Session::flash('warning', 'Holiday is not available with this id');
            // receiving page action
            return redirect()->back();
        }
    }

    /**
     * @param $holidayId
     * @param $academicYearId
     * @param $campusId
     * @param $instituteId
     * @return  boolean
     */
    public function createHolidayDetails($holidayId, $date, $academicYearId, $campusId, $instituteId)
    {
        // create holiday details
        $holidayDetailProfile = new $this->holidayDetails();
        // input details
        $holidayDetailProfile->holiday_id = $holidayId;
        $holidayDetailProfile->date = $date;
        $holidayDetailProfile->academic_year = $academicYearId;
        $holidayDetailProfile->campus_id = $campusId;
        $holidayDetailProfile->institute_id = $instituteId;
        // save holiday details
        if($holidayDetailProfile->save()){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @param $startDate
     * @param $endDate
     * @param $academicYearId
     * @param $campusId
     * @param $instituteId
     * @return  boolean
     */
    public function checkHoliday($startDate, $endDate, $academicYearId, $campusId, $instituteId)
    {
        // date formation
        $startDate = date('Y-m-d', strtotime($startDate));
        $endDate = date('Y-m-d', strtotime($endDate));
        // find holiday
        $holidayDetailsList = $this->holidayDetails->where([
            'academic_year' => $academicYearId,
            'campus_id' => $campusId,
            'institute_id' => $instituteId
        ])->whereBetween('date', [$startDate, $endDate])->get();
        // checking $holidayDetailsProfile
        if($holidayDetailsList->count()>0){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @param $holidayId
     * @return  boolean
     */
    public function deleteHolidayDetails($holidayId)
    {
        $holidayList = $this->holidayDetails->where(['holiday_id' => $holidayId])->get();
        // checking
        if ($holidayList->count()>0) {
            // delete counter
            $deleteCounter = 0;
            // holiday list looping
            foreach ($holidayList as $index=>$holiday) {
                if($holiday->delete()){
                    $deleteCounter+=1;
                }
            }
            // checking
            if($deleteCounter==$holidayList->count()){
                return true;
            }else{
                return false;
            }
        }else{
            return true;
        }
    }


    public function createHoliday($holidayDetails)
    {
        // convert $holidayDetails into object
        $holidayDetails = (object)$holidayDetails;
        // checking holiday id
        if ($holidayDetails->id > 0) {
            $holidayProfile = $this->nationalHoliday->find($holidayDetails->id);
        } else {
            $holidayProfile = new $this->nationalHoliday();
        }
        // input request details
        $holidayProfile->name = $holidayDetails->name;
        $holidayProfile->start_date = date('Y-m-d', strtotime($holidayDetails->start_date));
        $holidayProfile->end_date =  date('Y-m-d', strtotime($holidayDetails->end_date));
        $holidayProfile->remarks = $holidayDetails->remarks;
        $holidayProfile->academic_year = $holidayDetails->year;
        $holidayProfile->campus_id = $holidayDetails->campus;
        $holidayProfile->institute_id = $holidayDetails->institute;
        // checking
        if($holidayProfile->save()){
            return $holidayProfile;
        }else{
            return null;
        }
    }

    /**
     * @param $startDate
     * @param $endDate
     * @param $holidayId
     * @param $academicYearId
     * @param $campusId
     * @param $instituteId
     * @return  boolean
     */
    public function makeHolidayDetails($startDate, $endDate, $holidayId, $academicYearId, $campusId, $instituteId)
    {
        // from_date details
        $fromYear = date('Y', strtotime($startDate));
        $fromMonth = date('m', strtotime($startDate));
        $fromDate = date('d', strtotime($startDate));
        // to_date details
        $toYear = date('Y', strtotime($endDate));
        $toMonth = date('m', strtotime($endDate));
        $toDate = date('d', strtotime($endDate));

        if ($fromYear == $toYear AND $fromMonth == $toMonth AND $fromDate == $toDate) {
            // for same year and same month and same date
            $toDayDate = date('Y-m-d', strtotime($startDate));
            // create holiday details and checking
            if($this->createHolidayDetails($holidayId, $toDayDate, $academicYearId, $campusId, $instituteId)){
                return true;
            }else{
                return false;
            }
        } elseif ($fromYear == $toYear AND $fromMonth == $toMonth) {

            // loop counter
            $dayCounter = 0;
            $dayLoopCounter = 0;
            // for same year and same month
            for ($day = $fromDate; $day <= $toDate; $day++) {
                // today's date
                $toDayDate = date('Y-m-' . $day, strtotime($startDate));
                // create holiday details and checking
                if($this->createHolidayDetails($holidayId, $toDayDate, $academicYearId, $campusId, $instituteId)){
                    $dayLoopCounter += 1;
                }

                // day counter
                $dayCounter += 1;
            }
            // checking
            if($dayCounter==$dayLoopCounter){
                return true;
            }else{
                return false;
            }

        } elseif ($fromYear == $toYear AND $fromMonth < $toMonth) {
            // month loop counter
            $monthCounter = 0;
            $monthLoopCounter = 0;
            // for same year and different month
            for ($month = $fromMonth; $month <= $toMonth; $month++) {
                // current month date range finding
                $monthFirstDate = date('01', strtotime($fromYear . '-' . $month . '-01'));
                $monthLastDate = date('t', strtotime($fromYear . '-' . $month . '-01'));

                if ($fromMonth == $month) {
                    $monthFirstDate = $fromDate;
                }
                if ($toMonth == $month) {
                    $monthLastDate = $toDate;
                }

                // day counter
                $dayCounter  = 0;
                $dayLoopCounter  = 0;
                // current month date looping
                for ($day = $monthFirstDate; $day <= $monthLastDate; $day++) {
                    // today's date
                    $toDayDate = $fromYear . "-" . $month . "-" . $day;
                    // create holiday details and checking
                    if($this->createHolidayDetails($holidayId, $toDayDate, $academicYearId, $campusId, $instituteId)){
                        $dayLoopCounter += 1;
                    }

                    // day counter
                    $dayCounter += 1;
                }
                // day loop counter
                if($dayLoopCounter==$dayCounter){
                    $monthLoopCounter += 1;
                }

                // month counter
                $monthCounter += 1;
            }
            // checking
            if($monthLoopCounter==$monthCounter){
                return true;
            }else{
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * @param $academicYearId
     * @param $campusId
     * @param $instituteId
     * @return  mixed|array
     */
    public function holidayList($academicYearId, $campusId, $instituteId){
        // holiday array list
        $holidayArrayList = [];
        // find all holidays with campus and institute
        $allHolidayList = $this->holidayDetails->where([
            'academic_year' => $academicYearId,
            'campus_id' => $campusId,
            'institute_id' => $instituteId
        ])->orderBy('date', 'ASC')->get();
        // checking
        if($allHolidayList->count()>0){
            // holiday list looping
            foreach ($allHolidayList as $holidayDetails){
                // holiday profile
                $holidayProfile = $holidayDetails->holiday();
                // array list insertion
                $holidayArrayList[$holidayDetails->date] = $holidayProfile->name;
            }
            // return array list
            return $holidayArrayList;
        }else{
            return null;
        }
    }
}
