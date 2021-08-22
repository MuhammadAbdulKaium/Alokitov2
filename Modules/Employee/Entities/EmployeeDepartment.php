<?php

namespace Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\SoftDeletes;


class EmployeeDepartment extends Model
{
    use Userstamps;
    use SoftDeletes;

    // Table name
    protected $table = 'employee_departments';

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
        'dept_type',
        'campus_id',
        'institute_id'
    ];

    // return designation list
    public function designations(){
        return $this->hasMany('Modules\Employee\Entities\EmployeeDesignation', 'dept_id', 'id')->get();
    }

    // return stdDepartment list
    public function studentDepartment(){
        return $this->hasOne('Modules\Employee\Entities\StudentDepartment', 'dept_id', 'id')->first();
    }
}
