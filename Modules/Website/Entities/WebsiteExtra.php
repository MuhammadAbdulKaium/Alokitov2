<?php

namespace Modules\Website\Entities;

use Illuminate\Database\Eloquent\Model;

class WebsiteExtra extends Model
{
    protected $fillable = [
        'campus_id',
        'institute_id',
        'type',
        'curricular_type',
        'name',
        'description',
        'file',
        'file2'
    ];
}
