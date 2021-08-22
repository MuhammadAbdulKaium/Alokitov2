<?php

namespace Modules\Event\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Academics\Entities\Batch;
use Modules\House\Entities\RoomStudent;
use Modules\Student\Entities\StudentProfileView;

class EventTeam extends Model
{
    use SoftDeletes;

    protected $table = 'cadet_event_teams';
    protected $guarded = [];



    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id', 'id');
    }

    public function students()
    {
        $students = null;

        if ($this->house_id) {
            $studentIds = RoomStudent::where('house_id', $this->house_id)->pluck('student_id');
            $students = StudentProfileView::whereIn('std_id', $studentIds)->get();
        } else if ($this->batch_id) {
            $students = StudentProfileView::where('batch', $this->batch_id)->get();
        } else if ($this->section_id) {
            $students = StudentProfileView::where('section', $this->section_id)->get();
        }

        return $students;
    }
}
