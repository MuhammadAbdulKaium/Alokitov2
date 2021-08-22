<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassPeriodCategory extends Model
{
    use SoftDeletes;

    protected $table = 'class_period_categories';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'campus',
        'institute',
        'academic_year',
    ];

    public function periods()
    {
        return $this->hasMany('Modules\Academics\Entities\ClassPeriod', 'period_category', 'id')->get();
    }

    // class section
    public function classSections()
    {
        return $this->hasMany('Modules\Academics\Entities\ClassSectionPeriod', 'cs_period_category', 'id')->get();
    }
}
