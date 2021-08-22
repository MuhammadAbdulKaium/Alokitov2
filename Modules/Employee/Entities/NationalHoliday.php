<?php

namespace Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class NationalHoliday extends Model
{

    use SoftDeletes;

    // Table name
    protected $table = 'national_holidays';

    // The attribute that should be used for soft delete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'remarks',
        'academic_year',
        'campus_id',
        'institute_id'
    ];
}