<?php

namespace Modules\Admission\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admission\Entities\ApplicantAddress;
use Modules\Admission\Entities\ApplicantDocument;
use Modules\Admission\Entities\ApplicantEnrollment;
use Modules\Admission\Entities\ApplicantInformation;
use Modules\Admission\Entities\ApplicantManageView;
use Modules\Admission\Entities\ApplicantUser;
use Modules\Admission\Entities\ApplicantFees;
use Modules\Admission\Entities\ApplicantExamSetting;
use Modules\Setting\Entities\Country;
use App;
use Excel;
use Modules\Academics\Entities\AcademicsYear;

class ApplicantFeesController extends Controller
{

    private $academicHelper;
    private $applicant;
    private $address;
    private $document;
    private $enrollment;
    private $personalInfo;
    private $country;
    private $applicantView;
    private $applicantFees;
    private $examSetting;
    private $academicsYear;

    // constructor
    public function __construct(AcademicHelper $academicHelper, ApplicantManageView $applicantView, ApplicantUser $applicant, ApplicantAddress $address, ApplicantDocument $document, ApplicantEnrollment $enrollment, ApplicantInformation $personalInfo, Country $country, ApplicantFees $applicantFees, ApplicantExamSetting $examSetting, AcademicsYear $academicsYear)
    {
        $this->academicHelper = $academicHelper;
        $this->applicant      = $applicant;
        $this->address        = $address;
        $this->document       = $document;
        $this->enrollment     = $enrollment;
        $this->personalInfo   = $personalInfo;
        $this->country        = $country;
        $this->applicantView  = $applicantView;
        $this->applicantFees  = $applicantFees;
        $this->examSetting  = $examSetting;
        $this->academicsYear  = $academicsYear;
    }

    // fees index
    public function index()
    {
        // academic year
        //$academicYears = $this->academicHelper->getAllAcademicYears();
        // academic year list
        $academicYears =  $this->academicsYear->where([
            'institute_id'=>$this->academicHelper->getInstitute(),
            'campus_id'=>$this->academicHelper->getCampus(),
        ])->get();
        // return view with variable
        return view('admission::admission-fees.fees', compact('academicYears'));
    }

    // add fees
    public function showFees($applicantId)
    {
        $applicantProfile = $this->applicantView->where(['applicant_id'=>$applicantId])->first();
        // return view with variable
        return view('admission::admission-fees.modals.fees-add', compact('applicantProfile'));
    }

    // store fees
    public function storeFees(Request $request)
    {
        // request details
        $feesAmount = $request->input('fees_amount');
        $applicantId = $request->input('applicant_id');
        $campusId = $this->academicHelper->getCampus();
        $instituteId = $this->academicHelper->getInstitute();

        // applicant profile
        $applicantProfile = $this->applicant->find($applicantId);
        // applicant enroll details
        $enrollment = $applicantProfile->enroll();
        if($applicantProfile){
            // applicantFeesProfile
            $applicantFeesProfile = new $this->applicantFees();
            // input details
            $applicantFeesProfile->applicant_id = $applicantId;
            $applicantFeesProfile->fees_amount = $feesAmount;
            $applicantFeesProfile->batch = $enrollment->batch;
            $applicantFeesProfile->academic_level = $enrollment->academic_level;
            $applicantFeesProfile->academic_year = $enrollment->academic_year;
            $applicantFeesProfile->campus_id = $campusId;
            $applicantFeesProfile->institute_id = $instituteId;
            $applicantFeesProfile->invoice_no = $campusId.mt_rand(10000, (date('YmdHi')-date('YmdHi', strtotime('-30 days'))));
            // save fees profile
            if($applicantFeesProfile->save()){
                // update application status
                $applicantProfile->payment_status = 1;
                $applicantProfile->application_status = 1;
                // save application status
                if($applicantProfile->save()){
                    return [
                        'status'=>'success',
                        'msg'=>'Applicant Fees Added Successfully',
                        'applicant_id'=>$applicantProfile->id,
                        'fees_payment_date'=>date('d M, Y', strtotime($applicantFeesProfile->created_at))
                    ];
                }else{
                    return ['status'=>'failed', 'msg'=>'unable to update applicant payment status'];
                }
            }else{
                return ['status'=>'failed', 'msg'=>'unable to add applicant fees'];
            }
        }else{
            return ['status'=>'failed', 'msg'=>'applicant profile not found'];
        }
    }

    public function downloadInvoice($applicantId)
    {
        // applicant profile
        $applicantProfile = $this->applicant->find($applicantId);
        //institute profile
        $institute = $this->academicHelper->getInstituteProfile();
        // share all variables with the view
        view()->share(compact('institute', 'applicantProfile'));
        // generate pdf
        $pdf = App::make('dompdf.wrapper');
        // load view
        $pdf->loadView('admission::admission-fees.reports.report-fees-invoice')->setPaper('a4', 'portrait');
//         return $pdf->stream();
        return $pdf->download('application_no_'.$applicantProfile->application_no.'.pdf');

    }


    public function findFees(Request $request)
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
        // institute details
        $qry['campus_id'] = $this->academicHelper->getCampus();
        $qry['institute_id'] = $this->academicHelper->getInstitute();
        // applicant enquiry
        $applicantEnquiry =  $this->applicantView->where($qry)->orderBy('application_no', 'ASC')->get();
        // return view with variable
        return view('admission::admission-fees.modals.fees', compact('applicantEnquiry'));
    }

}
