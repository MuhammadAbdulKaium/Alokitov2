<?php

namespace Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeShiftAllocation extends Model
{
    use SoftDeletes;
    // Table name
    protected $table = 'shift_assign';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    public function shift(){
        $shift = $this->belongsTo('Modules\Employee\Entities\Shift','shift_id', 'id')->first();
        // checking
        if ($shift) {
            // return user info
            return $shift;
        } else {
            // return false
            return false;
        }
    }
    public function employee(){
        $employee = $this->belongsTo('Modules\Employee\Entities\EmployeeInformation','emp_id', 'user_id')->first();
        // checking
        if ($employee) {
            // return user info
            return $employee;
        } else {
            // return false
            return false;
        }
    }
}
