<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class SubscriptionManagementTransaction extends Model
{
    protected $table = 'subscription_management_transactions';
    protected $fillable = ['institute_billing_info_id', 'old_dues', 'monthly_total_charge', 'paid_amount', 'new_dues', 'status', 'sms', 'email', 'invoice'];

    public function billingInfo()
    {
        return $this->belongsTo('Modules\Admin\Entities\BillingInfo', 'institute_billing_info_id', 'id');
    }
}
