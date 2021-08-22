<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentGuardian extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'student_guardians';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'user_id',
        'title',
        'type',
        'first_name',
        'last_name',
        'guardian_photo',
        'guardian_signature',
        'bn_fullname',
        'bn_edu_qualification',
        'email',
        'date_of_birth',
        'gender',
        'mobile',
        'phone',
        'relation',
        'income',
        'qualification',
        'occupation',
        'home_address',
        'office_address',
        'remarks',
        'nid_number',
        'nid_file',
        'birth_certificate',
        'birth_file',
        'tin_number',
        'tin_file',
        'passport_number',
        'passport_file',
        'dln',
        'driving_lic_file',
        'institute_info',
        'is_guardian'
    ];

    //returs the user information from the user db table
    public function students()
    {
        return $this->hasMany('Modules\Student\Entities\StudentParent', 'gud_id', 'id')->get();
    }




    //returs the user information from the user db table
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id')->first();
    }

}
