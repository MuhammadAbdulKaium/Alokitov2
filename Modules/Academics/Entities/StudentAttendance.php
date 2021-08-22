<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentAttendance extends Model
{

    use SoftDeletes;

    // Table name
    protected $table = 'academices_student_attendances';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'student_id',
        'attendacnce_type',
        'attendance_date',
        'teacher_id',
        'remarks',
    ];

    // get attendance details
    public function details()
    {
        return $this->hasOne('Modules\Academics\Entities\StudentAttendanceDetails', 'student_attendace_id', 'id')->first();
    }

    // get attend  student

    public function student(){
        return $this->belongsTo('Modules\Student\Entities\StudentInformation', 'student_id', 'id')->first();
    }

}
