<?php

namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CadetAssessmentActivity extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
    protected $table = 'cadet_assesment_activity';
    protected $fillable = ['assessment_id', 'activity_id','activity_point'];

    public function subjectName(){
        return $this->belongsTo('Modules\Setting\Entities\CadetPerformanceActivity','activity_id','id')->first();
    }


}
