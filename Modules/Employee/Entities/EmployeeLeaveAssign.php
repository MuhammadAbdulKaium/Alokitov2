<?php

namespace Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;

class EmployeeLeaveAssign extends Model
{
    protected $table='employee_leave_assign';
    protected $fillable = ['emp_id','dept_id','designation_id','leave_type_id','duration','leave_process_procedure','inst_id','campus_id'];
}
