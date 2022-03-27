<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Modules\Setting\Entities\Institute;

class StudentProfileView extends Model
{
    // Table name
    protected $table = 'student_manage_view';

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'std_id',
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'enroll_id',
        'gr_no',
        'academic_year',
        'academic_level',
        'batch',
        'section',
        'campus',
        'institute',
        'username',
        'bn_fullname',
        'status',
    ];

    public function getStudentAddress(){
        return $this->hasMany('App\Address','user_id','user_id');
    }
    public function singleUser()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
    public function singleStudent()
    {
        // getting student attachment from student attachment db table
        return $this->belongsTo('Modules\Student\Entities\StudentInformation', 'std_id', 'id');
    }
   
    //returns the user information from the user db table
    public function user()
    {
        // getting user info
        return $user = $this->belongsTo('App\User', 'user_id', 'id')->first();

    }

    // returns enrollment student
    public function student()
    {
        // getting student attachment from student attachment db table
        return $student = $this->belongsTo('Modules\Student\Entities\StudentInformation', 'std_id', 'id')->first();
    }

    public function enroll()
    {
        // getting student attachment from student attachment db table
        return $student = $this->belongsTo('Modules\Student\Entities\StudentEnrollment', 'enroll_id', 'id')->first();
    }

    // return enrollment student academic year
    public function year()
    {
        // getting student attachment from student attachment db table
        return $academicsYear = $this->belongsTo('Modules\Academics\Entities\AcademicsYear', 'academic_year', 'id')->first();
    }
    public function academicYear(){
        return $this->belongsTo('Modules\Academics\Entities\AcademicsYear', 'academic_year', 'id');
    }

    // return enrollment studnet academic level
    public function level()
    {
        // getting student attatchment from student attachment db table
        return $level = $this->belongsTo('Modules\Academics\Entities\AcademicsLevel', 'academic_level', 'id')->first();
    }

    public function academicLevel(){
        return $this->belongsTo('Modules\Academics\Entities\AcademicsLevel', 'academic_level', 'id');
    }

    // returs enrollment studnet academic batch
    public function batch()
    {
        // getting student attatchment from student attachment db table
        return $batch = $this->belongsTo('Modules\Academics\Entities\Batch', 'batch', 'id')->first();
    }
    

    public function singleBatch()
    {
        return $this->belongsTo('Modules\Academics\Entities\Batch', 'batch', 'id');
    }

    // returs enrollment studnet academic section
    public function section()
    {
        // getting student attatchment from student attachment db table
        return $section = $this->belongsTo('Modules\Academics\Entities\Section', 'section', 'id')->first();

    }
    public function singleSection()
    {
        // getting student attatchment from student attachment db table
        return $this->belongsTo('Modules\Academics\Entities\Section', 'section', 'id');
    }
   
    public function campus()
    {
        return $this->hasMany('Modules\Setting\Entities\Campus', 'institute', 'id')->orderBy('name', 'ASC')->get();
    }
    // returs single document of the user with attachment type
    public function singelAttachment($type)
    {
        // getting student attatchment from student attachment db table
        return $this->hasOne('Modules\Student\Entities\StudentAttachment', 'std_id', 'std_id')
            ->where('doc_type', $type)->first();
    }


    // returs single document of the user with attachment type
    public function testimonial_result($resultType)
    {
        // getting student attatchment from student attachment db table
        return $section = $this->belongsTo('Modules\Student\Entities\StudentTestimonialResult', 'std_id', 'std_id')->where('result_type',$resultType)->first();
    }


    // student waiver
    public function student_waiver()
    {
        $date = Carbon::now();
        return $this->belongsTo('Modules\Student\Entities\StudentWaiver','std_id','std_id')->where([
            'institution_id'=> session()->get('institute'),
            'campus_id'=> session()->get('campus')
        ])->where('end_date','>=',$date)
            ->where('start_date','<=',$date)
            ->where('status',1)->first();
    }



    // find class topper profile
    public function classTopper(){
        // getting student class topper profile
        return $this->hasOne('Modules\Student\Entities\ClassTopper', 'std_id', 'std_id');
    }

    public function registerStudent()
    {
        // getting student attatchment from student attachment db table
        return $this->belongsTo('Modules\Student\Entities\StdRegister', 'std_id', 'std_id')->first();
    }

    public function studentAddress(){
        return $this->belongsTo('App\Address', 'user_id', 'user_id')->first();

    }

    //
    public function stdAdditionalSubject()
    {
        // getting student attachment from student attachment db table
        return $this->belongsTo('Modules\Academics\Entities\AdditionalSubject', 'std_id', 'std_id')->first();
    }


    public function guardian(){
        $gudIds = $this->hasMany('Modules\Student\Entities\StudentParent', 'std_id', 'std_id')->pluck('gud_id');

        return StudentGuardian::whereIn('id', $gudIds)->where('is_guardian', 1)->first();
    }
}
