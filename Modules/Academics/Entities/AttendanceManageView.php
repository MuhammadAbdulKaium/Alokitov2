<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;

class AttendanceManageView extends Model
{

    // Table name
    protected $table = 'student_attendance_view_two';

    //The attributes that are mass assignable.
    protected $fillable = [
        'att_id',
        'student_id',
        'class_id',
        'section_id',
        'subject_id',
        'session_id',
        'sorting_order',
        'attendacnce_type',
        'attendance_date',
        'deleted_at',
    ];

    public function classSubject()
    {
        return $this->belongsTo('Modules\Academics\Entities\ClassSubject', 'subject_id', 'id')->first();
    }

}
