<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentWaiver extends Model
{

    use SoftDeletes;
    // Table name
    protected $table = 'student_waiver';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'std_id',
        'institution_id',
        'campus_id',
        'type',
        'value',
        'start_date',
        'end_date',
        'status'
    ];



    // returs enrollment student
    public function student()
    {
        // getting student attatchment from student attachment db table
        return $this->belongsTo('Modules\Student\Entities\StudentInformation', 'std_id', 'id')->first();
    }

}
