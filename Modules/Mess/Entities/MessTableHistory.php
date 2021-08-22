<?php

namespace Modules\Mess\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\Student\Entities\StudentInformation;

class MessTableHistory extends Model
{
    protected $table = 'cadet_mess_table_histories';
    protected $guarded = [];


    public function cadet()
    {
        return $this->belongsTo(StudentInformation::class, 'person_id', 'id');
    }

    public function employee()
    {
        return $this->belongsTo(EmployeeInformation::class, 'person_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
