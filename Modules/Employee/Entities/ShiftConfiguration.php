<?php

namespace Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ShiftConfiguration extends Model
{
    use SoftDeletes;

    protected $guarded = [];
}
