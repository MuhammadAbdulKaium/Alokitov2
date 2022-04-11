<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;

class ExamCategory extends Model
{
    protected $table = 'cadet_exam_category';

    protected $fillable = [
        'exam_category_name',
        'alias',
        'campus',
        'institute',
    ];

    public function examNames()
    {
        return $this->hasMany('Modules\Academics\Entities\ExamName', 'exam_category_id', 'id');
    }
}
