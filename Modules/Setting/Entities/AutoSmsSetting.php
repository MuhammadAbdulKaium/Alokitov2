<?php

namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class AutoSmsSetting extends Model
{
    use SoftDeletes;
    protected $table    = 'auto_sms_settings';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'id',
        'user_type',
        'auto_sms_module_id',
        'ins_id',
        'campus_id',
        'description',
    ];
}
