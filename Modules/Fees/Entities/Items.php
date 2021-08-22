<?php

namespace Modules\Fees\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Items extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'items';

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
        'item_name',
        'status',
        'acc_chart_id'
    ];

}
