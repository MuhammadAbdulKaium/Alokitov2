<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskSchedule extends Model
{
    use SoftDeletes;

    protected $table = 'cadet_task_schedules';
    protected $guarded = [];



    public function taskScheduleDate()
    {
        return $this->belongsTo(TaskScheduleDate::class, 'task_schedule_date_id', 'id');
    }


    public function activityCategory()
    {
        return $this->belongsTo(StudentActivityDirectoryCategory::class, 'student_activity_category_id', 'id');
    }


    public function activity()
    {
        return $this->belongsTo(StudentActivityDirectoryActivity::class, 'student_activity_id', 'id');
    }
}
