<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class AssessmentPassMark extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'assessment_passing_marks';

    // The attribute that should be used for softDelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = ['marks', 'batch', 'campus', 'institute'];
}
