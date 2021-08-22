<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskScheduleDate extends Model
{
    use SoftDeletes;

    protected $table = 'cadet_task_schedule_date';

    protected $fillable = ['name', 'start_date', 'expected_date'];
}
