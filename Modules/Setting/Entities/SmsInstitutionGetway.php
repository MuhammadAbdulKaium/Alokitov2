<?php

namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmsInstitutionGetway extends Model
{
    use SoftDeletes;
    protected $table    = 'sms_institution_getway';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'id',
        'institution_id',
        'campus_id',
        'api_path',
        'sender_id',
        'remark',
        'status',
        'activated_upto'
    ];
}
