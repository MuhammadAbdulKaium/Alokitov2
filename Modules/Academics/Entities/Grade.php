<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Academics\Entities\GradeCategory;


class Grade extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'academics_grades';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'name',
        'grade_scale_id',
        'campus',
        'institute'
    ];

    // check grade assign to the class, section and shift
    public function checkGradeAssign($levelId, $batchId)
    {
        return $this->hasMany('Modules\Academics\Entities\ClassGradeScale', 'scale_id', 'id')->where([
            'institute'=>$this->institute,
            'campus'=>$this->campus,
            'level_id'=>$levelId,
            'batch_id'=>$batchId
        ])->first();
    }

    // all grade details
    public function allDetails()
    {
        return $details = $this->hasMany('Modules\Academics\Entities\GradeDetails', 'grade_id', 'id')->get();
    }

    public function assessmentCategory()
    {
        // institute details
        $campus = session()->get('campus');
        $institute = session()->get('institute');
        // return
        return GradeCategory::where(['institute'=>$institute, 'campus'=>$campus])->orderBy('sort_order', 'ASC')->get();
    }

    public function assessmentsCount()
    {
        return $this->hasMany('Modules\Academics\Entities\Assessments', 'grade_id', 'id')->get()->count();
    }

    public function assessments()
    {
        return $this->hasMany('Modules\Academics\Entities\Assessments', 'grade_id', 'id')->get();
    }

    // grade scale assessment category list
    public function assessmentsCategoryList()
    {
        return $this->hasMany('Modules\Academics\Entities\Assessments', 'grade_id', 'id')->distinct()->get(['grading_category_id']);
    }

    //

    public function getAssessmentExtraList()
    {
        return $this->hasMany('Modules\Academics\Entities\Assessments', 'grade_id', 'id')->where(['counts_overall_score'=>1, 'status'=>1])->get();
    }
}
