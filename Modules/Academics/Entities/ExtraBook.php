<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExtraBook extends Model
{

    use SoftDeletes;

    // Table name
    protected $table = 'student_grade_extra_books';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'std_id',
        'extra_marks',
        'semester',
        'section',
        'batch',
        'a_level',
        'a_year',
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
