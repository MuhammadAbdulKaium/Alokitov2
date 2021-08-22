<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentAttendanceDetails extends Model
{

    use SoftDeletes;

    // Table name
    protected $table = 'academices_student_attendances_details';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'student_attendace_id',
        'class_id',
        'section_id',
        'subject_id',
        'session_id',
    ];

    public  function section(){
        return  $this->belongsTo('Modules\Academics\Entities\Section', 'section_id', 'id')->first();

    }

    public  function batch(){
        return  $this->belongsTo('Modules\Academics\Entities\Batch', 'class_id', 'id')->first();

    }
}
