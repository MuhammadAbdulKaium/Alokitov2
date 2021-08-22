<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;

class BatchSemester extends Model
{
    // table name
    protected $table = 'academics_year_semester_assign';
    // table fillable fields
    protected $fillable = ['academic_year', 'academic_level','batch', 'semester_id'];

    // return batch semester
    public function semester(){
        return $this->belongsTo('Modules\Academics\Entities\Semester', 'semester_id', 'id')->where('status', 1)->first();
    }
}