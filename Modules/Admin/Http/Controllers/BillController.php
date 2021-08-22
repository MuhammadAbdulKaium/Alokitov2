<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Entities\Bill;
use Modules\Admin\Entities\BillingInfo;
use Illuminate\Support\Facades\Session;
use Modules\Communication\Entities\SmsCredit;
use Modules\Communication\Entities\SmsLog;
use Modules\API\Http\Controllers\AcademicAPIController;
use Modules\Admin\Entities\SubscriptionManagementTransaction;

class BillController extends Controller
{

    private $bill;
    private $billingInfo;
    private $academicHelper;
    private  $smsCredit;
    private  $smsLog;
    private $academicApiController;
    private $smts;

    public function __construct(AcademicHelper $academicHelper, Bill $bill, BillingInfo $billingInfo, SmsCredit $smsCredit, SmsLog $smsLog, AcademicAPIController $academicApiController, SubscriptionManagementTransaction $smts )
    {
        $this->bill = $bill;
        $this->billingInfo = $billingInfo;
        $this->academicHelper = $academicHelper;
        $this->smsCredit = $smsCredit;
        $this->smsLog = $smsLog;
        $this->academicApiController = $academicApiController;
        $this->smts = $smts;
    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index($page)
    {
        if(Auth::user()->hasRole(['super-admin'])) {
            // institute list
            $instituteList = $this->academicHelper->getInstituteList();

            // return view with variables
            switch ($page){

                case  'bill-info':
                    // get current academic year bill list
                    $billArrayList = $this->getBillList(null);
                    // return view with variables
                    return view('admin::pages.manage-bill.bill-info', compact('instituteList', 'page', 'billArrayList'));

                case 'manage-bill':
                    // allBillingInfoArrayList
                    $allBillingInfoArrayList = $this->getBillingInfoList();

                    $allBillingInfoArrayListByCampus = $this->getBillingInfoListByCampus();

                    $allInstituteSmsCountByCampus = $this->getInstituteSmsCountByCampus();

                    // return view with all variables
                    return view('admin::pages.manage-bill.manage-bill', compact('instituteList', 'page', 'allBillingInfoArrayList', 'allBillingInfoArrayListByCampus', 'allInstituteSmsCountByCampus'));

                case 'subscription-management':
                    $allBillingInfoArrayList = $this->getBillingInfoList();

                    $allBillingInfoArrayListByCampus = $this->getBillingInfoListByCampus();

                    $allBillingInfoByCurrMonYrs = $this->billingInfo->where('year', date('Y'))->where('month', date('F'))->get();

                    return view('admin::pages.subscription-management.subscription-management', compact('instituteList', 'page', 'allBillingInfoArrayList', 'allBillingInfoArrayListByCampus', 'allBillingInfoByCurrMonYrs'));

                case 'bill-reports':
                    return view('admin::pages.manage-bill.manage-bill', compact('instituteList', 'page'));
            }
        } else {
            return redirect('/');
        }

    }

    ///////////////////////////// institute bill /////////////////////////////////////

    // create bill
    public function createBill()
    {
        // institute list
        $instituteList = $this->academicHelper->getInstituteList();
        // return view with variables
        return view('admin::pages.manage-bill.modals.bill', compact('instituteList'));
    }

    // store bill
    public function storeBill(Request $request)
    {
        // request details
        $monthId = $request->input('month', null);
        $instituteList = $request->input('institute_list', null);

        // checking institute list
        if(!empty($instituteList) AND $instituteList !=null AND count($instituteList)>0){
            // checking month id
            if($monthId){
                // get current academic year bill list use month id
                $billArrayList = $this->getBillList($monthId);
                // institute bill counter
                $instBillLoopCount = 0;
                // institute list looping
                foreach ($instituteList as $index=>$instId){
                    // institute billing list
                    $instBillList = array_key_exists($instId, $billArrayList)?$billArrayList[$instId]:[];

                    // checking institute bill list
                    if(array_key_exists($monthId, $instBillList)==false){
                        // new bill
                        $billProfile = new $this->bill();
                        // bill details
                        $billProfile->billing_month = $monthId;
                        $billProfile->institute_id = $instId;
                        // save and checking
                        if($billProfile->save()){
                            // loop counter
                            $instBillLoopCount+=1;
                        }
                    }else{
                        // loop counter
                        $instBillLoopCount+=1;
                    }
                }

                // checking $instBillLoopCount
                if($instBillLoopCount==count($instituteList)){
                    // session success msg
                    Session::flash('success', 'Bill Submitted !!!!');
                    // return redirect back
                    return redirect()->back();
                }else{
                    // session warning msg
                    Session::flash('warning', 'Unable to submit bill');
                    // return redirect back
                    return redirect()->back();
                }

            }else{
                // session warning msg
                Session::flash('warning', 'Billing Month not found');
                // return redirect back
                return redirect()->back();
            }
        }else{
            // session warning msg
            Session::flash('warning', 'No Institute Found');
            // return redirect back
            return redirect()->back();
        }
    }



    //////////////////////// Billing information ////////////////////////

    // create billing information
    public function createBillInfo()
    {
        // billing info
        $billingInfoProfile = null;
        // allBillingInfoArrayList
        $allBillingInfoArrayList = $this->getBillingInfoList();

        // allBillingInfoArrayListByCampus
        $allBillingInfoArrayListByCampus = $this->getBillingInfoListByCampus();

        // institute list
        $instituteList = $this->academicHelper->getInstituteList();

        // campus list
        $campusList = $this->academicHelper->getCampusList();

        // return view with variables
        return view('admin::pages.manage-bill.modals.bill-create', compact('instituteList', 'billingInfoProfile', 'allBillingInfoArrayList', 'campusList', 'allBillingInfoArrayListByCampus'));
    }


    // store billing information
    public function storeBillingInfo(Request $request) {
        // request details
        $instituteId = $request->input('institute_id');
        $campusId = $request->input('campus_id');
        $deposited = $request->input('deposited');
        $ratePerStudent = $request->input('rate_per_student');
        $totalAmount = $request->input('total_amount');
        $acceptedAmount = $request->input('accepted_amount');
        $year = $request->input('year');
        $month = $request->input('month');

        $allInstituteSmsCountByCampus = $this->academicApiController->getInstituteSmsCount($request);

        if(isset($allInstituteSmsCountByCampus))
        {
            $ratePerSms = $request->input('rate_per_sms');
            $totalSmsPrice = $request->input('total_sms_price');
            $acceptedSmsPrice = $request->input('accepted_sms_price');
        }

        $billingInfoId = $request->input('billing_info_id');

        // checking billing info id
        if($billingInfoId>0) {
            // billing info profile
            $billInfoProfile = $this->billingInfo->findOrFail($billingInfoId);

            $prevTotalAmount = $billInfoProfile->total_amount;
            $prevAcceptedAmount = $billInfoProfile->accepted_amount;
            if(isset($prevAcceptedAmount)) {
                $finalAmount = $prevAcceptedAmount;
            } else {
                $finalAmount = $prevTotalAmount; 
            }

            $prevTotalSmsPrice = $billInfoProfile->total_sms_price;
            $prevAcceptedSmsPrice = $billInfoProfile->accepted_sms_price;
            if(isset($prevAcceptedSmsPrice)) {
                $finalSmsPrice = $prevAcceptedSmsPrice;
            } else {
                $finalSmsPrice = $prevTotalSmsPrice;
            }

            session()->forget('oneStepPrevFinalAmount');
            session()->forget('oneStepPrevFinalSmsPrice');

            session(['oneStepPrevFinalAmount' => $finalAmount]);
            session(['oneStepPrevFinalSmsPrice' => $finalSmsPrice]);

        } else {
            // create billing info profile
            $billInfoProfile = new $this->billingInfo();
        }

        // store billing details
        $billInfoProfile->institute_id = $instituteId;
        $billInfoProfile->campus_id = $campusId;
        $billInfoProfile->deposited = $deposited;
        $billInfoProfile->rate_per_student = $ratePerStudent;
        $billInfoProfile->total_amount = $totalAmount;
        $billInfoProfile->accepted_amount = $acceptedAmount;
        $billInfoProfile->year = $year;
        $billInfoProfile->month = $month;

        if(isset($allInstituteSmsCountByCampus))
        {
            $billInfoProfile->rate_per_sms = $ratePerSms;
            $billInfoProfile->total_sms_price = $totalSmsPrice;
            $billInfoProfile->accepted_sms_price = $acceptedSmsPrice;
        }

        // save and check
        if($billInfoProfile->save()) {
            // session success msg
            Session::flash('success', 'Billing Info Submitted !!!');
            // return redirect back
            return redirect()->back();
        } else {
            // session warning msg
            Session::flash('warning', 'Unable Submit Billing information');
            // return redirect back
            return redirect()->back();
        }
    }


    // edit billing information
    public function editBillingInfo($billingInfoId)
    {
        // billing info
        $billingInfoProfile = $this->billingInfo->findOrFail($billingInfoId);
        // institute list
        $instituteList = $this->academicHelper->getInstituteList();

        $allInstituteSmsCountByCampus = $this->getInstituteSmsCountByCampus();
        // return view with variables
        return view('admin::pages.manage-bill.modals.bill-create', compact('instituteList', 'billingInfoProfile', 'allInstituteSmsCountByCampus'));
    }

    // delete billing info
    public function destroyBillingInfo($billingInfoId)
    {
        $this->billingInfo->destroy(1);
        // billing info
        $billingInfoProfile = $this->billingInfo->findOrFail($billingInfoId);
        // checking
        if($billingInfoProfile) {
            $billingInfoProfile->delete();
            // session success msg
            Session::flash('success', 'Billing Info deleted !!!');
            // return redirect back
            return redirect()->back();
        }else{
            // session warning msg
            Session::flash('warning', 'Unable to perform the action');
            // return redirect back
            return redirect()->back();
        }
    }


    ///////////////////  others /////////////////

    // institute bill array list
    public function getBillList($monthId)
    {
        // billing year
        $year = date('Y');
        // checking bill month
        if($monthId){
            // all billing information
            $allBillProfile = $this->bill->whereYear('created_at', $year)->where('billing_month', $monthId)->get();
        }else{
            // all billing information
            $allBillProfile = $this->bill->whereYear('created_at', $year)->get();
        }
        // response array list
        $responseArray = array();
        // looping
        foreach ($allBillProfile as $billProfile){
            // add to the array list
            $responseArray[$billProfile->institute_id][$billProfile->billing_month] = [
                'id'=>$billProfile->id,
                'status'=>$billProfile->status
            ];
        }
        // return
        return $responseArray;
    }

    // billing info array list
    public function getBillingInfoList()
    {
        // all billing information
        $allBillingInfo = $this->billingInfo->all();
        // response array list
        $responseArray = array();
        // looping
        foreach ($allBillingInfo as $billingInfo) {
            // add to the array list
            $responseArray[$billingInfo->institute_id] = [
                'id'=>$billingInfo->id,
                'status'=>$billingInfo->status,
                'rate'=>$billingInfo->rate_per_student,
                'total_amount'=>$billingInfo->total_amount,
                'accepted_amount'=>$billingInfo->accepted_amount
            ];
        }
        // return
        return $responseArray;
    }

    public function getBillingInfoListByCampus()
    {
        $allBillingInfo = $this->billingInfo->where('year', date('Y'))->where('month', date('F'))->get();

        $responseArray = array();

        foreach ($allBillingInfo as $billingInfo) {
            $responseArray[$billingInfo->campus_id] = [
                'id'                => $billingInfo->id,
                'deposited'         => $billingInfo->deposited,
                'status'            => $billingInfo->status,
                'rate'              => $billingInfo->rate_per_student,
                'total_amount'      => $billingInfo->total_amount,
                'accepted_amount'   => $billingInfo->accepted_amount,
                'rate_per_sms'      => $billingInfo->rate_per_sms,
                'total_sms_price'   => $billingInfo->total_sms_price,
                'accepted_sms_price'=> $billingInfo->accepted_sms_price,
                'year'              => $billingInfo->year,
                'month'             => $billingInfo->month, 
                'created_at'        => $billingInfo->created_at,
                'updated_at'        => $billingInfo->updated_at,  
            ];
        }
        return $responseArray;
    }

    public function getInstituteSmsCountByCampus() {
        $allBillingInfo = $this->billingInfo->all();

        $responseArray = array();

        foreach ($allBillingInfo as $billingInfo) {
            $institute_id = $billingInfo->institute_id;
            $campus_id = $billingInfo->campus_id;

            $smsCreditCount=$this->smsCredit->where('institution_id', $institute_id)->where('campus_id', $campus_id)->where('status','1')->sum('sms_amount');
            $smsLogCount= $this->smsLog->where('institution_id', $institute_id)->where('campus_id', $campus_id)->count();
            $totalSmsCredit=$smsCreditCount-$smsLogCount;

            if(($totalSmsCredit > 0)) {
                $responseArray[$billingInfo->campus_id] = [
                    'total_sms' => $totalSmsCredit,  
                ];
            } else {
                $responseArray[$billingInfo->campus_id] = [
                    'total_sms' => NULL, 
                ];
            }
        }

        return $responseArray;
    }
}
