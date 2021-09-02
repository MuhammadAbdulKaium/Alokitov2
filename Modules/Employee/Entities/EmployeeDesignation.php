<?php

namespace Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeDesignation extends Model
{
    use Userstamps;
    use SoftDeletes;

    // Table name
    protected $table = 'employee_designations';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'name',
        'alias',
        'campus_id',
        'institute_id'
    ];

    // return the department profile
    public function department()
    {
        return $this->belongsTo('Modules\Employee\Entities\EmployeeDepartment', 'dept_id', 'id')->first();
    }




    public function evaluationBy()
    {
        return $this->belongsToMany('Modules\Employee\Entities\Evaluation', 'employee_designation_evaluation_by', 'employee_designation_id', 'evaluation_by_id');
    }

    public function evaluationById()
    {
        return $this->belongsToMany('Modules\Employee\Entities\Evaluation', 'employee_designation_evaluation_by', 'employee_designation_id', 'evaluation_by_id')->get()->pluck('id');
    }
}
