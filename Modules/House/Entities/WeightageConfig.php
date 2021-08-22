<?php

namespace Modules\House\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Academics\Entities\ExamName;
use Modules\Setting\Entities\CadetPerformanceCategory;

class WeightageConfig extends Model
{
    use SoftDeletes;

    protected $table = 'cadet_weightage_config';

    protected $guarded = [];

    
    
    public function exam()
    {
        return $this->belongsTo(ExamName::class, 'exam_id', 'id');
    }

    public function performanceCategory()
    {
        return $this->belongsTo(CadetPerformanceCategory::class, 'performance_cat_id', 'id');
    }
}
