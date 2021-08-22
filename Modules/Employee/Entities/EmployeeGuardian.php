<?php

namespace Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeGuardian extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'employee_guardians';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'emp_id',
        'title',
        'first_name',
        'last_name',
        'email',
        'mobile',
        'phone',
        'relation',
        'income',
        'qualification',
        'occupation',
        'home_address',
        'office_address',
    ];

    //returs the user information from the user db table
    public function employee()
    {
        // getting user info
        $employee = $this->belongsTo('Modules\Employee\Entities\EmployeeInformation', 'emp_id', 'id')->first();
        // checking
        if ($employee) {
            // return user info
            return $employee;
        } else {
            // return false
            return false;
        }
    }
}
