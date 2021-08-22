<?php

namespace Modules\Reports\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IdCardTemplate extends Model
{

    use SoftDeletes;

    // Table name
    protected $table = 'id_card_template_settings';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'temp_id',
        'temp_type',
        'setting',
        'status',
        'campus',
        'institute',
    ];
}
