<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubjectTeacher extends Model
{

    use SoftDeletes;

    // Table name
    protected $table = 'class_subject_teachers';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'class_subject_id',
        'employee_id',
        'status',
        'is_active',
    ];

    //return the user information from the user db table
    public function employee()
    {
        // getting user info
       return $this->belongsTo('Modules\Employee\Entities\EmployeeInformation', 'employee_id', 'id')->first();
    }

    public function classSubject()
    {
        return $this->belongsTo('Modules\Academics\Entities\ClassSubject', 'class_subject_id', 'id')->first();
    }
}
