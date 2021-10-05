<?php

namespace Modules\Website\Entities;

use Illuminate\Database\Eloquent\Model;

class WebsiteFormDuration extends Model
{
    protected $fillable = [
        'campus_id',
        'institute_id',
        'starting_date',
        'ending_date',
        'is_active'
    ];
}
