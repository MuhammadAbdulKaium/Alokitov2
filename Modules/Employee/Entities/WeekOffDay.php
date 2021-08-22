<?php

namespace Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WeekOffDay extends Model
{

    use SoftDeletes;

    // Table name
    protected $table = 'week_off_days';

    // The attribute that should be used for soft delete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'date',
        'dept_id',
        'academic_year',
        'campus_id',
        'institute_id'
    ];

    // return the department profile
    public function department(){
        return $this->belongsTo('Modules\Employee\Entities\EmployeeDepartment', 'dept_id', 'id')->first();
    }
}
