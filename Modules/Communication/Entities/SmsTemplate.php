<?php

namespace Modules\Communication\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmsTemplate extends Model

{
    use SoftDeletes;

    // Table name
    protected $table = 'sms_template';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'message',
        'status',
        'template_name',
        'sms_type',
        'institution_id',
        'campus_id',
    ];

}
