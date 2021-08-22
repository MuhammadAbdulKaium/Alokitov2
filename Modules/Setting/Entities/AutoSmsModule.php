<?php

namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class AutoSmsModule extends Model
{
    use SoftDeletes;
    protected $table    = 'auto_sms_module';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'id',
        'status_code',
        'status',
        'sms_temp_id',
        'ins_id',
        'campus_id',
        'description',
    ];
}
