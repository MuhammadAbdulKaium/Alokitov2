<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentGrade extends Model
{

    use SoftDeletes;

    // Table name
    protected $table = 'student_grades';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'std_id',
        'scale_id',
        'mark_id',
        'class_sub_id',
        'semester',
        'academic_year',
        'section',
        'batch',
        'campus',
        'institute',
    ];

    public function gradeMark()
    {
        return $this->belongsTo('Modules\Academics\Entities\StudentMark', 'mark_id', 'id')->first();
    }

    public function student()
    {
        return $this->belongsTo('Modules\Student\Entities\StudentInformation', 'std_id', 'id')->first();
    }

    public function classSubject()
    {
        return $this->belongsTo('Modules\Academics\Entities\ClassSubject', 'class_sub_id', 'id')->first();
    }


}
