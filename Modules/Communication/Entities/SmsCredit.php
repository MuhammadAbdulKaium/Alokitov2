<?php

namespace Modules\Communication\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmsCredit extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'sms_credit';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'institution_id',
        'campus_id',
        'sms_amount',
        'status',
        'submitted_by',
        'submission_date',
        'accepted_date',
        'year',
        'month',
        'payable',
        'payment_status',
        'comment',
        'remark'
    ];


    public function institute(){
        return $this->belongsTo('Modules\Setting\Entities\Institute','institution_id','id')->first();
    }
}
