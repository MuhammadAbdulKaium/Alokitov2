<?php

namespace Modules\Communication\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmsMessage extends Model
{

    use SoftDeletes;

    // Table name
    protected $table = 'sms_message';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'message'
    ];
}
