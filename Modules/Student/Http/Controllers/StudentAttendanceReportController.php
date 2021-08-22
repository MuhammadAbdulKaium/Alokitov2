<?php

namespace Modules\Student\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use JasperPHP\JasperPHP;
use Modules\Academics\Entities\AttendanceManageView;
use Modules\Academics\Entities\ClassSubject;
use Modules\Academics\Entities\StudentAttendance;
use Modules\Academics\Entities\StudentAttendanceDetails;
use Modules\Student\Entities\StudentEnrollment;
use Modules\Student\Entities\StudentInformation;
use Modules\Student\Entities\StudentAttachment;
use App;
use PDF;
use SnappyPDF;
use Illuminate\Support\Facades\File;

class StudentAttendanceReportController extends Controller
{

    private $classSubject;
    private $studentInformation;
    private $studentAttendance;
    private $studentAttendanceDetails;
    private $studentEnrollment;
    private $attendanceManageView;
    private $studentAttachment;

    public function __construct(ClassSubject $classSubject, StudentInformation $studentInformation, StudentEnrollment $studentEnrollment, StudentAttendance $studentAttendance, StudentAttendanceDetails $studentAttendanceDetails, AttendanceManageView $attendanceManageView, StudentAttachment $studentAttachment)
    {
        $this->classSubject             = $classSubject;
        $this->studentEnrollment        = $studentEnrollment;
        $this->studentInformation       = $studentInformation;
        $this->studentAttendance        = $studentAttendance;
        $this->studentAttendanceDetails = $studentAttendanceDetails;
        $this->attendanceManageView     = $attendanceManageView;
        $this->studentAttachment        = $studentAttachment;
    }

    /*
     * indexReport function
     * function is to create the report with necessary input
     */
    public function indexReport(Request $request)
    {
        $report_name = 'Student-Attendance-Detail-Report'; //Please Do not use any space while creating $report_name
        $ext         = "pdf"; // TODO This field value should come from user's choise like pdf/excel
        $driver      = 'json'; //This could be changed to mysql
        $data        = $this->createDataSource($request);
        $parameters  = $this->createParameter($request);
        if (isset($data)) {
            $json_file_location = $this->createJsonFile($report_name, $data);
            if (isset($json_file_location)) {
                $jasper_file_location = env('HOME_PROJECT') . "/Modules/Student/Resources/reports/attendance.jrxml";
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
    public function createDataSource(Request $request)
    {
        // array for response
        $response = array();

        /*// studentProfile
        $studentProfile = StudentInformation::findOrFail($id);

        $enrollment = array();
        foreach ($studentProfile->allEnrolls() as $enroll) {

            $response['gr_id']    = $enroll->gr_id;
            $response['a_year']   = $enroll->academicsYear()->year_name;
            $response['course']   = $enroll->level()->level_name;
            $response['batch']    = $enroll->batch()->batch_name;
            $response['section']  = $enroll->section()->section_name;
            $response['b_status'] = $enroll->batch_status;
            $response['e_status'] = $enroll->enroll_status;

            array_push($enrollment, $response);
        }*/

        $studnetId = $request->input('std_id');
        $fromDate  = $request->input('from_date');
        $toDate    = $request->input('to_date');
        // studentProfile
        $studentInfo = $this->studentInformation->where('id', $studnetId)->first();
        // enrollment Profile
        $studentEnrollment = $studentInfo->singleEnroll();
        // academic details
        $studentYear    = $studentEnrollment->academic_year;
        $studentLevel   = $studentEnrollment->academic_level;
        $studentBatch   = $studentEnrollment->batch;
        $studentSection = $studentEnrollment->section;

        // studentAttendanceList
        $studentAttendanceList = array();
        $attendances =  $this->attendanceManageView->orderBy('attendance_date', 'ASC')->where(['student_id' => $studnetId, 'class_id' => $studentBatch, 'section_id' => $studentSection])->whereNull('deleted_at')->get();
        foreach ($attendances as $attendance) {
            $row = array();
            $row['subject_name']        =  $attendance->classSubject()->subject()->subject_name;
            $row['subject_id']          =  $attendance->subject_id;
            $row['attendance_date']     =  $attendance->attendance_date;
            $row['attendacnce_type']    =  $attendance->attendacnce_type;
           array_push($studentAttendanceList, $row);
        }

        return json_encode($studentAttendanceList);



    }

    public function createParameter($request)
    {
        $parameters = array();
        $studnetId = $request->input('std_id');
        $studentInfo = $this->studentInformation->where('id', $studnetId)->first();
        $studentAttachment = $this->studentAttachment
            ->where('std_id', $studnetId)
            ->where('doc_type', 'PROFILE_PHOTO')->first();
        $content_path = $studentAttachment->singleContent()->path;
        $file_name = $studentAttachment->singleContent()->file_name;
        $path =base_path()."/public/".$content_path.$file_name;
        /*$type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
       */
       if($path){
           //$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
           $parameters['profile_image'] = $path;
       }

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

    public function attendanceReport(Request $request)
    {

        $studnetId = $request->input('std_id');
        $fromDate  = $request->input('from_date');
        $toDate    = $request->input('to_date');
        // studentProfile
        $studentInfo = $this->studentInformation->where('id', 1)->first();
        // enrollment Profile
        $studentEnrollment = $studentInfo->singleEnroll();
        // academic details
        $studentYear    = $studentEnrollment->academic_year;
        $studentLevel   = $studentEnrollment->academic_level;
        $studentBatch   = $studentEnrollment->batch;
        $studentSection = $studentEnrollment->section;
        // all subject list
        $studentAllSubjects = $this->classSubject->orderBy('sorting_order', 'ASC')->where(array('class_id' => $studentBatch, 'section_id' => $studentSection))->get(['subject_id']);
        // subject array list
        $academicSubjects = array();
        // looping
        foreach ($studentAllSubjects as $singleSubject) {
            // get subject details
            $details = $singleSubject->subject();
            // adding to the array list
            $academicSubjects[] = array('id' => $details->id, 'name' => $details->subject_name, 'code' => $details->subject_code);
        }
        // [{"id":3,"name":"Physics 1st Paper","code":"P01"},{"id":4,"name":"Math","code":"M01"},{"id":1,"name":"Bangla 1st Paper","code":"B01"},{"id":2,"name":"English 1st paper","code":"E01"}]

        // studentAttendanceList
        $studentAttendanceList = array();
        // student all unique attendance date
        $attendanceDates = $this->studentAttendance->orderBy('attendance_date', 'ASC')->where('student_id', $studnetId)->distinct('attendance_date')->get(['attendance_date']);
        // now get all attendance on a specific date
        foreach ($attendanceDates as $singleDate) {
            // single date all subject attendance
            $allSubjectAttendance = $this->attendanceManageView->orderBy('sorting_order', 'ASC')->where(['student_id' => $studnetId, 'class_id' => $studentBatch, 'section_id' => $studentSection, 'attendance_date' => $singleDate->attendance_date])->whereNull('deleted_at')->get();
            // single date attendance array
            $singeDateAttendanceArray         = array();
            // date
            $singeDateAttendanceArray['date'] = $singleDate->attendance_date;
            // attendance
            foreach ($allSubjectAttendance as $singleAttendance) {
                $subjectName = $singleAttendance->classSubject()->subject()->subject_name;
                $subjectId   = $singleAttendance->classSubject()->subject()->id;
                // attendance
                $singeDateAttendanceArray['attendance'][$subjectId] = $singleAttendance->attendacnce_type;
            }
            $studentAttendanceList[] = $singeDateAttendanceArray;
        }
        $attendances =  $this->attendanceManageView->orderBy('attendance_date', 'ASC')->where(['student_id' => $studnetId, 'class_id' => $studentBatch, 'section_id' => $studentSection])->whereNull('deleted_at')->get();
        return $attendances;

        // $pdf = App::make('dompdf.wrapper');
        // $pdf->loadHTML('<h1>Test</h1>');
        // return $pdf->stream('testfile')
        //        ->header('Content-Type','application/pdf');
        /*$data = array(
            'studentInfo' => $studentInfo,
            'studentAttendanceList' => $studentAttendanceList,
            'academicSubjects' => $academicSubjects
        );

        //amirul
        /*view()->share(compact('studentInfo','studentAttendanceList', 'academicSubjects'));
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('student::pages.reports.attendance')->setPaper('a4', 'portrait');
        return view('student::pages.reports.attendance');*/
        // return $pdf->stream();

        //snappy
        view()->share(compact('studentInfo','studentAttendanceList', 'academicSubjects'));
        $html = view('student::pages.reports.attendance');
        return $html;

        $pdf = \SnappyPDF::generateFromHtml($html,'/tmp/bill-123.pdf')
            ->setPaper('a4', 'portrait')
            ->setTimeout(3600)
            ->stream('report.pdf');
        if($pdf)
            return $pdf->download('attendance.pdf');
        return $html;
        return $pdf->stream('testfile')->header('Content-Type','application/pdf');


        return view('student::pages.reports.attendance', compact('studentInfo','studentAttendanceList', 'academicSubjects'));

    }

}
