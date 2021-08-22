<?php

namespace Modules\Admission\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplicantInformation extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'applicant_information';

    // The attribute that should be used for soft delete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = ['*'];

    // applicant application
    public function application()
    {
        return $this->belongsTo('Modules\Admission\Entities\ApplicantUser', 'applicant_id', 'id')->first();
    }

    // applicant nationality / country
    public function nationality()
    {
        return $this->belongsTo('Modules\Setting\Entities\Country', 'nationality', 'id')->first();
    }

    // applicant city
    public function preCity()
    {
        return $this->belongsTo('Modules\Setting\Entities\City', 'add_pre_city', 'id')->first();
    }

    // applicant state
    public function preState()
    {
        return $this->belongsTo('Modules\Setting\Entities\State', 'add_pre_state', 'id')->first();
    }


    // applicant city
    public function perCity()
    {
        return $this->belongsTo('Modules\Setting\Entities\City', 'add_per_city', 'id')->first();
    }

    // applicant state
    public function perState()
    {
        return $this->belongsTo('Modules\Setting\Entities\State', 'add_per_state', 'id')->first();
    }
}

