<?php

namespace Modules\HealthCare\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\Student\Entities\StudentInformation;

class HealthInvestigationReport extends Model
{
    use SoftDeletes;

    protected $table = 'cadet_health_investigation_reports';
    protected $guarded = [];



    public function investigation()
    {
        return $this->belongsTo(HealthInvestigation::class, 'investigation_id', 'id');
    }

    public function prescription()
    {
        return $this->belongsTo(HealthPrescription::class, 'prescription_id', 'id');
    }

    public function cadet()
    {
        return $this->belongsTo(StudentInformation::class, 'patient_id', 'id');
    }

    public function employee()
    {
        return $this->belongsTo(EmployeeInformation::class, 'patient_id', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
