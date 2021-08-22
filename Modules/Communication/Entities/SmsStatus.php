<?php

namespace Modules\Communication\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class smsStatus extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'sms_status';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'message_id',
        'status',
        'status_text',
        'error_code',
        'error_text',
        'sms_count',
        'current_credit',
        'sms_logid',
    ];


    public function parents(){
        return $this->belongsTo('Modules\Student\Entities\StudentGuardian', 'user_id', 'id')->first();
    }

}
