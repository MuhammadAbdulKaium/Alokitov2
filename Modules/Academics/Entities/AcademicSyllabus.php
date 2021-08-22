<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicSyllabus extends Model
{
    use SoftDeletes;

    protected $table = 'academics_syllabus';
    protected $dates = ['deleted_at'];
    // fill able fields
    protected $fillable = [];
}
