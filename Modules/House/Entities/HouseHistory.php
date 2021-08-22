<?php

namespace Modules\House\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HouseHistory extends Model
{
    use SoftDeletes;

    protected $table = 'cadet_house_history';
    protected $guarded = [];
}
