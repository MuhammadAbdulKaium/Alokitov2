<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class AcademicsYear extends Model
{
    use SoftDeletes;
    protected $table = 'academics_year';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'year_name', 'start_date', 'end_date', 'status', 'institute_id', 'campus_id'
    ];
    

    // academic level using academic year id
    public function academicsYear()
    {
        // getting user
        return $this->belongsTo('Modules\Academics\Entities\AcademicsYear', 'academics_year_id', 'id')->first();
    }
    // all semesters using this academics year
    public function semesters()
    {
        return $this->hasMany('Modules\Academics\Entities\Semester', 'academic_year_id', 'id')->where(['status'=>1])->get();
    }

    // all semesters using this academics year
    public function batchList()
    {
        return $this->hasMany('Modules\Academics\Entities\Batch', 'academics_year_id', 'id')->get();
    }

    public static function getAcademicYearById($id){
        $academicProfile= AcademicsYear::find($id);
        return $academicProfile->year_name;
    }



}
