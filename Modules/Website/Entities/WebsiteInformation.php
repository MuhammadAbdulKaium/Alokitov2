<?php

namespace Modules\Website\Entities;

use Illuminate\Database\Eloquent\Model;

class WebsiteInformation extends Model
{
    protected $fillable = [
        'campus_id',
        'institute_id',
        'school_name',
        'school_address',
        'school_phone',
        'school_email',
        'school_fb',
        'school_logo',
        'school_contact',
        'school_history',
        'school_mission',
        'school_structure',
        'website_location_google_iframe'
    ];
}
