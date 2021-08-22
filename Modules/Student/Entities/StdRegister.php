<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;

class StdRegister extends Model
{
    // Table name
    protected $table = 'student_registers';
    // timestamps false;
    public $timestamps = false;

    // The attribute that should be used for softdelete.
    // protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    // protected $fillable = [];

}
