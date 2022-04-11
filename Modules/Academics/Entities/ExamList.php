<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;

class ExamList extends Model
{
    protected $table = 'cadet_exam_lists';
    protected $guarded = [];



    public function year()
    {
        return $this->belongsTo(AcademicsYear::class, 'academic_year_id', 'id');
    }

    public function term()
    {
        return $this->belongsTo(Semester::class, 'term_id', 'id');
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id', 'id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id', 'id');
    }

    public function exam()
    {
        return $this->belongsTo(ExamName::class, 'exam_id', 'id');
    }
}
