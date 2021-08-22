<?php

namespace Modules\Academics\Entities\ManageTimetable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TimeTable extends Model
{

    use SoftDeletes;

    protected $table = 'class_timetables';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'day',
        'period',
        'shift',
        'room',
        'batch',
        'section',
        'subject',
        'teacher',
        'campus',
        'institute',
        'academic_year',
    ];


//    public function classTeacher(){
//        return $this->belongsTo('Modules\Employee\Entities\EmployeeInformation', 'teacher', 'id')->first();
//    }

    // get class subject
    public function classSubject()
    {
        return $this->belongsTo('Modules\Academics\Entities\ClassSubject', 'subject', 'id')->first();
    }

    // get class period
    public function classPeriod()
    {
        return $this->belongsTo('Modules\Academics\Entities\ClassPeriod', 'period', 'id')->first();
    }



}
