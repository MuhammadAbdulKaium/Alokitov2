<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use SoftDeletes;

    protected $table = 'subject';

    protected $fillable = [
        'subject_name', 'subject_code', 'subject_alias'
    ];


    public function checkSubGroupAssign()
    {
        return $this->hasOne('Modules\Academics\Entities\SubjectGroupAssign', 'sub_id', 'id')->first();
    }

    public function checkSubGroupAssignSingle()
    {
        return $this->hasOne('Modules\Academics\Entities\SubjectGroupAssign', 'sub_id', 'id');
    }
}
