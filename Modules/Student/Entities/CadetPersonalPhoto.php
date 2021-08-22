<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class CadetPersonalPhoto extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'cadet_personal_photo';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    //The attributes that are mass assignable.
    protected $fillable = [
        'image',
        'cadet_no',
        'date',
        'student_id',
        'campus_id',
        'institute_id',
        'academics_year_id',
        'section_id',
        'batch_id',
    ];

    public function student()
    {
        // getting student attachment from student attachment db table
        return $student = $this->belongsTo('Modules\Student\Entities\StudentInformation', 'student_id', 'id')->first();
    }

    public function section()
    {
        // getting student attatchment from student attachment db table
        return $section = $this->belongsTo('Modules\Academics\Entities\Section', 'section_id', 'id')->first();
    }

    public function year()
    {
        // getting student attachment from student attachment db table
        return $academicsYear = $this->belongsTo('Modules\Academics\Entities\AcademicsYear', 'academics_year_id', 'id')->first();
    }

    public function batch()
    {
        // getting student attatchment from student attachment db table
        $batch = $this->belongsTo('Modules\Academics\Entities\Batch', 'batch_id', 'id')->first();
        if(isset($batch))
        {
            return $batch;
        }
    }
}
