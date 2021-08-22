<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;

class StudentActivityDirectoryActivity extends Model
{
    protected $table = "student_activity_directory_activities";
    protected $fillable = [
        'id',
        'student_activity_directory_category_id',
        'room_id',
        'activity_name',
        'remarks'
    ];

    public function studentActivityDirectoryCategories()
    {
        // return $this->belongsTo('Modules\Student\Entities\StudentActivityDirectoryCategory');
        return $this->belongsTo('Modules\Student\Entities\StudentActivityDirectoryCategory', 'student_activity_directory_category_id', 'id');
    }



    public function physicalRoom()
    {
        return $this->belongsTo('Modules\Academics\Entities\PhysicalRoom', 'room_id', 'id');
    }
}
