<?php

namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

class AttendanceSmsSetting extends Model
{
    use Userstamps;
    use SoftDeletes;

    protected $table = 'attendance_sms_setting';
    protected $fillable = [
        'institute_id',
        'campus_id',
        'attendance_medium',
        'attendance_type',
        'set_time',
        'status',
    ];


}
