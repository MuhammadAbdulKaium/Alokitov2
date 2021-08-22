<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class ClassTeacherAssign extends Model
{

    use SoftDeletes;

    protected $table = 'class_teacher_assign';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'institute_id',
        'campus_id',
        'teacher_id',
        'batch_id',
        'section_id',
        'status',
    ];

    public function batch()
    {
        return $batch = $this->belongsTo('Modules\Academics\Entities\Batch', 'batch_id', 'id')->first();
    }

    public function section()
    {
        return $section = $this->belongsTo('Modules\Academics\Entities\Section', 'section_id', 'id')->first();
    }

    public function teacher()
    {
        return $section = $this->belongsTo('Modules\Employee\Entities\EmployeeInformation', 'teacher_id', 'id')->first();
    }


}
