<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;

class AttendanceViewTwo extends Model
{

    // Table name
    protected $table = 'student_attendance_view_two';

    //The attributes that are mass assignable.
    protected $fillable = [
        'att_id',
        'student_id',
        'std_gender',
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

    // gender sorter
    public function genderSorter($gender, $collections)
    {
        return $collections->filter(function($singleProfile) use ($gender)
        {
            return $singleProfile->std_gender == $gender;
        });
    }

    // attendance sorter
    public function attendanceSorter($type, $collections)
    {
        return $collections->filter(function($singleProfile) use ($type)
        {
            return $singleProfile->attendacnce_type == $type;
        });
    }

    // std unique profile
    public function getUniqueStdProfile($collections)
    {
        return $collections->unique('student_id');
    }


}
