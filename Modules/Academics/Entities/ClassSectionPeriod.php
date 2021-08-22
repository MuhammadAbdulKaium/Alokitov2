<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassSectionPeriod extends Model
{
    use SoftDeletes;

    protected $table = 'class_section_period';

    protected $dates = ['deleted_at'];

    protected $fillable = [];


    public function periodCategory()
    {
        return $this->belongsTo('Modules\Academics\Entities\ClassPeriodCategory', 'cs_period_category', 'id')->first();
    }

    // return enrollment academic year
    public function academicYear()
    {
        return $this->belongsTo('Modules\Academics\Entities\AcademicsYear', 'academic_year', 'id')->first();
    }

    // return academic level
    public function academicLevel()
    {
        return $this->belongsTo('Modules\Academics\Entities\AcademicsLevel', 'academic_level', 'id')->first();
    }

    // return enrollment batch
    public function batch()
    {
        return $this->belongsTo('Modules\Academics\Entities\Batch', 'batch', 'id')->first();
    }

    // return enrollment section
    public function section()
    {
        return $this->belongsTo('Modules\Academics\Entities\Section', 'section', 'id')->first();
    }

}
