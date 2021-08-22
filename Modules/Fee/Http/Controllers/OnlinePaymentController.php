<?php

namespace Modules\Fee\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Setting\Entities\Country;
use Modules\Setting\Entities\Campus;
use Modules\Fee\Entities\FeeInvoice;
use Illuminate\Support\Facades\Session;

class OnlinePaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */

    private $country;
    private $campus;
    private $feeInvoice;

    public function __construct(FeeInvoice $feeInvoice, Campus $campus, Country $country)
    {
        $this->middleware('auth');
        $this->country = $country;
        $this->campus = $campus;
        $this->feeInvoice = $feeInvoice;
    }
    
    public function onlinePaymentRequest(Request $request)
    {
        session(['stores' => $request->all()]);
        $feeInvoiceObj = $this->feeInvoice->find($request->invoice_id);
        $feeHeadName = $feeInvoiceObj->feehead()->name;
        $feeSubHeadName = $feeInvoiceObj->subhead()->name;
        $sectionName = $feeInvoiceObj->section()->section_name;

        $studentProfileViewObj = $feeInvoiceObj->studentProfile();
        $studentFirstName = $studentProfileViewObj->first_name;
        $studentMiddleName = $studentProfileViewObj->middle_name;
        $studentLastName = $studentProfileViewObj->last_name;
        $studentEmail = $studentProfileViewObj->email;
        $studentPhone = $studentProfileViewObj->student()->phone;
        $className = $studentProfileViewObj->batch()->batch_name;
        $levelName = $studentProfileViewObj->level()->level_name;

        $campusObj = $this->campus->find($feeInvoiceObj->campus_id);
        $campusName = $campusObj->name;

        $instituteObj = $campusObj->institute();
        $instituteName = $instituteObj->institute_name;

        $instituteAddressObj = $campusObj->address();
        $instituteCityID = $instituteAddressObj->city_id;
        $instituteStateID = $instituteAddressObj->state_id;
        $instituteCountryID = $instituteAddressObj->country_id;
        $instituteZipCode = $instituteAddressObj->zip;

        $instituteCountryObj = $this->country->find($instituteCountryID);
        $instituteCountryName = $instituteCountryObj->name;

        $instituteStateObj = $instituteCountryObj->allState()->where('id', $instituteStateID)->first();
        $instituteStateName = $instituteStateObj->name;

        $instituteCityObj = $instituteStateObj->allCity()->where('id', $instituteCityID)->first();
        $instituteCityName = $instituteCityObj->name;

        $url = "https://sandbox.easypayway.com/payment/request.php";     //sandbox
        $fields = array(
            'store_id' => 'epw',
            'signature_key' => 'dc0c2802bf04d2ab3336ec21491146a3',
            'amount' => $request->paid_amount,
            //'payment_type' => 'VISA',
            'currency' => 'BDT',
            'tran_id' => bin2hex(openssl_random_pseudo_bytes(12)),
            'cus_name' => $studentFirstName.' '.$studentMiddleName.' '.$studentLastName,
            'cus_email' => $studentEmail,
            'cus_city' => $instituteCityName, 'cus_state' => $instituteStateName,
            'cus_postcode' => $instituteZipCode,
            'cus_country' => $instituteCountryName, 'cus_phone' => $studentPhone,
            'cus_fax' => 'Not¬Applicable', 'ship_name' => 'Not¬Applicable',
            'ship_add1' => 'Not¬Applicable', 'ship_add2' => 'Not¬Applicable',
            'ship_city' => 'Not¬Applicable', 'ship_state' => 'Not¬Applicable',
            'ship_postcode' => 'Not¬Applicable', 'ship_country' => 'Not¬Applicable',
            'desc' => $levelName.'-'.$feeHeadName.'-'.$feeSubHeadName,
            'success_url' => url("/fee/student/onlinepayment/callback"),
            'fail_url' => url("/fee/student/onlinepayment/callback"),
            'cancel_url' => url("/fee/student/onlinepayment/callback"),
            'opt_a' => 'Institute: '.$instituteName, 'opt_b' => 'Campus: '.$campusName,
            'opt_c' => 'Section: '.$sectionName, 'opt_d' => 'Class: '.$className);
        $fields_string = "";
        foreach ($fields as $key => $value) {
            $fields_string .= $key . '=' . $value . '&';
        }
        $fields_string = rtrim($fields_string, '&');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        
        curl_close($ch);
        $resultData = json_decode($result, true);
        
        if (!is_array($resultData) && strpos($resultData, 'easypayway.com') !== false) {
            print_r($resultData);
            return redirect()->away($resultData);
        } else {

            //$request->session()->flash('message', array_values($resultData)[0]);
            //$request->session()->flash('alert-class', 'alert-danger');
            Session::flash('message', array_values($resultData)[0]);
            Session::flash('alert-class', 'alert-danger');
            return back()->withInput();
        }
    }

    public function onlinePaymentCallback(Request $request)
    {
        $params = $request->all();
		
        if ($params['pay_status'] == 'Successful') {
            
			//$url = "https://securepay.easypayway.com/api/v1/trxcheck/request.php?store_id=epw&signature_key=dc0c2802bf04d2ab3336ec21491146a3&type=json&request_id=" . $params['mer_txnid'];   //live
            
			$url = "https://sandbox.easypayway.com/api/v1/trxcheck/request.php?store_id=epw&signature_key=dc0c2802bf04d2ab3336ec21491146a3&type=json&request_id=" . $params['mer_txnid'];    //sandbox
			
			$curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                echo "cURL Error #:" . $err;
            } else {

                $resultData = json_decode($response, true);
            }

            if($resultData['pay_status'] == 'Successful'){
                Session::flash('message', 'Payment Successful.');
                Session::flash('resultData', $resultData);
                Session::flash('alert-class', 'alert-info');
            } else{
                Session::flash('message', 'Payment failed.');
                Session::flash('alert-class', 'alert-danger');
            }

        } else{
            Session::flash('message', 'Payment failed.');
            Session::flash('alert-class', 'alert-danger');
        }

        return redirect()->route('paymentStatus')->with('store', session('stores'));
    }

    public function onlinePaymentStatus()
    {
        if(Session::has('message'))
        {
            $resultData = Session::get('resultData');
            return view('fee::onlinepayment.onlinepayment-status', ['resultData' => $resultData ]);
        }
    }
}
