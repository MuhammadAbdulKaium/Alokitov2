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
use Modules\Admission\Entities\ApplicantManageView;
use Modules\Admission\Entities\ApplicantUser;
use Modules\Academics\Entities\ReportCardSetting;
use Modules\Setting\Entities\Country;
use Modules\Setting\Entities\Institute;
use Modules\Setting\Http\Controllers\UserInstitutionController;
use Mpdf\Output\Destination;
use Redirect;
use Session;
use Validator;
use App;
use Excel;
use MPDF;
use Illuminate\Support\Facades\View;

class ApplicationController extends Controller
{

    private $academicHelper;
    private $applicant;
    private $applicantView;
    private $address;
    private $document;
    private $enrollment;
    private $personalInfo;
    private $country;
    private $reportCardSetting;

    // constructor
    public function __construct(AcademicHelper $academicHelper, ApplicantUser $applicant, ApplicantManageView $applicantView, ApplicantAddress $address, ApplicantDocument $document, ApplicantEnrollment $enrollment, ApplicantInformation $personalInfo, Country $country, ReportCardSetting $reportCardSetting)
    {
        $this->academicHelper = $academicHelper;
        $this->applicant      = $applicant;
        $this->applicantView      = $applicantView;
        $this->address        = $address;
        $this->document       = $document;
        $this->enrollment     = $enrollment;
        $this->personalInfo   = $personalInfo;
        $this->country        = $country;
        $this->reportCardSetting = $reportCardSetting;
    }

    // online application form
    public function index()
    {
        // academic year
        $academicYears = $this->academicHelper->getAllAcademicYears();
        // all country
        $allCountry = $this->country->orderBy('name', 'ASC')->get();
        // return view with all variables
        return view('admission::application.application-form', compact('academicYears', 'allCountry'));
    }


    public function downloadApplication($applicantId)
    {
        // application new profile
        $applicantProfile = $this->applicant->find($applicantId);
//        return $applicantProfile->personalInfo();
        $instituteId= $applicantProfile->institute_id;
        //institute profile
        $instituteInfo =Institute::find($instituteId);
        // share all variables with the view
        view()->share(compact('instituteInfo', 'applicantProfile'));

        // use mPDF

        $pdf = App::make('mpdf.wrapper');
        $pdf->loadView('admission::application.reports.report-application');
        $view = View::make('admission::application.reports.report-application');
        $html = $view->render();
        $mpdf = new MPDF('utf-8',   'Legal', 14,'SolaimanLipi','10','5','5','0');
        $mpdf->autoScriptToLang = true;// Mandatory
        $mpdf->autoLangToFont = true;//Mandatory
        $mpdf->WriteHTML($html);
        $mpdf->Output('application_no_'.$applicantProfile->application_no.'.pdf', 'D');

//        // generate pdf
//        $pdf = App::make('dompdf.wrapper');
//        // load view
//        $pdf->loadView('admission::application.reports.report-application')->setPaper('a4', 'portrait');
//        return $pdf->download('application_no_'.$applicantProfile->application_no.'.pdf');
//        return $pdf->stream();
    }


    public function downloadAdmitCard($applicantId)
    {
        // application new profile
        $applicantProfile = $this->applicantView->where(['applicant_id'=>$applicantId])->first();
        $reportCardSetting = $this->reportCardSetting->where(['institute'=>$applicantProfile->institute_id, 'campus'=>$applicantProfile->campus_id])->first();
        //institute profile
        $instituteInfo = $applicantProfile->institute();
        // share all variables with the view
        view()->share(compact('instituteInfo', 'applicantProfile', 'reportCardSetting'));
        // generate pdf
        $pdf = App::make('dompdf.wrapper');
        // load view
        $pdf->loadView('admission::application.reports.report-admit-card')->setPaper('a4', 'portrait');
        return $pdf->download('admit_card_no_'.$applicantProfile->application_no.'.pdf');
        //return $pdf->stream();
    }


    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'title'            => 'required',
            'first_name'       => 'required|max:100',
            'middle_name'      => 'required|max:100',
            'last_name'        => 'required|max:100',
            'gender'           => 'required|max:100',
            'birth_date'       => 'required|max:100',
            'blood_group'      => 'required',
            'phone'            => 'required|max:15',
            'religion'         => 'required|max:100',
            'nationality'      => 'required',
            'academic_year'    => 'required',
            'academic_level'   => 'required',
            'batch'            => 'required|max:100',
            'address'          => 'required',
            'country'          => 'required',
            'state'            => 'required',
            'city'             => 'required',
            'house_no'         => 'required',
            'phone_no'         => 'required',
            'zip'              => 'required|numeric',
            'email'            => 'required|max:100|email|unique:applicant_user',
            'password'         => 'required',
            'confirm_password' => 'required|same:password',
        ]);

        // storing requesting input data
        if ($validator->passes()) {
            // Start transaction!
            DB::beginTransaction();

            // create application profile
            try {
                // institute and campus details
                $campus = $this->academicHelper->getCampus();
                $institute = $this->academicHelper->getInstitute();
                // application new profile
                $applicantProfile = new $this->applicant();
                // input details
                $applicantProfile->email          = $request->input('email');
                $applicantProfile->username          = $request->input('username');
                $applicantProfile->password       = bcrypt($request->input('password'));
                $applicantProfile->campus_id      = $campus;
                $applicantProfile->institute_id   = $institute;
                $applicantProfile->application_no = $institute.sprintf("%09d", mt_rand(10000, (date('YmdHi')-date('YmdHi', strtotime('-30 days')))).$campus);
                // save applicant profile
                $applicantProfileSubmitted = $applicantProfile->save();
            } catch (ValidationException $e) {
                // Rollback and then redirect back to form with errors
                DB::rollback();
                return redirect()->back()->withErrors($e->getErrors())->withInput();
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

            // create applicant profile
            try {
                // applicant new personal profile
                $personalInoProfile = new $this->personalInfo();
                // input details
                $personalInoProfile->applicant_id = $applicantProfile->id;
                $personalInoProfile->title        = $request->input('title');
                $personalInoProfile->first_name   = $request->input('first_name');
                $personalInoProfile->middle_name  = $request->input('middle_name');
                $personalInoProfile->last_name    = $request->input('last_name');
                $personalInoProfile->gender       = $request->input('gender');
                $personalInoProfile->phone        = $request->input('phone');
                $personalInoProfile->birth_date   = date('Y-m-d', strtotime($request->input('birth_date')));
                $personalInoProfile->religion     = $request->input('religion');
                $personalInoProfile->nationality  = $request->input('nationality');
                // save applicant personalInoProfile
                $personalInoProfileSubmitted = $personalInoProfile->save();
            } catch (ValidationException $e) {
                // Rollback and then redirect back to form with errors
                DB::rollback();
                return redirect()->back()->withErrors($e->getErrors())->withInput();
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

            // create applicant enrollment
            try {
                // enrollment new profile
                $enrollmentProfile = new $this->enrollment();
                // input details
                $enrollmentProfile->applicant_id   = $applicantProfile->id;
                $enrollmentProfile->academic_year  = $request->input('academic_year');
                $enrollmentProfile->academic_level = $request->input('academic_level');
                $enrollmentProfile->batch          = $request->input('batch');
                // save enrollment profile
                $enrollmentProfile->save();

            } catch (ValidationException $e) {
                // Rollback and then redirect back to form with errors
                DB::rollback();
                return redirect()->back()->withErrors($e->getErrors())->withInput();
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

            // create applicant address
            try {
                // looping
                for ($i = 0; $i < 2; $i++) {
                    // address type
                    $addressType = ($i == 0 ? 'present' : 'permanent');
                    // address new profile
                    $addressProfile = new $this->address();
                    // input details
                    $addressProfile->applicant_id = $applicantProfile->id;
                    $addressProfile->type         = strtoupper($addressType);
                    $addressProfile->address      = $request->input('address');
                    $addressProfile->city_id      = $request->input('city');
                    $addressProfile->state_id     = $request->input('state');
                    $addressProfile->country_id   = $request->input('country');
                    $addressProfile->zip          = $request->input('zip');
                    $addressProfile->house        = $request->input('house_no');
                    // $addressProfile->street = $request->input('address');
                    $addressProfile->phone = $request->input('phone_no');
                    // save address profile
                    $addressProfileSubmitted = $addressProfile->save();
                }

            } catch (ValidationException $e) {
                // Rollback and then redirect back to form with errors
                DB::rollback();
                return redirect()->back()->withErrors($e->getErrors())->withInput();
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

            // If we reach here, then data is valid and working.
            // Commit the queries!
            DB::commit();
            // success message
            Session::flash('success', 'Application Submitted');
            // redirecting to applicant profile page
            return redirect('/admission/application/' . $applicantProfile->id);
        } else {
            Session::flash('warning', 'Invalid Information');
            // receiving page action
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    // Show the specified resource.
    public function show($id)
    {
        // find applicant profile
        $applicantProfile = $this->applicant->find($id);
        // page type
        $page = 'personal';
        // return assessment view page
        return view('admission::application.personal', compact('applicantProfile', 'page'));
    }

    // show applicant profile by page type
    public function showApplicant($id, $dataType)
    {
        // find applicant profile
        $applicantProfile = $this->applicant->find($id);
        // checking data type requested
        switch ($dataType) {
            case 'personal':
                // page type
                $page = $dataType;
                // return assessment view page
                return view('admission::application.personal', compact('applicantProfile', 'page'));
                break;

            case 'address':
                // page type
                $page = $dataType;
                // return assessment view page
                return view('admission::application.address', compact('applicantProfile', 'page'));
                break;

            case 'apply':
                // page type
                $page = $dataType;
                // return assessment view page
                return view('admission::application.enrollment', compact('applicantProfile', 'page'));
                break;

            case 'document':
                // page type
                $page = $dataType;
                // return assessment view page
                return view('admission::application.document', compact('applicantProfile', 'page'));
                break;
        }
    }

    // edit email
    public function editEmail($id)
    {
        // applicant profile
        $applicantProfile = $this->applicant->find($id);
        // return view with  variable
        return view('admission::application.modals.applicant-email', compact('applicantProfile'));
    }

    // Update the specified resource in storage.
    public function updateEmail(Request $request)
    {
        // applicant id
        $applicantId = $request->input('applicant_id');
        // applicant new profile
        $applicantProfile = $this->applicant->find($applicantId);
        // input details
        $applicantProfile->email = $request->input('email');
        // save applicant profile
        $applicantProfileSubmitted = $applicantProfile->save();
        // checking
        if ($applicantProfileSubmitted) {
            Session::flash('success', 'Applicant Email Updated');
            return redirect()->back();
        } else {
            Session::flash('warning', 'Unable to perform action');
            return redirect()->back();
        }
    }

    // Remove the specified resource from storage.
    public function destroy($id)
    {
        // applicant profile
        $applicantProfile = $this->applicant->find($id);
        // delete applicant profile
        $applicantProfileDeleted = $applicantProfile->delete();
        // checking
        if ($applicantProfileDeleted) {
            Session::flash('success', 'Applicant Deleted');
            return redirect()->back();
        } else {
            Session::flash('warning', 'Unable to perform action');
            return redirect()->back();
        }
    }

    public function downloadAdmissionLetter($applicantId)
    {
        // application new profile
        $applicantProfile = $this->applicantView->where(['applicant_id'=>$applicantId])->first();
        //institute profile
        $instituteInfo = $this->academicHelper->getInstituteProfile();
        // share all variables with the view
        view()->share(compact('instituteInfo', 'applicantProfile'));
        // generate pdf
        $pdf = App::make('dompdf.wrapper');
        // load view
        $pdf->loadView('admission::application.reports.report-admission-letter')->setPaper('a4', 'portrait');
        // return $pdf->stream();
        return $pdf->download('admission_letter_'.$applicantProfile->application_no.'.pdf');    }

}
