<?php

namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class State extends Model
{
    use SoftDeletes;
    protected $table    = 'setting_state';
    protected $fillable = ['name', 'country_id'];

    public function country()
    {
        // getting institute info
        $city = $this->belongsTo('Modules\Setting\Entities\Country', 'country_id', 'id')->first();
        // checking
        if ($city) {
            // return institute info
            return $city;
        } else {
            // return false
            return false;
        }
    }

    public function allCountry()
    {

        // getting institute info
        $city = $this->belongsTo('Modules\Setting\Entities\Country', 'country_id', 'id')->get();
        // checking
        if ($city) {
            // return institute info
            return $city;
        } else {
            // return false
            return false;
        }
    }

    // all states
    public function allCity()
    {
        // getting state info
        $stateList = $this->hasMany('Modules\Setting\Entities\City', 'state_id', 'id')->get();
        // checking
        if ($stateList) {
            // return state info
            return $stateList;
        } else {
            // return false
            return false;
        }
    }
}
