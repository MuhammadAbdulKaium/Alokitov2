<?php

namespace Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttendanceDeviceLog extends Model
{
    use SoftDeletes;
    // Table name
    protected $table = 'attendance_device_log';
    protected $fillable = [
        'access_id',
        'start_date',
        'start_time',
        'end_date',
        'end_time',
        'status'
    ];
}
