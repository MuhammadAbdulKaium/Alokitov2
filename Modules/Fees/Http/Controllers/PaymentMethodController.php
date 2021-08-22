<?php

namespace Modules\Fees\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Fees\Entities\PaymentMethod;
use App\Http\Controllers\Helpers\AcademicHelper;



class PaymentMethodController extends Controller
{
    private  $paymentMethod;

    public function __construct( PaymentMethod $paymentMethod, AcademicHelper $academicHelper)
    {
        $this->paymentMethod       = $paymentMethod;
        $this->academicHelper       = $academicHelper;

    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

        $methodList= $this->paymentMethod->where('institution_id',$instituteId)->where('campus_id',$campus_id)->get();
        return view('fees::pages.payment.payment_method',compact('methodList'))->with('page', '');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {

        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();

    //   store payment method name
        $paymentMethod=new $this->paymentMethod;
        $paymentMethod->institution_id=$instituteId;
        $paymentMethod->campus_id=$campus_id;
        $paymentMethod->method_name=$request->input('method_name');
        $paymentMethod->save();

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
