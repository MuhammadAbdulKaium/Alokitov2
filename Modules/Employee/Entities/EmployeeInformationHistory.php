<?php

namespace Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeInformationHistory extends Model
{
    use SoftDeletes;
    protected $table = 'employee_information_histories';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'user_id','employee_id','operation','value_type','old_value','new_value','institute_id','campus_id','created_by','updated_by'
    ];
}
