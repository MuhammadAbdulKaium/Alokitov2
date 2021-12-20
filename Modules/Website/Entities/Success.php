<?php

namespace Modules\Website\Entities;

use Illuminate\Database\Eloquent\Model;

class Success extends Model
{
    protected $fillable = [
        'campus_id',
        'institute_id',
        'passing_year',
        'total_examine',
        'psc_passing_rate',
        'jsc_passing_rate',
        'ssc_passing_rate',
        'hsc_passing_rate',
    ];

}
