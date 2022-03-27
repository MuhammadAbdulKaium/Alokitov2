<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\CadetFees\Entities\CadetFeesAssign;
use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Modules\Student\Entities\StudentProfileView;
use Illuminate\Support\Facades\DB;

class StudentInformation extends Model
{
    use Userstamps;
    use SoftDeletes;
    // Table name
    protected $table = 'student_informations';

    // The attribute that should be used for soft delete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'user_id',
        'punch_id',
        'campus',
        'institute',
        'type',
        'title',
        'first_name',
        'middle_name',
        'last_name',
        'bn_fullname',
        'gender',
        'dob',
        'blood_group',
        'religion',
        'birth_place',
        'email',
        'phone',
        'nationality',
        'passport_no',
        'residency',
        'identification_mark',
        'language',
        'batch_no',
    ];
    public function hobbyDreamIdolAim(){
        return $this->hasMany('Modules\Student\Entities\CadetAssesment', 'student_id', 'id');
    }
    public function singleEnrollment()
    {
        // getting student attatchment from student attachment db table
        return $this->hasOne('Modules\Student\Entities\StudentEnrollment', 'std_id', 'id');
    }
    public function singleUser()
    {
        // getting user info
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
     //return the student nationality 
     public function nationalitys()
     {
         return $this->hasOne('Modules\Setting\Entities\Country', 'id', 'nationality');
     }
    public function nationality()
    {
        return $this->belongsTo('Modules\Setting\Entities\Country', 'nationality', 'id')->first();
    }


    //returs the user information from the user db table
    public function user()
    {
        // getting user info
        return $this->belongsTo('App\User', 'user_id', 'id')->first();
    }

    // returs student allEnroll
    public function allEnrolls()
    {
        // getting student attatchment from student attachment db table
        return $this->hasMany('Modules\Student\Entities\StudentEnrollment', 'std_id', 'id')->get();
    }

    // returs student single enroll
    public function singleEnroll()
    {
        // getting student attatchment from student attachment db table
        return $this->hasOne('Modules\Student\Entities\StudentEnrollment', 'std_id', 'id')->first();
    }

    // returs single document of the user with attachment type
    public function singelAttachment($type)
    {
        // getting student attatchment from student attachment db table
        return $this->hasOne('Modules\Student\Entities\StudentAttachment', 'std_id', 'id')
            ->where('doc_type', $type)->first();
    }

    // returs all Attachment of the student
    public function allAttachment()
    {
        // getting student attatchment from student attachment db table
        return $this->hasMany('Modules\Student\Entities\StudentAttachment', 'std_id', 'id')->get();
    }

    // returs all guardians of the student
    public function allGuardian()
    {
        // getting student attatchment from student attachment db table
        return $this->hasMany('Modules\Student\Entities\StudentGuardian', 'std_id', 'id')->get();
    }
    //    // returs all academics of the student
    //    public function allAcademics()
    //    {
    //        return $this->hasMany('Modules\Student\Entities\StudentEnrollment', 'std_id', 'id')->get();
    //    }

    // returns student active enroll information
    public function enroll()
    {
        return $this->hasOne('Modules\Student\Entities\StudentEnrollment', 'std_id', 'id')->first();
    }

    //all guardian list
    public function myGuardians()
    {
        return $this->hasMany('Modules\Student\Entities\StudentParent', 'std_id', 'id')->orderBy('id', 'desc')->get();
    }

    // single guardian
    public function myGuardian()
    {
        return $this->hasMany('Modules\Student\Entities\StudentParent', 'std_id', 'id')->first();
    }


    // student gender
    public function gender($type)
    {
        $thisInstitute = session()->get('institute');
        $thisCampus = session()->get('campus');

        // total student
        $totalStudent = StudentProfileView::where(['institute' => $thisInstitute, 'campus' => $thisCampus])->count();
        // checking
        if ($type == 'all') {
            // return total count
            return $totalStudent;
        } elseif ($type == 'male_count') {
            return  StudentProfileView::where(['gender' => 'male', 'institute' => $thisInstitute, 'campus' => $thisCampus])->get()->count();
        } elseif ($type == 'female_count') {
            return  StudentProfileView::where(['gender' => 'female', 'institute' => $thisInstitute, 'campus' => $thisCampus])->get()->count();
        } else {
            // count a single type student
            $genderStudent = StudentProfileView::where(['institute' => $thisInstitute, 'campus' => $thisCampus, 'gender' => $type])->get()->count();
            // calculate and return the percentage
            return $percentage = ($genderStudent / $totalStudent) * 100;
        }
    }


    // student waiver
    public function student_waiver()
    {
        // getting student attatchment from student attachment db table
        return $this->hasOne('Modules\Student\Entities\StudentWaiver', 'std_id', 'id')->first();
    }



    public function getWaiverList()
    {
        $date = Carbon::now();
        return $this->hasMany('Modules\Student\Entities\StudentWaiver', 'std_id', 'id')->where([
            'institution_id' => session()->get('institute'),
            'campus_id' => session()->get('campus')
        ])->where('end_date', '>=', $date)
            ->where('start_date', '<=', $date)
            ->where('status', 1)->get();
    }


    // find class topper profile
    public function classTopper()
    {
        // getting student class topper profile
        return $this->hasOne('Modules\Student\Entities\ClassTopper', 'std_id', 'id');
    }


    public function physcialRoomsClub($ids)
    {
        return $this->belongsToMany('Modules\Academics\Entities\PhysicalRoom', 'physical_room_students', 'student_information_id', 'physical_room_id')->with('activities')->whereIn('category_id', $ids)->get();
    }

    public function tuitionFees()
    {
        return $this->belongsTo('Modules\CadetFees\Entities\CadetFeesAssign', 'id', 'std_id');
    }

    public function presentAddress() {
        $addresses = $this->user()->allAddress();
        return $addresses->firstWhere('type', 'STUDENT_PRESENT_ADDRESS');
    }

    public function permanentAddress() {
        $addresses = $this->user()->allAddress();
        return $addresses->firstWhere('type', 'STUDENT_PERMANENT_ADDRESS');
    }
}
