<?php

namespace Modules\Fee\Entities;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\SoftDeletes;
class SubHeadFine extends Model
{

    use Userstamps;
    use SoftDeletes;

    // Table name
    protected $table = 'subheadfine';

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
        'sub_head_id',
        'class_id',
        'amount_percentage',
        'fine_amount',
        'fine_type',
        'monthy_daily',
        'maximumfine',

    ];

    // protected $hidden = [];
    //The attributes that are mass assignable.

    public function batch()
    {
        // getting student attatchment from student attachment db table
        return $this->belongsTo('Modules\Academics\Entities\Batch', 'class_id', 'id')->first();
    }

    public function feehead(){
        return $this->belongsTo('Modules\Fee\Entities\FeeHead','head_id','id')->first();
    }

    public function subhead(){
        return $this->belongsTo('Modules\Fee\Entities\FeeSubhead','sub_head_id','id')->first();
    }


}
