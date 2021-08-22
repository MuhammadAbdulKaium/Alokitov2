<?php

namespace Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class HolidayCalenderCategory extends Model
{
    use SoftDeletes;

    protected $fillable = ['name'];
}
