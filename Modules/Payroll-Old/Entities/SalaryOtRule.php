<?php

namespace Modules\Payroll\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalaryOtRule extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'pay_emp_ot';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    public function otType(){
        $data = $this->belongsTo(SalaryComponent::class,'ot_type_id','id')->first();
        return $data->name;
    }
}
