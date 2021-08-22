<?php

namespace Modules\Admission\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplicantAddress extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'applicant_address';

    // The attribute that should be used for soft delete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'applicant_id',
        'type',
        'address',
        'house',
        'street',
        'city_id',
        'state_id',
        'country_id',
        'zip',
        'phone'
    ];

    // return address application
    public function application()
    {
        return $this->belongsTo('Modules\Admission\Entities\ApplicantUser', 'applicant_id', 'id')->first();
    }
    // applicant country
    public function country()
    {
        return $this->belongsTo('Modules\Setting\Entities\Country', 'country_id', 'id')->first();
    }
    // applicant state
    public function state()
    {
        return $this->belongsTo('Modules\Setting\Entities\State', 'state_id', 'id')->first();
    }
    // applicant country
    public function city()
    {
        return $this->belongsTo('Modules\Setting\Entities\City', 'city_id', 'id')->first();
    }

}
