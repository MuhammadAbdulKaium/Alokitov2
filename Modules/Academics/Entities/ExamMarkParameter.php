<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;

class ExamMarkParameter extends Model
{
    protected $table = 'cadet_exam_mark_parameters';

    protected $fillable = ['name'];
}
