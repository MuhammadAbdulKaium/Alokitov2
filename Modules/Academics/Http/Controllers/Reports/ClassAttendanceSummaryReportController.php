<?php

namespace Modules\Academics\Http\Controllers\Reports;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use JasperPHP\JasperPHP;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Academics\Entities\AttendanceManageView;
use Modules\Academics\Entities\Batch;
use Modules\Academics\Entities\ClassSubject;
use Modules\Academics\Entities\Section;
use Modules\Academics\Entities\StudentAttendance;
use Modules\Academics\Entities\StudentAttendanceDetails;
use Modules\Employee\Entities\NationalHolidayDetails;
use Modules\Employee\Entities\StudentDepartment;
use Modules\Employee\Entities\WeekOffDay;
use Modules\Student\Entities\StudentEnrollment;
use Modules\Student\Entities\StudentInformation;
use Modules\Student\Entities\StudentAttachment;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Academics\Entities\AttendanceUpload;

class ClassAttendanceSummaryReportController extends Controller
{

    private $classSubject;
    private $studentInformation;
    private $studentAttendance;
    private $studentAttendanceDetails;
    private $studentEnrollment;
    private $attendanceManageView;
    private $studentAttachment;
    private $academicHelper;
    private $attendanceUpload;
    private $holidayDetails;
    private $weekOffDay;
    private $studentDepartment;

    public function __construct(ClassSubject $classSubject, StudentInformation $studentInformation, StudentEnrollment $studentEnrollment, StudentAttendance $studentAttendance, StudentAttendanceDetails $studentAttendanceDetails, AttendanceManageView $attendanceManageView, StudentAttachment $studentAttachment, AcademicHelper $academicHelper, AttendanceUpload $attendanceUpload, StudentDepartment $studentDepartment, NationalHolidayDetails $holidayDetails, WeekOffDay $weekOffDay)
    {
        $this->classSubject             = $classSubject;
        $this->studentEnrollment        = $studentEnrollment;
        $this->studentInformation       = $studentInformation;
        $this->studentAttendance        = $studentAttendance;
        $this->studentAttendanceDetails = $studentAttendanceDetails;
        $this->attendanceManageView     = $attendanceManageView;
        $this->studentAttachment        = $studentAttachment;
        $this->academicHelper        = $academicHelper;
        $this->attendanceUpload        = $attendanceUpload;
        $this->holidayDetails  = $holidayDetails;
        $this->weekOffDay  = $weekOffDay;
        $this->studentDepartment  = $studentDepartment;
    }

    // find class section attendance report by date range
    public function classSectionAttendanceReportByDateRange(Request $request)
    {
        // request details
        $level = $request->input('academic_level');
        $batch = $request->input('batch');
        $batchName = $request->input('batch_name');
        $section = $request->input('section');
        $sectionName = $request->input('section_name');
        $fromDate =  $request->input('from_date');
        $toDate =  $request->input('to_date');
        $reportType = $request->input('doc_type'); // pdf, xlsx
        // date conversion
        $startDate =  date('Y-m-d 00:00:00', strtotime($fromDate));
        $endDate =  date('Y-m-d 23:59:59', strtotime($toDate));
        // total days between start date and end date
        $totalDays = (round((strtotime($toDate)-strtotime($fromDate))/86400)+1);

        // attendance array list
        $studentArrayList = array();
        //$attendanceArrayList = array();
        // find batch section std list
        $academicYear = $this->academicHelper->getAcademicYear();
        // institute details
        $campus = $this->academicHelper->getCampus();
        $institute = $this->academicHelper->getInstitute();
        // institute information
        $instituteInfo = $this->academicHelper->getInstituteProfile();
        // class section dept id
        $stdDeptId = $this->studentDepartment->where(['academic_level'=>$level, 'academic_batch'=>$batch, 'campus_id'=>$campus, 'institute_id'=>$institute])->first();

        // find all weekOffDays with campus and institute
        $allWeekOffDayList = $this->weekOffDay
            ->where(['dept_id' => $stdDeptId->dept_id, 'academic_year' => $academicYear, 'campus_id' => $campus, 'institute_id' => $institute])
            ->whereBetween('date', [$startDate, $endDate])->orderBy('date', 'ASC')->get(['id', 'date'])->count();

        // find all holidays with campus and institute
        $allHolidayList = $this->holidayDetails
            ->where(['academic_year' => $academicYear, 'campus_id' => $campus, 'institute_id' => $institute])
            ->whereBetween('date', [$startDate, $endDate])->orderBy('date', 'ASC')->get(['id', 'date'])->count();

        // find student and attendance list
        $attendanceList =   DB::table('student_informations as s')
            ->leftJoin('attendance_upload as a', function ($join) use($startDate, $endDate) {
                $join->on('s.id', '=', 'a.std_id')->whereBetween('a.entry_date_time', [$startDate, $endDate]);
            })->leftJoin('student_enrollments as e', 'e.std_id', '=', 's.id')
            ->select('s.id as std_id','s.first_name as first_name','s.middle_name as middle_name','s.last_name as last_name','e.gr_no as gr_no','a.entry_date_time as entry_date_time')
            ->where(['e.academic_level'=>$level, 'e.batch'=>$batch, 'e.section'=>$section, 'e.academic_year'=>$academicYear, 's.status'=>1, 's.institute'=>$institute, 's.campus'=>$campus])->orderByRaw('LENGTH(e.gr_no) asc')->orderBy('e.gr_no', 'asc')->get();

        //  attendance array list maker
        foreach ($attendanceList as $index=>$attendance){
            // checking and input student details
            if(array_key_exists($attendance->std_id, $studentArrayList)){
                // student attendance counter
                $studentArrayList[$attendance->std_id]['attendance'] += 1;
            }else{
                // input student details
                $studentArrayList[$attendance->std_id] = [
                    'roll'=>$attendance->gr_no, 'name'=>$attendance->first_name.' '.$attendance->middle_name.' '.$attendance->last_name, 'attendance'=>1
                ];
            }
        }
        // calculate total working days
        $totalWorkingDays = ($totalDays-($allWeekOffDayList+$allHolidayList));
        // attendance array list
        $attendanceArrayList = ['total_days'=>$totalDays, 'week_off_days'=>$allWeekOffDayList, 'holidays'=>$allHolidayList, 'working_days'=>$totalWorkingDays];

        // share all variables with the view
        view()->share(compact('studentArrayList', 'attendanceArrayList', 'reportType', 'instituteInfo', 'batchName', 'sectionName','fromDate', 'toDate'));
        // checking $reportType (pdf or xlsx)
        if($reportType=='pdf'){
            // generate pdf
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView('academics::manage-attendance.reports.report-class-section-attendance-summary')->setPaper('a4', 'portrait');
            return $pdf->stream();
            //return $pdf->download('upload_attendance_report.pdf');

        }elseif($reportType=='xlsx') {
            //generate excel
            Excel::create('Student Attendance Summary Report', function ($excel) {
                $excel->sheet('Student Attendance Summary Report', function ($sheet) {
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
                    $sheet->loadView('academics::manage-attendance.reports.report-class-section-attendance-summary');
                });
            })->download('xlsx');
        }
    }



    /*
     * indexReport function
     * function is to create the report with necessary input
     */
    public function indexReport(Request $request)
    {

        $class_id =  $request->input('batch');
        $section_id = $request->input('section');
        $from_date =  $request->input('from_date');
        $to_date =  $request->input('to_date');
        $doc_type =  $request->input('doc_type');
        $report_name = 'Class-Attendance-Summary-Report'; //Please Do not use any space while creating $report_name
        $ext         = $doc_type; // TODO This field value should come from user's choise like pdf/excel
        $driver      = 'json'; //This could be changed to mysql
        $data        = $this->createDataSource($class_id, $section_id, $from_date, $to_date);
        $parameters  = $this->createParameter($class_id, $section_id);
        if (isset($data)) {
            $json_file_location = $this->createJsonFile($report_name, $data);
            if (isset($json_file_location)) {
                $jasper_file_location = env('HOME_PROJECT') . "/Modules/Academics/Resources/reports/classattendancesummary.jrxml";
                $this->createReport($report_name, $json_file_location, $jasper_file_location, $ext, $driver, $parameters);
            }
        } else {
            //TODO Redirect to Report General Error Page
        }

    }
    /*
     * createDatasource is a function to create custom datasource as needed for the report
     * Reuturn format of data should be json encoded.
     */
    public function createDataSource($class_id, $section_id, $from_date, $to_date)
    {
        // array for response
        $response = array();
        $from_date = date('Y-m-d', strtotime($from_date));
        $to_date = date('Y-m-d', strtotime($to_date));
        $attendances =  $this->attendanceManageView
            ->orderBy('student_id', 'ASC')
            ->where(['class_id' => $class_id, 'section_id' => $section_id])
            ->whereBetween('attendance_date', array($from_date, $to_date))
            ->whereNull('deleted_at')->get();
        foreach ($attendances as $attendance){
            $row = array();
            $student_id = $attendance->student_id;
            $student = $this->studentInformation->findorfail($student_id);
            $row["student_name"] = $student->first_name." ".$student->last_name;
            $row["student_id"] = $student_id;
            $row["attendacnce_type"] = $attendance->attendacnce_type;
            array_push($response, $row);
        }
        return json_encode($response);

    }

    public function createParameter($class_id, $section_id)
    {
        $parameters = array();
        $class= Batch::findorfail($class_id);
        $section = Section::findorfail($section_id);
        $parameters["batch_name"] = $class->batch_name;
        $parameters["section_name"] = $section->section_name;
        return $parameters;
    }

    /*
     * createJsonFile is a function to create custom json file as needed for the report
     * Reuturn format of this function should be the file path
     * TODO in some cases the generated file should be stored in the database system
     *
     * @param $report_name
     * @param $data
     * @return string
     */

    public function createJsonFile($report_name, $data)
    {
        $file_location = public_path() . '/report/json/' . time() . '_' . $report_name . '.json';
        $file          = fopen($file_location, 'w');
        fwrite($file, $data);
        fclose($file);
        return $file_location;
    }

    /**
     * createReport function
     * takes the following required parameters to create the report
     * and make it downloadable to users
     *
     * @param $report_name
     * @param $json_file_location
     * @param $jasper_file_location
     * @param $ext
     * @param $driver
     */
    public function createReport($report_name, $json_file_location, $jasper_file_location, $ext, $driver, &$parameters)
    {
        $output_file = public_path() . '/report/' . time() . '_' . $report_name;
        $jasper      = new JasperPHP;
        $result      = $jasper->process(
            $jasper_file_location,
            $output_file,
            array($ext),
            $parameters,
            array(
                'driver'     => $driver,
                'json_query' => '""',
                'data_file'  => $json_file_location,
            )
        )->execute();
        //dd($result);
        //exit();

        //sleep(9);
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . time() . '_' . $report_name . '.' . $ext);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output_file . '.' . $ext));
        flush();
        readfile($output_file . '.' . $ext);
        unlink($output_file . '.' . $ext);
    }

    /////////////////////  student attendance report /////////////////////
    public function searchAttendanceReport($id)
    {
        return view('student::pages.student-profile.modals.attendance-report')->with('id', $id);
    }


//    // student absent report
//    public function studentAbsentReport($request)
//    {
//        // statements
//    }
//
//    // class-section absent report
//    public function classSectionAbsentReport($request)
//    {
//        // statements
//    }


}
