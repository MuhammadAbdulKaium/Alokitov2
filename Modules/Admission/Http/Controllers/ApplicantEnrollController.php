<?php

namespace Modules\Admission\Http\Controllers;

use Redirect;
use Session;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Admission\Entities\ApplicantAddress;
use Modules\Admission\Entities\ApplicantDocument;
use Modules\Admission\Entities\ApplicantEnrollment;
use Modules\Admission\Entities\ApplicantInformation;
use Illuminate\Support\Facades\DB;
use Modules\Academics\Entities\AcademicsLevel;
use Modules\Academics\Entities\AcademicsYear;
use Modules\Setting\Entities\Country;
use App\Http\Controllers\Helpers\AcademicHelper;

class ApplicantEnrollController extends Controller
{

    private $academicHelper;
    private $address;
    private $document;
    private $enrollment;
    private $information;
    private $country;
    private $academicsLevel;
    private $academicsYear;

    // constructor
    public function __construct(AcademicHelper $academicHelper, ApplicantAddress $address, ApplicantDocument $document, ApplicantEnrollment $enrollment, ApplicantInformation $information, AcademicsLevel $academicsLevel, AcademicsYear $academicsYear, Country $country)
    {
        $this->academicHelper = $academicHelper;
        $this->address = $address;
        $this->document = $document;
        $this->enrollment  = $enrollment;
        $this->information  = $information;

        $this->country  = $country;
        $this->academicsLevel  = $academicsLevel;
        $this->academicsYear  = $academicsYear;
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('admission::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('admission::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('admission::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('admission::edit');
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
