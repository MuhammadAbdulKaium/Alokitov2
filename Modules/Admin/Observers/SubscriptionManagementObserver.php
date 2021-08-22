<?php
namespace Modules\Admin\Observers;

use Illuminate\Support\Facades\Log;
use Modules\Setting\Entities\Institute;
use Modules\Admin\Entities\BillingInfo;
use Modules\Admin\Entities\SubscriptionManagementTransaction;
use Illuminate\Support\Facades\Session;

class SubscriptionManagementObserver
{
    private $institute;
    private $billingInfos;
    private $smts;

    public function __construct(Institute $institute, BillingInfo $billingInfos, SubscriptionManagementTransaction $smts) {
        $this->institute = $institute;
        $this->billingInfos = $billingInfos;
        $this->smts = $smts;
    }

    public function saved(BillingInfo $billingInfo) {
        Log::info('Subscription Payment Management');
        $institutes = $this->institute->orderBy('institute_name', 'ASC')->get();
        foreach($institutes as $institute)
        {
            foreach($institute->campus() as $cam)
            {
                if(($cam->institute_id==$billingInfo->institute_id) && ($cam->id==$billingInfo->campus_id))
                {
                    $ID = $billingInfo->id;

                    $totalAmount = $billingInfo->total_amount;
                    $acceptedAmount = $billingInfo->accepted_amount;
                    if(isset($acceptedAmount)) {
                        $finalAmount = $acceptedAmount;
                    } else {
                        $finalAmount = $totalAmount; 
                    }

                    $totalSmsPrice = $billingInfo->total_sms_price;
                    $acceptedSmsPrice = $billingInfo->accepted_sms_price;
                    if(isset($acceptedSmsPrice)) {
                        $finalSmsPrice = $acceptedSmsPrice;
                    } else {
                        $finalSmsPrice = $totalSmsPrice; 
                    }

                    $curYr = $billingInfo->year;
                    $curMon = $billingInfo->month;
                    $preYear = (int)$curYr - 1;
                    $preMonth = date("F", strtotime($curMon . " last month"));

                    $insID = $billingInfo->institute_id;
                    $camID = $billingInfo->campus_id;
                    
                    if($billingInfo->subscriptionManagementTransaction()->where('institute_billing_info_id', $ID)->count() > 0 ) {

                        $oldDues = $billingInfo->subscriptionManagementTransaction->old_dues;
                        $currMonthlyTotalCharge = $finalAmount+$finalSmsPrice+$oldDues;
                        $prevMonthlyTotalCharge = $billingInfo->subscriptionManagementTransaction->monthly_total_charge;
                        
                        if(session('oneStepPrevFinalAmount')) {
                            $prevFinalAmount = session('oneStepPrevFinalAmount');
                        }
                        
                        if(session('oneStepPrevFinalSmsPrice')) {
                            $prevFinalSmsPrice = session('oneStepPrevFinalSmsPrice');
                        }

                        if( ($currMonthlyTotalCharge != $prevMonthlyTotalCharge) && ( ($prevFinalAmount != $finalAmount) || ($prevFinalSmsPrice != $finalSmsPrice ) ) ) {

                            if( isset($oldDues) && ($oldDues > 0) ) {
                            
                                $paidAmount = $billingInfo->subscriptionManagementTransaction->paid_amount;
                            
                                if(session("processSession_".$billingInfo->subscriptionManagementTransaction->id)) {
                                    $prevProcessSessionData = session("processSession_".$billingInfo->subscriptionManagementTransaction->id);
                                    $prevProcessSessionTotalAmount = $prevProcessSessionData['totalAmount'];
                                    $prevProcessSessionTotalSmsPrice = $prevProcessSessionData['totalSmsPrice'];
                                    $prevProcessSessionMonthlyTotalCharge = $prevProcessSessionData['monthlyTotalCharge'];
                                    $prevProcessSessionPaidAmount = $prevProcessSessionData['paidAmount'];
                                    $prevProcessSessionOldDues = $prevProcessSessionData['oldDues'];

                                    if( ($currMonthlyTotalCharge == $prevProcessSessionMonthlyTotalCharge) && ($prevProcessSessionTotalAmount==$finalAmount) && ($prevProcessSessionTotalSmsPrice==$finalSmsPrice) && ($prevProcessSessionOldDues==$oldDues) && ($prevProcessSessionPaidAmount==$paidAmount) ) {
                                        if(($paidAmount > 0) && ($currMonthlyTotalCharge > 0)) {
                                            if($paidAmount < $currMonthlyTotalCharge) {
                                                $newDues = $currMonthlyTotalCharge - $paidAmount;
                                                $status = "paid_due";
                                                $email = "yes";
                                            }
                
                                            elseif($paidAmount == $currMonthlyTotalCharge) {
                                                $newDues = null;
                                                $status = "paid";
                                                $email = "yes";
                                            }
                
                                            else {
                                                $newDues = null;
                                                $status = "paid";
                                                $email = "yes";
                                            }
                
                                        } else {
                                            
                                            if( $currMonthlyTotalCharge > 0) {
                                                $newDues = $currMonthlyTotalCharge;
                                                $status = "processed";
                                                $email = 'yes';
                
                                            } else {
                                                $newDues = null;
                                                $status = $billingInfo->subscriptionManagementTransaction->status;
                                                $email = $billingInfo->subscriptionManagementTransaction->email;
                                            }
                                        }
                                        
                                    } else {
        
                                        if(($paidAmount > 0) && ($currMonthlyTotalCharge > 0)) {
                                            if($paidAmount < $currMonthlyTotalCharge) {
                                                $newDues = $currMonthlyTotalCharge - $paidAmount;
                                                $status = "paid_due";
                                                $email = $billingInfo->subscriptionManagementTransaction->email;
                                            }
                
                                            elseif($paidAmount == $currMonthlyTotalCharge) {
                                                $newDues = null;
                                                $status = "paid";
                                                $email = $billingInfo->subscriptionManagementTransaction->email;
                                            }
                
                                            else {
                                                $newDues = null;
                                                $status = "paid";
                                                $email = $billingInfo->subscriptionManagementTransaction->email;
                                            }
                
                                        } else{
                                            
                                            if( $currMonthlyTotalCharge > 0) {
                                                $newDues = $currMonthlyTotalCharge;
                                                $status = "due";
                                                $email = $billingInfo->subscriptionManagementTransaction->email;
                
                                            } else {
                                                $newDues = $billingInfo->subscriptionManagementTransaction->new_dues;
                                                $status = $billingInfo->subscriptionManagementTransaction->status;
                                                $email = $billingInfo->subscriptionManagementTransaction->email;
                                            }
                                        }
                                    }

                                } else {

                                    if(($paidAmount > 0) && ($currMonthlyTotalCharge > 0)) {
                                        if($paidAmount < $currMonthlyTotalCharge) {
                                            $newDues = $currMonthlyTotalCharge - $paidAmount;
                                            $status = "paid_due";
                                            $email = $billingInfo->subscriptionManagementTransaction->email;
                                        }
            
                                        elseif($paidAmount == $currMonthlyTotalCharge) {
                                            $newDues = null;
                                            $status = "paid";
                                            $email = $billingInfo->subscriptionManagementTransaction->email;
                                        }
            
                                        else {
                                            $newDues = null;
                                            $status = "paid";
                                            $email = $billingInfo->subscriptionManagementTransaction->email;
                                        }
            
                                    } else {
                                        
                                        if( $currMonthlyTotalCharge > 0) {
                                            $newDues = $currMonthlyTotalCharge;
                                            $status = "pending";
                                            $email = $billingInfo->subscriptionManagementTransaction->email;
            
                                        } else {
                                            $newDues = $billingInfo->subscriptionManagementTransaction->new_dues;
                                            $status = $billingInfo->subscriptionManagementTransaction->status;
                                            $email = $billingInfo->subscriptionManagementTransaction->email;
                                        }
                                    }
                                }

                            } else {

                                $paidAmount = $billingInfo->subscriptionManagementTransaction->paid_amount;
                            
                                if(session("processSession_".$billingInfo->subscriptionManagementTransaction->id)) {
                                    $prevProcessSessionData = session("processSession_".$billingInfo->subscriptionManagementTransaction->id);
                                    $prevProcessSessionTotalAmount = $prevProcessSessionData['totalAmount'];
                                    $prevProcessSessionTotalSmsPrice = $prevProcessSessionData['totalSmsPrice'];
                                    $prevProcessSessionMonthlyTotalCharge = $prevProcessSessionData['monthlyTotalCharge'];
                                    $prevProcessSessionPaidAmount = $prevProcessSessionData['paidAmount'];
                                    $prevProcessSessionOldDues = $prevProcessSessionData['oldDues'];

                                    if( ($currMonthlyTotalCharge == $prevProcessSessionMonthlyTotalCharge) && ($prevProcessSessionTotalAmount==$finalAmount) && ($prevProcessSessionTotalSmsPrice==$finalSmsPrice) && (($prevProcessSessionOldDues==$oldDues) || ($oldDues==0) || ($oldDues==null)) && ($prevProcessSessionPaidAmount==$paidAmount) ) {
                                        if(($paidAmount > 0) && ($currMonthlyTotalCharge > 0)) {
                                            if($paidAmount < $currMonthlyTotalCharge) {
                                                $newDues = $currMonthlyTotalCharge - $paidAmount;
                                                $status = "paid_due";
                                                $email = "yes";
                                            }
                
                                            elseif($paidAmount == $currMonthlyTotalCharge) {
                                                $newDues = null;
                                                $status = "paid";
                                                $email = "yes";
                                            }
                
                                            else {
                                                $newDues = null;
                                                $status = "paid";
                                                $email = "yes";
                                            }
                
                                        } else {
                                            
                                            if( $currMonthlyTotalCharge > 0) {
                                                $newDues = $currMonthlyTotalCharge;
                                                $status = "processed";
                                                $email = 'yes';
                
                                            } else {
                                                $newDues = null;
                                                $status = $billingInfo->subscriptionManagementTransaction->status;
                                                $email = $billingInfo->subscriptionManagementTransaction->email;
                                            }
                                        }
                                        
                                    } else {
        
                                        if(($paidAmount > 0) && ($currMonthlyTotalCharge > 0)) {
                                            if($paidAmount < $currMonthlyTotalCharge) {
                                                $newDues = $currMonthlyTotalCharge - $paidAmount;
                                                $status = "paid_due";
                                                $email = $billingInfo->subscriptionManagementTransaction->email;
                                            }
                
                                            elseif($paidAmount == $currMonthlyTotalCharge) {
                                                $newDues = null;
                                                $status = "paid";
                                                $email = $billingInfo->subscriptionManagementTransaction->email;
                                            }
                
                                            else {
                                                $newDues = null;
                                                $status = "paid";
                                                $email = $billingInfo->subscriptionManagementTransaction->email;
                                            }
                
                                        } else{
                                            
                                            if( $currMonthlyTotalCharge > 0) {
                                                $newDues = $currMonthlyTotalCharge;
                                                $status = "due";
                                                $email = $billingInfo->subscriptionManagementTransaction->email;
                
                                            } else {
                                                $newDues = $billingInfo->subscriptionManagementTransaction->new_dues;
                                                $status = $billingInfo->subscriptionManagementTransaction->status;
                                                $email = $billingInfo->subscriptionManagementTransaction->email;
                                            }
                                        }
                                    }

                                } else {

                                    if(($paidAmount > 0) && ($currMonthlyTotalCharge > 0)) {
                                        if($paidAmount < $currMonthlyTotalCharge) {
                                            $newDues = $currMonthlyTotalCharge - $paidAmount;
                                            $status = "paid_due";
                                            $email = $billingInfo->subscriptionManagementTransaction->email;
                                        }
            
                                        elseif($paidAmount == $currMonthlyTotalCharge) {
                                            $newDues = null;
                                            $status = "paid";
                                            $email = $billingInfo->subscriptionManagementTransaction->email;
                                        }
            
                                        else {
                                            $newDues = null;
                                            $status = "paid";
                                            $email = $billingInfo->subscriptionManagementTransaction->email;
                                        }
            
                                    } else {
                                        
                                        if( $currMonthlyTotalCharge > 0) {
                                            $newDues = $currMonthlyTotalCharge;
                                            $status = "pending";
                                            $email = $billingInfo->subscriptionManagementTransaction->email;
            
                                        } else {
                                            $newDues = $billingInfo->subscriptionManagementTransaction->new_dues;
                                            $status = $billingInfo->subscriptionManagementTransaction->status;
                                            $email = $billingInfo->subscriptionManagementTransaction->email;
                                        }
                                    }
                                }
                            }
                            
    
                            $getSmt = $this->smts->updateOrCreate (
                                ['institute_billing_info_id' => $ID],
                                [
                                    'monthly_total_charge'  => $currMonthlyTotalCharge,
                                    'new_dues'              => $newDues, 
                                    'status'                => $status,
                                    'email'                 => $email ? $email: "no",
                                ],
                            );
                        }

                    } else {

                        $getBillingInfoCnt = $this->billingInfos
                                                ->where('institute_id', $insID)
                                                ->where('campus_id', $camID)
                                                ->whereYear('year', $curMon=='January'?$preYear:$curYr)
                                                ->where('month', $preMonth)
                                                ->count();

                        if($getBillingInfoCnt > 0) {

                            $getBillingInfo = $this->billingInfos
                                                ->where('institute_id', $insID)
                                                ->where('campus_id', $camID)
                                                ->whereYear('year', $curMon=='January'?$preYear:$curYr)
                                                ->where('month', $preMonth)
                                                ->first();

                            if(isset($getBillingInfo->subscriptionManagementTransaction->new_dues) && ($getBillingInfo->subscriptionManagementTransaction->new_dues > 0)) {
                                
                                $oldDues = $getBillingInfo->subscriptionManagementTransaction->new_dues;
                                $monthlyTotalCharge = $finalAmount+$finalSmsPrice+$oldDues;
                                $newDues = $monthlyTotalCharge;

                            } else {

                                $oldDues = null;
                                $monthlyTotalCharge = $finalAmount+$finalSmsPrice;
                                $newDues = $monthlyTotalCharge;
                            }

                        } else {

                            $oldDues = null;
                            $monthlyTotalCharge = $finalAmount+$finalSmsPrice;
                            $newDues = $monthlyTotalCharge;
                        }

                        $getSmt = $this->smts->updateOrCreate (
                            ['institute_billing_info_id' => $ID],
                            [
                                'old_dues'              => $oldDues, 
                                'monthly_total_charge'  => $monthlyTotalCharge,
                                'new_dues'              => $newDues, 
                                'status'                => "pending",
                                'sms'                   => "no", 
                                'email'                 => "no", 
                                'invoice'               => "no",
                            ],
                        );
                    }
                }
            }
        }  
    }
}
