<?php

namespace Modules\Admission\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Academics\Entities\Batch;

class ApplicantEnrollment extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'applicant_enrollment';

    // The attribute that should be used for soft delete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'applicant_id',
        'academic_year',
        'academic_level',
        'batch',
        'section'
    ];


    // return enrollment applicant
    public function application()
    {
        return $this->belongsTo('Modules\Admission\Entities\ApplicantUser', 'applicant_id', 'id')->first();
    }

    // return enrollment applicant
    public function applicantPersonalInfo()
    {
        return $this->belongsTo('Modules\Admission\Entities\ApplicantInformation', 'applicant_id', 'applicant_id')->first();
    }



    // return enrollment academic year
    public function academicYear()
    {
        return $this->belongsTo('Modules\Academics\Entities\AcademicsYear', 'academic_year', 'id')->first();
    }

    // return academic level
    public function academicLevel()
    {
        return $this->belongsTo('Modules\Academics\Entities\AcademicsLevel', 'academic_level', 'id')->first();
    }

    // return enrollment batch
    public function batch($batchId=null)
    {
        // checking batch id
        if($batchId){
            return Batch::find($batchId);
        }else{
            return $this->belongsTo('Modules\Academics\Entities\Batch', 'batch', 'id')->first();
        }
    }

    // return enrollment section
    public function section()
    {
        return $this->belongsTo('Modules\Academics\Entities\Section', 'section', 'id')->first();
    }


}