<?php

namespace Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeLeaveHistory extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'employee_leave_histories';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'application_id',
        'leave_type',
        'approved_date',
        'start_date',
        'end_date',
        'approved_leave_days',
        'employee',
        'approved_by',
        'campus_id',
        'institute_id'
    ];

    public function application()
    {
        return $this->belongsTo('Modules\Employee\Entities\EmployeeLeaveApplication','application_id', 'id')->first();
    }

    public function leaveType()
    {
        return $this->belongsTo('Modules\Employee\Entities\EmployeeLeaveType','leave_type', 'id')->first();
    }

    // returns employee profile
    public function employee()
    {
        return $this->belongsTo('Modules\Employee\Entities\EmployeeInformation', 'employee', 'id' )->first();
    }
}


