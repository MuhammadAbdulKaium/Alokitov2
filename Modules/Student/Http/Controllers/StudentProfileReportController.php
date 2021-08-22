<?php

namespace Modules\Student\Http\Controllers;

use App\User;
use Barryvdh\DomPDF\PDF;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Academics\Entities\AttendanceManageView;
use Modules\Academics\Entities\ClassSubject;
use Modules\Academics\Entities\StudentAttendance;
use Modules\Academics\Entities\StudentAttendanceDetails;
use Modules\Student\Entities\StudentAttachment;
use Modules\Student\Entities\StudentEnrollment;
use Modules\Student\Entities\StudentInformation;
use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Support\Facades\App;

class StudentProfileReportController extends Controller
{

    private $classSubject;
    private $studentInformation;
    private $studentAttendance;
    private $studentAttendanceDetails;
    private $studentEnrollment;
    private $attendanceManageView;
    private $studentAttachment;
    private $academicHelper;

    public function __construct(ClassSubject $classSubject, StudentInformation $studentInformation, StudentEnrollment $studentEnrollment, StudentAttendance $studentAttendance, StudentAttendanceDetails $studentAttendanceDetails, AttendanceManageView $attendanceManageView, StudentAttachment $studentAttachment, AcademicHelper  $academicHelper) {
        $this->classSubject             = $classSubject;
        $this->studentEnrollment        = $studentEnrollment;
        $this->studentInformation       = $studentInformation;
        $this->studentAttendance        = $studentAttendance;
        $this->studentAttendanceDetails = $studentAttendanceDetails;
        $this->attendanceManageView     = $attendanceManageView;
        $this->studentAttachment        = $studentAttachment;
        $this->academicHelper        = $academicHelper;
    }


    public function index($id)
    {
        $campusId = $this->academicHelper->getCampus();
        $instituteId = $this->academicHelper->getInstitute();
        $instituteInfo = $this->academicHelper->getInstituteProfile();
        $studentProfile = $this->studentInformation->where([ 'id'=>$id, 'campus'=>$campusId, 'institute'=>$instituteId])->first();

//        // checking
//        if($studentProfile){
//            $snappy = new Pdf('/usr/local/bin/wkhtmltopdf');
//            header('Content-Type: application/pdf');
//            echo $snappy->getOutput('http://www.github.com');
//            // share all variables with the view
////            view()->share(compact('studentProfile', 'instituteInfo'));
////            $html = view('student::reports/std-profile');
////            $pdf = App::make('snappy.pdf.wrapper');
////
////            $pdf->loadHTML($html);
////
////            return $pdf->inline($id.'_student_profile.pdf');
//        }else{
//            // 404 errors redirection
//            abort(404);
//        }

//        dd($studentProfile->enroll());
        if($studentProfile){
            // share all variables with the view
            view()->share(compact('studentProfile', 'instituteInfo'));
            $html = view('student::reports/std-profile');
            $pdf = App::make('snappy.pdf.wrapper');
            $pdf->loadHTML($html);

//            dd($html);

            return $pdf->inline($id.'_student_profile.pdf');
        }else{
            // 404 errors redirection
            abort(404);
        }



//        $pdf = App::make('snappy.pdf.wrapper');
//        $pdf->loadHTML('<h1>Test</h1>');
//        return $pdf->inline('hello.pdf');


//        return SnappyPdf::loadHTML("https://www.google.com/")->setPaper('a4')->setOrientation('landscape')->setOption('margin-bottom', 0)->download('myfile.pdf');
//        $testpdf = new PDF();
//        return PDF::loadHTML("hello")->setPaper('a4', 'landscape')->setWarnings(false)->download('download.pdf');
//        $pdf = App::make('dompdf.wrapper');
//        $pdf->loadHTML('<h1>Test</h1>');
//        return $pdf->stream();

    }

    /*
     * indexReport function
     * function is to create the report with necessary input
     */
    public function indexReport($id)
    {

        $report_name = 'Student-Profile-Report'; //Please Do not use any space while creating $report_name
        $ext         = "pdf"; // TODO This field value should come from user's choise like pdf/excel
        $driver      = 'json'; //This could be changed to mysql
        $data        = $this->createDataSource($id);
        $parameters  = $this->createParameter($id);
        if (isset($data)) {
            $json_file_location = $this->createJsonFile($report_name, $data);
            if (isset($json_file_location)) {
                $jasper_file_location = env('HOME_PROJECT') . "/Modules/Student/Resources/reports/studentprofile.jrxml";
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
    public function createDataSource($id)
    {
        // array for response
        $response = array();

        // studentProfile
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
        }

        return json_encode($enrollment);

    }

    public function createParameter($id)
    {
        // array for response
        $response = array();

        // studentProfile
        $studentProfile = StudentInformation::findOrFail($id);

        // storing student profile details
        $response['name']        = $studentProfile->middle_name;
        $response['std_id']      = $studentProfile->id;
        $response['email']       = $studentProfile->email;
        $response['phone']       = $studentProfile->phone;
        $response['status']      = "null";
        $response['gender']      = $studentProfile->gender;
        $response['dob']         = $studentProfile->dob;
        $response['b_group']     = $studentProfile->blood_group;
        $response['b_place']     = $studentProfile->birth_place;
        $response['religion']    = $studentProfile->religion;
        $response['residency']   = $studentProfile->residency;
        $response['nationality'] = $studentProfile->nationality;
        $response['language']    = $studentProfile->language;

        // studnet address
        $allAddress = $studentProfile->user()->allAddress();
        // looping
        foreach ($allAddress as $address) {
            // present address
            if ($address->type == "STUDENT_PRESENT_ADDRESS") {
                // present array
                $response['pre_address'] = $address->address;
                $response['pre_city']    = $address->city()->name;
                $response['pre_state']   = $address->state()->name;
                $response['pre_country'] = $address->country()->name;
                $response['pre_zip']     = $address->zip;
                $response['pre_house']   = $address->house;
                $response['pre_phone']   = $address->phone;
            }
            if ($address->type == "STUDENT_PERMANENT_ADDRESS") {
                // permanent array
                $response['per_address'] = $address->address;
                $response['per_city']    = $address->city()->name;
                $response['per_state']   = $address->state()->name;
                $response['per_country'] = $address->country()->name;
                $response['per_zip']     = $address->zip;
                $response['per_house']   = $address->house;
                $response['per_phone']   = $address->phone;
            }
        }



        $studentAttachment = $this->studentAttachment->where(['std_id'=>$id, 'doc_type'=>'PROFILE_PHOTO'])->first();
        if($studentAttachment){
            $stdAttachment = $studentAttachment->singleContent();
            $content_path = $stdAttachment->path;
            $file_name    = $stdAttachment->file_name;
            $response['profile_image'] = base_path() . "/public/" . $content_path . $file_name;
        }else{
            $response['profile_image'] = base_path() . "/public/assets/users/images/user-default.png";
        }
        // return response
        return $response;

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
        // dd($result);
        // exit();

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
            $singeDateAttendanceArray = array();
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

        $attendances = $this->attendanceManageView->orderBy('attendance_date', 'ASC')->where(['student_id' => $studnetId, 'class_id' => $studentBatch, 'section_id' => $studentSection])->whereNull('deleted_at')->get();
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
        view()->share(compact('studentInfo', 'studentAttendanceList', 'academicSubjects'));

        $html = view('student::pages.reports.attendance')->render();
        // return $html;
        $pdf = \SnappyPDF::generateFromHtml($html, '/tmp/bill-123.pdf')
            ->setPaper('a4', 'portrait')
            ->setTimeout(3600)
            ->stream('report.pdf');
        if ($pdf) {
            return $pdf->download('attendance.pdf');
        }

        return $html;
        return $pdf->stream('testfile')->header('Content-Type', 'application/pdf');

        return view('student::pages.reports.attendance', compact('studentInfo', 'studentAttendanceList', 'academicSubjects'));

    }

}
