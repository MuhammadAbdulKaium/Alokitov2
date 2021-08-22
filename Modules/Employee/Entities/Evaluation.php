<?php

namespace Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Evaluation extends Model
{
    use SoftDeletes;

    protected $guarded = [];

   
    
    public function byDesignations()
    {
        return $this->belongsToMany('Modules\Employee\Entities\EmployeeDesignation', 'employee_designation_evaluation_by', 'evaluation_by_id', 'employee_designation_id');
    }

    public function forDesignations()
    {
        return $this->belongsToMany('Modules\Employee\Entities\EmployeeDesignation', 'employee_designation_evaluation_for', 'evaluation_for_id', 'employee_designation_id');
    }

    
    
    public function parameters()
    {
        return $this->belongsToMany('Modules\Employee\Entities\EvaluationParameter', 'evaluation_with_parameters', 'evaluation_id', 'evaluation_parameter_id')->withPivot('year','score','created_at','campus_id','institute_id');
    }

    public function noOfparameters()
    {
        return $this->belongsToMany('Modules\Employee\Entities\EvaluationParameter', 'evaluation_with_parameters', 'evaluation_id', 'evaluation_parameter_id')->count();
    }

    public function designationForId()
    {
        return $this->belongsToMany('Modules\Employee\Entities\EmployeeDesignation', 'employee_designation_evaluation_for', 'evaluation_for_id', 'employee_designation_id')->get()->pluck('id');
    }


}
