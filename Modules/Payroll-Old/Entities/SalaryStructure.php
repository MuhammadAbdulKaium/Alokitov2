<?php

namespace Modules\Payroll\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class salaryStructure extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'pay_salary_structure';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];
}
