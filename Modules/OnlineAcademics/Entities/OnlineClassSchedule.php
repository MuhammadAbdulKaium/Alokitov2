<?php

namespace Modules\OnlineAcademics\Entities;

use Illuminate\Database\Eloquent\Model;

class OnlineClassSchedule extends Model
{
    public         $table         = "online_class_schedule";
    protected     $fillable     = [];



    public function presentStudents()
    {
        return $this->hasMany('Modules\OnlineAcademics\Entities\OnlineAttendance', 'online_class_id', 'id');
    }
}
