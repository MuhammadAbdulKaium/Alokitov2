<?php

namespace Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentDepartment extends Model
{

    use SoftDeletes;

    // Table name
    protected $table = 'student_departments';

    // The attribute that should be used for soft delete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'dept_id',
        'academic_level',
        'academic_batch',
        'academic_year',
        'campus_id',
        'institute_id'
    ];

    public function academicLevel()
    {
        return $this->belongsTo('Modules\Academics\Entities\AcademicsLevel', 'academic_level', 'id')->first();
    }

}
