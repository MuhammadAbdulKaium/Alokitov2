<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{

    protected $table = 'subject';

    protected $fillable = [
        'subject_name','subject_code','subject_alias'
    ];


    public function checkSubGroupAssign()
    {
        return $this->hasOne('Modules\Academics\Entities\SubjectGroupAssign', 'sub_id', 'id')->first();
    }

}
