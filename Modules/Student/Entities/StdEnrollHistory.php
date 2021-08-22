<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class StdEnrollHistory extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'student_enrollment_history';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'enroll_id',
        'gr_no',
        'section',
        'batch',
        'academic_level',
        'academic_year',
        'enrolled_at',
        'batch_status',
        'remark',
    ];

    // return the enroll profile
    public function enroll()
    {
        // return
        return $this->belongsTo('Modules\Student\Entities\StudentEnrollment', 'enroll_id', 'id')->first();
    }

    // returs enrollment studnet academic year
    public function academicsYear()
    {
        // getting student attatchment from student attachment db table
        return $this->belongsTo('Modules\Academics\Entities\AcademicsYear', 'academic_year', 'id')->first();
    }

    // returs enrollment studnet academic level
    public function level()
    {
        // getting student attatchment from student attachment db table
        return $this->belongsTo('Modules\Academics\Entities\AcademicsLevel', 'academic_level', 'id')->first();
    }

    // returs enrollment studnet academic batch
    public function batch()
    {
        // getting student attatchment from student attachment db table
        return $this->belongsTo('Modules\Academics\Entities\Batch', 'batch', 'id')->first();
    }

    // returs enrollment studnet academic section
    public function section()
    {
        // getting student attatchment from student attachment db table
        return $this->belongsTo('Modules\Academics\Entities\Section', 'section', 'id')->first();
    }

}
