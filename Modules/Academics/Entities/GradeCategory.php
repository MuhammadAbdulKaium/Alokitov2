<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GradeCategory extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'academics_grade_categories';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'name', 'is_sba', 'sort_order', 'campus', 'institute'
    ];

    public function checkAssignment($batchId, $sectionId, $semesterId){
        return $this->hasOne('Modules\Academics\Entities\GradeCategoryAssign', 'grade_cat_id', 'id')->where([
            'batch'=>$batchId,
            'section'=>$sectionId,
            'semester'=>$semesterId,
            'institute'=>$this->institute,
            'campus'=>$this->campus,
        ])->first();

    }

// get all assessment under this category
    public function myAssessments()
    {
        return $this->hasMany('Modules\Academics\Entities\Assessments', 'grading_category_id', 'id')->get();
    }

// all grade details
    public function allAssessment($gradeType)
    {
        return $this->hasMany('Modules\Academics\Entities\Assessments', 'grading_category_id', 'id')->where('grade_id', $gradeType)->orderBy('name', 'ASC')->get();
        // return $this->hasMany('Modules\Academics\Entities\Assessments', 'grading_category_id', 'id')->where('grade_id', $gradeType)->orderBy('position', 'ASC')->get();
    }

// all grade details
    public function allAssessmentCounter($gradeType)
    {
        return $details = $this->hasMany('Modules\Academics\Entities\Assessments', 'grading_category_id', 'id')->where('grade_id', $gradeType)->orderBy('name', 'ASC')->get()->count();
    }

    // result count
    public function resultCount($batchId, $sectionId, $semesterId){
        // return
        return $this->hasOne('Modules\Academics\Entities\GradeCategoryAssign', 'grade_cat_id', 'id')->where([
            'batch'=>$batchId, 'section'=>$sectionId, 'semester'=>$semesterId
        ])->first();
    }

}
