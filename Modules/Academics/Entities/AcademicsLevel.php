<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicsLevel extends Model
{

    use SoftDeletes;
    protected $table = 'academics_level';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'level_name', 'level_code', 'academics_year_id', 'is_active',
    ];

    public function academicsYear()
    {

        // getting user
        $academicsYear = $this->belongsTo('Modules\Academics\Entities\AcademicsYear', 'academics_year_id', 'id')->first();

        // var_dump($academicsYear);
        // checking
        if ($academicsYear) {
            // return user info
            return $academicsYear;
        } else {
            // return false
            return false;
        }
    }

    public function academicsLevelAll()
    {
        // getting user info
        $academicsLevelAll = $this->belongsTo('Modules\Academics\Entities\AcademicsYear', 'academics_year_id', 'id')->get();
        // checking
        if ($academicsLevelAll) {
            // return user info
            return $academicsLevelAll;
        } else {
            // return false
            return false;
        }
    }


    public function batch()
    {
        return $this->hasMany('Modules\Academics\Entities\Batch', 'academics_level_id', 'id')->get();
    }

    // get academic level student list
    public function stdList()
    {
        return $this->hasMany('Modules\Student\Entities\StudentProfileView', 'academic_level', 'id')->where([
            'campus' => $this->campus_id,
            'institute' => $this->institute_id,
            'academic_year' => $this->academics_year_id
        ])->get();
    }


    public static function getAcademicLevelById($id)
    {
        $academicLevelProfile = AcademicsLevel::find($id);
        return $academicLevelProfile->level_name;
    }
}
