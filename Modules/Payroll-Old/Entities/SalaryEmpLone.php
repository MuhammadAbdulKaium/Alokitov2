<?php

namespace Modules\Payroll\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Employee\Entities\EmployeeInformation;

class SalaryEmpLone extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'pay_emp_loan';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    public function loneType(){
        $data = $this->belongsTo(SalaryComponent::class,'loan_type_id','id')->first();
        return $data->name;
    }

    public function empName($empId){
        return  EmployeeInformation::empName($empId);
    }
}
