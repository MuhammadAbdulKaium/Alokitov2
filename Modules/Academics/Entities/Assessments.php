<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assessments extends Model
{

    use SoftDeletes;

    // Table name
    protected $table = 'academics_assessments';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'name',
        'grading_category_id',
        'grade_id',
        'points',
        'passing_points',
        'status',
        'counts_overall_score',
        'applied_to',
    ];

    public function gradeCategory()
    {
        return $this->belongsTo('Modules\Academics\Entities\GradeCategory', 'grading_category_id', 'id')->first();
    }

    public function gradeScale()
    {
        return $this->belongsTo('Modules\Academics\Entities\Grade', 'grade_id', 'id')->first();
    }

}
