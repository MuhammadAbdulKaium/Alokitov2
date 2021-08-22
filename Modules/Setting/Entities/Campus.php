<?php

namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campus extends Model
{
    use SoftDeletes;

    protected $table      = 'setting_campus';
    protected $dates      = ['deleted_at'];
    protected $fillable   = [
        'name', 'campus_code', 'address_id', 'institute_id',
    ];

    public function institute()
    {
        return $this->belongsTo('Modules\Setting\Entities\Institute', 'institute_id', 'id')->first();
    }

    public function student()
    {
        return $this->hasMany('Modules\Student\Entities\StudentInformation', 'campus', 'id')->where(['institute'=>$this->institute_id]);
    }

    public function staff()
    {
        return $this->hasMany('Modules\Employee\Entities\EmployeeInformation', 'campus_id', 'id')->where(['institute_id'=>$this->institute_id]);
    }

    // address
    public function address()
    {
        // getting institute info
        $address = $this->belongsTo('App\Address', 'address_id', 'id')->first();
        // checking
        if ($address) {
            // return institute info
            return $address;
        } else {
            // return false
            return false;
        }
    }

    // address
    public function instAddress()
    {
        // getting institute info
        return $this->hasOne('Modules\Setting\Entities\InstituteAddress', 'campus_id', 'id')->first();
    }

}
