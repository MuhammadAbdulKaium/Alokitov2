<?php

namespace Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeLeaveEntitlement extends Model
{

    use SoftDeletes;

    // Table name
    protected $table = 'employee_leave_entitlements';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'category',
        'structure',
        'employee',
        'designation',
        'department',
        'is_custom',
        'campus_id',
        'institute_id'
    ];


    // returns employee profile
    public function employee()
    {
        return $this->belongsTo('Modules\Employee\Entities\EmployeeInformation', 'employee', 'id' )->first();
    }

    // returns structure profile
    public function structure()
    {
        return $this->belongsTo('Modules\Employee\Entities\EmployeeLeaveStructure', 'structure', 'id' )->first();
    }

    // returns structure profile
    public function designation()
    {
        return $this->belongsTo('Modules\Employee\Entities\EmployeeDesignation', 'designation', 'id' )->first();
    }

    // returns department profile
    public function department()
    {
        return $this->belongsTo('Modules\Employee\Entities\EmployeeDepartment', 'department', 'id' )->first();
    }
}
