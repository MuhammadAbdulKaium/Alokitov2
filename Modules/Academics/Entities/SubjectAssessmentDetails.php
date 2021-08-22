<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubjectAssessmentDetails extends Model
{

    use SoftDeletes;

    // Table name
    protected $table = 'subject_assessment_details';

    // The attribute that should be used for softDelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = ['sub_ass_id','sub_id','assessment_marks',];

//    // group subject
//    public function groupSubjects()
//    {
//        return $this->hasMany('Modules\Academics\Entities\SubjectGroupAssign', 'sub_group_id', 'id')->get();
//    }
}
