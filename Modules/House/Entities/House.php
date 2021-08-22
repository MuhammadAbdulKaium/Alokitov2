<?php

namespace Modules\House\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\Student\Entities\StudentProfileView;

class House extends Model
{
    protected $table = 'cadet_houses';
    
    protected $guarded = [];

    
    
    public function rooms()
    {
        return $this->hasMany('Modules\House\Entities\Room', 'house_id', 'id');
    }    
    
    public function houseMaster()
    {
        return $this->belongsTo(EmployeeInformation::class, 'employee_id', 'id');
    }

    public function housePrefect()
    {
        return $this->belongsTo(StudentProfileView::class, 'student_id', 'std_id');
    }
}
