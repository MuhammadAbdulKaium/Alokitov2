<?php

namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

class IdCardSetting extends Model
{
    use Userstamps;
    use SoftDeletes;

    protected $table = 'student_idcard_setting';
    protected $fillable = [
        'institution_id','campus_id',' template_number','status'
    ];


}

