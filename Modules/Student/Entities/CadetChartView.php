<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;

class CadetChartView extends Model
{
    protected $table = 'cadet_chart_view';
    protected $fillable = [
        'student_id',
        'institute_id',
        'campus_id',
        'academics_year_id',
        'academics_level_id',
        'section_id',
        'batch_id',
        'name',
        'date',
        'cadet_performance_category_id',
        'category_name',
        'cadet_performance_activity_id',
        'performance_category_id',
        'group_name',
        'value',
        'total_point',
        'cadet_value'
    ];
}
