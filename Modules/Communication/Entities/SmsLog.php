<?php

namespace Modules\Communication\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmsLog extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'sms_log';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'institution_id',
        'campus_id',
        'user_id',
        'user_group',
        'user_no',
        'message_id',
        'delivery_status'
    ];


    public function parents(){
        return $this->belongsTo('Modules\Student\Entities\StudentGuardian', 'user_id', 'id')->first();
    }

    public function singleMessage(){
        return $this->belongsTo('Modules\Communication\Entities\SmsMessage', 'message_id', 'id')->first();
    }

    public function teacher(){
        return $this->belongsTo('Modules\Employee\Entities\EmployeeInformation', 'user_id', 'user_id')->first();
    }

    public function student(){
        return $this->belongsTo('Modules\Student\Entities\StudentInformation', 'user_id', 'id')->first();
    }

    public function message(){
        return $this->belongsTo('Modules\Communication\Entities\SmsMessage', 'message_id', 'id')->first();
    }




}
