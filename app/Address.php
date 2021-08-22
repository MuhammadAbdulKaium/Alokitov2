<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'addresses';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'user_id',
        'type',
        'address',
        'house',
        'street',
        'city_id',
        'state_id',
        'zip',
        'country_id',
        'phone',
    ];

    // address city
    public function city()
    {
        // getting City info
        $city = $this->belongsTo('Modules\Setting\Entities\City', 'city_id', 'id')->first();
        // checking
        if ($city) {
            // return City info
            return $city;
        } else {
            // return false
            return false;
        }
    }

    // address state
    public function state()
    {
        // getting state info
        $state = $this->belongsTo('Modules\Setting\Entities\State', 'state_id', 'id')->first();
        // checking
        if ($state) {
            // return state info
            return $state;
        } else {
            // return false
            return false;
        }
    }


    // address country
    public function country()
    {
        // getting country info
        $country = $this->belongsTo('Modules\Setting\Entities\Country', 'country_id', 'id')->first();
        // checking
        if ($country) {
            // return country info
            return $country;
        } else {
            // return false
            return false;
        }
    }

}
