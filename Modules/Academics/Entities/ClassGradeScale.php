<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassGradeScale extends Model
{

    use SoftDeletes;

    protected $table = 'academics_class_grade_scales';
    protected $dates = ['deleted_at'];

    protected $fillable = ['scale_id', 'cs_shift','section_id', 'batch_id', 'level_id', 'academic_year_id', 'campus', 'institute'];

    public function batch()
    {
        return $this->belongsTo('Modules\Academics\Entities\Batch', 'batch_id', 'id')->first();
    }

    public function section()
    {
        return $this->belongsTo('Modules\Academics\Entities\Section', 'section_id', 'id')->first();
    }

    // academic grade scale profile
    public function gradeScale()
    {
        return $this->belongsTo('Modules\Academics\Entities\Grade', 'scale_id', 'id')->first();
    }


    // find a batch / Class scale id
    public function getClassGradeScale($batch)
    {
        // find and checking
        if($gradeScale = $this->where(['batch_id' => $batch])->first(['scale_id'])){
            return $gradeScale->scale_id;
        }else{
            return 0;
        }
    }

}
