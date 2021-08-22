<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Academics\Entities\AcademicsLevel;
class StudentEnrollment extends Model
{

    use SoftDeletes;

    // Table name
    protected $table = 'student_enrollments';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'std_id',
        'gr_no',
        'academic_level',
        'batch',
        'section',
        'academic_year',
        'admission_year',
        'enrolled_at',
        'enroll_status',
        'batch_status',
        'remark',
    ];

    // returs enrollment student
    public function student()
    {
        // getting student attatchment from student attachment db table
        return $this->belongsTo('Modules\Student\Entities\StudentInformation', 'std_id', 'id')->first();
    }

    // returs enrollment history
    public function history($type)
    {
        // checking type
        if($type=='IN_PROGRESS'){
            return $this->hasMany('Modules\Student\Entities\StdEnrollHistory', 'enroll_id', 'id')->where(['batch_status'=>'IN_PROGRESS'])->first();
        }elseif ($type=="LEVEL_UP"){
            return $this->hasMany('Modules\Student\Entities\StdEnrollHistory', 'enroll_id', 'id')->where(['batch_status'=>'LEVEL_UP'])->get();
        }elseif ($type=="REPEATED"){
            return $this->hasMany('Modules\Student\Entities\StdEnrollHistory', 'enroll_id', 'id')->where(['batch_status'=>'REPEATED'])->get();
        }elseif ($type=="ENROLL_HISTORY"){
            $academicLevels = AcademicsLevel::where(['academics_year_id'=>session()->get('academic_year'), 'is_active'=>1])->get();
            // response array
            $response = array();
            // academic level looping
            foreach ($academicLevels as $academicLevel){
                $enrollHistory = $this->hasMany('Modules\Student\Entities\StdEnrollHistory', 'enroll_id', 'id')->where(['academic_level'=>$academicLevel->id])->get();
                // checking
                if($enrollHistory->count()>0){
                    $response[] = [
                        'level_id'=> $academicLevel->id,
                        'level_name'=> $academicLevel->level_name,
                        'level_enroll'=> $enrollHistory,
                    ];
                }
            }
            // return response array
            return $response;
        }else{
            return $this->hasMany('Modules\Student\Entities\StdEnrollHistory', 'enroll_id', 'id')->get();
        }
    }

    // returs enrollment studnet academic year
    public function academicsYear()
    {
        // getting student attatchment from student attachment db table
        return $this->belongsTo('Modules\Academics\Entities\AcademicsYear', 'academic_year', 'id')->first();
    }

    // returs enrollment studnet academic level
    public function level()
    {
        // getting student attatchment from student attachment db table
        return $this->belongsTo('Modules\Academics\Entities\AcademicsLevel', 'academic_level', 'id')->first();
    }

    // returs enrollment studnet academic batch
    public function batch()
    {
        // getting student attatchment from student attachment db table
        return $this->belongsTo('Modules\Academics\Entities\Batch', 'batch', 'id')->first();
    }

    // returs enrollment studnet academic section
    public function section()
    {
        // getting student attatchment from student attachment db table
        return $this->belongsTo('Modules\Academics\Entities\Section', 'section', 'id')->first();
    }


    // academic syllabus
    public function academicSyllabus()
    {
        return $this->hasOne('Modules\Academics\Entities\AcademicSyllabus','academic_year','academic_year')->where([
            'academic_level'=>$this->academic_level,
            'batch'=>$this->batch,
            'section'=>$this->section,
            'institute_id'=> session()->get('institute'),
            'campus_id'=> session()->get('campus')
        ])->first();
    }

    public function test(){
        return "test";
    }

    public function admissionYear()
    {
        return $this->belongsTo('Modules\Academics\Entities\AcademicsAdmissionYear', 'admission_year', 'id');
    }

}
