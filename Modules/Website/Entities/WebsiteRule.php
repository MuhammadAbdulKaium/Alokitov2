<?php

namespace Modules\Website\Entities;

use Illuminate\Database\Eloquent\Model;

class WebsiteRule extends Model
{
    protected $fillable = [
        'campus_id',
        'institute_id',
        'student_rule',
        'teacher_rule',
        'parent_rule'
    ];
}
