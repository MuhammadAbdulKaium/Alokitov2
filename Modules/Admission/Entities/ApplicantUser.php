<?php

namespace Modules\Admission\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplicantUser extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'applicant_user';

    // The attribute that should be used for soft delete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    protected $hidden = ['password', 'remember_token'];

    //The attributes that are mass assignable.
    protected $fillable = [
        'email',
        'password',
        'application_no',
        'application_status',
        'campus_id',
        'institute_id',
        'payment_status',
    ];

    // get applicant personalInfo
    public function personalInfo()
    {
        return $this->hasOne('Modules\Admission\Entities\ApplicantInformation', 'applicant_id', 'id')->first();
    }

    // get applicant address
    public function address($addressType)
    {
        // checking address type
        if ($addressType == 'all') {
            return $this->hasMany('Modules\Admission\Entities\ApplicantAddress', 'applicant_id', 'id')->get();
        } else {
            return $this->hasMany('Modules\Admission\Entities\ApplicantAddress', 'applicant_id', 'id')->where(['type' => $addressType])->first();
        }
    }

    // get applicant enrollment
    public function enroll()
    {
        return $this->hasOne('Modules\Admission\Entities\ApplicantEnrollment', 'applicant_id', 'id')->first();
    }

    // get applicant document
    public function document($documentType)
    {
        // applicant documents
        $documents = $this->hasMany('Modules\Admission\Entities\ApplicantDocument', 'applicant_id', 'id');
        // checking address type
        if ($documentType == 'all') {
            return $documents->get();
        } elseif ($documentType == 'doc') {
            return $documents->whereNotIn('doc_type', ['PROFILE_PHOTO'])->get();
        } else {
            return $documents->where(['doc_type' => $documentType])->first();
        }
    }

    // fees details
    public function fees()
    {
        return $this->hasOne('Modules\Admission\Entities\ApplicantFees', 'applicant_id', 'id')->first();
    }

    public function result()
    {
        return $this->hasOne('Modules\Admission\Entities\ApplicantResult', 'applicant_id', 'id')->first();
    }
}
