<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassTopper extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'class_toppers';

    // The attribute that should be used for soft delete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'gr_no',
        'status',
        'std_id',
        'section',
        'batch',
        'academic_level',
        'academic_year',
        'campus',
        'institute'
    ];

    // return student profile
    public function student()
    {
        // getting student profile
        return $student = $this->belongsTo('Modules\Student\Entities\StudentInformation', 'std_id', 'id');
    }

    // returs single document of the user with attachment type
    public function photo()
    {
        // getting student photo
        $singleAttachment = $this->hasOne('Modules\Student\Entities\StudentAttachment', 'std_id', 'std_id')->where('doc_type', 'PROFILE_PHOTO')->first();
        // checking
        if($singleAttachment){
            // return photo path
            return url('/assets/users/images/'.$singleAttachment->singleContent()->name);
        }else{
            // return default image
            return url('/assets/users/images/user-default.png');
        }
    }

}