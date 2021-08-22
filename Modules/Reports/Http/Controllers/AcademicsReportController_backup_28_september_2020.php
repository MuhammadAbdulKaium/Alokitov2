<?php

namespace Modules\Reports\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Academics\Entities\AcademicsLevel;
use Modules\Academics\Http\Controllers\Reports\GenderReportController;
use Modules\Student\Http\Controllers\reports\StudentAttendanceReportController;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Communication\Entities\Event;
use Modules\Student\Entities\StudentProfileView;
use Modules\Student\Entities\StudentInformation;
use Modules\Student\Entities\StudentEnrollment;
use Modules\Student\Entities\StdEnrollHistory;
use Illuminate\Support\Facades\DB;

class AcademicsReportController extends Controller
{

    private $genderReportController;
    private $academicsLevel;
    private $studentAttendanceReportController;
    private $academicHelper;
    private $event;
    private $carbon;
    private $studentProfileView;
    private $studentInformation;
    private $studentEnrollment;
    private $enrollHistory;

    public function __construct(GenderReportController $genderReportController, AcademicsLevel $academicsLevel, StudentAttendanceReportController $studentAttendanceReportController, AcademicHelper $academicHelper, Event $event, Carbon $carbon, StudentProfileView $studentProfileView, StudentInformation $studentInformation, StudentEnrollment $studentEnrollment, StdEnrollHistory $enrollHistory)
    {
        $this->genderReportController            = $genderReportController;
        $this->academicsLevel                    = $academicsLevel;
        $this->studentAttendanceReportController = $studentAttendanceReportController;
        $this->academicHelper                    = $academicHelper;
        $this->event                             = $event;
        $this->carbon                            = $carbon;
        $this->studentProfileView                = $studentProfileView;
        $this->studentInformation                = $studentInformation;
        $this->studentEnrollment                = $studentEnrollment;
        $this->enrollHistory                = $enrollHistory;
    }

    ////////////////////////////////////////  Student Absent Report  /////////////////////////////// /////////

    public function studentAbsentDays()
    {
        // academics level
        $allAcademicsLevel = $this->academicHelper->getAllAcademicLevel();
        // return veiw with the variable
        return view('academics::manage-attendance.modals.report-student-absent-days', compact('allAcademicsLevel'));
    }

    public function studentAbsentDaysSummary(Request $request)
    {
        // making request data source
        $requestDataSource = (object) [
            'level'       => $request->input('academic_level'),
            'batch'       => $request->input('batch'),
            'section'     => $request->input('section'),
            'fromDate'    => date('Y-m-d', strtotime($request->input('summary_from_datepicker'))),
            'toDate'      => date('Y-m-d', strtotime($request->input('summary_to_datepicker'))),
            'docType'     => $request->input('summary_doc_type'),
            'instituteId' => $this->getInstituteId(),
            'campusId'    => $this->getInstituteCampusId(),
        ];
        // load report
        return $this->studentAttendanceReportController->studentAbsentDaysSummary($requestDataSource);
    }

    public function studentAbsentDaysDetails(Request $request)
    {
        // making request data source
        $requestDataSource = (object) [
            'stdId'       => $request->input('details_std_name'),
            'fromDate'    => date('Y-m-d', strtotime($request->input('details_from_datepicker'))),
            'toDate'      => date('Y-m-d', strtotime($request->input('details_to_datepicker'))),
            'docType'     => $request->input('details_doc_type'),
            'instituteId' => $this->getInstituteId(),
            'campusId'    => $this->getInstituteCampusId(),
        ];
        // load report
        return $this->studentAttendanceReportController->studentAbsentDaysDetails($requestDataSource);
    }

    ////////////////////////////////////////  Student Gender Report  /////////////////////////////// /////////
    //  get all class gender report
    public function allStudentReport($type, $downloadToken)
    {
        // report details variable
        $reportDetails = (object) [
            'class'         => 0,
            'section'       => 0,
            'doc_type'          => 'pdf',
            'report_type'          => $type,
            'downloadToken' => $downloadToken,
        ];
        // load report
        $this->genderReportController->indexReport($reportDetails);
    }

    // get batch section repeater and transfer
    public function getBatchSectionRepeaterTransfer()
    {
        // academics level
        $allAcademicYears = $this->academicHelper->getAllAcademicYears();
        // return view with the variable
        return view('reports::pages.modals.academic-report', compact('allAcademicYears'))->with('page', 'transfer');
    }

    // get batch section repeater and transfer
    public function getBatchSectionDropoutPromotion()
    {
        // academics level
        $allAcademicYears = $this->academicHelper->getAllAcademicYears();
        // return view with the variable
        return view('reports::pages.modals.academic-report', compact('allAcademicYears'))->with('page', 'promotion');
    }

    // get batch section repeater and transfer
    public function manageBatchSectionRepeaterDropOutPromotionTransfer(Request $request)
    {
        // request details
        $year = $request->input('academic_year');
        $level = $request->input('academic_level');
        $batch = $request->input('batch');
        $section = $request->input('section');
        $pageType = $request->input('page');
        $requestType = $request->input('request_type', 'view');
        // checking request type
        if($requestType=='view'){
            $campusId = $this->academicHelper->getCampus();
            $instituteId = $this->academicHelper->getInstitute();
        }else{
            $campusId = $request->input('campus_id');
            $instituteId = $request->input('institute_id');
        }

        // response data
        $responseData = array('std_total'=>0,'std_male'=>0,'std_female'=>0,'upobritti_total'=>0,'upobritti_male'=>0,'upobritti_female'=>0,'scholarship_total'=>0,'scholarship_male'=>0,'scholarship_female'=>0,'repeater_total'=>0,'repeater_male'=>0,'repeater_female'=>0,'transfer_in'=>0,'transfer_out'=>0,
            'promotion_total'=>0,
            'promotion_male'=>0,
            'promotion_female'=>0,
            'dropout_total'=>0,
            'dropout_male'=>0,
            'dropout_female'=>0
        );

        // student list
        $studentList = $this->studentProfileView->where([
            'academic_year'=>$year, 'academic_level'=>$level, 'batch'=>$batch, 'campus'=>$campusId, 'institute'=>$instituteId,
        ])->get();

        // student enrollment history
        $enrollRepeatedHistory = $this->enrollHistory->where(['academic_year'=>$year, 'academic_level'=>$level, 'batch'=>$batch, 'batch_status'=>'REPEATED'])->get();

        // student enrollment history
        $enrollLevelUpHistory = $this->enrollHistory->where(['academic_year'=>$year, 'academic_level'=>$level, 'batch'=>$batch, 'batch_status'=>'LEVEL_UP'])->get();

        // student enrollment history
        $enrollDropOutHistory = $this->enrollHistory->where(['academic_year'=>$year, 'academic_level'=>$level, 'batch'=>$batch, 'batch_status'=>'DROPOUT'])->get();


        // student looping
        foreach ($studentList as $student){
            // student gender
            $gender = $student->gender;
            // checking gender
            if($gender=='Male'){
                // checking student waiver
                if($stdWaiver = $student->student_waiver()){
                    // checking std waiver type
                    if($stdWaiver->waiver_type==2){
                        // male upobritti
                        $responseData['upobritti_male'] +=1;
                        // total upobritti count
                        $responseData['upobritti_total'] +=1;

                    }elseif($stdWaiver->waiver_type==3){
                        // male Scholarship
                        $responseData['scholarship_male'] +=1;
                        // total Scholarship count
                        $responseData['scholarship_total'] +=1;
                    }
                }
                // male std count
                $responseData['std_male'] +=1;

            }elseif($gender=='Female'){
                // checking student waiver
                if($stdWaiver = $student->student_waiver()){
                    // checking std waiver type
                    if($stdWaiver->waiver_type==2){
                        // male upobritti
                        $responseData['upobritti_female'] +=1;
                        // total upobritti count
                        $responseData['upobritti_total'] +=1;

                    }elseif($stdWaiver->waiver_type==3){
                        // male Scholarship
                        $responseData['scholarship_female'] +=1;
                        // total Scholarship count
                        $responseData['scholarship_total'] +=1;
                    }
                }
                // male std count
                $responseData['std_female'] +=1;

            }else{
                //
            }
            // total std count
            $responseData['std_total'] +=1;
        }

        // repeater student counting
        foreach ($enrollRepeatedHistory as $history){
            // enroll details
            $enroll = $history->enroll();
            // student profile
            $student = $enroll->student();
            // checking gender
            if($student->gender=='Male'){
                // repeater_male count
                $responseData['repeater_male'] +=1;
            }else{
                // repeater_female count
                $responseData['repeater_female'] +=1;
            }

            // repeater_total count
            $responseData['repeater_total'] +=1;
        }


        // level_up student counting
        foreach ($enrollLevelUpHistory as $history){
            // enroll details
            $enroll = $history->enroll();
            // student profile
            $student = $enroll->student();
            // checking gender
            if($student->gender=='Male'){
                // promotion_male count
                $responseData['promotion_male'] +=1;
            }else{
                // promotion_female count
                $responseData['promotion_female'] +=1;
            }

            // promotion_female count
            $responseData['promotion_total'] +=1;
        }

        // dropout student counting
        foreach ($enrollDropOutHistory as $history){
            // enroll details
            $enroll = $history->enroll();
            // student profile
            $student = $enroll->student();
            // checking gender
            if($student->gender=='Male'){
                // dropout_male count
                $responseData['dropout_male'] +=1;
            }else{
                // dropout_female count
                $responseData['dropout_female'] +=1;
            }

            // dropout_total count
            $responseData['dropout_total'] +=1;
        }

        // checking request type
        if($requestType=='view'){
            // checking page type
            if($pageType=='transfer'){
                // return view with the variable
                return view('reports::pages.modals.batch-section-repeater-and-transfer', compact('responseData'));
            }else{
                // return view with the variable
                return view('reports::pages.modals.batch-section-dropout-and-promotion', compact('responseData'));
            }
        }else{
            return $responseData;
        }

    }

    // class subject student report
    public function classSubjectStudentList(){
        // academics level
        $allAcademicsLevel=$this->academicHelper->getAllAcademicLevel();
        // return view with the variable
        return view('academics::manage-academics.modals.report-class-section-subject-student', compact('allAcademicsLevel'));
    }

    // downlaod setudent class section report card
    public function studentReportClassSectionModal(){
        // academics level
        $allAcademicsLevel=$this->academicHelper->getAllAcademicLevel();
        // return view with the variable
        return view('academics::manage-academics.modals.report-class-section-student', compact('allAcademicsLevel'));

    }

    // class subject report modal here
    public function studentReportClassSubjectList(){
        // academics level
        $allAcademicsLevel=$this->academicHelper->getAllAcademicLevel();
        // return view with the variable
        return view('academics::manage-academics.modals.report-class-subject-student', compact('allAcademicsLevel'));

    }


    // get class section selection gender modal
    public function classSectionStudentReportGet($type)
    {
        $allAcademicsLevel=$this->academicHelper->getAllAcademicLevel();
        // return view with the variable
        return view('academics::manage-academics.modals.report-class-section-gender', compact('allAcademicsLevel'))->with('type', $type);
    }

    // get class section gender report
    public function classSectionStudentReportPost(Request $request, $myType)
    {
        // request details
        $levelId = $request->input('academic_level');
        $batchId = $request->input('batch');
        $sectionId = $request->input('section');
        $type = $request->input('type');
        $docType = $request->input('doc_type');
        // academic year details
        $campus = $this->academicHelper->getCampus();
        $institute = $this->academicHelper->getInstitute();
        $academicYear = $this->academicHelper->getAcademicYear();
        // class profile
        $classProfile = $this->academicHelper->findBatch($batchId);
        $sectionProfile = $this->academicHelper->FindSection($sectionId);

        // report details variable
        $reportDetails = (object) [
            'class'         => $request->input('batch'),
            'class_name'         => $classProfile->batch_name,
            'section'       => $request->input('section'),
            'section_name'       => $sectionProfile->section_name,
            'doc_type'          => $request->input('doc_type'),
            'report_type'          => $type,
            'downloadToken' => $request->input('downloadToken'),
        ];

        $studentList = $this->studentProfileView->where([
            'batch'=>$batchId, 'section'=>$sectionId, 'campus'=>$campus, 'institute'=>$institute
        ])->orderByRaw('LENGTH(gr_no) asc')->orderBy('gr_no', 'asc')->get();

        // institute profile
        $instituteInfo = $this->academicHelper->getInstituteProfile();

        // share all variables with the view
        view()->share(compact('studentList', 'instituteInfo', 'reportDetails'));
        // generate pdf
        $pdf = App::make('dompdf.wrapper');

        // report_type
        if ($type == "gender") { // gender report
            // load gender pdf
            $pdf->loadView('reports::pages.report.std-gender-report')->setPaper('a4', 'portrait');
            // return $pdf->stream();
            //return $pdf->download('class section gender report.pdf');


        } elseif ($type == "birthday") { // birthday report
            // load birthday pdf
            $pdf->loadView('reports::pages.report.std-birthday-report')->setPaper('a4', 'portrait');
            // return $pdf->stream();
            //return $pdf->download('class section birthday report.pdf');


        } elseif ($type =="religion") {
            // load birthday pdf
            $pdf->loadView('reports::pages.report.std-religion-report')->setPaper('a4', 'portrait');
            // return $pdf->stream();
            //return $pdf->download('class section birthday report.pdf');


        } else { // contact report
            // load contact pdf
            $pdf->loadView('reports::pages.report.std-contact-report')->setPaper('a4', 'landscape');
            // return $pdf->stream();
           // return $pdf->download('class section gender report.pdf');

        }
        // stream pdf
        return $pdf->stream();
    }


    //////////////////////////////////// Enrollment Report Section Starts Here //////////////////////////////////////

    public function getEnrollmentHistory(Request $request)
    {

//        return $request->all();

        // input details
        $academicYear = $request->input('academic_year', null);
        $academicLevel = $request->input('academic_level');
        $batch = $request->input('batch', null);
        $section = $request->input('section');
        $requestType = $request->input('request_type', null);
        $enrollStatus = $request->input('enroll_type', null);

        // qry maker
        $qry = ['academic_year'=>$academicYear, 'batch_status'=>$enrollStatus];
        // checking inputs
        if($academicLevel != null) $qry['academic_level'] = $academicLevel;
        if($batch  != null) $qry['batch'] = $batch;
        if($section  != null) $qry['section'] = $section;
        // enroll history
        $enrollHistory = $this->enrollHistory->where($qry)->get();
        // checking request type
        if($requestType=='view'){
            // return view with variables
            return view('reports::pages.modals.enrollment', compact('enrollHistory'));

        }elseif($requestType=='download'){
            // institute profile
            $instituteInfo = $this->academicHelper->getInstituteProfile();
            // share all variables with the view
            view()->share(compact('enrollHistory', 'instituteInfo'));
            // generate pdf
            $pdf = App::make('dompdf.wrapper');
            // load gender pdf
            $pdf->loadView('reports::pages.report.enroll-history')->setPaper('a4', 'portrait');
            return $pdf->stream();
            //return $pdf->download('class section gender report.pdf');

        }else{
            return abort(404);
        }
    }
    //////////////////////////////////// Enrollment Report Section Ends Here ////////////////////////////////////////



    //////////////////////////////////  Event Report Section //////////////////////////////////////

    // get event downloading window
    public function getEventReport()
    {
        // return view
        return view('communication::pages.event.modals.event-report');
    }

    // download Event report
    public function downloadEventReport(Request $request)
    {
        $startDate = $request->input('from_date');
        $endDate = $request->input('to_date');
        // request type
        $docType = $request->input('doc_type', 'pdf');
        // date formatting using input details
        $fromDate = date('Y-m-d 00:00:00', strtotime($startDate));
        $toDate = date('Y-m-d 23:59:59', strtotime($endDate));
        // event list
        $allEventList = $this->event->where('start_date_time', '>=', $fromDate)->where('end_date_time', '<=', $toDate)->where([
            'campus'=>$this->academicHelper->getCampus(),
            'institute'=>$this->academicHelper->getInstitute()
        ])->orderBy('created_at', 'DESC')->get();
        // institute profile
        $instituteInfo = $this->academicHelper->getInstituteProfile();
        // share all variables with the view
        view()->share(compact('allEventList', 'instituteInfo', 'startDate', 'endDate'));

        // generate pdf
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('communication::pages.event.reports.report-event')->setPaper('a4', 'portrait');
        // return $pdf->stream();
        return $pdf->download('event_report.pdf');

//        // checking doc_type
//        if ($docType == 'pdf') {
//            // generate pdf
//            $pdf = App::make('dompdf.wrapper');
//            $pdf->loadView('communication::pages.event.reports.report-event')->setPaper('a4', 'portrait');
//            // return $pdf->stream();
//            return $pdf->download('event_report.pdf');
//        } else {
//            //generate excel
//            Excel::create('event_report', function ($excel) {
//                $excel->sheet('New sheet', function ($sheet) {
//                    $sheet->loadView('communication::pages.event.reports.report-event');
//                });
//            })->download('xlxs');
//        }

    }


    /////////////  get institute information from session    /////////////
    public function getAcademicYearId()
    {
        return $this->academicHelper->getAcademicYear();
    }

    public function getInstituteId()
    {
        return $this->academicHelper->getInstitute();
    }
    public function getInstituteProfile()
    {
        return $this->academicHelper->getInstituteProfile();
    }

    public function getInstituteCampusId()
    {
        return $this->academicHelper->getCampus();
    }

    public function getGradeScaleTypeId()
    {
        return $this->academicHelper->getGradingScale();
    }

    public function getAcademicSemesterId()
    {
        return 1;
    }


}
