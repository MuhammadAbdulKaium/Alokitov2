<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GradeCategoryAssign extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'academics_grade_category_assign';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'result_count', 'grade_cat_id', 'level', 'batch', 'section','semester', 'campus', 'institute'
    ];
}