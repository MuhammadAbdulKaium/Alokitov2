<?php

namespace Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeAttendance extends Model
{
    use SoftDeletes;
    // Table name
    protected $table = 'employee_attendance';
    // The attribute that should be used for softdelete.
    protected $fillable = [
      'in_date', 'in_time', 'out_date', 'out_time', 'emp_id', 'company_id', 'brunch_id'
    ];
    protected $dates = ['deleted_at'];

    // return the department profile
    public function name(){
        return $this->belongsTo('Modules\Employee\Entities\EmployeeInformation', 'emp_id', 'id')->first();
    }




}
