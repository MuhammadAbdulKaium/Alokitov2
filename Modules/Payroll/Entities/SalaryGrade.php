<?php

namespace Modules\Payroll\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class SalaryGrade extends Model
{
    use SoftDeletes;

    protected $table = 'pay_salary_grade';
    protected $dates = ['deleted_at'];

    protected $fillable = [];

//    public static function boot()
//    {
//        parent::boot();
//
//        self::creating(function($model){
//            $model->create_by = 1;
//            dd(Auth::id());
//        });
//
//        self::created(function($model){
//            // ... code here
//        });
//
//        self::updating(function($model){
//            // ... code here
//        });
//
//        self::updated(function($model){
//            // ... code here
//        });
//
//        self::deleting(function($model){
//            // ... code here
//        });
//
//        self::deleted(function($model){
//            // ... code here
//        });
//    }
}
