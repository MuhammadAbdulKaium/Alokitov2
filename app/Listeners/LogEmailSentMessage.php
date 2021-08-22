<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Queue\InteractsWithQueue;
use Modules\Admin\Entities\SubscriptionManagementTransaction;
use Modules\Admin\Entities\SubscriptionManagementProcessSession;
use Illuminate\Support\Facades\Session;

class LogEmailSentMessage
{
    /**
     * Create the event listener.
     *
     * @return void
     */

    private $smt;
    private $msgDataTransID;
    private $smpt;
    
    public function __construct(SubscriptionManagementTransaction $smt, SubscriptionManagementProcessSession $smpt)
    {
        $this->smt = $smt;
        $this->smpt = $smpt;
    }

    /**
     * Handle the event.
     *
     * @param  MessageSent  $event
     * @return void
     */
    public function handle(MessageSent $event)
    {
        $this->msgDataTransID = $event->data['subscriptionManagementData']['transactionID'];
        $smtObj = $this->smt->findOrFail($this->msgDataTransID);
        $smtObj->status = "processed";
        $smtObj->email = "yes";

        session()->forget("processSession_".$this->msgDataTransID);

        $processSessionArry = array();
        $processSessionArry['totalAmount'] = $event->data['subscriptionManagementData']['totalAmount'];
        $processSessionArry['totalSmsPrice'] = $event->data['subscriptionManagementData']['totalSmsPrice'];
        $processSessionArry['oldDues'] = $event->data['subscriptionManagementData']['oldDues'];
        $processSessionArry['monthlyTotalCharge'] = $event->data['subscriptionManagementData']['monthlyTotalCharge'];
        $processSessionArry['paidAmount'] = $event->data['subscriptionManagementData']['paidAmount'];

        session(["processSession_".$this->msgDataTransID => $processSessionArry]);

        if($smtObj->save()) {

            $smptObj = $this->smpt::create([
                "subscription_management_transactions_id" => $event->data['subscriptionManagementData']['transactionID'],
                "total_amount"                            => $event->data['subscriptionManagementData']['totalAmount'],
                "total_sms_price"                         => $event->data['subscriptionManagementData']['totalSmsPrice'],
                "old_dues"                                => $event->data['subscriptionManagementData']['oldDues'],
                "monthly_total_charge"                    => $event->data['subscriptionManagementData']['monthlyTotalCharge'],
                "paid_amount"                             => $event->data['subscriptionManagementData']['paidAmount'],
                "new_dues"                                => $event->data['subscriptionManagementData']['newDues'],
                "status"                                  => 'processed',
                "email"                                   => 'yes',
            ]);
        }
    }
}
