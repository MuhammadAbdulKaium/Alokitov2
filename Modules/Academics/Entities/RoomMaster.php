<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomMaster extends Model
{
    use SoftDeletes;

    protected $table = 'room_master';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'category_id',
        'seat_capacity',
        'location'
    ];

    public function roomCategory()
    {
        // getting user info
        $category = $this->belongsTo('Modules\Academics\Entities\RoomCategory', 'category_id', 'id')->first();
        // checking
        if ($category) {
            // return user info
            return $category;
        } else {
            // return false
            return false;
        }
    }
}
