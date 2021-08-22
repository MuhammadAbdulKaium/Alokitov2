<?php

namespace Modules\Fee\Entities;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\SoftDeletes;
class FeeAssign extends Model
{

    use Userstamps;
    use SoftDeletes;

    // Table name
    protected $table = 'fee_assign';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'id',
        'institution_id',
        'campus_id',
        'head_id',
        'sub_head_id',
        'class_id',
        'section_id',
        'student_id',
        'funds',
        'status',

    ];

    public function feehead(){
        return $this->belongsTo('Modules\Fee\Entities\FeeHead','head_id','id')->first();
    }

    public function subhead(){
        return $this->belongsTo('Modules\Fee\Entities\FeeSubhead','sub_head_id','id')->first();
    }

    public function batch()
    {
        // getting student attatchment from student attachment db table
        return $this->belongsTo('Modules\Academics\Entities\Batch', 'class_id', 'id')->first();
    }

    // returs enrollment studnet academic section
    public function section()
    {
        // getting student attatchment from student attachment db table
        return $this->belongsTo('Modules\Academics\Entities\Section', 'section_id', 'id')->first();
    }



}
