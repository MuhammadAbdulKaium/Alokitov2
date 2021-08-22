<?php

namespace Modules\Fee\Entities;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\SoftDeletes;
class FeeSubhead extends Model
{

    use Userstamps;
    use SoftDeletes;

    // Table name
    protected $table = 'fee_subhead';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    protected $fillable = [
        'id',
        'institution_id',
        'campus_id',
        'head_id',
        'name',
        'class_id',
        'amount',
        'start_date',
        'due_date',
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
        return $this->belongsTo('Modules\Academics\Entities\Batch', 'class_id', 'id')->first();
    }

}
