<?php

namespace Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttendanceDevice extends Model
{
    use SoftDeletes;
    // Table name
    protected $table = 'attendance_device';
    protected $fillable = [
        'card',
        'access_id',
        'access_date',
        'access_time',
        'registration_id',
        'person_type',
        'institute_id',
        'campus_id'
    ];
}
