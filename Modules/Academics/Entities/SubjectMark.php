<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;

class SubjectMark extends Model
{
    protected $table = 'cadet_subject_marks';

    protected $guarded = [];

    public function subject()
    {
        return $this->belongsTo('Modules\Academics\Entities\Subject', 'subject_id', 'id')->with('division');
    }


    public function exam()
    {
        return $this->belongsTo(ExamName::class, 'exam_id', 'id');
    }
}
