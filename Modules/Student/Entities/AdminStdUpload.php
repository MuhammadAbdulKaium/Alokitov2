<?php

namespace Modules\Student\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class AdminStdUpload extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'admin_student_uploads';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'name',
        'file_name',
        'u_file_name',
        'mime',
        'status',
        'campus',
        'institute',
    ];
}
