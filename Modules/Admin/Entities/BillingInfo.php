<?php

namespace Modules\Admin\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BillingInfo extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'institute_billing_info';

    // The attribute that should be used for soft Delete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = ['rate_per_student', 'total_amount', 'accepted_amount', 'status', 'institute_id'];

    public function subscriptionManagementTransaction()
    {
        return $this->hasOne('Modules\Admin\Entities\SubscriptionManagementTransaction', 'institute_billing_info_id', 'id');
    }

    public function institute()
    {
        return $this->belongsTo('Modules\Setting\Entities\Institute', 'institute_id', 'id')->orderBy('institute_name', 'DESC')->first();
    }
}