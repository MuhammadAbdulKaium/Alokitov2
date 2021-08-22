<?php

namespace Modules\Fees\Entities;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeeType extends Model
{

    use Userstamps;
    use SoftDeletes;

    // Table name
    protected $table = 'fee_type';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'id',
        'acc_head_id',
        'institution_id',
        'campus_id',
        'fee_type_name',
        'status'
    ];


    public function  accountingHead() {
        return $this->belongsTo('Modules\Accounting\Entities\AccCharts','acc_head_id','id')->first();

    }

}
