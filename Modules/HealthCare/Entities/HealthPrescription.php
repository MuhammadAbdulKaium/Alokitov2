<?php

namespace Modules\HealthCare\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\Student\Entities\StudentInformation;

class HealthPrescription extends Model
{
    use SoftDeletes;

    protected $table = 'cadet_health_prescriptions';
    protected $guarded = [];



    public function investigations()
    {
        return $this->hasMany(HealthInvestigationReport::class, 'prescription_id', 'id');
    }

    public function drugReports()
    {
        return $this->hasMany(HealthDrug::class, 'prescription_id', 'id');
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
