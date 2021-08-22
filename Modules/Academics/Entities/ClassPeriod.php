<?php

namespace Modules\Academics\Entities;

use App\Subject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Academics\Entities\ClassSubject;
use  Modules\Employee\Entities\EmployeeInformation;

class ClassPeriod extends Model
{
    use SoftDeletes;

    protected $table = 'class_periods';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'period_name',
        'campus',
        'institute',
        'academic_year',
        'period_category',
        'period_shift',
        'period_start_hour',
        'period_start_min',
        'period_start_meridiem',
        'period_end_hour',
        'period_end_min',
        'period_end_meridiem',
        'is_break',
    ];


    public function teacher($teacherId){
        return EmployeeInformation::findOrFail($teacherId);
    }

    public function subject($subId){
        return ClassSubject::findOrFail($subId);
    }

    public function category()
    {
        return $this->belongsTo('Modules\Academics\Entities\ClassPeriodCategory', 'period_category', 'id')->first();
    }



}
