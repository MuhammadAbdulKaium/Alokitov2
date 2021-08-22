<?php

namespace Modules\Payroll\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalaryHead extends Model
{
    use SoftDeletes;

    protected $table = 'pay_salary_head';
    protected $dates = ['deleted_at'];

    protected $fillable = [];
}
