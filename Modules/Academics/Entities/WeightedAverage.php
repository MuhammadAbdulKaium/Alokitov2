<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WeightedAverage extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'academic_grade_scale_weighted_average';

    // The attribute that should be used for soft Delete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'marks',
        'ass_cat_id',
        'grade_scale_id',
        'level_id',
        'batch_id',
        'campus_id',
        'institute_id',
    ];
}