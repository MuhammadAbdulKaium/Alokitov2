<?php

namespace Modules\Admission\Entities;

use Illuminate\Database\Eloquent\Model;

class ApplicantManageView extends Model
{
    // Table name
    protected $table = 'applicant_manage_view';

    //The attributes that are mass assignable.
    protected $fillable = [''];

    // return enrollment applicant
    public function application()
    {
        return $this->belongsTo('Modules\Admission\Entities\ApplicantUser', 'applicant_id', 'id')->first();
    }

    // get applicant document
    public function document($documentType)
    {
        // applicant documents
        $documents = $this->hasMany('Modules\Admission\Entities\ApplicantDocument', 'applicant_id', 'applicant_id');
        // checking address type
        if ($documentType == 'all') {
            return $documents->get();
        } elseif ($documentType == 'doc') {
            return $documents->whereNotIn('doc_type', ['PROFILE_PHOTO'])->get();
        } else {
            return $documents->where(['doc_type' => $documentType])->first();
        }
    }

    // get applicant address
    public function address($addressType)
    {
        // checking address type
        if ($addressType == 'all') {
            return $this->hasMany('Modules\Admission\Entities\ApplicantAddress', 'applicant_id', 'applicant_id')->get();
        } else {
            return $this->hasMany('Modules\Admission\Entities\ApplicantAddress', 'applicant_id', 'applicant_id')->where(['type' => $addressType])->first();
        }
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
    public function institute()
    {
        return $this->belongsTo('Modules\Setting\Entities\Institute', 'institute_id', 'id')->first();
    }

    // return enrollment batch
    public function batch()
    {
        return $this->belongsTo('Modules\Academics\Entities\Batch', 'batch', 'id')->first();
    }

    // return enrollment section
    public function section()
    {
        return $this->belongsTo('Modules\Academics\Entities\Section', 'section', 'id')->first();
    }

    // return admission examDetails
    public function examDetails()
    {
        return $this->hasOne('Modules\Admission\Entities\ApplicantExamSetting', 'institute_id', 'institute_id')->where([
            'academic_year'=>$this->academic_year,
            'academic_level'=>$this->academic_level,
            'batch'=>$this->batch,
            'campus_id'=>$this->campus_id,
        ])->first();
    }

    // fees details
    public function fees()
    {
        return $this->hasOne('Modules\Admission\Entities\ApplicantFees', 'applicant_id', 'applicant_id')->first();
    }

    // exam grades
    public function grade()
    {
        return $this->hasOne('Modules\Admission\Entities\ApplicantGrade', 'applicant_id', 'applicant_id')->first();
    }

}
