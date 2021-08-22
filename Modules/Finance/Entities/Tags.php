<?php

namespace Modules\Finance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;
class Tags extends Model
{
    use Userstamps;
//    use SoftDeletes;

    // Table name
    protected $table = 'finance_tags';

    // The attribute that should be used for softdelete.
//    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'id',
        'account_id',
        'title',
        'color',
        'background',
        'status',
    ];




}
