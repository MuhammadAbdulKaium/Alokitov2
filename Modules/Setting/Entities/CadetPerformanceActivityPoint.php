<?php

namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CadetPerformanceActivityPoint extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'id';
    protected $table = 'cadet_performance_activity_point';
    protected $fillable = ['cadet_performance_activity', 'value', 'point'];
}
