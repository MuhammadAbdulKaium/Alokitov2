<?php

namespace Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeLeaveType extends Model
{

    use SoftDeletes;

    // Table name
    protected $table = 'employee_leave_types';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'name',
        'details',
        'closing_cycle',
//        'carray_forward',
//        'max_cf_amount',
//        'leave_encash',
//        'salary_type',
//        'percentage',
        'campus_id',
        'institute_id'
    ];
}
