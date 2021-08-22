<?php

namespace Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class EvaluationParameter extends Model
{
    use SoftDeletes;

    protected $fillable = ['name'];

    public function evaluations()
    {
        return $this->belongsToMany('Modules\Employee\Entities\Evaluation', 'evaluation_with_parameters', 'evaluation_parameter_id', 'evaluation_id')->withPivot('year','score','created_at','campus_id','institute_id');
    }
}
