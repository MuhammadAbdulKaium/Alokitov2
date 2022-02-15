<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\SubscriptionManagementTransaction;
use Modules\Setting\Entities\Institute;
use Modules\Admin\Emails\SubscriptionManagementMail;
use Illuminate\Support\Facades\Mail;

class SubscriptionManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */

    private $smt;
    private $institute;

    public function __construct(SubscriptionManagementTransaction $smt, Institute $institute)
    {
        $this->smt = $smt;
        $this->institute = $institute;
    }

    public function processSubscriptionManagement(Request $request)
    {
//        return $request;
        $chkBoxSelected = $request->chkbox;
        $datas = array();
        $cnt = 1;

        if(is_array($chkBoxSelected )) {
            foreach($chkBoxSelected as $k=>$v)
            {
                foreach($v as $x=>$y)
                {
                    $billStatus= SubscriptionManagementTransaction::where('id',$y)->first();
                    $billStatus->update(['status'=>"paid"]);
                }
//            $billStatus= SubscriptionManagementTransaction::where('id',$v)->find();
//            return $billStatus;
//                foreach($v as $x=>$y)
//                {
//                        $smtObj = $this->smt->findOrFail($y);
//                        $instID = $smtObj->billingInfo->institute_id;
//                        $camID = $smtObj->billingInfo->campus_id;
//                        $institute = $this->institute->findOrFail($instID);
//                        $campus = $institute->campus()->find($camID);
//
//                    $instEmail = $institute->email;
//
//                    $instPhones = $institute->phone;
//                    $instPhones = explode(',', $instPhones);
//                        if(is_array($instPhones)) {
//                            foreach($instPhones as $k=>$instPhone) {
//                                $getInstPhone = preg_replace('/[^\d\+]/', '', $instPhone);
//                                $getInstPhone = preg_replace('/\D+$/', '', $getInstPhone);
//                                $instPhones[$k] = $getInstPhone;
//                                //$instPhone[$k] = preg_replace('/[^0-9]/', '', $instPhone);
//                            }
//                        }
//
//                    $insName = $institute->institute_name;
//                    $camName = $campus->name;
//
//                        $totalAmount = $smtObj->billingInfo->total_amount;
//                        $acceptedAmount = $smtObj->billingInfo->accepted_amount;
//                    if(isset($acceptedAmount)) {
//                        $finalAmount = $acceptedAmount;
//                    } else {
//                        $finalAmount = $totalAmount;
//                    }
//
//                        $totalSmsPrice = $smtObj->billingInfo->total_sms_price;
//                        $acceptedSmsPrice = $smtObj->billingInfo->accepted_sms_price;
//                    if(isset($acceptedSmsPrice)) {
//                        $finalSmsPrice = $acceptedSmsPrice;
//                    } else {
//                        $finalSmsPrice = $totalSmsPrice;
//                    }
//
//                    $newDues = $smtObj->new_dues;
//
//                    $oldDues = $smtObj->old_dues;
//
//                    $monthlyTotalCharge = $smtObj->monthly_total_charge;
//
//                    $paidAmount = $smtObj->paid_amount;
//
//                    $month = $smtObj->billingInfo->month;
//
//                    $year = $smtObj->billingInfo->year;
//
//                    $datas[$cnt]["billingID"]            = $smtObj->institute_billing_info_id;
//                    $datas[$cnt]['transactionID']        = $smtObj->id;
//                    $datas[$cnt]['instituteID']          = $institute->id;
//                    $datas[$cnt]['campusID']             = $campus->id;
//                    $datas[$cnt]['instituteName']        = $insName;
//                    $datas[$cnt]['campusName']           = $camName;
//                    $datas[$cnt]['instituteEmail']       = $instEmail;
//                    $datas[$cnt]['institutePhones']      = $instPhones;
//                    $datas[$cnt]['year']                 = $year;
//                    $datas[$cnt]['month']                = $month;
//                    $datas[$cnt]['totalAmount']          = $finalAmount;
//                    $datas[$cnt]['totalSmsPrice']        = $finalSmsPrice;
//                    $datas[$cnt]['oldDues']              = $oldDues;
//                    $datas[$cnt]['monthlyTotalCharge']   = $monthlyTotalCharge;
//                    $datas[$cnt]['paidAmount']           = $paidAmount;
//                    $datas[$cnt]['newDues']              = $newDues;
//
//                    $cnt++;
//                }

            }
        }

//        foreach($datas as $data)
//        {
//            $this->sendForMailProcessing($data);
//        }

        return redirect()->back();
    }

    public function sendForMailProcessing($data)
    {
        event(Mail::to('saifulmasud@hotmail.com')->send(new SubscriptionManagementMail($data)));
    }


    public function store(Request $request)
    {
        //
    }
    
    /*
    public function index()
    {
        return view('admin::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */

    /*
    public function create()
    {
        return view('admin::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */

    /*
    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */

    /*
    public function show($id)
    {
        return view('admin::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */

    /*
    public function edit($id)
    {
        return view('admin::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */

    /*
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */

    /*
    public function destroy($id)
    {
        //
    }
    */
}
