<?php

namespace Modules\HealthCare\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\Inventory\Entities\CadetInventoryProduct;
use Modules\Student\Entities\StudentInformation;

class HealthDrug extends Model
{
    use SoftDeletes;

    protected $table = "cadet_health_drugs";
    protected $guarded = [];



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

    public function drug()
    {
        return $this->belongsTo(CadetInventoryProduct::class, 'product_id', 'id');
    }

    public function prescription()
    {
        return $this->belongsTo(HealthPrescription::class, 'prescription_id', 'id');
    }
}
