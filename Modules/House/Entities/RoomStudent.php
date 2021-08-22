<?php

namespace Modules\House\Entities;

use Illuminate\Database\Eloquent\Model;

class RoomStudent extends Model
{
    protected $table = 'cadet_room_students';
    protected $guarded = [];




    public function batch()
    {
        return $this->belongsTo('Modules\Academics\Entities\Batch', 'batch_id', 'id');
    }

    public function section()
    {
        return $this->belongsTo('Modules\Academics\Entities\Section', 'section_id', 'id');
    }

    public function student()
    {
        return $this->belongsTo('Modules\Student\Entities\StudentProfileView', 'student_id', 'std_id');
    }
}
