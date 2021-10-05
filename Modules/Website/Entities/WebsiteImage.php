<?php

namespace Modules\Website\Entities;

use Illuminate\Database\Eloquent\Model;

class WebsiteImage extends Model
{
    protected $fillable = [
        'campus_id',
        'institute_id',
        'images',
        'type',
        'name',
        'file'
    ];
}
