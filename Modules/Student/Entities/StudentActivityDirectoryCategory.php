<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;

class StudentActivityDirectoryCategory extends Model
{
    protected $table = 'student_activity_directory_categories';
    protected $fillable = [
        'id',
        'category_name',
        'cadet_hr_fm',
        'remarks'
    ];

    // public function setcadetFmHrAttribute($value)
    // {
    //     $this->attributes['cadet_hr_fm'] = json_encode($value);
    // }
  
    // /**
    //  * Get the categories
    //  *
    //  */
    // public function getcadetFmHrAttribute($value)
    // {
    //     return $this->attributes['cadet_hr_fm'] = json_decode($value);
    // }

    public function studentActivityDirectoryActivities()
    {
        // return $this->hasMany('Modules\Student\Entities\StudentActivityDirectoryActivity');
        return $this->hasMany('Modules\Student\Entities\StudentActivityDirectoryActivity', 'student_activity_directory_category_id', 'id');
    }

    public function userTypes()
    {
        return $this->belongsToMany('Modules\Student\Entities\UserType', 'activity_directory_category_user_type', 'activity_directory_category_id', 'user_type_id');
    }
}
