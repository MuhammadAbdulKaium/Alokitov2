<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
class StudentParent extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'student_parents';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'gud_id',
        'std_id',
        'emp_id',
        'is_emergency',
    ];


    //returns the user information from the user db table
    public function guardian()
    {
        return $this->belongsTo('Modules\Student\Entities\StudentGuardian', 'gud_id', 'id')->first();
    }
    public function singleGuardian(){
        return $this->belongsTo('Modules\Student\Entities\StudentGuardian', 'gud_id', 'id');
    }

    public function myStudent()
    {
        return $this->belongsTO('Modules\Student\Entities\StudentInformation', 'std_id', 'id')->first();
    }

    //returns the user information from the user db table
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id')->first();
    }



    public function emergencyContact($std_id){
          $contactProfile = DB::table('student_parents')
            ->select('student_guardians.*')
            ->join('student_guardians', 'student_guardians.id', '=', 'student_parents.gud_id')
            ->where('student_parents.std_id', $std_id)
            ->where('student_parents.is_emergency', 1)
            ->first();

             return ['mobile'=>$contactProfile->mobile,'id'=>$contactProfile->id];
    }


}
