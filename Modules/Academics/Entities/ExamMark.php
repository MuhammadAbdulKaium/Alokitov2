<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamMark extends Model
{
    use SoftDeletes;
    
    protected $table = 'cadet_exam_marks';

    protected $guarded = [];
}
