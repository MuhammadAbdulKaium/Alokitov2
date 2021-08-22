<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GradeDetails extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'academics_grade_details';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'grade_id',
        'name',
        'min_per',
        'max_per',
        'sorting_order',
    ];
}
