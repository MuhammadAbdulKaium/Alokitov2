<?php

namespace Modules\Website\Entities;

use Illuminate\Database\Eloquent\Model;

class WebsitePublicExamResult extends Model
{
    protected $fillable = [
        'campus_id',
        'institute_id',
        'year',
        'name',
        'examinee',
        'golden_a',
        'a_plus',
        'pass_percentage'
    ];
}
