<?php

namespace Modules\Fees\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Fees\Entities\PaymentExtra;
use Modules\Academics\Entities\AcademicsYear;
use Modules\Academics\Entities\AcademicsLevel;
use Modules\Academics\Entities\Batch;
use Modules\Academics\Entities\Section;
use Modules\Student\Entities\StudentEnrollment;
use App\Http\Controllers\Helpers\AcademicHelper;


class PaymentExtraController extends Controller
{


    private  $batch;
    private  $section;
    private  $paymentExtra;
    private  $academicsYear;
    private  $academicsLevel;
    private  $studentEnrollment;
    private  $academicHelper;


    public function __construct( AcademicHelper $academicHelper, AcademicsLevel $academicsLevel, StudentEnrollment $studentEnrollment, AcademicsYear $academicsYear,PaymentExtra $paymentExtra,Batch $batch,Section $section)

    {
        $this->batch                  = $batch;
        $this->section                = $section;
        $this->academicsYear           = $academicsYear;
        $this->paymentExtra           = $paymentExtra;
        $this->academicsLevel           = $academicsLevel;
        $this->studentEnrollment           = $studentEnrollment;
        $this->academicHelper           = $academicHelper;


    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function searchPaymentExtra(Request $request)
    {

        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();


//        return $request->all();
        $studentIdlist = array();

        $studentId=$request->input('std_id');
        $batch = $request->input('batch');
        $section = $request->input('section');
        $academicLevel = $request->input('academic_level');

        $allSearchInputs = array();
        // check fees_id

        if (!empty($batch) && !empty($section)) {
            $std_enrollments = $this->studentEnrollment->where(['academic_level' => $academicLevel, 'batch' => $batch, 'section' => $section])->get();
            $data = array();
            $i = 1;
            if ($std_enrollments) {
                $studentIdlist = array();
                foreach ($std_enrollments as $enrollment) {
                    $studentinfo = $enrollment->student();
                    $studentIdlist[] = $studentinfo->id;
                }

            }
        }

        if(!empty($studentId)){
            $extraPaymentList = $this->paymentExtra->where('student_id', $studentId)->where('institution_id',$instituteId)->where('campus_id',$campus_id)->paginate(2);
        }

          else {
              $extraPaymentList = $this->paymentExtra->whereIn('student_id', $studentIdlist)->where('institution_id',$instituteId)->where('campus_id',$campus_id)->paginate(2);

          }

        if ($extraPaymentList) {

            return view('fees::pages.modal.extrapayment_amount', compact('extraPaymentList'));

        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function advancePaymentModal()
    {
        return view('fees::pages.modal.advance_payment');
    }

    public function advancePaymentStore(Request $request){
        return $request->all();
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
