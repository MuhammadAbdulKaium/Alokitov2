<?php

namespace Modules\Fees\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeesItem extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'fees_item';

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
        'fees_id',
        'item_name',
        'rate',
        'qty',
        'status ',
    ];


    public function items(){
        return $this->belongsTo('Modules\Fees\Entities\Items','item_id','id')->first();
    }


}
