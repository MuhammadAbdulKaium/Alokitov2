<?php

namespace Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NationalHolidayDetails extends Model
{

    use SoftDeletes;

    // Table name
    protected $table = 'national_holiday_details';

    // The attribute that should be used for soft delete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'date',
        'holiday_id',
        'academic_year',
        'campus_id',
        'institute_id'
    ];

    // return holiday
    public function holiday()
    {
        return $this->belongsTo('Modules\Employee\Entities\NationalHoliday', 'holiday_id', 'id')->first();
    }
}