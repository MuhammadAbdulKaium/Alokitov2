<?php

namespace Modules\Fee\Entities;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\SoftDeletes;
class WaiverAssign extends Model
{

    use Userstamps;
    use SoftDeletes;

    // Table name
    protected $table = 'fee_waiverassign';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    protected $fillable = [
        'id',
        'institution_id',
        'campus_id',
        'year_id',
        'head_id',
        'class',
        'section',
        'waiver_type',
        'student_id',
        'amount_percentage',
        'amount',
        'date',
        'status',

    ];

    // protected $hidden = [];
    //The attributes that are mass assignable.


    public function feehead(){
        return $this->belongsTo('Modules\Fee\Entities\FeeHead','head_id','id')->first();
    }


    public function batch()
    {
        // getting student attatchment from student attachment db table
        return $this->belongsTo('Modules\Academics\Entities\Batch', 'class', 'id')->first();
    }

    // returs enrollment studnet academic section
    public function section()
    {
        // getting student attatchment from student attachment db table
        return $this->belongsTo('Modules\Academics\Entities\Section', 'section', 'id')->first();
    }

    public function studentProfile(){
        return $this->belongsTo('Modules\Student\Entities\StudentProfileView', 'student_id', 'std_id')->first();

    }

    public function waiver_type(){
        return $this->belongsTo('Modules\Fee\Entities\FeeWaiverType', 'waiver_type', 'id')->first();

    }

}