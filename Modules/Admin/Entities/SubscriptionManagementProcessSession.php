<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;

class SubscriptionManagementProcessSession extends Model
{
    protected $table = 'subscription_management_processed_session';
    protected $fillable = ['subscription_management_transactions_id', 'total_amount', 'accepted_amount', 'total_sms_price', 'accepted_sms_price', 'old_dues', 'monthly_total_charge', 'paid_amount', 'new_dues', 'status', 'sms','email', 'invoice'];

    public function subscriptionManagementTransaction()
    {
        return $this->belongsTo('Modules\Admin\Entities\SubscriptionManagementTransaction', 'subscription_management_transactions_id', 'id');
    }
}
