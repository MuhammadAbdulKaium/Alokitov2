<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamSeatPlan extends Model
{
    use SoftDeletes;
    
    protected $table = 'cadet_exam_seat_plans';
    protected $guarded = [];
}
