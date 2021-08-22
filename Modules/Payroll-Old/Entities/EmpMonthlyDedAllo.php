<?php

namespace Modules\Payroll\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Employee\Entities\EmployeeInformation;

class EmpMonthlyDedAllo extends Model
{
    use SoftDeletes;
    // Table name
    protected $table = 'pay_emp_monthly_ded_allo';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    public function empName($empId){
        return EmployeeInformation::empName($empId);
    }
    public function salCompName($compId){
        return SalaryComponent::salCompName($compId);
    }
}
