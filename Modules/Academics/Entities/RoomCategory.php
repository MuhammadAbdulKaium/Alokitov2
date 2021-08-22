<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomCategory extends Model
{
    use SoftDeletes;

    protected $table = 'room_category';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'categoryname'
    ];
}
