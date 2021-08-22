<?php

namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
    protected $table = 'setting_city';
    protected $fillable = [
        'country_id',' name','state_id'
    ];
    public function singleCountry()
    {

        // getting institute info
        $country = $this->belongsTo('Modules\Setting\Entities\Country', 'country_id', 'id')->first();
        // checking
        if ($country) {
            // return institute info
            return $country;
        } else {
            // return false
            return false;
        }
    }


    // returs all Attachment of the student
    public function allCountries()
    {
        // getting campus from student attachment db table
        $countries= $this->hasMany('Modules\Setting\Entities\Country', 'country_id', 'id')->get();
        // checking
        if ($countries) {
            // return campus attachment
            return $countries;
        } else {
            // return false
            return false;
        }
    }

    public function singleState()
    {

        // getting institute info
        $state = $this->belongsTo('Modules\Setting\Entities\State', 'state_id', 'id')->first();
        // checking
        if ($state) {
            // return institute info
            return $state;
        } else {
            // return false
            return false;
        }
    }


    // returs all Attachment of the student
    public function allStates()
    {
        // getting campus from student attachment db table
        $states= $this->hasMany('Modules\Setting\Entities\State', 'state_id', 'id')->get();
        // checking
        if ($states) {
            // return campus attachment
            return $states;
        } else {
            // return false
            return false;
        }
    }
}
