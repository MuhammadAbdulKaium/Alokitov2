<?php

namespace Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeOtHour extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'employee_ot_hour';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    public function empName($empId){
        return EmployeeInformation::empName($empId);
    }
}
