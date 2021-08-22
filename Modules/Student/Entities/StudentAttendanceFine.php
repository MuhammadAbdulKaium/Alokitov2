<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentAttendanceFine extends Model
{

    use SoftDeletes;
    // Table name
    protected $table = 'student_attendance_fine';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'ins_id',
        'campus_id',
        'academic_year',
        'std_id',
        'date',
        'fine_amount',
    ];



    public function enroll()
    {
        // getting student attatchment from student attachment db table
        return $student = $this->belongsTo('Modules\Student\Entities\StudentEnrollment', 'std_id', 'id')->first();
    }



}
