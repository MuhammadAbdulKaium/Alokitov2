<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamSummary extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'exam_summary';

    // The attribute that should be used for soft Delete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'es_id',
        'std_id',
        'merit',
        'merit_wa',
        'merit_extra',
        'marks',
        'marks_wa',
        'marks_extra',
        'result',
        'result_wa',
        'result_extra',
        'attendance',
        'comments',
    ];

    // student profile
    public function student()
    {
        return $this->belongsTo('Modules\Student\Entities\StudentInformation', 'std_id', 'id')->first();
    }

}