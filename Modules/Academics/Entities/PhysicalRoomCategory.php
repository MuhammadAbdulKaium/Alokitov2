<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PhysicalRoomCategory extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'cat_type'];

    
    
    public function rooms()
    {
        return $this->hasMany('Modules\Academics\Entities\PhysicalRoom', 'category_id', 'id')->with('activities')->get();
    }
}
