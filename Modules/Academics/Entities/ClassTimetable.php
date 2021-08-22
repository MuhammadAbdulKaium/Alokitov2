<?php
//
//namespace Modules\Academics\Entities;
//
//use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;
//use Modules\Academics\Entities\ClassSubject;
//use  Modules\Employee\Entities\EmployeeInformation;
//
//class ClassTimetable extends Model
//{
//
//    use SoftDeletes;
//
//    protected $table = 'class_timetables';
//
//    protected $dates = ['deleted_at'];
//
//    protected $fillable = [
//        'day',
//        'period',
//        'shift',
//        'room',
//        'batch',
//        'section',
//        'subject',
//        'teacher',
//        'campus',
//        'institute',
//        'academic_year',
//    ];
//
////    public function teacher($teacherId){
////        return EmployeeInformation::findOrFail($teacherId);
////    }
////
////    public function classSubject(){
////        return $this->belongsTo(ClassSubject::class, 'subject', 'id')->first();
////    }
//
//
//    public function teacher($teacherId){
//        return EmployeeInformation::findOrFail($teacherId);
//    }
//
//    public function classSubject($subId){
//        return ClassSubject::findOrFail($subId);
//    }
//
//
//
//}
