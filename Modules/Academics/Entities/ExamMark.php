<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Student\Entities\StudentProfileView;

class ExamMark extends Model
{
    use SoftDeletes;
    
    protected $table = 'cadet_exam_marks';

    protected $guarded = [];



    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id', 'id');
    }

    public function Student(){
        return $this->belongsTo(StudentProfileView::class,'student_id','std_id');
    }

    public function examName(){
        return $this->belongsTo(ExamName::class,'exam_id','id');
    }
}
