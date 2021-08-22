<?php

namespace Modules\Fee\Entities;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\SoftDeletes;
class SubHeadFund extends Model
{

    use Userstamps;
    use SoftDeletes;

    // Table name
    protected $table = 'subheadfund';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    protected $fillable = [
        'id',
        'institution_id',
        'campus_id',
        'sub_head_id',
        'fund_id',
        'percentage',
        'status',

    ];

    // protected $hidden = [];
    //The attributes that are mass assignable.

}
