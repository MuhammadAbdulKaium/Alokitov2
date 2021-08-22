<?php

namespace Modules\House\Entities;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'cadet_rooms';
    
    protected $guarded = [];


    
    public function house()
    {
        return $this->belongsTo('Modules\House\Entities\House', 'house_id', 'id');
    }
}
