<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;

class StudentsActivitySchedule extends Model
{
    protected $guarded = [];


    public function comments()
    {
        return $this->hasMany('Modules\Student\Entities\StudentsActivityScheduleComment', 'activity_schedule_id', 'id');
    }



    public function activity()
    {
        return $this->belongsTo('Modules\Student\Entities\StudentActivityDirectoryActivity', 'activity_id', 'id');
    }

    
}
