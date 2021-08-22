<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CadetAssesment extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'id';
    protected $table = 'cadet_assesment';
    protected $fillable = [
        'student_id', 
        'campus_id', 
        'campus_id', 
        'institute_id',
        'academics_year_id',
        'academics_level_id',
        'section_id',
        'batch_id',
        'date',
        'cadet_performance_category_id', 
        'cadet_performance_activity_id',
        'cadet_performance_activity_point_id', 
        'performance_category_id', 
        'total_point',
        'type', 
        'remarks',
        'create_date', 
        'create_by', 
        'update_date', 
        'update_by'
    ];

    public function student()
    {
        // getting student attachment from student attachment db table
        return $student = $this->belongsTo('Modules\Student\Entities\StudentInformation', 'student_id', 'id')->first();
    }

    // return enrollment student academic year
    public function year()
    {
        // getting student attachment from student attachment db table
        return $academicsYear = $this->belongsTo('Modules\Academics\Entities\AcademicsYear', 'academics_year_id', 'id')->first();
    }

    public function lavel()
    {
        // getting student attachment from student attachment db table
        return $academicsLevel = $this->belongsTo('Modules\Academics\Entities\AcademicsLevel', 'academics_level_id', 'id')->first();
    }

    // returs enrollment studnet academic batch
    public function batch()
    {
        // getting student attatchment from student attachment db table
        return $batch = $this->belongsTo('Modules\Academics\Entities\Batch', 'batch_id', 'id')->first();
    }

    // returs enrollment studnet academic section
    public function section()
    {
        // getting student attatchment from student attachment db table
        return $section = $this->belongsTo('Modules\Academics\Entities\Section', 'section_id', 'id')->first();
    }

    public function performance_category()
    {
        // getting student attatchment from student attachment db table
        return $section = $this->belongsTo('Modules\Setting\Entities\CadetPerformanceCategory', 'cadet_performance_category_id', 'id')->first();
    }

    public function activity_point()
    {
        // getting student attatchment from student attachment db table
        return $section = $this->belongsTo('Modules\Setting\Entities\CadetPerformanceActivityPoint', 'cadet_performance_activity_point_id', 'id')->first();
    }

    public function performance_activity()
    {
        // getting student attatchment from student attachment db table
        return $section = $this->belongsTo('Modules\Setting\Entities\CadetPerformanceActivity', 'cadet_performance_activity_id', 'id')->first();
    }

}
