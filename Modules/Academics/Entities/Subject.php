<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{

    use SoftDeletes;

    protected $table = 'subject';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'subject_name', 'subject_code', 'subject_alias','academic_year','campus','institute'
    ];

    public function checkSubGroupAssign()
    {
        return $this->hasOne('Modules\Academics\Entities\SubjectGroupAssign', 'sub_id', 'id')->first();
    }

    public function division()
    {
        return $this->belongsTo('Modules\Academics\Entities\Division', 'division_id', 'id');
    }
}
