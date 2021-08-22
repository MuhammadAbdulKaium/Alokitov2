<?php

namespace Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;

class EvaluationMark extends Model
{
    protected $guarded = [];

    public function employeeDesignation()
    {
        return $this->belongsTo('Modules\Employee\Entities\EmployeeDesignation', 'score_for_designation', 'id');
    }
}
