<?php

namespace Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeLeaveStructureType extends Model
{

    use SoftDeletes;

    // Table name
    protected $table = 'employee_leave_structure_type';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'structure_id',
        'type_id',
        'leave_days'
    ];

    public function leaveType()
    {
        return $this->belongsTo('Modules\Employee\Entities\EmployeeLeaveType','type_id', 'id')->first();
    }

    // returns structure profile
    public function structure()
    {
        return $this->belongsTo('Modules\Employee\Entities\EmployeeLeaveStructure', 'structure_id', 'id' )->first();
    }

    // leave history
    public function leaveHistory($employeeId)
    {
        return $this->belongsTo('Modules\Employee\Entities\EmployeeLeaveHistory', 'type_id', 'leave_type')->where(['employee'=>$employeeId])->get()->sum('approved_leave_days');
    }

}
