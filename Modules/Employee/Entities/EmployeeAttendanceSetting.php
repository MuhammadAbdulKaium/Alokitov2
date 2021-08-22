<?php

namespace Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class EmployeeAttendanceSetting extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'employee_attendance_setting';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'institution_id',
        'campus_id',
        'emp_id',
        'start_time',
        'end_time',
        'status'
    ];


    // return the department profile
    public function name(){
        return $this->belongsTo('Modules\Employee\Entities\EmployeeInformation', 'emp_id', 'id')->first();
    }

}
