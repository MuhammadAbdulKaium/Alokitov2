<?php

namespace Modules\OnlineAcademics\Entities;

use Illuminate\Database\Eloquent\Model;

class OnlineAttendance extends Model
{
    protected $table = 'online_attandences';
    protected $guarded = [];

    public function student()
    {
        return $this->belongsTo('Modules\Student\Entities\StudentProfileView', 'std_id', 'std_id');
    }
}
