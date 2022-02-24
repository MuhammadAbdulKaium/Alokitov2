<?php

namespace Modules\Admission\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplicantRelative extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'applicant_id',
        'isGuardian',
        'relation',
        'name',
        'bengaliName',
        'nationality',
        'profession',
        'designation',
        'department',
        'organization',
        'address',
        'referenceContact',
        'totalYearOfWorking',
        'incomeYearly',
        'nidNo',
        'tinNo',
        'passport',
        'birthCertificateNo',
        'drivingLicense',
        'contactAddress',
        'contactPhone',
        'contactEmail',
        'remarks'

    ];
    public function applicantStudent(){
        $this->belongsTo(ApplicantInformation::class);
    }

}
