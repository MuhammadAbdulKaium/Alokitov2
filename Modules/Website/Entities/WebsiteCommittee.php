<?php

namespace Modules\Website\Entities;

use Illuminate\Database\Eloquent\Model;


class WebsiteCommittee extends Model
{

    protected $fillable = [
        'campus_id',
        'institute_id',
        'name',
        'designation',
        'speech',
        'image',
        'email',
        'phone'
    ];
}
