<?php

namespace Modules\Admin\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SubscriptionManagementMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $subscriptionManagementData;

    public function __construct($subscriptionManagementData)
    {
        $this->subscriptionManagementData = $subscriptionManagementData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('saifulmasud@gmail.com')
                    ->subject("Monthly Subscription bill of ". $this->subscriptionManagementData['month'] ." ". $this->subscriptionManagementData['year'] )
                    ->view('admin::emails.subscription-management-mail');
    }
}
