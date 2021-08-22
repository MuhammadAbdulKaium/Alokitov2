<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Academics\Entities\BatchSemester;
use App\Http\Controllers\Helpers\AcademicHelper;

class Semester extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'academics_year_semesters';

    // The attribute that should be used for soft Delete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'name',
        'academic_year_id',
        'name',
        'start_date',
        'end_date',
        'status',
    ];

    // check semester assignment
    public function checkBatchSemester($levelId, $batchId)
    {
        return $this->hasMany('Modules\Academics\Entities\BatchSemester')->where([
            'academic_year'=>$this->academic_year_id,
            'academic_level'=>$levelId,
            'batch'=>$batchId,
            'semester_id'=>$this->id,
        ])->get();
    }

    public function examNames()
    {
        return $this->hasMany('Modules\Academics\Entities\ExamName', 'term_id', 'id');
    }
}
