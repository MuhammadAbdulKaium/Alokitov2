<?php

namespace Modules\Payroll\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmpSalaryAssignExtra extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'pay_emp_salary_assign_extra';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];
}
