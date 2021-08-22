<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamStatus extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'exam_status';

    // The attribute that should be used for soft Delete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'status',
        'semester',
        'section',
        'batch',
        'level',
        'academic_year',
        'assessments',
        'campus',
        'institute',
    ];

    // exam summary
    public function examSummary()
    {
        return $this->hasMany('Modules\Academics\Entities\ExamSummary', 'es_id', 'id');
    }

}