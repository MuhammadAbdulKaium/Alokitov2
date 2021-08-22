<?php

namespace Modules\Admission\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Admission\Entities\ApplicantAddress;
use Modules\Admission\Entities\ApplicantDocument;
use Modules\Admission\Entities\ApplicantEnrollment;
use Modules\Admission\Entities\ApplicantInformation;
use Modules\Admission\Entities\ApplicantExamSetting;
use Modules\Admission\Entities\ApplicantFees;
use Modules\Admission\Entities\ApplicantResult;
use Modules\Admission\Entities\ApplicantUser;
use Modules\Admission\Entities\ApplicantManageView;
use Modules\Admission\Entities\ApplicantGrade;
use Modules\Setting\Entities\Country;
use Illuminate\Support\Facades\View;
use Redirect;
use Session;
use Validator;
use Modules\Academics\Entities\AcademicsYear;

use MPDF;
use App;

class AdmissionController extends Controller
{

    private $academicHelper;
    private $applicant;
    private $address;
    private $document;
    private $enrollment;
    private $personalInfo;
    private $country;
    private $applicantView;
    private $applicantGrade;
    private $applicantResult;
    private $applicantFees;
    private $applicantExamSetting;
    private $academicsYear;

    // constructor
    public function __construct(AcademicHelper $academicHelper, ApplicantManageView $applicantView, ApplicantUser $applicant, ApplicantAddress $address, ApplicantDocument $document, ApplicantEnrollment $enrollment, ApplicantInformation $personalInfo, Country $country, ApplicantGrade $applicantGrade, ApplicantExamSetting $applicantExamSetting, ApplicantFees $applicantFees, ApplicantResult $applicantResult, AcademicsYear $academicsYear)
    {
        $this->academicHelper = $academicHelper;
        $this->applicant      = $applicant;
        $this->address        = $address;
        $this->document       = $document;
        $this->enrollment     = $enrollment;
        $this->personalInfo   = $personalInfo;
        $this->country        = $country;
        $this->applicantView  = $applicantView;
        $this->applicantGrade = $applicantGrade;
        $this->applicantResult = $applicantResult;
        $this->applicantFees = $applicantFees;
        $this->applicantExamSetting = $applicantExamSetting;
        $this->academicsYear = $academicsYear;
    }

    // index page
    public function index()
    {
        return view('admission::index');
    }

    //////////////////////////////  manage enquiry ////////////////////////
    public function manageEnquiry()
    {
        // academic year
        // $academicYears = $this->academicHelper->getAllAcademicYears();
        // academic year list
        $academicYears =  $this->academicsYear->where([
            'institute_id'=>$this->academicHelper->getInstitute(),
            'campus_id'=>$this->academicHelper->getCampus(),
        ])->get();
        // return view with variable
        return view('admission::admission-enquiry.enquiry', compact('academicYears'));
    }


    public function applicantdownlaodView()
    {
        $academicYears =  $this->academicsYear->where([
            'institute_id'=>$this->academicHelper->getInstitute(),
            'campus_id'=>$this->academicHelper->getCampus(),
        ])->get();
        // return view with variable
        return view('admission::admission-enquiry.applicant-download', compact('academicYears'));
    }

    public function applicantDownlaod(Request $request){
        $batchName=$request->batch_name;

        $instituteInfo=$this->academicHelper->getInstituteProfile();
        // making qry
        $qry = array();
        // input qry details
        if($academicYear = $request->input('academic_year', null)){
            $qry['academic_year']= $academicYear;
        }
        if($academicLevel = $request->input('academic_level', null)){
            $qry['academic_level']= $academicLevel;
        }
        if($academicBatch = $request->input('batch', null)){
            $qry['batch']= $academicBatch;
        }
        if($applicationNo = $request->input('application_no', null)){
            $qry['application_no']= $applicationNo;
        }
        if($applicantEmail = $request->input('applicant_email', null)){
            $qry['email']= $applicantEmail;
        }
        if($applicantDob = $request->input('applicant_dob', null)){
            $qry['birth_date']= date('Y-m-d', strtotime($applicantDob));
        }
        if($applicantStatus = $request->input('applicant_status', null)){
            $qry['application_status']= $applicantStatus;
        }
        // institute details
        $qry['campus_id'] = $this->academicHelper->getCampus();
        $qry['institute_id'] = $this->academicHelper->getInstitute();
        // applicant enquiry
        $applicantEnquiry =  $this->applicantView->where($qry)->orderBy('application_no', 'ASC')->get();
        return view('admission::admission-enquiry.report.applicant',compact('instituteInfo','applicantEnquiry','batchName'));

    }



    // applicant sit plan
    public function applicantSitPlandownlaodView()
    {
        $academicYears =  $this->academicsYear->where([
            'institute_id'=>$this->academicHelper->getInstitute(),
            'campus_id'=>$this->academicHelper->getCampus(),
        ])->get();
        // return view with variable
        return view('admission::admission-enquiry.applicant-sitplan', compact('academicYears'));
    }

    public function applicantSitPlanDownlaod(Request $request){
        $batchName=$request->batch_name;

        $instituteInfo=$this->academicHelper->getInstituteProfile();
        // making qry
        $qry = array();
        // input qry details
        if($academicYear = $request->input('academic_year', null)){
            $qry['academic_year']= $academicYear;
        }
        if($academicLevel = $request->input('academic_level', null)){
            $qry['academic_level']= $academicLevel;
        }
        if($academicBatch = $request->input('batch', null)){
            $qry['batch']= $academicBatch;
        }
        if($applicationNo = $request->input('application_no', null)){
            $qry['application_no']= $applicationNo;
        }
        if($applicantEmail = $request->input('applicant_email', null)){
            $qry['email']= $applicantEmail;
        }
        if($applicantDob = $request->input('applicant_dob', null)){
            $qry['birth_date']= date('Y-m-d', strtotime($applicantDob));
        }
        if($applicantStatus = $request->input('applicant_status', null)){
            $qry['application_status']= $applicantStatus;
        }
        // institute details
        $qry['campus_id'] = $this->academicHelper->getCampus();
        $qry['institute_id'] = $this->academicHelper->getInstitute();
        // applicant enquiry
        $applicantEnquiry =  $this->applicantView->where($qry)->orderBy('application_no', 'ASC')->get();
        return view('admission::admission-enquiry.report.sitplan',compact('instituteInfo','applicantEnquiry','batchName'));

    }



    public function findApplicant(Request $request)
    {
        // making qry
        $qry = array();
        // input qry details
        if($academicYear = $request->input('academic_year', null)){
            $qry['academic_year']= $academicYear;
        }
        if($academicLevel = $request->input('academic_level', null)){
            $qry['academic_level']= $academicLevel;
        }
        if($academicBatch = $request->input('batch', null)){
            $qry['batch']= $academicBatch;
        }
        if($applicationNo = $request->input('application_no', null)){
            $qry['application_no']= $applicationNo;
        }
        if($applicantEmail = $request->input('applicant_email', null)){
            $qry['email']= $applicantEmail;
        }
        if($applicantDob = $request->input('applicant_dob', null)){
            $qry['birth_date']= date('Y-m-d', strtotime($applicantDob));
        }
        if($applicantStatus = $request->input('applicant_status', null)){
            $qry['application_status']= $applicantStatus;
        }
        // institute details
        $qry['campus_id'] = $this->academicHelper->getCampus();
        $qry['institute_id'] = $this->academicHelper->getInstitute();
        // applicant enquiry
        $applicantEnquiry =  $this->applicantView->where($qry)->orderBy('application_no', 'ASC')->get();

        // return view with variable
        return view('admission::admission-enquiry.modals.enquiry', compact('applicantEnquiry'));
    }


    public function admissionReports(Request $request)
    {
        // campus and institute details
        $campus = $this->academicHelper->getCampus();
        $institute = $this->academicHelper->getInstitute();
        // input details
        $batch = $request->input('batch', null);
        $academicYear = $request->input('academic_year', null);
        $academicLevel = $request->input('academic_level', null);
        // institute information
        $instituteInfo = $this->academicHelper->getInstituteProfile();
        // query maker
        $qry = ['campus_id'=>$campus, 'institute_id'=>$institute];
        // find level list
        $academicLevelList = $this->academicHelper->getAllAcademicLevel();

        ////////// calculate admission report summary //////////
        $admissionSummary = array();
        // academic year looping
        foreach ($academicLevelList as $levelProfile){
            // find batch list form level profile
            $academicBatchList = $levelProfile->batch();
            // checking academic level
            if($academicBatchList->count()>0 AND $academicLevel AND ($academicLevel!=$levelProfile->id)) continue;
            // academic year batch list looping
            foreach ($academicBatchList as $batchProfile){
                // checking academic batch
                if($batchProfile AND $batch AND ($batch!=$batchProfile->id)) continue;
                // batch name
                if($division = $batchProfile->division()){
                    $batchName = $batchProfile->batch_name.' ('.$division->name.')';
                }else{
                    $batchName = $batchProfile->batch_name;
                }
                // query details
                $myQry['batch'] = $batchProfile->id;
                $myQry['academic_level'] = $levelProfile->id;
                $myQry['academic_year'] = $academicYear;
                // find applicant list
                $enrollmentList = $this->enrollment->where($myQry)->get();
                // total applicant list
                $applicantResultList = $this->applicantResult->where($qry)->where($myQry)->get();
                // applicant exam setting
                $examSetting = $this->applicantExamSetting->where($qry)->where($myQry)->first();
                // checking institute and campus for applicant exam fees
                if($institute==29 && $campus==31){
                    $feesList = DB::table('applicant_user as u')
                        ->join('applicant_enrollment  as e', 'u.id', '=', 'e.applicant_id')
                        ->select('u.id as id', 'u.application_no as application_no', 'u.payment_status as payment_status', 'e.batch as batch', 'e.academic_level as academic_level', 'e.academic_year as academic_year', 'u.campus_id as campus_id', 'u.institute_id as institute_id')
                        ->where(['e.batch'=>$batchProfile->id, 'e.academic_level'=>$levelProfile->id,'e.academic_year'=>$academicYear])
                        ->where(['u.payment_status'=>1,'u.campus_id'=>$campus, 'u.institute_id'=>$institute])
                        ->get();
                }else{
                    // applicant exam fees
                    $feesList = $this->applicantFees->where($qry)->where($myQry)->get();
                }
                // exam fee
                $myExamFee = $examSetting?$examSetting->exam_fees:0;
                // report summary data input
                $admissionSummary[$levelProfile->id][$batchProfile->id] = [
                    'batch_name' =>$batchName,
                    'total_application' =>$enrollmentList->count(),
                    'paid_application' =>$feesList->count(),
                    'exam_fee' => $myExamFee,
                    'fees_collected' => ($institute==29 && $campus==31)?($myExamFee*$feesList->count()):($feesList->sum('fees_amount')),
                    'passed' => $applicantResultList->sum('applicant_exam_result'),
                    'admitted' => $applicantResultList->sum('admission_status'),
                ];
            }
        }
        // share all variables with the view
        view()->share(compact('instituteInfo','admissionSummary', 'academicLevelList'));
        // generate pdf
        $pdf = App::make('dompdf.wrapper');
        // load view
        $pdf->loadView('admission::application.reports.report-admission')->setPaper('a4', 'portrait');
        return $pdf->stream();
        // return $pdf->download('application_no');
    }


    // admission from download

    public function  admissionFormDownload(){
//        view()->share(compact('fpt_list', 'instituteInfo','report_type'));

        //generate PDf
//        $pdf = App::make('dompdf.wrapper');
//        $pdf->loadView('admission::application.admission-form')->setPaper('a4', 'portrait');
//        // return $pdf->stream();
//        $downloadFileName = "admission-form.pdf";
//        return $pdf->download($downloadFileName);

        $institute=$this->academicHelper->getInstituteProfile();

        view()->share(compact('institute'));
        $pdf = App::make('mpdf.wrapper');
        $pdf->loadView('admission::application.admission-form');
        $view = View::make('admission::application.admission-form');
        $html = $view->render();
        $mpdf = new MPDF('utf-8',   'Legal', 14,'SolaimanLipi','10','5','5','0');
        $mpdf->autoScriptToLang = true;// Mandatory
        $mpdf->autoLangToFont = true;//Mandatory
        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }


}
