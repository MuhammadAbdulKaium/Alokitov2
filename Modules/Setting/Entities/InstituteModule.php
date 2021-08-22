<?php

namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InstituteModule extends Model
{
    use SoftDeletes;
    protected $table    = 'setting_institute_modules';
    protected $dates = ['deleted_at'];

    protected $fillable = ['institute_id', 'module_id', 'status'];
}