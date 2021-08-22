<?php

namespace Modules\Payroll\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalaryPfRule extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'pay_emp_pf';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    public function pfType(){
       $data = $this->belongsTo(SalaryComponent::class,'pf_type_id','id')->first();
        return $data->name;
    }
}
