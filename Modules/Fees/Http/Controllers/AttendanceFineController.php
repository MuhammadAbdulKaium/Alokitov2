<?php

namespace Modules\Fees\Http\Controllers;

use Modules\Student\Entities\StudentProfileView;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Academics\Entities\AttendanceUpload;
use Modules\Student\Entities\StudentInformation;
use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Routing\Controller;
use Modules\Setting\Entities\AttendanceFine;
use Modules\Student\Entities\StudentAttendanceFine;

class AttendanceFineController extends Controller
{


    private $academicHelper;
    private $attendanceUpload;
    private $studentInformation;
    private $studentProfileView;
    private $attendanceFine;
    private $studentAttendanceFine;

    // constructor
    public function __construct(AttendanceFine $attendanceFine, StudentAttendanceFine $studentAttendanceFine, AttendanceUpload $attendanceUpload, StudentInformation $studentInformation, AcademicHelper $academicHelper, StudentProfileView $studentProfileView)
    {
        $this->attendanceUpload  = $attendanceUpload;
        $this->studentInformation  = $studentInformation;
        $this->academicHelper  = $academicHelper;
        $this->studentProfileView  = $studentProfileView;
        $this->attendanceFine  = $attendanceFine;
        $this->studentAttendanceFine  = $studentAttendanceFine;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('fees::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function fineStudentList(Request $request)
    {



        ///get institute Id and Campus Id
        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();
//        return "My name is Khan";
// request details
        $requestType = $request->input('request_type', 'view'); // view or pdf
        $reportType = $request->input('report_type'); // PRESENT, ABSENT, LATE_PRESENT or ALL
        $attendanceDate = $request->input('attendance_date');
        $level = $request->input('level_id');
        $batch = $request->input('batch_id');
        $section = $request->input('section_id');
        // std list
        $stdList = $this->stdList($level, $batch, $section);
        // attendance list
        $selectedDateAttendanceList = $this->attendanceList($attendanceDate, $level, $batch, $section);

        //absent fine amount
        $absentAmount=$this->attendanceFine->where('ins_id',$instituteId)->where('campus_id',$campus_id)->where('setting_type','ABSENT')->first();

        // attendanceArrayList
        $attendanceArrayList = array();
        // make present list in the array list
        $attendanceArrayList['attendance_list']['all_list'] = null;
        $attendanceArrayList['attendance_list']['present_list'] = null;
        $attendanceArrayList['attendance_list']['late_present_list'] = null;
        $attendanceArrayList['attendance_list']['absent_list'] = null;

        // std and attendance list empty checking
        if($stdList->count()>0 AND !empty($selectedDateAttendanceList)) {
            // std list looping
            foreach ($stdList as $stdProfile) {
                // std enrollment details
                $stdProfileDetails = $this->stdEnrollmentDetails($stdProfile);


                // std attendance checking
                if (array_key_exists($stdProfile->std_id, $selectedDateAttendanceList) == true) {
                    // stdAttendanceDetails
                    $stdAttendanceDetails = $selectedDateAttendanceList[$stdProfile->std_id];
                    $entryDateTime = date('H:i:s',strtotime($stdAttendanceDetails['entry_date_time']));
                    $outDateTime = date('H:i:s',strtotime($stdAttendanceDetails['out_date_time']));
                    $attendanceArrayList['attendance_list']['all_list'][$stdProfile->std_id]= $this->check_attendance_type($entryDateTime, $outDateTime, $stdProfileDetails,$attendanceDate);

                } else {
                    $attendanceArrayList['attendance_list']['all_list'][$stdProfile->std_id] = [
                        'att_type' => 'ABSENT',
                        'att_date' => $attendanceDate,
                        'entry_date_time' => 'N/A',
                        'std_profile' => $stdProfileDetails,
                        'fine' =>$absentAmount->amount,
                    ];
                }
            }
        }
//        return $attendanceArrayList['attendance_list'];
//        $attendanceFineSetting=$this->attendanceFine->all();
//        foreach ($attendanceFineSetting as $setting) {
//
//        }

        $reportType="ALL";
        // return view with variable
        return view('fees::pages.modal.attendance.std_attend_list',compact('reportType','stdList','attendanceArrayList'));
    }



    // student attendance generate method
    public function  fineGenerate(Request $request) {
//        return $request->all();

           $attendanceFineList= $request->input('attendanceFine');
        // get campus
        $campus=$this->academicHelper->getCampus();
        $institute=$this->academicHelper->getInstitute();
        $academic_year=$this->academicHelper->getAcademicYear();

        $attendanceFineCount=$request->input('attendanceFine');
        $message="";

        foreach($attendanceFineList as $key=>$attendanceFine){
            $date=date('Y-m-d', strtotime($attendanceFineList[$key]['date']));
            $std_id=$attendanceFineList[$key]['std_id'];
            // check old valu database
            $studentFineOld=$this->studentAttendanceFine->where('std_id',$std_id)->where('date',$date)->first();

            if(empty($studentFineOld)) {
                // check fine amount
                if ($attendanceFineList[$key]['amount'] > 0) {
                    $studentAttendanceFine = new $this->studentAttendanceFine;
                    $studentAttendanceFine->academic_year = $academic_year;
                    $studentAttendanceFine->ins_id = $institute;
                    $studentAttendanceFine->campus_id = $campus;
                    $studentAttendanceFine->std_id = $attendanceFineList[$key]['std_id'];
                    $studentAttendanceFine->date = date('Y-m-d', strtotime($attendanceFineList[$key]['date']));
                    $studentAttendanceFine->fine_amount = $attendanceFineList[$key]['amount'];
                    $studentAttendanceFine->save();
                }

                $message='success';

            } else {
                $message= 'exists';
            }
        }

        return $message;

    }



    // find std list
    public function stdList($level, $batch, $section)
    {
        // qry
        $qry = [
            'academic_year'=>$this->academicHelper->getAcademicYear(),
            'academic_level'=>$level,
            'campus'=>$this->academicHelper->getCampus(),
            'institute'=>$this->academicHelper->getInstitute()
        ];

        if($batch) $qry['batch']=$batch;
        if($section) $qry['section']=$section;
        // find std list
         return $stdList = $this->studentProfileView->where($qry)->get();
    }

    // find attendance list
    public function attendanceList($attendanceDate, $level, $batch, $section)
    {
        $fromDateTime = date('Y-m-d 00:00:00', strtotime($attendanceDate));
        $toDateTime = date('Y-m-d 23:59:59', strtotime($attendanceDate));

        // qry
        $qry = [
            'academic_year'=>$this->academicHelper->getAcademicYear(),
            'campus'=>$this->academicHelper->getCampus(),
            'institute'=>$this->academicHelper->getInstitute(),
        ];
        // checking
        if($level) $qry['level']=$level;
        if($batch) $qry['batch']=$batch;
        if($section) $qry['section']=$section;
        // find attendance list
        $attendanceList = $this->attendanceUpload->where($qry)->whereBetween('entry_date_time', array($fromDateTime, $toDateTime))->get();
        // checking
        if($attendanceList->count()>0){
            // attendance array list
            $attendanceArrayList = array();
            // looping
            foreach ($attendanceList as $attendance){
                // store attendance to the array list
                $attendanceArrayList[$attendance->std_id] = [
                    'std_id'=> $attendance->std_id,
                    'std_gr_no'=> $attendance->std_gr_no,
                    'card_no'=> $attendance->card_no,
                    'entry_date_time'=> $attendance->entry_date_time
                ];
            }
            // return attendance array list
            return $attendanceArrayList;
        }else{
            // return false
            return null;
        }
    }

    /**
     * @param $stdProfile
     * @return object
     */
    public function stdEnrollmentDetails($stdProfile)
    {
        // Std Enrollment details
        $stdEnrollment = $stdProfile->enroll();
        $level = $stdEnrollment->level();
        $batch = $stdEnrollment->batch();
        $section = $stdEnrollment->section();
        $year = $stdEnrollment->academicsYear();

        // std profile details
        $stdProfileDetails = (object)[
            'id' => $stdProfile->std_id,
            'gr_no' => $stdEnrollment->gr_no,
            'name' => $stdProfile->first_name . " " . $stdProfile->middle_name . " " . $stdProfile->last_name,
            'enroll' =>$level->level_name." - ".$batch->batch_name."(".$section->section_name.") ",
            'year' => $year->id,
            'year_name' => $year->year_name,
            'level' => $level->id,
            'level_name' => $level->level_name,
            'batch' => $batch->id,
            'batch_name' => $batch->batch_name,
            'section' => $section->id,
            'section_name' => $section->section_name,
        ];
        // return stdProfileDetails
        return $stdProfileDetails;
    }


    public function check_attendance_type($entryDateTime, $outDateTime, $stdProfileDetails, $attendanceDate)
    {
        $campusId = $this->academicHelper->getCampus();
        $instituteId = $this->academicHelper->getInstitute();
        // array define
        $attendanceFineArray=array();
        // attendance Setting
        $attendanceSetting = $this->attendanceFine->where('ins_id',$instituteId)->where('campus_id',$campusId)->orderBy('sorting_order', 'asc')->get();
        // $attendanceSetting = $this->attendanceFine->where(['ins_id'=>$instituteId, 'campus_id'=>$campusId])->orderBy('sorting_order', 'asc')->get();

        // attendance setting looping
        foreach ($attendanceSetting as $setting){
            if($entryDateTime>=$setting->form_entry_time && $entryDateTime<=$setting->to_entry_time) {
                $attendanceFineArray= [
                    'att_type' =>$setting->setting_type,
                    'att_date' => $attendanceDate,
                    'entry_date_time' => $entryDateTime,
                    'out_date_time' => $outDateTime,
                    'std_profile' => $stdProfileDetails,
                    'fine' => $setting->amount
                ];
                break;
            }elseif($entryDateTime<=$setting->form_entry_time) {
                $attendanceFineArray= [
                    'att_type' =>$setting->setting_type,
                    'att_date' => $attendanceDate,
                    'entry_date_time' => $entryDateTime,
                    'out_date_time' => $outDateTime,
                    'std_profile' => $stdProfileDetails,
                    'fine' => $setting->amount
                ];
                break;
            } else {
                $attendanceFineArray= [
                    'att_type' => 'ABSENT',
                    'att_date' => $attendanceDate,
                    'entry_date_time' => 'N/A',
                    'out_date_time' => 'N/A',
                    'std_profile' => $stdProfileDetails,
                    'fine' =>$setting->amount,
                ];
            }
        }
        // return array list
        return $attendanceFineArray;
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('fees::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('fees::edit');
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
