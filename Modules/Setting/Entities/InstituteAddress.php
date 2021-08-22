<?php

namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InstituteAddress extends Model
{
    use SoftDeletes;

    protected $table = 'institute_addresses';
    protected $dates = ['deleted_at'];

    protected $fillable = ['address','campus_id', 'institute_id', 'city_id', 'state_id', 'country_id'];


    // institute

    public function institute()
    {
        return $this->belongsTo('Modules\Setting\Entities\Institute', 'institute_id', 'id')->first();
    }
}





