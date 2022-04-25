<?php

namespace Modules\Reports\Http\Controllers;

use App\Subject;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Academics\Entities\AcademicsYear;
use Modules\Academics\Entities\AcademicsLevel;
use Modules\Academics\Entities\AttendanceManageView;
use Modules\Academics\Entities\ClassSubject;
use Modules\Academics\Entities\Grade;
use Modules\Academics\Entities\GradeDetails;
use Modules\Academics\Entities\GradeScale;
use Modules\Academics\Entities\StudentAttendance;
use Modules\Academics\Entities\StudentAttendanceDetails;
use Modules\Academics\Http\Controllers\AssessmentsController;
use Modules\Student\Entities\StudentEnrollment;
use Modules\Student\Entities\StudentInformation;
use Modules\Academics\Entities\AttendanceSetting;
use Modules\Academics\Entities\AttendanceViewOne;
use Modules\Academics\Entities\AttendanceViewTwo;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Academics\Entities\Batch;
use Modules\Academics\Entities\Section;
use Modules\Fees\Entities\FeesInvoice;
use Modules\Fees\Entities\Fees;
use Modules\Student\Entities\StudentProfileView;
use Modules\Admission\Entities\ApplicantResult;
use Modules\Admission\Entities\ApplicantExamSetting;
use Modules\Reports\Http\Controllers\AcademicsReportController;
use Modules\Student\Http\Controllers\reports\StudentAttendanceReportController;
use Modules\Academics\Http\Controllers\AttendanceUploadController;
use Modules\Academics\Entities\Semester;
use Modules\Student\Entities\StudentWaiver;
use EasyBanglaDate\Types\BnDateTime;
use Modules\Reports\Entities\IdCardTemplate;
use Modules\Academics\Entities\ReportCardSetting;
use Modules\Academics\Entities\AdditionalSubject;
use Modules\Academics\Entities\GradeCategory;

use Excel;
use App;
use MPDF;
use View;
use MPDFGEN;
use Modules\Setting\Entities\IdCardSetting;
use Modules\Academics\Entities\BatchSemester;

// use Illuminate\Support\Facades\App;

class ReportsController extends Controller
{
    private $academicsReportController;
    private $studentAttendanceReportController;
    private $academicsLevel;
    private $academicsYear;
    private $classSubject;
    private $studentInformation;
    private $studentAttendance;
    private $studentAttendanceDetails;
    private $studentEnrollment;
    private $attendanceManageView;
    private $attendanceSetting;
    private $attendanceViewOne;
    private $attendanceViewTwo;
    private $academicHelper;
    private $batch;
    private $section;
    private $feesInvoice;
    private $fees;
    private $studentProfileView;
    private $applicantResult;
    private $examSetting;
    private $semester;
    private $studentWaiver;
    private $idCardTemplate;
    private $attendanceUploadController;
    private $idCardSetting;
    private $reportCardSetting;
    private $additionalSubject;
    private $batchSemester;
    private $grade;
    private $gradeDetails;
    private $assessmentsController;
    private $gradeCategory;


    public function __construct(AcademicsLevel $academicsLevel, BatchSemester $batchSemester, GradeCategory $gradeCategory, IdCardSetting $idCardSetting, StudentWaiver $studentWaiver, StudentProfileView $studentProfileView, Fees $fees, FeesInvoice $feesInvoice, Batch $batch, ClassSubject $classSubject, StudentInformation $studentInformation, StudentEnrollment $studentEnrollment, StudentAttendance $studentAttendance, StudentAttendanceDetails $studentAttendanceDetails, AttendanceManageView $attendanceManageView, AttendanceSetting $attendanceSetting, AttendanceViewOne $attendanceViewOne, Section $section, AttendanceViewTwo $attendanceViewTwo, AcademicHelper $academicHelper, AcademicsYear $academicsYear, ApplicantResult $applicantResult, ApplicantExamSetting $examSetting, AcademicsReportController $academicsReportController, StudentAttendanceReportController $studentAttendanceReportController, Semester $semester, AttendanceUploadController $attendanceUploadController, IdCardTemplate $idCardTemplate, ReportCardSetting $reportCardSetting, AdditionalSubject $additionalSubject, GradeDetails $gradeDetails, Grade $grade, AssessmentsController $assessmentsController)
    {
        $this->academicsReportController = $academicsReportController;
        $this->studentAttendanceReportController = $studentAttendanceReportController;
        $this->academicsYear = $academicsYear;
        $this->academicsLevel = $academicsLevel;
        $this->classSubject = $classSubject;
        $this->studentEnrollment = $studentEnrollment;
        $this->studentInformation = $studentInformation;
        $this->studentAttendance = $studentAttendance;
        $this->studentAttendanceDetails = $studentAttendanceDetails;
        $this->attendanceManageView = $attendanceManageView;
        $this->attendanceSetting = $attendanceSetting;
        $this->attendanceViewOne = $attendanceViewOne;
        $this->attendanceViewTwo = $attendanceViewTwo;
        $this->academicHelper = $academicHelper;
        $this->batch = $batch;
        $this->section = $section;
        $this->feesInvoice = $feesInvoice;
        $this->fees = $fees;
        $this->studentProfileView = $studentProfileView;
        $this->applicantResult = $applicantResult;
        $this->examSetting = $examSetting;
        $this->semester = $semester;
        $this->studentWaiver = $studentWaiver;
        $this->attendanceUploadController = $attendanceUploadController;
        $this->idCardTemplate = $idCardTemplate;
        $this->idCardSetting = $idCardSetting;
        $this->reportCardSetting = $reportCardSetting;
        $this->additionalSubject = $additionalSubject;
        $this->batchSemester = $batchSemester;
        $this->gradeDetails = $gradeDetails;
        $this->grade = $grade;
        $this->assessmentsController = $assessmentsController;
        $this->gradeCategory = $gradeCategory;
    }

    // report index page
    public function index()
    {
        // student information
        $all = $this->studentInformation->gender('all');
        $male = $this->studentInformation->gender('male');
        $female = $this->studentInformation->gender('female');
        // return veiw with variables
        return view('reports::pages.academics', compact('all', 'male', 'female'))->with('page', 'academics');
    }

    // all pages
    public function allReports($tabId)
    {
        $instituteId = $this->academicHelper->getInstitute();
        $campus_id = $this->academicHelper->getCampus();
        $allAcademicsLevel = $this->academicHelper->getAllAcademicLevel();
        $academicYears = $this->academicHelper->getAllAcademicYears();
        $allsemester = $this->academicHelper->getAcademicSemester();

        // gradeCategory
        $allGradeCategory = $this->gradeCategory->where([
            'institute' => $this->academicHelper->getInstitute(), 'campus' => $this->academicHelper->getCampus(), 'is_sba' => 0
        ])->orderBy('created_at', 'ASC')->get();

        switch ($tabId) {

            case 'academics':
                // student information
                $all = $this->studentInformation->gender('all');
                $male = $this->studentInformation->gender('male');
                $female = $this->studentInformation->gender('female');
                // academic info
                $academicInfo = $this->academicHelper->getAcademicInfo();
                // batch section subject information
                $batchSubjectInfo = $this->getBatchSectionSubjectInfo();
                // return view with variables
                return view('reports::pages.academics', compact('all', 'male', 'female', 'academicInfo', 'batchSubjectInfo'))->with('page', 'academics');
                break;

            case 'attendance':
                // today's attendance details
                // $attendanceInfo = $this->todayAttendanceInfo();
                $attendanceInfo = (object)$this->attendanceUploadController->dailyAttendanceReport();
                // return view with variables
                return view('reports::pages.attendance', compact('attendanceInfo'))->with('page', 'attendance');
                break;

            case 'id-card':
                // $academic_levels
                $academic_levels = $this->academicsLevel->where(['campus_id' => $campus_id, 'institute_id' => $instituteId])->get();
                // instituteInfo
                $instituteInfo = $this->academicHelper->getInstituteProfile();
                // institute information
                $templateProfile = $this->idCardSetting->where(['campus_id' => $campus_id, 'institution_id' => $instituteId])->first();
                // return view with variables
                return view('reports::pages.id-card', compact('academic_levels', 'templateProfile', 'instituteInfo'))->with('page', 'id-card');
                break;


            case 'waiver':

                $academicYears = $this->academicHelper->getAllAcademicYears();
                // return view with variables
                return view('reports::pages.student-waiver', compact('academicYears'))->with('page', 'waiver');
                break;

            case 'result':
                // return view with variables
                return view('reports::pages.result')->with('page', 'result');
                break;


            case 'college-report':
                // return view with variables
                return view('reports::pages.college-report', compact('allAcademicsLevel', 'allGradeCategory', 'allsemester'))->with('page', 'college-report');
                break;


            case 'documents':
                $academicYears = $this->academicHelper->getAllAcademicYears();
                $instituteProfile = $this->academicHelper->getInstituteProfile();
                // return view with variables
                return view('reports::pages.documents', compact('academicYears', 'instituteProfile'))->with('page', 'documents');
                break;

            case 'admission':
                $academicYears = $this->academicHelper->getAllAcademicYears();
                // return view with variables
                return view('reports::pages.admission', compact('academicYears'))->with('page', 'admission');
                break;

            case 'admit-card':
                $academicYears = $this->academicHelper->getAllAcademicYears();
                // return view with variables
                return view('reports::pages.admit-card', compact('academicYears'))->with('page', 'admit-card');
                break;

            case 'sitplan':
                $academicYears = $this->academicHelper->getAllAcademicYears();
                // return view with variables
                return view('reports::pages.sitplan', compact('academicYears'))->with('page', 'sitplan');
                break;

            case 'examatsheet':
                $academicYears = $this->academicHelper->getAllAcademicYears();
                // return view with variables
                return view('reports::pages.examatsheet', compact('academicYears'))->with('page', 'examatsheet');
                break;

            case 'enrollment':
                $academicYears = $this->academicHelper->getAllAcademicYears();
                // return view with variables
                return view('reports::pages.enrollment', compact('academicYears'))->with('page', 'enrollment');
                break;

            case 'employee':
                $academicYears = $this->academicHelper->getAllAcademicYears();
                // return view with variables
                return view('reports::pages.employee', compact('academicYears'))->with('page', 'employee');
                break;

            case 'fees':
                // get batch static report
                // academic year fees inforamtion
//                $academicYear=session()->get('academic_year');
//
//                $studentInfo=$this->studentProfileView->select('std_id')->where('academic_year',$academicYear)->get();
//                $studentIdList=array();
//                foreach($studentInfo as $student){
//                    $studentIdList[]=$student->std_id;
//                }
//
//                $invoiceInfo=$this->feesInvoice->select('fees_id')->where('institution_id',$instituteId)->where('campus_id',$campus_id)->whereIn('payer_id',$studentIdList)->distinct('fees_id')->get();
//
//                $PaidAmount=$this->feesInvoice->select('fees_id')->where('institution_id',$instituteId)->where('campus_id',$campus_id)->whereIn('payer_id',$studentIdList)->where('invoice_status',1)->get();
//
//                $feesIdList=array();
//                foreach($invoiceInfo as $invoice){
//                    $feesIdList[]=$invoice->fees_id;
//                    $totalUnpaid=0;
//                    foreach ($invoice->fees()->feesItems() as $amount) {
//                        $totalUnpaid += $amount->rate * $amount->qty;
//                    }
//                }
//
//                foreach($PaidAmount as $invoice){
//                    $feesIdList[]=$invoice->fees_id;
//                    $totalUnpaid=0;
//                    foreach ($invoice->fees()->feesItems() as $amount) {
//                        $totalUnpaid += $amount->rate * $amount->qty;
//                    }
//                    $totalUnpaid += $totalUnpaid;
//                }
//
//
////                echo $totalUnpaid;
////                exit();
//
//
//
//                $fees=$this->fees->where('institution_id',$instituteId)->where('campus_id',$campus_id)->whereIn('id',$feesIdList)->get();
////                return $fees;
//
//                $feesIdList=array();
//                foreach($invoiceInfo as $invoice){
//                    $feesIdList[]=$invoice->fees_id;
//                }
//
//                $feesInfo=array();
//                foreach ($fees as $fee) {
//                    $paidAmount=0;
//                    $unPaidAmount=0;
//                    $cancelAmount=0;
//                    $totalDiscount=0;
//                    $inprogress=0;
//                    $subtotal=0;
//
//                    // discount
//
//                    foreach ($fee->feesItems() as $amount) {
//                        $subtotal += $amount->rate * $amount->qty;
//                    }
//                    foreach ($fee->removeCancelDiscount() as $invoice) {
//                        if ($fee->discount()) {
//                            $discountPercent = $fee->discount()->discount_percent;
//                            $totalDiscount += (($subtotal * $discountPercent) / 100);
//                        }
//                    }
//
//
//
//                    foreach ( $fee->invoicePaid() as $invoice) {
//                        $paidAmount+=$invoice->totalPayment();
//                    }
//
////                   if(!emptyArray($fee->invoiceCancel())) {
//                    foreach ($fee->invoiceCancel() as $invoice) {
//                        foreach ($invoice->fees()->feesItems() as $amount) {
//                            $cancelAmount += $amount->rate * $amount->qty;
//                        }
//                    }
//
//                    foreach ($fee->invoicePartialAmount() as $invoice) {
//                        $inprogress+=$invoice->totalPayment();
//                        $unPaidAmount = ($subtotal - $invoice->totalPayment()) + $unPaidAmount;
//                    }
//
//                    foreach ($fee->invoiceUnPaid() as $invoice) {
//                        foreach ($invoice->fees()->feesItems() as $amount) {
//                            $unPaidAmount+= $amount->rate * $amount->qty;
//                        }
//                    }
//
//
////                    if($fee->invoicePartialAmount()) {
////
////                    }
//
//// else {
////                        $cancelAmount=0;
////                   }
//
////                    echo $paidAmount."paid Amount";
//
//                    //end fees unpaid amount
//
//                    $feesData['fees_id'] = $fee->id;
//                    $feesData['fees_name'] = $fee->fee_name;
//                    $feesData['paid_amount'] = $paidAmount;
//                    $feesData['inprogress'] = $inprogress;
//                    if($totalDiscount>0) {
//                        $feesData['totalDiscount'] = $totalDiscount;
//                    } else {
//                        $feesData['totalDiscount'] = 0;
//                    }
//                    $feesData['unpaid'] = $unPaidAmount;
//                    $feesData['cancel'] = $cancelAmount;
//
//                    array_push($feesInfo,$feesData);
//
//
//                }

//            exit();


//                 return $feesInfo;
//
//                return view('reports::pages.fees',compact('totalPaybale','totalPaid','totalDiscount','unPaidCount','fees','feesInfo'))->with('page', 'fees');
                return view('reports::pages.fees')->with('page', 'fees');
                break;

            // invoice report and search

            case 'invoice':
                // return view with variables
                $searchInvoice = "";
                $academicYear = $this->academicHelper->getAllAcademicYears();
                $feesinvoices = $this->feesInvoice->orderBy('id', 'desc')->paginate(10);
                return view('reports::pages.invoice', compact('searchInvoice', 'feesinvoices', 'academicYear'))->with('page', 'invoice');
                break;


            default:
                # code...
                break;
        }
    }

    // class-section average report
    public function classSectionAverageReport()
    {
        // academic level
        $allAcademicsLevel = $this->academicHelper->getAllAcademicLevel();
        // return view with variable
        return view('academics::manage-attendance.modals.attendance-summay-report', compact('allAcademicsLevel'));
    }


    // class section monthly attendance report
    public function getClassSectionMonthlyAttendanceReport()
    {
        // academic level
        $allAcademicsLevel = $this->academicHelper->getAllAcademicLevel();
        // return view with variable
        return view('academics::manage-attendance.modals.attendance-class-section-monthly-report', compact('allAcademicsLevel'));
    }


    // class-section average report
    public function studentAttendance()
    {
        // academic semester list
        $semesterList = $this->academicHelper->getAcademicSemester();
        // return view with variables
        return view('academics::manage-attendance.modals.student-attendance-summay-report', compact('semesterList'));
    }

    // student fees report card
    public function invoiceReportCard(Request $request)
    {
        return view('fees::pages.modal.invoice_report_card');
    }


    public function apiTodayAttendanceForGraph($instId, $campId)
    {
        // find institute and campus
        $instProfile = $this->academicHelper->findInstitute($instId);
        $campProfile = $this->academicHelper->findCampusWithInstId($campId, $instId);
        // checking
        if ($instProfile && $campProfile) {
            // institute student information
            $totalStd = $this->studentInformation->gender('all');
            $maleStd = $this->studentInformation->gender('male_count');
            $femaleStd = $this->studentInformation->gender('female_count');

            // today's date
            $todayDate = Carbon::today()->toDateString();
            // institute attendance setting
            $attendanceSettings = $this->attendanceSetting->where(['institution_id' => $instId, 'campus_id' => $campId])->first();
            // checking
            if ($attendanceSettings && $totalStd > 0) {
                // checking Attendance Settings
                if ($attendanceSettings->subject_wise == 0) {
                    $attendanceProfile = $this->attendanceViewOne;
                    $stdAttendanceList = $this->attendanceViewOne->where(['attendance_date' => $todayDate,])->get();
                } else {
                    $attendanceProfile = $this->attendanceViewTwo;
                    $stdAttendanceList = $this->attendanceViewTwo->where(['attendance_date' => $todayDate])->get();
                }

                // total present attendance list
                $stdPresentList = $attendanceProfile->getUniqueStdProfile($attendanceProfile->attendanceSorter(1, $stdAttendanceList));
                $totalPresent = $stdPresentList->count();
                $totalMalePresent = $attendanceProfile->genderSorter('Male', $stdPresentList)->count();
                $totalFemalePresent = $attendanceProfile->genderSorter('Female', $stdPresentList)->count();

                //$number = '1518845.756789';
                // precision count
                // $precision = 3;
                // male_present_percentage' => substr(number_format($number, $precision+1, '.', ''), 0, -1),


                // attendance information calculation in percentage
                // precision count
                $precision = 3;
                return $attendanceInfo = [
                    'total_std' => $totalStd,
                    'total_male_std' => $maleStd,
                    'total_female_std' => $femaleStd,

                    'total_present_std' => substr(number_format($totalPresent, $precision + 1, '.', ''), 0, -1),
                    'total_absent_std' => substr(number_format(($totalStd - $totalPresent), $precision + 1, '.', ''), 0, -1),

                    'total_present_percentage' => substr(number_format(($totalPresent * 100 / $totalStd), $precision + 1, '.', ''), 0, -1),
                    'total_absent_percentage' => substr(number_format((100 - ($totalPresent * 100 / $totalStd)), $precision + 1, '.', ''), 0, -1),

                    'male_present_percentage' => substr(number_format(($totalMalePresent * 100 / $maleStd), $precision + 1, '.', ''), 0, -1),
                    'male_absent_percentage' => substr(number_format((100 - ($totalMalePresent * 100 / $maleStd)), $precision + 1, '.', ''), 0, -1),

                    'female_present_percentage' => substr(number_format(($totalFemalePresent * 100 / $femaleStd), $precision + 1, '.', ''), 0, -1),
                    'female_absent_percentage' => substr(number_format((100 - ($totalFemalePresent * 100 / $femaleStd)), $precision + 1, '.', ''), 0, -1)
                ];
            } else {
                return ['status' => 'failed', 'msg' => 'No student or attendance setting found'];
            }
        } else {
            return ['status' => 'failed', 'msg' => 'Invalid information'];
        }
    }


    public function todayAttendanceInfo()
    {
        // institute student information
        $totalStd = $this->studentInformation->gender('all');
        $maleStd = $this->studentInformation->gender('male_count');
        $femaleStd = $this->studentInformation->gender('female_count');

        // today's date
        $todayDate = Carbon::today()->toDateString();
        $attendanceSettings = $this->attendanceSetting->where(['institution_id' => $this->getInstituteId(), 'campus_id' => $this->getInstituteCampusId()])->first();
        // checking std count
        if ($totalStd > 0) {
            // checking attendance setting
            if ($attendanceSettings) {
                // qry
                $qry = [
                    'attendance_date' => $todayDate,
                    'institute' => $this->academicHelper->getInstitute(),
                    'campus' => $this->academicHelper->getCampus(),
                    'academic_year' => $this->academicHelper->getAcademicYear()
                ];

                // checking Attendance Settings
                if ($attendanceSettings->subject_wise == 0) {
                    $attendanceProfile = $this->attendanceViewOne;
                    $stdAttendanceList = $this->attendanceViewOne->where($qry)->get();
                } else {
                    $attendanceProfile = $this->attendanceViewTwo;
                    $stdAttendanceList = $this->attendanceViewTwo->where($qry)->get();
                }
                // total present attendance list
                $stdPresentList = $attendanceProfile->getUniqueStdProfile($attendanceProfile->attendanceSorter(1, $stdAttendanceList));
                $totalPresent = $stdPresentList->count();
                $totalMalePresent = $attendanceProfile->genderSorter('Male', $stdPresentList)->count();
                $totalFemalePresent = $attendanceProfile->genderSorter('Female', $stdPresentList)->count();

                //$number = '1518845.756789';
                // precision count
                // $precision = 3;
                // male_present_percentage' => substr(number_format($number, $precision+1, '.', ''), 0, -1),


                // attendance information calculation in percentage
                // precision count
                $precision = 3;
                return $attendanceInfo = (object)[
                    'status' => 'success',
                    'total_std' => $totalStd,
                    'total_male_std' => $maleStd,
                    'total_female_std' => $femaleStd,

                    'total_present_std' => substr(number_format($totalPresent, $precision + 1, '.', ''), 0, -1),
                    'total_absent_std' => substr(number_format(($totalStd - $totalPresent), $precision + 1, '.', ''), 0, -1),

                    'total_present_percentage' => substr(number_format(($totalPresent * 100 / $totalStd), $precision + 1, '.', ''), 0, -1),
                    'total_absent_percentage' => substr(number_format((100 - ($totalPresent * 100 / $totalStd)), $precision + 1, '.', ''), 0, -1),

                    'male_present_percentage' => substr(number_format(($totalMalePresent * 100 / $maleStd), $precision + 1, '.', ''), 0, -1),
                    'male_absent_percentage' => substr(number_format((100 - ($totalMalePresent * 100 / $maleStd)), $precision + 1, '.', ''), 0, -1),

                    'female_present_percentage' => substr(number_format(($totalFemalePresent * 100 / $femaleStd), $precision + 1, '.', ''), 0, -1),
                    'female_absent_percentage' => substr(number_format((100 - ($totalFemalePresent * 100 / $femaleStd)), $precision + 1, '.', ''), 0, -1)
                ];
            } else {
                return (object)[
                    'status' => 'failed',
                    'error_type' => 'att_setting',
                    'total_std' => $totalStd,
                    'total_male_std' => $maleStd,
                    'total_female_std' => $femaleStd,
                    'msg' => 'No Attendance settings found for this campus/institute'
                ];
            }
        } else {
            return (object)[
                'status' => 'failed',
                'error_type' => 'no_std',
                'msg' => 'No Student found for this campus/institute'
            ];
        }

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


    //invoice report feltering

    public function invoiceFiltering(Request $request)
    {

        $instituteId = $this->academicHelper->getInstitute();
        $campus_id = $this->academicHelper->getCampus();

        $academics_years = $request->input('academic_year');
        $academics_level = $request->input('academic_level');
        $batch = $request->input('batch');
        $section = $request->input('section');
        $invoice_type = $request->input('invoice_type');
        $from_date = $request->input('search_start_date');
        $to_date = $request->input('search_end_date');
        $invoice_status = $request->input('invoice_status');

        if (!empty($from_date)) {
            $from_date = date('Y-m-d H:i:s', strtotime($from_date));
        }

        if (!empty($to_date)) {
            $to_date = date('Y-m-d', strtotime($to_date));
            $to_date = new Carbon($to_date);

            $to_date = $to_date->endOfDay();
        }
        $conditionArray = array();

        $conditionArray['academic_year'] = $academics_years;

        if (!empty($academics_level)) {
            $conditionArray['academic_level'] = $academics_level;
        }

        if (!empty($batch)) {
            $conditionArray['batch'] = $batch;
        }

        if (!empty($section)) {
            $conditionArray['section'] = $section;
        }

//        return $conditionArray;


//        return $to_date->endOfDay();;

        $allSearchInputs = array();


        $std_enrollments = $this->studentEnrollment->where($conditionArray)->get();
        $data = array();
        $i = 1;
        if ($std_enrollments) {
            $studentIdlist = array();
            foreach ($std_enrollments as $enrollment) {
                $studentinfo = $enrollment->student();
                $studentIdlist[] = $studentinfo->id;
            }

        }

//        return $studentIdlist;


        if ($invoice_status == 1 || $invoice_status == 2 || $invoice_status == 4) {
            $allSearchInputs['invoice_status'] = $invoice_status;
            $allSearchInputs['invoice_status'] = $invoice_status;
        }
        if ($invoice_type == 2 || $invoice_type == 1) {
            $allSearchInputs['invoice_type'] = $invoice_type;
        }

        if (!empty($from_date) && !empty($to_date)) {

            $allFeesInvoices = $this->feesInvoice->where($allSearchInputs)->where('institution_id', $instituteId)->where('campus_id', $campus_id)->whereIn('payer_id', $studentIdlist)->whereBetween('created_at', [$from_date, $to_date])->paginate(10);
        }

//
        $allFeesInvoices;

        if ($allFeesInvoices) {
            // all inputs
            $allInputs = [
                'search_start_date' => $from_date,
                'search_end_date' => $to_date,
                'invoice_status' => $invoice_status,
            ];
            // return view
            $allInputs = (object)$allInputs;
            $searchInvoice = 1;
            return view('reports::pages.modals.invoice_search', compact('searchInvoice', 'allFeesInvoices', 'allInputs'))->with('page', 'invoice');
            // return redirect()->back()->with(compact('state'))->withInput();

        }

    }


    // download invoice report pdf or excel

    public function importInvoicePdforExcel(Request $request)
    {
//        return $request->all();
        $reportTitle = 'Fees Invoice Reports';

        $instituteInfo = $this->academicHelper->getInstituteProfile();

        $instituteId = $this->academicHelper->getInstitute();
        $campus_id = $this->academicHelper->getCampus();

        $academics_years = $request->input('academic_year');
        $academics_level = $request->input('academic_level');
        $batch = $request->input('batch');
        $section = $request->input('section');

        $academics_years_name = $request->input('academic_year_name');
        $academics_level_name = $request->input('academic_level_name');
        $batch_name = $request->input('batch_name');
        $section_name = $request->input('section_name');
        $invoice_type = $request->input('invoice_type');


        $from_date = $request->input('search_start_date');
        $to_date = $request->input('search_end_date');
        $invoice_status = $request->input('invoice_status');

        if (!empty($from_date)) {
            $from_date = date('Y-m-d H:i:s', strtotime($from_date));
        }

        if (!empty($to_date)) {
            $to_date = date('Y-m-d', strtotime($to_date));
            $to_date = new Carbon($to_date);

            $to_date = $to_date->endOfDay();
        }
        $conditionArray = array();

        $conditionArray['academic_year'] = $academics_years;

        if (!empty($academics_level)) {
            $conditionArray['academic_level'] = $academics_level;
        }

        if (!empty($batch)) {
            $conditionArray['batch'] = $batch;
        }

        if (!empty($section)) {
            $conditionArray['section'] = $section;
        }


//        return $to_date->endOfDay();;

        $allSearchInputs = array();


        $std_enrollments = $this->studentEnrollment->where($conditionArray)->get();
        $data = array();
        $i = 1;
        if ($std_enrollments) {
            $studentIdlist = array();
            foreach ($std_enrollments as $enrollment) {
                $studentinfo = $enrollment->student();
                $studentIdlist[] = $studentinfo->id;
            }

        }


        if ($invoice_status) {
            $allSearchInputs['invoice_status'] = $invoice_status;

        }
        if ($invoice_type == 2 || $invoice_type == 1) {
            $allSearchInputs['invoice_type'] = $invoice_type;
        }


        if (!empty($from_date) && !empty($to_date)) {

            $allFeesInvoices = $this->feesInvoice->where($allSearchInputs)->where('institution_id', $instituteId)->where('campus_id', $campus_id)->whereIn('payer_id', $studentIdlist)->whereBetween('created_at', [$from_date, $to_date])->get();
        }


        $allInputs = [
            'academics_years' => $academics_years_name,
            'academics_level' => $academics_level_name,
            'academics_level_value' => $academics_level,
            'batch_value' => $batch,
            'batch' => $batch_name,
            'section' => $section_name,
            'section_value' => $section,
            'search_start_date' => $from_date,
            'search_start_date' => $from_date,
            'search_end_date' => $to_date,
            'invoice_status' => $invoice_status,
        ];

        $allInputs = (object)$allInputs;
//        var_dump ($allInputs);
//        exit();


        $report_type = $request->input('report_type');
        if ($report_type == "pdf") {
            // $allFeesInvoices = $this->feesInvoice->where($allSearchInputs)->where('institution_id',$instituteId)->where('campus_id',$campus_id)->whereBetween('created_at', [$from_date, $to_date])->get();
            view()->share(compact('allFeesInvoices', 'allInputs', 'instituteInfo', 'report_type', 'reportTitle'));

            //generate PDf
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView('reports::pages.report.invoice_report')->setPaper('a4', 'portrait');
            // return $pdf->stream();
            $downloadFileName = "invoice_report.pdf";
            return $pdf->download($downloadFileName);

        } else {


            view()->share(compact('allFeesInvoices', 'allInputs', 'report_type'));
            //generate excel
            return Excel::create('invoice_report', function ($excel) {
                $excel->sheet('invoice_report', function ($sheet) {
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

                    $sheet->loadView('reports::pages.report.invoice_report');
                });
            })->download('xls');
        }


    }


    //////////////////   Admission reports area starts here ///////////////////////
    public function filterApplicants(Request $request)
    {
        // sql qry
        $qry = [
            'academic_year' => $request->input('academic_year'),
            'academic_level' => $request->input('academic_level'),
            'batch' => $request->input('batch'),
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ];
        // exam settings
        if ($examTakenSettingProfile = $this->examSetting->where($qry)->first()) {
            $examTaken = $examTakenSettingProfile->exam_taken;
        } else {
            $examTaken = 0;
        }
        // generate applicant result sheet
        $applicantResultSheet = $this->applicantResult->where($qry)->get();
        // show applicant result sheet
        return view('admission::admission-assessment.modals.result', compact('applicantResultSheet', 'examTaken'));
    }

    /**
     * @return object
     */
    public function getBatchSectionSubjectInfo()
    {
        $batchList = $this->batch->where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
            'academics_year_id' => $this->academicHelper->getAcademicYear()
        ])->get();

        // response data
        $label = array();
        $core_data = array();
        $elective_data = array();

        // looping
        foreach ($batchList as $batch) {
            // find batch all subject
            $classSubjectList = $this->classSubject->where(['class_id' => $batch->id])->distinct('subject_id')->get();
            //sub tyep count
            $core = 0;
            $elective = 0;
            // looping
            foreach ($classSubjectList as $subject) {
                // checking type
                if ($subject->subject_type == 1) {
                    // core subject
                    $core = ($core + 1);
                } else {
                    // elective subject
                    $elective = ($elective + 1);
                }
            }
            // input response dataset
            $label[] = $batch->batch_name;
            array_push($core_data, $core);
            array_push($elective_data, $elective);
        }

        $batchSubjectInfo = (object)[
            'label' => json_encode($label),
            'core_data' => json_encode($core_data),
            'elective_data' => json_encode($elective_data),
        ];
        return $batchSubjectInfo;
    }


    // fees monthly report model
    public function feesMonthlyReportView()
    {
        $academicsYears = $this->academicsYear->all();
        return view('fees::pages.monthly_fees_report', compact('academicsYears'));
    }


    // fine fees report Modal View
    public function feesFineModalView()
    {
        return view('reports::pages.modals.fine-fees-report-modal');
    }

    public function feesDetailsReports()
    {
        return view('fees::pages.modal.fees_details_report');
    }


    public function customisedTestimonials(Request $request)
    {
       // return $request->all();
        // testimonialInfoArray
        $info['border_color']=$request->border_color ;
        $info['s_name']=$request-> s_name;
        $info['gender']=$request->gender ;
        $info['f_name']=$request-> f_name;
        $info['m_name']=$request-> m_name;
        $info['village']=$request-> village;
        $info['post']=$request->post ;
        $info['upzilla']=$request->upzilla ;
        $info['zilla']=$request-> zilla;
        $info['exam']=$request-> exam;
        $info['board']=$request->board ;
       $testimonialInfoArray = json_encode($request->all());
      // return $testimonialInfoArray;
        // institute profile
        $instituteProfile = $this->academicHelper->getInstituteProfile();
        //generate PDf
        $pdf = App::make('dompdf.wrapper');
     $pdf->loadView('reports::pages.report.testimonial-english',compact('testimonialInfoArray','instituteProfile'))->setPaper('a4','landscape');
    //   return view('reports::pages.report.testimonial-english',compact('testimonialInfoArray', 'instituteProfile'));

        return $pdf->stream();
        // $downloadFileName = "testimonial_en_file.pdf";
        // return $pdf->download($downloadFileName);
        // return view
        // return view('reports::pages.report.testimonial-english',compact('instituteProfile'));
    }


    public function getTestimonialReport(Request $request)
    {
//

        $download = $request->input('download');
        $instituteProfile = $this->academicHelper->getInstituteProfile();
        $testimonialInfo = $request->all();
        $testimonialInfoArray = array(
            'std_name' => $request->input('std_name'),
            'father' => $request->input('father'),
            'mother' => $request->input('mother'),
            'village' => $request->input('village'),
            'post' => $request->input('post'),
            'upzilla' => $request->input('upzilla'),
            'zilla' => $request->input('zilla'),
            'class1' => $request->input('class1'),
            'class2' => $request->input('class2'),
            'year1' => $request->input('year1'),
            'year2' => $request->input('year2'),
            'year3' => $request->input('year3'),
            'class3' => $request->input('class3'),
            'gpa' => $request->input('gpa'),
            'dob' => $request->input('dob'),
            'year4' => $request->input('year4'),
            'class4' => $request->input('class4'),
        );
        $testimonialInfoArray = (object)$testimonialInfoArray;
        if ($download == "preview") {
            return view('reports::pages.report.testimonial', compact('testimonialInfoArray', 'instituteProfile'));

        } else {
            //generate PDf
//     $pdf = App::make('dompdf.wrapper');
//       $pdf->loadView('reports::pages.report.testimonial-bn',compact('testimonialInfoArray','instituteProfile'))
//           ->setPaper('a4','landscape');
//       return $pdf->stream();
//        $downloadFileName = "testimonial_file.pdf";
//        return $pdf->download($downloadFileName);

            //generate PDf
           // $pdf = App::make('mpdf.wrapper');
            $pdf=MPDFGEN::loadView('reports::pages.report.testimonial_download',
                compact('testimonialInfoArray', 'instituteProfile'),
                [],[

                ]);
        //return $pdf->stream('document.pdf');
            $view = View::make('reports::pages.report.tc-download', compact('testimonialInfoArray', 'instituteProfile'));
            $html = $view->render();
            $mpdf = new MPDF\Mpdf([ 'mode' => 'utf-8',
                'format' => 'A4-L',
                'orientation' => 'L'
                ]);
            $mpdf->autoScriptToLang = true;// Mandatory
            $mpdf->autoLangToFont = true;//Mandatory
            $mpdf->WriteHTML($html);
            $mpdf->Output();
        }

//        return view('reports::pages.report.testimonial',compact('testimonialInfoArray','instituteProfile'));
    }


    // download and preview transfer certificate report

    public function getTransferCertificateReport(Request $request)
    {
//

        $download = $request->input('download');
        $instituteProfile = $this->academicHelper->getInstituteProfile();
//        return $testimonialInfo=$request->all();
        $testimonialInfoArray = array(
            'std_name' => $request->input('std_name'),
            'father' => $request->input('father'),
            'mother' => $request->input('mother'),
            'village' => $request->input('village'),
            'post' => $request->input('post'),
            'upzilla' => $request->input('upzilla'),
            'zilla' => $request->input('zilla'),
            'class1' => $request->input('class1'),
            'class2' => $request->input('class2'),
            'year1' => $request->input('year1'),
            'year2' => $request->input('year2'),
            'year3' => $request->input('year3'),
            'class3' => $request->input('class3'),
            'gpa' => $request->input('gpa'),
            'dob' => $request->input('dob'),
            'year4' => $request->input('year4'),
            'class4' => $request->input('class4'),
            'character' => $request->input('character'),
            'behavior' => $request->input('behavior'),
            'attendance' => $request->input('attendance'),
            'talent' => $request->input('talent'),
            'leave_message' => $request->input('leave_message')
        );
        //return $request->all();
        $testimonialInfoArray = (object)$testimonialInfoArray;
        if ($download == "preview") {
            return view('reports::pages.report.transfer-certificate', compact('testimonialInfoArray', 'instituteProfile'));

        } else {
            //generate PDf
//        $pdf = App::make('dompdf.wrapper');
//        $pdf->loadView('reports::pages.report.testimonial_download',compact('testimonialInfoArray','instituteProfile'))->setPaper('a4','landscape');
//        // return $pdf->stream();
//        $downloadFileName = "testimonial_file.pdf";
//        return $pdf->download($downloadFileName);

            //generate PDf
           // dd (testimonialInfoArray);

            $pdf = App::make('mpdf.wrapper');
            $pdf->loadView('reports::pages.report.transfer-certificate_download', compact('testimonialInfoArray', 'instituteProfile'));
            $view = View::make('reports::pages.report.transfer-certificate_download', compact('testimonialInfoArray', 'instituteProfile'));
            $html = $view->render();

            //$mpdf=new \Mpdf\Mpdf()
            $mpdf = new MPDF\Mpdf(['orientation' => 'L']);
            $mpdf->autoScriptToLang = true;// Mandatory
            $mpdf->autoLangToFont = true;//Mandatory
            $mpdf->WriteHTML($html);
            $mpdf->Output();
        }

//        return view('reports::pages.report.testimonial',compact('testimonialInfoArray','instituteProfile'));
    }


    //////////////////////////  ADMIT CARD ///////////////////////////////
    // show admit card
    public function admitCard(Request $request)
    {
        // request details
        $year = $request->input('academic_year');
        $level = $request->input('academic_level');
        $batch = $request->input('batch');
        $section = $request->input('section');
        $semester = $request->input('semester');
        // academic details
        $campusId = $this->academicHelper->getCampus();
        $instituteId = $this->academicHelper->getInstitute();

        // checking campus and institute details
        if ($campusId == 20 and $instituteId == 19) {
            // find batch section subject list
            $classSubjectList = (object)$this->academicHelper->findClassSectionGroupSubjectList($section, $batch, $campusId, $instituteId);
            // return view with variables
            return view('reports::pages.includes.admit-card-college', compact('classSubjectList'));
        } else {
            // find batch section subject list
            $classSubjectList = (object)$this->studentAttendanceReportController->getClsssSectionSubjectList($batch, $section);
            // return view with variables
            return view('reports::pages.includes.admit-card', compact('classSubjectList'));
        }
    }

    // download admit card
    public function downloadAdmitCardForCollege(Request $request)
    {
        // exam routine
        $examRoutine = $request->input('routine');
        // request details
        $year = $request->input('academic_year');
        $level = $request->input('academic_level');
        $batch = $request->input('batch');
        $section = $request->input('section');
        $semester = $request->input('semester');
        $institute = $this->academicHelper->getInstitute();
        $campus = $this->academicHelper->getCampus();
        // instituteInfo
        $instituteInfo = $this->academicHelper->getInstituteProfile();
        // Report Card Setting
        $reportCardSetting = $this->reportCardSetting->where(['institute' => $institute, 'campus' => $campus])->first();

        // find semester
        $semesterProfile = $this->semester->find($semester);
        // find batch section student list
        $studentList = $this->studentProfileView->where([
            'academic_year' => $year,
            'academic_level' => $level,
            'batch' => $batch,
            'section' => $section,
            'campus' => $campus,
            'institute' => $institute,
        ])->orderByRaw('LENGTH(gr_no) asc')->orderBy('gr_no', 'asc')->get();

        // additionalSubject
        $additionalArrayList = $this->academicHelper->findClassSectionAdditionalSubjectList($section, $batch, $campus, $institute);

        // share variables with view
        view()->share(compact('studentList', 'examRoutine', 'instituteInfo', 'semesterProfile', 'reportCardSetting', 'campus', 'additionalArrayList'));
        $html = view('reports::pages.report.admit-card-college');
        $pdf = App::make('snappy.pdf.wrapper');
        $pdf->loadHTML($html);
        return $pdf->inline('batch-section-admit-card.pdf');
    }


    // download admit card
    public function downloadAdmitCard(Request $request)
    {
        // exam routine
        $examRoutine = $request->input('routine');
        // request details
        $year = $request->input('academic_year');
        $level = $request->input('academic_level');
        $batch = $request->input('batch');
        $section = $request->input('section');
        $semester = $request->input('semester');
        $institute = $this->academicHelper->getInstitute();
        $campus = $this->academicHelper->getCampus();
        // instituteInfo
        $instituteInfo = $this->academicHelper->getInstituteProfile();
        // Report Card Setting
        $reportCardSetting = $this->reportCardSetting->where(['institute' => $institute, 'campus' => $campus])->first();

        // find semester
        $semesterProfile = $this->semester->find($semester);
        // find batch section student list
        $studentList = $this->studentProfileView->where([
            'academic_year' => $year,
            'academic_level' => $level,
            'batch' => $batch,
            'section' => $section,
            'campus' => $campus,
            'institute' => $institute,
        ])->orderByRaw('LENGTH(gr_no) asc')->orderBy('gr_no', 'asc')->get();
        //
        view()->share(compact('studentList', 'examRoutine', 'instituteInfo', 'semesterProfile', 'reportCardSetting', 'campus'));

        // checking admit card type
        if ($campus == 28 and $institute == 27) {
            $html = view('reports::pages.report.admit-card-two');
        } else {
            $html = view('reports::pages.report.admit-card');
        }
        $pdf = App::make('snappy.pdf.wrapper');
        $pdf->loadHTML($html);
        return $pdf->inline('batch-section-admit-card.pdf');

    }


    // class section testimonial search

    public function searchStudentClassSectionTestimonial(Request $request)
    {


        $result = array();
        // batch section student list
        $studentList = $this->studentProfileView->where([
            'academic_year' => $request->input('academic_year'),
            'academic_level' => $request->input('academic_level'),
            'batch' => $request->input('batch'),
            'section' => $request->input('section'),
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->get();

        $resultType = 3;

        foreach ($studentList as $std) {
            $result[$std->std_id]['profile'][] = $std;
            $stdGuaridiansList = $std->student()->myGuardians();
            $address = $std->user()->singleAddress("STUDENT_PRESENT_ADDRESS");
            $resultProfile = $std->testimonial_result($resultType);
            $dob = $std->student()->dob;
            $result[$std->std_id]['dob'][] = $dob;
            $result[$std->std_id]['address'][] = $address;
            $result[$std->std_id]['testimonial_result'][] = $resultProfile;
            foreach ($stdGuaridiansList as $stdGuaridian) {
                $guaridian = $stdGuaridian->guardian();
                $result[$std->std_id]['guardian'][] = $guaridian;
            }
        }

        $studentList = $result;


//        return $studentList;

        // institute profile
        $instituteInfo = $this->academicHelper->getInstituteProfile();

        // return view with variables
        //return $studentList;
        return view('reports::pages.modals.testimonial-class-section',
            compact('studentList', 'instituteInfo'));

    }


    // testimonial Result Download

    public function getTestimonialResult(Request $request)
    {
       // return $request;

        $result = array();
        // batch section student list
        $studentList = $this->studentProfileView->where([
            'academic_year' => $request->input('academic_year'),
            'academic_level' => $request->input('academic_level'),
            'batch' => $request->input('batch'),
            'section' => $request->input('section'),
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->get();

        $resultType = 3;

        foreach ($studentList as $std) {
            $result[$std->std_id]['profile'][] = $std;
            $stdGuaridiansList = $std->student()->myGuardians();
            $address = $std->user()->singleAddress("STUDENT_PRESENT_ADDRESS");
            $resultProfile = $std->testimonial_result($resultType);
            $dob = $std->student()->dob;
            $result[$std->std_id]['dob'][] = $dob;
            $result[$std->std_id]['address'][] = $address;
            $result[$std->std_id]['testimonial_result'][] = $resultProfile;
            foreach ($stdGuaridiansList as $stdGuaridian) {
                $guaridian = $stdGuaridian->guardian();
                $result[$std->std_id]['guardian'][] = $guaridian;
            }
        }


        $studentList = $result;


//        return $studentList;

        // institute profile
        $instituteInfo = $this->academicHelper->getInstituteProfile();

        //generate PDf
        /*ini_set('max_execution_time', '300');
        ini_set("pcre.backtrack_limit", "5000000");
        ini_set('memory_limit', '44M');*/
        $pdf = App::make('mpdf.wrapper');
        $pdf->loadView('reports::pages.report.testimonial_result', compact('studentList', 'instituteInfo'));
        $view = View::make('reports::pages.report.testimonial_result', compact('studentList', 'instituteInfo'));
        $html = $view->render();
        $mpdf = new MPDF\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4-L',
            'orientation' => 'L'
        ]);
        $mpdf->autoScriptToLang = true;// Mandatory
        $mpdf->autoLangToFont = true;//Mandatory
        $mpdf->WriteHTML($html);
        $mpdf->Output();

    }


    public function searchStudentWaiverClassSection(Request $request)
    {
//     return   $request->all();
        $academic_year = $request->input('academic_year');
        $academic_level = $request->input('academic_level');
        $batch = $request->input('batch');
        $section = $request->input('section');
        $waiver_type = $request->input('waiver_type');

        $conditionArray['institute'] = $this->academicHelper->getInstitute();
        $conditionArray['campus'] = $this->academicHelper->getCampus();
        $conditionArray = array();

        if (!empty($academic_year)) {
            $conditionArray['academic_year'] = $academic_year;
        }

        if (!empty($academic_level)) {
            $conditionArray['academic_level'] = $academic_level;
        }

        if (!empty($batch)) {
            $conditionArray['batch'] = $batch;
        }

        if (!empty($section)) {
            $conditionArray['section'] = $section;
        }
//         return $conditionArray;


        $studentList = $this->studentProfileView->where($conditionArray)->get(['std_id']);

        $studendList = $studentList->toArray();
        $studendIdList = array_pluck($studendList, 'std_id');

        $studentWaiverList = $this->studentWaiver->where('waiver_type', $waiver_type)->whereIn('std_id', $studendIdList)->paginate(20);

        return view('reports::pages.modals.waiver_search', compact('studentWaiverList'));


    }


    // waiver report downlaod class section wise

    public function studentWaiverReportExport(Request $request)
    {
//        return $request->all();

        $instituteProfile = $this->academicHelper->getInstituteProfile();
        $academic_year = $request->input('academic_year');
        $academic_level = $request->input('academic_level');
        $batch = $request->input('batch');
        $section = $request->input('section');
        $waiver_type = $request->input('waiver_type');

        $conditionArray['institute'] = $this->academicHelper->getInstitute();
        $conditionArray['campus'] = $this->academicHelper->getCampus();
        $conditionArray = array();

        $conditionArray['academic_year'] = $academic_year;
        $conditionArray['academic_level'] = $academic_level;
        $conditionArray['batch'] = $batch;


        if ($section > 0) {
            $conditionArray['section'] = $section;
        }
        $studentList = $this->studentProfileView->where($conditionArray)->get(['std_id']);

        $studendList = $studentList->toArray();
        $studendIdList = array_pluck($studendList, 'std_id');

        $studentWaivers = $this->studentWaiver->where('waiver_type', $waiver_type)->whereIn('std_id', $studendIdList)->get();


        view()->share(compact('instituteProfile', 'studentWaivers', 'waiver_type', 'batch', 'section'));

        //generate PDf
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('reports::pages.report.class_section_waiver_report')->setPaper('a4', 'portrait');
        // return $pdf->stream();
        $downloadFileName = "waiver_reports.pdf";
        return $pdf->download($downloadFileName);


    }


    public function sitPlanView(Request $request)
    {

        // instititue information
        $examName = $request->semester;
        $instituteInfo = $this->academicHelper->getInstituteProfile();
        $studentList = $this->studentProfileView
            ->where('batch', $request->batch)
            ->where('section', $request->section)
            ->where('academic_level', $request->academic_level)
            ->where('academic_year', $request->academic_year)
            ->orderByRaw('LENGTH(gr_no) asc')->orderBy('gr_no', 'asc')
            ->get();

        return view('reports::pages.report.sitplan.sitplanview', compact('studentList', 'instituteInfo', 'examName'));
    }


    public function examAttSheet(Request $request)
    {
        // institute information
        $instituteInfo = $this->academicHelper->getInstituteProfile();
        // student list
        $studentList = $this->studentProfileView
            ->where('batch', $request->batch)
            ->where('section', $request->section)
            ->where('academic_level', $request->academic_level)
            ->where('academic_year', $request->academic_year)
            ->orderByRaw('LENGTH(gr_no) asc')->orderBy('gr_no', 'asc')
            ->get(['username', 'std_id', 'gr_no', 'first_name', 'middle_name', 'last_name', 'bn_fullname', 'batch', 'section']);

        // checking institute and campus
        if (campus_id() == 20 and institution_id() == 19) {
            // additionalSubject
            $additionalArrayList = $this->academicHelper->findClassSectionAdditionalSubjectList($request->section, $request->batch, campus_id(), institution_id(), 'group');
            // batch semester get
            $batchSemseterList = $this->batchSemester->where('batch', $request->batch)->where('academic_year', $request->academic_year)->get();
            // class subject list
            $classSubjectList = (object)$this->academicHelper->findClassSectionGroupSubjectList($request->section, $request->batch, campus_id(), institution_id());
            // return view with variables
            return view('reports::pages.report.examsheet.index1', compact('studentList', 'instituteInfo', 'batchSemseterList', 'additionalArrayList', 'classSubjectList'));
        } else {
            // semester profile / examination profile
            $semesterProfile = $this->academicHelper->getSemester($request->input('semester'));
            // additionalSubject
            $additionalArrayList = $this->academicHelper->findClassSectionAdditionalSubjectList($request->section, $request->batch, campus_id(), institution_id(), 'subject');
            // class subject list
            $classSubjectList = $this->academicHelper->findClassSectionSubjectList($request->section, $request->batch, campus_id(), institution_id());
            // return view with variables
            return view('reports::pages.report.examsheet.exam-attendance-sheet', compact('studentList', 'instituteInfo', 'semesterProfile', 'additionalArrayList', 'classSubjectList'));
        }
    }



//    public function examAttSheet(Request $request){
//        // instititue information
//        $instituteInfo=$this->academicHelper->getInstituteProfile();
//        $studentList=$this->studentProfileView
//            ->where('batch',$request->batch)
//            ->where('section',$request->section)
//            ->where('academic_level',$request->academic_level)
//            ->where('academic_year',$request->academic_year)
//            ->orderByRaw('LENGTH(gr_no) asc')->orderBy('gr_no', 'asc')
//            ->get();
//
//        // additionalSubject
//         $additionalArrayList = $this->academicHelper->findClassSectionAdditionalSubjectList($request->section, $request->batch, campus_id(), institution_id());
//
//         $classSubjectList = (object)$this->academicHelper->findClassSectionGroupSubjectList($request->section, $request->batch, campus_id(), institution_id());
//
//        return view('reports::pages.report.examsheet.index',compact('studentList','instituteInfo','examName','additionalArrayList','classSubjectList'));
//        
//    }


    // chirto 2.1
    public function collegeResultReport(Request $request)
    {
        $instituteInfo = $this->academicHelper->getInstituteProfile();

        $level = $request->academic_level;
        $batch = $request->batch;
        $section = $request->section;
        $subject = $request->subject;
        $semester = $request->semester;
        $category = $request->category;
        // class subject profile
        $classSubjectProfile = $this->academicHelper->getClassSubject($subject);
        $classSubjectGroup = $classSubjectProfile ? $classSubjectProfile->subject_group : 0;

        $academicYear = $this->academicHelper->getAcademicYearProfile();
        $subjectName = $this->academicHelper->getClassSubject($subject)->subject();
        $batchName = $this->academicHelper->findBatch($batch);
        $semesterName = $this->academicHelper->getSemester($semester);

        // institute and campus
        $institute = $this->academicHelper->getInstitute();
        $campus = $this->academicHelper->getCampus();
        // scale id
        $scaleId = $this->assessmentsController->getGradeScaleId($batch, $section);
        // grade scale scale details
        $gradeScaleDetails = $this->gradeDetails->where('grade_id', $scaleId)->orderBy('sorting_order', 'ASC')->get();
        // grading scale
        $gradeScale = $this->grade->orderBy('name', 'ASC')->where('id', $scaleId)->first(['id', 'name', 'grade_scale_id']);
        // find grade scale category list
        $allCategoryList = $gradeScale->assessmentsCount() ? $gradeScale->assessmentCategory() : [];
        // find category details array list
        $catDetailArrayList = $this->assessmentsController->getCategoryDetails($allCategoryList, $gradeScale);

        // class subject profile
        $classSubjectProfile = $this->academicHelper->getClassSubject($subject);
        // student list
        $classStdList = $this->assessmentsController->getClsssSectionStudentList($batch, $section);
        $classSubStdList = $this->academicHelper->getAdditionalSubjectStdList($subject, $section, $batch, $campus, $institute);
        // find subject student from class student list
        $studentList = $this->academicHelper->getClassSubjectStudentList($classSubjectProfile, $classSubStdList, $classStdList);

//        // student list
//        $studentList = $this->studentProfileView->where([
//            'batch'=>$batch, 'section'=>$section, 'academic_level'=>$level, 'campus'=>$campus, 'institute'=>$institute
//        ])->orderByRaw('LENGTH(gr_no) asc')->orderBy('gr_no', 'asc')->get(['std_id', 'gr_no','username', 'first_name', 'middle_name', 'last_name']);

        // additional subject list
        $additionalSubArrayList = $this->academicHelper->findClassSectionAdditionalSubjectList($section, $batch, $campus, $institute);

        // tabulation sheet
        $tabulationSheet = $this->assessmentsController->getTabulationMarkSheetForCollege($section, $batch, $category, $semester, $additionalSubArrayList, $gradeScaleDetails, $campus, $institute, $subject, null, "SUB_SINGLE_RESULT");


        return view('reports::pages.report.college-result.student-report', compact('studentList', 'tabulationSheet', 'category', 'subject', 'classSubjectGroup', 'catDetailArrayList', 'subjectName', 'batchName', 'semesterName', 'instituteInfo', 'academicYear'));

    }

    //chirto 3
    public function collegeSubjectWiseSummery(Request $request)
    {
        $instituteInfo = $this->academicHelper->getInstituteProfile();
        // request details
        $level = $request->academic_level;
        $batch = $request->batch;
//        $section =  $request->section;
        $semester = $request->semester;
        $category = $request->category;

        $academicYear = $this->academicHelper->getAcademicYearProfile();
        $batchName = $this->academicHelper->findBatch($batch);
        $semesterName = $this->academicHelper->getSemester($semester);

        // institute and campus
        $institute = $this->academicHelper->getInstitute();
        $campus = $this->academicHelper->getCampus();
        // scale id
        $scaleId = $this->assessmentsController->getGradeScaleId($batch, null);
        // grade scale scale details
        $gradeScaleDetails = $this->gradeDetails->where('grade_id', $scaleId)->orderBy('sorting_order', 'ASC')->get();
        // Class subject list
        $classSubGroupArrayList = $this->academicHelper->findClassSectionGroupSubjectList(null, $batch, $campus, $institute);
        // additional subject list
        $additionalSubArrayList = $this->academicHelper->findClassSectionAdditionalSubjectList(null, $batch, $campus, $institute);
        // subject group result list
        $subGroupResultList = array();
        // sublist array list
        foreach ($classSubGroupArrayList as $groupSubId => $groupSubDetail) {
            $subGroupResultList[$groupSubId] = $tabulationSheet = $this->assessmentsController->getTabulationMarkSheetForCollege(null, $batch, $category, $semester, $additionalSubArrayList, $gradeScaleDetails, $campus, $institute, null, $groupSubId, "SUB_GROUP_RESULT");
        }


        // return view with variables
        return view('reports::pages.report.college-result.subject-summery', compact('classSubGroupArrayList', 'subGroupResultList', 'instituteInfo', 'academicYear', 'batchName', 'semesterName'));
    }

    //4444
    public function collegeResultSummery(Request $request)
    {
        $semester = $request->semester;
        $category = $request->category;

        // institute and campus
        $institute = $this->academicHelper->getInstitute();
        $campus = $this->academicHelper->getCampus();
        $allBatch = $this->academicHelper->getBatchList();


        $instituteInfo = $this->academicHelper->getInstituteProfile();
        $academicYear = $this->academicHelper->getAcademicYearProfile();
        $semesterName = $this->academicHelper->getSemester($semester);

        $subSummaryResultList = array();
        // sublist array list
        foreach ($allBatch as $batchId => $groupSubDetail) {
            // scale id
            $scaleId = $this->assessmentsController->getGradeScaleId($batchId, null);
            // grade scale scale details
            $gradeScaleDetails = $this->gradeDetails->where('grade_id', $scaleId)->orderBy('sorting_order', 'ASC')->get();
            // additional subject list
            $additionalSubArrayList = $this->academicHelper->findClassSectionAdditionalSubjectList(null, $batchId, $campus, $institute);

            $subSummaryResultList[$batchId] = $tabulationSheet = $this->assessmentsController->getTabulationMarkSheetForCollege(null, $batchId, $category, $semester, $additionalSubArrayList, $gradeScaleDetails, $campus, $institute, null, null, "SUMMERY_RESULT");
        }

        return view('reports::pages.report.college-result.result-summery', compact('subSummaryResultList', 'allBatch', 'instituteInfo', 'academicYear', 'semesterName'));
    }


    public function collegeSummeryofGrade()
    {
        return view('reports::pages.report.college-result.summery-grade');
    }

    public function tutorialExamReport(Request $request)
    {
//        return $request->all();
        $instituteInfo = $this->academicHelper->getInstituteProfile();
        $acdemicYear = $this->academicHelper->getAcademicYearProfile();

        $level = $request->academic_level;
        $batch = $request->batch;
        $section = $request->section;
        $semester = $request->semester;
        $category = $request->category;
        $category_name = $request->category_name;

        if (!empty($request->std_id)) {
            $myStdId = $request->std_id;
        } else {
            $myStdId = null;
        }


        // institute and campus
        $institute = $this->academicHelper->getInstitute();
        $campus = $this->academicHelper->getCampus();
        // scale id
        $scaleId = $this->assessmentsController->getGradeScaleId($batch, $section);
        // grade scale scale details
        $gradeScaleDetails = $this->gradeDetails->where('grade_id', $scaleId)->orderBy('sorting_order', 'ASC')->get();
        // grading scale
        $gradeScale = $this->grade->orderBy('name', 'ASC')->where('id', $scaleId)->first(['id', 'name', 'grade_scale_id']);
        // find grade scale category list
        $allCategoryList = $gradeScale->assessmentsCount() ? $gradeScale->assessmentCategory() : [];
        // find category details array list
        $catDetailArrayList = $this->assessmentsController->getCategoryDetails($allCategoryList, $gradeScale);
        // Class subject list
        $classSubGroupArrayList = $this->academicHelper->findClassSectionGroupSubjectList($section, $batch, $campus, $institute);
        // additional subject list
        $additionalSubArrayList = $this->academicHelper->findClassSectionAdditionalSubjectList($section, $batch, $campus, $institute);


        $studentList = $this->studentProfileView->where([
            'batch' => $batch, 'section' => $section, 'academic_level' => $level, 'campus' => $campus, 'institute' => $institute
        ])->orderByRaw('LENGTH(gr_no) asc')->orderBy('gr_no', 'asc');

        // checking student
//        if($stdId){
//            $studentList->where(['std_id'=>$myStdId]);
//        }

        $studentList = $studentList->get();

        // tabulation sheet
        $tabulationSheet = $this->assessmentsController->getTabulationMarkSheetForCollege($section, $batch, $category, $semester, $additionalSubArrayList, $gradeScaleDetails, $campus, $institute, null, null, null, null);


        return view('reports::pages.report.college-result.tutorial-exam', compact('catDetailArrayList', 'tabulationSheet', 'classSubGroupArrayList', 'myStdId', 'category', 'studentList', 'instituteInfo', 'acdemicYear', 'category_name'));
    }


    public function testExamReport()
    {
        return view('reports::pages.report.college-result.test-exam');
    }

    public function hscExamReport(Request $request)
    {
        $instituteInfo = $this->academicHelper->getInstituteProfile();
        $acdemicYear = $this->academicHelper->getAcademicYearProfile();
        //        return $request->all();
//        $level = $request->academic_level;
//        $batch = $request->batch;
//        $section =  $request->section;
//        $semester =$request->semester;
//        $category = $request->category;


//        $level = 68;
//        $batch = 218;
        $semester = $request->semester;
        $category = $request->category;
        $categoryName = $request->category_name;
        $semesterName = $request->semester_name;

        // institute and campus
        $institute = $this->academicHelper->getInstitute();
        $campus = $this->academicHelper->getCampus();
        $allBatch = $this->academicHelper->getBatchList();


        $boardTypeResultList = array();
        // sublist array list
        foreach ($allBatch as $batchId => $groupSubDetail) {
            // student list
            $boardTypeResultList[$batchId]['student'] = $this->stdArrayMaker($this->studentProfileView->where([
                'batch' => $batchId, 'campus' => $campus, 'institute' => $institute
            ])->orderByRaw('LENGTH(gr_no) asc')->orderBy('gr_no', 'asc')->get(['std_id', 'gr_no']));
            // scale id
            $scaleId = $this->assessmentsController->getGradeScaleId($batchId, null);
            // grade scale scale details
            $gradeScaleDetails = $this->gradeDetails->where('grade_id', $scaleId)->orderBy('sorting_order', 'ASC')->get();
            // additional subject list
            $additionalSubArrayList = $this->academicHelper->findClassSectionAdditionalSubjectList(null, $batchId, $campus, $institute);
            // tabulation sheet
            $boardTypeResultList[$batchId]['result'] = $this->assessmentsController->getTabulationMarkSheetForCollege(null, $batchId, $category, $semester, $additionalSubArrayList, $gradeScaleDetails, $campus, $institute, null, null, 'BOARD_RESULT', null);
        }

        return view('reports::pages.report.college-result.hscexam-report', compact('boardTypeResultList', 'allBatch', 'categoryName', 'instituteInfo', 'acdemicYear', 'semesterName'));
    }


    public function stdArrayMaker($studentList)
    {

        $studentArrayList = [];

        foreach ($studentList as $std) {
            $studentArrayList[$std->std_id] = $std->gr_no;
        }

        return $studentArrayList;
    }

    // SUMMERY_RESULT


}
